<?php
	/*-------------------------
	Autor: Miguel Ampuero
	Mail: kmiguel16@gmail.com
	---------------------------*/
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }
	
	/* Connect To Database*/
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	$active_reportes="active";
	$title="Reportes | Simple Stock";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include("head.php");?>
  </head>
  <body>
	<?php
	include("navbar.php");
	?>
	
    <div class="container">
	<div class="panel panel-success">
		<div class="panel-heading">
		    <div class="btn-group pull-right">
				<button type='button' class="btn btn-default" onclick="exportar_excel();"><span class="glyphicon glyphicon-download-alt" ></span> Exportar excel</button>
			</div>
			<h4><i class='glyphicon glyphicon-search'></i> Buscar productos</h4>
		</div>
		<div class="panel-body">
		
			
			
			<?php
				include("modal/registro_categorias.php");
				include("modal/editar_categorias.php");
			?>
			<form class="form-horizontal" role="form" id="datos_cotizacion">
				
						<div class="form-group row">
							<label for="q" class="col-md-2 control-label">Categoría</label>
							<div class="col-md-5">
								<select class='form-control' name='id_categoria' id='id_categoria' onchange="load(1);">
									<option value="">Selecciona una categoría</option>
									<?php 
									$query_categoria=mysqli_query($con,"select * from categorias order by nombre_categoria");
									while($rw=mysqli_fetch_array($query_categoria))	{
										?>
									<option value="<?php echo $rw['id_categoria'];?>"><?php echo $rw['nombre_categoria'];?></option>			
										<?php
									}
									?>
								</select>
							</div>
							<div class="col-md-3">
								<button type="button" class="btn btn-default" onclick='load(1);'>
									<span class="glyphicon glyphicon-search" ></span> Buscar</button>
								<span id="loader"></span>
							</div>
							
						</div>
				
				
				
			</form>
				<div id="resultados"></div><!-- Carga los datos ajax -->
				<div class='outer_div'></div><!-- Carga los datos ajax -->
			
		
	
			
			
			
  </div>
</div>
		 
	</div>
	<hr>
	<?php
	include("footer.php");
	?>
	<script type="text/javascript" src="js/reportes.js"></script>
  </body>
</html>
