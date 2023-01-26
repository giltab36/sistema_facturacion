<?php
include "../conexion.php";
session_start();

if (!empty($_POST)) {

    //Extraer datos del producto
    if ($_POST['action'] == 'infoProducto') {

        $producto_id = $_POST['producto'];
        $query = mysqli_query($conection, "SELECT cod_producto, descripcion FROM producto WHERE cod_producto = $producto_id AND estatus = 1");
        mysqli_close($conection);
        $result = mysqli_num_rows($query);

        if ($result > 0) {
            $data = mysqli_fetch_assoc($query);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }

    //Agregar producto a entrada
    if ($_POST['action'] == 'addPorduct') {

        if (!empty($_POST['cantidad']) || !empty($_POST['precio']) || !empty($_POST['producto_id'])) {
            $cantidad = $_POST['cantidad'];
            $precio = $_POST['precio'];
            $producto_id = $_POST['producto_id'];
            $usuario_id = $_SESSION['idUser'];

            $query_insert = mysqli_query($conection, "INSERT INTO entradas (cod_producto, cantidad, precio, usuario_id) VALUE ($producto_id, $cantidad, $precio, $usuario_id)");

            if ($query_insert) {
                //procedimiento almacenado
                $query_upd = mysqli_query($conection, "CALL actualizar_precio_producto($cantidad, $precio, $producto_id)");
                $result_pro = mysqli_num_rows($query_upd);
                if ($result_pro > 0) {
                    $data = mysqli_fetch_assoc($query_upd);
                    $data['producto_id'] = $producto_id;
                    echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {
                echo 'error';
            }
            mysqli_close($conection);
        } else {
            echo 'error';
        }
        exit;
    }

    //Eliminar producto
    if ($_POST['action'] == 'delProduct') {

        if (empty($_POST['producto_id']) || !is_numeric($_POST['producto_id'])) {
            echo 'error';
        } else {
            $idproducto = $_POST['producto_id'];
            $query_delete = mysqli_query($conection, "UPDATE producto SET estatus = 0 WHERE cod_producto = $idproducto");
            mysqli_close($conection);

            if ($query_delete) {
                echo 'ok';
            } else {
                echo 'error';
            }
        }
        echo 'error';
        exit;
    }

    //Buscar Cliente - Venta
    if ($_POST['action'] == 'searchCliente') {
        if (!empty($_POST['cliente'])) {

            $ruc = $_POST['cliente'];
            $query = mysqli_query($conection, "SELECT * FROM cliente WHERE cedula LIKE '$ruc' AND estatus = 1");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            $data = '';
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query);
            } else {
                $data = 0;
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    //  Registrar Cliente - Ventas
    if ($_POST['action'] == 'addCliente') {

        $nit = $_POST['ruc_cliente'];
        $nombre = $_POST['nom_cliente'];
        $telefono = $_POST['tel_cliente'];
        $direccion = $_POST['dir_cliente'];
        $usuario_id = $_SESSION['idUser'];

        $query_insert = mysqli_query($conection, "INSERT INTO cliente (cedula, nombre, telefono, direccion, usuario_id) VALUE ('$nit', '$nombre', '$telefono', '$direccion', '$usuario_id')");

        if ($query_insert) {
            $codCliente = mysqli_insert_id($conection);
            $msg = $codCliente;
        } else {
            $msg = 'error';
        }
        mysqli_close($conection);
        echo $msg;
        exit;
    }
}
exit;
