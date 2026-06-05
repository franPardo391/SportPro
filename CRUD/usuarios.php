<?php

include "conexion.php";

/*
    Cantidad de usuarios que quiero mostrar por cada página.
    Si pongo 5, salen 5 usuarios por página.
*/
$usuariosPorPagina = 5;

/*
    Recogemos la página actual desde la URL.

    Ejemplo:
    usuarios.php?pagina=2

    Si no viene ninguna página, usamos la página 1.
*/
$pagina = $_GET["pagina"] ?? 1;

/*
    Convertimos la página a número entero.
    Esto evita que llegue texto raro por la URL.
*/
$pagina = (int) $pagina;

/*
    Si alguien pone una página menor que 1,
    la dejamos en 1 para evitar errores.
*/
if ($pagina < 1) {
    $pagina = 1;
}

/*
    Calculamos desde qué usuario empieza la página.

    Página 1:
    (1 - 1) * 5 = 0

    Página 2:
    (2 - 1) * 5 = 5

    Página 3:
    (3 - 1) * 5 = 10
*/
$inicio = ($pagina - 1) * $usuariosPorPagina;

/*
    Contamos cuántos usuarios hay en total en la tabla.
    Esto sirve para saber cuántas páginas hay.
*/
$consultaTotal = $conexion->query("SELECT COUNT(*) FROM usuarios");
$totalUsuarios = $consultaTotal->fetchColumn();

/*
    Calculamos el número total de páginas.

    ceil() redondea hacia arriba.
    Ejemplo:
    12 usuarios / 5 por página = 2,4
    ceil(2,4) = 3 páginas
*/
$totalPaginas = ceil($totalUsuarios / $usuariosPorPagina);

/*
    Sacamos solo los usuarios de la página actual.

    LIMIT indica cuántos usuarios queremos.
    OFFSET indica desde dónde empezamos.
*/
$consulta = $conexion->query("SELECT * FROM usuarios LIMIT $usuariosPorPagina OFFSET $inicio");

/*
    fetchAll() guarda todos los usuarios encontrados
    dentro de la variable $usuarios.
*/
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
                <td><?php echo $usuario["password"]; ?></td>
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

    <div class="paginador">

        <?php if ($pagina > 1) { ?>
            <a href="usuarios.php?pagina=<?php echo $pagina - 1; ?>">← Anterior</a>
        <?php } ?>

        <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>

            <?php if ($i == $pagina) { ?>
                <strong><?php echo $i; ?></strong>
            <?php } else { ?>
                <a href="usuarios.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php } ?>

        <?php } ?>

        <?php if ($pagina < $totalPaginas) { ?>
            <a href="usuarios.php?pagina=<?php echo $pagina + 1; ?>">Siguiente →</a>
        <?php } ?>

    </div>

</body>
</html>