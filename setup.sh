#!/bin/bash

echo "🔧 Setting up backend..."

cd ./api || exit

# Install composer dependencies
if ! command -v composer &>/dev/null; then
    echo "❌ Composer not found. Please install Composer first."
    exit 1
fi

composer install

# Copy .env if not exists
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅ .env file created."
else
    echo "ℹ️ .env file already exists."
fi

# Generate Laravel app key
php artisan key:generate

cd ..

echo "🔧 Setting up frontend..."

cd ./client || exit

# Install frontend dependencies
if ! command -v pnpm &>/dev/null; then
    echo "❌ pnpm not found. Please install pnpm first."
    exit 1
fi

pnpm install

cd ..

echo "✅ Setup completed."
