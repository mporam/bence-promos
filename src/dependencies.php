<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($container) {
    $settings = $container->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($container) {
    $settings = $container->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// db
$container['db'] = function ($container) {
    $cfg = $container->get('settings')['pdo'];

    $servername = $cfg['servername'];
    $database = $cfg['database'];
    $username = $cfg['username'];
    $password = $cfg['password'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", "$username", "$password");
        echo $database;
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $logger = $container->get('logger');
        $logger->error("Connection failed: " . $e->getMessage());
        die("Database Connection failed");

    }

    return $conn;
};
