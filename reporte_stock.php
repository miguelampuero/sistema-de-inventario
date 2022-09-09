<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=archivo.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
$html='<html><body><h1>Reporte de inventario</h1><table border=1><tbody><tr><td>Código</td><td>Nombre</td><td>Categoría</td><td>Precio</td><td>Stock</td></tr>';
$html.='</tbody>';

include('config/db.php');
include('config/conexion.php');
$id_categoria=intval($_GET['id_categoria']);
$sql="select * from products, categorias where products.id_categoria=categorias.id_categoria";
if ($id_categoria>0){
	$sql.=" and products.id_categoria='$id_categoria'";
}
$sql_productos=mysqli_query($con,$sql);
while($row=mysqli_fetch_array($sql_productos)){
	$codigo_producto=$row['codigo_producto'];
	$nombre_producto=$row['nombre_producto'];
	$precio_producto=$row['precio_producto'];
	$nombre_categoria=$row['nombre_categoria'];
	$stock=$row['stock'];
	$html.='<tr>';
	$html.='<td>'.$codigo_producto.'</td>';
	$html.='<td>'.$nombre_producto.'</td>';
	$html.='<td>'.$nombre_categoria.'</td>';
	$html.='<td>'.number_format($precio_producto,2).'</td>';
	$html.='<td>'.number_format($stock,2).'</td>';
	$html.='</tr>';
}
$html.='</body></table></html>';
echo utf8_decode($html); 
?>