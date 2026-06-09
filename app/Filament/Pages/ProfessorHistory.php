<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Models\StockMovement;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use BackedEnum;
use UnitEnum;

class ProfessorHistory extends Page implements HasTable
{
    use HasRoleBasedAccess;

    public static function canAccess(): bool
    {
        return static::hasRole('professor');
    }
    use InteractsWithTable;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-archive';
    protected static UnitEnum|string|null $navigationGroup = 'Histórico';
    protected static ?string $navigationLabel = 'Meu Histórico';
    protected static ?string $slug = 'meu-historico';

    protected function getTableQuery()
    {
        return StockMovement::query()
            ->where('actor_id', auth()->id())
            ->where('movement_type', 'retirada')
            ->orderByDesc('created_at');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('created_at')->label('Data')->dateTime('d/m/Y H:i')->sortable(),
            TextColumn::make('book.title')->label('Livro')->sortable()->searchable(),
            TextColumn::make('quantidade')->label('Quantidade')->sortable(),
            TextColumn::make('turma')->label('Turma')->sortable(),
            TextColumn::make('observacao')->label('Observação')->limit(50),
        ];
    }
}
