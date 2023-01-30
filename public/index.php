<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload.php';

$kernel = new Kernel();
$kernel->run();