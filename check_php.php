<?php
echo "=== PHP CLI Information ===\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "PHP Binary: " . PHP_BINARY . "\n";
echo "Loaded php.ini: " . (php_ini_loaded_file() ?: "None") . "\n";
echo "Extension Directory (extension_dir): " . ini_get('extension_dir') . "\n";

echo "\n=== Checked Extensions ===\n";
$extensions = ['pdo_pgsql', 'pgsql', 'pdo_mysql'];
foreach ($extensions as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? "ENABLED" : "DISABLED") . "\n";
}
