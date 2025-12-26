@echo off
chcp 65001 >nul
echo ========================================
echo   Restauration de la base de donnees
echo ========================================
echo.

echo [1/4] Verification de MySQL...
if exist "C:\xampp\mysql\bin\mysql.exe" (
    set MYSQL_PATH=C:\xampp\mysql\bin\mysql.exe
    echo MySQL trouve dans XAMPP
) else (
    echo Recherche de MySQL dans le PATH...
    where mysql >nul 2>&1
    if %errorlevel% equ 0 (
        set MYSQL_PATH=mysql
        echo MySQL trouve dans le PATH
    ) else (
        echo ERREUR: MySQL n'est pas trouve!
        echo Verifiez que XAMPP est installe dans C:\xampp
        pause
        exit /b 1
    )
)
echo.

echo [2/4] Creation de la base de donnees 'hotux'...
%MYSQL_PATH% -u root -e "CREATE DATABASE IF NOT EXISTS hotux CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul
if %errorlevel% neq 0 (
    echo ATTENTION: Probleme lors de la creation de la base de donnees
    echo Verifiez que MySQL est demarre dans XAMPP
    echo.
    set /p CONTINUE="Voulez-vous continuer quand meme? (O/N): "
    if /i not "%CONTINUE%"=="O" exit /b 1
) else (
    echo Base de donnees 'hotux' creee avec succes!
)
echo.

echo [3/4] Verification du fichier backup...
if exist "hotux_backup_utf8.sql" (
    set BACKUP_FILE=hotux_backup_utf8.sql
    echo Fichier backup UTF-8 trouve: hotux_backup_utf8.sql
) else if exist "hotux_backup.sql" (
    set BACKUP_FILE=hotux_backup.sql
    echo Fichier backup trouve: hotux_backup.sql
    echo ATTENTION: Si vous avez des erreurs, utilisez hotux_backup_utf8.sql
) else (
    echo ERREUR: Aucun fichier backup trouve!
    echo Assurez-vous d'etre dans le bon dossier.
    pause
    exit /b 1
)
echo.

echo [4/4] Importation du backup SQL...
echo Cela peut prendre quelques instants...
%MYSQL_PATH% -u root hotux < %BACKUP_FILE%
if %errorlevel% neq 0 (
    echo.
    echo ERREUR lors de l'importation!
    echo.
    echo Solutions possibles:
    echo 1. Verifiez que MySQL est demarre dans XAMPP
    echo 2. Verifiez que la base 'hotux' existe
    echo 3. Importez manuellement via phpMyAdmin
    echo.
) else (
    echo.
    echo ========================================
    echo   Importation terminee avec succes!
    echo ========================================
    echo.
    echo La base de donnees 'hotux' est maintenant prete.
    echo Vous pouvez maintenant lancer l'application.
    echo.
)
echo.

pause

