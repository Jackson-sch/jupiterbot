<?php

// Token de telegram
$token = '2118507613:AAHOslLjQD-BWc3SWkQYcJGLDLrLMDVScr0';
$website = 'https://api.telegram.org/bot'.$token; 

$input = file_get_contents('php://input');
$update = json_decode($input, true);

$chat_id = $update['message']['chat']['id'];
$message = $update['message']['text'];
$first_name = $update['message']['from']['first_name'];


// Datos
$token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';
$dni = $message;

// Iniciar llamada a API
$curl = curl_init();

// Buscar dni
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.apis.net.pe/v1/dni?numero=' . $dni,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 2,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Referer: https://apis.net.pe/consulta-dni-api',
    'Authorization: Bearer ' . $token
  ),
));

$response = curl_exec($curl);

if (curl_errno($curl)) {
    echo 'Error:' . curl_error($curl);
}else $persona = json_decode($response, true);
var_dump($persona);

curl_close($curl);

switch($message) {
    case '/start':
        $response = 'Bienvenido '.$first_name.' al bot de la comunidad de Jupiter @jackson';
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
    case '/persona':
        $response = 'Ingresa el Numero de DNI';
        sendMessage($chat_id, $response);
        break;
    case $message:
        // buscar persona por dni desde una api externa usando curl
        
        if ($message) {
            $response = 'Nombre: '.$persona['nombres'].'***'.' DNI: '.$persona['numeroDocumento'];
        }else{
            $response = 'No se encontraron datos';
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
