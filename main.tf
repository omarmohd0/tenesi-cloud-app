terraform {
  required_providers {
    digitalocean = {
      source  = "digitalocean/digitalocean"
      version = "~> 2.0"
    }
  }
}

provider "digitalocean" {
  # Token yako itawekwa hapa au kupitia Environment Variable
}

resource "digitalocean_droplet" "tenesi_server" {
  image  = "ubuntu-22-04-x64"
  name   = "tenesi-production-server"
  region = "fra1"
  size   = "s-1vcpu-1gb"
}

resource "local_file" "ansible_inventory" {
  content  = <<-EOT
    [web_servers]
    ${digitalocean_droplet.tenesi_server.ipv4_address} ansible_user=root
  EOT
  filename = "${path.module}/hosts"
}
