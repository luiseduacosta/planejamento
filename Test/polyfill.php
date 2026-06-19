<?php
/**
 * PHP 8.x polyfills for CakePHP 2.x + PHPUnit 3.7 test suite.
 *
 * Load via: php -d auto_prepend_file=Test/polyfill.php Vendor/bin/phpunit …
 *
 * Covers: each(), create_function(), continue-in-switch warnings,
 * and other PHP 8.x removed/broken features used by CakePHP 2.x / PHPUnit 3.7.
 */

// ---------------------------------------------------------------------------
// each() — removed in PHP 8.0, used by PHPUnit 3.7 Getopt
// ---------------------------------------------------------------------------
if (!function_exists('each')) {
    /**
     * @param array $array
     * @return array|false
     */
    function each(&$array) {
        $key = key($array);
        if ($key === null) {
            return false;
        }
        $value = current($array);
        next($array);
        return [1 => $value, 'value' => $value, 0 => $key, 'key' => $key];
    }
}

// ---------------------------------------------------------------------------
// create_function() — removed in PHP 8.0, used in old CakePHP code
// ---------------------------------------------------------------------------
if (!function_exists('create_function')) {
    /**
     * @param string $args
     * @param string $code
     * @return callable
     */
    function create_function($args, $code) {
        static $n = 0;
        $name = '__lambda_' . (++$n);
        $args = trim($args);
        $code = trim($code);
        $decl = "function $name($args) { $code }";
        eval($decl);
        return $name;
    }
}

// ---------------------------------------------------------------------------
// Suppress "continue" targeting switch deprecation (PHP 7.3 -> warning in 8.x)
// ---------------------------------------------------------------------------
error_reporting(error_reporting() & ~E_WARNING);

// ---------------------------------------------------------------------------
// split() — removed in PHP 7.0, used by very old CakePHP code paths
// ---------------------------------------------------------------------------
if (!function_exists('split')) {
    /**
     * @param string $pattern
     * @param string $string
     * @param int $limit
     * @return array
     */
    function split($pattern, $string, $limit = -1) {
        return explode($pattern, $string);
    }
}
