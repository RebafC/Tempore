<?php

declare(strict_types=1);

namespace Tempore\Alert;

class Notifier
{
    private array $config;

    public function __construct(array $alertConfig)
    {
        $this->config = $alertConfig;
    }

    public function send(string $message): void
    {
        match ($this->config['method']) {
            'log' => $this->log($message),
            'email' => $this->email($message),
            'webhook' => $this->webhook($message),
            default => null,
        };
    }

    private function log(string $message): void
    {
        error_log('[TEMPORAL ALERT] ' . $message);
    }

    private function email(string $message): void
    {
        mail($this->config['email'], 'Tempore Monitor Alert', $message);
    }

    private function webhook(string $message): void
    {
        if (!empty($this->config['webhook_url'])) {
            file_get_contents($this->config['webhook_url'] . '?msg=' . urlencode($message));
        }
    }
}