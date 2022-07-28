<?php require_once('Connections/conexion.php'); ?>
<?php


//Eliminamos los productos sacados
		 //productos comprados
		 mysql_select_db($database_conexion, $conexion);
            $sql_cp = "SELECT * FROM compra_productos where factura='$_GET[factura]' and transaccion!='ELIMINADO POR MODIFICACION' ";
			$query_cp = mysql_query($sql_cp,$conexion) or die ($sql_cp.mysql_error());
			$rows_lista_cp = mysql_num_rows($query_cp);
			
			//

			//comparamos productos que no interfiera la eliminacion con lo vendido
			while ($field_cp = mysql_fetch_array($query_cp)) {
					
							//consultamos disponibilidad
							$query_almacen = "SELECT sum(cantida) FROM almacen where id_producto='$field_cp[producto]' and transaccion!='VENTA' and transaccion!='ELIMINADO POR MODIFICACION'";
							$almacen = mysql_query($query_almacen, $conexion) or die(mysql_error());
							$row_almacen = mysql_fetch_assoc($almacen);
							
							$query_almacen2 = "SELECT sum(cantida) FROM almacen where  id_producto='$field_cp[producto]' and transaccion='VENTA' ";
							$almacen2 = mysql_query($query_almacen2, $conexion) or die(mysql_error());
							$row_almacen2 = mysql_fetch_assoc($almacen2);
							
							$query_pedido = "SELECT sum(cantidad) FROM pedido_productos where  id_producto='$field_cp[producto]' ";
							$pedido = mysql_query($query_pedido, $conexion) or die(mysql_error());
							$row_pedido = mysql_fetch_assoc($pedido);
							
							$query_almacen3 = "SELECT sum(cantida) FROM almacen where  id_producto='$field_cp[producto]' and transaccion='EXTRAIDO' ";
							$almacen3 = mysql_query($query_almacen3, $conexion) or die(mysql_error());
							$row_almacen3 = mysql_fetch_assoc($almacen3);
							
							$query_apartado = "SELECT sum(cantidad) FROM productos_apartados where  id_producto='$field_cp[producto]' ";
							$apartado = mysql_query($query_apartado, $conexion) or die(mysql_error());
							$row_apartado = mysql_fetch_assoc($apartado);
							
							$disponible=$row_almacen["sum(cantida)"]-$row_almacen2["sum(cantida)"]-$row_pedido["sum(cantidad)"]-$row_almacen3["sum(cantida)"]-$row_apartado["sum(cantidad)"];
						
							//
							
							//consultamos la cantidad del producto a eliminar y la comparamos 
							$query_almacen3 = "SELECT * FROM almacen where id_producto='$field_cp[producto]' and factura_compra='$_GET[factura]'  and transaccion!='ELIMINADO POR MODIFICACION' ";
							$almacen3 = mysql_query($query_almacen3, $conexion) or die(mysql_error());
							$row_almacen3 = mysql_fetch_assoc($almacen3);
							
							
							if($row_almacen3["cantida"]>$disponible){
								echo "<script type=\"text/javascript\">alert ('No se puede procesar la transacion debido a que la cantidad a eliminar de un producto supera a la Disponible');  location.href='consultar_compras.php' </script>";
  							exit;
								
							}
						
						
			}//fin del primer while

/////////////////////////////////////////////////////////////////////////////////////////	


mysql_select_db($database_conexion, $conexion);
$sql="delete from compras where id_compras='$id'";
$verificar=mysql_query($sql,$conexion) or die(mysql_error());

if($verificar){
	echo"<script type=\"text/javascript\">alert ('COMPRA ELIMINADA'); location.href='consultar_compras.php' </script>";
}
else{
	echo"<script type=\"text/javascript\">alert ('Error'); location.href='consultar_compras.php' </script>";
	
}//fin de l primer else


?>