<?php
// api_nessie.php

function callNessieAPI($metodo, $endpoint, $datos = null) {
    $apiKey = "dcf66cc4218f5673819c32313c487141"; 
    $baseUrl = "https://api.nessieisreal.com";
    
    // Construir la URL completa
    $url = $baseUrl . $endpoint . "?key=" . $apiKey;

    // Inicializar cURL
    $ch = curl_init(); 
    // Configuración básica
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);

    // Configurar según el método (GET o POST)
    if ($metodo === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($datos) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
        }
    }

    // Ejecutar petición
    $respuesta = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Manejo de errores de conexión
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return ["error" => "Error de conexión: " . $error];
    }
    
    curl_close($ch);

    // Devolver el JSON decodificado como un arreglo de PHP
    return json_decode($respuesta, true);
}
?>