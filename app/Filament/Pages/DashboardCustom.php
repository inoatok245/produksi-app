<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DashboardCustom extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard-custom';
    protected static ?string $title = 'Beranda';
}
