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

$maxRows_emp = 15;
$pageNum_emp = 0;
if (isset($_GET['pageNum_emp'])) {
  $pageNum_emp = $_GET['pageNum_emp'];
}
$startRow_emp = $pageNum_emp * $maxRows_emp;

mysql_select_db($database_conexion, $conexion);
$query_emp = "SELECT * FROM empleados";
$query_limit_emp = sprintf("%s LIMIT %d, %d", $query_emp, $startRow_emp, $maxRows_emp);
$emp = mysql_query($query_limit_emp, $conexion) or die(mysql_error());
$row_emp = mysql_fetch_assoc($emp);

if (isset($_GET['totalRows_emp'])) {
  $totalRows_emp = $_GET['totalRows_emp'];
} else {
  $all_emp = mysql_query($query_emp);
  $totalRows_emp = mysql_num_rows($all_emp);
}
$totalPages_emp = ceil($totalRows_emp/$maxRows_emp)-1;


$queryString_emp = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_emp") == false && 
        stristr($param, "totalRows_emp") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_emp = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_emp = sprintf("&totalRows_emp=%d%s", $totalRows_emp, $queryString_emp);

if($totalRows_emp==0){
  echo "<script type=\"text/javascript\">alert ('No existen Empleados Registrados');  location.href='fondo.php' </script>";
  exit;
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css.css" rel="stylesheet" type="text/css" />
<title>Documento sin título</title>
<style type="text/css">
.centrar {	text-align: center;
	color:#EFEFEF;
}
a{text-decoration:none}
</style>
</head>
<script language="javascript">
<!--

function validar(){

			var valor=confirm('¿Esta seguro de Eliminar este Usuario?');
			if(valor==false){
			return false;
			}
			else{
			return true;
			}
		
}
//-->
</script>

<body>
<table border="0" cellpadding="0" cellspacing="0" width="769" >
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td colspan="7" align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Consulta deUsuarios</span></td>
      <td align="right" height="1" width="13"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr align="center" class="centrar">
      <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
      <td width="167"  valign="baseline" bgcolor="#0000FF"class="negrita">Nombre</td>
      <td width="97" valign="baseline" bgcolor="#0000FF" class="negrita">Cedula </td>
      <td width="91" valign="baseline" bgcolor="#0000FF" class="negrita">Cargo</td>
      <td width="128" valign="baseline" bgcolor="#0000FF" class="negrita">Telefono</td>
      <td width="115" valign="baseline" bgcolor="#0000FF" class="negrita">Usuario</td>
      <td width="68" valign="baseline" bgcolor="#0000FF" class="negrita">Opciones</td>
      <td width="77" valign="baseline" bgcolor="#0000FF" class="negrita">Opciones</td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
   
    <?php do { 
	
	mysql_select_db($database_conexion, $conexion);
	$query_usu = "SELECT * FROM usuarios where id_empleado='$row_emp[cedula]'";
	$usu = mysql_query($query_usu, $conexion) or die(mysql_error());
	$row_usu = mysql_fetch_assoc($usu);
	$totalRows_usu = mysql_num_rows($usu); 

?>
      <tr class="trListaBody" onclick="mClk(this, 'registro');" id="<?php echo $row_clie['id_clientes']; ?>">
        <td background="imagenes/v_back_izq.gif" height="1">&nbsp;</td>
        <td align="center" ><?php echo $row_emp['nombres']; ?></td>
        <td align="center"><?php echo $row_emp['cedula']; ?></td>
        <td align="center"><?php echo $row_emp['cargo']; ?></td>
        <td align="center"><?php echo $row_emp['telefono']; ?></td>
        <td align="center"><?php echo $row_usu['login']; ?></td>
        <td align="center"><a href="modificar_usuarios.php?cedula=<?php echo $row_emp['cedula']; ?>">
          <input type="button"  value="Modificar" />
        </a></td>
        <td align="center"><a onclick='return validar()' href="eliminar_usuarios.php?cedula=<?php echo $row_emp['cedula']; ?>">
          <input type="button"  value="Eliminar" />
        </a></td>
        <td background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <?php } while ($row_emp = mysql_fetch_assoc($emp)); ?>
    <tr>
      <td background="imagenes/v_back_izq.gif" height="1" width="13"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td colspan="7" class="tituloDOSE_2">&nbsp;
        <table border="0" align="center">
          <tr>
            <td><?php if ($pageNum_emp > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_emp=%d%s", $currentPage, 0, $queryString_emp); ?>"><img src="First.gif" /></a>
            <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_emp > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_emp=%d%s", $currentPage, max(0, $pageNum_emp - 1), $queryString_emp); ?>"><img src="Previous.gif" /></a>
            <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_emp < $totalPages_emp) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_emp=%d%s", $currentPage, min($totalPages_emp, $pageNum_emp + 1), $queryString_emp); ?>"><img src="Next.gif" /></a>
            <?php } // Show if not last page ?></td>
            <td><?php if ($pageNum_emp < $totalPages_emp) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_emp=%d%s", $currentPage, $totalPages_emp, $queryString_emp); ?>"><img src="Last.gif" /></a>
            <?php } // Show if not last page ?></td>
          </tr>
      </table></td>
      <td background="imagenes/v_back_der.gif" width="13">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" height="1" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
      <td height="1" colspan="7" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
      <td align="right" height="1" width="13"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
    </tr>
  </tbody>
</table>
</body>
</html>
<?php
mysql_free_result($emp);

mysql_free_result($usu);
?>
