#!/usr/bin/env php
<?php
/**
 * Standalone test runner for PlanejamentosController null-ID guards.
 *
 * Bypasses PHPUnit 3.7 entirely (incompatible with PHP 8.5) and works around
 * CakePHP 2.x / PHP 8.2+ compatibility issues by subclassing the controller
 * to override redirect() and avoid dynamic-property deprecations on CakeEvent.
 *
 * Usage:
 *   php Test/run_planejamentos_tests.php
 */

// ---------------------------------------------------------------------------
// PHP 8.x polyfills
// ---------------------------------------------------------------------------
if (!function_exists('each')) {
    function each(&$array) {
        $key = key($array);
        if ($key === null) return false;
        $value = current($array);
        next($array);
        return [1 => $value, 'value' => $value, 0 => $key, 'key' => $key];
    }
}

// ---------------------------------------------------------------------------
// Bootstrap CakePHP
// ---------------------------------------------------------------------------
define('DS', DIRECTORY_SEPARATOR);
$_testDir = __DIR__;
$_appDir  = dirname($_testDir);
define('APP_DIR', basename($_appDir));
define('ROOT', dirname($_appDir) . DS);
define('APP', $_appDir . DS);
define('WEBROOT_DIR', 'webroot');
define('WWW_ROOT', APP . WEBROOT_DIR . DS);
define('TESTS', APP . 'Test' . DS);
define('TMP', rtrim(sys_get_temp_dir(), DS) . DS . APP_DIR . DS);
define('LOGS', TMP . 'logs' . DS);
define('CACHE', TMP . 'cache' . DS);
define('CAKE_CORE_INCLUDE_PATH', APP . 'Vendor' . DS . 'cakephp' . DS . 'cakephp' . DS . 'lib');
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
if (!defined('CAKE_SALT')) {
    define('CAKE_SALT', 'test-salt-' . uniqid());
}

foreach ([TMP, CACHE, CACHE . 'models', CACHE . 'persistent', CACHE . 'views', LOGS] as $d) {
    if (!is_dir($d)) mkdir($d, 0777, true);
}

require CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php';
require APP . 'Config' . DS . 'core.php';
require APP . 'Config' . DS . 'bootstrap.php';

// Disable CakePHP's custom error handler so PHP 8.2 deprecations don't become
// fatal exceptions. Use a PHP-level error_reporting that excludes deprecations.
restore_error_handler();
restore_exception_handler();
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

Cache::clear(false, '_cake_core_');
Cache::clear(false, '_cake_model_');

App::uses('PlanejamentosController', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('CakeSession', 'Model/Datasource');
App::uses('ClassRegistry', 'Utility');

// ---------------------------------------------------------------------------
// TestPlanejamentosController — overrides redirect() to avoid CakeEvent
// dynamic-property deprecation (PHP 8.2+) and exit().
// ---------------------------------------------------------------------------
class TestPlanejamentosController extends PlanejamentosController
{
    /**
     * Capture redirect target without triggering CakeEvent or calling exit().
     */
    public $testRedirectUrl  = null;
    public $testRedirectCode = null;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->autoRender = false;
        $this->testRedirectUrl  = $url;
        $this->testRedirectCode = $status;
        // Set the actual response header (many tests read this)
        if ($url !== null) {
            $this->response->header('Location', Router::url($url, true));
        }
        if ($status === null) {
            $status = 302;
        }
        if (is_string($status)) {
            $codes = array_flip($this->response->httpCodes());
            if (isset($codes[$status])) $status = $codes[$status];
        }
        $this->response->statusCode($status);
        // Never exit — let the test continue
        return $this->response;
    }
}

// ---------------------------------------------------------------------------
// Simple test harness
// ---------------------------------------------------------------------------
$pass = 0;
$fail = 0;

