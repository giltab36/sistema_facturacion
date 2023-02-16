<?php
session_start();
include "../conexion.php";

//	Datos de la Empresa
$nit = '';
$nombreEmpresa = '';
$razonSocial = '';
$telEmpresa = '';
$emailEmpresa = '';
$dirEmpresa = '';
$iva;

$query_empresa = mysqli_query($conection, "SELECT * FROM configuracion");
$row_empesa = mysqli_num_rows($query_empresa);

if ($row_empesa > 0) {
	while ($arrayInfoEmpresa  = mysqli_fetch_assoc($query_empresa)) {
		$nit = $arrayInfoEmpresa['ruc'];
		$nombreEmpresa = $arrayInfoEmpresa['nombre'];
		$razonSocial = $arrayInfoEmpresa['razon_social'];;
		$telEmpresa = $arrayInfoEmpresa['telefono'];
		$emailEmpresa = $arrayInfoEmpresa['email'];
		$dirEmpresa = $arrayInfoEmpresa['direccion'];
		$iva = $arrayInfoEmpresa['iva'];
	}
}

//	Datos del Dashboard
$query_dash = mysqli_query($conection, "CALL data_dashboard();");
$result_dash = mysqli_num_rows($query_dash);

if ($result_dash > 0) {
	$data_dash = mysqli_fetch_assoc($query_dash);
	mysqli_close($conection);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<?php include "include/script.php"; ?>
	<title>Dashboard</title>
</head>

<body>
	<?php include "include/header.php"; ?>
	<section id="container">
		<div class="divContainer">
			<h1 class="title titlePanelControl">Panel de Control</h1>
			<!-- DATOS DEL DASHBOARD -->
			<div class="dashboard">
				<?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
					<a href="lista_usuario.php">
						<i class="fas fa-users"></i>
						<p>
							<strong>Usuarios</strong><br>
							<span><?php echo $data_dash['usuarios']; ?></span>
						</p>
					</a>
				<?php } ?>
				<a href="lista_cliente.php">
					<i class="fas fa-users"></i>
					<p>
						<strong>Clientes</strong><br>
						<span><?php echo $data_dash['clientes']; ?></span>
					</p>
				</a>
				<?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
					<a href="lista_proveedor.php">
						<i class="far fa-building"></i>
						<p>
							<strong>Proveedores</strong><br>
							<span><?php echo $data_dash['proveedores']; ?></span>
						</p>
					</a>
				<?php } ?>
				<a href="lista_producto.php">
					<i class="fas fa-cubes"></i>
					<p>
						<strong>Productos</strong><br>
						<span><?php echo $data_dash['productos']; ?></span>
					</p>
				</a>
				<a href="lista_venta.php">
					<i class="far fa-file-alt"></i>
					<p>
						<strong>Ventas del Día</strong><br>
						<span><?php echo $data_dash['ventas']; ?></span>
					</p>
				</a>
			</div>
		</div>
		<!-- DATOS DEL SISTEMA -->
		<div class="divInfoSistema">
			<h1 class="title titlePanelControl">Configuración</h1>
			<div class="containerPerfil">
				<!-- DATOS DE LA EMPRESA -->
				<?php if ($_SESSION['rol'] == 1) { ?>
					<div class="conatinerDataEmpresa">
						<div class="logoUser">
							<img src="img/logoEmpresa.png">
						</div>
						<h4>Datos de la Empresa</h4>
						<form method="POST" name="formEmpresa" id="formEmpresa">
							<input type="hidden" name="action" value="updateDataEmpresa">
							<div>
								<label>Ruc:</label>
								<input type="text" name="txtRuc" id="txtRuc" placeholder="Ruc de la empresa" value="<?php echo $nit ?>" required>
							</div>
							<div>
								<label>Nombre:</label>
								<input type="text" name="txtNombre" id="txtNombre" placeholder="Nombre de la empresa" value="<?php echo $nombreEmpresa ?>" required>
							</div>
							<div>
								<label>Razon social:</label>
								<input type="text" name="txtRSocial" id="txtRSocial" placeholder="Razon social" value="<?php echo $razonSocial ?>">
							</div>
							<div>
								<label>Teléfono:</label>
								<input type="text" name="txtTelEmpresa" id="txtTelEmpresa" placeholder="Número de teléfono" value="<?php echo $telEmpresa ?>" required>
							</div>
							<div>
								<label>Correo electrónico:</label>
								<input type="email" name="txtEmailEmpresa" id="txtEmailEmpresa" placeholdera="Correo electrónico" value="<?php echo $emailEmpresa ?>" required>
							</div>
							<div> <label>Dirección:</label>
								<input type="text" name="txtDirEmpresa" id="txtDirEmpresa" placeholder="Dirección de la empresa" value="<?php echo $dirEmpresa ?>" required>
							</div>
							<div>
								<label>IVA (%):</label>
								<input type="text" name="txtIva" id="txtIva" placeholder=" Impueto al valor agregado (IVA)" value="<?php echo $iva ?>" required>
							</div>
							<div class="alertFormEmrpresa" style="display: none;"></div>
							<div>
								<button type="submit" class="btn_save btnChangePass"><i class="far fa-save fa-lg"></i> Guardar datos</button>
							</div>
						</form>
					</div>
				<?php } ?>
				<!-- DATOS DEL USUARIO -->
				<div class="containerDataUser">
					<div class="logoUser">
						<img src="img/logoUser.png">
					</div>
					<div class="divDataUser">
						<h4>Información Personal</h4>
						<div>
							<label for="">Nombre:</label><span><?php echo $_SESSION['nombre'] ?></span>
						</div>
						<div>
							<label for="">Correo:</label><span><?php echo $_SESSION['email'] ?></span>
						</div>

						<h4>Datos del Usuario</h4>
						<div>
							<label for="">Rol:</label><span><?php echo $_SESSION['Nrol'] ?></span>
						</div>
						<div>
							<label for="">Usuario:</label><span><?php echo $_SESSION['user'] ?></span>
						</div>
						<h4>Cambiar Contraseña</h4>
						<form action="" method="POST" name="formChangePass" id="formChangePass">
							<div>
								<input type="password" name="txtPassUser" id="txtPassUser" placeholder="Contraseña actual" required>
							</div>
							<div>
								<input class="newPass" type="password" name="txtNewPassUser" id="txtNewPassUser" placeholder="Nueva contraseña" required>
							</div>
							<div>
								<input class="newPass" type="password" name="txtPassConfirm" id="txtPassConfirm" placeholder="Confirmar contraseña" required>
							</div>
							<div class="alertChangePass" style="display: none;"></div>
							<div>
								<button type="submit" class="btn_save btnChangePass"><i class="fas fa-key"></i> Cambiar contraseña</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php include "include/footer.php"; ?>
</body>

</html>