<?php require_once('Connections/conexion.php'); ?>
<?php 
//validar usuario

if($_COOKIE["val"]==true){
	if($_COOKIE["cc"]!=1){
	echo "<script type=\"text/javascript\">alert ('Usted no posee permisos para Cerrar Caja');location.href='fondo.php' </script>";
    exit;
	}
}
else{
echo "<script type=\"text/javascript\">alert ('Error usuario invalido'); location.href='fondo.php' </script>";
 exit;
}
?>
<?php

//validamos que no existan otra caja abierta
mysql_select_db($database_conexion, $conexion);
$query_cajas = "SELECT * FROM caja where estado='ABIERTA' ";
$cajas = mysql_query($query_cajas, $conexion) or die(mysql_error());
$row_cajas = mysql_fetch_assoc($cajas);
$totalRows_cajas = mysql_num_rows($cajas);


if($totalRows_cajas==0){
	echo "<script type=\"text/javascript\">alert ('Debe Aperturar Caja Primero'); location.href='fondo.php' </script>";
 exit;
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	//ejecucuion de la sentemcia sql
$sql="select * from usuarios where login='$_POST[usuario]' and clave='$_POST[clave]'";
$resultado= mysql_query($sql)or die(mysql_error());
$fila=mysql_fetch_array($resultado);
$totalRows_fila = mysql_num_rows($resultado);


mysql_select_db($database_conexion, $conexion);
$query_permisos = "SELECT * FROM permisos_usuarios where id_usuario='$fila[id_empleado]'";
$permisos = mysql_query($query_permisos, $conexion) or die(mysql_error());
$row_permisos = mysql_fetch_assoc($permisos);
$totalRows_permisos = mysql_num_rows($permisos);


if($row_permisos["cc"]==1){
	
  $updateSQL = sprintf("UPDATE caja SET fecha_ci=NOW(), estado=%s WHERE estado=%s",
                       GetSQLValueString("CERRADA", "text"),
                       GetSQLValueString("ABIERTA", "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
   if($Result1){
	 echo "<script type=\"text/javascript\">alert ('Caja Cerrada'); location.href='fondo.php' </script>";
	}//fin del if
	
}//fin del if
else{
	echo "<script type=\"text/javascript\">alert ('Usted no Posee perminsos para Cerrar Cajas'); location.href='fondo.php' </script>";
 exit;
}//fin del else
		
}//fin del procesamiento del formulario



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css.css" rel="stylesheet" type="text/css" />
<title>Documento sin título</title>
</head>
<script type="text/javascript">
function validar(){
	
	

			if(document.form1.usuario.value==''){
						alert('Debe Ingresar un Usuario');
						return false;
			}
			if(document.form1.clave.value==''){
						alert('Debe Ingresar una Clave');
						return false;
			}
			
			var valor=confirm('¿Esta seguro de Cerrar Caja?');
			if(valor==false){
			return false;
			}
			else{
			return true;
			}
		
		   						
}
</script>
<body>
<form action="<?php echo $editFormAction; ?>" method="post"  onsubmit="return validar()" name="form1" id="form1">
  <table width="559" border="0" align="center" cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
        <td align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Cierre de Caja</span></td>
        <td align="right" height="1" width="12"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
      </tr>
      <tr>
        <td width="13" height="140" rowspan="2" background="imagenes/v_back_izq.gif"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
        <td align="center" valign="top" class="tituloDOSE_2"><img src="imagenes/logoHeader2.jpg" width="353" height="38" /></td>
        <td width="12" rowspan="2" background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <tr>
        <td class="tituloDOSE_2" valign="top" width="534"><table border="0" cellpadding="0" cellspacing="0" width="534">
          <tbody>
            <tr>
              <td width="1" align="left"  ></td>
              <td width="650"  height="1" class="tituloDOSE_2"><table border="0" cellpadding="0" cellspacing="0">
                <tbody>
                  <tr>
                    <td align="right"></td>
                  </tr>
                  <tr>
                    <td align="center" width="520"><table width="518" align="center" cellpadding="3" cellspacing="0">
                      <tbody>
                        <tr>
                          <td width="209"align="right" valign="baseline"><strong>Ingrese su Usuario:</strong></td>
                          <td width="295" valign="baseline"><input name="usuario" type="text" id="usuario" value=""  size="20" maxlength="20" <?=$disable?>/></td>
                        </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap"><strong>Ingrese su Clave:</strong></td>
                          <td valign="baseline"><input name="clave" type="password" id="clave" value="" size="20" maxlength="10" <?=$disable?>/></td>
                        </tr>
                        <tr>
                          <td colspan="2" align="center" class="tituloDOSE_2">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="2" align="center" class="tituloDOSE_2"><input type="submit" name="button" id="button" value="ACCEDER" /></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                  <tr>
                    <td width="520" height="2" align="right"></td>
                  </tr>
                </tbody>
              </table></td>
              <td width="1"></td>
            </tr>
          </tbody>
        </table></td>
      </tr>
      <tr>
        <td align="left" height="12" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
        <td width="534" height="12" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
        <td align="right" height="12" width="12"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($cajas);

mysql_free_result($permisos);
?>
