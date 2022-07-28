<?php require_once('Connections/conexion.php'); ?>
<?php 
//validar usuario
if($_COOKIE["val"]==true){
	if($row_permisos["d"]!=1){
	echo "<script type=\"text/javascript\">alert ('Usted no posee permisos para realizar Devoluciones'); location.href='fondo.php'";
	}
}
else{
echo "<script type=\"text/javascript\">alert ('Error usuario invalido'); location.href='fondo.php' </script>";
 exit;
}


?>
<?php



mysql_select_db($database_conexion, $conexion);
$query_pedido = "SELECT * FROM facturas where id_facturas='$_GET[id]'";
$pedido = mysql_query($query_pedido, $conexion) or die(mysql_error());
$row_pedido = mysql_fetch_assoc($pedido);
$totalRows_pedido = mysql_num_rows($pedido);


mysql_select_db($database_conexion, $conexion);
$query_productos_pedidos = "SELECT * FROM ventas_productos where factura='$row_pedido[numero]' and serie='$row_pedido[serie]'";
$productos_pedidos = mysql_query($query_productos_pedidos, $conexion) or die(mysql_error());
$row_productos_pedidos = mysql_fetch_assoc($productos_pedidos);
$totalRows_productos_pedidos = mysql_num_rows($productos_pedidos);


	
$disable="disabled='disabled'";


mysql_select_db($database_conexion, $conexion);
$query_clientes = "SELECT * FROM clientes where cedula='$row_pedido[id_cliente]'";
$clientes = mysql_query($query_clientes, $conexion) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

	
	


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

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.16.custom.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="css/estilo.css" charset="utf-8" />
<link type="text/css" rel="stylesheet" href="css/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="jscalendar-1.0/lang/calendar-es.js"></script>

<script type="text/javascript" src="js2/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="js2/fscript.js" charset="utf-8"></script>

<script type="text/javascript" src="js/jquery-1.7.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery.prettyPhoto.js" charset="utf-8"></script>
<script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="js/fscript.js" charset="utf-8"></script>
<script type="text/javascript" src="js/funciones.js" charset="utf-8"></script>
<script type="text/javascript" src="js/fscript.js" charset="utf-8"></script>
<script type="text/javascript" src="js/form.js" charset="utf-8"></script>
<style type="text/css"> 
    @import url("jscalendar-1.0/calendar-win2k-cold-1.css");
	
    </style>
<title>Documento sin título</title>
</head>

<script type="text/javascript">






</script>
<body>
<body>
<span class="gallery clearfix"></span>
   
<form id="form1"   name="form1" method="post" >
<input type="hidden" name="sel_detalles" id="sel_detalles" />
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
      <td valign="top" class="tituloDOSE_2">
        
        </td>
      <td  class="tituloDOSE_2"><span class="negrita">&nbsp;&nbsp;SUB TOTAL: </span><?php echo number_format($row_pedido['sub_total'],"2",",","."); ?></td>
      <td width="14" rowspan="5" background="imagenes/v_back_der.gif"><img src="imagenes/cargar_productos.php" alt="1" height="2" width="13" /></td>
    </tr>
    <tr>
      <td width="494" rowspan="4" valign="top" class="tituloDOSE_2">
      <table border="0" cellpadding="0" cellspacing="0" width="460">
    <tbody>
      <tr>
        <td width="13" align="left"  ></td>
        <td width="447"  height="1" class="tituloDOSE_2">
 
        <table border="0" cellpadding="0" cellspacing="0">
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
                    <td width="46" align="right" valign="baseline" nowrap="nowrap">Cedula o Rif:</td>
                    <td width="146" valign="baseline">
                    <input name="cedula2" type="text" id="cedula2" value="<?php echo $row_clientes['cedula']; ?>"  size="32" maxlength="8" <?=$disable?>/>
                      <input name="cedula" type="hidden" id="cedula" value="<?php echo $row_clientes['cedula']; ?>"  size="32" maxlength="8" />
                    </td>
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
  </table>
