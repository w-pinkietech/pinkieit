FROM ghcr.io/w-pinkietech/pinkieit/base-image:latest

# Copy Apache configuration file
COPY docker/app/apache/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copy .env file
COPY .env /var/www/html/.env

# Run npm production
RUN npm run production

# Set permissions for storage and bootstrap/cache directories
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Generate application key
RUN php artisan key:generate --force

# Add startup script
COPY app/laravel/app-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/app-entrypoint.sh

# Change CMD to use the startup script
CMD ["/usr/local/bin/docker-entrypoint.sh"]