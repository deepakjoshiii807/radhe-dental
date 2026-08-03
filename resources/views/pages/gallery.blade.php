@extends('layouts.app')

@section('title', 'Gallery | '.config('clinic.name'))

@section('content')
  <section class="section-shell py-16">
    <x-section-heading title="Gallery" subtitle="A closer look at our reception, treatment rooms, and patient experience." />
    <div class="mt-12 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
      @foreach ($gallery as $item)
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900" data-reveal>
          <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'800\' height=\'480\' viewBox=\'0 0 800 480\'><rect fill=\'%23f8fafc\' width=\'100%\' height=\'100%\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%23647\' font-family=\'Arial, Helvetica, sans-serif\' font-size=\'18\'>Image unavailable</text></svg>' class="h-72 w-full object-cover transition duration-500 hover:scale-105">
          <div class="p-5">
            <p class="text-sm font-semibold text-clinic-blue">{{ $item['title'] }}</p>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $item['category'] ?? 'Clinic Experience' }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </section>
@endsection
