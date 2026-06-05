<?php

include "../CRUD/conexion.php";

$mensaje = "";

/*
    Si se ha enviado el formulario, entramos aquí.
*/
if ($_POST) {

    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $dni = $_POST["dni"];
    $telefono = $_POST["telefono"];
    $password = $_POST["password"];

    /*
        Ciframos la contraseña.
        Así no se guarda como texto normal en la base de datos.
    */
    $passwordCifrada = password_hash($password, PASSWORD_DEFAULT);

    /*
        Primero comprobamos si ya existe un usuario con ese email.
    */
    $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE email = :email");
    $consulta->execute([
        ":email" => $email
    ]);

    $usuarioExiste = $consulta->fetch();

    if ($usuarioExiste) {
        $mensaje = "Ese correo ya está registrado.";
    } else {

        /*
            Si no existe, insertamos el usuario.
        */
        $consulta = $conexion->prepare(
            "INSERT INTO usuarios (nombre, email, dni, telefono, rol, activo, password)
             VALUES (:nombre, :email, :dni, :telefono, 'cliente', 1, :password)"
        );

        $consulta->execute([
            ":nombre" => $nombre,
            ":email" => $email,
            ":dni" => $dni,
            ":telefono" => $telefono,
            ":password" => $passwordCifrada
        ]);

        header("Location: inicio-sesion.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - SportPro</title>
</head>
<body>

    <h1>Registro</h1>

    <a href="../index.html">Volver al inicio</a>
    |
    <a href="inicio-sesion.php">Iniciar sesión</a>

    <br><br>

    <?php if ($mensaje != "") { ?>
        <p><?php echo $mensaje; ?></p>
    <?php } ?>

    <form method="post">

        <label>Nombre:</label><br>
        <input type="text" name="nombre" required>
        <br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required>
        <br><br>

        <label>DNI:</label><br>
        <input type="text" name="dni">
        <br><br>

        <label>Teléfono:</label><br>
        <input type="text" name="telefono">
        <br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit">Registrarse</button>

    </form>

</body>
</html>