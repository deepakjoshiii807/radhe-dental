@extends('layouts.admin')

@section('title', 'Appointments | Radhe Admin')

@section('content')
  <div class="space-y-6">
    <div class="admin-card">
      <form method="GET" class="grid gap-4 lg:grid-cols-4">
        <x-ui.input label="Search" name="search" :value="$search" placeholder="Name, phone, or email" />
        <x-ui.select label="Status" name="status" :options="['' => 'All', 'Pending' => 'Pending', 'Approved' => 'Approved', 'Cancelled' => 'Cancelled']" :selected="$filters['status'] ?? ''" />
        <x-ui.select label="Doctor" name="doctor" :options="array_merge(['' => 'All'], collect($doctors)->mapWithKeys(fn ($doctor) => [$doctor['id'] => $doctor['name']])->all())" :selected="$filters['preferred_doctor'] ?? ''" />
        <x-ui.input label="Date" name="date" type="date" :value="$filters['appointment_date'] ?? ''" />
        <div class="lg:col-span-4 flex gap-3">
          <x-ui.button type="submit">Filter</x-ui.button>
          <a href="{{ route('admin.appointments.index') }}" class="premium-button-secondary">Reset</a>
        </div>
      </form>
    </div>

    <div class="admin-card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
          <thead class="text-xs uppercase tracking-[0.2em] text-slate-500">
            <tr>
              <th class="py-3 pr-4">Patient</th>
              <th class="py-3 pr-4">Contact</th>
              <th class="py-3 pr-4">Doctor</th>
              <th class="py-3 pr-4">Date / Time</th>
              <th class="py-3 pr-4">Status</th>
              <th class="py-3 pr-4">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
            @forelse ($appointments as $appointment)
              <tr>
              @php
                $pref = data_get($appointment, 'preferred_doctor');
                if (is_array($pref)) {
                  $doctorName = data_get($pref, 'name', data_get($pref, 'id', null));
                } else {
                  $doctorName = data_get($doctorMap, $pref, $pref ?? null);
                }

                if (is_array($doctorName)) {
                  if (isset($doctorName['name']) && is_string($doctorName['name'])) {
                    $doctorName = $doctorName['name'];
                  } else {
                    $flatten = array_filter($doctorName, static fn($v) => is_string($v) || is_numeric($v));
                    $doctorName = $flatten ? implode(' ', $flatten) : json_encode($doctorName);
                  }
                }

                $doctorName = (string) ($doctorName ?? '-');
                $status = data_get($appointment, 'status', 'Pending');
              @endphp
                <td class="py-4 pr-4">
                  <div class="font-semibold text-slate-900 dark:text-white">{{ $appointment['patient_name'] ?? 'Unknown' }}</div>
                  <div class="text-xs text-slate-500">{{ $appointment['gender'] ?? '-' }}, {{ $appointment['age'] ?? '-' }} years</div>
                </td>
                <td class="py-4 pr-4 text-slate-600 dark:text-slate-300">
                  <div>{{ $appointment['phone'] ?? '-' }}</div>
                  <div class="text-xs">{{ $appointment['email'] ?? '-' }}</div>
                </td>
                <td class="py-4 pr-4 text-slate-600 dark:text-slate-300">{{ $doctorName }}</td>
                <td class="py-4 pr-4 text-slate-600 dark:text-slate-300">
                  <div>{{ $appointment['preferred_date'] ?? '-' }}</div>
                  <div class="text-xs">{{ $appointment['preferred_time'] ?? '-' }}</div>
                </td>
                <td class="py-4 pr-4"><x-ui.badge :tone="$status === 'Approved' ? 'teal' : ($status === 'Cancelled' ? 'amber' : 'blue')">{{ $status }}</x-ui.badge></td>
                <td class="py-4 pr-4">
                  <div class="flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('admin.appointments.approve', $appointment['id']) }}">
                      @csrf
                      @method('PATCH')
                      <x-ui.button type="submit" variant="secondary">Approve</x-ui.button>
                    </form>
                    <form method="POST" action="{{ route('admin.appointments.reject', $appointment['id']) }}">
                      @csrf
                      @method('PATCH')
                      <x-ui.button type="submit" variant="secondary">Reject</x-ui.button>
                    </form>
                    <form method="POST" action="{{ route('admin.appointments.destroy', $appointment['id']) }}" onsubmit="return confirm('Delete this appointment?')">
                      @csrf
                      @method('DELETE')
                      <x-ui.button type="submit" variant="secondary">Delete</x-ui.button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-10 text-center text-slate-500">No appointments found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
