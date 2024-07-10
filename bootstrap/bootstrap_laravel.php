<?php

use App\Console\Kernel as ConsoleKernel;

$app = require '/var/www/e-cidade/bootstrap/app.php';
$app->make(ConsoleKernel::class)->bootstrap();
