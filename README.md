Ce fichier README a été généré le 2025/09/11 par [halilxdev](https://github.com/halilxdev/)

Dernière mise-à-jour le : 2025/09/11.

# TaskLinker — Gestion de projets en entreprise















# Liste de choses à faire pour créer un container

fichier : docker-compose.yaml
```yaml
    services:
    # Service Nginx - Serveur web
    nginx:
        image: nginx:alpine
        container_name: symfony_nginx
        restart: unless-stopped
        ports:
        - "8000:80"      # Port pour accéder à l'application
        volumes:
        - ./app:/var/www/symfony           # Code de l'application
        - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
        depends_on:
        - php
        networks:
        - symfony-network

    # Service PHP - Application Symfony
    php:
        build:
        context: .
        dockerfile: docker/php/Dockerfile
        container_name: symfony_php
        restart: unless-stopped
        volumes:
        - ./app:/var/www/symfony           # Partage du code avec le container
        networks:
        - symfony-network
        depends_on:
        - database

    # Service MySQL - Base de données
    database:
        image: mysql:8.0
        platform: linux/amd64  # ← Ajoutez cette ligne aussi
        container_name: symfony_mysql
        restart: unless-stopped
        environment:
        MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
        MYSQL_DATABASE: ${DB_NAME}
        MYSQL_USER: ${DB_USER}
        MYSQL_PASSWORD: ${DB_PASSWORD}
        volumes:
        - db_data:/var/lib/mysql                    # Persistance des données
        # - ./docker/mysql/my.cnf:/etc/mysql/conf.d/custom.cnf  # Configuration personnalisée
        ports:
        - "3306:3306"  # Port pour connexion directe si besoin
        networks:
        - symfony-network

    # Service PHPMyAdmin - Interface de gestion de base de données
    phpmyadmin:
        image: phpmyadmin/phpmyadmin:latest
        platform: linux/amd64  # ← Ajoutez cette ligne
        container_name: symfony_phpmyadmin
        restart: unless-stopped
        environment:
        PMA_HOST: database                 # Nom du service MySQL
        PMA_PORT: 3306
        PMA_USER: root
        PMA_PASSWORD: ${DB_ROOT_PASSWORD}
        UPLOAD_LIMIT: 1G                   # Limite pour l'import de fichiers
        ports:
        - "8080:80"    # Port pour accéder à PHPMyAdmin
        depends_on:
        - database
        networks:
        - symfony-network

    # Réseau personnalisé pour isoler les services
    networks:
    symfony-network:
        driver: bridge

    # Volume pour la persistance des données MySQL
    volumes:
    db_data:
        driver: local
```

ficher : .env
```bash
    DB_ROOT_PASSWORD=motdepasse_root_securise
    DB_NAME=moselpro
    DB_USER=symfony_user
    DB_PASSWORD=motdepasse_user_securise
    APP_ENV=dev
```

fichier : docker/mysql.my.cnf
```bash
    [mysqld]
    innodb_buffer_pool_size = 256M
    innodb_log_file_size = 64M
    innodb_flush_log_at_trx_commit = 2
    innodb_flush_method = O_DIRECT
    character-set-server = utf8mb4
    collation-server = utf8mb4_unicode_ci
    query_cache_type = 1
    query_cache_size = 128M
```

fichier : docker/nginx/default.conf
```conf
    server {
        listen 80;
        server_name localhost;
        root /var/www/symfony/public;
        index index.php index.html;

        # Configuration pour Symfony
        location / {
            try_files $uri $uri/ /index.php$is_args$args;
        }

        # Gestion des fichiers PHP
        location ~ ^/index\.php(/|$) {
            fastcgi_pass php:9000;
            fastcgi_split_path_info ^(.+\.php)(/.*)$;
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
            fastcgi_param DOCUMENT_ROOT $realpath_root;
            fastcgi_buffer_size 128k;
            fastcgi_buffers 4 256k;
            fastcgi_busy_buffers_size 256k;
            internal;
        }

        # Bloquer l'accès aux autres fichiers PHP
        location ~ \.php$ {
            return 404;
        }

        # Optimisation des assets statiques
        location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
            expires 1y;
            add_header Cache-Control "public, immutable";
        }
    }
```

ficher : docker/php/Dockerfile
```bash
    FROM php:8.4-fpm

    # Installation des dépendances système
    RUN apt-get update && apt-get install -y \
        git \
        curl \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
        libicu-dev \
        libzip-dev \
        --no-install-recommends \
        && rm -rf /var/lib/apt/lists/*

    # Installation des extensions PHP requises pour Symfony 7
    RUN docker-php-ext-configure intl \
        && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
        opcache

    # Installation de Composer
    COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

    # Configuration du répertoire de travail
    WORKDIR /var/www/symfony

    # Configuration des permissions
    RUN useradd -m symfony && chown -R symfony:symfony /var/www/symfony
    USER symfony

    # Port exposé pour PHP-FPM
    EXPOSE 9000
```

# Création d'un projet Symfony
```bash
    symfony new app --version="7.3.x" --webapp
```