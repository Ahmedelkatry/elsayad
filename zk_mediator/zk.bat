@echo off
chcp 65001 >nul
cd /d "%~dp0"
title ZK IP Mediator - Attendance + User Sync
echo ====================================================
echo   ZK IP Mediator - Attendance + User Auto-Sync
echo ====================================================
echo.

set "PHP_BIN="

where php >nul 2>nul
if %ERRORLEVEL% equ 0 (
    set "PHP_BIN=php"
    goto php_found
)
if exist "C:\xampp\php\php.exe" (
    set "PHP_BIN=C:\xampp\php\php.exe"
    goto php_found
)
if exist "C:\wamp64\bin\php\php.exe" (
    for /d %%d in ("C:\wamp64\bin\php\php*") do set "PHP_BIN=%%d\php.exe"
    goto php_found
)
if exist "C:\wamp\bin\php\php.exe" (
    for /d %%d in ("C:\wamp\bin\php\php*") do set "PHP_BIN=%%d\php.exe"
    goto php_found
)
if exist "C:\php\php.exe" (
    set "PHP_BIN=C:\php\php.exe"
    goto php_found
)

echo.
echo [ERROR] PHP was not found on this machine!
echo.
echo Please install one of the following:
echo   1. XAMPP  -> https://www.apachefriends.org/
echo   2. PHP    -> https://windows.php.net/download/
echo.
pause
exit /b 1

:php_found
echo PHP Found: %PHP_BIN%
echo.
echo Starting continuous sync loop (every 60s)...
echo   - Attendance sync  : ON
echo   - Employee sync    : ON (auto add new + auto delete removed employees)
echo Press Ctrl+C to stop.
echo.

"%PHP_BIN%" zk_mediator.php --loop --sync-users
pause