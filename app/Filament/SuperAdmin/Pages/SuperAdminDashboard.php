<?php

namespace App\Filament\SuperAdmin\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class SuperAdminDashboard extends Page
{
    protected string $view = 'filament.super-admin.pages.super-admin-dashboard';

    public function mount(): void
    {
        if (! Auth::check()) {
            abort(403);
        }
    }
}
