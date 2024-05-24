<?php
//Muestra una alerta según los campos en pruebas.php
if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];
    switch ($tipo) {
        case 'usuario':
            $mensaje = "Usuario incorrecto";
            break;
        case 'contrasena':
            $mensaje = "Contraseña incorrecta";
            break;
        case 'vacio':
            $mensaje = "Por favor, complete todos los campos";
            break;
        default:
            $mensaje = "Error desconocido";
    }
    echo "<script>mostrarAlerta('$mensaje');</script>";
}
?>
