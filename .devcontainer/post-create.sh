#!/bin/bash
set -e

echo "Setting up Laravel development environment..."

# Navigate to workspace
cd /workspace

# Copy .env.example to .env if .env doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    
    # Update environment variables for devcontainer
    sed -i 's|MEILISEARCH_HOST=.*|MEILISEARCH_HOST=http://localhost:7700|g' .env
    sed -i 's|MEILISEARCH_KEY=.*|MEILISEARCH_KEY=masterKey|g' .env
    sed -i 's|SCOUT_DRIVER=.*|SCOUT_DRIVER=meilisearch|g' .env
    
    # Add Scout and Meilisearch config if not present
    if ! grep -q "SCOUT_DRIVER" .env; then
        echo "" >> .env
        echo "SCOUT_DRIVER=meilisearch" >> .env
    fi
    if ! grep -q "MEILISEARCH_HOST" .env; then
        echo "MEILISEARCH_HOST=http://localhost:7700" >> .env
    fi
    if ! grep -q "MEILISEARCH_KEY" .env; then
        echo "MEILISEARCH_KEY=masterKey" >> .env
    fi
fi

# Create SQLite database if it doesn't exist
if [ ! -f database/database.sqlite ]; then
    echo "Creating SQLite database..."
    touch database/database.sqlite
fi

# Install Composer dependencies
if [ ! -d vendor ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Generate application key if not set
if grep -q "APP_KEY=$" .env || ! grep -q "APP_KEY" .env; then
    echo "Generating application key..."
    php artisan key:generate --ansi
fi

# Install NPM dependencies
if [ ! -d node_modules ]; then
    echo "Installing NPM dependencies..."
    npm install
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force --ansi || true

# Set proper permissions
echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache
chmod 664 database/database.sqlite || true

echo "✅ Development environment setup complete!"
echo ""
echo "Available commands:"
echo "  - composer dev        : Start all services (Laravel, Vite, Meilisearch, Queue)"
echo "  - php artisan serve   : Start Laravel development server"
echo "  - npm run dev         : Start Vite development server"
echo "  - php artisan test    : Run tests"
echo ""
echo "Services:"
echo "  - Laravel:     http://localhost:8000"
echo "  - Vite:        http://localhost:5173"
echo "  - Meilisearch: http://localhost:7700"
