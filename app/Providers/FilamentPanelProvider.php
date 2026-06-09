<?php

namespace App\Providers;

use App\Filament\Pages\CriticalStock;
use App\Filament\Pages\CoordinatorDashboard;
use App\Filament\Pages\EntryStock;
use App\Filament\Pages\ExitStock;
use App\Filament\Pages\ProfessorDashboard;
use App\Filament\Pages\ProfessorHistory;
use App\Filament\Pages\RetiradaPage;
use App\Filament\Pages\SecretaryDashboard;
use App\Filament\Resources\BookResource;
use App\Filament\Resources\StockMovementResource;
use App\Filament\Resources\UserResource;
use Filament\Panel;
use Filament\PanelProvider;

class FilamentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('default')
            ->default()
            ->path('filament')
            ->login()
            ->homeUrl(fn (): string => (
                auth()->check()
                    ? match (auth()->user()->role ?? null) {
                        'coordenador' => route('filament.default.pages.coordenador-dashboard'),
                        'secretaria' => route('filament.default.pages.secretaria-dashboard'),
                        'professor' => route('filament.default.pages.professor-dashboard'),
                        default => route('filament.default.home'),
                    }
                    : url('/filament/login')
            ))
            ->resources([
                BookResource::class,
                UserResource::class,
                StockMovementResource::class,
            ])
            ->pages([
                CoordinatorDashboard::class,
                SecretaryDashboard::class,
                ProfessorDashboard::class,
                CriticalStock::class,
                EntryStock::class,
                ExitStock::class,
                RetiradaPage::class,
                ProfessorHistory::class,
            ]);
    }
}
