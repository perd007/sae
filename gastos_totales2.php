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


//calculamos los gastos totales de las compras

//seleccionamos las compras entre el rango de fechas
mysql_select_db($database_conexion, $conexion);
$query_compras = "SELECT * FROM compras where fecha>='$_POST[fecha1] 00:00:00' and fecha<='$_POST[fecha2] 23:59:59' and estatus!='PENDIENTE'";
$compras = mysql_query($query_compras, $conexion) or die(mysql_error());
$row_compras = mysql_fetch_assoc($compras);
$totalRows_compras = mysql_num_rows($compras);


$total_insumo=0;
$total_producto=0;		

//ahora buscamos los productos asociados a cada factura
do{
	mysql_select_db($database_conexion, $conexion);
	$query_compras_productos = "SELECT * FROM compra_productos where factura='$row_compras[num_fac]'";
	$compras_productos = mysql_query($query_compras_productos, $conexion) or die(mysql_error());
	$row_compras_productos = mysql_fetch_assoc($compras_productos);
	$totalRows_compras_productos = mysql_num_rows($compras_productos);
		//dependiendo del tipo de producto vamos sumando su precio a las distinatas variables
		do{
			mysql_select_db($database_conexion, $conexion);
			$query_productos = "SELECT * FROM productos where id_productos='$row_compras_productos[producto]'";
			$productos = mysql_query($query_productos, $conexion) or die(mysql_error());
			$row_productos = mysql_fetch_assoc($productos);
			$totalRows_productos = mysql_num_rows($productos);
	
			if($row_productos["tipo"]=="insumo"){    $total_insumo+=($row_compras_productos["precio"] * $row_compras_productos["cantidad"]); }
			if($row_productos["tipo"]=="producto"){  $total_producto+=($row_compras_productos["precio"] * $row_compras_productos["cantidad"]); }
			
		} while ($row_compras_productos = mysql_fetch_assoc($compras_productos));
		//
		
		
} while ($row_compras = mysql_fetch_assoc($compras));
//



//calculamos el iva y los sumano a los calculos
$total_insumo_iva=$total_insumo + (($total_insumo*12)/100);
$total_producto_iva=$total_producto + (($total_producto*12)/100);


mysql_select_db($database_conexion, $conexion);
$query_productos = "SELECT * FROM productos";
$productos = mysql_query($query_productos, $conexion) or die(mysql_error());
$row_productos = mysql_fetch_assoc($productos);
$totalRows_productos = mysql_num_rows($productos);


// calcularemos los totales de otros gatos
//principal
mysql_select_db($database_conexion, $conexion);
$query_gastos = "SELECT * FROM gastos";
$gastos = mysql_query($query_gastos, $conexion) or die(mysql_error());
$row_gastos = mysql_fetch_assoc($gastos);
$totalRows_gastos = mysql_num_rows($gastos);


//obtenemos el total de los gastos empresariales periodicos
mysql_select_db($database_conexion, $conexion);
$query_gastos1 = "SELECT sum(monto) FROM gastos where clasificacion='empresarial' and tipo='periodico' and fecha>='$_POST[fecha1] 00:00:00' and fecha<='$_POST[fecha2] 23:59:59' ";
$gastos1 = mysql_query($query_gastos1, $conexion) or die(mysql_error());
$row_gastos1 = mysql_fetch_assoc($gastos1);
$totalRows_gastos1 = mysql_num_rows($gastos1);

//obtenemos el total de los gastos empresariales eventuales
mysql_select_db($database_conexion, $conexion);
$query_gastos2 = "SELECT sum(monto) FROM gastos where clasificacion='empresarial' and tipo='eventual' and fecha>='$_POST[fecha1] 00:00:00' and fecha<='$_POST[fecha2] 23:59:59'";
$gastos2 = mysql_query($query_gastos2, $conexion) or die(mysql_error());
$row_gastos2 = mysql_fetch_assoc($gastos2);
$totalRows_gastos2 = mysql_num_rows($gastos2);

