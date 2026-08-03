<div id="page-loader" class="fixed inset-0 z-[60] flex items-center justify-center bg-white dark:bg-slate-950">
  <div class="flex flex-col items-center gap-4">
    <div class="h-14 w-14 animate-spin rounded-full border-4 border-slate-200 border-t-clinic-blue"></div>
    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-clinic-teal">Loading {{ config('clinic.name') }}</p>
  </div>
</div>
<script>
  window.addEventListener('load', () => {
    document.getElementById('page-loader')?.classList.add('opacity-0', 'pointer-events-none');
    window.setTimeout(() => document.getElementById('page-loader')?.remove(), 400);
  });
</script>
