<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\UserStats;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class AdminDashboard extends Page
{
    protected string $view = 'filament.pages.admin-dashboard';

    public function mount(): void
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak punya akses ke halaman ini.');
        }
    }

    public function getWidgets(): array
    {
        return [
            UserStats::class,
        ];
    }
}