//obtenemos el total de los gastos personales periodicos
mysql_select_db($database_conexion, $conexion);
$query_gastos3 = "SELECT sum(monto) FROM gastos where clasificacion='personal' and tipo='periodico' and fecha>='$_POST[fecha1] 00:00:00' and fecha<='$_POST[fecha2] 23:59:59'";
$gastos3 = mysql_query($query_gastos3, $conexion) or die(mysql_error());
$row_gastos3 = mysql_fetch_assoc($gastos3);
$totalRows_gastos3 = mysql_num_rows($gastos3);

//obtenemos el total de los gastospersonales eventuales
mysql_select_db($database_conexion, $conexion);
$query_gastos4 = "SELECT sum(monto) FROM gastos where clasificacion='personal' and tipo='eventual' and fecha>='$_POST[fecha1] 00:00:00' and fecha<='$_POST[fecha2] 23:59:59'";
$gastos4 = mysql_query($query_gastos4, $conexion) or die(mysql_error());
$row_gastos4 = mysql_fetch_assoc($gastos4);
$totalRows_gastos4 = mysql_num_rows($gastos4);


//CALCULAMOS LOS INGRESOS DIARIOS SEGUN PERIODO A CONSULTAR

mysql_select_db($database_conexion, $conexion);
$query_ingreso = "SELECT sum(monto) FROM ingresos_diarios where fecha>='$_POST[fecha1] 00:00:00' and fecha<='$_POST[fecha2] 23:59:59'";
$ingreso = mysql_query($query_ingreso, $conexion) or die(mysql_error());
$row_ingreso = mysql_fetch_assoc($ingreso);
$totalRows_ingreso = mysql_num_rows($ingreso);


//calculamos loas deudas a proveedores/////////////////////////////////////////////////////////////////////////////////////////////
mysql_select_db($database_conexion, $conexion);
$query_deudas = "SELECT * FROM compras where estatus='PENDIENTE'";
$deudas = mysql_query($query_deudas, $conexion) or die(mysql_error());
$row_deudas = mysql_fetch_assoc($deudas);
$totalRows_deudas = mysql_num_rows($deudas);

	

//ahora buscamos los productos asociados a cada factura
do{
	mysql_select_db($database_conexion, $conexion);
	$query_compras_deudas = "SELECT * FROM compra_productos where factura='$row_deudas[num_fac]'";
	$compras_deudas = mysql_query($query_compras_deudas, $conexion) or die(mysql_error());
	$row_compras_deudas = mysql_fetch_assoc($compras_deudas);
	$totalRows_compras_deudas = mysql_num_rows($compras_deudas);
		//dependiendo del tipo de producto vamos sumando su precio a las distinatas variables
		do{
			
			mysql_select_db($database_conexion, $conexion);
			$query_productos_deudas = "SELECT * FROM productos where id_productos='$row_compras_deudas[producto]'";
			$productos_deudas = mysql_query($query_productos_deudas, $conexion) or die(mysql_error());
			$row_productos_deudas = mysql_fetch_assoc($productos_deudas);
			$totalRows_productos_deudas = mysql_num_rows($productos_deudas);
	
			if($row_productos_deudas ["tipo"]=="insumo"){    $total_insumo_deudas+=($row_compras_deudas["precio"] * $row_compras_deudas["cantidad"]); }
			if($row_productos_deudas ["tipo"]=="producto"){  $total_producto_deudas+=($row_compras_deudas["precio"] * $row_compras_deudas["cantidad"]); }
			
		} while ($row_compras_deudas = mysql_fetch_assoc($compras_deudas));
		//
		
		
} while ($row_deudas = mysql_fetch_assoc($deudas));
//
//calculamos el iva y los sumano a los calculos
$total_insumo_deuda_iva= $total_insumo_deudas + (( $total_insumo_deudas*12)/100);
$total_producto_deuda_iva=$total_producto_deudas + (($total_producto_deudas*12)/100);



//calculamos el iva y los sumano a los calculos
$total_insumo_iva=$total_insumo + (($total_insumo*12)/100);
$total_producto_iva=$total_producto + (($total_producto*12)/100);


