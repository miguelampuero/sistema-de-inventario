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
	include("funciones.php");
	
	$active_productos="active";
	$active_clientes="";
	$active_usuarios="";	
	$title="Producto | Simple Stock";
	
	if (isset($_POST['reference']) and isset($_POST['quantity'])){
		$quantity=intval($_POST['quantity']);
		$reference=mysqli_real_escape_string($con,(strip_tags($_POST["reference"],ENT_QUOTES)));
		$id_producto=intval($_GET['id']);
		$user_id=$_SESSION['user_id'];
		$firstname=$_SESSION['firstname'];
		$nota="$firstname agregó $quantity producto(s) al inventario";
		$fecha=date("Y-m-d H:i:s");
		guardar_historial($id_producto,$user_id,$fecha,$nota,$reference,$quantity);
		$update=agregar_stock($id_producto,$quantity);
		if ($update==1){
			$message=1;
		} else {
			$error=1;
		}
	}
	
	if (isset($_POST['reference_remove']) and isset($_POST['quantity_remove'])){
		$quantity=intval($_POST['quantity_remove']);
		$reference=mysqli_real_escape_string($con,(strip_tags($_POST["reference_remove"],ENT_QUOTES)));
		$id_producto=intval($_GET['id']);
		
		$query_product=mysqli_query($con,"select stock from products where id_producto='$id_producto'");
		$rw=mysqli_fetch_array($query_product);
		$stock_actual=$rw['stock'];
		if ($quantity<=$stock_actual){
		$user_id=$_SESSION['user_id'];
		$firstname=$_SESSION['firstname'];
		$nota="$firstname eliminó $quantity producto(s) del inventario";
		$fecha=date("Y-m-d H:i:s");
		guardar_historial($id_producto,$user_id,$fecha,$nota,$reference,$quantity);
		$update=eliminar_stock($id_producto,$quantity);
		if ($update==1){
			$message=1;
		} else {
			$error=1;
		}
		} else {
			$error=1;
		}
	}
	
	if (isset($_GET['id'])){
		$id_producto=intval($_GET['id']);
		$query=mysqli_query($con,"select * from products where id_producto='$id_producto'");
		$row=mysqli_fetch_array($query);
		
		$query_img=mysqli_query($con,"select * from imagenes where id_producto='$id_producto'");
		$fetch_img=mysqli_fetch_array($query_img);
		$url=$fetch_img['url'];
		
	} else {
		die("Producto no existe");
	}
	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include("head.php");?>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="plugins/jasny-bootstrap/css/jasny-bootstrap.min.css">
  </head>
  <body>
	<?php
	include("navbar.php");
	include("modal/agregar_stock.php");
	include("modal/eliminar_stock.php");
	include("modal/editar_productos.php");
	
	?>
	
	<div class="container">

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-sm-4 col-sm-offset-2 text-center">
				 <div class="fileinput fileinput-new" data-provides="fileinput">
				  <span class="btn btn-primary btn-file"><span class="fileinput-new">Selecciona imagen</span><span class="fileinput-exists">Cambiar</span><input type="file" name="imagefile" id="imagefile" onchange="upload_image(<?php echo $id_producto;?>)"></span>
				  <span class="fileinput-filename"></span>
				  <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
				</div>
				 <div id="load_img">
				 <?php 
					if (!empty($url)){
						?>
					<a href="#" title="<?php echo $row['nombre_producto']?>" class="thumb">	
						<center><img src="<?php echo $url;?>" class="img-responsive img-rounded" data-toggle="modal" data-target=".modal-profile-lg"> </center>
					</a>	
						<?php
					} else {
						?>
						<img class="item-img img-responsive" src="img/stock.png" alt="">
						<?php
					}
				 ?>
					
				 </div>
				
				
				  <br>
                    <a href="#" class="btn btn-danger" onclick="eliminar('<?php echo $row['id_producto'];?>')" title="Eliminar"> <i class="glyphicon glyphicon-trash"></i> Eliminar </a> 
					<a href="#myModal2" data-toggle="modal" data-codigo='<?php echo $row['codigo_producto'];?>' data-nombre='<?php echo $row['nombre_producto'];?>' data-categoria='<?php echo $row['id_categoria']?>' data-precio='<?php echo $row['precio_producto']?>' data-stock='<?php echo $row['stock'];?>' data-id='<?php echo $row['id_producto'];?>' class="btn btn-info" title="Editar"> <i class="glyphicon glyphicon-pencil"></i> Editar </a>	
					
              </div>
			  
              <div class="col-sm-4 text-left">
                <div class="row margin-btm-20">
                    <div class="col-sm-12">
                      <span class="item-title"> <?php echo $row['nombre_producto'];?></span>
                    </div>
                    <div class="col-sm-12 margin-btm-10">
                      <span class="item-number"><?php echo $row['codigo_producto'];?></span>
                    </div>
                    <div class="col-sm-12 margin-btm-10">
                    </div>
                    <div class="col-sm-12">
                      <span class="current-stock">Stock disponible</span>
                    </div>
                    <div class="col-sm-12 margin-btm-10">
                      <span class="item-quantity"><?php echo number_format($row['stock'],2);?></span>
                    </div>
					<div class="col-sm-12">
                      <span class="current-stock"> Precio venta  </span>
                    </div>
					<div class="col-sm-12">
                      <span class="item-price">$ <?php echo number_format($row['precio_producto'],2);?></span>
                    </div>
					
                    <div class="col-sm-12 margin-btm-10">
					</div>
                    <div class="col-sm-6 col-xs-6 col-md-4 ">
                      <a href="" data-toggle="modal" data-target="#add-stock"><img width="100px"  src="img/stock-in.png"></a>
                    </div>
                    <div class="col-sm-6 col-xs-6 col-md-4">
                      <a href="" data-toggle="modal" data-target="#remove-stock"><img width="100px"  src="img/stock-out.png"></a>
                    </div>
                    <div class="col-sm-12 margin-btm-10">
                    </div>
                    
                   
                                    </div>
              </div>
            </div>
            <br>
            <div class="row">

            <div class="col-sm-8 col-sm-offset-2 text-left">
                  <div class="row">
                    <?php
						if (isset($message)){
							?>
						<div class="alert alert-success alert-dismissible" role="alert">
						  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						  <strong>Aviso!</strong> Datos procesados exitosamente.
						</div>	
							<?php
						}
						if (isset($error)){
							?>
						<div class="alert alert-danger alert-dismissible" role="alert">
						  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						  <strong>Error!</strong> No se pudo procesar los datos.
						</div>	
							<?php
						}
					?>	
					 <table class='table table-bordered'>
						<tr>
							<th class='text-center' colspan=5 >HISTORIAL DE INVENTARIO</th>
						</tr>
						<tr>
							<td>Fecha</td>
							<td>Hora</td>
							<td>Descripción</td>
							<td>Referencia</td>
							<td class='text-center'>Total</td>
						</tr>
						<?php
							$query=mysqli_query($con,"select * from historial where id_producto='$id_producto'");
							while ($row=mysqli_fetch_array($query)){
								?>
						<tr>
							<td><?php echo date('d/m/Y', strtotime($row['fecha']));?></td>
							<td><?php echo date('H:i:s', strtotime($row['fecha']));?></td>
							<td><?php echo $row['nota'];?></td>
							<td><?php echo $row['referencia'];?></td>
							<td class='text-center'><?php echo number_format($row['cantidad'],2);?></td>
						</tr>		
								<?php
							}
						?>
					 </table>
                  </div>
                                    
                                    
				</div>
            </div>
          </div>
        </div>
    </div>
