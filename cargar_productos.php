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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
mysql_select_db($database_conexion, $conexion);


if($totalRows_productos>=1){ echo "<script type=\"text/javascript\">alert ('Este codigo ya existe para otro Producto');  location.href='' </script>";
  exit;}
	
  $insertSQL = sprintf("INSERT INTO productos (tipo, marca, modelo, nombre, descripcion) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tipo'], "text"),
                       GetSQLValueString($_POST['marca'], "text"),
                       GetSQLValueString($_POST['modelo'], "text"),
                       GetSQLValueString($_POST['nombre'], "text"),
					   GetSQLValueString($_POST['descripcion'], "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  if($Result1==1){
  echo "<script type=\"text/javascript\">alert ('Producto Cargado');  location.href='' </script>";
  }else{
  echo "<script type=\"text/javascript\">alert ('Ocurrio un Error');  location.href='' </script>";
  exit;
  }
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="css.css" rel="stylesheet" type="text/css" />
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
<form action="<?php echo $editFormAction; ?>" method="post" onsubmit="return validar()" name="form1" id="form1">
<table align="left" border="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="668"><table border="0" cellpadding="0" cellspacing="0" width="668">
        <tbody>
          <tr>
            <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
            <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita">Carga de Productos y Servicios</span></td>
            <td align="right" height="1" width="12"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
          </tr>
          <tr>
            <td background="imagenes/v_back_izq.gif" height="1" width="13"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
            <td class="tituloDOSE_2"><table border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td align="right"></td>
                  </tr>
                <tr>
                  <td align="center" width="642"><table cellpadding="3" cellspacing="0" width="643">
                    <tbody>
                      <tr>
                        <td align="right" valign="baseline" nowrap="nowrap">Tipo:</td>
                        <td colspan="3" valign="baseline"><label for="radio">Producto Venta
                          <input type="radio" name="tipo" id="tipo" value="producto" />
                           </label>
                           <label for="radio">Insumo
                          <input type="radio" name="tipo" id="tipo" value="insumo"  />
                          </label>
                          <label for="radio"> Servicio 
                            <input type="radio" name="tipo" id="tipo" value="servicio"  checked="checked" />
                          </label>
                          </td>
                      </tr>
                      <tr>
                        <td width="54" align="right" valign="baseline" nowrap="nowrap">Marca:</td>
                        <td colspan="3" valign="baseline"><input name="marca" type="text" value="" size="45" maxlength="45" /></td>
                        </tr>
                      <tr>
                        <td align="right">Modelo:</td>
                        <td colspan="3" align="left"><input  name="modelo" id="modelo" type="text" value="" size="45" maxlength="45" /></td>
                        </tr>
                      <tr>
                        <td align="right" valign="baseline" nowrap="nowrap">Nombre:</td>
                        <td colspan="3" valign="baseline"><input name="nombre" type="text" value="" size="45" maxlength="45" /></td>
                        </tr>
                      <tr>
                        <td class="tituloDOSE_2" align="left">Descripcion</td>
                        <td colspan="3" valign="baseline"><label for="descripcion"></label>
                          <textarea name="descripcion" onKeyDown="if(this.value.length &gt;= 500){ alert('Has superado el tama&ntilde;o m&aacute;ximo permitido de este campo'); return false; }" id="descripcion" cols="75" rows="8"></textarea></td>
                      </tr>
                      <tr>
                        <td class="tituloDOSE_2" align="left">&nbsp;</td>
                        <td width="270" align="left" class="tituloDOSE_2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="4" align="center"><input type="submit" value="CARGAR PRODUCTO" /></td>
                      </tr>
                      </tbody>
                  </table></td>
                  </tr>
                <tr>
                  <td align="right" width="642"></td>
                  </tr>
              </tbody>
            </table></td>
            <td background="imagenes/v_back_der.gif" width="12">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" height="1" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
            <td background="imagenes/v_back_inf.gif" height="1" width="643"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
            <td align="right" height="1" width="12"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr> </tr>
  </tbody>
</table>
<input type="hidden" name="MM_insert" value="form1">

</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($productos);
?>
