
<?php
// Iniciar sesión si aún no se ha iniciado
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user'])) {
    // Redirigir al usuario de vuelta al inicio de sesión si no ha iniciado sesión
    header('Location: index.php');
    exit;
}

// Acceder a la variable de sesión del nombre de usuario
$usuario = $_SESSION['user'];

// Acceder a la variable de sesión del ID de usuario
$userID = $_SESSION['user_id'];

// Verificar si el código de usuario está definido en la sesión
if (!isset($_SESSION['codigo_usuario'])) {
    // Manejar el caso en que la variable de sesión no esté definida
    echo "Error: El código de usuario no está definido.";
    exit;
}

// Acceder a la variable de sesión del código de usuario
$codigo_usuario = $_SESSION['codigo_usuario'];

// Comprobar si se ha hecho clic en el botón "Volver"
if (isset($_POST['logout'])) {
    // Destruir todas las variables de sesión
    session_unset();
    // Destruir la sesión
    session_destroy();
    // Redirigir al usuario de vuelta al inicio de sesión
    header('Location: ../Login/index.php');
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
    
    <title>Repartidor</title>

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
      
          <a href="./RegistroUsuario/registrarUsuario.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"><div class="relative w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
    <svg class="absolute w-12 h-12 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
</div></a>
 
  </div>
   <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
      
      <a href="../Login/index.php"><button type="button"  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cerrar session</button></a>
      
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
        <a href="index.php" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Menu</a>
      </li>
      <li>
        <a href="./VerMisPedidos/misPedidos.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Pedidos asignados</a>
      </li>
      <li>
        <a href="./PedidosHechos/pedidosHechos.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Pedidos realizados</a>
      </li>
    
    </ul>
  </div>
  </div>
</nav>

<!--Cuerpo -->

<br>
<div class="flex justify-center items-center mt-20">
    

<h1 class="mb-4 text-3xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-6xl"><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">Bienvenido</span> <?php echo $usuario; ?> </h1>

</div>


<div class="flex justify-center items-center mt-10">

  <div class="grid grid-cols-1 gap-8 md:grid-cols-2">

    

    
      <div>
      <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
     <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 21 21">
    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6.072 10.072 2 2 6-4m3.586 4.314.9-.9a2 2 0 0 0 0-2.828l-.9-.9a2 2 0 0 1-.586-1.414V5.072a2 2 0 0 0-2-2H13.8a2 2 0 0 1-1.414-.586l-.9-.9a2 2 0 0 0-2.828 0l-.9.9a2 2 0 0 1-1.414.586H5.072a2 2 0 0 0-2 2v1.272a2 2 0 0 1-.586 1.414l-.9.9a2 2 0 0 0 0 2.828l.9.9a2 2 0 0 1 .586 1.414v1.272a2 2 0 0 0 2 2h1.272a2 2 0 0 1 1.414.586l.9.9a2 2 0 0 0 2.828 0l.9-.9a2 2 0 0 1 1.414-.586h1.272a2 2 0 0 0 2-2V13.8a2 2 0 0 1 .586-1.414Z"/>
</svg>
        <a href="./VerMisPedidos/misPedidos.php">
            <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Pedidos asignados</h5>
        </a>
        <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">Apartado para ver los pedidos que me han asignado</p>
        <a href="./VerMisPedidos/misPedidos.php" class="inline-flex font-medium items-center text-blue-600 hover:underline">
            Ver mis pedidos
            <svg class="w-3 h-3 ms-2.5 rtl:rotate-[270deg]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778"/>
            </svg>
        </a>
      </div>
    </div>

      <div>
      <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
     <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
    <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M8 8v1h4V8m4 7H4a1 1 0 0 1-1-1V5h14v9a1 1 0 0 1-1 1ZM2 1h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Z"/>
</svg>
        <a href="./PedidosHechos/pedidosHechos.php">
            <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Pedidos realizados</h5>
        </a>
        <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">Apartado para ver los pedidos que he realizado en la plataforma</p>
        <a href="./PedidosHechos/pedidosHechos.php" class="inline-flex font-medium items-center text-blue-600 hover:underline">
            Ver mis pedidos hechos
            <svg class="w-3 h-3 ms-2.5 rtl:rotate-[270deg]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778"/>
            </svg>
        </a>
      </div>
    </div>



  </div>



  
  
</div>

<br>


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

