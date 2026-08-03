<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class SmokeTest extends TestCase
{
    public function test_homepage_loads(): void
    {
        $this->get('/')->assertOk();
    }
}
