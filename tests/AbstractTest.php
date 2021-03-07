<?php declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{
    private const RESOURCES_PATH = __DIR__ . '/resources/';

    protected function getResourceContent(string $filepath): string
    {
        return file_get_contents($this->getResourcePath($filepath));
    }

    protected function getResourcePath(string $filepath): string
    {
        return self::RESOURCES_PATH . $filepath;
    }
}
