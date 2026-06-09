<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Models\Book;
use App\Models\StockMovement;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class SecretaryDashboard extends Page
{
    use HasRoleBasedAccess;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-folder-open';
    protected static string | UnitEnum | null $navigationGroup = 'Dashboard';
    protected static ?string $navigationLabel = 'Painel da Secretaria';
    protected static ?string $slug = 'secretaria-dashboard';
    protected string $view = 'filament.pages.dashboard';

    public static function canAccess(): bool
    {
        return static::hasRole('secretaria');
    }

    protected function getViewData(): array
    {
        $activeBooks = Book::where('active', true)->count();
        $criticalBooks = Book::whereColumn('current_stock', '<', 'quantidade_minima')->count();
        $booksBySubject = Book::groupBy('materia')->selectRaw('materia, count(*) as total')->orderByDesc('total')->limit(3)->get();
        $monthlyMovements = StockMovement::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $pendingRetiradas = StockMovement::where('movement_type', 'retirada')->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();

        return [
            'heading' => 'Painel da Secretaria',
            'subheading' => 'Informações operacionais de estoque e retiradas para o time administrativo.',
            'cards' => [
                ['label' => 'Livros ativos', 'value' => $activeBooks, 'description' => 'Itens disponíveis para empréstimo.'],
                ['label' => 'Estoque crítico', 'value' => $criticalBooks, 'description' => 'Produtos com estoque abaixo do mínimo.'],
                ['label' => 'Retiradas neste mês', 'value' => $pendingRetiradas, 'description' => 'Solicitações registradas de professores.'],
                ['label' => 'Movimentações no mês', 'value' => $monthlyMovements, 'description' => 'Entradas, saídas e retiradas.'],
            ],
            'rows' => $booksBySubject->map(fn ($book) => ['label' => $book->materia, 'value' => $book->total])->all(),
            'rowHeading' => 'Top 3 matérias com mais livros',
        ];
    }
}
