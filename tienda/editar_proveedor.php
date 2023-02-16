<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("location: ./");
}

include "../conexion.php";

if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {

        $idproveedor = $_POST['id'];
        $proveedor = $_POST['proveedor'];
        $contacto = $_POST['contacto'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];


        $sql_update = mysqli_query($conection, "UPDATE proveedor SET proveedor = '$proveedor', contacto = '$contacto', telefono = '$telefono', direccion = '$direccion' WHERE cod_proveedor = $idproveedor");

        if ($sql_update) {
            $alert = '<p class="msg_save">Proveedor editado correctamente.</p>';
        } else {
            $alert = '<p class="msg_error">Error al editar el cliente.</p>';
        }
    }
}



//Mostrar Datos
if (empty($_REQUEST['id'])) {
    header('Location: lista_proveedor.php');
    mysqli_close($conection);
}

$idproveedor = $_REQUEST['id'];
$sql = mysqli_query($conection, "SELECT * FROM proveedor WHERE cod_proveedor = $idproveedor AND estatus = 1");
$result_sql = mysqli_num_rows($sql);

if ($result_sql == 0) {
    header('Location: lista_proveedor.php');
} else {

    while ($data = mysqli_fetch_array($sql)) {
        $idproveedor = $data['cod_proveedor'];
        $proveedor = $data['proveedor'];
        $contacto = $data['contacto'];
        $telefono = $data['telefono'];
        $direccion = $data['direccion'];
    }
}

//	Datos de la Empresa
$nombreEmpresa = '';

$query_empresa = mysqli_query($conection, "SELECT nombre FROM configuracion");
$row_empesa = mysqli_num_rows($query_empresa);

if ($row_empesa > 0) {
	while ($arrayInfoEmpresa  = mysqli_fetch_assoc($query_empresa)) {
		$nombreEmpresa = $arrayInfoEmpresa['nombre'];
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Editar Proveedor</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">

        <div class="form_register">
            <h1 class="user_new"><i class="fa-regular fa-pen-to-square"></i> Editar Proveedor</h1>
            <hr class="hr">
            <div class="alerta"><?php echo isset($alert) ? $alert : ''; ?></div>

            <form action="" method="post">

                <input type="hidden" name="id" value="<?php echo $idproveedor; ?>">
                <label for="proveedor">Proveedor</label>
                <input type="text" name="proveedor" id="proveedor" placeholder="Nombre del Proveedor" value="<?php echo $proveedor; ?>">
                <label for="contacto">Contacto</label>
                <input type="text" name="contacto" id="contacto" placeholder="Nombre completo del contacto" value="<?php echo $contacto; ?>">
                <label for="telefono">Teléfono</label>
                <input type="number" name="telefono" id="telefono" placeholder="Numero de telefono" value="<?php echo $telefono; ?>">
                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" placeholder="Direccion completa" value="<?php echo $direccion; ?>">

                <button type="submit" class="btn_save"><i class="fa-regular fa-pen-to-square"></i> Modificar</button>

            </form>

        </div>

    </section>



    <?php 
    include "include/footer.php"; 
    mysqli_close($conection);
    ?>
</body>

</html>