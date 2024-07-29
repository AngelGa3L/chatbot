<?php

namespace App\Services;

use GuzzleHttp\Client;
use OpenAI\Client as OpenAIClient;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $apiKey = env('OPENAI_API_KEY');
        $transporter = new GuzzleHttp\Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
            ],
        ]);
        $this->client = new OpenAIClient($transporter);
    }

    public function getResponse($prompt)
    {
        try {
            $response = $this->client->completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $prompt,
                'max_tokens' => 150,
            ]);

            return $response->choices[0]->text ?? 'Lo siento, no obtuvimos una respuesta.';
        } catch (\Exception $e) {
            Log::error('OpenAI API request failed: ' . $e->getMessage());
            return 'Lo siento, hubo un error al procesar tu solicitud.';
        }
    }
}
