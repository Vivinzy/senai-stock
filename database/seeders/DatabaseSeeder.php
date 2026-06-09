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
        // Use updateOrCreate to avoid unique constraint errors when seeding multiple times
        User::updateOrCreate([
            're' => 'RE00001',
        ], [
            'name' => 'Coordenador Teste',
            'email' => 'coordenador@senai.local',
            'role' => 'coordenador',
            'materia' => null,
            'active' => true,
            'password' => Hash::make('password'),
        ]);

        User::updateOrCreate([
            're' => 'RE00002',
        ], [
            'name' => 'Secretária Teste',
            'email' => 'secretaria@senai.local',
            'role' => 'secretaria',
            'materia' => null,
            'active' => true,
            'password' => Hash::make('password'),
        ]);

        User::updateOrCreate([
            're' => 'RE00003',
        ], [
            'name' => 'Professor Teste',
            'email' => 'professor@senai.local',
            'role' => 'professor',
            'materia' => 'Matemática',
            'active' => true,
            'password' => Hash::make('password'),
        ]);

        Book::updateOrCreate([
            'isbn' => '978-1234567890',
        ], [
            'title' => 'Álgebra Básica',
            'materia' => 'Matemática',
            'curso' => 'Técnico em Informática',
            'editora' => 'Editora SENAI',
            'quantidade_minima' => 10,
            'current_stock' => 8,
            'active' => true,
        ]);

        Book::updateOrCreate([
            'isbn' => '978-0987654321',
        ], [
            'title' => 'Português Técnico',
            'materia' => 'Língua Portuguesa',
            'curso' => 'Técnico em Química',
            'editora' => 'Editora SENAI',
            'quantidade_minima' => 10,
            'current_stock' => 15,
            'active' => true,
        ]);
    }
}
