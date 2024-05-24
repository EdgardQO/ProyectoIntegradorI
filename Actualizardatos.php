<?php
include_once("conexion.php");

//Obtener ID de usuario de la URL
$id_usuario = $_GET['id_usuario'];

//Verificar que id_usuario esté presente en la URL
if (empty($id_usuario)) {
    echo "ID de usuario no proporcionado.";
    exit;
}

//Obtener datos de la BD
$conexion = Cconexion::ConexionBD();
$consulta = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$consulta->execute([$id_usuario]);
$usuario = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit;
}

//Variables con la BD
$nombre = $usuario['nombre'];
$edad = $usuario['edad'];
$apellido = $usuario['apellido'];
$email = $usuario['email'];

//Generar la URL de la imagen del usuario
$extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'];

//Buscar la imagen existente con cualquier formato permitido
foreach ($extensiones_permitidas as $extension) {
    $imageUrl = 'img/' . $id_usuario . '.' . $extension;
    if (file_exists($imageUrl)) {
        break;
    }
}

//Si no se encuentra ninguna imagen se establece una imagen predeterminada
if (!isset($imageUrl)) {
    $imageUrl = 'ruta/a/imagen_predeterminada.png'; //Ruta de img predeterminada
}

//Verifica el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Actualiza solo los campos que se han ingresado en el formulario
    if (!empty($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
    }
    if (!empty($_POST['edad'])) {
        $edad = $_POST['edad'];
    }
    if (!empty($_POST['apellido'])) {
        $apellido = $_POST['apellido'];
    }
    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
    }

    //Actualiza los datos del usuario en la base de datos
    $consulta = $conexion->prepare("UPDATE usuarios SET nombre = ?, edad = ?, apellido = ?, email = ? WHERE id_usuario = ?");
    $resultado = $consulta->execute([$nombre, $edad, $apellido, $email, $id_usuario]);

    if ($resultado) {
        //echo "Datos actualizados correctamente."; //Solo para comprobar y luego lo comentas
    } else {
        //echo "Error al actualizar los datos.";
    }

    //Manejar la carga de la nueva foto del usuario
    if ($_FILES['foto_usuario']['error'] === UPLOAD_ERR_OK) {
        //Obtener el nombre temporal y el nombre de archivo
        $nombre_temporal = $_FILES['foto_usuario']['tmp_name'];
        $nombre_original = $_FILES['foto_usuario']['name'];
        $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);

        //Verificar si la extensión del archivo es permitida
        if (in_array($extension, $extensiones_permitidas)) {
            $nombre_archivo = 'img/' . $id_usuario . '.' . $extension;

            //Eliminar la imagen anterior si existe
            if (file_exists($imageUrl)) {
                unlink($imageUrl);
            }

            //Mover el archivo temporal a su ubicación definitiva
            if (move_uploaded_file($nombre_temporal, $nombre_archivo)) {
                echo "Foto de usuario actualizada correctamente.";
                //Actualizar la URL de la imagen del usuario
                $imageUrl = $nombre_archivo;
            } else {
                echo "Error al subir la foto de usuario.";
            }
        } else {
            echo "Formato de imagen no permitido.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Datos del Usuario</title>
    <link rel="icon" href="./logo/logo_fiei_2021.png">
    <link rel="stylesheet" href="interfaz.css">
</head>
<body>
    <header>
        <div class="logo-title">
            <img src="./logo/logo_fiei_2021.png" alt="Logo">
            <h1>¡Gracias por usar el nuevo sistema de prácticas de la FIEI!</h1>
        </div>
        <div class="header-icons">
            <img src="./icono/configuracion (2).png" alt="Configuración" class="header-icon">
            <img src="./icono/notificacion (1).png" alt="Notificaciones" class="header-icon">
        </div>
    </header>

    <section>
        <nav class="menu">
            <ul>
                <li><a href="interfaz.php?id_usuario=<?php echo $id_usuario; ?>" class="selected">Inicio</a></li>
                <li><a href="Archivos.php?id_usuario=<?php echo $id_usuario; ?>">Mis archivos</a></li>
                <li><a href="#">Opción 3</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <!-- Contenido principal -->
            <form class="user-form" action="actualizardatos.php?id_usuario=<?php echo $id_usuario; ?>" method="POST" enctype="multipart/form-data">
                <fieldset>
                    <legend>Datos del Usuario</legend>
                    <div class="user-photo">
                        <img src="<?php echo $imageUrl; ?>" alt="Foto del Usuario">
                    </div>
                    <div class="user-details">
                        <div class="left-column">
                            <div class="field">
                                <strong>Nombre:</strong>
                                <input type="text" name="nombre" value="<?php echo $nombre; ?>">
                            </div>
                            <div class="field">
                                <strong>Edad:</strong>
                                <input type="number" name="edad" value="<?php echo $edad; ?>">
                            </div>
                        </div>
                        <div class="right-column">
                            <div class="field">
                                <strong>Apellido:</strong>
                                <input type="text" name="apellido" value="<?php echo $apellido; ?>">
                            </div>
                            <div class="field">
                                <strong>Email:</strong>
                                <input type="email" name="email" value="<?php echo $email; ?>">
                            </div>
                            <div class="field">
                                <strong>Foto:</strong>
                                <input type="file" name="foto_usuario">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="update-button">Actualizar Datos</button>
                    <a href="interfaz.php?id_usuario=<?php echo $id_usuario; ?>" class="update-button">Volver a Interfaz</a>
                </fieldset>
            </form>
            <div class="news-container">
                <div class="news">
                    <h2>Noticia 1</h2>
                    <p>Contenido de la noticia 1...</p>
                </div>
                <div class="news">
                    <h2>Noticia 2</h2>
                    <p>Contenido de la noticia 2...</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        © 2024 Todos los derechos reservados.
    </footer>
</body>
</html>