function assert_true($cond, $name, $detail = '')
{
    global $pass, $fail;
    if ($cond) {
        $pass++;
        echo "  \033[32m✓\033[0m $name\n";
    } else {
        $fail++;
        $detailStr = $detail ? " — $detail" : '';
        echo "  \033[31m✗\033[0m $name$detailStr\n";
    }
}

function assert_eq($expected, $actual, $name, $detail = '')
{
    $ok = ($expected === $actual);
    if (!$ok && $detail === '') {
        $detail = 'expected ' . var_export($expected, true) . ', got ' . var_export($actual, true);
    }
    assert_true($ok, $name, $detail);
}

function assert_contains($needle, $haystack, $name, $detail = '')
{
    $ok = (is_string($haystack) && strpos($haystack, $needle) !== false);
    if (!$ok && $detail === '') {
        $haystackStr = is_string($haystack) ? $haystack : var_export($haystack, true);
        $detail = "'{$needle}' not found in '{$haystackStr}'";
    }
    assert_true($ok, $name, $detail);
}

function assert_not_empty($value, $name)
{
    assert_true(!empty($value), $name);
}

/**
 * Create a controller, set up auth bypass, and call the action method directly.
 * Returns [$controller, $response].
 */
function call_action($action, $id = null, $data = null)
{
    static $sessionStarted = false;
    if (!$sessionStarted) {
        CakeSession::start();
        $sessionStarted = true;
    }

    $request = new CakeRequest();
    $request->params['controller'] = 'planejamentos';
    $request->params['action']     = $action;
    if ($id !== null) {
        $request->params['pass'] = [$id];
    }
    if ($data !== null) {
        $request->data = $data;
    }

    $response = new CakeResponse();
    $Controller = new TestPlanejamentosController($request, $response);
    $Controller->constructClasses();
    $Controller->Auth->allow($action);
    // Bypass isAuthorized by stubbing
    $Controller->Auth->allowedActions = [$action];
    
    // Call the action method directly
    call_user_func([$Controller, $action], $id);

    return [$Controller, $response];
}

/**
 * Get all flash messages from session.
 */
function get_flash()
{
    $messages = [];
    $msg = CakeSession::read('Message.flash');
    if ($msg && isset($msg['message'])) $messages[] = $msg['message'];
    for ($i = 1; $i <= 5; $i++) {
        $msg = CakeSession::read("Message.flash.{$i}");
        if ($msg && isset($msg['message'])) $messages[] = $msg['message'];
    }
    return $messages;
}

/**
 * Get redirect Location header.
 */
function get_location($response)
{
    $headers = $response->header();
    return isset($headers['Location']) ? $headers['Location'] : '';
}

// ---------------------------------------------------------------------------
// Tests
// ---------------------------------------------------------------------------
echo "PlanejamentosController — Null-ID Guard Tests\n";
echo "============================================================\n\n";

// ---- R-1: view() -----------------------------------------------------------
echo "R-1: view()\n";
CakeSession::clear(false);

[$ctrl, $resp] = call_action('view', null);
assert_contains('Planejamentos/listar', get_location($resp), 'view(null) redirects');
assert_contains('Registro não encontrado', implode(' | ', get_flash()), 'view(null) flashes error');

CakeSession::clear(false);
[$ctrl, $resp] = call_action('view', 99999);
assert_contains('Planejamentos/listar', get_location($resp), 'view(99999) redirects');
assert_contains('Registro não encontrado', implode(' | ', get_flash()), 'view(99999) flashes error');

CakeSession::clear(false);
[$ctrl, $resp] = call_action('view', 1);
$vars = $ctrl->viewVars;
assert_true(isset($vars['planejamento']), 'view(1) sets $planejamento view var');
assert_eq(1, $vars['planejamento']['Planejamento']['id'] ?? null, 'view(1) returns record #1');

echo "\n";

// ---- R-2: delete() ---------------------------------------------------------
echo "R-2: delete()\n";

