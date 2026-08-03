@extends('layouts.app')

@section('title', 'About | '.config('clinic.name'))

@section('content')
  <section class="section-shell py-16 lg:py-20">
    <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
      <div class="space-y-6" data-reveal>
        <x-ui.badge tone="blue">About {{ config('clinic.name') }}</x-ui.badge>
        <h1 class="font-['Cormorant_Garamond'] text-5xl font-semibold tracking-tight text-slate-900 dark:text-white">A clinic built around calm, trust, and clinical excellence.</h1>
        <p class="text-lg leading-8 text-slate-600 dark:text-slate-300">We combine premium interiors, gentle care, and modern dentistry so patients feel informed and comfortable at every step.</p>
      </div>
      <div class="premium-card" data-reveal>
        <img src="https://images.unsplash.com/photo-1588776814546-ec7b4f3e4d5b?auto=format&fit=crop&w=1400&q=80" alt="SmileCare clinic" class="h-[420px] w-full rounded-[1.75rem] object-cover">
      </div>
    </div>
  </section>

  <section class="section-shell py-10">
    <div class="grid gap-6 lg:grid-cols-3">
      <div class="premium-card">
        <p class="text-sm font-semibold text-clinic-blue">Mission</p>
        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">Deliver precise dentistry with compassionate communication and a design-led experience.</p>
      </div>
      <div class="premium-card">
        <p class="text-sm font-semibold text-clinic-blue">Vision</p>
        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">Be the most trusted neighborhood dental clinic for preventive and cosmetic care.</p>
      </div>
      <div class="premium-card">
        <p class="text-sm font-semibold text-clinic-blue">Approach</p>
        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">Listen first, explain clearly, and recommend treatment plans that suit the patient.</p>
      </div>
    </div>
  </section>
@endsection
