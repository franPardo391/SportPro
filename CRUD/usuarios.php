<?php

include "conexion.php";


$usuariosPorPagina = 5;

$pagina = $_GET["pagina"] ?? 1;

$pagina = (int) $pagina;

if ($pagina < 1) {
    $pagina = 1;
}

$inicio = ($pagina - 1) * $usuariosPorPagina;

$consulta = $conexion->query("SELECT * FROM usuarios LIMIT $usuariosPorPagina OFFSET $inicio");

$usuarios = $consulta->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de usuarios</title>
</head>
<body>

    <h1>Listado de usuarios</h1>

    <a href="../index.html">Volver al inicio</a>
    |
    <a href="crear.php">Crear nuevo usuario</a>

    <br><br>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Contraseña</th>
            <th>Rol</th>
            <th>Activo</th>
            <th>Acciones</th>
            
        </tr>

<?php foreach ($usuarios as $usuario) { ?>
    <tr>
        <td><?php echo $usuario["id"]; ?></td>
        <td><?php echo $usuario["nombre"]; ?></td>
        <td><?php echo $usuario["email"]; ?></td>
        <td><?php echo $usuario["dni"]; ?></td>
        <td><?php echo $usuario["telefono"]; ?></td>
        <td><?php echo $usuario["password"];?></td>
        <td><?php echo $usuario["rol"]; ?></td>
        <td><?php echo $usuario["activo"]; ?></td>
        <td>
            <a href="editar.php?id=<?php echo $usuario["id"]; ?>">Editar</a>
    |
            <a href="borrar.php?id=<?php echo $usuario["id"]; ?>">Borrar</a>
        </td>
    </tr>
<?php } ?>

    </table>

    <br>

    <a href="usuarios.php?pagina=1">Página 1</a>
    <a href="usuarios.php?pagina=2">Página 2</a>
    <a href="usuarios.php?pagina=3">Página 3</a>

</body>
</html>