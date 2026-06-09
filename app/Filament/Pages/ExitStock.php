<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Models\Book;
use App\Models\StockMovement;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use BackedEnum;
use UnitEnum;

class ExitStock extends Page
{
    use HasRoleBasedAccess;

    public static function canAccess(): bool
    {
        return static::hasAnyRole(['coordenador', 'secretaria']);
    }
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static UnitEnum|string|null $navigationGroup = 'Operações';
    protected static ?string $navigationLabel = 'Saída de Estoque';
    protected static ?string $slug = 'saida-estoque';

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('book_id')
                ->label('Livro')
                ->options(Book::query()->orderBy('title')->pluck('title', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\Select::make('performed_for_user_id')
                ->label('Professor')
                ->options(User::where('role', 'professor')->orderBy('name')->pluck('name', 'id'))
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
                ->title('Quantidade insuficiente no estoque.')
                ->body('A saída não pode ser registrada porque não há saldo suficiente.')
                ->send();

            return;
        }

        $book->decrement('current_stock', $state['quantidade']);

        StockMovement::create([
            'book_id' => $book->id,
            'actor_id' => auth()->id(),
            'performed_for_user_id' => $state['performed_for_user_id'],
            'movement_type' => 'saida',
            'quantidade' => $state['quantidade'],
            'turma' => $state['turma'],
            'observacao' => $state['observacao'] ?? null,
        ]);

        Notification::make()
            ->success()
            ->title('Saída registrada com sucesso.')
            ->send();

        $this->form->fill([]);
    }
}
