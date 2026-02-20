<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$yamlPath = __DIR__ . '/../public/docs/openapi.yaml';
$jsonPath = __DIR__ . '/../public/docs/openapi.json';

if (!file_exists($yamlPath)) {
    fwrite(STDERR, "Missing OpenAPI YAML at {$yamlPath}. Run: php artisan scribe:generate\n");
    exit(1);
}

$spec = Yaml::parseFile($yamlPath);

if (!is_array($spec)) {
    fwrite(STDERR, "Failed to parse OpenAPI YAML at {$yamlPath}.\n");
    exit(1);
}

$json = json_encode($spec, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
if ($json === false) {
    fwrite(STDERR, "Failed to encode OpenAPI JSON.\n");
    exit(1);
}

file_put_contents($jsonPath, $json . "\n");

fwrite(STDOUT, "Wrote OpenAPI JSON to {$jsonPath}.\n");
