<!doctype html>
<html lang="en" class="scroll-smooth" x-data="{ navOpen: false }">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="Premium dental care in a modern, patient-first clinic.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 font-[Manrope] text-slate-900">
    @include('partials.loader')
    @include('partials.nav')

    <main class="py-10">
      <div class="section-shell">
        <div class="glass-panel p-8">
          @yield('content')
        </div>
      </div>
    </main>

    <div class="section-shell mb-12">
      <div class="glass-panel p-6">
        @include('partials.footer')
      </div>
    </div>

    @include('partials.flash')
    @stack('scripts')
  </body>
</html>
