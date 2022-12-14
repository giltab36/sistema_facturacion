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
    if ($_POST['action'] == 'addProduct') {

        if (!empty($_POST['cantidad']) || !empty($_POST['precio']) || !empty($_POST['producto_id'])) {
            $cantidad = $_POST['cantidad'];
            $precio = $_POST['precio'];
            $producto_id = $_POST['cod_producto'];
            $usuario_id = $_SESSION['idUser'];

            $query_insert = mysqli_query($conection, "INSERT INTO entradas (cod_producto, cantidad, precio, usuario_id) VALUE ($producto_id, $cantidad, $precio, $usuario_id)");

            if ($query_insert) {
                //procedimiento almacenado
                $query_upd = mysqli_query($conection, "CALL actualizar_precio_producto($cantidad, $precio, $producto_id)");
                $result_pro = mysqli_num_rows($query_upd);
                if ($result_pro > 0) {
                    $data = mysqli_fetch_assoc($query_upd);
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
}

exit;
