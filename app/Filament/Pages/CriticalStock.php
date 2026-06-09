<?php

namespace App\Filament\Pages;

use App\Models\Book;
use App\Filament\Concerns\HasRoleBasedAccess;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use BackedEnum;
use UnitEnum;

class CriticalStock extends Page implements HasTable
{
    use HasRoleBasedAccess;

    public static function canAccess(): bool
    {
        return static::hasAnyRole(['coordenador', 'secretaria']);
    }
    use InteractsWithTable;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static UnitEnum|string|null $navigationGroup = 'Relatórios';
    protected static ?string $navigationLabel = 'Estoque Crítico';
    protected static ?string $slug = 'estoque-critico';

    protected function getTableQuery()
    {
        return Book::query()->whereColumn('current_stock', '<', 'quantidade_minima');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')->label('Livro')->searchable()->sortable(),
            TextColumn::make('current_stock')->label('Saldo Atual')->sortable(),
            TextColumn::make('quantidade_minima')->label('Quantidade Mínima')->sortable(),
            BadgeColumn::make('status')
                ->label('Status')
                ->getStateUsing(fn () => 'Crítico')
                ->colors(['danger' => 'Crítico']),
        ];
    }
}
