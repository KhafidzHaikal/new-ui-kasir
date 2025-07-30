@echo off
echo ========================================
echo    Stop Docker Aplikasi Kasir
echo ========================================

echo.
echo Menghentikan semua container...
docker-compose down

echo.
echo Container berhasil dihentikan!
echo.
echo Tekan Enter untuk keluar...
pause > nul