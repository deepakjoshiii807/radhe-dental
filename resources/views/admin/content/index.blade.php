@extends('layouts.admin')

@section('title', $meta['title'].' | Radhe Admin')

@section('content')
  @php
    $editing = $editingItem ?? null;
    $action = $editing ? route('admin.content.update', [$collection, $editing['id']]) : route('admin.content.store', $collection);
  @endphp

  <div class="grid gap-6 xl:grid-cols-[0.95fr_1.05fr]">
    <div class="admin-card">
      <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $meta['title'] }}</h2>
      <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Create or update clinic content without leaving the dashboard.</p>

      <form method="POST" action="{{ $action }}" class="mt-6 space-y-5">
        @csrf
        @if ($editing)
          @method('PATCH')
        @endif

        @if ($collection === 'services')
          <x-ui.input label="Name" name="name" :value="$editing['name'] ?? ''" />
          <x-ui.input label="Slug" name="slug" :value="$editing['slug'] ?? ''" />
          <x-ui.textarea label="Description" name="description" :value="$editing['description'] ?? ''" />
          <x-ui.textarea label="Benefits" name="benefits" :value="isset($editing['benefits']) ? implode("\n", $editing['benefits']) : ''" rows="4" />
          <x-ui.input label="Price" name="price" :value="$editing['price'] ?? ''" placeholder="$120+" />
          <x-ui.input label="Image URL" name="image" :value="$editing['image'] ?? ''" />
        @elseif ($collection === 'testimonials')
          <x-ui.input label="Name" name="name" :value="$editing['name'] ?? ''" />
          <x-ui.input label="Role" name="role" :value="$editing['role'] ?? ''" />
          <x-ui.textarea label="Message" name="message" :value="$editing['message'] ?? ''" />
          <x-ui.input label="Rating" name="rating" type="number" min="1" max="5" :value="$editing['rating'] ?? 5" />
          <x-ui.input label="Avatar URL" name="avatar" :value="$editing['avatar'] ?? ''" />
        @elseif ($collection === 'gallery')
          <x-ui.input label="Title" name="title" :value="$editing['title'] ?? ''" />
          <x-ui.input label="Category" name="category" :value="$editing['category'] ?? ''" />
          <x-ui.input label="Image URL" name="image" :value="$editing['image'] ?? ''" />
        @elseif ($collection === 'availability')
          <x-ui.select label="Doctor" name="doctor_id" :options="collect($doctors)->mapWithKeys(fn ($doctor) => [$doctor['id'] => $doctor['name']])->all()" :selected="$editing['doctor_id'] ?? ''" />
          <x-ui.textarea label="Available Days" name="available_days" :value="isset($editing['available_days']) ? implode("\n", $editing['available_days']) : ''" placeholder="Monday, Wednesday, Friday" />
          <x-ui.textarea label="Available Time Slots" name="available_time_slots" :value="isset($editing['available_time_slots']) ? implode("\n", $editing['available_time_slots']) : ''" placeholder="09:00 AM, 10:00 AM" />
          <x-ui.textarea label="Unavailable Dates" name="unavailable_dates" :value="isset($editing['unavailable_dates']) ? implode("\n", $editing['unavailable_dates']) : ''" placeholder="2026-08-15, 2026-08-16" />
        @endif

        <div class="flex gap-3">
          <x-ui.button type="submit">{{ $editing ? 'Update' : 'Save' }} {{ $meta['singular'] }}</x-ui.button>
          @if ($editing)
            <a href="{{ route('admin.content.index', $collection) }}" class="premium-button-secondary">Cancel</a>
          @endif
        </div>
      </form>
    </div>

    <div class="admin-card overflow-hidden">
      <h3 class="text-2xl font-semibold text-slate-900 dark:text-white">Existing records</h3>
      <div class="mt-5 overflow-x-auto">
        <table class="min-w-full text-left text-sm">
          <thead class="text-xs uppercase tracking-[0.2em] text-slate-500">
            <tr>
              @if ($collection === 'services')
                <th class="py-3 pr-4">Name</th><th class="py-3 pr-4">Price</th><th class="py-3 pr-4">Actions</th>
              @elseif ($collection === 'testimonials')
                <th class="py-3 pr-4">Name</th><th class="py-3 pr-4">Role</th><th class="py-3 pr-4">Actions</th>
              @elseif ($collection === 'gallery')
                <th class="py-3 pr-4">Title</th><th class="py-3 pr-4">Category</th><th class="py-3 pr-4">Actions</th>
              @else
                <th class="py-3 pr-4">Doctor</th><th class="py-3 pr-4">Days</th><th class="py-3 pr-4">Actions</th>
              @endif
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
            @forelse ($items as $item)
              <tr>
                @if ($collection === 'services')
                  <td class="py-4 pr-4 text-slate-900 dark:text-white">{{ $item['name'] ?? '-' }}</td>
                  <td class="py-4 pr-4 text-slate-600 dark:text-slate-300">{{ $item['price'] ?? '-' }}</td>
                @elseif ($collection === 'testimonials')
                  <td class="py-4 pr-4 text-slate-900 dark:text-white">{{ $item['name'] ?? '-' }}</td>
                  <td class="py-4 pr-4 text-slate-600 dark:text-slate-300">{{ $item['role'] ?? '-' }}</td>
                @elseif ($collection === 'gallery')
                  <td class="py-4 pr-4 text-slate-900 dark:text-white">{{ $item['title'] ?? '-' }}</td>
                  <td class="py-4 pr-4 text-slate-600 dark:text-slate-300">{{ $item['category'] ?? '-' }}</td>
                @else
                  <td class="py-4 pr-4 text-slate-900 dark:text-white">{{ data_get(collect($doctors)->firstWhere('id', $item['doctor_id'] ?? ''), 'name', $item['doctor_id'] ?? '-') }}</td>
                  <td class="py-4 pr-4 text-slate-600 dark:text-slate-300">{{ implode(', ', $item['available_days'] ?? []) }}</td>
                @endif
                <td class="py-4 pr-4">
                  <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.content.index', [$collection, 'edit' => $item['id']]) }}" class="premium-button-secondary">Edit</a>
                    <form method="POST" action="{{ route('admin.content.destroy', [$collection, $item['id']]) }}" onsubmit="return confirm('Delete this record?')">
                      @csrf
                      @method('DELETE')
                      <x-ui.button type="submit" variant="secondary">Delete</x-ui.button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="py-10 text-center text-slate-500">No records found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
