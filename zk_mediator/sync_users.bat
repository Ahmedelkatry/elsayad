@echo off
chcp 65001 >nul
cd /d "%~dp0"
title ZK User Sync - نقل الموظفين الجدد لماكينة البصمة
echo ====================================================
echo   ZK User Sync - نقل الموظفين للماكينة
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
echo Please install XAMPP or PHP standalone.
echo.
pause
exit /b 1

:php_found
echo PHP Found: %PHP_BIN%
echo.
echo اختر نوع المزامنة:
echo.
echo   [1] مزامنة الموظفين (رفع الجدد + حذف المحذوفين من الماكينة)
echo   [2] اعادة رفع جميع الموظفين (Force Sync)
echo   [3] فرض حذف الموظفين المحذوفين من الماكينة (Force Delete)
echo.
set /p CHOICE=اختيارك (1 او 2 او 3): 

if "%CHOICE%"=="2" (
    echo.
    echo [FORCE] اعادة رفع جميع الموظفين للماكينة...
    "%PHP_BIN%" zk_mediator.php --force-sync
) else if "%CHOICE%"=="3" (
    echo.
    echo [FORCE-DELETE] فرض حذف كافة الموظفين المحذوفين من الماكينة...
    "%PHP_BIN%" zk_mediator.php --sync-users --force-delete
) else (
    echo.
    echo [SYNC] مزامنة الموظفين (إضافة الجدد وحذف المحذوفين)...
    "%PHP_BIN%" zk_mediator.php --sync-users
)

echo.
echo ====================================================
echo   انتهت عملية المزامنة. راجع الرسائل اعلاه.
echo ====================================================
pause
