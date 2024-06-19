<?php
require_once '../../vendor/autoload.php';

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
    $tipo_usuario = $_POST['tipo_usuario'];
    $estado = $_POST['estado'];

    // Verificar si el usuario ya existe
if (verificar_usuario_existente($service, $spreadsheetId, $usuario)) {
    echo '<div style="display: flex; flex-direction: column; align-items: center; background-color: #fcd9d9; border: 1px solid #f56565; color: #c53030; padding: 3rem; border-radius: 0.375rem; padding-bottom: 5rem; margin: 0 2rem;" role="alert">
            <strong style="font-weight: bold;">Error de envio:</strong>
            <span style="margin-top: 0.5rem;" class="block sm:inline">El nombre de usuario ya existe. Por favor, elija otro nombre de usuario.</span>
            <div style="margin-top: 1rem;">
                <button style="background-color: #3b82f6; color: white; font-weight: bold; padding: 0.5rem 1rem; border: none; border-radius: 0.25rem; cursor: pointer; transition: background-color 0.3s ease;" onclick="window.location.href = \'registrarUsuario.php\';">
                    Aceptar
                </button>
            </div>
          </div>';
    exit;
}



    // Encriptar la contraseña
    $contrasena_encriptada = encriptar_contrasena($contrasena);

    // Asignar el código de usuario según el tipo de usuario
    switch ($tipo_usuario) {
        case 'cliente':
            $random_number = rand(100, 999); 
            $codigo_usuario = 'CLI' . str_pad($random_number, 6, '0', STR_PAD_LEFT); 
            break;
        case 'repartidor':
            $random_number = rand(100, 999); 
            $codigo_usuario = 'REP' . str_pad($random_number, 6, '0', STR_PAD_LEFT); 
            break;
        case 'administrador':
            $random_number = rand(100, 999); 
            $codigo_usuario = 'ADM' . str_pad($random_number, 6, '0', STR_PAD_LEFT); 
            break;
        default:
            $codigo_usuario = 'nulo'; // En caso de un tipo de usuario no reconocido
            break;
    }

    $token = 'nulo';

    // Obtener el último ID de la hoja de Google
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

    // Configurar los parámetros de inserción
    $params = [
        'valueInputOption' => 'RAW',
    ];

    // Insertar los datos en Google Sheets
    try {
        $result = $service->spreadsheets_values->append($spreadsheetId, 'Usuarios!A:L', $insertData, $params);
        if ($result->getUpdates()->getUpdatedCells() > 0) {
            header('Location: ../VerUsuario/VerUsuario.php');
            exit;
        } else {
            echo "Error al insertar los datos.";
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'BIEN.';
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
    
    <title>Registrar usuarios</title>

</head>
<body  style="background-image: linear-gradient(22.5deg, rgba(242, 242, 242, 0.03) 0%, rgba(242, 242, 242, 0.03) 16%,rgba(81, 81, 81, 0.03) 16%, rgba(81, 81, 81, 0.03) 26%,rgba(99, 99, 99, 0.03) 26%, rgba(99, 99, 99, 0.03) 73%,rgba(43, 43, 43, 0.03) 73%, rgba(43, 43, 43, 0.03) 84%,rgba(213, 213, 213, 0.03) 84%, rgba(213, 213, 213, 0.03) 85%,rgba(125, 125, 125, 0.03) 85%, rgba(125, 125, 125, 0.03) 100%),linear-gradient(22.5deg, rgba(25, 25, 25, 0.03) 0%, rgba(25, 25, 25, 0.03) 54%,rgba(144, 144, 144, 0.03) 54%, rgba(144, 144, 144, 0.03) 60%,rgba(204, 204, 204, 0.03) 60%, rgba(204, 204, 204, 0.03) 76%,rgba(37, 37, 37, 0.03) 76%, rgba(37, 37, 37, 0.03) 78%,rgba(115, 115, 115, 0.03) 78%, rgba(115, 115, 115, 0.03) 91%,rgba(63, 63, 63, 0.03) 91%, rgba(63, 63, 63, 0.03) 100%),linear-gradient(157.5deg, rgba(71, 71, 71, 0.03) 0%, rgba(71, 71, 71, 0.03) 6%,rgba(75, 75, 75, 0.03) 6%, rgba(75, 75, 75, 0.03) 15%,rgba(131, 131, 131, 0.03) 15%, rgba(131, 131, 131, 0.03) 18%,rgba(110, 110, 110, 0.03) 18%, rgba(110, 110, 110, 0.03) 37%,rgba(215, 215, 215, 0.03) 37%, rgba(215, 215, 215, 0.03) 62%,rgba(5, 5, 5, 0.03) 62%, rgba(5, 5, 5, 0.03) 100%),linear-gradient(90deg, #ffffff,#ffffff);" >

<!--MENU -->

<nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
  <a href="https://flowbite.com/" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo">
      <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Gestor Pedidos</span>
  </a>
  <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
      <a href="../../Login/index.php"><button type="button"  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cerrar session</button></a>
      <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
  </div>
  <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
    <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
      <li>
        <a href="../index.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Menu</a>
      </li>
      <li>
        <a href="registrarUsuario.php" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Registrar usuarios</a>
      </li>
      
      <li>
        <a href="../VerUsuario/VerUsuario.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Ver usuarios</a>
      </li>
      <li>
        <a href="../AsignarRepartidor/pedidos.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Asignar repartidor</a>
      </li>
        <li>
        <a href="../VerPedidos/Verpedidos.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Ver pedidos</a>
      </li>
    </ul>
  </div>
  </div>
</nav>

<!--Cuerpo -->

<br>
<div class="flex justify-center items-center mt-20">
<h1 class="mb-4 text-3xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-6xl"><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">Registrar</span> Usuario </h1>
</div>


<div class="flex justify-center items-center mt-10">
  <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
    <form class="max-w-md mx-auto" method="POST" action="">      
      <div class="relative z-0 w-full mb-5 group">
        <input type="text" name="nombre" id="nombre" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="nombre" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nombre</label>
      </div>
      <div class="relative z-0 w-full mb-5 group">
        <input type="text" name="apellido" id="apellido" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="apellido" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Apellido</label>
      </div>
      <div class="relative z-0 w-full mb-5 group">
        <input type="number" name="edad" id="edad" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="edad" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Edad</label>
      </div>
      <div class="grid md:grid-cols-2 md:gap-6">
        <div class="relative z-0 w-full mb-5 group">
          <input type="text" name="usuario" id="usuario" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
          <label for="usuario" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Usuario</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
          <input type="password" name="contrasena" id="contrasena" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
          <label for="contrasena" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Contraseña</label>
        </div>
      </div>
      <div class="grid md:grid-cols-2 md:gap-6">
        <div class="relative z-0 w-full mb-5 group">
          <input type="tel" name="telefono" id="telefono" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
          <label for="telefono" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Teléfono</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
          <input type="email" name="correo" id="correo" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
          <label for="correo" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Correo</label>
        </div>
      </div>
      <div class="relative z-0 w-full mb-5 group">
        <select name="tipo_usuario" id="tipo_usuario" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" required>
          <option value="" disabled selected></option>
          <option value="cliente">Cliente</option>
          <option value="repartidor">Repartidor</option>
          <option value="administrador">Administrador</option>
        </select>
        <label for="tipo_usuario" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Tipo de usuario</label>
      </div>
      <div class="relative z-0 w-full mb-5 group">
        <select name="estado" id="estado" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" required>
          <option value="" disabled selected></option>
          <option value="activo">Activo</option>
          <option value="inactivo">Inactivo</option>
        </select>
        <label for="estado" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Estado</label>
      </div>

      <!-- Alerta-->
      <div id="alert-container"></div>


<div class="flex justify-center items-center mt-8">
      <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Registrar</button>
   </div>
   <div class="flex justify-center items-center mt-8">
        <a href="../index.php" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Volver</a>
    </div>
   
    </form>
  </div>
</div>

<br>



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








<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>






<!--FOOTER -->

<footer class="bg-white rounded-lg shadow dark:bg-gray-900 m-4">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <a href="https://flowbite.com/" class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
                <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Gestor Pedidos</span>
            </a>
            <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-500 sm:mb-0 dark:text-gray-400">
                <li>
                    <a href="#" class="hover:underline me-4 md:me-6">About</a>
                </li>
                <li>
                    <a href="#" class="hover:underline me-4 md:me-6">Privacy Policy</a>
                </li>
                <li>
                    <a href="#" class="hover:underline me-4 md:me-6">Licensing</a>
                </li>
                <li>
                    <a href="#" class="hover:underline">Contact</a>
                </li>
            </ul>
        </div>
        <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
        <span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 <a href="https://flowbite.com/" class="hover:underline">Flowbite™</a>. All Rights Reserved.</span>
    </div>
</footer>

</body>
</html>

