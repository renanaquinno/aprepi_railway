<?php

use App\Models\User;

test('usuário pode ter nome completo', function () {
    $user = new User([
        'name' => 'Renan Aquino'
    ]);

    expect($user->name)->toBe('Renan Aquino');
});
