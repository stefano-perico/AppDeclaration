###########################################################################################################
# GLOBAL VARIABLES
###########################################################################################################

# Split the SERVICE_HOSTNAMES string into an array based on the pipe delimiter
IFS='|' read -ra RECORD_HOSTNAMES <<< "$SERVICE_HOSTNAMES"

APPGWNAME=$(az network application-gateway list --resource-group $APPGWRG --query '[0].name' -o tsv)
#retrieve application gateway public ip address
APPGWNAME=$(az network application-gateway list --resource-group $APPGWRG --query '[0].name' -o tsv)
APPGW_PUBLIC_IP_ID=$(az network application-gateway show --name $APPGWNAME --resource-group $APPGWRG --query "frontendIPConfigurations[0].publicIPAddress.id" --output tsv)
APPGW_PUBLIC_IP=$(az network public-ip show --ids $APPGW_PUBLIC_IP_ID --query "ipAddress" --output tsv)
echo "APP GW PUBLIC IP FOR A RECORD '$APPGWNAME' is: $APPGW_PUBLIC_IP"
#retrieve cloudflare token from hub keyvault
CLOUDFLARE_TOKEN=$(az keyvault secret show --name "cloudflare-token" --vault-name "$HUB_KV" --query value -o tsv)
# Cloudflare zone is the zone which holds the record
DNS_ZONE=$DNS_ZONE

echo "HUB KV - $HUB_KV"
echo "DNS ZONE - $DNS_ZONE"
echo "APPGW RG - $APPGWRG"
echo $KC_HOSTNAMES
for RECORD_HOSTNAME in "${RECORD_HOSTNAMES[@]}"; do

    echo "DNS CONFIGURATION FOR: $RECORD_HOSTNAME"
    DNS_RECORD=$RECORD_HOSTNAME


    ZONE_ID=$(curl -s -X GET "https://api.cloudflare.com/client/v4/zones?name=$DNS_ZONE&status=active" \
            -H "Authorization: Bearer $CLOUDFLARE_TOKEN" \
            -H "Content-Type: application/json" | jq -r '{"result"}[] | .[0] | .id')

    echo "Zoneid for $DNS_ZONE is $ZONE_ID"


    # List DNS records for the specified zone and filter by the desired record name
    RESPONSE=$(curl -s -X GET "https://api.cloudflare.com/client/v4/zones/$ZONE_ID/dns_records?name=$DNS_RECORD" \
      -H "Authorization: Bearer $CLOUDFLARE_TOKEN" \
      -H "Content-Type: application/json")

    # Check if the desired DNS record exists
    RECORD_EXISTS=$(echo $RESPONSE | jq '.result | length > 0')

    if [ "$RECORD_EXISTS" == "true" ]; then
      echo "The DNS record '$DNS_RECORD' exists in the zone with ID '$ZONE_ID'."
      # get the dns record id
        DNS_RECORD_ID=$(curl -s -X GET "https://api.cloudflare.com/client/v4/zones/$ZONE_ID/dns_records?type=A&name=$DNS_RECORD" \
            -H "Authorization: Bearer $CLOUDFLARE_TOKEN" \
            -H "Content-Type: application/json" | jq -r '{"result"}[] | .[0] | .id')
    ##implement UPDATE OF IP NEW SPOKE..
        echo "DNSrecordid for $DNS_RECORD is $DNS_RECORD_ID"
        # update the record only if a change is needed otherwise, return record already exists
        RESPONSE=$(curl -s -X PUT "https://api.cloudflare.com/client/v4/zones/$ZONE_ID/dns_records/$DNS_RECORD_ID" \
            -H "Authorization: Bearer $CLOUDFLARE_TOKEN" \
            -H "Content-Type: application/json" \
            --data "{\"type\":\"A\",\"name\":\"$DNS_RECORD\",\"content\":\"$APPGW_PUBLIC_IP\",\"ttl\":1,\"proxied\":false,\"comment\":\"Updated by azdo\"}" | jq )
        # Check if the update was successful
        SUCCESS=$(echo $RESPONSE | jq -r '.success')
        if [ "$SUCCESS" == "true" ]; then
            echo "DNS record updated successfully."
        else
            echo "Failed to update DNS record."
            echo $RESPONSE
            #exit 1
        fi
    else
      echo "The DNS record '$DNS_RECORD' does not exist in the zone with ID '$ZONE_ID'."

        # Create a new DNS record
        DNS_RECORD_CREATION=$(curl -s -X POST "https://api.cloudflare.com/client/v4/zones/$ZONE_ID/dns_records" \
            -H "Authorization: Bearer $CLOUDFLARE_TOKEN" \
            -H "Content-Type: application/json" \
            --data "{\"type\":\"A\",\"name\":\"$DNS_RECORD\",\"content\":\"$APPGW_PUBLIC_IP\",\"ttl\":60,\"proxied\":false,\"comment\":\"Updated by azdo\"}" | jq)
        SUCCESS=$(echo $DNS_RECORD_CREATION | jq -r '.success')
        if [ "$SUCCESS" == "true" ]; then
            echo "DNS record created successfully."
        else
            echo "Failed to create DNS record."
            echo $RESPONSE
            #exit 1
        fi

    fi

done