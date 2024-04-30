<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processar Texto</title>
</head>
<body>
    <h1>Processar Texto</h1>

    @if(isset($textoProcessado))
        <p>Texto Processado: {{ $textoProcessado }}</p>
    @endif

    <form method="POST" action="{{ route('text.process') }}">
        @csrf
        <label for="texto">Digite o texto:</label><br>
        <textarea name="texto" id="texto" cols="30" rows="10"></textarea><br>
        <button type="submit">Processar</button>
    </form>
</body>
</html>
