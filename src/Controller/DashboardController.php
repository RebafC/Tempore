<?php

declare(strict_types=1);

namespace Tempore\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class DashboardController
{
    private array $config;
    private Environment $twig;

    public function __construct(array $config)
    {
        $this->config = $config;
        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        $this->twig = new Environment($loader);
    }

    public function render(): void
    {
        $peerStatus = $this->getPeerStatus();
        $localStatus = $this->getLocalStatus();

        echo $this->twig->render('dashboard.twig', [
            'peer_url' => $this->config['peer_url'],
            'peer_last_heartbeat' => $peerStatus['last_heartbeat'],
            'peer_status' => $peerStatus['status'],
            'local_heartbeat_url' => $this->getPublicHeartbeatUrl(),
            'local_last_heartbeat' => $localStatus['last_heartbeat'],
            'heartbeat_tolerance' => $this->config['heartbeat_tolerance'],
            'alert_method' => $this->config['alert']['method'],
            'last_alert' => $this->getLastAlertLine(),
        ]);
    }

    private function getPeerStatus(): array
    {
        $timestamp = @file_get_contents($this->config['peer_url']);
        $valid = is_numeric($timestamp);
        $age = $valid ? (time() - (int)$timestamp) : null;

        return [
            'last_heartbeat' => $valid ? date('c', (int)$timestamp) : 'N/A',
            'status' => (!$valid)
                ? 'Unreachable'
                : (($age > $this->config['heartbeat_tolerance']) ? 'Stale' : 'Healthy'),
        ];
    }

    private function getLocalStatus(): array
    {
        $file = $this->config['local_heartbeat'];
        $timestamp = is_file($file) ? (int)trim((string)@file_get_contents($file)) : null;

        return [
            'last_heartbeat' => $timestamp ? date('c', $timestamp) : 'Never',
        ];
    }

    private function getPublicHeartbeatUrl(): string
    {
        return (isset($_SERVER['HTTPS']) ? 'https://' : 'http://')
            . ($_SERVER['HTTP_HOST'] ?? 'localhost')
            . dirname($_SERVER['SCRIPT_NAME'])
            . '/heartbeat.php';
    }

    private function getLastAlertLine(): ?string
    {
        $log = ini_get('error_log');
        if (!$log || !is_file($log)) {
            return null;
        }

        $lines = file($log, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lines = array_reverse($lines);

        foreach ($lines as $line) {
            if (str_contains($line, '[TEMPORAL ALERT]')) {
                return $line;
            }
        }

        return null;
    }
}