<?php

include "conexion.php";


$id = $_GET["id"];

/*
    Si el formulario se ha enviado, actualizamos el usuario.
*/

if ($_POST) {

    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $dni = $_POST["dni"];
    $telefono = $_POST["telefono"];
    $rol = $_POST["rol"];
    $activo = $_POST["activo"];

    /*
        WHERE id = :id indica qué usuario vamos a cambiar.
    */

    $consulta = $conexion->prepare(
        "UPDATE usuarios 
         SET nombre = :nombre,
             email = :email,
             dni = :dni,
             telefono = :telefono,
             rol = :rol,
             activo = :activo
         WHERE id = :id"
    );

    $consulta->execute([
        ":nombre" => $nombre,
        ":email" => $email,
        ":dni" => $dni,
        ":telefono" => $telefono,
        ":rol" => $rol,
        ":activo" => $activo,
        ":id" => $id
    ]);

    /*
        Después de actualizar, volvemos al listado.
    */

    header("Location: usuarios.php");
    exit();
}

/*
    Si todavía no se ha enviado el formulario,
    buscamos el usuario para mostrar sus datos en el formulario.
*/

$consulta = $conexion->prepare("SELECT * FROM usuarios WHERE id = :id");

$consulta->execute([
    ":id" => $id
]);

$usuario = $consulta->fetch();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar usuario</title>
</head>
<body>

    <h1>Editar usuario</h1>

    <a href="usuarios.php">Volver al listado</a>

    <br><br>

    <form method="post">

        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?php echo $usuario["nombre"]; ?>" required>
        <br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo $usuario["email"]; ?>" required>
        <br><br>

        <label>DNI:</label><br>
        <input type="text" name="dni" value="<?php echo $usuario["dni"]; ?>">
        <br><br>

        <label>Teléfono:</label><br>
        <input type="text" name="telefono" value="<?php echo $usuario["telefono"]; ?>">
        <br><br>

        <label>Rol:</label><br>
        <select name="rol">
            <option value="cliente" <?php if ($usuario["rol"] == "cliente") echo "selected"; ?>>Cliente</option>
            <option value="gerente" <?php if ($usuario["rol"] == "gerente") echo "selected"; ?>>Gerente</option>
            <option value="admin" <?php if ($usuario["rol"] == "admin") echo "selected"; ?>>Admin</option>
        </select>
        <br><br>

        <label>Activo:</label><br>
        <select name="activo">
            <option value="1" <?php if ($usuario["activo"] == 1) echo "selected"; ?>>Sí</option>
            <option value="0" <?php if ($usuario["activo"] == 0) echo "selected"; ?>>No</option>
        </select>
        <br><br>

        <button type="submit">Guardar cambios</button>

    </form>

</body>
</html>