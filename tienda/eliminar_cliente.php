<?php
session_start();
if ($_SESSION['rol'] != 1 /*and $_SESSION['rol'] != 2*/) {
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

    if (empty($_POST['id_cliente'])) {
        header('location: lista_cliente.php');
        mysqli_close($conection);
    }

    $idcliente = $_POST['id_cliente'];
    $query_delete = mysqli_query($conection, "UPDATE cliente SET estatus = 0 WHERE id_cliente = $idcliente");
    mysqli_close($conection);

    if ($query_delete) {
        header('location: lista_cliente.php');
    } else {
        echo "Error al eliminar los datos";
    }
}

if (empty($_REQUEST['id'])) {
    header('location: lista_usuario.php');
    mysqli_close($conection);
} else {
    $idcliente = $_REQUEST['id'];

    $query = mysqli_query($conection, "SELECT * FROM cliente WHERE id_cliente = $idcliente");
    mysqli_close($conection);
    $result = mysqli_num_rows($query);

    if ($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $cedula = $data['cedula'];
            $nombre = $data['nombre'];
        }
    } else {
        header('location: lista_usuario.php');
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
            <i class="fa-solid fa-user-xmark fa-7x" style="color: silver;"></i>
            <br>
            <br>
            <h2><b>¿Seguro que desea eliminar estos datos?</b></h2>
            <p><b>Nombre del Cliente:</b> <span><?php echo $nombre; ?></span></p>
            <p><b>Cedula:</b> <span><?php echo $cedula; ?></span></p>

            <form action="" method="POST">
                <input type="hidden" name="id_cliente" value="<?php echo $idcliente; ?>">
                <a href="lista_cliente.php" class="btn_cancel">Cancelar</a>
                <button type="submit" class="btn_ok"><i class="fa-solid fa-check"></i> Eliminar</button>
            </form>
        </div>
    </section>



    <?php include "include/footer.php"; ?>
</body>

</html>