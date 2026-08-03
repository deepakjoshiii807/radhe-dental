<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', config('clinic.name')) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="bg-slate-50 text-slate-900">
    <div class="section-shell flex min-h-screen items-center justify-center">
      <div class="glass-panel max-w-xl p-10 text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-clinic-teal">{{ config('clinic.name') }}</p>
        <h1 class="mt-4 text-4xl font-semibold text-slate-900">Laravel 12 project scaffold is ready.</h1>
        <p class="mt-4 text-slate-600">The public site, appointment flow, and admin dashboard are included in this workspace.</p>
      </div>
    </div>
  </body>
</html>
