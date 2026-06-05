<?php

session_start();

include "../CRUD/conexion.php";

$mensaje = "";

/*
    Si se envía el formulario de login.
*/
if ($_POST) {

    $email = $_POST["email"];
    $password = $_POST["password"];

    /*
        Buscamos el usuario por email.
    */
    $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE email = :email");
    $consulta->execute([
        ":email" => $email
    ]);

    $usuario = $consulta->fetch();

    /*
        Si existe el usuario y la contraseña coincide, iniciamos sesión.
    */
    if ($usuario && password_verify($password, $usuario["password"])) {

        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["usuario_nombre"] = $usuario["nombre"];
        $_SESSION["usuario_rol"] = $usuario["rol"];

        header("Location: ../index.php");
        exit();

    } else {
        $mensaje = "Email o contraseña incorrectos.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión - SportPro</title>
</head>
<body>

    <h1>Iniciar sesión</h1>

    <a href="../index.html">Volver al inicio</a>
    |
    <a href="registro.php">Crear cuenta</a>

    <br><br>

    <?php if ($mensaje != "") { ?>
        <p><?php echo $mensaje; ?></p>
    <?php } ?>

    <form method="post">

        <label>Email:</label><br>
        <input type="email" name="email" required>
        <br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit">Entrar</button>

    </form>

</body>
</html>