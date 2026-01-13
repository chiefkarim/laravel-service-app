#!/bin/bash

sudo apt-get update -y
sudo timedatectl set-timezone UTC

# Add Docker's official GPG key:
sudo apt-get install -y ca-certificates curl gnupg
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc

# Add the repository to Apt sources:
sudo tee /etc/apt/sources.list.d/docker.sources <<EOF
Types: deb
URIs: https://download.docker.com/linux/ubuntu
Suites: $(. /etc/os-release && echo "${UBUNTU_CODENAME:-$VERSION_CODENAME}")
Components: stable
Signed-By: /etc/apt/keyrings/docker.asc
EOF

sudo apt-get update -y
# notice that this will install latest versions
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

sudo systemctl enable docker
sudo systemctl start docker
touch /etc/docker/daemon.json
sudo tee /etc/docker/daemon.json >/dev/null <<'EOF'
{
  "log-driver": "json-file",
  "log-opts": {
    "max-size": "10m",
    "max-file": "3"
  }
}
EOF
sudo systemctl restart docker

# change user
USERNAME=user

# Create user if it does not exist
if ! id "$USERNAME" >/dev/null 2>&1; then
    useradd -m -s /bin/bash "$USERNAME"
fi

# Ensure docker group exists
if ! getent group docker >/dev/null; then
    groupadd docker
fi

# Add user to docker group
sudo usermod -aG docker "$USERNAME"

# you can ommit this part if hosting on gcp and allow 443 80 and ssh form the instance creation panel
sudo apt-get install -y ufw
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow OpenSSH
sudo ufw allow 443/tcp
sudo ufw allow 80/tcp
sudo ufw --force enable

sudo apt-get install -y openssh-server
SSHD_CONFIG="/etc/ssh/sshd_config"
sudo cp "$SSHD_CONFIG" "${SSHD_CONFIG}.bak.$(date +%F-%T)"
sudo sed -i 's/^#PermitRootLogin.*/PermitRootLogin no/' "$SSHD_CONFIG"
sudo sed -i 's/^#PasswordAuthentication.*/PasswordAuthentication no/' "$SSHD_CONFIG"
sudo sed -i 's/^#PubkeyAuthentication.*/PubkeyAuthentication yes/' "$SSHD_CONFIG"
sudo systemctl restart ssh

sudo mkdir -p /opt/laravel
sudo mkdir /opt/laravel/storage
sudo mkdir /opt/laravel/logs

# change the user
sudo chown -R $USERNAME:$USERNAME /opt/laravel

sudo touch /opt/laravel/.env
# put your environment variables here
echo 'EXAMPLE="something"' >/opt/laravel/.env
