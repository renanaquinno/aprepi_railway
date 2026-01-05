<?php
namespace Database\Seeders;

use App\Models\Doacao;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoacaoSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = User::all();

        if ($usuarios->isEmpty()) {
            $this->command->info('Nenhum usuário encontrado. Crie usuários antes de rodar o seeder de doações.');
            return;
        }

        foreach ($usuarios as $usuario) {
            Doacao::factory()->count(rand(3, 10))->create([
                'user_id' => $usuario->id,
            ]);
        }
    }
}
