<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

Artisan::command('smilecare:sync', function (): void {
    $this->comment('SmileCare application is ready.');
})->purpose('Smoke test for the SmileCare application');
