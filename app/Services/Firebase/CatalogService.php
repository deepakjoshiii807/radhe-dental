<?php

declare(strict_types=1);

namespace App\Services\Firebase;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

final class CatalogService
{
    public function __construct(private readonly FirestoreRepository $repository)
    {
    }

    public function services(): array
    {
        return Cache::remember('clinic.services', 3600, fn () => $this->repository->all('services') ?: config('clinic.services', []));
    }

    public function doctors(): array
    {
        return Cache::remember('clinic.doctors', 3600, fn () => $this->repository->all('doctors') ?: config('clinic.doctors', []));
    }

    public function testimonials(): array
    {
        return Cache::remember('clinic.testimonials', 3600, fn () => $this->repository->all('testimonials') ?: $this->defaultTestimonials());
    }

    public function gallery(): array
    {
        return Cache::remember('clinic.gallery', 3600, fn () => $this->repository->all('gallery') ?: $this->defaultGallery());
    }

    public function faqs(): array
    {
        return config('clinic.faq', []);
    }

    public function serviceBySlug(string $slug): ?array
    {
        foreach ($this->services() as $service) {
            if (Arr::get($service, 'slug') === $slug) {
                return $service;
            }
        }

        return null;
    }

    public function doctorById(string $id): ?array
    {
        foreach ($this->doctors() as $doctor) {
            if (Arr::get($doctor, 'id') === $id) {
                return $doctor;
            }
        }

        return null;
    }

    private function defaultTestimonials(): array
    {
        return [
            [
                'name' => 'Samantha K.',
                'role' => 'Patient',
                'message' => 'SmileCare made my treatment comfortable, modern, and reassuring from the first visit.',
            ],
            [
                'name' => 'Daniel R.',
                'role' => 'Parent',
                'message' => 'The pediatric team was warm and patient. My child actually enjoyed the appointment.',
            ],
        ];
    }

    private function defaultGallery(): array
    {
        return [
            ['title' => 'Reception Lounge', 'image' => 'https://images.unsplash.com/photo-1629909615184-74f2f1d07d6c?auto=format&fit=crop&w=1200&q=80'],
            ['title' => 'Treatment Suite', 'image' => 'https://images.unsplash.com/photo-1606265752439-ef7d0a5f2b1f?auto=format&fit=crop&w=1200&q=80'],
            ['title' => 'Smile Makeover', 'image' => 'https://images.unsplash.com/photo-1576013551627-0b7dfe71e7c8?auto=format&fit=crop&w=1200&q=80'],
        ];
    }
}
