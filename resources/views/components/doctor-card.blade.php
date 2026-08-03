@props(['doctor'])

<div class="premium-card overflow-hidden" data-reveal>
  <div class="h-72 overflow-hidden rounded-2xl">
    <img src="{{ $doctor['photo'] }}" alt="{{ $doctor['name'] }}" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'600\' height=\'600\' viewBox=\'0 0 600 600\'><rect fill=\'%23f8fafc\' width=\'100%\' height=\'100%\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%23647\' font-family=\'Arial, Helvetica, sans-serif\' font-size=\'18\'>Photo unavailable</text></svg>' class="h-full w-full object-cover">
  </div>
  <div class="mt-5 space-y-3">
    <div class="flex items-start justify-between gap-4">
      <div>
        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $doctor['name'] }}</h3>
        <p class="text-sm font-medium text-clinic-blue">{{ $doctor['specialization'] }}</p>
      </div>
      <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-clinic-teal dark:bg-teal-950/40">{{ $doctor['experience'] }}</span>
    </div>
    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $doctor['qualification'] }}</p>
    <div class="flex flex-wrap gap-2">
      @foreach ($doctor['available_days'] ?? [] as $day)
        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $day }}</span>
      @endforeach
    </div>
    <a href="{{ route('appointment.create') }}" class="premium-button-secondary w-full">Book Appointment</a>
  </div>
</div>
