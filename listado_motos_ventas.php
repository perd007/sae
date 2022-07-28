<?php
extract($_POST);
extract($_GET);
//	------------------------------------
 require_once("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);
//	------------------------------------
if ($filtrar == "default") {
	$maxlimit = 15;
	$fOrdenar = "id_productos";
}
if ($fBuscar != "") { 
	$filtro.="  and nombre LIKE '%".utf8_decode($fBuscar)."%' OR
					tipo LIKE '%".utf8_decode($fBuscar)."%' OR
					modelo LIKE '%".utf8_decode($fBuscar)."%' OR
					Codigo LIKE '%".utf8_decode($fBuscar)."%' OR
					marca LIKE '%".utf8_decode($fBuscar)."%' OR
					descripcion LIKE '%".utf8_decode($fBuscar)."%";
} else $dBuscar = "disabled";

//	------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="js/fscript.js" charset="utf-8"></script>
<link href="css.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="titulo">Lista de Motos</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_motos_ventas.php?" method="post">
<input type="hidden" name="registro" id="registro" />
<input type="hidden" name="cod" id="cod" value="<?=$cod?>" />
<input type="hidden" name="nom" id="nom" value="<?=$nom?>" />
<input type="hidden" name="campo3" id="campo3" value="<?=$campo3?>" />
<input type="hidden" name="campo4" id="campo4" value="<?=$campo4?>" />
<input type="hidden" name="ventana" id="ventana" value="<?=$ventana?>" />
<input type="hidden" name="seldetalle" id="seldetalle" value="<?=$seldetalle?>" />

<div class="divBorder" style="width:400px;">
<table width="400" class="tblFiltro">
    <tr b>
		<td width="54" height="29" align="right" class="negrita">Buscar:</td>
		<td width="261"><input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:250px;"/></td>
	  </tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" class="negrita" value="Buscar" /></td>
	  </tr>
</table>
</div>

<center>


<p>&nbsp;</p>
<div style="overflow:scroll; width:900px; height:300px;">
<table width="100%" class="tblLista">
	<thead>
	<tr>
		 <th width="105" scope="col">Nombre</th>
        <th width="105" scope="col">Año</th>
        <th width="80" scope="col">Disponible</th>
        <th width="86" scope="col">Precio Bs.F</th>
        <th width="93" scope="col">Marca</th>
        <th width="78" scope="col">Modelo</th>

	</tr>
    </thead>
	  <?php
		//	consulto todos
	$sql = "SELECT *
			FROM productos
			where tipo='Vehiculo'
			
			";
	$query = mysql_query($sql,$conexion) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT *
			FROM productos
			where tipo='Vehiculo'
			
			";
			 
	$query = mysql_query($sql,$conexion) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
while ($field = mysql_fetch_array($query)) {
	
	

//obtenemso el precio del producto
mysql_select_db($database_conexion, $conexion);
$query_precios = "SELECT * FROM precios where id_producto='$field[id_productos]' order by id_precios desc";
$precios = mysql_query($query_precios, $conexion) or die(mysql_error());
$row_precios = mysql_fetch_assoc($precios);
$totalRows_precios = mysql_num_rows($precios);	
		
	//consultamos disponibilidad
							$query_almacen = "SELECT sum(cantida) FROM almacen where id_producto='$field[id_productos]' and (transaccion='COMPRA' or transaccion='COMPRA-MODIFICADA') ";
							$almacen = mysql_query($query_almacen, $conexion) or die(mysql_error());
							$row_almacen = mysql_fetch_assoc($almacen);
							
							$query_almacen2 = "SELECT sum(cantida) FROM almacen where  id_producto='$field[id_productos]' and transaccion='VENTA' ";
							$almacen2 = mysql_query($query_almacen2, $conexion) or die(mysql_error());
							$row_almacen2 = mysql_fetch_assoc($almacen2);
							
							$query_pedido = "SELECT sum(cantidad) FROM pedido_productos where  id_producto='$field[id_productos]' ";
							$pedido = mysql_query($query_pedido, $conexion) or die(mysql_error());
							$row_pedido = mysql_fetch_assoc($pedido);
							
							$query_almacen3 = "SELECT sum(cantida) FROM almacen where  id_producto='$field[id_productos]' and transaccion='EXTRAIDO' ";
							$almacen3 = mysql_query($query_almacen3, $conexion) or die(mysql_error());
							$row_almacen3 = mysql_fetch_assoc($almacen3);
							/*
							$query_apartado = "SELECT sum(cantidad) FROM productos_apartados where  id_producto='$field[id_productos]' ";
							$apartado = mysql_query($query_apartado, $conexion) or die(mysql_error());
							$row_apartado = mysql_fetch_assoc($apartado);
							
							*/
							$disponible=$row_almacen["sum(cantida)"]-$row_almacen2["sum(cantida)"]-$row_pedido["sum(cantidad)"]-$row_almacen3["sum(cantida)"];
						
							//
//verificr si h SIDO COMPRADO ALGUNAS VEZ
//obtenemso el precio del producto
mysql_select_db($database_conexion, $conexion);
$query_compra = "SELECT * FROM compra_productos where producto='$field[id_productos]' and transaccion!='ELIMINADO POR MODIFICACION'";
$compra = mysql_query($query_compra, $conexion) or die(mysql_error());
$row_compra = mysql_fetch_assoc($compra);
$totalRows_compra = mysql_num_rows($compra);
if($totalRows_compra>=1){
	
			?><tr class="trListaBody" <? if($disponible>0 and $row_precios['precio']!=""){ ?>onclick="requerimiento_Moto('<?=$field["id_productos"]?>', 'item','<?=(number_format($row_precios['precio'],"2",",","."))?>', '<?=($disponible)?>','<?=($field['nombre'])?>','<?=($field['marca'])?>','<?=($field['modelo'])?>');" <? } ?> id="<?=$field['id_productos']?>">
		 <td align="center" ><?=($field['nombre'])?></td>
      <td align="center" ><?=($field['ano'])?></td>
      <td align="center" ><?=($disponible)?></td>
      <td align="center" ><?=(number_format($row_precios['precio'],"2",",","."))?></td>
      <td align="center" ><?=($field['marca'])?></td>
      <td align="center"><?=($field['modelo'])?></td>
        </tr>
        <?
}//fin del if de virifacion de compras
	}
	?>
</table>
</div>
</center>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows_lista?>));
</script>
</body>
</html>