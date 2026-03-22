<?php
require_once __DIR__ . '/../db.php';

function authBasePrefix(): string
{
    $script = $_SERVER['PHP_SELF'] ?? '';
    return (str_contains($script, '/admin/') || str_contains($script, '/reports/')) ? '../' : '';
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        flashMessage('Please login to continue.', 'warning');
        redirectTo(authBasePrefix() . 'login.php');
    }
}

function requireAdmin(): void
{
    if (!isAdmin()) {
        flashMessage('Admin access required.', 'danger');
        redirectTo(authBasePrefix() . 'admin-login.php');
    }
}
?>
