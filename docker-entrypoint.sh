#!/bin/bash
set -e

# Wait for the database to be ready
echo "Waiting for database connection..."
MAX_TRIES=30
TRIES=0

while ! mysql -h db -u laravel -psecret -e "SELECT 1" >/dev/null 2>&1; do
    TRIES=$((TRIES+1))
    if [ $TRIES -ge $MAX_TRIES ]; then
        echo "Could not connect to database after $MAX_TRIES attempts. Exiting."
        exit 1
    fi
    echo "Waiting for database connection... attempt $TRIES/$MAX_TRIES"
    sleep 5
done

echo "Database connection established!"

# Check if Laravel is already installed by checking for key files
if [ ! -f "/var/www/artisan" ]; then
    echo "Creating a new Laravel project..."
    
    # Create a new Laravel project in a temporary directory
    cd /tmp
    laravel new laravel_project
    
    # Copy Laravel files to the /var/www directory
    cp -R /tmp/laravel_project/. /var/www/
    
    # Clean up temporary files
    rm -rf /tmp/laravel_project
    
    # Generate application key
    cd /var/www
    php artisan key:generate
    
    # Copy the migrations and models if they exist
    if [ -d "/var/www/database/migrations" ]; then
        echo "Migrations already exist, skipping copy"
    fi
fi

# Create storage directory structure if it doesn't exist
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/framework/cache
mkdir -p /var/www/storage/logs

# Fix permissions
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache
chown -R www-data:www-data /var/www

cd /var/www
# Run migrations
php artisan migrate --force || echo "Migration failed"

# Run seeders
php artisan db:seed --force || echo "Seeding failed"

echo "Laravel setup complete!"

# Start the original entrypoint command
exec "$@"
