@echo off
echo Clearing Laravel Cache...

echo.
echo 1. Clearing view cache...
php artisan view:clear

echo.
echo 2. Clearing config cache...
php artisan config:clear

echo.
echo 3. Clearing route cache...
php artisan route:clear

echo.
echo 4. Clearing application cache...
php artisan cache:clear

echo.
echo 5. Clearing compiled views...
php artisan view:clear

echo.
echo ✅ All caches cleared successfully!
echo.
echo Now try accessing: http://localhost:8000/jasa
echo.
pause
