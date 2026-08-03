<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Firebase\CatalogService;
use App\Services\Firebase\DashboardService;
use Illuminate\Contracts\View\View;

final class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboard,
        private readonly CatalogService $catalog,
    )
    {
    }

    public function index(): View
    {
        return view('admin.dashboard.index', $this->dashboard->overview() + [
            'doctorMap' => collect($this->catalog->doctors())->mapWithKeys(static fn (array $doctor): array => [$doctor['id'] => $doctor['name']])->all(),
        ]);
    }
}
