<nav>
    <ul>
        <li><a href="index.php">Inicio</a></li>
        <?php
        if ($_SESSION['rol'] == 1) {
        ?>
            <li class="principal">
                <a href="">Usuarios</a>
                <ul>
                    <li><a href="registro_usuario.php">Nuevo Usuario</a></li>
                    <li><a href="lista_usuario.php">Lista de Usuarios</a></li>
                </ul>
            </li>
        <?php } ?>
        <li class="principal">
            <a href="">Clientes</a>
            <ul>
                <li><a href="registro_cliente.php">Nuevo Cliente</a></li>
                <li><a href="lista_cliente.php">Lista de Clientes</a></li>
            </ul>
        </li>
        <li class="principal">
            <a href="">Proveedores</a>
            <ul>
                <li><a href="registro_proveedor.php">Nuevo Proveedor</a></li>
                <li><a href="lista_proveedor.php">Lista de Proveedores</a></li>
            </ul>
        </li>
        <li class="principal">
            <a href="">Productos</a>
            <ul>
                <li><a href="registro_producto.php">Nuevo Producto</a></li>
                <li><a href="lista_producto.php">Lista de Productos</a></li>
            </ul>
        </li>
        <li class="principal">
            <a href="">Ventas</a>
            <ul>
                <li><a href="registro_venta.php">Nuevo Venta</a></li>
                <li><a href="lista_ventas.php">Ventas</a></li>
            </ul>
        </li>
    </ul>
</nav>