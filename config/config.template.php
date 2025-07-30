<?php

return [
    'local_heartbeat' => __DIR__ . '/../data/heartbeat.log',
    'peer_url' => 'https://peer.example.com/heartbeat.php',
    'heartbeat_tolerance' => 600,
    'alert' => [
        'method' => 'log',
        'email' => 'admin@example.com',
        'webhook_url' => null,
    ],
];