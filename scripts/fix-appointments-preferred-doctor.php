<?php

declare(strict_types=1);

use App\Services\Firebase\FirestoreRepository;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

/** @var FirestoreRepository $repo */
$repo = $app->make(FirestoreRepository::class);

$doctors = $repo->all('doctors') ?: [];
$doctorMapByName = [];
$doctorMapById = [];
foreach ($doctors as $d) {
    $id = $d['id'] ?? null;
    $name = $d['name'] ?? null;
    if ($id) $doctorMapById[$id] = $d;
    if ($name) $doctorMapByName[strtolower(trim($name))] = $d;
}

$appointments = $repo->all('appointments') ?: [];

foreach ($appointments as $appt) {
    $id = $appt['id'] ?? null;
    if (!$id) continue;

    $pref = $appt['preferred_doctor'] ?? null;
    $normalized = null;

    if (is_string($pref) && $pref !== '') {
        $normalized = $pref;
    } elseif (is_array($pref)) {
        // If associative with id
        if (isset($pref['id']) && is_string($pref['id'])) {
            $normalized = $pref['id'];
        } else {
            // If numeric array of ids or objects, pick first
            $first = reset($pref);
            if (is_string($first)) {
                $normalized = $first;
            } elseif (is_array($first)) {
                if (isset($first['id'])) $normalized = $first['id'];
                elseif (isset($first['name'])) {
                    $k = strtolower(trim($first['name']));
                    if (isset($doctorMapByName[$k])) $normalized = $doctorMapByName[$k]['id'] ?? null;
                }
            }
        }
    }

    // Try to resolve by name if normalized is still null and pref is a string-like name
    if ($normalized === null && is_string($pref) && $pref !== '') {
        $k = strtolower(trim($pref));
        if (isset($doctorMapByName[$k])) $normalized = $doctorMapByName[$k]['id'] ?? null;
    }

    if ($normalized && $normalized !== ($appt['preferred_doctor'] ?? null)) {
        $repo->update('appointments', $id, ['preferred_doctor' => $normalized]);
        echo "Updated appointment {$id}: preferred_doctor -> {$normalized}\n";
    }
}

echo "Done.\n";
