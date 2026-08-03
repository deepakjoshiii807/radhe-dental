@extends('layouts.app')

@section('title', config('clinic.name').' | Premium Dental Care')

@section('content')
  <section class="bg-hero-grid relative overflow-hidden">
    <div class="section-shell grid items-center gap-12 py-20 lg:grid-cols-2 lg:py-28">
      <div class="space-y-8" data-reveal>
        <x-ui.badge tone="teal">Premium patient care</x-ui.badge>
        <div class="space-y-5">
          <h1 class="max-w-2xl font-['Cormorant_Garamond'] text-5xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-6xl">A brighter smile starts with a calmer dental experience.</h1>
          <p class="max-w-xl text-lg leading-8 text-slate-600 dark:text-slate-300">{{ config('clinic.name') }} blends advanced dentistry, soft design, and thoughtful service to make every visit feel modern, clear, and stress-free.</p>
        </div>
        <div class="flex flex-wrap gap-4">
          <a href="{{ route('appointment.create') }}" class="premium-button">Book Appointment</a>
          <a href="{{ route('services') }}" class="premium-button-secondary">Explore Services</a>
        </div>
        <div class="grid gap-4 sm:grid-cols-3">
          @foreach (array_slice($stats, 0, 3) as $stat)
            <x-stat-card :label="$stat['label']" :value="$stat['value']" />
          @endforeach
        </div>
      </div>

      <div class="relative" data-reveal>
        <div class="absolute -left-6 top-12 h-32 w-32 rounded-full bg-clinic-teal/20 blur-3xl"></div>
        <div class="absolute -right-4 bottom-0 h-44 w-44 rounded-full bg-clinic-blue/20 blur-3xl"></div>
        <div class="glass-panel relative overflow-hidden p-4 animate-float">
          <img src="https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=1400&q=80" alt="Modern dental clinic" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'1200\' height=\'680\' viewBox=\'0 0 1200 680\'><rect fill=\'%23f8fafc\' width=\'100%\' height=\'100%\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%23647\' font-family=\'Arial, Helvetica, sans-serif\' font-size=\'24\'>Image unavailable</text></svg>' class="h-[540px] w-full rounded-[2rem] object-cover">
          <div class="absolute bottom-8 left-8 rounded-3xl bg-white/95 p-5 shadow-soft dark:bg-slate-900/95">
            <p class="text-sm font-semibold text-slate-500">Next availability</p>
            <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">Today 10:00 AM</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section-shell py-20">
    <x-section-heading title="Services designed for comfort and confidence" subtitle="From preventive care to full smile transformations, every service is planned with precision and a premium patient experience." />
    <div class="mt-12 grid gap-8 md:grid-cols-2 xl:grid-cols-4">
      @foreach (array_slice($services, 0, 4) as $service)
        <x-service-card :service="$service" />
      @endforeach
    </div>
  </section>

  <section class="section-shell py-10">
    <x-section-heading title="Meet the doctors" subtitle="Experienced specialists who combine clinical expertise with a calm, modern approach." />
    <div class="mt-12 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
      @foreach (array_slice($doctors, 0, 3) as $doctor)
        <x-doctor-card :doctor="$doctor" />
      @endforeach
    </div>
  </section>

  <section class="section-shell py-20">
    <div class="grid gap-10 rounded-[2rem] bg-slate-900 px-6 py-10 text-white shadow-soft lg:grid-cols-3 lg:px-10" data-reveal>
      <div class="lg:col-span-1">
        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-clinic-teal">Why choose us</p>
        <h2 class="mt-3 text-3xl font-semibold">Precision dentistry with a soft, reassuring touch.</h2>
      </div>
      <div class="grid gap-4 md:grid-cols-3 lg:col-span-2">
        <div class="rounded-3xl bg-white/10 p-5 backdrop-blur">
          <p class="font-semibold">Modern equipment</p>
          <p class="mt-2 text-sm text-slate-300">Digital workflows, clean diagnostics, and efficient treatment planning.</p>
        </div>
        <div class="rounded-3xl bg-white/10 p-5 backdrop-blur">
          <p class="font-semibold">Comfort first</p>
          <p class="mt-2 text-sm text-slate-300">Gentle care, clear communication, and a relaxing environment.</p>
        </div>
        <div class="rounded-3xl bg-white/10 p-5 backdrop-blur">
          <p class="font-semibold">Trusted specialists</p>
          <p class="mt-2 text-sm text-slate-300">Senior doctors with years of experience across specialties.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section-shell py-20">
    <x-section-heading title="What patients say" subtitle="Real experiences from families and individuals who trusted {{ config('clinic.name') }} with their oral health." />
    <div class="mt-12 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
      @foreach (array_slice($testimonials, 0, 3) as $testimonial)
        <x-testimonial-card :testimonial="$testimonial" />
      @endforeach
    </div>
  </section>

  <section class="section-shell py-20">
    <div class="flex flex-wrap items-end justify-between gap-6">
      <x-section-heading title="Gallery preview" subtitle="A look inside our thoughtfully designed clinic spaces and treatment rooms." />
      <a href="{{ route('gallery') }}" class="premium-button-secondary">View full gallery</a>
    </div>
    <div class="mt-12 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
      @foreach (array_slice($gallery, 0, 3) as $item)
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900" data-reveal>
          <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'600\' height=\'400\' viewBox=\'0 0 600 400\'><rect fill=\'%23f8fafc\' width=\'100%\' height=\'100%\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%23647\' font-family=\'Arial, Helvetica, sans-serif\' font-size=\'18\'>Image unavailable</text></svg>' class="h-72 w-full object-cover">
          <div class="p-5">
            <p class="text-sm font-semibold text-clinic-blue">{{ $item['title'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  <section class="section-shell py-20">
    <div class="grid gap-8 rounded-[2rem] bg-clinic-blue px-8 py-12 text-white lg:grid-cols-2 lg:items-center" data-reveal>
      <div>
        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-teal-200">Ready to visit?</p>
        <h2 class="mt-3 text-4xl font-semibold">Book your consultation in just a few clicks.</h2>
      </div>
      <div class="flex flex-wrap gap-4 lg:justify-end">
        <a href="{{ route('appointment.create') }}" class="premium-button bg-white text-slate-900 hover:bg-slate-100">Book Appointment</a>
      </div>
    </div>
  </section>
@endsection
