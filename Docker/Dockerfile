FROM php:8.2-apache  

# Mise à jour et installation des dépendances en une seule commande
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    nodejs \
    npm && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Installation de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Activation de mod_rewrite pour Apache
RUN a2enmod rewrite

# Configuration Apache pour pointer vers le dossier public de Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Augmentation des limites de taille pour les uploads
RUN { \
    echo 'upload_max_filesize = 50M'; \
    echo 'post_max_size = 50M'; \
    echo 'max_execution_time = 300'; \
    echo 'max_input_time = 300'; \
} > /usr/local/etc/php/conf.d/uploads.ini

# Définition du répertoire de travail
WORKDIR /var/www/html

# Lancement d'Apache
CMD ["apache2-foreground"]