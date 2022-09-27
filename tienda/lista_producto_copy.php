<?php
session_start();


include "../conexion.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Lista de Productos</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">


        <h1 class="title"><i class="fa-solid fa-boxes-stacked"></i> Listado de Productos</h1>
        <a href="registro_cliente.php" class="btn_new"><i class="fa-solid fa-user-plus"></i> Agregar Producto</a>

        <form action="buscar_cliente.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        

        <!--Paginación-->
        

    </section>

    <?php include "include/footer.php"; ?>
</body>

</html>