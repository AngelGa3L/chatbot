<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .header {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }
        .header img {
            height: 100px; /* Ajusta el tamaño de la imagen */
            margin-right: 15px; /* Espacio entre la imagen y el título */
        }
        h1 {
            color: #009c49; /* Verde */
            margin: 0; /* Elimina el margen por defecto */
        }
        #chat-box {
            width: 80%;
            max-width: 800px;
            height: 400px;
            border: 1px solid #ddd;
            background-color: #fff;
            padding: 10px;
            overflow-y: scroll;
            margin: 20px 0;
            border-radius: 8px;
        }
        .user {
            background-color: #e6ffe6; /* Verde claro */
            color: #009c49;
            padding: 10px;
            border-radius: 8px;
            margin: 5px 0;
        }
        .bot {
            background-color: #fff5e6; /* Amarillo claro */
            color: #ffcc00;
            padding: 10px;
            border-radius: 8px;
            margin: 5px 0;
        }
        form {
            width: 80%;
            max-width: 800px;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #009c49; /* Verde */
            color: #fff;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #007a3d;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://orlegisports.com/wp-content/uploads/2022/09/logo-club-santos-laguna-003.png" alt="Logo del Club Santos Laguna">
        <h1>Chatbot de Soporte Técnico</h1>
    </div>
    <div id="chat-box">
        @foreach($messages as $message)
            <div class="{{ $message->sender }}">
                {{ $message->content }}
            </div>
        @endforeach
    </div>

    <form action="{{ route('chat.send') }}" method="POST">
        @csrf
        <input type="text" name="content" placeholder="Escribe tu mensaje..." required>
        <input type="hidden" name="sender" value="user">
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
