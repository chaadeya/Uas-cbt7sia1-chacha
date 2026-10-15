@echo off
php artisan optimize:clear
php artisan filament:optimize-clear
echo Cache cleared successfully!
pause
