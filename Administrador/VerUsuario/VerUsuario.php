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


// Verificar si se envió el formulario para despedir al usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['despedir'])) {
    // Obtener el ID del usuario a despedir
    $id = $_POST['id'];
    // Definir el estado "Inactivo"
    $estado_inactivo = "Inactivo";

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

    // Definir el rango de celda para actualizar el estado del usuario
    $range = 'Usuarios!K' . ($id + 1);

    // Crear los datos para actualizar el estado a "Inactivo"
    $data = new \Google_Service_Sheets_ValueRange([
        'values' => [[$estado_inactivo]]
    ]);

    // Configurar los parámetros de actualización
    $params = [
        'valueInputOption' => 'RAW'
    ];

    // Realizar la actualización del estado del usuario en Google Sheets
    $service->spreadsheets_values->update($spreadsheetId, $range, $data, $params);
    
    // Redireccionar de vuelta a la página de ver usuarios
    header('Location: VerUsuario.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
    
    <title>Usuarios</title>

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
        <a href="../RegistroUsuario/registrarUsuario.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Registrar usuarios</a>
      </li>
      <li>
        <a href="VerUsuario.php" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Ver usuarios</a>
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
<h1 class="mb-4 text-3xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-6xl"><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">Manejo</span> de usuarios </h1>
</div>


<div class="flex justify-center items-center mt-10">
    <form class="max-w-md mx-auto">   
        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Buscar</label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"></div>
            <input type="text" id="search" onkeyup="searchTable()" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar por Código Usuario" required />
        </div>
    </form>
</div>


<div class="flex justify-center items-center mt-10 px-10"> <!-- Agregado rellenado horizontal -->
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white dark:bg-gray-800 p-4"> <!-- Agregado rellenado vertical y fondo blanco -->
    <table id="pedidoTable" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-8">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th scope="col" class="px-6 py-3">ID</th>
          <th scope="col" class="px-6 py-3">Código Usuario</th>
          <th scope="col" class="px-6 py-3">Nombre</th>
          <th scope="col" class="px-6 py-3">Apellido</th>
          <th scope="col" class="px-6 py-3">Edad</th>
          <th scope="col" class="px-6 py-3">Usuario</th>
       
          <th scope="col" class="px-6 py-3">Teléfono</th>
          <th scope="col" class="px-6 py-3">Correo</th>
          <th scope="col" class="px-6 py-3">Tipo Usuario</th>
          <th scope="col" class="px-6 py-3">Estado</th>
          <th scope="col" class="px-6 py-3">Acciones</th>
        </tr>
      </thead>
    <tbody>
    <?php
    if ($values) {
        foreach ($values as $row) {
            echo "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600'>";
            // Excluir las columnas de contraseña y token (índices 6 y 11)
            for ($i = 0; $i < count($row); $i++) {
                if ($i != 6 && $i != 11) {
                    echo "<td class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white'>{$row[$i]}</td>";
                }
            }
            // Aquí se añade el formulario para enviar los datos a EditarUsuario.php
            echo "<td class='px-6 py-4 whitespace-nowrap text-right text-sm font-medium'>
                <form action='EditarUsuario.php' method='post'>
                    <input type='hidden' name='id' value='{$row[0]}'>
                    <input type='hidden' name='codigo_usuario' value='{$row[1]}'>
                    <input type='hidden' name='nombre' value='{$row[2]}'>
                    <input type='hidden' name='apellido' value='{$row[3]}'>
                    <input type='hidden' name='edad' value='{$row[4]}'>
                    <input type='hidden' name='usuario' value='{$row[5]}'>
                    <input type='hidden' name='telefono' value='{$row[7]}'>
                    <input type='hidden' name='correo' value='{$row[8]}'>
                    <input type='hidden' name='tipo_usuario' value='{$row[9]}'>
                    <input type='hidden' name='estado' value='{$row[10]}'>
                    <input type='hidden' name='token' value='{$row[11]}'>
                    <button type='submit' class='text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2'>Editar empleado</button>
                </form>
                <br>
                <form action='' method='post'>
                    <input type='hidden' name='id' value='{$row[0]}'>
                    <button type='submit' name='despedir' class='flowbite-button bg-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-white text-sm px-4 py-2'>Despedir</button>
                </form>
            </td>";
            echo "</tr>";
        }
    }
    ?>
</tbody>
    </table>
  </div>
</div>


<br>

<script>
    function searchTable() {
        // Obtiene el valor del input de búsqueda y lo convierte a minúsculas para comparación
        let input = document.getElementById("search").value.toLowerCase();
        // Obtiene la tabla y sus filas
        let table = document.getElementById("pedidoTable");
        let tr = table.getElementsByTagName("tr");

        // Recorre todas las filas de la tabla, excepto la primera (encabezados)
        for (let i = 1; i < tr.length; i++) {
            // Obtiene la celda que contiene el código de pedido
            let td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                // Si el texto de la celda coincide con el valor de búsqueda, muestra la fila, de lo contrario la oculta
                let txtValue = td.textContent || td.innerText;
                if (txtValue.toLowerCase().indexOf(input) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>




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

