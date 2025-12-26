<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__.'/.env');

// Récupérer la chaîne de connexion et la parser proprement
$databaseUrl = $_ENV['DATABASE_URL'] ?? null;

if (!$databaseUrl) {
    echo "❌ Erreur: DATABASE_URL n'est pas défini dans .env\n";
    exit(1);
}

// Exemple attendu : mysql://root:@127.0.0.1:3306/hotux?serverVersion=mariadb-10.4.0&charset=utf8mb4
$parts = parse_url($databaseUrl);

if ($parts === false || !isset($parts['scheme'], $parts['host'], $parts['path'])) {
    echo "❌ Erreur: DATABASE_URL a un format invalide: $databaseUrl\n";
    exit(1);
}

$user = $parts['user'] ?? 'root';
$password = $parts['pass'] ?? '';
$host = $parts['host'] ?? '127.0.0.1';
$port = $parts['port'] ?? 3306;
$dbname = ltrim($parts['path'], '/');

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Générer un nouveau hash pour admin123
    $newPassword = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Mettre à jour le mot de passe si l'utilisateur existe déjà
    $stmt = $pdo->prepare("UPDATE user SET password = ? WHERE email = 'root@root.com'");
    $stmt->execute([$newPassword]);

    if ($stmt->rowCount() === 0) {
        // Aucun utilisateur mis à jour : on le crée avec le rôle ADMIN
        $roles = json_encode(['ROLE_ADMIN']);
        $insert = $pdo->prepare("INSERT INTO user (email, password, roles, nom, prenom, telephone, image) VALUES ('root@root.com', ?, ?, 'Root', 'Admin', '00000000', NULL)");
        $insert->execute([$newPassword, $roles]);
        echo "✅ Utilisateur admin créé et mot de passe défini avec succès !\n";
    } else {
        echo "✅ Mot de passe admin réinitialisé avec succès !\n";
    }

    echo "Email: root@root.com\n";
    echo "Password: admin123\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}



