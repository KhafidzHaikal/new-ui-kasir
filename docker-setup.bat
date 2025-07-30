@echo off
echo ========================================
echo    Setup Docker Aplikasi Kasir
echo ========================================

echo.
echo 1. Menyalin file environment...
copy .env.docker .env

echo.
echo 2. Building dan menjalankan container...
docker-compose up -d --build

echo.
echo 3. Menunggu database siap...
timeout /t 30

echo.
echo 4. Generate application key...
docker-compose exec app php artisan key:generate --force

echo.
echo 5. Menjalankan migrasi database...
docker-compose exec app php artisan migrate --force

echo.
echo 6. Menjalankan seeder...
docker-compose exec app php artisan db:seed --force

echo.
echo 7. Clear cache...
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear

echo.
echo 8. Set permission...
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache

echo.
echo ========================================
echo           Setup Selesai!
echo ========================================
echo.
echo Aplikasi dapat diakses di:
echo - Aplikasi Utama: http://localhost:8000
echo - phpMyAdmin: http://localhost:8080
echo.
echo Tekan Enter untuk keluar...
pause > nul