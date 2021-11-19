<?php
// Conexion base de datos MongoDB

$conn = new MongoDB\Client('mongodb+srv://admin:admin123@cluster0.32pgj.mongodb.net/myFirstDatabase?retryWrites=true&w=majority');
$db = $conn->interacion;




// Token de telegram
$token = '2118507613:AAHOslLjQD-BWc3SWkQYcJGLDLrLMDVScr0';
$website = 'https://api.telegram.org/bot'.$token; 

$input = file_get_contents('php://input');
$update = json_decode($input, true);

$chat_id = $update['message']['chat']['id'];
$message = $update['message']['text'];
$first_name = $update['message']['from']['first_name'];

switch($message) {
    case '/inicio':
        $response = 'Bienvenido '.$first_name.' al bot de la comunidad de telegram';
        sendMessage($chat_id, $response);
        break;
    case '/frases':
        $arrays = array(
            1 => "Nada nuevo hay bajo el sol, pero cuántas cosas viejas hay que no conocemos.",
            2 => "El verdadero amigo es aquel que está a tu lado cuando preferiría estar en otra parte.",
            3 => "La sabiduría es la hija de la experiencia.",
            4 => "Nunca hay viento favorable para el que no sabe hacia dónde va.",  
        );
        $response = $arrays[rand(1,4)];
        sendMessage($chat_id, $response);
        break;
    case '/help':
        $response = 'Este bot te ayudara a conocer el clima de una ciudad';
        sendMessage($chat_id, $response);
        break;
    case '/clima':
        $response = 'Ingresa el nombre de la ciudad';
        sendMessage($chat_id, $response);
        break;
    case '/laboratorio':
        // buscar laboratorios en la base de datos mongoDB coleccion laboratorios
        $laboratorios = $db->laboratorios;
        $laboratorio = $laboratorios->findOne(['nombre' => $message]);
        if($laboratorio) {
            $response = 'El laboratorio '.$laboratorio['nombre'].' se encuentra en '.$laboratorio['ubicacion'].' y tiene una capacidad de '.$laboratorio['capacidad'].' personas';
        } else {
            $response = 'No se encontró el laboratorio';
        }
        sendMessage($chat_id, $response);
        break;
    default:
        $response = 'No te entiendo';
        sendMessage($chat_id, $response);
        break;
}

function sendMessage($chat_id, $response) {
    $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chat_id.'&parse_mode=HTML&text='.urlencode($response);
    file_get_contents($url);
}
