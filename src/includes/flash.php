<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function flash_set(string $key, string $message, string $type = 'info'): void {
    $_SESSION['flash'][$key] = ['message' => $message, 'type' => $type];
}

function flash_get(string $key): ?array {
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }
    $data = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $data;
}

function flash_pull_all(): array {
    if (empty($_SESSION['flash'])) {
        return [];
    }
    $messages = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $messages;
}
