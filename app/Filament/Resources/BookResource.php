<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class BookResource extends Resource
{
    use HasRoleBasedAccess;

    public static function canAccess(): bool
    {
        return static::hasAnyRole(['coordenador', 'secretaria']);
    }
    protected static ?string $model = Book::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';

    protected static UnitEnum|string|null $navigationGroup = 'Estoque';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('isbn')
                    ->required()
                    ->maxLength(32),

                Forms\Components\TextInput::make('materia')
                    ->required()
                    ->maxLength(120),

                Forms\Components\TextInput::make('curso')
                    ->required()
                    ->maxLength(120),

                Forms\Components\TextInput::make('editora')
                    ->required()
                    ->maxLength(120),

                Forms\Components\TextInput::make('quantidade_minima')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(10),

                Forms\Components\TextInput::make('current_stock')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),

                Forms\Components\Toggle::make('active')
                    ->label('Ativo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('isbn')->searchable()->sortable(),
                TextColumn::make('materia')->sortable(),
                TextColumn::make('curso')->sortable(),
                TextColumn::make('editora')->sortable(),
                TextColumn::make('current_stock')->label('Estoque Atual')->sortable(),
                TextColumn::make('quantidade_minima')->label('Mínimo')->sortable(),
                BadgeColumn::make('active')
                    ->label('Status')
                    ->enum([true => 'Ativo', false => 'Inativo'])
                    ->colors(['success' => true, 'danger' => false]),
                BadgeColumn::make('critical')
                    ->label('Crítico')
                    ->getStateUsing(fn (Book $record): string => $record->isCritical() ? 'Sim' : 'Não')
                    ->colors(['danger' => 'Sim', 'success' => 'Não']),
            ])
            ->filters([
                TernaryFilter::make('active')
                    ->label('Ativo')
                    ->trueLabel('Ativos')
                    ->falseLabel('Inativos'),
            ])
            ->defaultSort('title');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
