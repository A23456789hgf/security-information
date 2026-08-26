#!/bin/bash

# ==========================================================
# Laravel Ubuntu Deployment Script
# Run this script on your Ubuntu server AFTER uploading files
# ==========================================================

echo "🚀 Starting Laravel post-deployment script..."

# 1. Go to the directory where the script is located (project root)
cd "$(dirname "$0")"

# 2. Clear out the uploaded cache files (which contain Windows paths)
echo "🧹 Clearing cached files from Windows..."
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes-v7.php
rm -f bootstrap/cache/packages.php
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/events.php

# 3. Set the correct permissions for the storage and cache directories
echo "🔐 Setting permissions for storage and bootstrap/cache..."
# Adjust the group if your web server user is different (e.g., www-data for Nginx/Apache)
chown -R $USER:www-data storage
chown -R $USER:www-data bootstrap/cache

# Allow the web server to write to these directories
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 3.5. Fix module directory case sensitivity and storage symbolic link
echo "🔄 Fixing module directory case sensitivity for Ubuntu..."
if [ -d "Modules/differences" ]; then
    echo "📁 Renaming Modules/differences to Modules/Differences..."
    mv Modules/differences Modules/Differences_temp
    mv Modules/Differences_temp Modules/Differences
fi

echo "🔗 Creating fresh storage symbolic link..."
if [ -L "public/storage" ] || [ -d "public/storage" ]; then
    echo "🧹 Removing existing/broken storage symlink..."
    rm -rf public/storage
fi
php artisan storage:link

# 4. Run Laravel optimization commands to generate fresh cache for Ubuntu
echo "⚡ Generating fresh cache for Ubuntu..."
php artisan optimize:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment script completed successfully! The 500 Error should now be resolved."
