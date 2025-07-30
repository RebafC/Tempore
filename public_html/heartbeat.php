<?php

declare(strict_types=1);

$config = require __DIR__ . '/../config/config.php';

header('Content-Type: text/plain');

echo readHeartbeat($config['local_heartbeat']);

function readHeartbeat(string $path): string
{
    return trim((string)@file_get_contents($path));
}