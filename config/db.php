<?php

$config = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=book_catalog',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
];

$localConfig = __DIR__ . '/db-local.php';

if (file_exists($localConfig)) {
    $config = array_merge($config, require $localConfig);
}

return $config;
