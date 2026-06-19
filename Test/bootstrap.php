<?php
/**
 * Test bootstrap for CakePHP 2.x.
 *
 * Sets up application constants and includes necessary config so that
 * ControllerTestCase and CakeFixture can operate correctly.
 *
 * Path conventions mirror webroot/index.php:
 *   ROOT    = /home/luis/html/        (parent of project dir)
 *   APP_DIR = planejamento            (project dir name)
 *   APP     = /home/luis/html/planejamento/
 */

// ---------------------------------------------------------------------------
// PHP 8.x polyfills (must load before CakePHP and PHPUnit)
// ---------------------------------------------------------------------------
if (!function_exists('each')) {
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
// CakePHP application constants
// ---------------------------------------------------------------------------
define('DS', DIRECTORY_SEPARATOR);

$_testDir = __DIR__;                           // .../planejamento/Test
$_appDir  = dirname($_testDir);                // .../planejamento
define('APP_DIR', basename($_appDir));         // planejamento
define('ROOT', dirname($_appDir) . DS);        // .../html/
define('APP', $_appDir . DS);                  // .../planejamento/

define('WEBROOT_DIR', 'webroot');
define('WWW_ROOT', APP . WEBROOT_DIR . DS);
define('TESTS', APP . 'Test' . DS);
define('TMP', rtrim(sys_get_temp_dir(), DS) . DS . APP_DIR . DS);
define('LOGS', TMP . 'logs' . DS);
define('CACHE', TMP . 'cache' . DS);
define('CAKE_CORE_INCLUDE_PATH', APP . 'Vendor' . DS . 'cakephp' . DS . 'cakephp' . DS . 'lib');
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);

// Ensure tmp directories exist
foreach ([TMP, CACHE, CACHE . 'models', CACHE . 'persistent', CACHE . 'views', LOGS] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// ---------------------------------------------------------------------------
// Security salt
// ---------------------------------------------------------------------------
if (!defined('CAKE_SALT')) {
    define('CAKE_SALT', 'test-salt-for-unit-tests');
}

// ---------------------------------------------------------------------------
// Load CakePHP core
// ---------------------------------------------------------------------------
require CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php';

// ---------------------------------------------------------------------------
// Load application configuration
// ---------------------------------------------------------------------------
require APP . 'Config' . DS . 'core.php';
require APP . 'Config' . DS . 'bootstrap.php';

// ---------------------------------------------------------------------------
// Load CakePHP test infrastructure
// ---------------------------------------------------------------------------
App::uses('CakeTestCase', 'TestSuite');
App::uses('ControllerTestCase', 'TestSuite');
App::uses('CakeFixtureManager', 'TestSuite/Fixture');

// Make the test DB connection available for fixtures
App::uses('ConnectionManager', 'Model');
try {
    ConnectionManager::getDataSource('test');
} catch (Exception $e) {
    // Test connection might not exist — fixtures using $import
    // will fall back to 'default' if 'test' is unavailable.
}

// Clear the cache for tests
Cache::clear(false, '_cake_core_');
Cache::clear(false, '_cake_model_');
