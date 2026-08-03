@props(['title', 'subtitle' => null, 'align' => 'left'])

<div class="{{ $align === 'center' ? 'mx-auto text-center' : '' }} max-w-3xl">
  <p class="text-sm font-semibold uppercase tracking-[0.28em] text-clinic-teal">{{ config('clinic.name') }}</p>
  <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-4xl">{{ $title }}</h2>
  @if ($subtitle)
    <p class="mt-4 text-base leading-7 text-slate-600 dark:text-slate-300">{{ $subtitle }}</p>
  @endif
</div>
