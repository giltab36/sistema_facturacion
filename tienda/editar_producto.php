<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("location: ./");
}

include "../conexion.php";

if (!empty($_POST)) {

    $alert = '';
    if (empty($_POST['proveedor']) || empty($_POST['descripcion']) || empty($_POST['precio']) || empty($_POST['id']) || empty($_POST['foto_actual']) || empty($_POST['foto_remove'])) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {
        $codproducto = $_POST['id'];
        $proveedor = $_POST['proveedor'];
        $producto = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $imgProducto = $_POST['foto_actual'];
        $imgRemove = $_POST['foto_remove'];

        $foto = $_FILES['foto'];
        $nombre_foto = $foto['name'];
        $type = $foto['type'];
        $url_temp = $foto['tmp_name'];

        $upd = '';

        if ($nombre_foto != '') {
            $destino = 'img/uploads/';
            $img_nombre = 'img_' . md5(date('d-m-Y H:m:s'));
            $imgProducto = $img_nombre . '.jpg';
            $src = $destino . $imgProducto;
        } else {
            if ($_POST['foto_actual'] != $_POST['foto_remove']) {
                $imgProducto = 'img_producto.png';
            }
        }

        $query_update = mysqli_query($conection, "UPDATE producto SET descripcion = '$producto', proveedor = $proveedor, precio = $precio, foto = '$imgProducto' WHERE cod_producto = $codproducto");

        if ($query_update) {
            if (($nombre_foto != '' && ($_POST['foto_actual'] != 'img_producto.png')) || ($_POST['foto_actual'] != $_POST['foto_remove'])) {
                @unlink('img/uploads/' . $_POST['foto_actual']);
            }

            if ($nombre_foto != '') {
                move_uploaded_file($url_temp, $src);
            }
            $alert = '<p class="msg_save">Producto actualizado correctamente.</p>';
        } else {
            $alert = '<p class="msg_error">Error al actualizar el Producto.</p>';
        }
    }
}

// Validar Producto
if (empty($_REQUEST['id'])) {
    header("location: lista_producto.php");
} else {
    $id_producto = $_REQUEST['id'];
    if (!is_numeric($id_producto)) {
        header("location: lista_producto.php");
    }

    $query_producto = mysqli_query($conection, "SELECT p.cod_producto, p.descripcion, p.precio, p.foto, pr.cod_proveedor, pr.proveedor FROM producto p INNER JOIN proveedor pr ON p.proveedor = pr.cod_proveedor WHERE p.cod_producto = $id_producto AND p.estatus = 1");
    $result_producto = mysqli_num_rows($query_producto);

    $foto = '';
    $classRemove = 'notBlock';

    if ($result_producto > 0) {
        $data_producto = mysqli_fetch_assoc($query_producto);

        if ($data_producto['foto'] != 'img_producto.png') {
            $classRemove = '';
            $foto = '<img id="img" src="img/uploads/' . $data_producto['foto'] . '" alt="Producto">';
        }
    } else {
        header("location: lista_proveedor.php");
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "include/script.php"; ?>
    <title>Editar Producto</title>
</head>

<body>
    <?php include "include/header.php"; ?>
    <section id="container">

        <div class="form_register">
            <h1 class="user_new"><i class="fa-solid fa-boxes-packing"></i> Editar Productos</h1>
            <hr class="hr">
            <div class="alerta"><?php echo isset($alert) ? $alert : ''; ?></div>

            <form action="" method="post" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?php echo $data_producto['cod_producto']; ?>">
                <input type="hidden" id="foto_actual" name="foto_actual" value="<?php echo $data_producto['foto']; ?>">
                <input type="hidden" id="foto_remove" name="foto_remove" value="<?php echo $data_producto['foto']; ?>">

                <label for="proveedor">Proveedor:</label>

                <?php
                $query_proveedor = mysqli_query($conection, "SELECT cod_proveedor, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
                $result_proveedor = mysqli_num_rows($query_proveedor);
                mysqli_close($conection);
                ?>

                <select name="proveedor" id="proveedor" class="notitemone">
                    <option value="<?php echo $data_producto['cod_proveedor']; ?>" selected><?php echo $data_producto['proveedor']; ?></option>
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

                <label for="descripcion">Producto:</label>
                <input type="text" name="descripcion" id="descripcion" value="<?php echo $data_producto['descripcion']; ?>">

                <label for="precio">Precio:</label>
                <input type="text" name="precio" id="precio" value="<?php echo $data_producto['precio']; ?>">

                <div class="photo">
                    <label for="foto">Foto</label>
                    <div class="prevPhoto">
                        <span class="delPhoto <?php echo $classRemove; ?>">X</span>
                        <label for="foto"></label>
                        <?php echo $foto; ?>
                    </div>
                    <div class="upimg">
                        <input type="file" name="foto" id="foto">
                    </div>
                    <div id="form_alert"></div>
                </div>

                <button type="submit" class="btn_save"><i class="fa-regular fa-floppy-disk"></i> Editar</button>

            </form>

        </div>

    </section>



    <?php include "include/footer.php"; ?>
</body>

</html>