@echo off
chcp 65001 >nul
echo ========================================
echo   Lancement automatique en LOCAL (XAMPP)
echo ========================================
echo.

echo [1/5] Verification de PHP...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERREUR: PHP n'est pas installe ou pas dans le PATH
    pause
    exit /b 1
)
echo OK
echo.

echo [2/5] Verification de MySQL (XAMPP)...
if exist "C:\xampp\mysql\bin\mysql.exe" (
    set MYSQL_PATH=C:\xampp\mysql\bin\mysql.exe
    echo MySQL detecte dans C:\xampp
) else (
    where mysql >nul 2>&1
    if %errorlevel% equ 0 (
        set MYSQL_PATH=mysql
        echo MySQL detecte dans le PATH
    ) else (
        echo ERREUR: MySQL non detecte. Lance XAMPP et demarre MySQL.
        pause
        exit /b 1
    )
)
echo.

echo [3/5] Import du backup (si necessaire)...
call restore_database.bat

echo [4/5] Migrations Doctrine...
php bin\console doctrine:migrations:migrate --no-interaction

echo [5/5] Correction/Completion de la base...
php fix_all_database.php

echo.
echo ========================================
echo   Demarrage de l'application locale
echo ========================================
echo.
call start.bat