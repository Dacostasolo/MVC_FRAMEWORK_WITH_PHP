
<?php

use app\core\Application;


require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'db' => [
        'user' => $_ENV['DB_USER'],
        'dsn' => $_ENV['DB_DSN'],
        'pass' => $_ENV['DB_PASS']
    ]

];

$app = new Application(__DIR__, $config);

$app->database->applyMigrations();
