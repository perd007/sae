<?

//Eliminamos los productos sacados
	$cont=0;
	$comparacion=0;
	$m=0;
	$n=0;
	if($_POST["can_productos"]>$_POST["can_detalles"]){ 
		$m=$_POST["can_productos"]; 
		$n=$_POST["can_detalles"];
			for($i=1;$i<=$m;$i++){
				
				echo "-----------------------------vuela $i----------------------";
				for($j=1;$j<=$m;$j++){
				
			echo "<br>productos select: ".$_POST["id".$j]."  Prducto Primero".$_POST['veri_prod'.$i]."";
				
				
						
					if($_POST['veri_prod'.$i]!=$_POST["id".$j] and $_POST["id".$j]>0){
						echo "<br>entro1 <br>";
						 $comparacion++;
						echo $_POST["id".$j]."- <br>";
					 }else 
					 if($_POST['veri_prod'.$i]==$_POST["id".$j] and $_POST["id".$j]>0){ 
					 $comparacion--; 
					 echo "<br>entro2 <br>";
					 }
					  echo "<br>comp: ".$comparacion."<br>";
					 
				}
				
				if($comparacion==0){
					$cont++;
					$sacados[$cont]=$_POST['veri_prod'.$i];
					}
				
			}

	}
	/*
	if($_POST["can_productos"]<$_POST["can_detalles"]){ 
		$n=$_POST["can_productos"];
		$m=$_POST["can_detalles"];
		for($i=1;$i<=$m;$i++){
				for($j=1;$j<=$n;$j++){
					if($_POST['veri_prod'.$j]!=$_POST["id".$i] and $_POST["id".$j]!=0){
					  $comparacion=0; 
					 }else $comparacion=1;	
				}
				if($comparacion==0){
					$cont++;
					$sacados[$cont]=$_POST['veri_prod'.$j];
					}
			}
	}*/
	echo "contador= ".$cont;
	exit;
	
	//
	
	
	
	//Eliminamos los productos sacados
	for($i=1;$i<=$_POST["can_productos"];$i++){
  	
			$x=0;

			for($j=1;$j<=$_POST["can_detalles"];$j++){
				if($_POST['veri_prod'.$i]==$_POST["id".$j]){ $x++; }
				if($x < 1){ 
				echo "Useted saco".$_POST["id".$j];
  exit;
  }
	}
	}
	
	//
	
	
	//validamos que las cantidades de los productos a actualizar no superen las disponibles
	   //consultamos todos los producto registrados en el sub-modulo de productos
	   /*
		mysql_select_db($database_conexion, $conexion);
		$query_C2P = "SELECT * FROM compra_productos where factura='$_POST[factura]'";
		$CP2 = mysql_query($query_CP2, $conexion) or die(mysql_error());
		$row_CP2 = mysql_fetch_assoc($CP2);
		$totalRows_CP2 = mysql_num_rows($CP2);
			//comparamos productos almacenados con enviados
			while($row_CP2 = mysql_fetch_assoc($CP2)){
				 while($_POST["can_detalles"]>=$i){
						
						if($row_CP2["producto"]==$_POST["id".$i]){	
							//consultamos disponibilidad
							$query_almacen = "SELECT sum(cantida) FROM almacen id_producto='$row_CP2[producto]'and transaccion='COMPRA' ";
							$almacen = mysql_query($query_almacen, $conexion) or die(mysql_error());
							$row_almacen = mysql_fetch_assoc($almacen);
							
							$query_almacen2 = "SELECT sum(cantida) FROM almacen id_producto='$row_CP2[producto]'and transaccion='VENTA' ";
							$almacen2 = mysql_query($query_almacen2, $conexion) or die(mysql_error());
							$row_almacen2 = mysql_fetch_assoc($almacen2);
							
							$disponible=$row_almacen["sum(cantida)"]-$row_almacen2["sum(cantida)"];
							//
							
							//consultamos la cantidad del producto a eliminar y la comparamos 
							$query_almacen3 = "SELECT * FROM almacen id_producto='$row_CP2[producto]'and factura_compra='$_POST[factura]' ";
							$almacen3 = mysql_query($query_almacen3, $conexion) or die(mysql_error());
							$row_almacen3 = mysql_fetch_assoc($almacen3);
							
							if($_POST["Cantidad".$i]>$disponible){
								echo "<script type=\"text/javascript\">alert ('No se puede procesar la transacion debido a que la cantidad a eliminar de un producto supera a la Disponible');  location.href='' </script>";
  							exit;
								
							}else{
							//
							//actualizamos campo para indicar que se modifico
							$actualizar = "UPDATE compra_productos SET transaccion='ELIMINADO POR MODIFICACION' WHERE producto='$row_CP2[producto]'  and factura='$_POST[factura]'";
							$$actualizar2 = mysql_query($actualizar, $conexion) or die(mysql_error());
							//
							//eliminamos registros del almacen simpre y cuando no hayan sido vendido
							$eliminar = "UPDATE almacen SET transaccion='ELIMINADO POR MODIFICACION' WHERE id_producto='$row_CP2[producto]'  and factura_compra='$_POST[factura]'";
							$eliminar2 = mysql_query($eliminar, $conexion) or die(mysql_error());
							//
							}//fin del else
						}//fin del if
					}//fin del segundo while	
			}//fin del primer while
			*/
// fin de la validacion de actualizacion

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
</body>
</html>