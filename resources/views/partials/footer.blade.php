<footer class="mt-20 border-t border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-950">
  <div class="section-shell grid gap-10 py-12 lg:grid-cols-4">
    <div class="space-y-4 lg:col-span-2">
      <h2 class="font-['Cormorant_Garamond'] text-4xl font-semibold text-slate-900 dark:text-white">{{ config('clinic.name') }}</h2>
      <p class="max-w-xl text-sm leading-7 text-slate-600 dark:text-slate-300">Modern dentistry with a calm, premium experience for families, professionals, and children.</p>
    </div>
    <div>
      <p class="text-sm font-semibold uppercase tracking-[0.28em] text-clinic-teal">Contact</p>
      <p class="mt-4 text-sm text-slate-600 dark:text-slate-300">{{ config('clinic.address') }}</p>
      <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ config('clinic.phone') }}</p>
      <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ config('clinic.email') }}</p>
    </div>
    <div>
      <p class="text-sm font-semibold uppercase tracking-[0.28em] text-clinic-teal">Hours</p>
      <p class="mt-4 text-sm text-slate-600 dark:text-slate-300">Mon - Sat: 9:00 AM - 7:00 PM</p>
      <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Sun: Emergency appointments</p>
    </div>
  </div>
  <div class="border-t border-slate-200 py-4 text-center text-sm text-slate-500 dark:border-slate-800 dark:text-slate-400">
    © {{ date('Y') }} {{ config('clinic.name') }}. All rights reserved.
  </div>
</footer>
