<?php

declare(strict_types=1);

namespace App\Services\Firebase;

final class DashboardService
{
    public function __construct(
        private readonly AppointmentService $appointments,
        private readonly FirestoreRepository $repository,
    ) {
    }

    public function overview(): array
    {
        $counters = $this->appointments->dashboardCounters();
        $appointments = $this->repository->all('appointments');
        $doctors = $this->repository->all('doctors');
        $patientCount = count(array_unique(array_filter(array_map(static function (array $appointment): string {
            return strtolower(trim(($appointment['email'] ?? '').'|'.($appointment['phone'] ?? '')));
        }, $appointments))));

        return [
            'cards' => [
                ['label' => 'Appointments Today', 'value' => $counters['today']],
                ['label' => 'Pending', 'value' => $counters['pending']],
                ['label' => 'Approved', 'value' => $counters['approved']],
                ['label' => 'Cancelled', 'value' => $counters['cancelled']],
                ['label' => 'Doctors', 'value' => count($doctors)],
                ['label' => 'Patients', 'value' => $patientCount],
            ],
            'recentAppointments' => array_slice($appointments, 0, 8),
        ];
    }
}
