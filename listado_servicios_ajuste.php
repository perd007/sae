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
					modelo LIKE '%".utf8_decode($fBuscar)."%' OR
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
        <th width="142" scope="col">Nombre</th>
        <th width="300" scope="col">Descripcion</th>
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
			FROM productos where tipo='servicio'
			$filtro
			";
			 
	$query = mysql_query($sql,$conexion) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
			while ($field = mysql_fetch_array($query)) {
				
		mysql_select_db($database_conexion, $conexion);
		$query_compra_Productos = "SELECT * FROM compra_productos where producto='$field[id_productos]' and transaccion!='ELIMINADO POR MODIFICACION' order by id_compra_productos desc";
		$compra_Productos = mysql_query($query_compra_Productos, $conexion) or die(mysql_error());
		$row_compra_Productos = mysql_fetch_assoc($compra_Productos);
		$totalRows_compra_Productos = mysql_num_rows($compra_Productos);
		
		mysql_select_db($database_conexion, $conexion);
		$query_compras = "SELECT * FROM compras where num_fac='$row_compra_Productos[factura]'";
		$compras = mysql_query($query_compras, $conexion) or die(mysql_error());
		$row_compras = mysql_fetch_assoc($compras);
		$totalRows_compras = mysql_num_rows($compras);
				
		
			?>
    <tr class="trListaBody" onclick="selListado2('<?=$field['marca']?>', '<?=($field['nombre'])?>', 'marca', 'nombre', '<?=$field['id_productos']?>', 'id_productos', '<?=($field['tipo'])?>','tipo', '<?=$row_compra_Productos['precio']?>','precioCompra','<?=($row_compras['fecha'])?>','fechaCompra');" id="<?=$field['id_productos']?>">

      <td align="center" ><?=($field['nombre'])?></td>
      <td align="center" ><?=($field['descripcion'])?></td>
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
