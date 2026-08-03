<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/85 backdrop-blur dark:border-slate-800 dark:bg-slate-950/80">
  <div class="section-shell flex items-center justify-between py-4">
    <a href="{{ route('home') }}" class="flex items-center gap-3">
      <span class="flex h-16 w-16 items-center justify-center rounded-none bg-clinic-blue text-white shadow-lg shadow-blue-500/25">
        <span class="text-3xl font-black leading-none tracking-tight">RD</span>
      </span>
      <div>
        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-clinic-teal">Radhe</p>
        <p class="text-sm text-slate-500 dark:text-slate-400">Dental Clinic</p>
      </div>
    </a>

    <nav class="hidden items-center gap-7 text-sm font-semibold lg:flex">
      <a href="{{ route('home') }}" class="transition hover:text-clinic-blue">Home</a>
      <a href="{{ route('about') }}" class="transition hover:text-clinic-blue">About</a>
      <a href="{{ route('services') }}" class="transition hover:text-clinic-blue">Services</a>
      <a href="{{ route('doctors') }}" class="transition hover:text-clinic-blue">Doctors</a>
      <a href="{{ route('gallery') }}" class="transition hover:text-clinic-blue">Gallery</a>
      <a href="{{ route('faq') }}" class="transition hover:text-clinic-blue">FAQ</a>
      <button data-theme-toggle class="rounded-full border border-slate-200 px-4 py-2 text-slate-700 transition hover:border-clinic-blue hover:text-clinic-blue dark:border-slate-700 dark:text-slate-200">Dark</button>
      <a href="{{ route('appointment.create') }}" class="premium-button">Book Appointment</a>
    </nav>

    <button type="button" class="rounded-full border border-slate-200 p-3 lg:hidden" onclick="document.getElementById('mobile-nav').classList.toggle('hidden')" aria-label="Toggle navigation">
      <span class="block h-0.5 w-5 bg-slate-900 dark:bg-white"></span>
      <span class="mt-1 block h-0.5 w-5 bg-slate-900 dark:bg-white"></span>
      <span class="mt-1 block h-0.5 w-5 bg-slate-900 dark:bg-white"></span>
    </button>
  </div>

  <div id="mobile-nav" class="section-shell hidden pb-5 lg:hidden">
    <div class="glass-panel grid gap-2 p-4 text-sm font-semibold">
      <a href="{{ route('home') }}" class="rounded-2xl px-4 py-3 hover:bg-blue-50">Home</a>
      <a href="{{ route('about') }}" class="rounded-2xl px-4 py-3 hover:bg-blue-50">About</a>
      <a href="{{ route('services') }}" class="rounded-2xl px-4 py-3 hover:bg-blue-50">Services</a>
      <a href="{{ route('doctors') }}" class="rounded-2xl px-4 py-3 hover:bg-blue-50">Doctors</a>
      <a href="{{ route('gallery') }}" class="rounded-2xl px-4 py-3 hover:bg-blue-50">Gallery</a>
      <a href="{{ route('faq') }}" class="rounded-2xl px-4 py-3 hover:bg-blue-50">FAQ</a>
      <a href="{{ route('appointment.create') }}" class="premium-button text-center">Book Appointment</a>
    </div>
  </div>
</header>
