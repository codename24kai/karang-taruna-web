#!/bin/bash
set -e # Stop script kalau ada error

echo "🚀 Deploying Karang Taruna Web..."

# 1. Masuk Maintenance Mode (biar user gak error pas proses)
(php artisan down) || true

# 2. Pull codingan terbaru dari git
echo "📥 Pulling latest code..."
git pull origin main

# 3. Install/Update Dependency PHP (Composer)
echo "📦 Installing PHP dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# 4. Install/Update Dependency JS/CSS (NPM) & Build Assets
# Hapus bagian ini kalau build assets dilakukan di lokal/CI-CD
echo "🎨 Building frontend assets..."
npm install
npm run build

# 5. Jalankan Migrasi Database (TAPI JANGAN FRESH!)
# Kita pakai --force biar jalan di production tanpa nanya-nanya
echo "🗄️  Migrating database..."
php artisan migrate --force

# 6. Clear & Cache Config/Route/View biar kenceng
echo "🧹 Optimizing..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Keluar Maintenance Mode
php artisan up

echo "✅ Deployment Slay! Website udah live lagi."
