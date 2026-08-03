<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Firebase\CatalogService;
use Illuminate\Contracts\View\View;

final class PublicController extends Controller
{
    public function __construct(private readonly CatalogService $catalog)
    {
    }

    public function home(): View
    {
        return view('pages.home', $this->sharedData());
    }

    public function about(): View
    {
        return view('pages.about', $this->sharedData());
    }

    public function services(): View
    {
        return view('pages.services', $this->sharedData());
    }

    public function doctors(): View
    {
        return view('pages.doctors', $this->sharedData());
    }

    public function gallery(): View
    {
        return view('pages.gallery', $this->sharedData());
    }

    public function faq(): View
    {
        return view('pages.faq', $this->sharedData());
    }

    public function testimonials(): View
    {
        return view('pages.testimonials', $this->sharedData());
    }

    private function sharedData(): array
    {
        return [
            'services' => $this->catalog->services(),
            'doctors' => $this->catalog->doctors(),
            'testimonials' => $this->catalog->testimonials(),
            'gallery' => $this->catalog->gallery(),
            'faqs' => $this->catalog->faqs(),
            'stats' => config('clinic.stats', []),
        ];
    }
}
