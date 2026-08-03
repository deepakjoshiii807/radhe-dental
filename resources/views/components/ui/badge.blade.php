@props(['tone' => 'blue'])

@php
  $toneClass = match ($tone) {
    'teal' => 'bg-teal-50 text-clinic-teal dark:bg-teal-950/40 dark:text-teal-300',
    'amber' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300',
    default => 'bg-blue-50 text-clinic-blue dark:bg-blue-950/40 dark:text-blue-300',
  };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold '.$toneClass]) }}>
  {{ $slot }}
</span>
