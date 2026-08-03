@props(['testimonial'])

<div class="premium-card h-full" data-reveal>
  <div class="flex items-center gap-3">
    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-clinic-blue/10 text-clinic-blue">
      <svg viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6"><path d="M7.17 6A5.001 5.001 0 0 0 2 11v6h6v-6H5a3 3 0 0 1 2.17-2.88A5 5 0 0 0 7.17 6Zm10 0A5.001 5.001 0 0 0 12 11v6h6v-6h-3a3 3 0 0 1 2.17-2.88A5 5 0 0 0 17.17 6Z"/></svg>
    </div>
    <div>
      <h3 class="font-semibold text-slate-900 dark:text-white">{{ $testimonial['name'] }}</h3>
      <p class="text-sm text-slate-500 dark:text-slate-400">{{ $testimonial['role'] }}</p>
    </div>
  </div>
  <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $testimonial['message'] }}</p>
  @if (! empty($testimonial['rating']))
    <div class="mt-4 flex gap-1 text-amber-400" aria-label="Rating {{ $testimonial['rating'] }} out of 5">
      @for ($i = 0; $i < (int) $testimonial['rating']; $i++)
        <span>★</span>
      @endfor
    </div>
  @endif
</div>
