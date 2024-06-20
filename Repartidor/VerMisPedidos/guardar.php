<?php
require_once '../../vendor/autoload.php';


session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id = $_POST['id'];
    $codigo_pedido = $_POST['codigo_pedido'];
    $pedido = $_POST['pedido'];
    $descripcion = $_POST['descripcion'];
    $estado = "Entregado"; 
    $concepto = $_POST['concepto']; 
    $codigo_usuario = $_POST['codigo_usuario'];
    $codigo_repartidor = $_POST['codigo_repartidor'];
    $tipo_pago = $_POST['tipo_pago'];
    $monitoreo = $_POST['monitoreo'];

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

    // Crear los datos a actualizar
    $data = [
        [$id, $codigo_pedido, $pedido, $descripcion, $estado, $concepto, $codigo_usuario, $codigo_repartidor, $tipo_pago, $monitoreo]
    ];

    // Rango de celdas a actualizar
    $range = 'Pedidos!A' . $row_id . ':J' . $row_id;

    // Crear el objeto ValueRange con los datos a actualizar
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

    header('Location: ../PedidosHechos/pedidosHechos.php');
    exit;
} else {
    echo "No se recibieron los datos necesarios para guardar la edición.";
}
?>
