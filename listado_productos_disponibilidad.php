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
	$filtro.=" and nombre LIKE '%".utf8_decode($fBuscar)."%' OR
					tipo LIKE '%".utf8_decode($fBuscar)."%' OR
					modelo LIKE '%".utf8_decode($fBuscar)."%' OR
					Codigo LIKE '%".utf8_decode($fBuscar)."%' OR
					marca LIKE '%".utf8_decode($fBuscar)."%' OR
					descripcion LIKE '%".utf8_decode($fBuscar)."%'";
} else $dBuscar = "disabled";

//	------------------------------------
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<link type="text/css" rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="js2/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/fscript.js" charset="utf-8"></script>
<link href="css.css" rel="stylesheet" type="text/css" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>

<form id="form1" name="form1" method="post" action="">
<input type="hidden" name="TipoClasificacion" id="FlagCompras" value="C" />
<div style="overflow:scroll; width:100%; height:250px;">
<div class="divBorder"  style="width:400px;">
  <table width="400" class="tblFiltro">
    <tr b="b">
      <td width="54" height="29" align="right" class="negrita">Buscar:</td>
      <td width="261"><input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:250px;"/></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="submit" class="negrita" value="Buscar" /></td>
    </tr>
</table>
</div>
<p>&nbsp;</p>
<div style="overflow:scroll; width:900px; height:300px;">
  <table width="82%" align="left" class="tblLista">
    <thead>
      <tr>   
        <th width="125" scope="col">Marca</th>
        <th width="126" scope="col">Modelo</th>
        <th width="142" scope="col">Nombre</th>
        <th width="150" scope="col">Disponible</th>
      </tr>
    </thead>
    <tbody>
    <?php
	//	consulto todos
	$sql = "SELECT *
			FROM productos
			
			";
	$query = mysql_query($sql,$conexion) or die ($sql.mysql_error());
	$rows_total = mysql_num_rows($query);
	
	//	consulto lista
	$sql = "SELECT *
FROM compra_productos INNER JOIN productos ON compra_productos.producto = productos.id_productos
where compra_productos.transaccion!='ELIMINADO POR MODIFICACION'
			$filtro
			";
			 
	$query = mysql_query($sql,$conexion) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
			while ($field = mysql_fetch_array($query)) {
				
				
				
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
		
			?>
    <tr class="trListaBody" onclick="selListado2('<?=$field['id_productos']?>', '<?=($field['nombre'])?>', 'codigo', 'nombre', '<?=$field['id_productos']?>', 'id_productos', '<?=($disponible)?>','disponible', '','','','');" id="<?=$field['id_productos']?>">

      <td align="center" ><?=($field['marca'])?></td>
      <td align="center"><?=($field['modelo'])?></td>
      <td align="center" ><?=($field['nombre'])?></td>
      <td align="center" ><?=($disponible)?></td>
    </tr>
    <?
	}
	?>
    </tbody>
  </table>
</div>
<p>&nbsp;</p>
</div>
</form>
</body>
</html>
<?php
mysql_free_result($proveedores);

mysql_free_result($clientes);
?>
