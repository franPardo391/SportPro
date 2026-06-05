<?php

include "conexion.php";

/*
    Este archivo sirve para CREAR usuarios.

    Primero se muestra un formulario.
    Cuando el formulario se envía, PHP recoge los datos
    y los guarda en la base de datos.
*/

if ($_POST) {

    /*
        Recogemos los datos que vienen del formulario.

        El name del input tiene que coincidir con esto:
        name="nombre"    -> $_POST["nombre"]
        name="email"     -> $_POST["email"]
        name="dni"       -> $_POST["dni"]
        name="telefono"  -> $_POST["telefono"]
        name="rol"       -> $_POST["rol"]
        name="activo"    -> $_POST["activo"]
        name="password"  -> $_POST["password"]
    */

    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $dni = $_POST["dni"];
    $telefono = $_POST["telefono"];
    $rol = $_POST["rol"];
    $activo = $_POST["activo"];
    $password = $_POST["password"];

    /*
        Ciframos la contraseña antes de guardarla.

        Así en la base de datos no se guarda como texto normal.
    */

    $passwordCifrada = password_hash($password, PASSWORD_DEFAULT);

    /*
        Preparamos la consulta INSERT.

        INSERT INTO sirve para meter datos nuevos en una tabla.
    */

    $consulta = $conexion->prepare(
        "INSERT INTO usuarios (nombre, email, dni, telefono, rol, activo, password)
         VALUES (:nombre, :email, :dni, :telefono, :rol, :activo, :password)"
    );

    /*
        Ejecutamos la consulta.

        Aquí se sustituyen los parámetros:
        :nombre, :email, :dni, etc.
        por los valores reales del formulario.
    */

    $consulta->execute([
        ":nombre" => $nombre,
        ":email" => $email,
        ":dni" => $dni,
        ":telefono" => $telefono,
        ":rol" => $rol,
        ":activo" => $activo,
        ":password" => $passwordCifrada
    ]);

    /*
        Después de crear el usuario, volvemos al listado.
    */

    header("Location: usuarios.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear usuario</title>
</head>
<body>

    <h1>Crear usuario</h1>

    <a href="usuarios.php">Volver al listado</a>

    <br><br>

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

        <label>Rol:</label><br>
        <select name="rol">
            <option value="cliente">Cliente</option>
            <option value="gerente">Gerente</option>
            <option value="admin">Admin</option>
        </select>
        <br><br>

        <label>Activo:</label><br>
        <select name="activo">
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>
        <br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit">Crear usuario</button>

    </form>

</body>
</html>