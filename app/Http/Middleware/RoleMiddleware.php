<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * $roles pode vir como parâmetros separados (variadic) ou como uma única string "admin,voluntario_adm"
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (! $user) {
            abort(403, 'Acesso negado.');
        }

        // Normaliza: se só veio 1 item e ele contém ',' ou '|' divide em múltiplos
        if (count($roles) === 1) {
            $roles = preg_split('/[,\|]/', $roles[0]);
        }

        // Remove espaços e limpa valores vazios
        $roles = array_filter(array_map('trim', $roles));

        // Checa tipo_usuario (coluna no usuário)
        $userRole = $user->tipo_usuario ?? null;
        if ($userRole && in_array($userRole, $roles, true)) {
            return $next($request);
        }

        // Se usa Spatie (ou método equivalente)
        if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($roles)) {
            return $next($request);
        }

        abort(403, 'Você não tem permissão para acessar esta página.');
    }
}
