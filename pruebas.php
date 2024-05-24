<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de prácticas FIEI UNFV</title>
    <link rel="icon" href="./logo/logo_fiei_2021.png">
    <link rel="stylesheet" href="inicio.css">
</head>
<body>
    <?php
    include_once("conexion.php");

    //Para alerta.php
    $alerta = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_usuario = $_POST['id_usuario'];
        $contrasena = $_POST['contrasena'];

        //Campos completos
        if (empty($id_usuario) || empty($contrasena)) {
            $alerta = "Por favor, complete todos los campos.";
        } else {
            // Verificar el usuario y contraseña en la base de datos
            $conexion = Cconexion::ConexionBD();
            $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuario = ? AND contrasena = ?");
            $consulta->execute([$id_usuario, $contrasena]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                // Usuario y contraseña válidos entonces lo envía a interfaz.php
                header('Location: interfaz.php?id_usuario=' . $id_usuario);
                exit;
            } else {
                // Usuario o contraseña incorrectos
                $alerta = "Usuario o contraseña incorrectos.";
            }
        }
    }
    ?>

    <div id="login">
        <h1>¡Bienvenido al nuevo sistema de prácticas de la FIEI!</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="id_usuario">ID de Usuario</label>
                <input type="text" id="id_usuario" name="id_usuario" placeholder="ID de Usuario">
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <div class="password-container">
                    <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña">
                    <span id="togglePassword" class="toggle-password">👁️</span>
                </div>
            </div>
            <button type="submit" id="loginButton">Iniciar sesión</button>
        </form>
        <!-- Mostrar la alerta -->
        <?php if (!empty($alerta)): ?>
        <div id="mensaje"><?php echo $alerta; ?></div>
        <?php endif; ?>
    </div>
    <div id="imagen">
        <img src="./logo/FIEI.jpg" alt="Imagen FIEI">
    </div>
    <script src="inicio.js"></script>
</body>
</html>
