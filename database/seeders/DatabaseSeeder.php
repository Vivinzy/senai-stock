<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Coordenador Teste',
            'email' => 'coordenador@senai.local',
            're' => 'RE00001',
            'role' => 'coordenador',
            'materia' => null,
            'active' => true,
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Secretária Teste',
            'email' => 'secretaria@senai.local',
            're' => 'RE00002',
            'role' => 'secretaria',
            'materia' => null,
            'active' => true,
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Professor Teste',
            'email' => 'professor@senai.local',
            're' => 'RE00003',
            'role' => 'professor',
            'materia' => 'Matemática',
            'active' => true,
            'password' => Hash::make('password'),
        ]);

        Book::create([
            'title' => 'Álgebra Básica',
            'isbn' => '978-1234567890',
            'materia' => 'Matemática',
            'curso' => 'Técnico em Informática',
            'editora' => 'Editora SENAI',
            'quantidade_minima' => 10,
            'current_stock' => 8,
            'active' => true,
        ]);

        Book::create([
            'title' => 'Português Técnico',
            'isbn' => '978-0987654321',
            'materia' => 'Língua Portuguesa',
            'curso' => 'Técnico em Química',
            'editora' => 'Editora SENAI',
            'quantidade_minima' => 10,
            'current_stock' => 15,
            'active' => true,
        ]);
    }
}
