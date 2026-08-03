@extends('layouts.auth')

@section('title', 'Admin Login | Radhe Dental Clinic')

@section('content')
  <div class="section-shell flex min-h-screen items-center justify-center py-12">
    <div class="grid w-full max-w-6xl gap-10 lg:grid-cols-2 lg:items-center">
      <div class="space-y-6 text-white" data-reveal>
        <x-ui.badge tone="teal">Admin access only</x-ui.badge>
        <h1 class="font-['Cormorant_Garamond'] text-6xl font-semibold tracking-tight">Radhe Admin Control Center</h1>
        <p class="max-w-xl text-lg leading-8 text-slate-300">Log in with your Firebase administrator account to manage appointments, availability, services, testimonials, and gallery content.</p>
        <div class="grid gap-4 sm:grid-cols-3">
          <div class="rounded-3xl bg-white/10 p-4 backdrop-blur">
            <p class="text-sm text-slate-300">Secure login</p>
            <p class="mt-2 font-semibold">Firebase Auth</p>
          </div>
          <div class="rounded-3xl bg-white/10 p-4 backdrop-blur">
            <p class="text-sm text-slate-300">Realtime data</p>
            <p class="mt-2 font-semibold">Firestore</p>
          </div>
          <div class="rounded-3xl bg-white/10 p-4 backdrop-blur">
            <p class="text-sm text-slate-300">Responsive UI</p>
            <p class="mt-2 font-semibold">Blade + Tailwind</p>
          </div>
        </div>
      </div>

      <div class="rounded-[2rem] border border-white/10 bg-white/95 p-8 text-slate-900 shadow-2xl backdrop-blur dark:bg-slate-900/95 dark:text-white" data-reveal>
        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-clinic-teal">Administrator sign in</p>
        <h2 class="mt-3 text-3xl font-semibold">Welcome back</h2>
        <form method="POST" action="{{ route('admin.login.store') }}" class="mt-8 space-y-5">
          @csrf
          <x-ui.input label="Email" name="email" type="email" />
          <x-ui.input label="Password" name="password" type="password" />
          <x-ui.button type="submit" class="w-full">Sign In</x-ui.button>
        </form>
      </div>
    </div>
  </div>
@endsection
