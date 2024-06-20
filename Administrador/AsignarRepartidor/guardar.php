<?php
require_once '../../vendor/autoload.php';

// Iniciar sesión
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $codigo_pedido = $_POST['codigo_pedido'];
    $pedido = $_POST['pedido'];
    $descripcion = $_POST['descripcion'];
    $concepto = $_POST['concepto'];
    $id_usuario = $_POST['id_usuario'];
    $id_repartidor = $_POST['id_repartidor'];
    $tipo_pago = $_POST['tipo_pago'];
    $monitoreo = $_POST['monitoreo'];
    $estado = 'Aceptado';

    // Sumar 1 al ID para obtener bien el dato
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

    // Definir el rango de celdas 
    $range = 'Pedidos!A' . $row_id . ':J' . $row_id;

    // Crear los datos a actualizar
    $data = [
        [$id, $codigo_pedido, $pedido, $descripcion, $estado, $concepto, $id_usuario, $id_repartidor, $tipo_pago, $monitoreo]
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

    header('Location: ../VerPedidos/Verpedidos.php');
    exit;
} else {
    echo "No se recibieron los datos necesarios para guardar la edición.";
}
?>

