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
	
/* verificar funcion
mysql_select_db($database_conexion, $conexion);
$query_productos = "SELECT * FROM productos where id_productos='$_POST[id_productos]'";
$productos = mysql_query($query_productos, $conexion) or die(mysql_error());
$row_productos = mysql_fetch_assoc($productos);
$totalRows_productos = mysql_num_rows($productos);

if($totalRows_productos>=1){ echo "<script type=\"text/javascript\">alert ('Este codigo ya existe para otro Producto');  location.href='' </script>";
  exit;}
	*/
  $updateSQL = sprintf("UPDATE productos SET tipo=%s, marca=%s, modelo=%s, nombre=%s,  descripcion=%s WHERE id_productos=%s",
                       GetSQLValueString($_POST['tipo'], "text"),
                       GetSQLValueString($_POST['marca'], "text"),
                       GetSQLValueString($_POST['modelo'], "text"),
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['descripcion'], "text"),
                       GetSQLValueString($_POST['id_productos'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
   if($Result1==1){
  echo "<script type=\"text/javascript\">alert ('Producto Actualizado');  location.href='consultar_productos.php' </script>";
  }else{
  echo "<script type=\"text/javascript\">alert ('Ocurrio un Error');  location.href='' </script>";
  exit;
  }
}

$id=$_GET["id"];
mysql_select_db($database_conexion, $conexion);
$query_productos = "SELECT * FROM productos where id_productos='$id'";
$productos = mysql_query($query_productos, $conexion) or die(mysql_error());
$row_productos = mysql_fetch_assoc($productos);
$totalRows_productos = mysql_num_rows($productos);

if($row_productos["tipo"]=="Vehiculo"){
$disable_resp="disabled='disabled'";	
}
if($row_productos["tipo"]=="Repuesto"){
$disable_vehi="disabled='disabled'";	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css.css" rel="stylesheet" type="text/css" />

<title>Documento sin título</title>
</head>
<script language="javascript">

/*style="visibility:hidden;"*/
function habilitar(){
	if(document.form1.tipo.value=="Vehiculo"){
		/* deshabilitamos y limpiamos los campos correspondientes a los respuestos*/
		document.form1.modelo.value=" ";	
		
		document.form1.marca.value=" ";	
		
		
		
		
		/* habilitamos y limpiamos los campos correspondientes a los vehiculos*/
		
		
		document.form1.ano.value=" ";	
		document.form1.ano.disabled=false;
		
			
	}
		
	if(document.form1.tipo.value=="Repuesto"){
	/* habilitamos y limpiamos los campos correspondientes a los respuestos*/
		document.form1.modelo.value=" ";	
		
		document.form1.marca.value=" ";	
	
		
		
	
		
	/* deshabilitamos y limpiamos los campos correspondientes a los vehiculos*/
		
		document.form1.ano.value=" ";	
		document.form1.ano.disabled=true;
	}
}


function validar(){

		
						
			
			if(document.form1.nombre.value==""){
						alert("Debe ingresar un nombre para el producto");
						return false;
				}
			
				
			
			
		}
		

		
</script>
<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="left" border="0" cellspacing="0">
    <tbody>
      <tr>
        <td><table border="0" cellpadding="0" cellspacing="0" width="760">
          <tbody>
            <tr>
              <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
              <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita">Actualizar de Producto</span></td>
              <td align="right" height="1" width="13"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
            </tr>
            <tr>
              <td background="imagenes/v_back_izq.gif" height="1" width="13"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
              <td class="tituloDOSE_2"><table border="0" cellpadding="0" cellspacing="0">
                <tbody>
                  <tr>
                    <td align="right"></td>
                  </tr>
                  <tr>
                    <td align="center" width="701"><table cellpadding="3" cellspacing="0" width="692">
                      <tbody>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap">Tipo:</td>
                          <td colspan="3" valign="baseline">Producto Venta
                            <input <?php if (!(strcmp($row_productos['tipo'],"producto"))) {echo "checked=\"checked\"";} ?> name="tipo" type="radio" id="tipo" value="producto" checked="checked" />
                            <label for="radio"> Insumo
                              <input <?php if (!(strcmp($row_productos['tipo'],"Insumo"))) {echo "checked=\"checked\"";} ?> type="radio" name="tipo" id="tipo" value="insumo" />
                            </label>
                            <label for="radio"> Servicio
                              <input <?php if (!(strcmp($row_productos['tipo'],"servicio"))) {echo "checked=\"checked\"";} ?> type="radio" name="tipo" id="tipo" value="servicio" />
                            </label></td>
                        </tr>
                        <tr>
                          <td width="89" align="right" valign="baseline" nowrap="nowrap">Marca:</td>
                          <td colspan="3" valign="baseline"><input name="marca" type="text" value="<?php echo $row_productos['marca']; ?>" size="45" maxlength="45"  /></td>
                        </tr>
                        <tr>
                          <td align="right">Modelo:</td>
                          <td colspan="3" align="left"><input name="modelo" type="text" value="<?php echo $row_productos['modelo']; ?>" size="45" maxlength="45"  /></td>
                          </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap">Nombre:</td>
                          <td colspan="3" valign="baseline"><input name="nombre" type="text" value="<?php echo $row_productos['nombre']; ?>" size="45" maxlength="45" /></td>
                          </tr>
                        <tr>
                          <td align="right">&nbsp;</td>
                          <td width="270" align="left"><label for="ano"></label></td>
                          <td width="89" align="right">&nbsp;</td>
                          <td width="270" align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="tituloDOSE_2" align="left">Descripcion</td>
                          <td colspan="3" valign="baseline"><label for="descripcion"></label>
                            <textarea name="descripcion" onkeydown="if(this.value.length &gt;= 500){ alert('Has superado el tamaño máximo permitido de este campo'); return false; }" id="descripcion" cols="75" rows="8"><?php echo $row_productos['descripcion']; ?></textarea></td>
                        </tr>
                        <tr>
                          <td class="tituloDOSE_2" align="left">&nbsp;</td>
                          <td class="tituloDOSE_2" align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="4" align="center"><input type="submit" value="ACTUALIZAR  PRODUCTO" /></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                  <tr>
                    <td align="right" width="701"></td>
                  </tr>
                </tbody>
              </table></td>
              <td background="imagenes/v_back_der.gif" width="13"><img src="imagenes/cargar_productos.php" alt="1" height="2" width="13" /></td>
            </tr>
            <tr>
              <td align="left" height="1" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
              <td background="imagenes/v_back_inf.gif" height="1" width="469"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
              <td align="right" height="1" width="13"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
            </tr>
          </tbody>
        </table></td>
      </tr>
      <tr> </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id_productos" value="<?php echo $row_productos['id_productos']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>

