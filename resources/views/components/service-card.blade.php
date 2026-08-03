@props(['service'])

@php
  $benefits = $service['benefits'] ?? [];
@endphp

<div class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-soft transition duration-300 hover:-translate-y-1 hover:shadow-2xl dark:border-slate-800 dark:bg-slate-900" data-reveal>
  <div class="h-56 overflow-hidden">
    <img src="{{ $service['image'] }}" alt="{{ $service['name'] }}" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'800\' height=\'450\' viewBox=\'0 0 800 450\'><rect fill=\'%23f3f4f6\' width=\'100%\' height=\'100%\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%2364748B\' font-family=\'Arial, Helvetica, sans-serif\' font-size=\'20\'>Image unavailable</text></svg>' class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
  </div>
  <div class="space-y-4 p-6">
    <div class="flex items-start justify-between gap-4">
      <div>
        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $service['name'] }}</h3>
        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $service['description'] }}</p>
      </div>
      <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-clinic-blue dark:bg-blue-950/50">{{ $service['price'] ?? 'Price on request' }}</span>
    </div>
    @if ($benefits)
      <div class="flex flex-wrap gap-2">
        @foreach ($benefits as $benefit)
          <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $benefit }}</span>
        @endforeach
      </div>
    @endif
    <a href="{{ route('appointment.create') }}" class="premium-button w-full">Book Appointment</a>
  </div>
</div>
