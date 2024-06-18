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

// Comprobar si se ha hecho clic en el botón "Volver"
if (isset($_POST['logout'])) {
    // Destruir todas las variables de sesión
    session_unset();
    // Destruir la sesión
    session_destroy();
    // Redirigir al usuario de vuelta al inicio de sesión
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Cliente</title>
</head>
<body>
    <h1>Bienvenido, <?php echo $usuario; ?>!</h1>
    <p>Tu ID de usuario es: <?php echo $userID; ?></p>
    <form method="post">
        <button type="submit" name="logout">Cerrar sesión</button>
    </form>
</body>
</html>

