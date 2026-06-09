<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\StockMovementResource\Pages;
use App\Models\StockMovement;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class StockMovementResource extends Resource
{
    use HasRoleBasedAccess;

    public static function canAccess(): bool
    {
        return static::hasAnyRole(['coordenador', 'secretaria']);
    }
    protected static ?string $model = StockMovement::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static UnitEnum|string|null $navigationGroup = 'Histórico';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Data')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('actor.name')->label('Usuário')->sortable(),
                TextColumn::make('performedFor.name')->label('Para')->sortable(),
                TextColumn::make('book.title')->label('Livro')->sortable()->searchable(),
                TextColumn::make('movement_type')->label('Tipo')->sortable(),
                TextColumn::make('quantidade')->label('Quantidade')->sortable(),
                TextColumn::make('turma')->label('Turma')->sortable(),
                TextColumn::make('observacao')->label('Observação')->limit(30),
                BadgeColumn::make('movement_type')
                    ->colors(['success' => 'entrada', 'warning' => 'saida', 'danger' => 'retirada']),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockMovements::route('/'),
        ];
    }
}
