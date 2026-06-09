<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Models\Book;
use App\Models\StockMovement;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class ProfessorDashboard extends Page
{
    use HasRoleBasedAccess;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';
    protected static string | UnitEnum | null $navigationGroup = 'Dashboard';
    protected static ?string $navigationLabel = 'Painel do Professor';
    protected static ?string $slug = 'professor-dashboard';
    protected string $view = 'filament.pages.dashboard';

    public static function canAccess(): bool
    {
        return static::hasRole('professor');
    }

    protected function getViewData(): array
    {
        $user = auth()->user();
        $myRetiradas = StockMovement::where('actor_id', $user->id)->where('movement_type', 'retirada')->count();
        $monthlyRetiradas = StockMovement::where('actor_id', $user->id)->where('movement_type', 'retirada')->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $availableBooks = Book::where('active', true)->when($user->materia, fn ($query) => $query->where('materia', $user->materia))->count();

        return [
            'heading' => 'Painel do Professor',
            'subheading' => 'Resumo de retiradas e livros disponíveis para sua matéria.',
            'cards' => [
                ['label' => 'Retiradas totais', 'value' => $myRetiradas, 'description' => 'Livros registrados por você.'],
                ['label' => 'Retiradas este mês', 'value' => $monthlyRetiradas, 'description' => 'Registro de retiradas do mês atual.'],
                ['label' => 'Livros disponíveis', 'value' => $availableBooks, 'description' => 'Estoque ativo para sua matéria.'],
                ['label' => 'Matéria', 'value' => $user->materia ?? '—', 'description' => 'Assunto vinculado ao seu perfil.'],
            ],
        ];
    }
}
