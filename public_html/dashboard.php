<?php

declare(strict_types=1);

use Tempore\Controller\DashboardController;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';

$controller = new DashboardController($config);
$controller->render();
