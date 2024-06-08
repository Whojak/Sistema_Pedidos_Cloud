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
$username = $_SESSION['user'];

// Acceder a la variable de sesión del ID de usuario
$userId = $_SESSION['user_id'];

// Ahora puedes usar $username y $userId como desees en tu página de destino
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Cliente</title>
</head>
<body>
    <h1>Bienvenido, <?php echo $username; ?>!</h1>
    <p>Tu ID de usuario es: <?php echo $userId; ?></p>
</body>
</html>
