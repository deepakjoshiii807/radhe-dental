<?php

declare(strict_types=1);

namespace App\Services\Firebase;

use Kreait\Firebase\Auth;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Factory;

final class FirebaseFactory
{
    public function auth(): Auth
    {
        return $this->factory()->createAuth();
    }

    public function firestore(): Firestore
    {
        return $this->factory()->createFirestore();
    }

    private function factory(): Factory
    {
        $factory = new Factory();

        $projectId = config('firebase.project_id');
        if (is_string($projectId) && $projectId !== '') {
            $factory = $factory->withProjectId($projectId);
        }

        $serviceAccount = config('firebase.service_account');
        if (is_array($serviceAccount) && $serviceAccount !== []) {
            $factory = $factory->withServiceAccount($serviceAccount);
        }

        return $factory;
    }
}
