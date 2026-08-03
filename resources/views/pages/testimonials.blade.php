@extends('layouts.app')

@section('title', 'Testimonials | '.config('clinic.name'))

@section('content')
  <section class="section-shell py-16">
    <x-section-heading title="Testimonials" subtitle="Feedback from patients who experienced our preventive and cosmetic care." />
    <div class="mt-12 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
      @foreach ($testimonials as $testimonial)
        <x-testimonial-card :testimonial="$testimonial" />
      @endforeach
    </div>
  </section>
@endsection
