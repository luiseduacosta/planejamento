<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(dirname(__FILE__))));
define('APP_DIR', 'planejamento');
define('WEBROOT_DIR', 'webroot');
define('WWW_ROOT', ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS);
define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . APP_DIR . DS . 'Vendor' . DS . 'cakephp' . DS . 'cakephp' . DS . 'lib');
define('TMP', sys_get_temp_dir() . DS . 'planejamento' . DS);

require CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php';
App::uses('ClassRegistry', 'Utility');

$logPath = ROOT . DS . APP_DIR . DS . '.cursor' . DS . 'debug-9eaa84.log';
$log = function ($hypothesisId, $location, $message, $data = [], $runId = 'post-fix-cli') use ($logPath) {
    file_put_contents($logPath, json_encode([
        'sessionId' => '9eaa84',
        'runId' => $runId,
        'hypothesisId' => $hypothesisId,
        'location' => $location,
        'message' => $message,
        'data' => $data,
        'timestamp' => round(microtime(true) * 1000),
    ]) . "\n", FILE_APPEND);
};

$Docente = ClassRegistry::init('Docente');

$condicoes = [];
$condicoes['OR'] = [
    ['motivoegresso' => ''],
    ['motivoegresso IS NULL'],
];
$log('H1', 'debug_run:assign-array', 'assign OR on empty array condicoes', [
    'condicoesType' => gettype($condicoes),
    'condicoes' => $condicoes,
]);

$wrongOr = ['OR' => ['motivoegresso' => '', 'motivoegresso IS NULL']];
$correctOr = ['OR' => [
    ['motivoegresso' => ''],
    ['motivoegresso IS NULL'],
]];

$wrongCount = $Docente->find('count', ['conditions' => $wrongOr]);
$correctCount = $Docente->find('count', ['conditions' => $correctOr]);
$inactiveCount = $Docente->find('count', ['conditions' => ['motivoegresso != ' => '']]);
$totalCount = $Docente->find('count');

$log('H2', 'debug_run:filter-counts', 'active filter SQL comparison', [
    'total' => (int) $totalCount,
    'wrongOrCount' => (int) $wrongCount,
    'correctOrCount' => (int) $correctCount,
    'expectedActive' => 77,
    'inactiveFilterCount' => (int) $inactiveCount,
    'expectedInactive' => 59,
]);

$wrongSample = $Docente->find('first', [
    'conditions' => $wrongOr,
    'fields' => ['Docente.id', 'Docente.nome', 'Docente.motivoegresso'],
    'order' => ['Docente.id' => 'DESC'],
]);
$log('H2', 'debug_run:wrong-or-sample', 'sample from wrong OR query', [
    'sample' => !empty($wrongSample['Docente']) ? $wrongSample['Docente'] : null,
]);

$missing = $Docente->find('first', ['conditions' => ['Docente.id' => 999999]]);
$log('H4', 'debug_run:missing-view', 'missing docente lookup', [
    'found' => !empty($missing),
]);

$deleteResult = $Docente->delete(999999);
$log('H5', 'debug_run:delete-missing', 'delete non-existent id', [
    'deleted' => (bool) $deleteResult,
]);

echo "Debug run complete. Log: $logPath\n";
