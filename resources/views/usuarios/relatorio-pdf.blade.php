<x-relatorio-layout title="Relatório de Usuários">
    <table>
        <thead>
            <tr>
                <th>Ord.</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Tipo de Usuário</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr>
			<td>{{ $loop->iteration }}</td>
			<td>{{ $usuario->name }}</td>
			<td>{{ $usuario->cpf }}</td>
			<td>{{ $usuario->email }}</td>
			<td>{{ $usuario->telefone }}</td>
			<td>{{ ucfirst($usuario->tipo_usuario) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-relatorio-layout>