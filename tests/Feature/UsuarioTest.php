<?php

use App\Models\User;

test('lista usuários com sucesso', function () {
    // Cria alguns usuários no banco (usa o factory)
    User::factory()->count(3)->create();

    // Faz uma requisição GET para a rota /usuarios (ajuste para a rota real do seu sistema)
    $response = $this->get('/usuarios');

    // Verifica se retornou status 200
    $response->assertStatus(200);

    // Verifica se o JSON tem 3 itens
    $response->assertJsonCount(3);

    // Verifica se o JSON tem o formato esperado (ajuste os campos conforme seu User)
    $response->assertJsonStructure([
        '*' => ['id', 'name', 'email', 'created_at', 'updated_at'],
    ]);
});
