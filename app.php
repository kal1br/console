<?php
require_once __DIR__ . '/autoloader.php';

$kernel = new ConsoleCommand\src\Kernel($argv);
$kernel->run();
