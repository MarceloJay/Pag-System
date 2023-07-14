<!DOCTYPE html>
<html>
<head>
    <title>Bem-vindo(a) à Pag System</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #333;
            font-size: 32px;
        }
        .description {
            color: #666;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .btn-entrar {
            padding: 10px 20px;
            font-size: 18px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .btn-entrar:hover {
            background-color: #45a049;
        }
        .logo {
            width: 600px;
            height: auto;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
    <h1>Bem-vindo(a)</h1>
    <img class="logo" src="{{URL::asset('images/pagsystem.png')}}"  alt="Logo da Pag System">
    <p class="description">Aceitamos PIX, boleto e cartão de crédito</p>
    <a href="{{ route('login') }}">
        <button class="btn-entrar" type="button">Entrar</button>
    </a>    
</body>
</html>
