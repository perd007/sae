<?php require_once('Connections/conexion.php'); ?>
<?php
session_start();
if($_GET['pageNum_gastos']==0){
$_SESSION["busqueda"]="";
}

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
?>

<?php	
extract($_POST);
extract($_GET);

//	------------------------------------
 require_once("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);
//	------------------------------------

if ($fBuscar != "") { 

	$filtro.=" where descripcion LIKE '%".utf8_decode($fBuscar)."%' OR
					tipo LIKE '%".utf8_decode($fBuscar)."%' OR
					clasificacion LIKE '%".utf8_decode($fBuscar)."%' OR
					periodo LIKE '%".utf8_decode($fBuscar)."%'";
					
					$_SESSION["busqueda"]=$filtro;
					
} else $dBuscar = "disabled";

?>
  <?php
	
	
$maxRows_gastos = 20;
$pageNum_gastos = 0;
if (isset($_GET['pageNum_gastos'])) {
  $pageNum_gastos = $_GET['pageNum_gastos'];
 
}
$startRow_gastos = $pageNum_gastos * $maxRows_gastos;

mysql_select_db($database_conexion, $conexion);
$query_gastos = "SELECT * FROM gastos $_SESSION[busqueda] order by fecha desc ";
$query_limit_gastos = sprintf("%s LIMIT %d, %d", $query_gastos, $startRow_gastos, $maxRows_gastos);
$gastos = mysql_query($query_limit_gastos, $conexion) or die(mysql_error());
$row_gastos = mysql_fetch_assoc($gastos);

if (isset($_GET['totalRows_gastos'])) {
  $totalRows_gastos = $_GET['totalRows_gastos'];
} else {
  $all_gastos = mysql_query($query_gastos);
  $totalRows_gastos = mysql_num_rows($all_gastos);
}
$totalPages_gastos = ceil($totalRows_gastos/$maxRows_gastos)-1;

$queryString_gastos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_gastos") == false && 
        stristr($param, "totalRows_gastos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_gastos = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_gastos = sprintf("&totalRows_gastos=%d%s", $totalRows_gastos, $queryString_gastos);


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
<title>Documento sin título</title>
<style type="text/css">
.centrar {	text-align: center;
	color:#EFEFEF;
}
</style>
</head>
<script language="javascript">
<!--

function validar(){

			var valor=confirm('¿Esta seguro de Eliminar este Gasto?');
			if(valor==false){
			return false;
			}
			else{
			return true;
			}
		
}
//-->
</script>
<body >
<form name="frmentrada" id="frmentrada"  action="consultar_gastos.php?" method="post">

<table width="400" class="tblFiltro">
  <tr b="b">
    <td width="54" height="29" align="right" class="negrita">Buscar:</td>
    <td width="261"><input type="text" name="fBuscar" id="fBuscar" value="<?=$fBuscar?>" style="width:250px;"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" class="negrita" value="Buscar" /></td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="760" >
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td colspan="8" align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita"><strong>Visualizar Gastos Ingresados</strong></span></td>
      <td align="right" height="1" width="13"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr align="center" class="centrar">
      <td height="1" align="center" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td width="89" align="left"  valign="baseline" bgcolor="#0000FF"class="negrita">Fecha</td>
      <td width="70" align="left" valign="baseline" bgcolor="#0000FF" class="negrita">Tipo</td>
      <td width="75" align="left" valign="baseline" bgcolor="#0000FF" class="negrita">Periodo</td>
      <td width="80" align="left" valign="baseline" bgcolor="#0000FF" class="negrita">Clasificacion</td>
      <td width="76" align="left" valign="baseline" bgcolor="#0000FF" class="negrita">Monto</td>
      <td width="300" align="center" valign="baseline" bgcolor="#0000FF" class="negrita">Descripcion</td>
      <td width="22" align="center" valign="baseline" bgcolor="#0000FF" class="negrita">Opc</td>
      <td width="22" align="center" valign="baseline" bgcolor="#0000FF" class="negrita">Opc</td>
      <td align="center" background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
  
    <?php do { 



	?>
      <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?php echo $row_prod['id_productos']; ?>">
        <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
        <td align="left" ><?php echo $row_gastos['fecha']; ?></td>
        <td align="left"><?php echo $row_gastos['tipo']; ?></td>
        <td align="left"><?php echo $row_gastos['periodo']; ?></td>
        <td align="left"><?php echo $row_gastos['clasificacion']; ?></td>
        <td align="left"><?=(number_format($row_gastos['monto'],"2",",","."))?></td>
        <td align="left"> <?php echo $row_gastos['descripcion']; ?></td>
        <td align="center"><a href="modificar_gastos.php?id=<?php echo $row_gastos['id_gastos']; ?>">
          <input type="button"  value="M" />
        </a></td>
        <td align="center"><a onclick='return validar()' href="eliminar_gastos.php?id=<?php echo $row_gastos['id_gastos']; ?>">
          <input type="button"  value="E" />
        </a></td>
        <td background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <?php } while ($row_gastos = mysql_fetch_assoc($gastos)); ?>
  
    <tr>
      <td background="imagenes/v_back_izq.gif" height="1" width="13"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td colspan="8" class="tituloDOSE_2"><table border="0" align="center">
          <tr>
            <td><?php if ($pageNum_gastos > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_gastos=%d%s", $currentPage, 0, $queryString_gastos); ?>?id=a"><img src="First.gif" /></a>
                <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_gastos > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_gastos=%d%s", $currentPage, max(0, $pageNum_gastos - 1), $queryString_gastos); ?>?id='a'"><img src="Previous.gif" /></a>
                <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_gastos < $totalPages_gastos) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_gastos=%d%s", $currentPage, min($totalPages_gastos, $pageNum_gastos + 1), $queryString_gastos);?>?wer=3"><img src="Next.gif" /></a>
                <?php } // Show if not last page ?></td>
            <td><?php if ($pageNum_gastos < $totalPages_gastos) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_gastos=%d%s", $currentPage, $totalPages_gastos, $queryString_gastos); ?>?id=a"><img src="Last.gif" /></a>
                <?php } // Show if not last page ?></td>
          </tr>
      </table></td>
      <td background="imagenes/v_back_der.gif" width="13">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" height="1" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
      <td height="1" colspan="8" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
      <td align="right" height="1" width="13"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
    </tr>
  </tbody>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

 
</form>
</body>
</html>
<?php
mysql_free_result($gastos);
?>
