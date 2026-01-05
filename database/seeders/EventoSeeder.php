<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evento;

class EventoSeeder extends Seeder
{
    public function run(): void
    {
            Evento::factory(20)->create()->each(function ($evento) {
            // Seleciona aleatoriamente 3-10 voluntários para associar como participantes
            $voluntarios = User::whereIn('tipo_usuario', ['voluntario_adm', 'voluntario_ext'])
            ->inRandomOrder()
            ->take(rand(3, 10))
            ->pluck('id');
            $evento->participantes()->sync($voluntarios);
        });   
    }
}