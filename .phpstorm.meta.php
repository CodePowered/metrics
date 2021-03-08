<?php declare(strict_types=1);

namespace PHPSTORM_META {
    override(
        \App\SuperMetrics\Client::handleGetRequest(),
        map([
            '' => '@'
        ])
    );
    override(
        \App\SuperMetrics\Client::handlePostRequest(),
        map([
            '' => '@'
        ])
    );
}
