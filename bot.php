<?php

// Token de telegram
$token = '2118507613:AAHOslLjQD-BWc3SWkQYcJGLDLrLMDVScr0';
$website = 'https://api.telegram.org/bot'.$token; 

$input = file_get_contents('php://input');
$update = json_decode($input, true);

$chat_id = $update['message']['chat']['id'];
$message = $update['message']['text'];

switch($message) {
    case '/start':
        $response = 'Bienvenido al bot de la comunidad de telegram';
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
    default:
        $response = 'No te entiendo';
        sendMessage($chat_id, $response);
        break;
}

function sendMessage($chat_id, $response) {
    $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chat_id.'&parse_mode=HTML&text='.urlencode($response);
    file_get_contents($url);
}
