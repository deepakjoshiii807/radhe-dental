<?php

declare(strict_types=1);

namespace App\Services\Firebase;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

final class AppointmentService
{
    public function __construct(
        private readonly FirestoreRepository $repository,
    ) {
    }

    public function list(array $filters = []): array
    {
        return $this->repository->all('appointments', $filters);
    }

    public function store(array $payload): string
    {
        $payload['status'] = 'Pending';
        $payload['appointment_date'] = Carbon::parse($payload['preferred_date'])->toDateString();
        $payload['appointment_time'] = $payload['preferred_time'];
        $payload['search_index'] = strtolower(trim(($payload['patient_name'] ?? '').' '.($payload['phone'] ?? '').' '.($payload['email'] ?? '')));

        $this->assertSlotAvailable($payload['preferred_doctor'], $payload['preferred_date'], $payload['preferred_time']);

        return $this->repository->create('appointments', $payload);
    }

    public function approve(string $id): void
    {
        $this->repository->update('appointments', $id, ['status' => 'Approved']);
    }

    public function reject(string $id): void
    {
        $this->repository->update('appointments', $id, ['status' => 'Cancelled']);
    }

    public function delete(string $id): void
    {
        $this->repository->delete('appointments', $id);
    }

    public function dashboardCounters(): array
    {
        $appointments = $this->repository->all('appointments');

        return [
            'today' => count(array_filter($appointments, static fn (array $appointment): bool => Arr::get($appointment, 'appointment_date') === Carbon::today()->toDateString())),
            'pending' => count(array_filter($appointments, static fn (array $appointment): bool => Arr::get($appointment, 'status') === 'Pending')),
            'approved' => count(array_filter($appointments, static fn (array $appointment): bool => Arr::get($appointment, 'status') === 'Approved')),
            'cancelled' => count(array_filter($appointments, static fn (array $appointment): bool => Arr::get($appointment, 'status') === 'Cancelled')),
            'total' => count($appointments),
        ];
    }

    public function availabilityFor(string $doctorId, ?string $date = null): array
    {
        $availability = $this->repository->find('availability', $doctorId) ?? [];
        $doctor = $this->repository->find('doctors', $doctorId) ?? [];
        $selectedDate = $date ? Carbon::parse($date) : Carbon::today();
        $dayName = $selectedDate->format('l');

        $availableDays = $availability['available_days'] ?? $doctor['available_days'] ?? [];
        $timeSlots = $availability['available_time_slots'] ?? ['09:00 AM', '10:00 AM', '11:00 AM', '02:00 PM', '03:00 PM', '04:00 PM'];
        $unavailableDates = $availability['unavailable_dates'] ?? [];

        if (! in_array($dayName, $availableDays, true) || in_array($selectedDate->toDateString(), $unavailableDates, true)) {
            return [];
        }

        return array_values($timeSlots);
    }

    public function assertSlotAvailable(string $doctorId, string $date, string $time): void
    {
        $availableSlots = $this->availabilityFor($doctorId, $date);

        if (! in_array($time, $availableSlots, true)) {
            throw ValidationException::withMessages([
                'preferred_time' => 'The selected time slot is not available for the chosen doctor and date.',
            ]);
        }
    }
}
