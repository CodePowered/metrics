<?php declare(strict_types=1);

use App\DependencyInjection\ServiceBuilder;
use App\Provider\Client;

require __DIR__ . '/vendor/autoload.php';

// Values received from somewhere
$client = new Client('ju16a6m81mhid5ue1z3v2g0uh', 'your@email.address', 'Your Name');

$statistics = ServiceBuilder::buildCollector()->collect($client);

$json = json_encode($statistics, JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR);

// Show pretty in browser
if (PHP_SAPI === 'apache2handler') {
    $json = '<pre>' . $json . '</pre>';
}

echo $json;