?>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
.letrasgrandes {
	font-size: 16px;
	color: #000000;
	font-weight: bold;
}
.letrasgrandes1 {
	font-size: 16px;
	color: #FF9933;
	font-weight: bold;
}
.letrasgrandes2 {
	font-size: 16px;
	color: #33CC99;
	font-weight: bold;
}
.letrasgrandes3 {
	font-size: 16px;
	color: #FF3300;
	font-weight: bold;
}
.letrasgrandes4 {
	font-size: 16px;
	color: #CC33FF;
	font-weight: bold;
}
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
}
.Estilo2 {color: #FFFFFF}
</style>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" width="668">
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita"><strong>Estadistica de Gastos Totales</strong></span></td>
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
            <td align="center" width="642"><table bordercolor="#0033FF" width="643" border="1"  cellpadding="3" cellspacing="0">
              <tbody>
                <tr class="letrasgrandes">
                  <td colspan="3" align="center" valign="baseline" nowrap="nowrap" bgcolor="#82D1FD">PERIODO: <?php echo $_POST["fecha1"]." - ".$_POST["fecha2"];  ?> </td>
                </tr>
                <tr>
                  <td colspan="3" align="center" valign="baseline" nowrap="nowrap" bgcolor="#FF9933"><strong><em>GASTOS DE COMPRAS</em></strong></td>
                  </tr>
                <tr>
                  <td width="253" align="center" valign="baseline" nowrap="nowrap" bgcolor="#FFCC00"><strong>Compras de Mercancia</strong></td>
                  <td width="299" align="center" valign="baseline" bgcolor="#FFCC00"><strong>Compras de Insumos</strong></td>
                  <td width="117" valign="baseline" bgcolor="#FFCC00"><strong>TOTAL:</strong></td>
                  </tr>
                <tr class="letrasgrandes1">
                  <td align="center" valign="baseline"  nowrap="nowrap"><?php echo (number_format($total_producto_iva,"2",",",".")); ?></td>
                  <td align="center" valign="baseline"><?php echo (number_format($total_insumo_iva,"2",",",".")); ?></td>
                  <td valign="baseline"><?php echo (number_format($total_producto_iva+$total_insumo_iva,"2",",",".")); ?></td>
                </tr>
                <tr>
                  <td colspan="3" align="center" valign="baseline" nowrap="nowrap" bgcolor="#33CC99"><strong><em>DEUDAS  DE COMPRAS</em></strong></td>
                </tr>
                <tr >
                  <td align="center" valign="baseline" nowrap="nowrap" bgcolor="#33FF99"><strong>Deudas de Mercancia</strong></td>
                  <td align="center" valign="baseline" bgcolor="#33FF99"><strong>Deudas de Insumos</strong></td>
                  <td valign="baseline" bgcolor="#33FF99"><strong>TOTAL:</strong></td>
                </tr>
                <tr class="letrasgrandes2">
                  <td align="center" valign="baseline" nowrap="nowrap" bgcolor="#FFFFFF"><strong><?php echo (number_format($total_producto_deuda_iva,"2",",",".")); ?></strong></td>
                  <td align="center" valign="baseline" nowrap="nowrap" bgcolor="#FFFFFF"><strong><?php echo (number_format($total_insumo_deuda_iva,"2",",",".")); ?></strong></td>
                  <td align="left" valign="baseline" nowrap="nowrap" bgcolor="#FFFFFF"><strong><?php echo (number_format($total_producto_deuda_iva+$total_insumo_deuda_iva,"2",",",".")); ?></strong></td>
                </tr>
                <tr>
                  <td colspan="3" align="center" valign="baseline" nowrap="nowrap" bgcolor="#FF3300"><strong><em>OTROS GASTOS DE LA EMPRESA</em></strong></td>
                  </tr>
                <tr>
                  <td align="center" valign="baseline" nowrap="nowrap" bgcolor="#FF9966"><strong>Gastos Eventuales </strong></td>
                  <td align="center" bgcolor="#FF9966"><strong>Gastos Periodicos</strong></td>
                  <td align="left" bgcolor="#FF9966"><strong>TOTAL:</strong></td>
                </tr>
                <tr class="letrasgrandes3">
                  <td align="center" valign="middle" nowrap="nowrap"><?php echo (number_format($row_gastos2['sum(monto)'],"2",",",".")); ?></td>
                  <td align="center" valign="baseline"><?php echo (number_format($row_gastos1['sum(monto)'],"2",",",".")); ?></td>
                  <td valign="baseline"><?php echo (number_format($row_gastos1['sum(monto)']+$row_gastos2['sum(monto)'],"2",",",".")); ?></td>
                </tr>
                <tr>
                  <td colspan="3" align="center" valign="middle" nowrap="nowrap" bgcolor="#CC33FF"> <strong><em>GASTOS PERSONALES</em></strong></td>
                </tr>
                <tr>
                  <td align="center" valign="baseline" nowrap="nowrap" bgcolor="#CC99FF"><strong>Gastos Eventuales</strong></td>
                  <td align="center" bgcolor="#CC99FF"><strong>Gastos Periodicos</strong></td>
                  <td align="left" bgcolor="#CC99FF"><strong>TOTAL:</strong></td>
                </tr>
                <tr class="letrasgrandes4">
                  <td align="center" valign="middle" nowrap="nowrap" ><?php echo (number_format($row_gastos4['sum(monto)'],"2",",",".")); ?></td>
                  <td align="center" valign="baseline" ><?php echo (number_format($row_gastos3['sum(monto)'],"2",",",".")); ?></td>
                  <td valign="baseline" ><?php echo (number_format($row_gastos3['sum(monto)']+$row_gastos4['sum(monto)'],"2",",",".")); ?></td>
                </tr>
                <tr>
                  <td colspan="2" align="right"><strong>SUMATORIA DE GASTOS TOTALES(sin incluir deudas):</strong></td>
                  <td align="center" class="letrasgrandes"><?php echo (number_format($gastos=$row_gastos1['sum(monto)']+$row_gastos2['sum(monto)']+$row_gastos3['sum(monto)']+$row_gastos4['sum(monto)']+$total_producto_iva+$total_insumo_iva,"2",",",".")); ?></td>
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
</table>
<table border="0" cellpadding="0" cellspacing="0" width="668">
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita"><strong>Balance de Ingresos y Gastos Totales</strong></span></td>
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
            <td align="center" width="642"><table bordercolor="#0033FF" width="643" border="1"  cellpadding="3" cellspacing="0">
              <tbody>
                <tr class="letrasgrandes">
                  <td colspan="2" align="center" valign="baseline" nowrap="nowrap" bgcolor="#82D1FD">PERIODO: <?php echo $_POST["fecha1"]." - ".$_POST["fecha2"];  ?></td>
                </tr>
                <tr>
                  <td width="552" align="right" bgcolor="#3399FF"><span class="Estilo1"><em>INGRESOS  SEGUN PERIODO</em>:</span></td>
                  <td width="117" align="center" bgcolor="#3399FF" class="letrasgrandes"><span class="Estilo2"><?php echo (number_format($row_ingreso['sum(monto)'],"2",",",".")); ?></span></td>
                </tr>
                <tr>
                  <td align="right"><strong><em>RESULTADO: INGRESOS - GASTOS:</em></strong></td>
                  <td align="center" class="letrasgrandes"><?php echo (number_format($row_ingreso['sum(monto)']-$gastos,"2",",",".")); ?></td>
                </tr>
                <tr>
                  <td align="right" bgcolor="#FF0033"><span class="Estilo2"><strong><em>DEUDAS DE MERCANCIA:</em></strong></span></td>
                  <td align="center" bgcolor="#FF0033" class="letrasgrandes"><span class="Estilo2"><strong><?php echo (number_format($total_producto_deuda_iva+$total_insumo_deuda_iva,"2",",",".")); ?></strong></span></td>
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
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($compras);

mysql_free_result($productos);

mysql_free_result($compras);

mysql_free_result($compras_productos);

mysql_free_result($gastos);

mysql_free_result($ingreso);

mysql_free_result($deudas);
?>
