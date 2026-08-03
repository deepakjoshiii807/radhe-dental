@props(['label', 'name', 'type' => 'text', 'value' => null, 'placeholder' => null])

<div>
  <label class="premium-label" for="{{ $name }}">{{ $label }}</label>
  <input
    id="{{ $name }}"
    name="{{ $name }}"
    type="{{ $type }}"
    value="{{ old($name, $value) }}"
    placeholder="{{ $placeholder }}"
    {{ $attributes->merge(['class' => 'premium-input']) }}
  >
  @error($name)
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
  @enderror
</div>
