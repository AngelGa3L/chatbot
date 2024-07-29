<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class ChatController extends Controller
{
    public function index()
    {
        $messages = Message::all();
        return view('chat.index', compact('messages'));
    }

    

    public function send(Request $request)
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

        return redirect()->route('chat.index');
    }

    private function generateBotResponse($userMessage)
{
    $userMessage = $this->normalizeText($userMessage);

    $responses = [
        'hola' => '¡Hola! ¿Cómo puedo ayudarte hoy?',
        'problema' => '¿Podrías proporcionar más detalles sobre el problema?, puedes escribir algunas de estas preguntas frecuetes:
        ¿donde puedo adquirir mis boletos?
        ¿como puedo adquirir mis boletos en la app?
        ¿puedo enviar mis boletos a otra persona?
        ¿cuales son los metodos de pago disponibles?
        ¿puedo realizar el pago de mis boletos directamente en taquilla?
        ¿si soy abonado, cómo puedo realizar mi renovacion en la app?
        ¿si deseo realizar algún cambio o crecer mi abono, que debo hacer?
        ¿si tengo más de 4 abonos a mi nombre, puedo renovar todos desde mi cuenta en la app?
        ¿si no cuento con la app de fanki, puedo realizar la compra de boletos?
        ¿como modifico los datos personales registrados en mi cuenta?
        ¿le puedo imprimir el nombre de mis familiares a los abonos fisicos?
        contacto directo con personal',
        'gracias' => '¡De nada! Si necesitas más ayuda, no dudes en preguntar.',
        '¿donde puedo adquirir mis boletos?' => 'Podrás adquirir tus boletos/abonos tanto en la página web fanki.com.mx/clubsantos, App Fanki y Taquillas TSM',
        '¿como puedo adquirir mis boletos en la app?' => 'Descarga FANKI desde App Store o Play Store Completa el formulario con tus datos personales y la casilla de e-mail que utilices habitualmente, ya que te llegará un correo de verificación para continuar con el registro. Elige el partido o la función de Tours que desees y selecciona el método de pago. Una vez finalizada tu compra, recibirás la confirmación de la misma en tu e-mail junto con los boletos adquiridos (también podrás visualizarlos en la sección "Mis entradas"). El boleto posee un código QR que te solicitarán para acceder al estadio.',
        '¿puedo enviar mis boletos a otra persona?' => 'Sí, dentro de tu usuario en la app de Fanki, dirígete a la sección "Mis compras". Selecciona la orden con el boleto que quieres transferir. Dentro de la información del boleto, podrás elegir "Transferir este boleto". Ingresa el número del celular al que desees transferir el ticket y listo! (Importante: el usuario al que vas a transferir la entrada debe contar con una cuenta en Fanki para poder recibirla)',
        '¿cuales son los metodos de pago disponibles?' => 'Efectivo en muchos establecimientos (Banco BBVA, Citibanamex, Banco Santander, Soriana, Extra, Oxxo, Farmacias del Ahorro, Ley, Chedraui, entre otros). Mercado Pago. SPEI. Tarjeta de débito y crédito (Visa, American Express, MasterCard). Para determinados eventos existe la posibilidad de que esté habilitada la opción de pago en efectivo en las taquillas del estadio.',
        '¿puedo realizar el pago de mis boletos directamente en taquilla?' => 'Sí, al elegir el método de pago en taquillas del estadio, recibirás un QR como orden de pago, el cual deberá ser escaneado por el personal de taquilla y así realizar el cobro en efectivo.',
        '¿si soy abonado, cómo puedo realizar mi renovacion en la app?' => 'Crea tu cuenta en FANKI con el correo que registraste en la compra de tus abonos 23/24. En automático, podrás ver los precios de los abonos actualizados al precio RENOVACIÓN. Si cuentas con un abono para renovar, podrás adquirir hasta 4 abonos con precio RENOVACIÓN.',
        '¿si deseo realizar algún cambio o crecer mi abono, que debo hacer?' => 'Sólo el titular del correo electrónico puede realizar la renovación (hasta 4 abonos por correo). Entra a tu cuenta en Fanki y elige la sección, lugar o plan de Abono que deseas, en automático seguirás contando con el descuento de renovación.',
        '¿si tengo más de 4 abonos a mi nombre, puedo renovar todos desde mi cuenta en la app?' => 'No, para renovar o adquirir más de 4 abonos, deberás contactar a un Asesor de Ventas quien podrá apoyarte y realizar tu renovación.',
        '¿si no cuento con la app de fanki, puedo realizar la compra de boletos?' => 'No, es indispensable contar con la app para realizar tu compra, o mínimo, recibir tu boleto.',
        '¿como modifico los datos personales registrados en mi cuenta?' => 'Ingresa a tu usuario y dirígete a la sección "Perfil". Allí podrás visualizar los datos registrados y realizar las modificaciones que desees, exceptuando el mail con el cual te registraste.',
        '¿le puedo imprimir el nombre de mis familiares a los abonos fisicos?' => 'El nombre del abono impreso corresponderá al nombre de la cuenta del abonado, si deseas personalizar con nombres diferentes los abonos, deberás "transferir" por medio de la app Fanki los abonos a cada persona previamente registrada, y así se le asignará su nombre en automático.',
        'contacto directo con personal' => 'Whatsapp: +57 3170292048 X (ex Twitter): fankisports Facebook: fankisports Instagram: fankisports E-mail: soporte@fanki.com.mx Horarios de atención FANKI: De lunes a lunes de 8 AM a 8 PM. También podrán contactarnos a nuestra guardia durante eventos, por consultas acerca de tu registro o entrada.'
    ];

    foreach ($responses as $keyword => $response) {
        $keyword = strtolower(trim($keyword));
        if (strpos($userMessage, $keyword) !== false) {
            return $response;
        }
    }

    return 'Lo siento, no entendí tu mensaje. ¿Puedes intentar de nuevo?';
}
private function normalizeText($text)
{
    $text = preg_replace('/[áàâãäå]/u', 'a', $text);
    $text = preg_replace('/[éèêë]/u', 'e', $text);
    $text = preg_replace('/[íìîï]/u', 'i', $text);
    $text = preg_replace('/[óòôõö]/u', 'o', $text);
    $text = preg_replace('/[úùûü]/u', 'u', $text);
    $text = preg_replace('/[ñ]/u', 'n', $text);
    $text = preg_replace('/[ç]/u', 'c', $text);

    return strtolower(trim($text));
}
}
