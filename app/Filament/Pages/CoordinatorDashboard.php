<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Models\Book;
use App\Models\StockMovement;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class CoordinatorDashboard extends Page
{
    use HasRoleBasedAccess;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static string | UnitEnum | null $navigationGroup = 'Dashboard';
    protected static ?string $navigationLabel = 'Painel do Coordenador';
    protected static ?string $slug = 'coordenador-dashboard';
    protected string $view = 'filament.pages.dashboard';

    public static function canAccess(): bool
    {
        return static::hasRole('coordenador');
    }

    protected function getViewData(): array
    {
        $totalBooks = Book::count();
        $criticalBooks = Book::whereColumn('current_stock', '<', 'quantidade_minima')->count();
        $activeUsers = User::where('active', true)->count();
        $professorCount = User::where('role', 'professor')->count();
        $monthlyMovements = StockMovement::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();

        return [
            'heading' => 'Painel do Coordenador',
            'subheading' => 'Visão geral do estoque, usuários e movimentações do sistema.',
            'cards' => [
                ['label' => 'Livros cadastrados', 'value' => $totalBooks, 'description' => 'Total de títulos no sistema.'],
                ['label' => 'Livros em estoque crítico', 'value' => $criticalBooks, 'description' => 'Itens abaixo da quantidade mínima.'],
                ['label' => 'Usuários ativos', 'value' => $activeUsers, 'description' => 'Contagem de contas ativas.'],
                ['label' => 'Professores cadastrados', 'value' => $professorCount, 'description' => 'Total de professores.'],
                ['label' => 'Movimentações neste mês', 'value' => $monthlyMovements, 'description' => 'Entradas, saídas e retiradas recentes.'],
            ],
        ];
    }
}
