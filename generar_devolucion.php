<?php require_once('Connections/conexion.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {


		
	
$updateSQL = sprintf("UPDATE facturas SET transaccion='DEVOLUCION' WHERE serie=%s and numero=%s ",

                        GetSQLValueString($_POST['serie_venta'], "text"),
                       GetSQLValueString($_POST['factura_venta'], "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
  
if($Result1==1){
$updateSQL2 = sprintf("UPDATE ventas_productos SET transaccion='DEVUELTO' WHERE serie=%s and factura=%s",

                        GetSQLValueString($_POST['serie_venta'], "text"),
                       GetSQLValueString($_POST['factura_venta'], "text")); 
					   

  mysql_select_db($database_conexion, $conexion);
  $Result2 = mysql_query($updateSQL2, $conexion) or die(mysql_error());

if($Result2==1){
	  //actualizamos el almacen
		$updateSQL3 = sprintf("UPDATE almacen SET transaccion='DEVOLUCION' WHERE serie_venta=%s and factura_venta=%s",

                       GetSQLValueString($_POST['serie_venta'], "text"),
                       GetSQLValueString($_POST['factura_venta'], "text"));
    

  mysql_select_db($database_conexion, $conexion);
  $Result3 = mysql_query($updateSQL3, $conexion) or die(mysql_error());
		
	
///////////////////////////////////////////////
	
	  }
	  
}
  

  

//validamos si fue exitosa la carga para eliminar los pedidos e insertar venta en el almacen	
	if($Result1==1 and $Result2==1 and $Result3==1){
		
		 echo "<script type=\"text/javascript\">alert ('DEVOLUCION REALIZADA');  location.href='devolver_factura.php' </script>";	
		
	}
//

}//fin del procesamiento del formulario





//RECIBIMOS DATOS
mysql_select_db($database_conexion, $conexion);
$query_factura = "SELECT * FROM facturas where numero='$_POST[numero]' and serie='$_POST[serie]'";
$factura = mysql_query($query_factura, $conexion) or die(mysql_error());
$row_factura = mysql_fetch_assoc($factura);
$totalRows_factura = mysql_num_rows($factura);


if($totalRows_factura==0){
	 echo "<script type=\"text/javascript\">alert ('Esta Factura no Existe');  location.href='devoluciones.php' </script>";
	 exit;
}



if($row_factura['transaccion']=='DEVOLUCION'){
	 echo "<script type=\"text/javascript\">alert ('Esta Factura ya fue Devuelta');  location.href='devoluciones.php' </script>";
	 exit;
}

mysql_select_db($database_conexion, $conexion);
$query_venta = "SELECT * FROM ventas_productos where factura='$_POST[numero]' and serie='$_POST[serie]'";
$venta = mysql_query($query_venta, $conexion) or die(mysql_error());
$row_venta = mysql_fetch_assoc($venta);
$totalRows_venta = mysql_num_rows($venta);


mysql_select_db($database_conexion, $conexion);
$query_clientes = "SELECT * FROM clientes where cedula='$row_factura[id_cliente]'";
$clientes = mysql_query($query_clientes, $conexion) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

$disable="disabled='disabled'";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<title>Documento sin título</title>
</head>

<body>
<form id="form1"   name="form1" method="post" onsubmit="return validar()" action="<?php echo $editFormAction; ?>" >
  <input type="hidden" name="MM_insert" value="form1" />
  <table border="0" cellpadding="0" cellspacing="0" width="776">
    <tbody>
      <tr>
        <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
        <td align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Datos  del Clientes</span></td>
        <td align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Datos de Facturacio</span>n</td>
        <td align="right" height="1" width="14"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
      </tr>
      <tr>
        <td width="13" height="140" rowspan="5" background="imagenes/v_back_izq.gif"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
        <td valign="top" class="tituloDOSE_2"></td>
        <td  class="tituloDOSE_2"><span class="negrita">&nbsp;&nbsp;SUB TOTAL: </span><?php echo number_format($row_factura['sub_total'],"2",",","."); ?></td>
        <td width="14" rowspan="5" background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <tr>
        <td width="487" rowspan="4" valign="top" class="tituloDOSE_2"><table border="0" cellpadding="0" cellspacing="0" width="460">
          <tbody>
            <tr>
              <td width="13" align="left"  ></td>
              <td width="447"  height="1" class="tituloDOSE_2"><table border="0" cellpadding="0" cellspacing="0">
                <tbody>
                  <tr>
                    <td align="right"></td>
                  </tr>
                  <tr>
                    <td align="center" width="446"><table width="447" align="left" cellpadding="3" cellspacing="0">
                      <tbody>
                        <tr>
                          <td width="61"align="right" valign="baseline">Nombre:</td>
                          <td width="168" valign="baseline"><input type="text" name="nombres" id="nombres" value="<?php echo $row_clientes['nombres']; ?>"  size="32" <?=$disable?>/></td>
                          <td width="46" align="right" valign="baseline" nowrap="nowrap">Cedula:</td>
                          <td width="146" valign="baseline"><input name="cedula2" type="text" id="cedula2" value="<?php echo $row_clientes['cedula']; ?>"  size="32" maxlength="8" <?=$disable?>/>
                            <input name="cedula" type="hidden" id="cedula" value="<?php echo $row_clientes['cedula']; ?>"  size="32" maxlength="8" /></td>
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
                            <textarea name="direccion" cols="80" rows="4" <?=$disable?>><?php echo $row_clientes['direccion']; ?></textarea>
                          </label></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                  <tr>
                    <td align="right" width="446"></td>
                  </tr>
                </tbody>
              </table></td>
              <td width="1"></td>
            </tr>
          </tbody>
        </table></td>
        <td class="tituloDOSE_2" valign="top" width="262"></td>
      </tr>
      <tr>
        <td class="tituloDOSE_2" ><span class="negrita">&nbsp;&nbsp;IVA: </span><?php echo number_format($row_factura['iva'],"2",",","."); ?></td>
      </tr>
      <tr>
        <td class="tituloDOSE_2" ><span class="negrita">&nbsp;&nbsp;TOTAL A PAGAR: </span><?php echo number_format($row_factura['total'],"2",",","."); ?></td>
      </tr>
      <tr>
        <td class="tituloDOSE_2" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" height="12" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
        <td height="12" colspan="2" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
        <td align="right" height="12" width="14"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" width="775">
    <tbody>
      <tr>
        <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
        <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita">Productos a Cancelar</span></td>
        <td align="right" height="1" width="12"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
      </tr>
      <tr>
        <td width="13" height="140" rowspan="2" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
        <td valign="baseline" align="right"  class="gallery clearfix"><a id="aItem" href="listado_productos_ventas.php?filtrar=default&amp;ventana=requerimiento_detalles_insertar&amp;iframe=true&amp;width=950&amp;height=525" rel="prettyPhoto[iframe3]" style="display:none;"></a></td>
        <td width="12" rowspan="2" background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <tr >
        <td valign="top"><table width="700" align="center" class="tblLista">
          <thead>
            <tr >
              <th width="80" scope="col">Codigo</th>
              <th width="77" scope="col">Tipo</th>
              <th width="212" scope="col">Nombre</th>
              <th width="40" scope="col">Cantidad</th>
              <th width="86" scope="col">Precio Bs.F</th>
              <th width="86" scope="col">Total</th>
            </tr>
          </thead>
          <?php 
		  $i=1;
		  do { 
		
		
		
mysql_select_db($database_conexion, $conexion);
$query_productos = "SELECT * FROM productos where id_productos='$row_venta[id_producto]'";
$productos = mysql_query($query_productos, $conexion) or die(mysql_error());
$row_productos = mysql_fetch_assoc($productos);
$totalRows_productos = mysql_num_rows($productos);


$total=$row_venta['precio'] * $row_venta['cantidad'];
		?>  
          <tr id="lista_detalles">
            <td width="80" scope="col"><?php echo $row_productos['codigo']; ?>
              <input type="hidden" name="codigo<?=$i?>" value="<?=$row_productos['codigo']?>" />
              <input type="hidden" name="id_prod<?=$i?>" value="<?=$row_productos['id_productos']?>" /></td>
            <td width="77" scope="col"><?php echo $row_productos['tipo']; ?></td>
            <td width="212" scope="col"><?php echo $row_productos['nombre']; ?></td>
            <td width="40" scope="col"><?php echo $row_venta['cantidad']; ?>
              <input type="hidden" name="cantidad<?=$i?>" value="<?=$row_venta['cantidad'];?>" /></td>
            <td width="86" scope="col"><?php echo number_format($row_venta['precio'],"2",",",".");?>
              <input type="hidden" name="precio<?=$i?>" id="precio<?=$i?>" value="<?=$row_venta['precio']?>" /></td>
            <td width="86" scope="col"><?php echo number_format($total,"2",",","."); ?></td>
        </tr>
          <?php 
		  $i++;
		   } while ($row_venta = mysql_fetch_assoc($venta)); 
		   ?>
        </table></td>
      </tr>
      <tr>
        <td height="19" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
        <td align="center" valign="top"><input type="submit" name="button2" id="button2" value="CONFIRMAR DEVOLUCION" /></td>
        <td background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <tr>
        <td height="19" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
        <td align="right" valign="top"></td>
        <td background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" height="12" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
        <td background="imagenes/v_back_inf.gif" height="12" width="750"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
        <td align="right" height="12" width="12"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
      </tr>
    </tbody>
  </table>
  <p>
    <input type="hidden" name="factura_venta" value="<?=$_POST['numero']?>" />
    <input type="hidden" name="serie_venta" value="<?=$_POST['serie']?>" />
    <input type="hidden" name="cant_prod" value="<?=$i-1?>" />

  </p>
</form>
</body>
</html>
<?php
mysql_free_result($productos);

mysql_free_result($clientes);

mysql_free_result($factura);

mysql_free_result($venta);
?>
