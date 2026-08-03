@extends('layouts.app')

@section('title', 'Doctors | '.config('clinic.name'))

@section('content')
  <section class="section-shell py-16">
    <x-section-heading title="Meet our doctors" subtitle="Experienced professionals with compassionate bedside manner and specialty training." />
    <div class="mt-12 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
      @foreach ($doctors as $doctor)
        <x-doctor-card :doctor="$doctor" />
      @endforeach
    </div>
  </section>
@endsection
