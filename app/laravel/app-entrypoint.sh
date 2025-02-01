#!/bin/bash
set -e

# Start Apache in the background
apache2-foreground &

# Wait for Apache to start
sleep 5

# Run Laravel commands
php artisan migrate --force
php artisan mqtt:subscribe &
php artisan websockets:serve &
php artisan queue:work --sleep=2

# Keep the container running
wait