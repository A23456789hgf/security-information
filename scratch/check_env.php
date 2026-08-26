<?php
echo "PHP Version: " . PHP_VERSION . "\n";
echo "OS: " . PHP_OS . "\n";
$output = [];
$return_var = 0;
exec('where mysqldump', $output, $return_var);
echo "mysqldump path: " . implode("\n", $output) . " (Return code: $return_var)\n";

exec('where mysql', $output2, $return_var2);
echo "mysql path: " . implode("\n", $output2) . " (Return code: $return_var2)\n";

echo "Current User: " . get_current_user() . "\n";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
