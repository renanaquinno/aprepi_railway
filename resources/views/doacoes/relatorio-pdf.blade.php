<x-relatorio-layout title="Relatório de Doações">
    <table>
        <thead>
            <tr>
                <th>Ord.</th>
                <th>Doador</th>
                <th>Data</th>
                <th>Valor</th>
                <th>Forma</th>
                <th>Status</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doacoes as $doacao)
                <tr>
			 <td>{{ $loop->iteration }}</td>
                    <td>{{ $doacao->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($doacao->data_doacao)->format('d/m/Y') }}</td>
                    <td>R$ {{ number_format($doacao->valor, 2, ',', '.') }}</td>
                    <td>{{ ucfirst($doacao->forma_pagamento) }}</td>
                    <td>{{ ucfirst($doacao->status) }}</td>
                    <td>{{ $doacao->observacoes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <p class="total">Total Arrecadado: R$ {{ number_format($total, 2, ',', '.') }}</p>
</x-relatorio-layout>