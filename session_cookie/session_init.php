<?php

$secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'; 
$httponly = true;
$samesite = 'Lax'; 

if (PHP_VERSION_ID < 70300) {
    session_set_cookie_params(0, '/', '', $secure, $httponly);
} else {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',   // set domain
        'secure' => $secure,
        'httponly' => $httponly,
        'samesite' => $samesite
    ]);
}

ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
session_name('symga_session');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['created_at'])) {
    $_SESSION['created_at'] = time();
}

$maxLifetime = 2 * 60 * 60; 
if (isset($_SESSION['created_at']) && (time() - $_SESSION['created_at']) > $maxLifetime) {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['created_at'] = time();
}
?>
