<?php
// Datos recibidos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    // Obtiene los datos de la fila seleccionada
    $data = $_POST['data'];

    // Obtiene el ID de la fila
    $row_id = array_shift($data); 

    // Incrementar el ID en 1 solo para mostrarlo, pero no cambiar el valor real del ID en los datos enviados
    //$increment_row_id = $row_id + 1;

    
    echo '<form method="POST" action="guardar.php">';
    echo '<input type="hidden" name="row_id" value="' . $row_id . '">';

    // Iniciar la tabla HTML
    echo '<table border="1">';
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

    
    echo '<tr>';
    // Imprimir el ID en la primera columna
    echo '<td>' . $row_id . '</td>';
    // Agregar un campo oculto para enviar el ID
    echo '<input type="hidden" name="data[]" value="' . $row_id . '">';
    foreach ($data as $value) {
        // Mostrar cada valor dentro de un input editable
        echo '<td><input type="text" name="data[]" value="' . $value . '"></td>';
    }
    // Agregar un botón para guardar los cambios
    echo '<td><button type="submit">Guardar</button></td>';
    echo '</tr>';

    // Cerrar la tabla HTML y el formulario
    echo '</tbody>';
    echo '</table>';
    echo '</form>';
} else {
    // Si no se recibieron datos, mostrar un mensaje de error
    echo "No se recibieron datos para editar.";
}
?>

