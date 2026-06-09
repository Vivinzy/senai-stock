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

class RetiradaPage extends Page
{
    use HasRoleBasedAccess;

    public static function canAccess(): bool
    {
        return static::hasRole('professor');
    }
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-shopping-bag';
    protected static UnitEnum|string|null $navigationGroup = 'Operações';
    protected static ?string $navigationLabel = 'Registrar Retirada';
    protected static ?string $slug = 'registrar-retirada';

    protected function getFormSchema(): array
    {
        $materia = auth()->user()?->materia;

        return [
            Forms\Components\Select::make('book_id')
                ->label('Livro')
                ->options(Book::query()
                    ->when($materia, fn ($query) => $query->where('materia', $materia))
                    ->where('active', true)
                    ->orderBy('title')
                    ->pluck('title', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('turma')
                ->label('Turma')
                ->required()
                ->maxLength(50),

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

        if ($state['quantidade'] > $book->current_stock) {
            Notification::make()
                ->danger()
                ->title('Estoque insuficiente')
                ->body('Não é possível registrar a retirada com a quantidade solicitada.')
                ->send();

            return;
        }

        $book->decrement('current_stock', $state['quantidade']);

        StockMovement::create([
            'book_id' => $book->id,
            'actor_id' => auth()->id(),
            'movement_type' => 'retirada',
            'quantidade' => $state['quantidade'],
            'turma' => $state['turma'],
            'observacao' => $state['observacao'] ?? null,
        ]);

        Notification::make()
            ->success()
            ->title('Retirada registrada com sucesso.')
            ->send();

        $this->form->fill([]);
    }
}
