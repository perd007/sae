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
  $insertSQL = sprintf("INSERT INTO clientes (nombres, telefono, cedula, direccion, tipo) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['nombres'], "text"),
                       GetSQLValueString($_POST['telefono'], "text"),
                       GetSQLValueString($_POST['cedula'], "int"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['tipo'], "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
   if($Result1==1){
  echo "<script type=\"text/javascript\">alert ('Cliente Registrado');  location.href='' </script>";
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
<style type="text/css">
</style>
</head>

<script type="text/javascript">
function validar(){

		

		
			if(document.form1.telefono.value!=""){
			 var filtro = /^(\d)+$/i;
		      if (!filtro.test(document.getElementById('telefono').value)){
				alert('EL NUMERO DE TELEFONO DEBE SER NUMERICO');
				return false;
		   		}
				}
		   		
				
			
			

			if(document.form1.cedula.value==''){
						alert('Debe Ingresar un Numero de Cedula');
						return false;
			}


			if(document.form1.nombres.value==''){
						alert('Debe Ingresar un Nombre');
						return false;
			}




			
}
</script>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" onsubmit="return validar()" name="form1" id="form1">

<table border="0" cellpadding="0" cellspacing="0" width="696">
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Registro de Clientes</span></td>
      <td align="right" height="1" width="12"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr>
      <td width="13" height="140" rowspan="2" background="imagenes/v_back_izq.gif"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td valign="top" class="tituloDOSE_2">&nbsp;</td>
      <td width="12" rowspan="2" background="imagenes/v_back_der.gif"><img src="imagenes/cargar_productos.php" alt="1" height="2" width="13" /></td>
    </tr>
    <tr>
      <td class="tituloDOSE_2" valign="top" width="671"><table border="0" cellpadding="0" cellspacing="0" width="671">
          <tbody>
            <tr>
              <td width="1" align="left"  ></td>
              <td width="763"  height="1" class="tituloDOSE_2"><table border="0" cellpadding="0" cellspacing="0">
                <tbody>
                  <tr>
                    <td align="right"></td>
                  </tr>
                  <tr>
                    <td align="center" width="659"><table cellpadding="3" cellspacing="0" width="660">
                      <tbody>
                        <tr>
                          <td width="61"align="right" valign="baseline">Nombre:</td>
                          <td width="222" valign="baseline"><input type="text" name="nombres" id="nombres" value="<?php echo $row_clientes['nombres']; ?>"  size="32" <?=$disable?>/></td>
                          <td width="79" align="right" valign="baseline" nowrap="nowrap">Cedula o Rif:</td>
                          <td width="272" valign="baseline"><input name="cedula" type="text" id="cedula" value="<?php echo $row_clientes['cedula']; ?>" size="32" maxlength="11" <?=$disable?>/></td>
                        </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap">Telefono:</td>
                          <td valign="baseline"><input type="text" name="telefono" id="telefono" value="<?php echo $row_clientes['telefono']; ?>" size="32" <?=$disable?>/></td>
                          <td align="right" valign="baseline">Tipo:</td>
                          <td valign="baseline"><label for="tipo"></label>
                            <select name="tipo" id="tipo" <?=$disable?>>
                              <option value="Natural" <?php if (!(strcmp("Natural", $row_clientes['tipo']))) {echo "selected=\"selected\"";} ?>>Natural</option>
                              <option value="Juridica" <?php if (!(strcmp("Juridica", $row_clientes['tipo']))) {echo "selected=\"selected\"";} ?>>Juridica</option>
                            </select></td>
                        </tr>
                        <tr>
                          <td class="tituloDOSE_2" align="left">Direccion</td>
                          <td colspan="3" valign="baseline"><label for="direccion">
                            <textarea name="direccion" cols="60" rows="4" <?=$disable?>><?php echo $row_clientes['direccion']; ?></textarea>
                          </label></td>
                        </tr>
                        <tr>
                          <td colspan="4" align="center" class="tituloDOSE_2"><input type="submit" name="button" id="button" value="REGISTRAR" /></td>
                          </tr>
                      </tbody>
                    </table></td>
                  </tr>
                  <tr>
                    <td align="right" width="659"></td>
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
      <td width="671" height="12" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
      <td align="right" height="12" width="12"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
    </tr>
  </tbody>
</table>
<p>&nbsp;</p>
 <input type="hidden" name="MM_insert" value="form1" />
</form>
</body>
</html>