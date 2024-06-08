<?php
require_once '../vendor/autoload.php';

// Iniciar sesión
session_start();

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

// ID de la hoja de cálculo
$spreadsheetId = '1QgmCzgtygUVkGSIEOHGSdrQflhBEBxyhk7YP0x9DcT0';

// Obtener todos los datos
$range = 'Usuarios!A2:L'; 
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

// Verificar si se envió el formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    $isAuthenticated = false;

    foreach ($values as $row) {
        $username = $row[5]; // Suponiendo que la columna 6 (índice 5) contiene el nombre de usuario
        $userPassword = $row[6]; // Suponiendo que la columna 7 (índice 6) contiene la contraseña encriptada
        $userRole = $row[9]; // Suponiendo que la columna 10 (índice 9) contiene el rol del usuario
        $estado = $row[10]; // Suponiendo que la columna 12 (índice 11) contiene el estado del usuario

        // Comparar el nombre de usuario y verificar la contraseña encriptada
        if ($username === $inputUsername && password_verify($inputPassword, $userPassword)) {
            // Verificar el estado del usuario
            if ($estado === 'activo') {
                $isAuthenticated = true;
                $_SESSION['user'] = $username;
                $_SESSION['role'] = $userRole;
                $_SESSION['user_id'] = $row[0];

                // Redirigir según el rol del usuario
                if ($userRole === 'cliente') {
                    header('Location: ../Cliente/index.php');
                    exit;
                } elseif ($userRole === 'administrador') {
                    header('Location: ../Administrador/index.php');
                    exit;
                } elseif ($userRole === 'repartidor') {
                    header('Location: ../Repartidor/index.php');
                    exit;
                }
            } else {
                // Si el usuario está inactivo, mostrar un mensaje de error
                $error = "Tu cuenta está inactiva. Por favor, contacta con el administrador.";
            }
        }
    }

    if (!$isAuthenticated && !isset($error)) {
        // Si las credenciales no coinciden, mostrar un mensaje de error
        $error = "Usuario o contraseña incorrectos.";
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
    <title>Login</title>
</head>
<body style="background-image: linear-gradient(22.5deg, rgba(242, 242, 242, 0.03) 0%, rgba(242, 242, 242, 0.03) 16%,rgba(81, 81, 81, 0.03) 16%, rgba(81, 81, 81, 0.03) 26%,rgba(99, 99, 99, 0.03) 26%, rgba(99, 99, 99, 0.03) 73%,rgba(43, 43, 43, 0.03) 73%, rgba(43, 43, 43, 0.03) 84%,rgba(213, 213, 213, 0.03) 84%, rgba(213, 213, 213, 0.03) 85%,rgba(125, 125, 125, 0.03) 85%, rgba(125, 125, 125, 0.03) 100%),linear-gradient(22.5deg, rgba(25, 25, 25, 0.03) 0%, rgba(25, 25, 25, 0.03) 54%,rgba(144, 144, 144, 0.03) 54%, rgba(144, 144, 144, 0.03) 60%,rgba(204, 204, 204, 0.03) 60%, rgba(204, 204, 204, 0.03) 76%,rgba(37, 37, 37, 0.03) 76%, rgba(37, 37, 37, 0.03) 78%,rgba(115, 115, 115, 0.03) 78%, rgba(115, 115, 115, 0.03) 91%,rgba(63, 63, 63, 0.03) 91%, rgba(63, 63, 63, 0.03) 100%),linear-gradient(157.5deg, rgba(71, 71, 71, 0.03) 0%, rgba(71, 71, 71, 0.03) 6%,rgba(75, 75, 75, 0.03) 6%, rgba(75, 75, 75, 0.03) 15%,rgba(131, 131, 131, 0.03) 15%, rgba(131, 131, 131, 0.03) 18%,rgba(110, 110, 110, 0.03) 18%, rgba(110, 110, 110, 0.03) 37%,rgba(215, 215, 215, 0.03) 37%, rgba(215, 215, 215, 0.03) 62%,rgba(5, 5, 5, 0.03) 62%, rgba(5, 5, 5, 0.03) 100%),linear-gradient(90deg, #ffffff,#ffffff);">
     <div class="flex justify-center items-center mt-20">
  
<h1 class="mb-4 text-3xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-6xl"><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">Logueate con</span> nosotros </h1>

    </div>
    <div class="flex justify-center items-center mt-8">
        <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
            <form class="space-y-6" method="POST" action="">
                               <h5 class="text-xl font-medium text-gray-900 dark:text-white">Inicia sesión para empezar</h5>
                <?php if (isset($error)): ?>
                    <div class="text-red-500 text-sm"><?= $error ?></div>
                <?php endif; ?>
                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Usuario</label>
                    <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Usuario" required />
                </div>
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                </div>
                <div class="flex items-start">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="remember" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" />
                        </div>
                        <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Recordar credenciales</label>
                    </div>
                    <a href="./recuperar/recuperar.php" class="ms-auto text-sm text-blue-700 hover:underline dark:text-blue-500">Olvidé mi contraseña?</a>
                </div>
                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Logueate</button>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                    Sin registrarte? <a href="register.php" class="text-blue-700 hover:underline dark:text-blue-500">Crea una cuenta</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

