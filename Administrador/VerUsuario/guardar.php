<?php
require_once '../../vendor/autoload.php';

// Iniciar sesión
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores de los campos pasados desde la página "Ver usuarios"
    $id = $_POST['id'];
    $codigo_usuario = $_POST['codigo_usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $edad = $_POST['edad'];
    $usuario = $_POST['usuario'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $estado = $_POST['estado'];

    // Sumar 1 al ID para obtener bien el dato (debido al encabezado)
    $row_id = $id + 1;

    // Configurar el cliente de Google
    $client = new \Google_Client();
    $client->setApplicationName('GestorPedidos');
    $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
    $client->setAccessType('offline');

    // El archivo credentials.json
    $path = '../../data/credentials.json';
    $client->setAuthConfig($path);

    // Configurar el servicio de Google Sheets
    $service = new \Google_Service_Sheets($client);
    $spreadsheetId = '1QgmCzgtygUVkGSIEOHGSdrQflhBEBxyhk7YP0x9DcT0';

    // Obtener la fila actual de la hoja de cálculo
    $range = 'Usuarios!A' . $row_id . ':L' . $row_id;
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $currentData = $response->getValues();

    // Mantener la contraseña y el token actuales si existen
    if ($currentData) {
        $currentData = $currentData[0];
        $contrasena = $currentData[6]; 
        $token = $currentData[11]; 
    } else {
        $contrasena = ''; // Valor por defecto si no se encuentra la fila
        $token = ''; // Valor por defecto si no se encuentra la fila
    }

    // Crear los datos a actualizar
    $data = [
        [$id, $codigo_usuario, $nombre, $apellido, $edad, $usuario, $contrasena, $telefono, $correo, $tipo_usuario, $estado, $token]
    ];

    $updateData = new \Google_Service_Sheets_ValueRange([
        'range' => $range,
        'majorDimension' => 'ROWS',
        'values' => $data,
    ]);

    // Configurar los parámetros de actualización
    $params = [
        'valueInputOption' => 'RAW',
    ];

    // Realizar la actualización de los datos en Google Sheets
    $service->spreadsheets_values->update($spreadsheetId, $range, $updateData, $params);

    header('Location: VerUsuario.php');
    exit;
} else {
    echo "No se recibieron los datos necesarios para guardar la edición.";
}
?>

