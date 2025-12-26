<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__.'/.env');

$databaseUrl = $_ENV['DATABASE_URL'];
// Extraire les paramètres de la DATABASE_URL
preg_match('/mysql:\/\/([^:]+):([^@]+)@([^:]+):(\d+)\/([^?]+)/', $databaseUrl, $matches);
$user = $matches[1];
$password = $matches[2];
$host = $matches[3];
$port = $matches[4];
$dbname = $matches[5];

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion à la base de données réussie\n";
    
    // 1. Corriger la table commentaire (ajouter date si manquante)
    echo "\n[1/6] Vérification de la table commentaire...\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM commentaire LIKE 'date'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE commentaire ADD COLUMN date DATETIME NULL");
        echo "  ✓ Colonne 'date' ajoutée à commentaire\n";
    } else {
        echo "  ✓ Colonne 'date' existe déjà\n";
    }
    
    // Rendre room_id et event_id nullable
    $pdo->exec("ALTER TABLE commentaire MODIFY COLUMN room_id INT NULL");
    $pdo->exec("ALTER TABLE commentaire MODIFY COLUMN event_id INT NULL");
    echo "  ✓ Colonnes room_id et event_id rendues nullable\n";
    
    // 2. Vérifier et corriger la table reservation
    echo "\n[2/6] Vérification de la table reservation...\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM reservation");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('start_date', $columns) && !in_array('StartDate', $columns)) {
        $pdo->exec("ALTER TABLE reservation ADD COLUMN start_date DATE NOT NULL AFTER prix");
        echo "  ✓ Colonne 'start_date' ajoutée\n";
    }
    if (!in_array('end_date', $columns) && !in_array('EndDate', $columns)) {
        $pdo->exec("ALTER TABLE reservation ADD COLUMN end_date DATE NOT NULL AFTER start_date");
        echo "  ✓ Colonne 'end_date' ajoutée\n";
    }
    
    // 3. Ajouter des événements avec images
    echo "\n[3/6] Ajout d'événements...\n";
    $events = [
        [
            'name' => 'Concert Jazz en Terrasse',
            'prix' => 50.0,
            'date' => '2025-12-15',
            'description' => 'Profitez d\'une soirée jazz exceptionnelle sur notre terrasse avec vue panoramique. Cocktails et tapas inclus.',
            'thumbnail' => '/uploads/images/eventi-social-events-2-d8afeca9-6769d24186ede.jpg'
        ],
        [
            'name' => 'Soirée Gastronomique',
            'prix' => 120.0,
            'date' => '2025-12-20',
            'description' => 'Découvrez notre menu dégustation préparé par notre chef étoilé. 7 services avec accords mets et vins.',
            'thumbnail' => '/uploads/images/Hotel-Events-6769de00b14a9.jpg'
        ],
        [
            'name' => 'Séance Yoga Matinale',
            'prix' => 25.0,
            'date' => '2025-12-10',
            'description' => 'Commencez votre journée en douceur avec une séance de yoga surplombant la mer. Tous niveaux.',
            'thumbnail' => '/uploads/images/slider5-6754b29b02baa.jpg'
        ],
        [
            'name' => 'Atelier Cuisine Méditerranéenne',
            'prix' => 80.0,
            'date' => '2025-12-18',
            'description' => 'Apprenez les secrets de la cuisine méditerranéenne avec notre chef. Déjeuner inclus.',
            'thumbnail' => '/uploads/images/visiteguidate_top-6771d1a33552d.jpg'
        ],
        [
            'name' => 'Soirée Casino',
            'prix' => 100.0,
            'date' => '2025-12-25',
            'description' => 'Une soirée élégante avec jeux de casino, champagne et musique live. Tenue de soirée requise.',
            'thumbnail' => '/uploads/images/wireless-conference-room-1024x683-6772b8f185e68.jpg'
        ]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO event (name, prix, date, description, thumbnail) VALUES (?, ?, ?, ?, ?)");
    $count = 0;
    foreach ($events as $event) {
        try {
            $stmt->execute([$event['name'], $event['prix'], $event['date'], $event['description'], $event['thumbnail']]);
            $count++;
        } catch (PDOException $e) {
            // Ignore si déjà existant
        }
    }
    echo "  ✓ $count événements ajoutés\n";
    
    // 4. Ajouter des services avec images
    echo "\n[4/6] Ajout de services...\n";
    $services = [
        [
            'nom' => 'Service de Chambre 24/7',
            'prix' => 15.0,
            'count' => null,
            'description' => 'Service de chambre disponible 24h/24 et 7j/7 pour votre confort maximum.',
            'thumbnail' => '/uploads/images/01_HotelsWithBabysittingServices__BeachesResorts_Beaches-Ocho-Rios_Kids-Camp-6772b8a0e1fb9.jpg'
        ],
        [
            'nom' => 'Spa & Bien-être',
            'prix' => 80.0,
            'count' => null,
            'description' => 'Détendez-vous dans notre spa avec massages, hammam et soins du visage.',
            'thumbnail' => '/uploads/images/slider5-6769f8d41e12f.jpg'
        ],
        [
            'nom' => 'Salle de Sport',
            'prix' => 0.0,
            'count' => null,
            'description' => 'Accès gratuit à notre salle de sport équipée avec vue panoramique.',
            'thumbnail' => '/uploads/images/Gym_Hotel+VIU+Milano+design-6771d12f72ca7.jpg'
        ],
        [
            'nom' => 'WiFi Haute Vitesse',
            'prix' => 0.0,
            'count' => null,
            'description' => 'WiFi gratuit et sécurisé dans tout l\'hôtel. Connexion ultra-rapide.',
            'thumbnail' => '/uploads/images/is-hotel-wifi-safe_card-6771d1470e292.png'
        ],
        [
            'nom' => 'Service de Navette Aéroport',
            'prix' => 50.0,
            'count' => null,
            'description' => 'Navette privée vers l\'aéroport. Réservation à l\'avance recommandée.',
            'thumbnail' => '/uploads/images/le-premier-taxi-100-electrique-en-tunisie-est-un-byd-2533_min.1980w-6771d6ece49c8.jpg'
        ],
        [
            'nom' => 'Service de Garde d\'Enfants',
            'prix' => 30.0,
            'count' => null,
            'description' => 'Service professionnel de garde d\'enfants pour que vous puissiez profiter en toute sérénité.',
            'thumbnail' => '/uploads/images/01_HotelsWithBabysittingServices__BeachesResorts_Beaches-Ocho-Rios_Kids-Camp-6772b8a0e1fb9.jpg'
        ]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO service (nom, prix, count, description, thumbnail) VALUES (?, ?, ?, ?, ?)");
    $count = 0;
    foreach ($services as $service) {
        try {
            $stmt->execute([$service['nom'], $service['prix'], $service['count'], $service['description'], $service['thumbnail']]);
            $count++;
        } catch (PDOException $e) {
            // Ignore si déjà existant
        }
    }
    echo "  ✓ $count services ajoutés\n";
    
    // 5. Mettre à jour l'utilisateur admin avec nom et prénom
    echo "\n[5/6] Mise à jour de l'utilisateur admin...\n";
    $stmt = $pdo->prepare("UPDATE user SET nom = ?, prenom = ? WHERE email = 'root@root.com'");
    $stmt->execute(['Admin', 'IH-AR']);
    echo "  ✓ Utilisateur admin mis à jour\n";
    
    // 6. Vérifier les données
    echo "\n[6/6] Vérification des données...\n";
    $stmt = $pdo->query("SELECT COUNT(*) FROM room");
    $rooms = $stmt->fetchColumn();
    echo "  ✓ Chambres: $rooms\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM event");
    $events_count = $stmt->fetchColumn();
    echo "  ✓ Événements: $events_count\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM service");
    $services_count = $stmt->fetchColumn();
    echo "  ✓ Services: $services_count\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM user");
    $users_count = $stmt->fetchColumn();
    echo "  ✓ Utilisateurs: $users_count\n";
    
    echo "\n✅ Base de données complétée avec succès !\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}

