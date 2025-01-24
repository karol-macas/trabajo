<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function handle(Request $request)
    {
        // Configuración del BotMan
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class); // Asegúrate de cargar el driver web si usas un widget web
        $config = [];
        $botman = BotManFactory::create($config);

        // Escucha por cualquier mensaje
        $botman->hears('{message}', function (BotMan $bot, $message) {
            // Mensaje de "pensando"
            $bot->reply('Déjame un momento...');

            // Obtén la respuesta de la IA
            $response = $this->getAIResponse($message);
            $bot->reply($response);
        });

        // Escucha por la palabra clave "Ayuda"
        $botman->hears('Ayuda', function (BotMan $bot) {
            // Obtener el usuario autenticado
            $user = auth()->user();

            if ($user) {
                // Si el usuario es administrador
                if ($user->hasRole('admin')) {
                    $bot->reply('¡Hola Admin! Puedo ayudarte con las siguientes opciones: 
                    - Consultar actividades
                    - Consultar productos
                    - Consultar clientes
                    - Consultar departamentos');
                } 
                // Si el usuario es empleado
                elseif ($user->hasRole('empleado')) {
                    $bot->reply('¡Hola Empleado! Solo puedes gestionar las actividades.');
                }
            } else {
                // Si el usuario no está autenticado
                $bot->reply('Por favor, inicia sesión para recibir asistencia.');
            }
        });

        // Inicia el proceso de escucha
        $botman->listen();
    }

    private function getAIResponse($message)
    {
        $client = new Client();

        try {
            // Llamada a la API de Hugging Face
            $response = $client->post('https://api-inference.huggingface.co/models/gpt2', [
                'json' => ['inputs' => $message],
                'headers' => [
                    'Authorization' => 'Bearer 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', // Usa tu token
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            // Ajustar según la estructura de la respuesta
            return $data[0]['generated_text'] ?? 'No tengo una respuesta para eso.';
        } catch (RequestException $e) {
            return 'Lo siento, ha ocurrido un error al comunicarme con el servicio. Inténtalo de nuevo más tarde.';
        } catch (\Exception $e) {
            return 'Ocurrió un error inesperado. Por favor, intenta nuevamente.';
        }
    }
}
