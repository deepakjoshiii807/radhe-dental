@php
  $hasErrors = session('success') || session('error') || $errors->any();
@endphp

@if ($hasErrors)
  <div class="toast-container fixed bottom-6 right-6 z-50 max-w-sm space-y-3">
    @if ($errors->any())
      <div data-toast class="toast-panel toast-error">
        <p class="text-sm font-semibold text-rose-900">Something went wrong</p>
        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-slate-700">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if (session('success'))
      <div data-toast class="toast-panel toast-success">
        <p class="text-sm font-semibold text-emerald-900">Success</p>
        <p class="mt-2 text-sm text-slate-700">{{ session('success') }}</p>
      </div>
    @endif

    @if (session('error'))
      <div data-toast class="toast-panel toast-error">
        <p class="text-sm font-semibold text-rose-900">Error</p>
        <p class="mt-2 text-sm text-slate-700">{{ session('error') }}</p>
      </div>
    @endif
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toasts = document.querySelectorAll('[data-toast]');
      toasts.forEach((toast) => {
        toast.classList.add('toast-visible');
        setTimeout(() => toast.classList.remove('toast-visible'), 6000);
      });
    });
  </script>
@endif
