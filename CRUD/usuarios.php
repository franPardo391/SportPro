<?php

include "conexion.php";

/*
    Número de usuarios que queremos mostrar por página.
*/
$usuariosPorPagina = 5;

/*
    Recogemos la página desde la URL.
    Si no viene ninguna página, usamos la página 1.
*/
$pagina = $_GET["pagina"] ?? 1;
$pagina = (int) $pagina;

if ($pagina < 1) {
    $pagina = 1;
}

/*
    Recogemos el texto del buscador.
    Si no se ha buscado nada, queda vacío.
*/
$buscar = $_GET["buscar"] ?? "";

/*
    Calculamos desde qué usuario empieza la página.
*/
$inicio = ($pagina - 1) * $usuariosPorPagina;

/*
    Si el buscador está vacío, mostramos todos los usuarios.
    Si el buscador tiene texto, filtramos por nombre, DNI o email.
*/
if ($buscar == "") {

    /*
        Contamos todos los usuarios.
    */
    $consultaTotal = $conexion->query("SELECT COUNT(*) FROM usuarios");
    $totalUsuarios = $consultaTotal->fetchColumn();

    /*
        Sacamos los usuarios de la página actual.
    */
    $consulta = $conexion->query("SELECT * FROM usuarios LIMIT $usuariosPorPagina OFFSET $inicio");
    $usuarios = $consulta->fetchAll();

} else {

    /*
        Preparamos el texto para buscar con LIKE.
        Los porcentajes sirven para buscar coincidencias parciales.

        Ejemplo:
        Si escribo "fra", busca cualquier nombre, dni o email que contenga "fra".
    */
    $textoBuscar = "%" . $buscar . "%";

    /*
        Contamos cuántos usuarios coinciden con la búsqueda.
    */
    $consultaTotal = $conexion->prepare(
        "SELECT COUNT(*) FROM usuarios 
         WHERE nombre LIKE :buscar 
         OR dni LIKE :buscar 
         OR email LIKE :buscar"
    );

    $consultaTotal->execute([
        ":buscar" => $textoBuscar
    ]);

    $totalUsuarios = $consultaTotal->fetchColumn();

    /*
        Sacamos solo los usuarios que coinciden con la búsqueda
        y además aplicamos LIMIT y OFFSET para paginar.
    */
    $consulta = $conexion->prepare(
        "SELECT * FROM usuarios 
         WHERE nombre LIKE :buscar 
         OR dni LIKE :buscar 
         OR email LIKE :buscar
         LIMIT $usuariosPorPagina OFFSET $inicio"
    );

    $consulta->execute([
        ":buscar" => $textoBuscar
    ]);

    $usuarios = $consulta->fetchAll();
}

/*
    Calculamos el total de páginas.
*/
$totalPaginas = ceil($totalUsuarios / $usuariosPorPagina);

/*
    Si no hay usuarios, dejamos totalPaginas en 1 para que no dé problemas.
*/
if ($totalPaginas < 1) {
    $totalPaginas = 1;
}

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

    <!-- 
        FORMULARIO DE BÚSQUEDA

        method="get" significa que la búsqueda se manda por la URL.
        Por ejemplo:
        usuarios.php?buscar=fran

        El input se llama "buscar", por eso en PHP lo recogemos con $_GET["buscar"].
    -->
    <form method="get" action="usuarios.php">
        <label>Buscar por nombre, DNI o email:</label>
        <input type="text" name="buscar" value="<?php echo $buscar; ?>">

        <button type="submit">Buscar</button>

        <a href="usuarios.php">Limpiar</a>
    </form>

    <br>

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

    <!-- PAGINADOR -->
    <div class="paginador">

        <?php if ($pagina > 1) { ?>
            <a href="usuarios.php?pagina=<?php echo $pagina - 1; ?>&buscar=<?php echo $buscar; ?>">
                ← Anterior
            </a>
        <?php } ?>

        <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>

            <?php if ($i == $pagina) { ?>
                <strong><?php echo $i; ?></strong>
            <?php } else { ?>
                <a href="usuarios.php?pagina=<?php echo $i; ?>&buscar=<?php echo $buscar; ?>">
                    <?php echo $i; ?>
                </a>
            <?php } ?>

        <?php } ?>

        <?php if ($pagina < $totalPaginas) { ?>
            <a href="usuarios.php?pagina=<?php echo $pagina + 1; ?>&buscar=<?php echo $buscar; ?>">
                Siguiente →
            </a>
        <?php } ?>

    </div>

</body>
</html>