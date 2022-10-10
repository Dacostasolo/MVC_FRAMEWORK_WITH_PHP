
<?php

use app\core\Application;
use app\controller\AuthController;
use app\controller\SiteController;

require_once __DIR__ . '/../vendor/autoload.php';
$ROOT_DIR = dirname(__DIR__);

$dotenv = Dotenv\Dotenv::createImmutable($ROOT_DIR);
$dotenv->load();

$config = [
    'db' => [
        'user' => $_ENV['DB_USER'],
        'dsn' => $_ENV['DB_DSN'],
        'pass' => $_ENV['DB_PASS']
    ]

];

$app = new Application($ROOT_DIR, $config);



$app->router->get('/', [SiteController::class, 'home']);

$app->router->get('/contact', [SiteController::class, 'contact']);

$app->router->post('/contact', [SiteController::class, 'handleContact']);


$app->router->get('/login', [AuthController::class, 'login']);

$app->router->post('/login', [AuthController::class, 'login']);


$app->router->get('/register', [AuthController::class, 'register']);

$app->router->post('/register', [AuthController::class, 'register']);


$app->run();
