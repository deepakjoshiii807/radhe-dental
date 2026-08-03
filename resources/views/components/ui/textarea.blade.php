@props(['label', 'name', 'rows' => 4, 'value' => null, 'placeholder' => null])

<div>
  <label class="premium-label" for="{{ $name }}">{{ $label }}</label>
  <textarea
    id="{{ $name }}"
    name="{{ $name }}"
    rows="{{ $rows }}"
    placeholder="{{ $placeholder }}"
    {{ $attributes->merge(['class' => 'premium-input']) }}
  >{{ old($name, $value) }}</textarea>
  @error($name)
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
  @enderror
</div>
