<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'isbn', 'materia', 'curso', 'editora', 'quantidade_minima', 'current_stock', 'active'])]
class Book extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'quantidade_minima' => 'int',
            'current_stock' => 'int',
            'active' => 'boolean',
        ];
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function isCritical(): bool
    {
        return $this->current_stock < $this->quantidade_minima;
    }
}
