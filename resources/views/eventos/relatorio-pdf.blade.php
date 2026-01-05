<x-relatorio-layout title="Relatório de Eventos">
    <table>
        <thead>
            <tr>
                <th>Ord.</th>
                <th>Título</th>
                <th>Data/Hora</th>
                <th>Local</th>
                <th>Recorrente</th>
                <th>Custo</th>
                <th>Arrecadação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eventos as $evento)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $evento->titulo }}</td>
                    <td>{{ \Carbon\Carbon::parse($evento->data_hora)->format('d/m/Y H:i') }}</td>
                    <td>{{ $evento->local }}</td>
                    <td>{{ $evento->recorrente ? 'Sim' : 'Não' }}</td>
                    <td>R$ {{ number_format($evento->valor_custo, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($evento->valor_arrecadado, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</x-relatorio-layout>
