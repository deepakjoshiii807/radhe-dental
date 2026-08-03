@extends('layouts.app')

@section('title', 'Book Appointment | '.config('clinic.name'))

@section('content')
  <section class="section-shell py-16">
    <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr]">
      <div class="space-y-6" data-reveal>
        <x-section-heading title="Book an appointment" subtitle="Choose the doctor, treatment, and time that works best for you." />
        <div class="grid gap-4 sm:grid-cols-2">
          @foreach ($doctors as $doctor)
            <div class="premium-card">
              <p class="font-semibold text-slate-900 dark:text-white">{{ $doctor['name'] }}</p>
              <p class="mt-1 text-sm text-clinic-blue">{{ $doctor['specialization'] }}</p>
                <?php $availableDays = $doctor['available_days'] ?? [];
                  if (is_string($availableDays)) {
                    $availableDays = array_filter(array_map('trim', explode(',', $availableDays)));
                  }
                ?>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ implode(', ', (array) $availableDays) }}</p>
            </div>
          @endforeach
        </div>
      </div>

      <div class="premium-card" data-reveal>
        <form method="POST" action="{{ route('appointment.store') }}" class="space-y-5" data-availability-form data-availability-url="{{ route('appointment.availability') }}">
          @csrf
          <x-ui.input label="Patient Name" name="patient_name" />
          <div class="grid gap-4 sm:grid-cols-2">
            <x-ui.input label="Phone" name="phone" />
            <x-ui.input label="Email" name="email" type="email" />
          </div>
          <div class="grid gap-4 sm:grid-cols-2">
            <x-ui.input label="Age" name="age" type="number" min="1" />
            <x-ui.select label="Gender" name="gender" :options="['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other']" />
          </div>
          <x-ui.select label="Preferred Doctor" name="preferred_doctor" :options="$doctors->mapWithKeys(fn ($doctor) => [$doctor['id'] => $doctor['name'].' - '.$doctor['specialization']])->all()" :selected="$defaultDoctor" data-availability-doctor />
          <x-ui.select label="Treatment" name="treatment" :options="$services->mapWithKeys(fn ($service) => [$service['name'] => $service['name']])->all()" />
          <div class="grid gap-4 sm:grid-cols-2">
            <x-ui.input label="Preferred Date" name="preferred_date" type="date" data-availability-date />
            <div>
              <label class="premium-label" for="preferred_time">Preferred Time</label>
              <select id="preferred_time" name="preferred_time" class="premium-input">
                @foreach ($defaultSlots as $slot)
                  <option value="{{ $slot }}">{{ $slot }}</option>
                @endforeach
              </select>
              @error('preferred_time')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
          </div>
          <x-ui.textarea label="Message" name="message" rows="4" />
          <x-ui.button type="submit" class="w-full">Submit Appointment Request</x-ui.button>
        </form>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-availability-form]');
    if (!form) return;

    const url = form.getAttribute('data-availability-url');
    const doctorField = form.querySelector('[data-availability-doctor]');
    const dateField = form.querySelector('[data-availability-date]');
    const timeSelect = form.querySelector('#preferred_time');

    const loadSlots = async () => {
      if (!doctorField?.value) return;
      const formData = new FormData();
      formData.append('doctor_id', doctorField.value);
      if (dateField?.value) formData.append('date', dateField.value);

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
        },
        body: formData,
      });

      const data = await response.json();
      timeSelect.innerHTML = '';
      (data.slots || []).forEach((slot) => {
        const option = document.createElement('option');
        option.value = slot;
        option.textContent = slot;
        timeSelect.appendChild(option);
      });
      if (!timeSelect.options.length) {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'No available slots';
        timeSelect.appendChild(option);
      }
    };

    doctorField?.addEventListener('change', loadSlots);
    dateField?.addEventListener('change', loadSlots);
  });
</script>
@endpush
