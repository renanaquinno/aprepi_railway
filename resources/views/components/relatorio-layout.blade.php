<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 40px; }
        header { text-align: center; margin-bottom: 20px; }
        header img { max-height: 80px; }
        h2 { margin: 10px 0; }
        .subtitulo { font-size: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 5px; }
        th { background-color: #f2f2f2; }
        footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #555; }
        .pagenum:before { content: counter(page); }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('images/logo-aprepi.png') }}" alt="Logo APREPI">
        <h2>{{ $title }}</h2>
        <div class="subtitulo">
            Emissão: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
        </div>
    </header>

    {{-- Slot para o conteúdo do relatório --}}
    {{ $slot }}

    <footer>
        Página <span class="pagenum"></span>
    </footer>
</body>
</html>