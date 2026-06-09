<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['book_id', 'actor_id', 'performed_for_user_id', 'movement_type', 'quantidade', 'turma', 'observacao'])]
class StockMovement extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'quantidade' => 'int',
        ];
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function performedFor()
    {
        return $this->belongsTo(User::class, 'performed_for_user_id');
    }
}
