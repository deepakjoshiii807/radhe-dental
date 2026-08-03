@extends('layouts.admin')

@section('title', 'Dashboard | Radhe Admin')

@section('content')
  <div class="space-y-8">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
      @foreach ($cards as $card)
        <x-stat-card :label="$card['label']" :value="$card['value']" />
      @endforeach
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
      <div class="admin-card overflow-hidden">
        <div class="flex items-center justify-between gap-4 border-b border-slate-200 pb-4 dark:border-slate-800">
          <div>
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">Recent appointments</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Latest submissions from the public appointment form.</p>
          </div>
          <a href="{{ route('admin.appointments.index') }}" class="premium-button-secondary">View all</a>
        </div>

        <div class="mt-5 overflow-x-auto">
          <table class="min-w-full text-left text-sm">
            <thead class="text-xs uppercase tracking-[0.2em] text-slate-500">
              <tr>
                <th class="py-3 pr-4">Patient</th>
                <th class="py-3 pr-4">Doctor</th>
                <th class="py-3 pr-4">Date</th>
                <th class="py-3 pr-4">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
              @foreach ($recentAppointments as $appointment)
                @php
                  $pref = data_get($appointment, 'preferred_doctor');
                  if (is_array($pref)) {
                    $doctorName = data_get($pref, 'name', data_get($pref, 'id', null));
                  } else {
                    $doctorName = data_get($doctorMap, $pref, $pref ?? null);
                  }

                  // Ensure $doctorName is a string for safe HTML escaping
                  if (is_array($doctorName)) {
                    if (isset($doctorName['name']) && is_string($doctorName['name'])) {
                      $doctorName = $doctorName['name'];
                    } else {
                      $flatten = array_filter($doctorName, static fn($v) => is_string($v) || is_numeric($v));
                      $doctorName = $flatten ? implode(' ', $flatten) : json_encode($doctorName);
                    }
                  }

                  $doctorName = (string) ($doctorName ?? '-');
                @endphp

                <tr>
                  <td class="py-4 pr-4 text-slate-900 dark:text-white">{{ data_get($appointment, 'patient_name', 'Unknown') }}</td>
                  <td class="py-4 pr-4 text-slate-600 dark:text-slate-300">{{ $doctorName }}</td>
                  <td class="py-4 pr-4 text-slate-600 dark:text-slate-300">{{ data_get($appointment, 'preferred_date', '-') }}</td>
                  <td class="py-4 pr-4">
                    <x-ui.badge :tone="(data_get($appointment, 'status') === 'Approved') ? 'teal' : ((data_get($appointment, 'status') === 'Cancelled') ? 'amber' : 'blue')">{{ data_get($appointment, 'status', 'Pending') }}</x-ui.badge>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div class="admin-card space-y-4">
        <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">Quick actions</h2>
        <a href="{{ route('admin.appointments.index') }}" class="block rounded-2xl bg-blue-50 px-4 py-4 text-sm font-semibold text-clinic-blue dark:bg-blue-950/30 dark:text-blue-300">Review appointments</a>
        <a href="{{ route('admin.content.index', 'services') }}" class="block rounded-2xl bg-teal-50 px-4 py-4 text-sm font-semibold text-clinic-teal dark:bg-teal-950/30 dark:text-teal-300">Update services</a>
        <a href="{{ route('admin.content.index', 'availability') }}" class="block rounded-2xl bg-slate-100 px-4 py-4 text-sm font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">Adjust availability</a>
      </div>
    </div>
  </div>
@endsection
