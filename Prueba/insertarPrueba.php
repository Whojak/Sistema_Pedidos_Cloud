<?php
require_once '../vendor/autoload.php';

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

// Verificar si se ha enviado el formulario 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $edad = $_POST['edad'];
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    $estado = $_POST['estado'];
    $token = $_POST['token'];

    // Obtener el último ID de la hoja de Google
    $range = 'Registro!A:A';
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();
    
    // Obtener el ID de la última fila
    if (!empty($values)) {
        // Obtener el ID de la última fila
        $lastId = end($values)[0];
    } else {
        // Si no hay filas, comenzar desde el ID 1
        $lastId = 1;
    }

    // Incrementar el ID
    $newId = $lastId + 1;

    
    $insertData = new Google_Service_Sheets_ValueRange([
        'range' => 'Registro!A:K', 
        'majorDimension' => 'ROWS',
        'values' => [[$newId, $nombre, $apellido, $edad, $usuario, $contraseña, $telefono, $correo, $rol, $estado, $token]],
    ]);

    // Configurar los parámetros de inserción
    $params = [
        'valueInputOption' => 'RAW',
    ];

    // Insertar los datos en Google Sheets
    $service->spreadsheets_values->append($spreadsheetId, 'Registro!A:K', $insertData, $params);

    // Redirigir a la página prueba.php
    header('Location: prueba.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Nuevo Usuario</title>
</head>
<body>
    <h1>Insertar Nuevo Usuario</h1>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required><br><br>

        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" required><br><br>

        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required><br><br>

        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required><br><br>

        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" required><br><br>

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required><br><br>

        <label for="rol">Rol:</label>
        <input type="text" id="rol" name="rol" required><br><br>

        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" required><br><br>

        <label for="token">Token:</label>
        <input type="text" id="token" name="token" required><br><br>

        <button type="submit">Insertar</button>
    </form>
</body>
</html>
