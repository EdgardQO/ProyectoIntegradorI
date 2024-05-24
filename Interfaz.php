<?php
include_once("conexion.php");

//Obtenemos el id_usuario desde pruebas.php
$id_usuario = $_GET['id_usuario'];

//Verificamos que el id_usuario sea correcto
if (empty($id_usuario)) {
    echo "ID de usuario no proporcionado.";
    exit;
}

//Obtenemos los datos de la BD
$conexion = Cconexion::ConexionBD();
$consulta = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$consulta->execute([$id_usuario]);
$usuario = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit;
}

//Formatos de imagen permitidos
$extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'];

//URL para buscar la imagen
$imageUrl = null;
foreach ($extensiones_permitidas as $extension) {
    $posible_ruta = 'img/' . $id_usuario . '.' . $extension;
    if (file_exists($posible_ruta)) {
        $imageUrl = $posible_ruta;
        break;//Si se encuentra la imagen se sale del bucle
    }
}

if (!$imageUrl) {
    //Si no se encuentra la imagen entonces se usa la default
    $imageUrl = 'img/default.jpg'; //Cambia default.jpg por la imagen que quieras usar por defecto
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interfaz</title>
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
            <img src="./iconos/configuracion (2).png" alt="Configuración" class="header-icon">
            <img src="./iconos/notificacion (1).png" alt="Notificaciones" class="header-icon">
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
            <form class="user-form" action="Actualizardatos.php" method="get">
                <fieldset>
                    <legend>Datos del Usuario</legend>
                    <div class="user-photo">
                        <img src="<?php echo $imageUrl; ?>" alt="Foto del Usuario">
                    </div>
                    <div class="user-details">
                        <div class="left-column">
                            <div class="field">
                                <strong>Nombre:</strong>
                                <p><?php echo $usuario['nombre']; ?></p>
                            </div>
                            <div class="field">
                                <strong>Edad:</strong>
                                <p><?php echo $usuario['edad']; ?> años</p>
                            </div>
                        </div>
                        <div class="right-column">
                            <div class="field">
                                <strong>Apellido:</strong>
                                <p><?php echo $usuario['apellido']; ?></p>
                            </div>
                            <div class="field">
                                <strong>Email:</strong>
                                <p><?php echo $usuario['email']; ?></p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="update-button" name="id_usuario" value="<?php echo $id_usuario; ?>">Actualizar Datos</button>
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
