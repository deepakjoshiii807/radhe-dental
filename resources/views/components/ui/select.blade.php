@props(['label', 'name', 'options' => [], 'selected' => null])

<div>
  <label class="premium-label" for="{{ $name }}">{{ $label }}</label>
  <select id="{{ $name }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'premium-input']) }}>
    @foreach ($options as $value => $text)
      <option value="{{ $value }}" @selected(old($name, $selected) == $value)>{{ $text }}</option>
    @endforeach
  </select>
  @error($name)
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
  @enderror
</div>
