<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Services\Firebase\AppointmentService;
use App\Services\Firebase\CatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

final class AppointmentController extends Controller
{
    public function __construct(
        private readonly AppointmentService $appointments,
        private readonly CatalogService $catalog,
    ) {
    }

    public function create(): View
    {
        $doctors = collect($this->catalog->doctors());
        $services = collect($this->catalog->services());
        $defaultDoctor = $doctors->first()['id'] ?? null;

        return view('pages.book-appointment', [
            'doctors' => $doctors,
            'services' => $services,
            'defaultDoctor' => $defaultDoctor,
            'defaultSlots' => $defaultDoctor ? $this->appointments->availabilityFor($defaultDoctor, now()->toDateString()) : [],
        ] + $this->sharedData());
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->appointments->store($data);

        return redirect()
            ->route('appointment.create')
            ->with('success', 'Your appointment request has been submitted successfully. Our team will contact you soon.');
    }

    public function availability(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'doctor_id' => ['required', 'string', 'max:120'],
            'date' => ['nullable', 'date'],
        ]);

        return response()->json([
            'slots' => $this->appointments->availabilityFor($payload['doctor_id'], $payload['date'] ?? null),
        ]);
    }

    private function sharedData(): array
    {
        return [
            'doctors' => collect($this->catalog->doctors()),
            'services' => collect($this->catalog->services()),
        ];
    }
}
