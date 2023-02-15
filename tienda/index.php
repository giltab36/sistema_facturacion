<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<?php include "include/script.php"; ?>
	<title>Sisteme Ventas</title>
</head>

<body>
	<?php include "include/header.php"; ?>
	<section id="container">
		<div class="divContainer">
			<h1 class="title titlePanelControl">Panel de Control</h1>
			<div class="dashboard">
				<a href="lista_usuario.php">
					<i class="fas fa-users"></i>
					<p>
						<strong>Usuarios</strong><br>
						<span>40</span>
					</p>
				</a>
				<a href="lista_cliente.php">
					<i class="fas fa-users"></i>
					<p>
						<strong>Clientes</strong><br>
						<span>1085</span>
					</p>
				</a>
				<a href="lista_proveedor.php">
					<i class="far fa-building"></i>
					<p>
						<strong>Proveedores</strong><br>
						<span>200</span>
					</p>
				</a>
				<a href="lista_producto.php">
					<i class="fas fa-cubes"></i>
					<p>
						<strong>Productos</strong><br>
						<span>2000</span>
					</p>
				</a>
				<a href="lista_venta.php">
					<i class="far fa-file-alt"></i>
					<p>
						<strong>Ventas</strong><br>
						<span>300</span>
					</p>
				</a>
			</div>
		</div>
		<div class="divInfoSistema">
			<h1 class="title titlePanelControl">Configuración</h1>
			<div class="containerPerfil">
				<div class="conatinerDataEmpresa">
					<div class="logoUser">
						<img src="img/logoEmpresa.png">
					</div>
					<h4>Datos de la Empresa</h4>
					<form method="POST" name="formEmpresa" id="formEmpresa">
						<input type="hidden" name="action" value="updateDataEmpresa">
						<div>
							<label>Ruc:</label>
							<input type="text" name="txtRuc" id="txtRuc" placeholder="Ruc de la empresa" valuea="" required>
						</div>
						<div>
							<label>Nombre:</label>
							<input type="text" name="txtNombre" id="txtNombre" placeholder="Nombre de la empresa" value="" required>
						</div>
						<div>
							<label>Razon social:</label>
							<input type="text" name="txtRSocial" id="txtRSocial" placeholder="Razon social" value="">
						</div>
						<div>
							<label>Teléfono:</label>
							<input type="text" name="txtTelEmpresa" id="txtTelEmpresa" placeholder="Número de teléfono" value="" required>
						</div>
						<div>
							<label>Correo electrónico:</label>
							<input type="email" name="txtEmailEmpresa" id="txtEmailEmpresa" placeholdera="Correo electrónico" value="" required>
						</div>
						<div> <label>Dirección:</label>
							<input type="text" name="txtDirEmpresa" id="txtDirEmpresa" placeholder="Dirección de la empresa" value="" required>
						</div>
						<div>
							<label>IVA (%):</label>
							<input type="text" name="txtIva" id="txtIva" placeholder=" Impueto al valor agregado (IVA)" value="" required>
						</div>
						<div class="alertFormEmrpresa" style="display: none;"></div>
						<div>
							<button type="submit" class="btn_save btnChangePass"><i class="far fa-save fa-lg"></i> Guardar datos</button>
						</div>
					</form>
				</div>
				<div class="containerDataUser">
					<div class="logoUser">
						<img src="img/logoUser.png">
					</div>
					<div class="divDataUser">
						<h4>Información Personal</h4>
						<div>
							<label for="">Nombre:</label> <span>José Villar</span>
						</div>
						<div>
							<label for="">Correo:</label> <span>josema95035@gmail.com</span>
						</div>

						<h4>Datos del Usuario</h4>
						<div>
							<label for="">Rol:</label> <span>Asministrador</span>
						</div>
						<div>
							<label for="">Usuario:</label> <span>José Villar</span>
						</div>
						<h4>Cambiar Contraseña</h4>
						<form action="" method="POST" name="formChangePass" id="formChangesPass">
							<div>
								<input type="password" name="txtPassUser" id="txtPassUser" placeholder="Contraseña actual" required>
							</div>
							<div>
								<input type="password" name="txtNewPassUser" id="txtNewPassUser" placeholder="Nueva contraseña" required>
							</div>
							<div>
								<input type="password" name="txtPassConfirm" id="txtPassConfirm" placeholder="Confirmar contraseña" required>
							</div>

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