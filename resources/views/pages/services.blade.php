@extends('layouts.app')

@section('title', 'Services | '.config('clinic.name'))

@section('content')
  <section class="section-shell py-16">
    <x-section-heading title="Our services" subtitle="Carefully designed treatments for prevention, restoration, and cosmetic enhancement." />
    <div class="mt-12 grid gap-8 md:grid-cols-2 xl:grid-cols-4">
      @foreach ($services as $service)
        <x-service-card :service="$service" />
      @endforeach
    </div>
  </section>
@endsection
