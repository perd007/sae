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
		<td class="titulo">Lista de Productos</td>
	</tr>
</table><hr width="100%" color="#333333" /><br />

<form name="frmentrada" id="frmentrada" action="listado_productos.php?" method="post">
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
		<th width="120" scope="col">Codigo</th>
		<th width="151" scope="col">Tipo</th>
		<th width="142" scope="col">Marca</th>
        <th width="146" scope="col">Modelo</th>
        <th width="148" scope="col">Nombre</th>
        <th width="165" scope="col">Descripcion</th>

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
			$filtro
			
			";
			 
	$query = mysql_query($sql,$conexion) or die ($sql.mysql_error());
	$rows_lista = mysql_num_rows($query);
			while ($field = mysql_fetch_array($query)) {
		if ($ventana == "requerimiento_detalles_insertar") {
			?><tr class="trListaBody" onclick="requerimiento_insertar_producto_apartado('<?=$field["id_productos"]?>', 'item');" id="<?=$field['id_productos']?>"><?
		}
		?>
		<td align="center"><?=$field['codigo']?></td>
		<td align="center"><?=($field['tipo'])?></td>
        <td align="center" ><?=($field['marca'])?></td>
        <td align="center"><?=($field['modelo'])?></td>
        <td align="center" ><?=($field['nombre'])?></td>
        <td align="center" ><?=($field['descripcion'])?></td>
        </tr>
        <?
	}
	?>
</table>
</div>
<table width="900">
	<tr>
    	<td>
        	Mostrar: 
            <select name="maxlimit" style="width:50px;" onchange="this.form.submit();">
                <?=loadSelectGeneral("MAXLIMIT", $maxlimit, 0)?>
            </select>
        </td>
        <td align="right">
        	<?=paginacion(intval($rows_total), intval($rows_lista), intval($maxlimit), intval($limit));?>
        </td>
    </tr>
</table>
</center>
</form>
<script type="text/javascript" language="javascript">
	totalRegistros(parseInt(<?=$rows_lista?>));
</script>
</body>
</html>