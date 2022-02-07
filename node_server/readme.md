# socket-server

## Requirements

- NodeJS 12.18.3
- Supervisor / SystemD / PM2 or other service management system

## Setup

1. Upload project into application root directory.
2. Install project requirements using NPM (`npm install` command).
3. Create .env file, based on existing .env.example file in application root directory.
4. Run express-server using you preffered service management system and proxy requests from nginx to express (`npm start` command)

## Configurations

All required configurations may be set into .env file

## Nginx and upstream express-server:

The following directive in your site configuration will upstream express-server:

```
upstream socket-bloggers {
    ip_hash;
    server 127.0.0.1:8010 weight=5;
}

server {
    location / {
        proxy_pass http://socket-bloggers;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
        add_header  Front-End-Https   on;
    }

    listen 1111 ssl;
    ssl_certificate /path/to/fullchain.pem;
    ssl_certificate_key /path/to/privkey.pem;
    include /path/to/options-ssl-nginx.conf;
    ssl_dhparam /path/to/ssl-dhparams.pem;
}
```
