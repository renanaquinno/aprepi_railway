<x-relatorio-layout title="Relatório de Cestas Básicas">
    <table>
        <thead>
            <tr>
                <th>Ord.</th>
                <th>Data Recebimento</th>
                <th>Entrada</th>
                <th>Origem</th>
                <th>Status</th>
                <th>Data Entrega</th>
                <th>Destinatário</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cestas as $cesta)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($cesta->data_recebimento)->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($cesta->entrada_tipo) }}</td>
                    <td>{{ $cesta->origemPessoa->name ?? '-' }}</td>
                    <td>{{ ucfirst($cesta->status) }}</td>
                    <td>{{ $cesta->data_entrega ? \Carbon\Carbon::parse($cesta->data_entrega)->format('d/m/Y') : '-' }}</td>
                    <td>{{$cesta->destinatario->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-relatorio-layout>
