<?php

require_once __DIR__ . '/config.php';

use Symfony\Component\Yaml\Yaml;
use Watchdog\HashProcessor;

$config = Yaml::parse(file_get_contents(AUDIT_CONFIG));

$processor = new HashProcessor();
$processor->setFolders($config['track']['folders']);
$processor->setFiles($config['track']['files']);
$processor->setSavePath($config['tmp']);
$processor->calculate();

$changed = $processor->isChanged();

if ($changed !== false) {
    $processor->sendAlert($changed);
}
