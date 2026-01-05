<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Estadias</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
        h2 { text-align: center; margin-top: 0; }
    </style>
</head>
<body>
    <h2>Relatório de Estadias</h2>
    <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Usuário</th>
                <th>Data Início</th>
                <th>Data Fim</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($estadias as $estadia)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $estadia->usuario->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($estadia->data_inicio)->format('d/m/Y') }}</td>
                    <td>{{ $estadia->data_fim ? \Carbon\Carbon::parse($estadia->data_fim)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $estadia->observacoes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nenhuma estadia encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
