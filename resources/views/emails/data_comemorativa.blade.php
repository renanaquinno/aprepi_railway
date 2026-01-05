@component('mail::message')
# {{ $mensagem['titulo'] }}

{!! str_replace('{{nome}}', $user->name, $mensagem['mensagem']) !!}

Obrigado, Equipe APREPI.<br>
@endcomponent
