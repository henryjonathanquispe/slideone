<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class SlideoneTest extends TestCase
{
    public function test_package_composer_name(): void
    {
        $composer = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true);
        $this->assertSame('processmaker/package-slideone', $composer['name']);
        $this->assertSame('SlideOne', $composer['friendly_name']);
    }
}
