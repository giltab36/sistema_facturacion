<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("location: ./");
}
include "../conexion.php";

if (!empty($_POST)) {

    if (empty($_POST['cod_proveedor'])) {
        header('location: lista_proveedor.php');
        mysqli_close($conection);
    }

    $idproveedor = $_POST['cod_proveedor'];
    $query_delete = mysqli_query($conection, "UPDATE proveedor SET estatus = 0 WHERE cod_proveedor = $idproveedor");
    mysqli_close($conection);

    if ($query_delete) {
        header('location: lista_proveedor.php');
    } else {
        echo "Error al eliminar los datos";
    }
}

if (empty($_REQUEST['id'])) {
    header('location: lista_proveedor.php');
    mysqli_close($conection);
} else {

    $idproveedor = $_REQUEST['id'];
    $query = mysqli_query($conection, "SELECT * FROM proveedor WHERE cod_proveedor = $idproveedor");
    mysqli_close($conection);
    $result = mysqli_num_rows($query);

    if ($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $proveedor = $data['proveedor'];
            $direccion = $data['direccion'];
        }
    } else {
        header('location: lista_proveedor.php');
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
            <i class="fa-solid fa-truck-field fa-7x" style="color: silver;"></i>
            <br>
            <br>
            <h2><b>¿Seguro que desea eliminar estos datos?</b></h2>
            <p><b>Nombre del Proveedor:</b> <span><?php echo $proveedor; ?></span></p>
            <p><b>Direccion:</b> <span><?php echo $direccion; ?></span></p>

            <form action="" method="POST">
                <input type="hidden" name="cod_proveedor" value="<?php echo $idproveedor; ?>">
                <a href="lista_proveedor.php" class="btn_cancel">Cancelar</a>
                <button type="submit" class="btn_ok"><i class="fa-solid fa-check"></i> Eliminar</button>
            </form>
        </div>
    </section>



    <?php include "include/footer.php"; ?>
</body>

</html>