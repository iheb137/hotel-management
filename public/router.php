<?php
/**
 * Router PHP pour le serveur de développement intégré
 * Sert les fichiers statiques directement sans passer par Symfony
 */

// Récupérer le chemin de la requête
$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Si c'est un fichier statique (CSS, JS, images, fonts, etc.), le servir directement
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot|webp|pdf)$/i', $requestPath)) {
    $filePath = __DIR__ . $requestPath;
    
    // Normaliser le chemin pour éviter les problèmes de sécurité
    $filePath = realpath($filePath);
    $docRoot = realpath(__DIR__);
    
    // Vérifier que le fichier est dans le répertoire public
    if ($filePath && strpos($filePath, $docRoot) === 0 && file_exists($filePath) && is_file($filePath)) {
        // Déterminer le type MIME
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
        ];
        
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: public, max-age=3600');
        
        readfile($filePath);
        exit;
    }
}

// Sinon, passer à Symfony
return false;

