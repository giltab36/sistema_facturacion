<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("location: ./");
}

include "../conexion.php";

if (!empty($_POST)) {

    $alert = '';
    if (empty($_POST['proveedor']) || empty($_POST['descripcion']) || empty($_POST['precio']) || $_POST['precio'] <= 0 || empty($_POST['existencia']) || $_POST['existencia'] <= 0) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {

        $proveedor = $_POST['proveedor'];
        $producto = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['existencia'];
        $usuario_id = $_SESSION['idUser'];

        $foto = $_FILES['foto'];
        $nombre_foto = $foto['name'];
        $type = $foto['type'];
        $url_temp = $foto['tmp_name'];

        $imgProducto = 'img_producto.png';

        if ($nombre_foto != '') {
            $destino = 'img/uploads/';
            $img_nombre = 'img_' . md5(date('d-m-Y H:m:s'));
            $imgProducto = $img_nombre . '.jpg';
            $src = $destino . $imgProducto;
        }

        /* $query_insert = mysqli_query($conection, "INSERT INTO producto (proveedor, cod_barra, descripcion, precio, existencia, usuario_id, foto) VALUE ('$proveedor', '$barra', '$producto', '$precio', '$cantidad', '$usuario_id', '$imgProducto')"); */

        if (empty($_POST['cod_barra'])) {
            $query_insert = mysqli_query($conection, "INSERT INTO producto (proveedor, descripcion, precio, existencia, usuario_id, foto) VALUE ('$proveedor', '$producto', '$precio', '$cantidad', '$usuario_id', '$imgProducto')");
        } else {
            $query_insert = mysqli_query($conection, "INSERT INTO producto (proveedor, cod_barra, descripcion, precio, existencia, usuario_id, foto) VALUE ('$proveedor', '$barra', '$producto', '$precio', '$cantidad', '$usuario_id', '$imgProducto')");
        }

        if ($query_insert) {
            if ($nombre_foto != '') {
                move_uploaded_file($url_temp, $src);
            }
            $alert = '<p class="msg_save">Producto creado correctamente.</p>';
        } else {
            $alert = '<p class="msg_error">Error al guardar el Producto.</p>';
        }
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
    <title>Registrar Producto</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">

        <div class="form_register">
            <h1 class="user_new"><i class="fa-solid fa-boxes-packing"></i> Registrar Productos</h1>
            <hr class="hr">
            <div class="alerta"><?php echo isset($alert) ? $alert : ''; ?></div>

            <form action="" method="post" enctype="multipart/form-data">

                <label for="proveedor">Proveedor:</label>

                <?php
                $query_proveedor = mysqli_query($conection, "SELECT cod_proveedor, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
                $result_proveedor = mysqli_num_rows($query_proveedor);
                mysqli_close($conection)
                ?>

                <select name="proveedor" id="proveedor">
                    <?php
                    if ($result_proveedor > 0) {
                        while ($proveedor = mysqli_fetch_array($query_proveedor)) {
                    ?>
                            <option value="<?php echo $proveedor['cod_proveedor']; ?>"><?php echo $proveedor['proveedor']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <!-- <label for="cod_barra">Codigo de Barra:</label>
                <input type="number" name="cod_barra" id="cod_barra" placeholder="Ingrese el codigo de barra"> -->

                <label for="descripcion">Producto:</label>
                <input type="text" name="descripcion" id="descripcion" placeholder="Nombre del producto">

                <label for="precio">Precio:</label>
                <input type="number" name="precio" id="precio" placeholder="Precio del producto">

                <label for="existencia">Cantidad:</label>
                <input type="number" name="existencia" id="existencia" placeholder="Cantidad del producto">

                <div class="photo">
                    <label for="foto">Foto</label>
                    <div class="prevPhoto">
                        <span class="delPhoto notBlock">X</span>
                        <label for="foto"></label>
                    </div>
                    <div class="upimg">
                        <input type="file" name="foto" id="foto">
                    </div>
                    <div id="form_alert"></div>
                </div>

                <button type="submit" class="btn_save"><i class="fa-regular fa-floppy-disk"></i> Registrar</button>

            </form>

        </div>

    </section>



    <?php include "include/footer.php"; ?>
</body>

</html>