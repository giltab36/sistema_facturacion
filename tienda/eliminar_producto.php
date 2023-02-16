<?php

session_start();

if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("location: ./");
}
include "../conexion.php";

//	Datos de la Empresa
$nombreEmpresa = '';

$query_empresa = mysqli_query($conection, "SELECT nombre FROM configuracion");
$row_empesa = mysqli_num_rows($query_empresa);

if ($row_empesa > 0) {
	while ($arrayInfoEmpresa  = mysqli_fetch_assoc($query_empresa)) {
		$nombreEmpresa = $arrayInfoEmpresa['nombre'];
	}
}
// ========================================================================= //

if (!empty($_POST)) {

    if (empty($_POST['cod_producto'])) {
        header('location: lista_producto.php');
        mysqli_close($conection);
    }

    $idproducto = $_POST['cod_producto'];
    $query_delete = mysqli_query($conection, "UPDATE producto pto SET pto.estatus = 0 WHERE cod_producto = $idproducto");
    mysqli_close($conection);

    if ($query_delete) {
        header('location: lista_producto.php');
    } else {
        echo "Error al eliminar los datos";
    }
}

if (empty($_REQUEST['id'])) {
    header('location: lista_producto.php');
    mysqli_close($conection);
} else {

    $idproducto = $_REQUEST['id'];
    $query = mysqli_query($conection, "SELECT * FROM producto WHERE cod_producto = $idproducto");
    $result = mysqli_num_rows($query);
    mysqli_close($conection);

    if ($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $producto = $data['descripcion'];
        }
    } else {
        header('location: lista_producto.php');
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Confirmar Eliminacion</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">
        <div class="data_delete">
            <i class="fa-solid fa-boxes-stacked fa-7x" style="color: silver;"></i>
            <br>
            <br>
            <h2><b>¿Seguro que desea eliminar estos datos?</b></h2>
            <p><b>Nombre del Producto:</b> <span><?php echo $producto; ?></span></p>

            <form action="" method="POST">
                <input type="hidden" name="cod_producto" value="<?php echo $idproducto; ?>">
                <a href="lista_producto.php" class="btn_cancel">Cancelar</a>
                <button type="submit" class="btn_ok"><i class="fa-solid fa-check"></i> Eliminar</button>
            </form>
        </div>
    </section>



    <?php include "include/footer.php"; ?>
</body>

</html>