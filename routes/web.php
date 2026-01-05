<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DoacaoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\CestaBasicaController;
use App\Http\Controllers\VoluntarioController;
use App\Http\Controllers\AdminVoluntarioController;

use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\EstadiaController;
use App\Http\Controllers\AniversariantesController;
use App\Http\Controllers\DatasComemorativasController;
use App\Http\Controllers\MensagemAniversarioController;

use App\Http\Controllers\PostagemController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\LikeController;

use App\Services\MercadoPagoService;
use App\Models\Doacao;


/**
 * Registrando alias para middlewares customizados
 */
app('router')->aliasMiddleware('role', RoleMiddleware::class);


// Grupo para quem está autenticado
Route::middleware(['auth'])->group(function () {

    // Rotas administrativas
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('voluntarios', [AdminVoluntarioController::class, 'index'])->name('voluntarios.index');
        Route::post('voluntarios/{id}/aprovar', [AdminVoluntarioController::class, 'aprovar'])->name('voluntarios.aprovar');
        Route::post('voluntarios/{id}/recusar', [AdminVoluntarioController::class, 'recusar'])->name('voluntarios.recusar');
    });

    // Rotas compartilhadas entre admin e voluntário administrador
    Route::middleware(['role:admin,voluntario_adm'])->group(function () {

        Route::resource('eventos', EventoController::class);
        Route::get('eventos/relatorio/pdf', [EventoController::class, 'gerarRelatorioPDF'])->name('eventos.relatorio.pdf');

        Route::resource('usuarios', UserController::class)->parameters(['usuarios' => 'user']);
        Route::get('usuarios/relatorio/pdf', [UserController::class, 'gerarRelatorioPDF'])->name('usuarios.relatorio.pdf');

        Route::resource('cestas', CestaBasicaController::class);
        Route::get('cestas/relatorio/pdf', [CestaBasicaController::class, 'gerarRelatorioPDF'])->name('cestas.relatorio.pdf');
        Route::get('cestas/{cesta}/entregar', [CestaBasicaController::class, 'entregar'])->name('cestas.entregar.form');
        Route::post('cestas/{cesta}/entregar', [CestaBasicaController::class, 'salvarEntrega'])->name('cestas.entregar');

        Route::resource('estadias', EstadiaController::class);
        Route::get('estadias/relatorio/pdf', [EstadiaController::class, 'relatorioPdf'])->name('estadias.relatorio.pdf');

        Route::get('/aniversariantes', [AniversariantesController::class, 'index'])->name('aniversariantes.index');

        Route::get('/aniversariantes/mensagem', [MensagemAniversarioController::class, 'edit'])->name('aniversariantes.mensagem');
        Route::post('/aniversariantes/mensagem', [MensagemAniversarioController::class, 'update'])->name('aniversariantes.mensagem.update');

        Route::post('/aniversariantes/enviar', [AniversariantesController::class, 'enviarEmails'])->name('aniversariantes.enviarEmails');


        Route::resource('datas_comemorativas', DatasComemorativasController::class);
        Route::post('datas_comemorativas/{dataComemorativa}/enviar-emails', [DatasComemorativasController::class, 'enviarEmails'])->name('datas_comemorativas.enviarEmails');

        Route::resource('postagens', PostagemController::class)->parameters(['postagens' => 'postagem']);
        Route::post('/postagens/upload-imagem', [PostagemController::class, 'uploadImagem'])->name('postagens.upload.imagem');
        Route::prefix('admin')->name('postagens.admin.')->group(function () {
            Route::get('postagens', [PostagemController::class, 'index'])->name('index');
        });

        Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    });

    // Rotas para qualquer usuário autenticado
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('doacoes', DoacaoController::class)
        ->parameters(['doacoes' => 'doacao']);

    Route::get('doacoes/relatorio/pdf', [DoacaoController::class, 'gerarRelatorioPDF'])->name('doacoes.relatorio.pdf');

   
    // Comentários - criar, editar, atualizar, deletar
    Route::post('postagens/{postagem:slug}/comentarios', [ComentarioController::class, 'store'])
        ->name('comentarios.store');

    Route::get('comentarios/{comentario}/edit', [ComentarioController::class, 'edit'])->name('comentarios.edit');
    Route::put('comentarios/{comentario}', [ComentarioController::class, 'update'])->name('comentarios.update');
    Route::delete('comentarios/{comentario}', [ComentarioController::class, 'destroy'])->name('comentarios.destroy');

    // Likes toggle (curtir/descurtir) para postagens e comentários
    Route::post('likes/toggle', [LikeController::class, 'toggle'])->name('likes.toggle');
});

/**
 * Rotas públicas
 */
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/sobre-nos', fn() => view('sobre-nos'))->name('sobre-nos');
Route::get('/contato', fn() => view('contato'))->name('contato');

Route::post('/contato', [ContatoController::class, 'enviar'])->name('contato.enviar');
Route::get('/voluntariado', [VoluntarioController::class, 'create'])->name('voluntariado.create');
Route::post('/voluntariado', [VoluntarioController::class, 'store'])->name('voluntariado.store');

Route::get('/postagens', [PostagemController::class, 'publicIndex'])->name('postagens.index');
Route::get('postagens/{postagem:slug}', [PostagemController::class, 'show'])->name('postagens.show');


// Retornos do checkout
Route::get('/mercadopago/success', [DoacaoController::class, 'success'])->name('mercadopago.success');
Route::get('/mercadopago/failure', [DoacaoController::class, 'failure'])->name('mercadopago.failure');
Route::get('/mercadopago/pending', [DoacaoController::class, 'pending'])->name('mercadopago.pending');

Route::post('/mercadopago/webhook', [DoacaoController::class, 'webhook'])->name('mercadopago.webhook');

require __DIR__ . '/auth.php';
