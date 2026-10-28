<?php
/**
 * webERP Security Headers - Added for CVE-2024-4577, CVE-2026-109618, CVE-2026-109619
 * 
 * This file adds critical security headers to all responses.
 * Include this at the top of index.php or via auto_prepend_file
 */

// Prevent direct access
if (!defined('ESIF')) {
    die('Direct access not permitted');
}

// HTTP Security Headers
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' https://*.weberp.org; style-src \'self\' \'unsafe-inline\'; img-src \'self\' data: https:; font-src \'self\'; connect-src \'self\'; frame-ancestors \'self\'; form-action \'self\'; base-uri \'self\';');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Sat, 01 Jan 1990 00:00:00 GMT');
header('X-Permitted-Cross-Domain-Policies: none');
header('X-Download-Options: noopen');
header('X-Powered-By: webERP');
header('Server: webERP');

// Prevent clickjacking
header('Content-Security-Policy-Report-Only: default-src \'self\'; frame-ancestors \'self\';');

// Content Type Sniffing
header('X-Content-Type-Options: nosniff');

// Remove sensitive headers
header_remove('X-Powered-By');
header_remove('Server');
header_remove('X-ASPNET');
header_remove('X-ASPNET-Version');

// Session Security (if session not started)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_secure', '1');
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cache_limiter', 'private, must-revalidate');
}

// Input validation
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate POST data
    if (count($_POST) > 5000) {
        http_response_code(413);
        die('Too many POST parameters');
    }
}

// Prevent PHP-CGI argument injection (CVE-2024-4577)
if (PHP_SAPI === 'cgi-fcgi' || PHP_SAPI === 'cgi') {
    if (isset($_SERVER['QUERY_STRING']) && preg_match('/[\x00-\x1F]/', $_SERVER['QUERY_STRING'])) {
        http_response_code(400);
        die('Invalid request');
    }
}