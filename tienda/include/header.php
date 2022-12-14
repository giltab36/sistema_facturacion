<?php

if (empty($_SESSION['active'])) {
    header('location: ../');
}

?>

<header>
    <div class="header">

        <h1 class="encabezado">Snow Informatic</h1>
        <div class="optionsBar">
            <p class="fecha">Paraguay, <?php echo fechaC(); ?></p>
            <span>|</span>
            <span class="user"><?php echo $_SESSION['nombre'] . " - " . $_SESSION['rol']; ?></span>
            <img class="photouser" src="img/user.png" alt="Usuario">
            <a href="salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
        </div>
    </div>
    <?php include "nav.php"; ?>
</header>

<div class="modal">
    <div class="bodyModal">
        <form action="" method="post" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct();">
            <h1><i class="fas fa-cubes" style="font-size: 45pt;"></i> <br> Agregar Producto</h1>
            <h2 class="nameProducto"></h2><br>
            <input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad del producto" required><br>
            <input type="text" name="precio" id="txtPrecio" placeholder="Precio del producto" required>
            <input type="" name="producto_id" id="producto_id" required readonly>
            <input type="" name="action" value="addPorduct" readonly>
            <div class="alert alertAddProduct">
                <p></p>
            </div>
            <button type="submit" class="btn_ok"><i class="fas fa-plus"></i> Agregar</button>
            <a href="#" class="btn_cancel closeModal" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>

        </form>
    </div>
</div>