CakeSession::clear(false);
[$ctrl, $resp] = call_action('delete', null);
assert_contains('Registro não encontrado', implode(' | ', get_flash()), 'delete(null) flashes error');
assert_contains('index', get_location($resp), 'delete(null) redirects');

CakeSession::clear(false);
[$ctrl, $resp] = call_action('delete', 99999);
assert_contains('Registro não encontrado', implode(' | ', get_flash()), 'delete(99999) flashes error');
assert_contains('index', get_location($resp), 'delete(99999) redirects');

// delete(2) should work and remove record 2
CakeSession::clear(false);
[$ctrl, $resp] = call_action('delete', 2);
assert_contains('Registro excluído', implode(' | ', get_flash()), 'delete(2) flashes success');
$Planejamento = ClassRegistry::init('Planejamento');
assert_true(!$Planejamento->exists(2), 'delete(2) removes record from DB');

echo "\n";

// ---- R-3: edit() -----------------------------------------------------------
echo "R-3: edit()\n";

CakeSession::clear(false);
[$ctrl, $resp] = call_action('edit', null);
assert_contains('Registro não encontrado', implode(' | ', get_flash()), 'edit(null) flashes error');
assert_contains('Planejamentos/listar', get_location($resp), 'edit(null) redirects');

CakeSession::clear(false);
[$ctrl, $resp] = call_action('edit', 99999);
assert_contains('Registro não encontrado', implode(' | ', get_flash()), 'edit(99999) flashes error');
assert_contains('Planejamentos/listar', get_location($resp), 'edit(99999) redirects');

CakeSession::clear(false);
[$ctrl, $resp] = call_action('edit', 1);
$vars = $ctrl->viewVars;
assert_true(isset($vars['dias']), 'edit(1) sets $dias form view var');
assert_eq(1, $ctrl->data['Planejamento']['id'] ?? null, 'edit(1) loads record data');

echo "\n";

// ---- R-4: relaciona() ------------------------------------------------------
echo "R-4: relaciona()\n";

CakeSession::clear(false);
[$ctrl, $resp] = call_action('relaciona', null);
assert_contains('Registro não encontrado', implode(' | ', get_flash()), 'relaciona(null) flashes error');
assert_contains('Planejamentos/listar', get_location($resp), 'relaciona(null) redirects');

CakeSession::clear(false);
[$ctrl, $resp] = call_action('relaciona', 99999);
assert_contains('Registro não encontrado', implode(' | ', get_flash()), 'relaciona(99999) flashes error');
assert_contains('Planejamentos/listar', get_location($resp), 'relaciona(99999) redirects');

CakeSession::clear(false);
[$ctrl, $resp] = call_action('relaciona', 1);
assert_true(isset($ctrl->viewVars['optativas']), 'relaciona(1) sets $optativas view var');
assert_eq(1, $ctrl->data['Planejamento']['id'] ?? null, 'relaciona(1) loads record');

echo "\n";

// ---- R-5: excluir() --------------------------------------------------------
echo "R-5: excluir()\n";

CakeSession::clear(false);
[$ctrl, $resp] = call_action('excluir', null);
assert_contains('Configuração não encontrada', implode(' | ', get_flash()), 'excluir(null) flashes error');
assert_contains('Planejamentos/index', get_location($resp), 'excluir(null) redirects');

CakeSession::clear(false);
[$ctrl, $resp] = call_action('excluir', 99);
assert_contains('Nenhum registro encontrado para exclusão', implode(' | ', get_flash()), 'excluir(99) flashes no-records error');
assert_contains('Planejamentos/index', get_location($resp), 'excluir(99) redirects');

// Cleanup: re-insert record 2 (was deleted in R-2 test) so config 1 still has records
$Planejamento = ClassRegistry::init('Planejamento');
$Planejamento->save([
    'Planejamento' => [
        'id' => 2, 'configuraplanejamento_id' => 1,
        'turno' => 'Diurno', 'periodo' => 2, 'dia_id' => 2, 'horario_id' => 2,
        'sala_id' => 2, 'disciplina_id' => 2, 'docente_id' => 2,
        'created' => '2025-01-01 00:00:00', 'modified' => '2025-01-01 00:00:00',
    ]
]);

