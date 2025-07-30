<?php

declare(strict_types=1);

$config = require __DIR__ . '/../config/config.php';

touchHeartbeat($config['local_heartbeat']);

function touchHeartbeat(string $path): void
{
    file_put_contents($path, (string)time() . PHP_EOL, LOCK_EX);
}