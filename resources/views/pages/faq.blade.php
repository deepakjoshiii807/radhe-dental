@extends('layouts.app')

@section('title', 'FAQ | '.config('clinic.name'))

@section('content')
  <section class="section-shell py-16">
    <x-section-heading title="Frequently asked questions" subtitle="Helpful answers for new and returning patients." />
    <div class="mt-12 space-y-4">
      @foreach ($faqs as $faq)
        <details class="premium-card group" data-reveal>
          <summary class="cursor-pointer list-none text-lg font-semibold text-slate-900 dark:text-white">{{ $faq['question'] }}</summary>
          <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $faq['answer'] }}</p>
        </details>
      @endforeach
    </div>
  </section>
@endsection
