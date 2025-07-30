<?php

declare(strict_types=1);

use Tempore\Alert\Notifier;

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';

$peerTimestamp = fetchPeerHeartbeat($config['peer_url']);
$tolerance = $config['heartbeat_tolerance'];

if (!$peerTimestamp || (time() - $peerTimestamp > $tolerance)) {
    $notifier = new Notifier($config['alert']);
    $notifier->send("Peer heartbeat stale or unreachable: {$config['peer_url']}");
}

function fetchPeerHeartbeat(string $url): ?int
{
    $response = @file_get_contents($url);
    return is_numeric($response) ? (int)$response : null;
}