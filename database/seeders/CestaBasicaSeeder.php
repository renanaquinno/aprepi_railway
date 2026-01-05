<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CestaBasica;
use App\Models\User;

class CestaBasicaSeeder extends Seeder
{
    public function run(): void
    {
        // Garante que há usuários antes de criar cestas básicas
        if (User::count() == 0) {
            $this->command->warn('Nenhum usuário encontrado. Criando 10 usuários para associar às cestas básicas...');
            User::factory(10)->create();
        }

        // Cria 50 cestas básicas aleatórias
        CestaBasica::factory(50)->create();

        $this->command->info('Seed de Cestas Básicas concluído com sucesso!');
    }
}
