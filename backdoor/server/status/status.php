<?php
// -----------------------------------------------------------------------
// Appelé DIRECTEMENT par server/status/dashboard.html (iframe de la page
// Configuration > Serveur), hors routeur : il n'avait AUCUNE vérification
// de session - n'importe qui lisait disque, RAM, CPU et trafic réseau du
// serveur. Même amorçage minimal que sbUploadServer.php (2026-10-01).
// -----------------------------------------------------------------------
defined('SBUIADMIN_PATH') or define('SBUIADMIN_PATH', dirname(__FILE__, 3));
defined('SBUIADMIN_URL')  or define('SBUIADMIN_URL', $_SERVER['SERVER_NAME'] . rtrim(dirname($_SERVER['SCRIPT_NAME'], 3), '/') . '/');
require_once(__DIR__ . '/../../../inc/sbsession.php');
session_start();
require_once(SBUIADMIN_PATH . '/inc/sbuiadmin-config.php');
require_once(SBUIADMIN_PATH . '/inc/sbuiadmin-rights.php');
require_once(_AM_SMARTY_DIR . 'Smarty.class.php'); // la classe "sql" hérite de Smarty
require_once(SBUIADMIN_PATH . '/inc/class/sbuiadmin-sql.php');
require_once(SBUIADMIN_PATH . '/inc/class/sbuiadmin-sanitize.php');
require_once(SBUIADMIN_PATH . '/inc/class/sbuiadmin-users.php');
$sbsql      = new sql();
$sbsanitize = new sanitize();
$sbusers    = new user();
session_write_close(); // lecture seule : ne bloque pas les autres requêtes de la session

header('Content-Type: application/json');
if (!sbHasRight('server', 'view')) {
	http_response_code(403);
	echo json_encode(array('error' => 'forbidden'));
	exit;
}

// shell_exec() peut être désactivé (disable_functions) : lire /proc directement.
function sbStatusShell($cmd) {
	if (!function_exists('shell_exec') || stripos((string)ini_get('disable_functions'), 'shell_exec') !== false) return null;
	return @shell_exec($cmd);
}

// Disque
$disk_path = "/";
$total = disk_total_space($disk_path);
$free = disk_free_space($disk_path);
$used = $total - $free;
$total_gb = round($total / (1024 ** 3), 2);
$used_gb = round($used / (1024 ** 3), 2);
$free_gb = round($free / (1024 ** 3), 2);

// RAM
$meminfo = file_get_contents("/proc/meminfo");
preg_match('/MemTotal:\s+(\d+)/', $meminfo, $total_mem);
preg_match('/MemAvailable:\s+(\d+)/', $meminfo, $avail_mem);
$ram_total = round($total_mem[1] / 1024, 2);
$ram_free = round($avail_mem[1] / 1024, 2);
$ram_used = round($ram_total - $ram_free, 2);

// CPU
$cpuinfo  = @file_get_contents('/proc/cpuinfo');
$cpuModel = ($cpuinfo && preg_match('/^model name\s*:\s*(.+)$/m', $cpuinfo, $m)) ? $m[1] : (string)sbStatusShell("lscpu | grep 'Model name' | awk -F: '{print $2}'");
$cpuCores = ($cpuinfo) ? preg_match_all('/^processor\s*:/m', $cpuinfo) : (int)sbStatusShell("nproc");
$cpuCores = max(1, (int)$cpuCores);
$cpuLoad = sys_getloadavg()[0] * 100 / $cpuCores;
$cpuTemp = null;
if (file_exists("/sys/class/thermal/thermal_zone0/temp")) {
    $rawTemp = file_get_contents("/sys/class/thermal/thermal_zone0/temp");
    $cpuTemp = round($rawTemp / 1000, 1);
}

// Réseau
$net_data = implode("\n", preg_grep('/eth0|ens|enp/', explode("\n", (string)@file_get_contents('/proc/net/dev'))));
$rx = $tx = 0;
foreach (explode("\n", $net_data) as $line) {
    if (preg_match('/\s*(\w+):\s*(\d+)/', $line)) {
        $fields = preg_split('/\s+/', trim($line));
        if (isset($fields[1], $fields[9])) {
            $rx += (int)$fields[1];
            $tx += (int)$fields[9];
        }
    }
}
$rx_mb = round($rx / (1024 ** 2), 2);
$tx_mb = round($tx / (1024 ** 2), 2);

echo json_encode([
    'disk' => [
        'total_gb' => $total_gb,
        'used_gb' => $used_gb,
        'free_gb' => $free_gb,
    ],
    'ram' => [
        'total_mb' => $ram_total,
        'used_mb' => $ram_used,
        'free_mb' => $ram_free,
    ],
    'cpu' => [
        'model' => trim($cpuModel),
        'cores' => (int)$cpuCores,
        'load' => round($cpuLoad, 1),
        'temp_c' => $cpuTemp ? str_replace('+', '', $cpuTemp) : null
    ],
    'network' => [
        'rx_mb' => $rx_mb,
        'tx_mb' => $tx_mb
    ],
    'timestamp' => date('c')
], JSON_PRETTY_PRINT);
