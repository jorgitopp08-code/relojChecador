<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function config_value(array $config, string $key, string $default = ''): string
{
    if (array_key_exists($key, $config)) {
        return (string) $config[$key];
    }

    $envValue = getenv($key);
    if ($envValue === false) {
        return $default;
    }

    return (string) $envValue;
}

$config = [];
$configPath = __DIR__ . '/config.php';

if (is_file($configPath)) {
    $loadedConfig = require $configPath;

    if (is_array($loadedConfig)) {
        $config = $loadedConfig;
    }
}

$dbHost = config_value($config, 'DB_HOST');
$dbPort = (int) config_value($config, 'DB_PORT', '3306');
$dbName = config_value($config, 'DB_NAME');
$dbUser = config_value($config, 'DB_USER');
$dbPassword = config_value($config, 'DB_PASSWORD');

if ($dbHost === '' || $dbName === '' || $dbUser === '') {
    http_response_code(500);
    exit('Falta configurar la base de datos. Crea config.php a partir de config.example.php o define variables de entorno.');
}

try {
    $conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName, $dbPort);
    mysqli_set_charset($conn, 'utf8mb4');
} catch (mysqli_sql_exception $exception) {
    http_response_code(500);
    exit('No fue posible conectar con la base de datos.');
}
