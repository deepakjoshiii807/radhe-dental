<!doctype html>
<html lang="en" class="scroll-smooth">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin | '.config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 font-[Manrope] text-slate-900">
    <div class="min-h-screen lg:flex">
      <aside class="glass-panel px-6 py-6 lg:sticky lg:top-6 lg:h-[calc(100vh-48px)] lg:w-80 lg:border-b-0 lg:border-r">
        <div class="flex items-center justify-between lg:block">
          <div>
              <p class="text-xs font-semibold uppercase tracking-[0.3em] text-clinic-teal">{{ config('clinic.name') }} Admin</p>
              <h1 class="mt-2 font-['Cormorant_Garamond'] text-3xl font-semibold text-slate-900">Dashboard</h1>
            </div>
          <!-- dark mode disabled for consistent styling -->
        </div>

        <nav class="mt-8 space-y-2 text-sm font-medium">
          <a href="{{ route('admin.dashboard') }}" class="block rounded-2xl px-4 py-3 hover:bg-blue-50 hover:text-clinic-blue">Overview</a>
          <a href="{{ route('admin.appointments.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-blue-50 hover:text-clinic-blue">Appointments</a>
          <a href="{{ route('admin.content.index', 'services') }}" class="block rounded-2xl px-4 py-3 hover:bg-blue-50 hover:text-clinic-blue">Services</a>
          <a href="{{ route('admin.content.index', 'availability') }}" class="block rounded-2xl px-4 py-3 hover:bg-blue-50 hover:text-clinic-blue">Availability</a>
          <a href="{{ route('admin.content.index', 'testimonials') }}" class="block rounded-2xl px-4 py-3 hover:bg-blue-50 hover:text-clinic-blue">Testimonials</a>
          <a href="{{ route('admin.content.index', 'gallery') }}" class="block rounded-2xl px-4 py-3 hover:bg-blue-50 hover:text-clinic-blue">Gallery</a>
        </nav>

        <div class="mt-8 rounded-3xl bg-slate-50 p-5 dark:bg-slate-800/60">
          <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ config('clinic.name') }}</p>
          <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ config('clinic.address') }}</p>
          <form class="mt-4" method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <x-ui.button type="submit" class="w-full">Logout</x-ui.button>
          </form>
        </div>
      </aside>

      <div class="flex-1 p-4 sm:p-6 lg:p-8">
        <div class="glass-panel p-6">
          @include('partials.flash')
          @yield('content')
        </div>
      </div>
    </div>
    @stack('scripts')
  </body>
</html>
