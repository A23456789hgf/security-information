@echo off
echo ==============================================================
echo Laravel Prepare for Deployment (Windows to Ubuntu)
echo ==============================================================
echo.
echo Clearing cached files containing Windows paths...

cd /d "%~dp0"
call php artisan optimize:clear

echo.
echo Done! You can now zip your files and upload them to Ubuntu.
echo Your bootstrap/cache folder is clean, so you won't get a 500 Error.
echo.
pause
