<?php
header('Content-Type: application/json');

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
$cpuModel = shell_exec("lscpu | grep 'Model name' | awk -F: '{print $2}'");
$cpuCores = shell_exec("nproc");
$cpuLoad = sys_getloadavg()[0] * 100 / (int)$cpuCores;
$cpuTemp = null;
if (file_exists("/sys/class/thermal/thermal_zone0/temp")) {
    $rawTemp = file_get_contents("/sys/class/thermal/thermal_zone0/temp");
    $cpuTemp = round($rawTemp / 1000, 1);
}

// Réseau
$net_data = shell_exec("cat /proc/net/dev | grep -E 'eth0|ens|enp'");
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
