@props(['variant' => 'primary', 'type' => 'button'])

@php
  $classes = $variant === 'secondary' ? 'premium-button-secondary' : 'premium-button';
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</button>
