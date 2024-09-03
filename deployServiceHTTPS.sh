###########################################################################################################
# GLOBAL VARIABLES
###########################################################################################################
#!/bin/bash

APPGWNAME=$(az network application-gateway list --resource-group $APPGWRG --query '[0].name' -o tsv)
echo "BACKEND IP - $NGINX_IP_APPGW_BACKEND"
echo "APPGWNAME - $APPGWNAME"
echo "KVCERTNAME - $TLS_KV_CERT_NAME"
echo "APPGW RG - $APPGWRG"
echo "SERVICE APP NAME - $SERVICE_APP_NAME"
echo "SERVICE HOSTNAMES - $SERVICE_HOSTNAMES"
echo "KEYVAULT NAME - $KEYVAULT_NAME"
echo "SPOKEENVIRONMENT - $SPOKEENVIRONMENT"
echo "APPGW_IDENTITY - appgwumsi-$SPOKEENVIRONMENT"


# Split the SERVICE_HOSTNAMES string into an array based on the pipe delimiter
IFS='|' read -ra HOSTNAMES <<< "$SERVICE_HOSTNAMES"

###########################################################################################################
# ADRRESS POOL
###########################################################################################################
echo "Start creating address pool"

BACKENDPOOL="backendpool-$SERVICE_APP_NAME-$SPOKEENVIRONMENT"

az network application-gateway address-pool create \
    --gateway-name $APPGWNAME \
    --resource-group $APPGWRG \
    --name $BACKENDPOOL \
    --servers $NGINX_IP_APPGW_BACKEND

echo "Address pool created"

# ###########################################################################################################
# # BREAK
# ###########################################################################################################

# sleep 30

###########################################################################################################
# CERTIFICATES
###########################################################################################################
echo "Start SSL Certificate creation"

CERTIFICATE_NAME=$TLS_KV_CERT_NAME

TEMP_CERTIFICATE_SECRET_ID=$(az keyvault certificate show \
                                --vault-name $KEYVAULT_NAME \
                                --name $CERTIFICATE_NAME \
                                --query "sid" \
                                --output tsv
                            )

echo "TEMP_CERTIFICATE_SECRET_ID: $TEMP_CERTIFICATE_SECRET_ID"

CERTIFICATE_SECRET_ID=$(echo $TEMP_CERTIFICATE_SECRET_ID | cut -d'/' -f-5)

echo "CERTIFICATE_SECRET_ID: $CERTIFICATE_SECRET_ID"

az network application-gateway ssl-cert create \
    --name $CERTIFICATE_NAME \
    --gateway-name $APPGWNAME \
    --resource-group $APPGWRG \
    --key-vault-secret-id $CERTIFICATE_SECRET_ID

echo "SSL Certificate created"

###########################################################################################################
# CREATE HEALTH PROBE
###########################################################################################################
echo "Start creating health probe"


HEALTH_PROBE_NAME="health-probe-$SERVICE_APP_NAME"

az network application-gateway probe create \
    --gateway-name $APPGWNAME \
    --resource-group $APPGWRG \
    --name $HEALTH_PROBE_NAME \
    --protocol Http \
    --host ${HOSTNAMES[0]} \
    --path "/" \
    --match-status-codes "200-600"

echo "Health Probe created"

###########################################################################################################
# HTTP SETTINGS
###########################################################################################################
echo "Start creating http settings"

SETTINGS_PORT="80"
SETTINGS_PROTOCOL="Http"
SETTINGS_NAME="httpsettingsnginx-$SERVICE_APP_NAME-$SETTINGS_PORT"

az network application-gateway http-settings create \
    --gateway-name $APPGWNAME \
    --resource-group $APPGWRG \
    --name $SETTINGS_NAME \
    --port $SETTINGS_PORT \
    --protocol $SETTINGS_PROTOCOL \
    --host-name ${HOSTNAMES[0]} \
    --probe $HEALTH_PROBE_NAME \
    --timeout 600

echo "Http settings created"

###########################################################################################################
# FRONTEND
###########################################################################################################
FRONTEND_PORT_NAME="port_443"
FRONTEND_PORT="443"

az network application-gateway frontend-port create --gateway-name $APPGWNAME --port $FRONTEND_PORT -n $FRONTEND_PORT_NAME -g $APPGWRG

# Get frontend IP configuration names
FRONTEND_CONFIG_NAME=$(az network application-gateway show \
                        --name $APPGWNAME \
                        --resource-group $APPGWRG \
                        --query 'frontendIPConfigurations[].name' \
                        --output tsv)

# Get frontend port ID for port 443
FRONTEND_PORT443_ID=$(az network application-gateway frontend-port list \
                        --gateway-name $APPGWNAME \
                        --resource-group $APPGWRG \
                        --query '[1].id' \
                        --output tsv)

echo "Frontend config ID: $FRONTEND_CONFIG_NAME"
echo "Frontend port ID: $FRONTEND_PORT443_ID"

###########################################################################################################
# HTTP LISTENER
###########################################################################################################
echo "Start creating http listener"

HTTP_LISTENER_NAME="lsn-$SERVICE_APP_NAME-$SPOKEENVIRONMENT"

az network application-gateway http-listener create \
    --gateway-name $APPGWNAME \
    --resource-group $APPGWRG \
    --frontend-ip $FRONTEND_CONFIG_NAME \
    --frontend-port $FRONTEND_PORT443_ID \
    --name $HTTP_LISTENER_NAME \
    --host-names $(echo "$SERVICE_HOSTNAMES" | tr '|' ' ') \
    --ssl-cert $CERTIFICATE_NAME

echo "Http listener created & updated"

###########################################################################################################
# RULE + PRIORITY CALCULATION
###########################################################################################################
echo "Start creating rule"

RULE_TYPE="Basic"
RULE_NAME="rule-$SERVICE_APP_NAME-$SPOKEENVIRONMENT"

MAX_PRIORITY=$(az network application-gateway rule list \
                --gateway-name $APPGWNAME \
                --resource-group $APPGWRG \
                --query "max([].priority)" \
                --output tsv
                )

# Increment the max priority value by 1
NEXT_PRIORITY=$((MAX_PRIORITY + 1))

echo "Current maximum rule priority: $MAX_PRIORITY"
echo "Next available priority value: $NEXT_PRIORITY"

# Create a rule with incremented priority value
az network application-gateway rule create \
    --gateway-name $APPGWNAME \
    --resource-group $APPGWRG \
    --name $RULE_NAME \
    --http-listener $HTTP_LISTENER_NAME \
    --rule-type $RULE_TYPE \
    --address-pool $BACKENDPOOL \
    --http-settings $SETTINGS_NAME \
    --priority $NEXT_PRIORITY

echo "Rule created"