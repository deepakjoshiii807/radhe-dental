<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Firebase\AppointmentService;
use App\Services\Firebase\CatalogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AppointmentController extends Controller
{
    public function __construct(
        private readonly AppointmentService $appointments,
        private readonly CatalogService $catalog,
    ) {
    }

    public function index(Request $request): View
    {
        $filters = array_filter([
            'status' => $request->string('status')->toString() ?: null,
            'preferred_doctor' => $request->string('doctor')->toString() ?: null,
            'appointment_date' => $request->string('date')->toString() ?: null,
        ]);

        $search = trim($request->string('search')->toString());
        $appointments = $this->appointments->list();

        if ($filters !== []) {
            $appointments = array_values(array_filter($appointments, static function (array $appointment) use ($filters): bool {
                foreach ($filters as $key => $value) {
                    if (($appointment[$key] ?? null) !== $value) {
                        return false;
                    }
                }

                return true;
            }));
        }

        if ($search !== '') {
            $appointments = array_values(array_filter($appointments, static fn (array $appointment): bool => str_contains(strtolower((string) ($appointment['search_index'] ?? '')), strtolower($search))));
        }

        return view('admin.appointments.index', [
            'appointments' => $appointments,
            'doctors' => $this->catalog->doctors(),
            'doctorMap' => collect($this->catalog->doctors())->mapWithKeys(static fn (array $doctor): array => [$doctor['id'] => $doctor['name']])->all(),
            'filters' => $filters,
            'search' => $search,
        ]);
    }

    public function approve(string $id): RedirectResponse
    {
        $this->appointments->approve($id);

        return back()->with('success', 'Appointment approved successfully.');
    }

    public function reject(string $id): RedirectResponse
    {
        $this->appointments->reject($id);

        return back()->with('success', 'Appointment rejected successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->appointments->delete($id);

        return back()->with('success', 'Appointment deleted successfully.');
    }
}
