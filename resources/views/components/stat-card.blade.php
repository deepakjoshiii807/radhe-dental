@props(['label', 'value'])

<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft dark:border-slate-800 dark:bg-slate-900">
  <div class="flex items-end justify-between gap-4">
    <div>
      <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $label }}</p>
      <p class="mt-2 text-4xl font-semibold text-slate-900 dark:text-white" data-counter="{{ $value }}">0</p>
    </div>
    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-clinic-blue dark:bg-blue-950/40">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-6 w-6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
    </div>
  </div>
</div>
