<?php
require_once './../vendor/autoload.php';

// Configurar el cliente de Google
$client = new \Google_Client();
$client->setApplicationName('GestorPedidos');
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');

// El archivo credentials.json 
$path = './../data/credentials.json';
$client->setAuthConfig($path);

// Configurar el servicio de Google Sheets
$service = new \Google_Service_Sheets($client);

$spreadsheetId = '1QgmCzgtygUVkGSIEOHGSdrQflhBEBxyhk7YP0x9DcT0';

// Función para encriptar la contraseña
function encriptar_contrasena($contrasena) {
    return password_hash($contrasena, PASSWORD_BCRYPT);
}

// Función para verificar si el nombre de usuario ya existe
function verificar_usuario_existente($service, $spreadsheetId, $usuario) {
    $range = 'Usuarios!F:F'; 
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();
    
    foreach ($values as $row) {
        if ($row[0] == $usuario) {
            return true;
        }
    }
    return false;
}

 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $edad = $_POST['edad'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $tipo_usuario = 'cliente';
    $estado = 'activo';
    $token = 'nulo';

    // Verificar si el usuario ya existe
    if (verificar_usuario_existente($service, $spreadsheetId, $usuario)) {
        echo '<div style="display: flex; flex-direction: column; align-items: center; background-color: #fcd9d9; border: 1px solid #f56565; color: #c53030; padding: 3rem; border-radius: 0.375rem; padding-bottom: 5rem; margin: 0 2rem;" role="alert">
                <strong style="font-weight: bold;">Error de envío:</strong>
                <span style="margin-top: 0.5rem;" class="block sm:inline">El nombre de usuario ya existe. Por favor, elija otro nombre de usuario.</span>
                <div style="margin-top: 1rem;">
                    <button style="background-color: #3b82f6; color: white; font-weight: bold; padding: 0.5rem 1rem; border: none; border-radius: 0.25rem; cursor: pointer; transition: background-color 0.3s ease;" onclick="window.location.href = \'register.php\';">
                        Aceptar
                    </button>
                </div>
              </div>';
        exit;
    }

    // Encriptar la contraseña
    $contrasena_encriptada = encriptar_contrasena($contrasena);

    
    if ($tipo_usuario === 'cliente') {
        $random_number = rand(100, 999);
        $codigo_usuario = 'CLI' . str_pad($random_number, 6, '0', STR_PAD_LEFT);
    }

    
    $range = 'Usuarios!A:A';
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();
    
    // Obtener el ID de la última fila
    if (!empty($values)) {
        $lastId = intval(end($values)[0]);
    } else {
        $lastId = 0; 
    }

    // Incrementar el ID
    $newId = $lastId + 1;

    $insertData = new \Google_Service_Sheets_ValueRange([
        'range' => 'Usuarios!A:L', 
        'majorDimension' => 'ROWS',
        'values' => [[
            $newId, $codigo_usuario, $nombre, $apellido, $edad, $usuario, $contrasena_encriptada, $telefono, $correo, $tipo_usuario, $estado, $token
        ]],
    ]);

    // Configurar los parámetros
    $params = [
        'valueInputOption' => 'RAW',
    ];

    
    try {
        $result = $service->spreadsheets_values->append($spreadsheetId, 'Usuarios!A:L', $insertData, $params);
        if ($result->getUpdates()->getUpdatedCells() > 0) {
            header('Location: index.php');
            exit;
        } else {
            echo "Error al insertar los datos.";
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
    <title>Registrate</title>
</head>
<body style="background-image: linear-gradient(22.5deg, rgba(242, 242, 242, 0.03) 0%, rgba(242, 242, 242, 0.03) 16%, rgba(81, 81, 81, 0.03) 16%, rgba(81, 81, 81, 0.03) 26%, rgba(99, 99, 99, 0.03) 26%, rgba(99, 99, 99, 0.03) 73%, rgba(43, 43, 43, 0.03) 73%, rgba(43, 43, 43, 0.03) 84%, rgba(213, 213, 213, 0.03) 84%, rgba(213, 213, 213, 0.03) 85%, rgba(125, 125, 125, 0.03) 85%, rgba(125, 125, 125, 0.03) 100%), linear-gradient(22.5deg, rgba(25, 25, 25, 0.03) 0%, rgba(25, 25, 25, 0.03) 54%, rgba(144, 144, 144, 0.03) 54%, rgba(144, 144, 144, 0.03) 60%, rgba(204, 204, 204, 0.03) 60%, rgba(204, 204, 204, 0.03) 76%, rgba(37, 37, 37, 0.03) 76%, rgba(37, 37, 37, 0.03) 78%, rgba(115, 115, 115, 0.03) 78%, rgba(115, 115, 115, 0.03) 91%, rgba(63, 63, 63, 0.03) 91%, rgba(63, 63, 63, 0.03) 100%), linear-gradient(157.5deg, rgba(71, 71, 71, 0.03) 0%, rgba(71, 71, 71, 0.03) 6%, rgba(75, 75, 75, 0.03) 6%, rgba(75, 75, 75, 0.03) 15%, rgba(131, 131, 131, 0.03) 15%, rgba(131, 131, 131, 0.03) 18%, rgba(110, 110, 110, 0.03) 18%, rgba(110, 110, 110, 0.03) 37%, rgba(215, 215, 215, 0.03) 37%, rgba(215, 215, 215, 0.03) 62%, rgba(5, 5, 5, 0.03) 62%, rgba(5, 5, 5, 0.03) 100%), linear-gradient(90deg, #ffffff,#ffffff);">
    <div class="flex justify-center items-center mt-20">
        <h1 class="mb-4 text-3xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-6xl"><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">Registrate con</span> nosotros </h1>
    </div>
    <div class="flex justify-center items-center mt-8">
        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <form class="max-w-sm mx-auto" method="POST" action="">
                <div class="mb-5">
                    <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="Ingresa tu nombre" required />
                </div>
                <div class="mb-5">
                    <label for="apellido" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="Ingresa tu apellido" required />
                </div>
                <div class="mb-5">
                    <label for="edad" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Edad</label>
                    <input type="number" id="edad" name="edad" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="Ingresa tu edad" required />
                </div>
                <div class="mb-5">
                    <label for="usuario" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Usuario</label>
                    <input type="text" id="usuario" name="usuario" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="Ingresa tu usuario" required />
                </div>
                <div class="mb-5">
                    <label for="contrasena" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="********" required />
                </div>
                <div class="mb-5">
                    <label for="telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="2222-2222" required />
                </div>
                <div class="mb-5">
                    <label for="correo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo</label>
                    <input type="email" id="correo" name="correo" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="name@flowbite.com" required />
                </div>
                <div class="flex items-start mb-5">
                    <div class="flex items-center h-5">
                        <input id="terms" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" required />
                    </div>
                    <label for="terms" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Acepto los <a href="#" class="text-blue-600 hover:underline dark:text-blue-500">términos y condiciones</a></label>
                </div>
                   <!-- Alerta-->
                <div id="alert-container"></div>

                <div class="flex justify-center items-center mt-8">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Registrar nueva cuenta</button>
                </div>
            </form>
            <div class="flex justify-center items-center mt-8">
                <a href="index.php" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                    Volver
                </a>
            </div>
        </div>
    </div>




<script>
 document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const nombre = document.getElementById('nombre');
    const apellido = document.getElementById('apellido');
    const edad = document.getElementById('edad');
    const contrasena = document.getElementById('contrasena');
    const telefono = document.getElementById('telefono');
    const alertContainer = document.getElementById('alert-container');




    function showAlert(message) {
    // Limpiar contenedor de alertas
    alertContainer.innerHTML = '';

    // Crear elemento de alerta
    const alertDiv = document.createElement('div');
    alertDiv.className = 'flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400';
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `
        <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Danger</span>
        <div>
            <span class="font-medium">${message}</span>
        </div>
    `;

    // Agregar alerta al contenedor
    alertContainer.appendChild(alertDiv);

    // Eliminar la alerta después de 5 segundos
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}


   

    form.addEventListener('submit', function (event) {
        // Clear previous alerts
        alertContainer.innerHTML = '';

        // Validar nombre y apellido (solo letras)
        const nombreRegex = /^[A-Za-z]+$/;
        const apellidoRegex = /^[A-Za-z]+$/;

        if (!nombreRegex.test(nombre.value)) {
            showAlert('El nombre solo debe contener letras.');
            nombre.focus();
            event.preventDefault();
            return false;
        }

        if (!apellidoRegex.test(apellido.value)) {
            showAlert('El apellido solo debe contener letras.');
            apellido.focus();
            event.preventDefault();
            return false;
        }

        // Validar edad (solo números)
        const edadRegex = /^\d+$/;
        if (!edadRegex.test(edad.value)) {
            showAlert('La edad solo debe contener números.');
            edad.focus();
            event.preventDefault();
            return false;
        }

        // Validar contraseña (al menos una letra mayúscula y un número)
        const contrasenaRegex = /^(?=.*[A-Z])(?=.*\d).{8,}$/;
        if (!contrasenaRegex.test(contrasena.value)) {
            showAlert('La contraseña debe contener al menos una letra mayúscula, un número y tener una longitud mínima de 8 caracteres.');
            contrasena.focus();
            event.preventDefault();
            return false;
        }

        // Validar teléfono (formato 2222-2222)
        const telefonoRegex = /^\d{4}-\d{4}$/;
        if (!telefonoRegex.test(telefono.value)) {
            showAlert('El teléfono debe tener el formato 2222-2222.');
            telefono.focus();
            event.preventDefault();
            return false;
        }
    });
});
</script>



<br>
<br>
<br>

</body>
</html>