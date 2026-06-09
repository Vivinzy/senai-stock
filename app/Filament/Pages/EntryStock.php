<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Models\Book;
use App\Models\StockMovement;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use BackedEnum;
use UnitEnum;

class EntryStock extends Page
{
    use HasRoleBasedAccess;

    public static function canAccess(): bool
    {
        return static::hasAnyRole(['coordenador', 'secretaria']);
    }
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static UnitEnum|string|null $navigationGroup = 'Operações';
    protected static ?string $navigationLabel = 'Entrada de Estoque';
    protected static ?string $slug = 'entrada-estoque';

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('book_id')
                ->label('Livro')
                ->options(Book::query()->orderBy('title')->pluck('title', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('quantidade')
                ->label('Quantidade')
                ->numeric()
                ->required()
                ->minValue(1),

            Forms\Components\Textarea::make('observacao')
                ->label('Observação')
                ->maxLength(65535),
        ];
    }

    public function submit(): void
    {
        $state = $this->form->getState();

        $book = Book::findOrFail($state['book_id']);
        $book->increment('current_stock', $state['quantidade']);

        StockMovement::create([
            'book_id' => $book->id,
            'actor_id' => auth()->id(),
            'movement_type' => 'entrada',
            'quantidade' => $state['quantidade'],
            'turma' => null,
            'observacao' => $state['observacao'] ?? null,
        ]);

        Notification::make()
            ->success()
            ->title('Entrada registrada com sucesso.')
            ->send();

        $this->form->fill([]);
    }
}
