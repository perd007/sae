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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE proveedor SET rif=%s, nombre=%s, telefono=%s, direccion=%s, tipo=%s WHERE id_proveedor=%s",
                       GetSQLValueString($_POST['rif'], "text"),
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['telefono'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
					   GetSQLValueString($_POST['tipo'], "text"),
                       GetSQLValueString($_POST['id_proveedor'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
    if($Result1==1){
  echo "<script type=\"text/javascript\">alert ('Datos Actualizados');  location.href='consultar_proveedores.php' </script>";
  }else{
  echo "<script type=\"text/javascript\">alert ('Ocurrio un Error');  location.href='consultar_proveedores.php' </script>";
  exit;
  }
}

mysql_select_db($database_conexion, $conexion);
$query_proveedor = "SELECT * FROM proveedor where id_proveedor='$_GET[id]'";
$proveedor = mysql_query($query_proveedor, $conexion) or die(mysql_error());
$row_proveedor = mysql_fetch_assoc($proveedor);
$totalRows_proveedor = mysql_num_rows($proveedor);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="css.css" rel="stylesheet" type="text/css" />
<title>Documento sin título</title>
</head>
<script language="javascript">



function validar(){
						
			if(document.form1.rif.value==""){
						alert("Debe ingresar el rif del proveedor");
						return false;
				}
			if(document.form1.nombre.value==""){
						alert("Debe ingresar un nombre para el proveedor");
						return false;
				}
			if(document.form1.telefono.value==""){
						alert("Debe ingresar un telefono del proveedor");
						return false;
				}
			if(document.form1.direccion.value==""){
						alert("Debe ingresar una direccion para el proveedor");
						return false;
				}
			
				
		}
		

		
</script>
<body>
<form action="<?php echo $editFormAction; ?>" onsubmit="return validar()" method="post" name="form1" id="form1">
  <table border="0" cellpadding="0" cellspacing="0" width="647">
    <tbody>
      <tr>
        <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
        <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita">Actualizacion de  Proveedores</span></td>
        <td align="right" height="1" width="12"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
      </tr>
      <tr>
        <td background="imagenes/v_back_izq.gif" height="1" width="15"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
        <td class="tituloDOSE_2"><table border="0" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
              <td align="right"></td>
            </tr>
            <tr>
              <td align="center" width="140"><table cellpadding="3" cellspacing="0" width="600">
                <tbody>
                  <tr>
                    <td valign="baseline"align="right">RIF:</td>
                    <td valign="baseline"><input name="rif" type="text" id="rif" value="<?php echo $row_proveedor['rif']; ?>" size="15" maxlength="12" /></td>
                    <td align="right" valign="baseline" nowrap="nowrap">&nbsp;</td>
                    <td valign="baseline">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="right" valign="baseline" nowrap="nowrap">Nombre:</td>
                    <td colspan="3" valign="baseline"><input name="nombre" type="text" value="<?php echo $row_proveedor['nombre']; ?>" size="80" maxlength="100" /></td>
                  </tr>
                  <tr>
                    <td align="right" valign="baseline" nowrap="nowrap">Tipo:</td>
                    <td valign="baseline"><label for="tipo"></label>
                      <select name="tipo" id="tipo">
                        <option value="CONTADO" <?php if (!(strcmp("CONTADO", $row_proveedor['tipo']))) {echo "selected=\"selected\"";} ?>>CONTADO</option>
                        <option value="CREDITO" <?php if (!(strcmp("CREDITO", $row_proveedor['tipo']))) {echo "selected=\"selected\"";} ?>>CREDITO</option>
                      </select></td>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="right" valign="baseline" nowrap="nowrap">Telefono:</td>
                    <td valign="baseline"><input name="telefono" type="text" id="telefono" value="<?php echo $row_proveedor['telefono']; ?>" size="32" maxlength="11" /></td>
                    <td align="left">&nbsp;</td>
                    <td align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="tituloDOSE_2" align="left">Direccion</td>
                    <td colspan="3" valign="baseline"><label for="direccion"></label>
                      <textarea name="direccion" onkeydown="if(this.value.length &gt;= 500){ alert('Has superado el tamaño máximo permitido de este campo'); return false; }" id="direccion" cols="75" rows="8"><?php echo $row_proveedor['direccion']; ?></textarea></td>
                  </tr>
                  <tr>
                    <td class="tituloDOSE_2" align="left">&nbsp;</td>
                    <td class="tituloDOSE_2" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4" align="center"><input type="submit" value="ACTUALIZAR  PROVEEDOR" /></td>
                  </tr>
                </tbody>
              </table></td>
            </tr>
            <tr>
              <td align="right" width="80%"></td>
            </tr>
          </tbody>
        </table></td>
        <td background="imagenes/v_back_der.gif" width="12"><img src="imagenes/cargar_productos.php" alt="1" height="2" width="13" /></td>
      </tr>
      <tr>
        <td align="left" height="1" width="15"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
        <td background="imagenes/v_back_inf.gif" height="1" width="620"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
        <td align="right" height="1" width="12"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id_proveedor" value="<?php echo $row_proveedor['id_proveedor']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($proveedor);
?>
