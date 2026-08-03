<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Firebase\AdminContentService;
use App\Services\Firebase\CatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

final class ContentController extends Controller
{
    public function __construct(
        private readonly AdminContentService $content,
        private readonly CatalogService $catalog,
    ) {
    }

    public function index(string $collection): View
    {
        $editingId = request()->string('edit')->toString() ?: null;

        return view('admin.content.index', [
            'meta' => $this->meta($collection),
            'collection' => $collection,
            'items' => $this->content->list($collection),
            'doctors' => $this->catalog->doctors(),
            'editingId' => $editingId,
            'editingItem' => $editingId ? $this->content->find($collection, $editingId) : null,
        ]);
    }

    public function store(Request $request, string $collection): RedirectResponse
    {
        $validated = $request->validate($this->rules($collection));
        $payload = $this->payload($collection, $validated);
        $this->content->save($collection, $payload, $this->documentId($collection, $payload));

        return back()->with('success', $this->meta($collection)['singular'].' saved successfully.');
    }

    public function update(Request $request, string $collection, string $id): RedirectResponse
    {
        $validated = $request->validate($this->rules($collection));
        $payload = $this->payload($collection, $validated);
        $this->content->save($collection, $payload, $id);

        return back()->with('success', $this->meta($collection)['singular'].' updated successfully.');
    }

    public function destroy(string $collection, string $id): RedirectResponse
    {
        $this->content->delete($collection, $id);

        return back()->with('success', $this->meta($collection)['singular'].' deleted successfully.');
    }

    private function meta(string $collection): array
    {
        return match ($collection) {
            'services' => ['singular' => 'Service', 'title' => 'Manage Services'],
            'testimonials' => ['singular' => 'Testimonial', 'title' => 'Manage Testimonials'],
            'gallery' => ['singular' => 'Gallery Item', 'title' => 'Manage Gallery'],
            'availability' => ['singular' => 'Availability', 'title' => 'Manage Doctor Availability'],
            default => ['singular' => ucfirst($collection), 'title' => 'Manage '.ucfirst($collection)],
        };
    }

    private function rules(string $collection): array
    {
        return match ($collection) {
            'services' => [
                'name' => ['required', 'string', 'max:150'],
                'slug' => ['required', 'string', 'max:160', 'alpha_dash'],
                'description' => ['required', 'string', 'max:500'],
                'benefits' => ['required', 'string', 'max:1000'],
                'price' => ['required', 'string', 'max:60'],
                'image' => ['required', 'url', 'max:2048'],
            ],
            'testimonials' => [
                'name' => ['required', 'string', 'max:120'],
                'role' => ['required', 'string', 'max:120'],
                'message' => ['required', 'string', 'max:1000'],
                'rating' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
                'avatar' => ['nullable', 'url', 'max:2048'],
            ],
            'gallery' => [
                'title' => ['required', 'string', 'max:150'],
                'image' => ['required', 'url', 'max:2048'],
                'category' => ['required', 'string', 'max:120'],
            ],
            'availability' => [
                'doctor_id' => ['required', 'string', 'max:150'],
                'available_days' => ['required', 'string', 'max:300'],
                'available_time_slots' => ['required', 'string', 'max:500'],
                'unavailable_dates' => ['nullable', 'string', 'max:500'],
            ],
            default => [],
        };
    }

    private function payload(string $collection, array $validated): array
    {
        return match ($collection) {
            'services' => [
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'benefits' => $this->lines($validated['benefits']),
                'price' => $validated['price'],
                'image' => $validated['image'],
            ],
            'testimonials' => [
                'name' => $validated['name'],
                'role' => $validated['role'],
                'message' => $validated['message'],
                'rating' => (int) $validated['rating'],
                'avatar' => $validated['avatar'] ?? null,
            ],
            'gallery' => [
                'title' => $validated['title'],
                'image' => $validated['image'],
                'category' => $validated['category'],
            ],
            'availability' => [
                'doctor_id' => $validated['doctor_id'],
                'available_days' => $this->lines($validated['available_days']),
                'available_time_slots' => $this->lines($validated['available_time_slots']),
                'unavailable_dates' => $this->lines($validated['unavailable_dates'] ?? ''),
            ],
            default => $validated,
        };
    }

    private function documentId(string $collection, array $payload): ?string
    {
        if ($collection === 'availability') {
            return $payload['doctor_id'] ?? null;
        }

        if ($collection === 'services') {
            return $payload['slug'] ?? null;
        }

        return null;
    }

    private function lines(string $value): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $value) ?: [])));
    }
}