</td>
      <td class="tituloDOSE_2" valign="top" width="255">

      </td>
    </tr>
    <tr>
      <td class="tituloDOSE_2" ><span class="negrita">&nbsp;&nbsp;IVA: </span><?php echo number_format($row_pedido['iva'],"2",",","."); ?></td>
    </tr>
    <tr>
      <td class="tituloDOSE_2" ><span class="negrita">&nbsp;&nbsp;TOTAL PAGADO: </span><?php echo number_format($row_pedido['total'],"2",",","."); ?></td>
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
      <td valign="baseline" align="right"  class="gallery clearfix">
        <a id="aItem" href="listado_productos_ventas.php?filtrar=default&ventana=requerimiento_detalles_insertar&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]" style="display:none;"></a></td>
      <td width="12" rowspan="2" background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr >
      <td valign="top">
      <table width="700" align="center" class="tblLista">
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
		$query_productos = "SELECT * FROM productos where id_productos='$row_productos_pedidos[id_producto]'";
		$productos = mysql_query($query_productos, $conexion) or die(mysql_error());
		$row_productos = mysql_fetch_assoc($productos);
		$totalRows_productos = mysql_num_rows($productos);





		
		$total=$row_productos_pedidos['precio'] * $row_productos_pedidos['cantidad'];
		?>
          <tr id="lista_detalles">
            <td width="80" scope="col"><?php echo $row_productos['codigo']; ?>
            <input type="hidden" name="codigo<?=$i?>" value="<?=$row_productos['codigo']?>" />
            <input type="hidden" name="id_prod<?=$i?>" value="<?=$row_productos['id_productos']?>" />
            </td>
            <td width="77" scope="col"><?php echo $row_productos['tipo']; ?></td>
            <td width="212" scope="col"><?php echo $row_productos['nombre']; ?></td>
            <td width="40" align="center" scope="col"><?php echo $row_productos_pedidos['cantidad']; ?>
             <input type="hidden" name="cantidad<?=$i?>" value="<?=$row_productos_pedidos['cantidad']?>" />
            </td>
            <td width="86" scope="col"><?php echo number_format($row_productos_pedidos['precio'],"2",",","."); ?>  
            <input type="hidden" name="precio<?=$i?>" id="precio<?=$i?>" value="<?=$row_productos_pedidos['precio']?>" />
            </td>
            <td width="86" scope="col"><?php echo number_format($total,"2",",","."); ?>
            </td>
          </tr>
          <?php 
		  $i++;
		  }while($row_productos_pedidos = mysql_fetch_assoc($productos_pedidos));  ?>
      </table>
      </td>
    </tr>
    <tr>
      <td height="19" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td align="center" valign="top">&nbsp;</td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr>
      <td height="19" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td align="right" valign="top">
      </td>
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
  <input type="hidden" id="nro_detalles" value="<?=$nrodetalles?>" />
  <input type="hidden" name="can_detalles" id="can_detalles" value="<?=$nrodetalles?>" />
  <input type="hidden" name="bus" id="bus" value="0"  />
  <input type="hidden" name="bus2" id="bus2" value="0"  />
  <input type="hidden" name="existe" id="existe" value="<?=$existe?>" />
  <input type="hidden" name="val" id="val" value="<?=$valor?>"  />
   <input type="hidden" name="total" value="<?=$row_pedido['total']?>" />
    <input type="hidden" name="subtotal" value="<?=$row_pedido['subtotal']?>" />
     <input type="hidden" name="iva" value="<?=$row_pedido['iva']?>" />
     <input type="hidden" name="cant_prod" value="<?=$i-1?>" />
     <input type="hidden" name="pedido" value="<?php echo $row_pedido['id_pedidos']; ?>" />
</p>
   

</form>
<p>&nbsp;</p>

</body>
</html>
<?php
mysql_free_result($productos_pedidos);

mysql_free_result($productos);

mysql_free_result($facturas);

mysql_free_result($pedido);

mysql_free_result($clientes);
?>
