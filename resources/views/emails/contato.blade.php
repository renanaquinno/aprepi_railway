<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contato - APREPI</title>
</head>
<body>
    <h2>Nova mensagem de contato</h2>
    <p><strong>Nome:</strong> {{ $dados['nome'] }}</p>
    <p><strong>Email:</strong> {{ $dados['email'] }}</p>
    <p><strong>Assunto:</strong> {{ $dados['assunto'] }}</p>
    <p><strong>Mensagem:</strong></p>
    <p>{{ $dados['mensagem'] }}</p>
</body>
</html>
