FROM wordpress:7.0-php8.2-apache

# Install WP-CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

# Install required PHP extensions for better performance
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd zip opcache \
    && rm -rf /var/lib/apt/lists/*

# Configure PHP for performance
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'upload_max_filesize=128M'; \
    echo 'post_max_size=128M'; \
    echo 'memory_limit=512M'; \
    echo 'max_execution_time=300'; \
    echo 'max_input_time=300'; \
} > /usr/local/etc/php/conf.d/custom.ini

# Fix MPM conflict and enable Apache modules
RUN a2dismod mpm_event 2>/dev/null; a2enmod mpm_prefork 2>/dev/null; a2enmod rewrite headers expires

# Apache optimization
RUN { \
    echo '<IfModule mod_expires.c>'; \
    echo '  ExpiresActive On'; \
    echo '  ExpiresByType image/jpg "access plus 1 year"'; \
    echo '  ExpiresByType image/jpeg "access plus 1 year"'; \
    echo '  ExpiresByType image/gif "access plus 1 year"'; \
    echo '  ExpiresByType image/png "access plus 1 year"'; \
    echo '  ExpiresByType image/webp "access plus 1 year"'; \
    echo '  ExpiresByType video/mp4 "access plus 1 year"'; \
    echo '  ExpiresByType text/css "access plus 1 month"'; \
    echo '  ExpiresByType application/javascript "access plus 1 month"'; \
    echo '  ExpiresByType application/x-javascript "access plus 1 month"'; \
    echo '</IfModule>'; \
    echo '<IfModule mod_headers.c>'; \
    echo '  Header set X-Content-Type-Options "nosniff"'; \
    echo '  Header set X-Frame-Options "SAMEORIGIN"'; \
    echo '  Header set X-XSS-Protection "1; mode=block"'; \
    echo '</IfModule>'; \
} > /etc/apache2/conf-available/optimization.conf \
    && a2enconf optimization

# Copy scripts
COPY scripts/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY scripts/update-content.php /tmp/update-content.php
COPY scripts/update-content-en.php /tmp/update-content-en.php
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy custom theme child
COPY config/birdnet-child /tmp/birdnet-child

# Copy assets (images and videos from Facebook page)
COPY assets/images /tmp/birdnet-assets/images
COPY assets/videos /tmp/birdnet-assets/videos

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
