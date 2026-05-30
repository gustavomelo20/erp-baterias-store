#!/bin/bash
set -e

echo "==> Instalando Nginx..."
sudo dnf install -y nginx

echo "==> Criando configuração do Nginx..."
sudo tee /etc/nginx/conf.d/erp-baterias.conf > /dev/null <<'EOF'
server {
    listen 80;
    server_name _;

    # Logs
    access_log /var/log/nginx/erp-baterias.access.log;
    error_log  /var/log/nginx/erp-baterias.error.log;

    # Proxy para o container Docker (php artisan serve na porta 8000)
    location / {
        proxy_pass         http://127.0.0.1:8000;
        proxy_http_version 1.1;
        proxy_set_header   Upgrade           $http_upgrade;
        proxy_set_header   Connection        'upgrade';
        proxy_set_header   Host              $host;
        proxy_set_header   X-Real-IP         $remote_addr;
        proxy_set_header   X-Forwarded-For   $proxy_add_x_forwarded_for;
        proxy_set_header   X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
        proxy_read_timeout 90;
    }
}
EOF

echo "==> Removendo configuração default do Nginx (se existir)..."
sudo rm -f /etc/nginx/conf.d/default.conf

echo "==> Testando configuração do Nginx..."
sudo nginx -t

echo "==> Habilitando e iniciando Nginx..."
sudo systemctl enable nginx
sudo systemctl restart nginx

echo "==> Status do Nginx:"
sudo systemctl status nginx --no-pager

echo ""
echo "==> Nginx configurado com sucesso! Acesse: http://44.223.63.42"