CakeSession::clear(false);
[$ctrl, $resp] = call_action('excluir', 1);
$before = $Planejamento->find('count', ['conditions' => ['Planejamento.configuraplanejamento_id' => 1]]);
assert_contains('Registros excluídos', implode(' | ', get_flash()), 'excluir(1) flashes success');
assert_eq(0, $before, 'excluir(1) deletes all records for config 1');

echo "\n";

// ---- R-3a/R-2a: Conflict non-regression ------------------------------------
echo "Phase 3: Conflict detection (add/edit save despite slot conflict)\n";

// Re-insert records for conflict tests
$Planejamento->saveMany([
    ['Planejamento' => ['id' => 1, 'configuraplanejamento_id' => 1, 'turno' => 'Diurno', 'periodo' => 1, 'dia_id' => 1, 'horario_id' => 1, 'sala_id' => 1, 'disciplina_id' => 1, 'docente_id' => 1, 'created' => '2025-01-01', 'modified' => '2025-01-01']],
    ['Planejamento' => ['id' => 3, 'configuraplanejamento_id' => 2, 'turno' => 'Diurno', 'periodo' => 1, 'dia_id' => 1, 'horario_id' => 1, 'sala_id' => 3, 'disciplina_id' => 3, 'docente_id' => 3, 'created' => '2025-01-01', 'modified' => '2025-01-01']],
    ['Planejamento' => ['id' => 5, 'configuraplanejamento_id' => 2, 'turno' => 'Diurno', 'periodo' => 1, 'dia_id' => 1, 'horario_id' => 1, 'sala_id' => 5, 'disciplina_id' => 5, 'docente_id' => 5, 'created' => '2025-01-01', 'modified' => '2025-01-01']],
]);

// add() with conflicting slot data
CakeSession::clear(false);
$conflictData = [
    'Planejamento' => [
        'configuraplanejamento_id' => 1,
        'turno'      => 'Diurno',
        'periodo'    => 1,
        'dia_id'     => 1,
        'horario_id' => 1,
        'sala_id'    => 99,
        'disciplina_id' => 99,
        'docente_id' => 99,
    ]
];
[$ctrl, $resp] = call_action('add', null, $conflictData);
assert_contains('listar', get_location($resp), 'add() redirects to listar (saves with conflict)');
$saved = $Planejamento->find('first', ['conditions' => ['Planejamento.disciplina_id' => 99]]);
assert_not_empty($saved, 'add() persists record despite slot conflict');

// edit() with conflicting slot data
CakeSession::clear(false);
$editConflictData = [
    'Planejamento' => [
        'configuraplanejamento_id' => 2,
        'turno'      => 'Diurno',
        'periodo'    => 1,
        'dia_id'     => 1,
        'horario_id' => 1,
        'sala_id'    => 3,
        'disciplina_id' => 88,
        'docente_id' => 3,
    ]
];
[$ctrl, $resp] = call_action('edit', 3, $editConflictData);
assert_contains('view', get_location($resp), 'edit() redirects to view (saves with conflict)');
$updated = $Planejamento->find('first', ['conditions' => ['Planejamento.id' => 3]]);
assert_eq(88, $updated['Planejamento']['disciplina_id'] ?? null, 'edit() updates record despite slot conflict');

echo "\n";

// Cleanup test data
$Planejamento->deleteAll(['Planejamento.disciplina_id' => [88, 99]]);

// ---------------------------------------------------------------------------
// Summary
// ---------------------------------------------------------------------------
echo "============================================================\n";
echo "RESULTS:  {$pass} passed, {$fail} failed, " . ($pass + $fail) . " total\n";
echo "============================================================\n";

exit($fail > 0 ? 1 : 0);
