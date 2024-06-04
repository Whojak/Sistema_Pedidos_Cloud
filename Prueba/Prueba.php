<?php
require_once '../vendor/autoload.php';

// Iniciar sesión
session_start();

// Verificar si se ha realizado una eliminación anteriormente
if (!isset($_SESSION['eliminacion_realizada'])) {
    $_SESSION['eliminacion_realizada'] = false;
}

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

//ID HOJA DE CALCULO
$spreadsheetId = '1QgmCzgtygUVkGSIEOHGSdrQflhBEBxyhk7YP0x9DcT0';

// Obtener todos los datos
$range = 'Registro!A2:K'; // Obtener todas las filas de una hoja desde la celda A2 hasta el final de la columna K
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();


//Logica de eliminar

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['row_id'])) {
    $row_id = intval($_POST['row_id']); // Convertir a entero
    // Verificar si el ID de fila es mayor que cero
    if ($row_id > 0) {
       //Logica de eliminacion 
        $deleteRequest = new Google_Service_Sheets_Request([
            'deleteDimension' => [
                'range' => [
                    'sheetId' => 0, // El ID de la hoja de cálculo (hoja) en la que se eliminará la fila
                    'dimension' => 'ROWS', 
                    'startIndex' => $row_id - 1, // Restar 1 para ajustar al índice basado en cero
                    'endIndex' => $row_id 
                ]
            ]
        ]);

        $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
            'requests' => [$deleteRequest]
        ]);

        $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);

        // Marcar la eliminación como realizada
        $_SESSION['eliminacion_realizada'] = true;

        // Redirigir para recargar la página
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
    
        echo "El ID de fila proporcionado no es válido.";
        exit;
    }
}

// Iniciar la tabla HTML
echo '<table border="1" id="tablaUsuarios">'; 
echo '<thead>';
echo '<tr>';
echo '<th>ID</th>';
echo '<th>Nombre</th>';
echo '<th>Apellido</th>';
echo '<th>Edad</th>';
echo '<th>Usuario</th>';
echo '<th>Contraseña</th>';
echo '<th>Telefono</th>';
echo '<th>Correo</th>';
echo '<th>Rol</th>';
echo '<th>Estado</th>';
echo '<th>Token</th>';
echo '<th>Acciones</th>'; 
echo '</tr>';
echo '</thead>';
echo '<tbody>';

// Iterar sobre los valores para imprimir las filas 
foreach ($values as $index => $row) {
    echo '<tr>';
    echo '<td>' . $row[0] . '</td>';
    echo '<td>' . $row[1] . '</td>';
    echo '<td>' . $row[2] . '</td>'; 
    echo '<td>' . $row[3] . '</td>'; 
    echo '<td>' . $row[4] . '</td>'; 
    echo '<td>' . $row[5] . '</td>'; 
    echo '<td>' . $row[6] . '</td>'; 
    echo '<td>' . $row[7] . '</td>';
    echo '<td>' . $row[8] . '</td>';
    echo '<td>' . $row[9] . '</td>';
    echo '<td>' . $row[10] . '</td>';
   
   
    // Agregar botones para editar y eliminar la fila actual
    echo '<td>
            <form method="POST" action="pruebaEdit.php" style="display: inline;">
                <input type="hidden" name="row_id" value="' . ($index + 2) . '">';
    // Agregar campos ocultos para todos los datos de la fila
    foreach ($row as $key => $value) {
        echo '<input type="hidden" name="data[' . $key . ']" value="' . $value . '">';
    }
    echo '<button type="submit">Editar</button>
            </form>
            <form method="POST" style="display: inline;">
                <input type="hidden" name="row_id" value="' . ($index + 2) . '">
                <button type="submit">Eliminar</button>
            </form>
          </td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

// Botón "Crear un nuevo usuario"
echo '<form method="GET" action="insertarPrueba.php">
        <button type="submit">Crear un nuevo usuario</button>
      </form>';

// Cerrar la sesión al finalizar
session_write_close();
?>
