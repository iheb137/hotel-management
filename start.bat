@echo off
echo ========================================
echo   Demarrage du projet Symfony
echo ========================================
echo.

echo [1/3] Verification de PHP...
php --version
if %errorlevel% neq 0 (
    echo ERREUR: PHP n'est pas installe ou pas dans le PATH
    pause
    exit /b 1
)
echo.

echo [2/3] Verification de la base de donnees...
php bin\console doctrine:database:create --if-not-exists
if %errorlevel% neq 0 (
    echo ATTENTION: Probleme de connexion a la base de donnees
    echo Verifiez que MySQL est demarre et que les identifiants dans .env sont corrects
    echo.
)
echo.

:: Importer le backup SQL dans XAMPP (si pas encore fait)
call restore_database.bat

:: Lancer les migrations Doctrine pour aligner le schema
php bin\console doctrine:migrations:migrate --no-interaction

:: Corriger/Completer la base avec ton script
php fix_all_database.php

echo [3/3] Demarrage du serveur Symfony...
echo.
echo Le serveur sera accessible sur: http://localhost:8000
echo Appuyez sur Ctrl+C pour arreter le serveur
echo.

:: Ouvrir automatiquement le navigateur
start "" "http://localhost:8000"

php -S localhost:8000 -t public public/router.php

pause





