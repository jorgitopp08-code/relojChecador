<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

date_default_timezone_set('America/Bogota');

const FLASH_SESSION_KEY = 'flash_message';
const CSRF_SESSION_KEY = 'csrf_token';

function redirect_with_message(string $location, string $message, string $type = 'info'): void
{
    $_SESSION[FLASH_SESSION_KEY] = [
        'text' => $message,
        'type' => $type,
    ];

    header("Location: {$location}");
    
    exit;
}

function get_flash_message(): ?array
{
    if (!isset($_SESSION[FLASH_SESSION_KEY]) || !is_array($_SESSION[FLASH_SESSION_KEY])) {
        return null;
    }

    $message = $_SESSION[FLASH_SESSION_KEY];
    unset($_SESSION[FLASH_SESSION_KEY]);

    return [
        'text' => (string) ($message['text'] ?? ''),
        'type' => (string) ($message['type'] ?? 'info'),
    ];
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function normalize_cedula(string $cedula): string
{
    return preg_replace('/\D+/', '', trim($cedula)) ?? '';
}

function is_valid_action(string $action): bool
{
    return in_array($action, ['ingreso', 'ini_refri', 'fin_refri', 'salida'], true);
}

function action_to_column(string $action): ?string
{
    $columns = [
        'ini_refri' => 'inicio_refrigerio',
        'fin_refri' => 'fin_refrigerio',
        'salida' => 'hora_salida',
    ];

    return $columns[$action] ?? null;
}

function action_label(string $action): string
{
    $labels = [
        'ingreso' => 'entrada',
        'ini_refri' => 'inicio de refrigerio',
        'fin_refri' => 'fin de refrigerio',
        'salida' => 'salida',
    ];

    return $labels[$action] ?? 'accion';
}

function csrf_token(): string
{
    if (empty($_SESSION[CSRF_SESSION_KEY])) {
        $_SESSION[CSRF_SESSION_KEY] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION[CSRF_SESSION_KEY];
}

function validate_csrf_token(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION[CSRF_SESSION_KEY])
        && hash_equals((string) $_SESSION[CSRF_SESSION_KEY], $token);
}
