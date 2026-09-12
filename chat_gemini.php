<?php
// chat_gemini.php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

$rawInput = file_get_contents('php://input');
$datos = json_decode($rawInput, true);

$mensajeUsuario = $datos['mensaje'] ?? '';
$contexto = $datos['contexto'] ?? null;

if (empty($mensajeUsuario)) {
    echo json_encode(['respuesta' => 'Por favor, escribe un mensaje válido.']);
    exit;
}

$apiKey = 'AQ.Ab8RN6LxGNhYAZ-6hkG4ZvMupUMtpBOfWorXppsWR0DKM56YXA'; 
$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=' . $apiKey;

// Instrucción directa y robusta con los datos incrustados en el perfil
$instruccionBase = "Eres Capital Asistente, un asesor financiero experto de Capital One. ";
if ($contexto) {
    $instruccionBase .= "Tienes acceso seguro al perfil del cliente con estos datos exactos -> Saldo disponible: $" . number_format($contexto['saldo'], 2) . " MXN, Ingreso mensual: $" . number_format($contexto['ingreso'], 2) . " MXN, Fondo de ahorro: $" . number_format($contexto['ahorro'], 2) . " MXN. Utiliza estos números de inmediato para responder con precisión a cualquier duda sobre presupuestos o pagos.";
} else {
    $instruccionBase .= "Si el usuario no te proporciona sus datos financieros, pídeselos amablemente antes de dar una recomendación.";
}

$body = [
    "system_instruction" => [
        "parts" => [ ["text" => $instruccionBase] ]
    ],
    "contents" => [
        [ "parts" => [ ["text" => $mensajeUsuario] ] ]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
// CRÍTICO: Evita que cURL se quede colgado eternamente si la red o Google tardan
curl_setopt($ch, CURLOPT_TIMEOUT, 15); 

$response = curl_exec($ch);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    echo json_encode(['respuesta' => 'Error de conexión con la IA (Timeout o Red).']);
    exit;
}

$resultado = json_decode($response, true);

if (isset($resultado['error'])) {
    $apiErrorMsg = $resultado['error']['message'] ?? 'Error desconocido';
    echo json_encode(['respuesta' => '⚠️ Error de API Google: ' . $apiErrorMsg]);
    exit;
}

$textoIA = $resultado['candidates'][0]['content']['parts'][0]['text'] ?? 'No pude procesar la respuesta en este momento.';

echo json_encode(['respuesta' => $textoIA]);
?>