<?php require_once('Connections/conexion.php'); ?>
<?php 
//validar usuario

if($_COOKIE["val"]==true){
	if($_COOKIE["f"]!=1){
	echo "<script type=\"text/javascript\">alert ('Usted no posee permisos para Facturar'); location.href='fondo.php' </script>";
    exit;
	}
}
else{
echo "<script type=\"text/javascript\">alert ('Error usuario invalido'); location.href='fondo.php' </script>";
 exit;
}
?>
<?php 
//validar caja abierta
mysql_select_db($database_conexion, $conexion);
$query_caja = "SELECT * FROM caja where estado='ABIERTA'";
$caja = mysql_query($query_caja, $conexion) or die(mysql_error());
$row_caja = mysql_fetch_assoc($caja);
$totalRows_caja = mysql_num_rows($caja);

if($totalRows_caja==0){
echo "<script type=\"text/javascript\">alert ('Debe Aperturar Caja Primero'); location.href='fondo.php' </script>";
 exit;	
}

?>
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

$maxRows_pedidos = 20;
$pageNum_pedidos = 0;
if (isset($_GET['pageNum_pedidos'])) {
  $pageNum_pedidos = $_GET['pageNum_pedidos'];
}
$startRow_pedidos = $pageNum_pedidos * $maxRows_pedidos;


if ($fBuscar != "") { 
	$filtro.=" where id_cliente LIKE '%".utf8_decode($fBuscar)."%' OR
					fecha LIKE '%".utf8_decode($fBuscar)."%'";
}



mysql_select_db($database_conexion, $conexion);
$query_pedidos = "SELECT * FROM pedidos $filtro";
$query_limit_pedidos = sprintf("%s LIMIT %d, %d", $query_pedidos, $startRow_pedidos, $maxRows_pedidos);
$pedidos = mysql_query($query_limit_pedidos, $conexion) or die(mysql_error());
$row_pedidos = mysql_fetch_assoc($pedidos);

if (isset($_GET['totalRows_pedidos'])) {
  $totalRows_pedidos = $_GET['totalRows_pedidos'];
} else {
  $all_pedidos = mysql_query($query_pedidos);
  $totalRows_pedidos = mysql_num_rows($all_pedidos);
}
$totalPages_pedidos = ceil($totalRows_pedidos/$maxRows_pedidos)-1;



$queryString_pedidos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_pedidos") == false && 
        stristr($param, "totalRows_pedidos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_pedidos = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_pedidos = sprintf("&totalRows_pedidos=%d%s", $totalRows_pedidos, $queryString_pedidos);


if($totalRows_pedidos==0){
  echo "<script type=\"text/javascript\">alert ('No existen Pedidos en Cola');  location.href='fondo.php' </script>";
  exit;
  }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="css.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="css/estilo.css" charset="utf-8" />
<script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
<title>Documento sin título</title>
<style type="text/css">
.centrar {
	text-align: center;
	color: #EFEFEF;
	
}

a{text-decoration:none}
</style>
</head>


<body>
<form id="form1" name="form1" method="post" action="lista_pedidos.php">

<div class="divBorder" style="width:400px;">
  <table width="400" align="center" class="tblFiltro">
    <tr b="b">
      <td height="21" colspan="2" align="center" class="negrita">Buscar</td>
      </tr>
    <tr b="b">
      <td width="138" height="29" align="right" class="negrita">Cedula o Fecha:</td>
      <td width="250"><input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:250px;"/></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="submit" class="negrita" value="Buscar" /></td>
    </tr>
  </table>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="743" >
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td colspan="5" align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Pedidos Pendientes por Facturar </span></td>
      <td align="right" height="1" width="14"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr align="center" class="centrar">
      <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
      <td width="315"  valign="baseline" bgcolor="#0000FF"class="negrita">Cliente</td>
      <td width="119"  valign="baseline" bgcolor="#0000FF"class="negrita">Cedula</td>
      <td width="176" valign="baseline" bgcolor="#0000FF" class="negrita">Fecha y Hora</td>
      <td width="106" valign="baseline" bgcolor="#0000FF" class="negrita">Facturar</td>
      <td width="106" valign="baseline" bgcolor="#0000FF" class="negrita">Eliminar</td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    
    

      <?php do { 
	  
	  mysql_select_db($database_conexion, $conexion);
	  $query_clientes = "SELECT * FROM clientes where cedula='$row_pedidos[id_cliente]'";
	  $clientes = mysql_query($query_clientes, $conexion) or die(mysql_error());
	  $row_clientes = mysql_fetch_assoc($clientes);
	  $totalRows_clientes = mysql_num_rows($clientes);
	  
	  ?>
        <tr class="trListaBody" style=" font-size: 14px;">
          <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td> 
          <td align="center"  ><?php echo $row_clientes['nombres']; ?></td>
          <td align="center" ><?php echo $row_clientes['cedula']; ?></td>
          <td align="center"><?php echo $row_pedidos['fecha']; ?></td>
          <td align="center"><a href="facturar_pedidos.php?pedido=<?php echo $row_pedidos['id_pedidos']; ?>"><img src="imagenes/botonFacturar.png" width="81" height="26" /></a></td>
          <td align="center"><a href="eliminar_pedidos.php?pedido=<?php echo $row_pedidos['id_pedidos']; ?>"><img src="imagenes/botonEliminar.png" width="81" height="26" /></a></td>
          <td background="imagenes/v_back_der.gif">&nbsp;</td>
        </tr>
        <?php } while ($row_pedidos = mysql_fetch_assoc($pedidos)); ?>
   
     

    <tr>
      <td background="imagenes/v_back_izq.gif" height="1" width="13"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td colspan="5" class="tituloDOSE_2">&nbsp;
        <table border="0" align="center">
          <tr>
            <td><?php if ($pageNum_pedidos > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_pedidos=%d%s", $currentPage, 0, $queryString_pedidos); ?>"><img src="First.gif" /></a>
              <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_pedidos > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_pedidos=%d%s", $currentPage, max(0, $pageNum_pedidos - 1), $queryString_pedidos); ?>"><img src="Previous.gif" /></a>
              <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_pedidos < $totalPages_pedidos) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_pedidos=%d%s", $currentPage, min($totalPages_pedidos, $pageNum_pedidos + 1), $queryString_pedidos); ?>"><img src="Next.gif" /></a>
              <?php } // Show if not last page ?></td>
            <td><?php if ($pageNum_pedidos < $totalPages_pedidos) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_pedidos=%d%s", $currentPage, $totalPages_pedidos, $queryString_pedidos); ?>"><img src="Last.gif" /></a>
              <?php } // Show if not last page ?></td>
          </tr>
        </table></td>
      <td background="imagenes/v_back_der.gif" width="14">&nbsp;</td>
  </tr>
    <tr>
      <td align="left" height="1" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
      <td height="1" colspan="5" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
      <td align="right" height="1" width="14"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
    </tr>
  </tbody>
</table>
<p>&nbsp;</p>
</form>
</body>
</html>
<?php
mysql_free_result($pedidos);

mysql_free_result($clientes);

mysql_free_result($almacen);

mysql_free_result($producto);
?>
