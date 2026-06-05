<?php

include "conexion.php";

/*
    Para borrar un usuario necesitamos saber su id.

    La URL será así:
    borrar.php?id=3

    Por eso usamos $_GET["id"].
*/

if (!isset($_GET["id"])) {
    echo "No se ha indicado ningún usuario.";
    echo "<br>";
    echo "<a href='usuarios.php'>Volver al listado</a>";
    exit();
}

$id = $_GET["id"];

/*
    DELETE sirve para borrar registros de una tabla.

    WHERE id = :id es MUY importante.
    Si no ponemos WHERE, podríamos borrar todos los usuarios.
*/

$consulta = $conexion->prepare("DELETE FROM usuarios WHERE id = :id");

$consulta->execute([
    ":id" => $id
]);

/*
    Después de borrar, volvemos al listado.
*/

header("Location: usuarios.php");
exit();

?>