<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use BackedEnum;
use UnitEnum;

class UserResource extends Resource
{
    use HasRoleBasedAccess;

    public static function canAccess(): bool
    {
        return static::hasRole('coordenador');
    }
    protected static ?string $model = User::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-users';

    protected static UnitEnum|string|null $navigationGroup = 'Usuários';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('re')
                    ->label('RE')
                    ->required()
                    ->maxLength(50),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),

                Forms\Components\Select::make('role')
                    ->required()
                    ->options([
                        'coordenador' => 'Coordenador',
                        'secretaria' => 'Secretaria',
                        'professor' => 'Professor',
                    ])
                    ->reactive(),

                Forms\Components\TextInput::make('materia')
                    ->maxLength(120)
                    ->visible(fn (?User $record, callable $get) => $get('role') === 'professor'),

                Forms\Components\Toggle::make('active')
                    ->label('Ativo')
                    ->default(true),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn (?string $state) => $state ? Hash::make($state) : null)
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->label('Senha')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('re')->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->sortable(),
                TextColumn::make('role')->sortable(),
                TextColumn::make('materia')->sortable(),
                BadgeColumn::make('active')
                    ->label('Status')
                    ->enum([true => 'Ativo', false => 'Inativo'])
                    ->colors(['success' => true, 'danger' => false]),
            ])
            ->filters([
                TernaryFilter::make('active')
                    ->label('Ativo')
                    ->trueLabel('Ativos')
                    ->falseLabel('Inativos'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
