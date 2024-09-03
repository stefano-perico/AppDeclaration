## Prerequisites for Launching the Project

### Install Azure CLI
Follow the link that corresponds to your installed system:
* **MacOS:** [Install Azure CLI on MacOS](https://learn.microsoft.com/fr-fr/cli/azure/install-azure-cli-macos)
* **Linux:** [Install Azure CLI on Linux](https://learn.microsoft.com/fr-fr/cli/azure/install-azure-cli-linux?pivots=apt)
* **Windows:** [Install Azure CLI on Windows](https://learn.microsoft.com/fr-fr/cli/azure/install-azure-cli-windows?tabs=azure-cli)

### Connection
Run the following commands to connect to Azure Registry:
1. Connect via your Inter Invest account:
    ```sh
    az login
    ```
2. Add an authentication token to Docker to retrieve images:
    ```sh
    az acr login -n interinvestcontainersregistry
    ```

## ⚙️ Installation

1. Install Docker with the Caddy proxy:
    - Project link: [Docker Project](https://dev.azure.com/INTERINVEST/_git/Docker)
    - Once the project is cloned, follow the instructions in the `README.md` to install the proxy.
    - Don't forget to add the domain `127.0.0.1 declaration.local` to `/etc/hosts` to access the project.

2. Create a `.env.local` file at the root of the project and add the following variables:
    - `DOMAIN=declaration.local`
    ```sh
    touch .env.local
    echo "DOMAIN=declaration.local" >> .env.local
    ```

3. Run the following command to install the project:
   This command creates Docker containers and installs the project's Composer dependencies.
    ```sh
    make install
    ```

4. 🚀 The project is now installed and accessible at [https://declaration.local](https://declaration.local)

## 🛠️ Configuration

You have the option to add an image for the database in a `docker-compose.override.yml` file at the root of the project.
A basic configuration exists in the `docker-compose.override.yaml.dist` file.
```sh
cp compose.override.yaml.dist compose.override.yaml
```