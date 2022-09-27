<nav>
    <ul>
        <li><a href="index.php"><i class="fa-solid fa-house"></i> Inicio</a></li>
        <?php if ($_SESSION['rol'] == 1) { ?>
            <li class="principal">
                <a href=""><i class="fa-solid fa-users"></i> Usuarios</a>
                <ul>
                    <li><a href="registro_usuario.php"><i class="fa-solid fa-user-plus"></i> Nuevo Usuario</a></li>
                    <li><a href="lista_usuario.php"><i class="fa-solid fa-users"></i> Lista de Usuarios</a></li>
                </ul>
            </li>
        <?php } ?>
        <li class="principal">
            <a href=""><i class="fa-solid fa-users"></i> Clientes</a>
            <ul>
                <li><a href="registro_cliente.php"><i class="fa-solid fa-user-plus"></i> Nuevo Cliente</a></li>
                <li><a href="lista_cliente.php"><i class="fa-solid fa-users"></i> Lista de Clientes</a></li>
            </ul>
        </li>
        <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
            <li class="principal">
                <a href=""><i class="fa-solid fa-truck-field"></i> Proveedores</a>
                <ul>
                    <li><a href="registro_proveedor.php"><i class="fa-solid fa-plus"></i> Nuevo Proveedor</a></li>
                    <li><a href="lista_proveedor.php"><i class="fa-solid fa-truck-field"></i> Lista de Proveedores</a></li>
                </ul>
            </li>
        <?php } ?>
        <li class="principal">
            <a href=""><i class="fa-solid fa-boxes-packing"></i> Productos</a>
            <ul>
                <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
                    <li><a href="registro_producto.php"><i class="fa-solid fa-truck-ramp-box"></i> Nuevo Producto</a></li>
                <?php } ?>
                <li><a href="lista_producto_copy.php"><i class="fa-solid fa-boxes-stacked"></i> Lista de Productos</a></li>
            </ul>
        </li>
        <li class="principal">
            <a href=""><i class="fa-solid fa-dollar-sign"></i> Ventas</a>
            <ul>
                <li><a href="registro_venta.php"><i class="fa-solid fa-cart-plus"></i> Nuevo Venta</a></li>
                <li><a href="lista_venta.php"><i class="fa-solid fa-list-check"></i> Ventas</a></li>
            </ul>
        </li>
    </ul>
</nav>