<?php declare(strict_types=1);

namespace PHPSTORM_META {
    override(
        \App\SuperMetrics\Api::handleGetRequest(),
        map([
            '' => '@'
        ])
    );
    override(
        \App\SuperMetrics\Api::handlePostRequest(),
        map([
            '' => '@'
        ])
    );
}
