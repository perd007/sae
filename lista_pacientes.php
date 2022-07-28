<?php require_once('Connections/conexion.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_ecos = 20;
$pageNum_ecos = 0;
if (isset($_GET['pageNum_ecos'])) {
  $pageNum_ecos = $_GET['pageNum_ecos'];
}
$startRow_ecos = $pageNum_ecos * $maxRows_ecos;


if ($fBuscar != "") { 
	$filtro.=" and rif LIKE '%".utf8_decode($fBuscar)."%' OR
					nombre LIKE '%".utf8_decode($fBuscar)."%' OR
					telefono LIKE '%".utf8_decode($fBuscar)."%' OR
					direccion LIKE '%".utf8_decode($fBuscar)."%'";
} 



mysql_select_db($database_conexion, $conexion);
$query_ecos = "SELECT * FROM ecos ";
$query_limit_ecos = sprintf("%s LIMIT %d, %d", $query_ecos, $startRow_ecos, $maxRows_ecos);
$ecos = mysql_query($query_limit_ecos, $conexion) or die(mysql_error());
$row_ecos = mysql_fetch_assoc($ecos);

if (isset($_GET['totalRows_ecos'])) {
  $totalRows_ecos = $_GET['totalRows_ecos'];
} else {
  $all_ecos = mysql_query($query_ecos);
  $totalRows_ecos = mysql_num_rows($all_ecos);
}
$totalPages_ecos = ceil($totalRows_ecos/$maxRows_ecos)-1;

$queryString_ecos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_ecos") == false && 
        stristr($param, "totalRows_ecos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_ecos = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_ecos = sprintf("&totalRows_ecos=%d%s", $totalRows_ecos, $queryString_ecos);


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

<form id="form1" name="form1" method="post" action="listaecos.php">

<div style="overflow:scroll; width:100%; height:600px;">
<div class="divBorder" style="width:400px;">
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
<table width="662" class="tblLista" >
<thead>
  <tr>
    <th width="142" scope="col">Nombre</th>
    <th width="50" scope="col">Cedula</th>
    <th width="20" scope="col">Edad</th>
    <th width="50" scope="col">Telefono</th>
    <th width="80" scope="col">Tipo de Eco</th>
    <th width="110" scope="col">Forma de Pago</th>
    <th width="10" scope="col">P</th>
    <th width="10" scope="col">M</th>
    <th width="10" scope="col">E</th>
    </tr>
    <thead>
  <tbody>


  
 
    <?php do { 
			
			mysql_select_db($database_conexion, $conexion);
			$query_clientes = "SELECT * FROM clientes where cedula='$row_ecos[cliente]'";
			$clientes = mysql_query($query_clientes, $conexion) or die(mysql_error());
			$row_clientes = mysql_fetch_assoc($clientes);
			$totalRows_clientes = mysql_num_rows($clientes);

	
	?>
      <tr class="trListaBody" id="<?=$row_ecos['rif']?>">
        <td><?php echo $row_clientes['nombres']; ?></td>
        <td><?php echo $row_clientes['cedula']; ?></td>
        <td><?php echo $edad; ?></td>
        <td><?php echo $row_clientes['telefono']; ?></td>
        <td><?php echo $row_ecos['tipo_eco']; ?></td>
        <td><?php echo $row_ecos['moneda']." / ".$row_ecos['monto']; ?></td>
        <td><a  href="procesar_eco.php?id=<?php echo $row_clientes['id_eco']; ?>">P</a></td>
        <td><a href="modificar_eco.php?id=<?php echo $row_clientes['id_eco']; ?>">M</a></td>
        <td><a href="eliminar_eco.php?id=<?php echo $row_clientes['id_eco']; ?>">E</a></td>
      </tr>
      <?php } while ($row_ecos = mysql_fetch_assoc($ecos)); ?>
  </tbody>
</table>
<table border="0" align="center">
  <tr>
    <td><?php if ($pageNum_ecos > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_ecos=%d%s", $currentPage, 0, $queryString_ecos); ?>"><img src="First.gif" /></a>
      <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_ecos > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_ecos=%d%s", $currentPage, max(0, $pageNum_ecos - 1), $queryString_ecos); ?>"><img src="Previous.gif" /></a>
      <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_ecos < $totalPages_ecos) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_ecos=%d%s", $currentPage, min($totalPages_ecos, $pageNum_ecos + 1), $queryString_ecos); ?>"><img src="Next.gif" /></a>
      <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_ecos < $totalPages_ecos) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_ecos=%d%s", $currentPage, $totalPages_ecos, $queryString_ecos); ?>"><img src="Last.gif" /></a>
      <?php } // Show if not last page ?></td>
  </tr>
</table>

</div>
</form>
</body>
</html>
<?php
mysql_free_result($ecos);

mysql_free_result($clientes);
?>
