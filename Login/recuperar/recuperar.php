<?php
require_once '../../vendor/autoload.php';

// Iniciar sesión
session_start();

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

// ID de la hoja de cálculo
$spreadsheetId = '1QgmCzgtygUVkGSIEOHGSdrQflhBEBxyhk7YP0x9DcT0';

// Obtener todos los datos
$range = 'Usuarios!A2:L'; 
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

// Función para generar un token aleatorio
function generarToken() {
    return 'T' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

// Verificar si se envió el formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['email'])) {
    $inputUsername = $_POST['username'];
    $inputEmail = $_POST['email'];

    $isAuthenticated = false;

    foreach ($values as $index => $row) {
        $username = $row[5]; 
        $userEmail = $row[8]; 
        $userRole = $row[9]; 
        $estado = $row[10]; 
        $token = $row[11];

        if ($username === $inputUsername && $userEmail === $inputEmail) {
            if ($estado === 'activo' || $estado === 'Activo') {
                $isAuthenticated = true;
                $_SESSION['user'] = $username;
                $_SESSION['role'] = $userRole;
                $_SESSION['user_id'] = $row[0];

                // Generar un nuevo token
                $newToken = generarToken();

                // Actualizar el token en Google Sheets
                $updateRange = 'Usuarios!L' . ($index + 2);
                $updateBody = new \Google_Service_Sheets_ValueRange([
                    'values' => [[ $newToken ]]
                ]);
                $params = ['valueInputOption' => 'RAW'];
                $service->spreadsheets_values->update($spreadsheetId, $updateRange, $updateBody, $params);

                // Almacenar el token en la sesión
                $_SESSION['token'] = $newToken;

             
                header('Location: validarToken.php');
                exit;
            } else {
                $error = "Tu cuenta está inactiva. Por favor, contacta con el administrador.";
            }
        }
    }

    if (!$isAuthenticated && !isset($error)) {
        $error = "Usuario o correo electrónico incorrectos.";
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
    
    <title>Recuperar contraseña</title>
</head>

<style>
background-image: linear-gradient(305deg, rgba(254, 254, 254,0.02) 0%, rgba(254, 254, 254,0.02) 1%,transparent 1%, transparent 50%,rgba(220, 220, 220,0.02) 50%, rgba(220, 220, 220,0.02) 64%,rgba(249, 249, 249,0.02) 64%, rgba(249, 249, 249,0.02) 100%),linear-gradient(38deg, rgba(70, 70, 70,0.02) 0%, rgba(70, 70, 70,0.02) 35%,transparent 35%, transparent 62%,rgba(152, 152, 152,0.02) 62%, rgba(152, 152, 152,0.02) 74%,rgba(99, 99, 99,0.02) 74%, rgba(99, 99, 99,0.02) 100%),linear-gradient(337deg, rgba(124, 124, 124,0.02) 0%, rgba(124, 124, 124,0.02) 45%,transparent 45%, transparent 55%,rgba(34, 34, 34,0.02) 55%, rgba(34, 34, 34,0.02) 72%,rgba(189, 189, 189,0.02) 72%, rgba(189, 189, 189,0.02) 100%),linear-gradient(92deg, rgba(239, 239, 239,0.02) 0%, rgba(239, 239, 239,0.02) 12%,transparent 12%, transparent 22%,rgba(204, 204, 204,0.02) 22%, rgba(204, 204, 204,0.02) 51%,rgba(70, 70, 70,0.02) 51%, rgba(70, 70, 70,0.02) 100%),linear-gradient(90deg, rgb(255,255,255),rgb(255,255,255));
  </style>
<body  style="background-image: linear-gradient(22.5deg, rgba(242, 242, 242, 0.03) 0%, rgba(242, 242, 242, 0.03) 16%,rgba(81, 81, 81, 0.03) 16%, rgba(81, 81, 81, 0.03) 26%,rgba(99, 99, 99, 0.03) 26%, rgba(99, 99, 99, 0.03) 73%,rgba(43, 43, 43, 0.03) 73%, rgba(43, 43, 43, 0.03) 84%,rgba(213, 213, 213, 0.03) 84%, rgba(213, 213, 213, 0.03) 85%,rgba(125, 125, 125, 0.03) 85%, rgba(125, 125, 125, 0.03) 100%),linear-gradient(22.5deg, rgba(25, 25, 25, 0.03) 0%, rgba(25, 25, 25, 0.03) 54%,rgba(144, 144, 144, 0.03) 54%, rgba(144, 144, 144, 0.03) 60%,rgba(204, 204, 204, 0.03) 60%, rgba(204, 204, 204, 0.03) 76%,rgba(37, 37, 37, 0.03) 76%, rgba(37, 37, 37, 0.03) 78%,rgba(115, 115, 115, 0.03) 78%, rgba(115, 115, 115, 0.03) 91%,rgba(63, 63, 63, 0.03) 91%, rgba(63, 63, 63, 0.03) 100%),linear-gradient(157.5deg, rgba(71, 71, 71, 0.03) 0%, rgba(71, 71, 71, 0.03) 6%,rgba(75, 75, 75, 0.03) 6%, rgba(75, 75, 75, 0.03) 15%,rgba(131, 131, 131, 0.03) 15%, rgba(131, 131, 131, 0.03) 18%,rgba(110, 110, 110, 0.03) 18%, rgba(110, 110, 110, 0.03) 37%,rgba(215, 215, 215, 0.03) 37%, rgba(215, 215, 215, 0.03) 62%,rgba(5, 5, 5, 0.03) 62%, rgba(5, 5, 5, 0.03) 100%),linear-gradient(90deg, #ffffff,#ffffff);" >



 <div class="flex justify-center items-center mt-20">
  
<h1 class="mb-4 text-3xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-6xl"><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">Recupera tu</span> contraseña </h1>

    </div>
<div class="flex justify-center items-center mt-8">
    
  

<div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
    <form class="space-y-6" method="POST" action="">
        <h5 class="text-xl font-medium text-gray-900 dark:text-white">Ingresa los siguientes datos</h5>
        <div>
            <?php if (isset($error)): ?>
                    <div class="text-red-500 text-sm"><?= $error ?></div>
                <?php endif; ?>

            <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Usuario</label>
            <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="user123" required />
        </div>
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo electronico</label>
            <input type="email" name="email" id="email" placeholder="name@company.com" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
        </div>
       
        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Logueate</button>
        
    </form>

    <div class="flex justify-center items-center mt-8">
  <a href="../index.php" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
    Volver
  </a>
</div>
</div>

<br>


<br>
</body>
</html>