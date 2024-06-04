<?php
require_once '../vendor/autoload.php';

// Iniciar sesión
session_start();

// Verificar si se ha recibido el ID de la fila y los datos actualizados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['row_id']) && isset($_POST['data'])) {
    // Obtener el ID de la fila y los datos actualizados
    $row_id = $_POST['row_id'];
    $data = $_POST['data'];

    // Incrementar el ID en 1 solo para mostrarlo, pero no cambiar el valor real del ID en los datos enviados
    $display_row_id = $row_id++;

    // Configurar el cliente de Google
    $client = new \Google_Client();
    $client->setApplicationName('GestorPedidos');
    $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
    $client->setAccessType('offline');

    // El archivo credentials.json 
    $path = '../data/credentials.json';
    $client->setAuthConfig($path);

    // Configurar el servicio de Google Sheets
    $service = new \Google_Service_Sheets($client);

    $spreadsheetId = '1QgmCzgtygUVkGSIEOHGSdrQflhBEBxyhk7YP0x9DcT0';

    // Definir el rango de celdas a actualizar (fila y columnas)
    $range = 'Registro!A' . $row_id . ':K' . $row_id;

    // Crear los datos a actualizar 
    $updateData = new Google_Service_Sheets_ValueRange([
        'range' => $range,
        'majorDimension' => 'ROWS',
        'values' => [$data],
    ]);

    // Configurar los parámetros de actualización
    $params = [
        'valueInputOption' => 'RAW',
    ];

    // Realizar la actualización de los datos en Google Sheets
    $service->spreadsheets_values->update($spreadsheetId, $range, $updateData, $params);

    
    header('Location: prueba.php');
    exit;
} else {
   
    echo "No se recibieron los datos necesarios para guardar la edición.";
}
?>