</div>



</div>

<!-- .modal-profile -->
	<div class="modal fade modal-profile" tabindex="-1" role="dialog" aria-labelledby="modalProfile" aria-hidden="true">
			<div class="modal-dialog ">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">×</button>
						<h3 class="title-image"></h3>
					</div>
					<div class="modal-body modal-image" >
					</div>
					<div class="modal-footer">
						<button class="btn btn-default" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
        
			</div>
		</div>
	<!-- //.modal-profile -->
	
	<?php
	include("footer.php");
	?>
	<script type="text/javascript" src="js/productos.js"></script>
	<script src="plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
  </body>
</html>
<script>
$( "#editar_producto" ).submit(function( event ) {
  $('#actualizar_datos').attr("disabled", true);
  
 var parametros = $(this).serialize();
	 $.ajax({
			type: "POST",
			url: "ajax/editar_producto.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados_ajax2").html("Mensaje: Cargando...");
			  },
			success: function(datos){
			$("#resultados_ajax2").html(datos);
			$('#actualizar_datos').attr("disabled", false);
			window.setTimeout(function() {
				$(".alert").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove();});
				location.replace('stock.php');
			}, 4000);
		  }
	});
  event.preventDefault();
})

	$('#myModal2').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget) // Button that triggered the modal
		var codigo = button.data('codigo') // Extract info from data-* attributes
		var nombre = button.data('nombre')
		var categoria = button.data('categoria')
		var precio = button.data('precio')
		var stock = button.data('stock')
		var id = button.data('id')
		var modal = $(this)
		modal.find('.modal-body #mod_codigo').val(codigo)
		modal.find('.modal-body #mod_nombre').val(nombre)
		modal.find('.modal-body #mod_categoria').val(categoria)
		modal.find('.modal-body #mod_precio').val(precio)
		modal.find('.modal-body #mod_stock').val(stock)
		modal.find('.modal-body #mod_id').val(id)
	})
	
	function eliminar (id){
		var q= $("#q").val();
		if (confirm("Realmente deseas eliminar el producto")){	
			location.replace('stock.php?delete='+id);
		}
	}
	
	function upload_image(product_id){
		$("#load_img").text('Cargando...');
		var inputFileImage = document.getElementById("imagefile");
		var file = inputFileImage.files[0];
		var data = new FormData();
		data.append('imagefile',file);
		data.append('product_id',product_id);
		$.ajax({
			url: "ajax/imagen_product_ajax.php",        // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: data, 			  // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(data)   // A function to be called if request succeeds
			{
				$("#load_img").html(data);
				/* show lightbox when clicking a thumbnail */
				$('a.thumb').click(function(event){
					event.preventDefault();
					var content = $('.modal-body');
					content.empty();
					var title = $(this).attr("title");
					$('.modal-title').html(title);      	
					content.html($(this).html());
					$(".modal-profile").modal({show:true});
				});
			}
		});
	}
	
	$(document).ready(function() {
/* show lightbox when clicking a thumbnail */
    $('a.thumb').click(function(event){
    	event.preventDefault();
    	var content = $('.modal-image');
    	content.empty();
      	var title = $(this).attr("title");
      	$('.title-image').html(title);      	
      	content.html($(this).html());
      	$(".modal-profile").modal({show:true});
    });

  });
</script>