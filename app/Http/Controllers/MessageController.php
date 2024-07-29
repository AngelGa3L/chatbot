<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function index()
    {
        return Message::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'sender' => 'required|string|in:user,bot',
        ]);

        $userMessage = Message::create($request->only('content', 'sender'));

        if ($request->sender === 'user') {
            $response = $this->generateBotResponse($userMessage->content);
            Message::create([
                'content' => $response,
                'sender' => 'bot',
            ]);
        }

        return response()->json(['success' => true]);
    }

    private function generateBotResponse($userMessage)
    {
        $responses = [
            'hola' => '¡Hola! ¿Cómo puedo ayudarte hoy?',
            'problema' => '¿Podrías proporcionar más detalles sobre el problema?',
            'gracias' => '¡De nada! Si necesitas más ayuda, no dudes en preguntar.',
        ];

        foreach ($responses as $keyword => $response) {
            if (strpos(strtolower($userMessage), $keyword) !== false) {
                return $response;
            }
        }

        return 'Lo siento, no entendí tu mensaje. ¿Puedes intentar de nuevo?';
    }
}

