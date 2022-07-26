<?php
require_once __DIR__ . '/../../autoload.php';

$kernel = new ConsoleCommand\src\Kernel($argv);
$kernel->run();
