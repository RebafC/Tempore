# Tempore

Tempore is a mutual cron monitoring system for PHP 8.2+ environments.

## Features

- Mutual peer monitoring via cron
- Configurable tolerance and alert methods
- Web dashboard (Twig 3)

## Important Note

**`heartbeat_tolerance` must exceed your cron interval.**
For example, if cron runs every 5 minutes, set tolerance to 600 seconds or more.

## Alerting

If a peer server becomes unreachable or its heartbeat becomes stale, `monitor.php` triggers an alert via:

- Log
- Email
- Webhook

## Crontab Example

```crontab
*/5 * * * * /usr/bin/php /path/to/scripts/healthcheck.php
*/6 * * * * /usr/bin/php /path/to/scripts/monitor.php
```

## Configuration

Copy `config/config.template.php` to `config/config.php` and edit accordingly.
