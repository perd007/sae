<?php require_once('Connections/conexion.php'); ?>
<?
/*
// Realizamos nuestra consulta
	mysql_select_db($database_conexion, $conexion);
	$strConsulta = "SELECT * FROM ventas_productos where factura='$_GET[factura]' and serie='$_GET[serie]'";
	
	// ejecutamos la consulta
	$productos = mysql_query($strConsulta, $conexion) or die(mysql_error());
		
	// listamos la tabla de electore 
	$numfilas = mysql_num_rows($productos);
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<title>Documento sin título</title>

<style type="text/css">
.letra12 {
	font-size: 20px;
}
.letra12a {
	font-size: 16px;
	font-weight: bold;
}
.negrita {
	font-weight: bold;
}
</style>
</head>
<script>
function imprimir(){
window.print();  

}
</script>

<body <? if($_GET["ver"]==1){ 
	  echo "onload='imprimir();'";	
	  } ?>>
<form id="form1" name="form1" method="get" action="factura_html.php">

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="915" border="0" align="center" class="letra12">
  
  <tr>
    <td width="564" class="letra12">&nbsp;</td>
    <td width="272" colspan="-2" class="negrita">&nbsp;</td>
  </tr>
  <tr>
    <td><span class="negrita">COD INTERNO:</span> <? echo $_GET["factura"]?>&nbsp;- <? echo $_GET["serie"]?></td>
    <td colspan="-2">&nbsp;</td>
  </tr>
  <tr>
    <td><span class="negrita">NOMBRE O RAZON SOCIAL:</span> <? echo $_GET["nombres"]?>&nbsp;</td>
    <td colspan="-2"><span class="negrita">CEDULA O RIF:</span> <? echo $_GET["cliente"]?></td>
  </tr>
  <tr>
    <td><span class="negrita">FECHA:</span> <? echo $_GET["fecha"]?>&nbsp;&nbsp;&nbsp;</td>
    <td colspan="-2"><span class="negrita">TELEFONO:</span> <? echo $_GET["telefono"]?></td>
  </tr>
  <tr>
    <td colspan="2"><span class="negrita">DIRECCION:</span> <? echo $_GET["direccion"]?></td>
  </tr>
  <tr>
    <td colspan="2">
    <table border="1" cellpadding="0" cellspacing="0">
      <tr>
        <td width="983" align="center"></td>
      </tr>
    </table></td>
  </tr>
  
  <? 
// Realizamos nuestra consulta
	mysql_select_db($database_conexion, $conexion);
	$strConsulta = "SELECT * FROM ventas_productos where factura='$_GET[factura]' and serie='$_GET[serie]'";
	
	// ejecutamos la consulta
	$productos = mysql_query($strConsulta, $conexion) or die(mysql_error());
		
	// listamos la tabla de electore 
	$numfilas = mysql_num_rows($productos);
for ($i=0; $i<$numfilas; $i++)   {       
	
	$fila = mysql_fetch_array($productos);                  
	
  	$strConsulta2 = "SELECT * FROM productos where id_productos='$fila[id_producto]'";
	$productos2 = mysql_query($strConsulta2, $conexion) or die(mysql_error());
	$fila2 = mysql_fetch_array($productos2);
	
	// los mostramos con la función Row
	//$precioNeto=$fila['precio']/1.12;
	$precioNeto1 = $fila['precio']/1.12;
	//$precioNeto = $fila['precio'] - $precioNeto1;
	$total=round($precioNeto1, 2)*$fila['cantidad'];
	
		
?>
  <?php 
  }//fin del for
?>

  <tr align="center">
    <td height="219" colspan="2" align="right" valign="top">
      <table width="902" border="0" align="left" >
        <tr align="center" class="letra12">
          <th align="center" scope="col">Nombre</th>
          <th width="134" scope="col">Cantidad</th>
          <th width="124" scope="col">Precio Bs.F</th>
          <th width="134" scope="col">Total</th>
        </tr>
        <tr align="center" class="letra12">
          <th colspan="4" scope="col">
          <table border="1" cellpadding="0" cellspacing="0">
      <tr>
        <td width="983" align="center"></td>
      </tr>
    </table>          </th>
          </tr>
		  
		  <? 
		  // Realizamos nuestra consulta
	mysql_select_db($database_conexion, $conexion);
	$strConsulta = "SELECT * FROM ventas_productos where factura='$_GET[factura]' and serie='$_GET[serie]'";
	
	// ejecutamos la consulta
	$productos = mysql_query($strConsulta, $conexion) or die(mysql_error());
		
	// listamos los productos comprados
	$numfilas = mysql_num_rows($productos);
for ($i=0; $i<$numfilas; $i++)   {       
	
	$fila = mysql_fetch_array($productos);                  
	
  	$strConsulta2 = "SELECT * FROM productos where id_productos='$fila[id_producto]'";
	$productos2 = mysql_query($strConsulta2, $conexion) or die(mysql_error());
	$fila2 = mysql_fetch_array($productos2);
	
	// los mostramos con la función Row
	$precioNeto=$fila['precio']/1.12;
	$total=round($precioNeto, 2)*$fila['cantidad'];

		
		  ?>
        <tr align="center" class="letra12">
          <td align="left"><?=$fila2["nombre"]?></td>
          <td><?=$fila['cantidad']?></td>
          <td><?=number_format($precioNeto,"2",",",".")?></td>
          <td><?=number_format($total,"2",",",".");?></td>
        </tr>
		
		<? }?>
        </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
  </tr>
  <tr align="center">
    <td colspan="2" align="right"><table border="1" cellpadding="0" cellspacing="0">
      <tr>
        <td width="983" align="center"></td>
      </tr>
    </table></td>
  </tr>
  <tr align="center">
    <td colspan="2" align="right"><strong>Sub-Total: 
      <?php echo number_format($_GET["sub"],"2",",","."); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
  </tr>
  <tr align="center">
    <td colspan="2" align="right"><strong>IVA 16%: 
      <?php echo number_format($_GET["iva"],"2",",","."); ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
  </tr>
  <tr align="center">
    <td colspan="2" align="right"><strong>Total: 
      <?php echo number_format($_GET["total"],"2",",","."); ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
  </tr>
  <tr align="center">
    <td colspan="2" align="right"><table border="1" cellpadding="0" cellspacing="0">
      <tr>
        <td width="983" align="center"></td>
      </tr>
    </table></td>
  </tr>
  <tr align="center">
    <td colspan="2" align="center"><span class="letra12a"> Sistema Desarrollado por Innovaciones Informatica J.P C.A- Ing. Jose Carlos Perdomo. </span></td>
  </tr>
  <tr align="center">
    <td colspan="2" align="center"> 
      
      
      <input type="hidden" name="ver" value="1"  >
      
      <input type="submit" onclick="imprimir()" name="imprimir" value="Imprimir" <? if($_GET["ver"]==1){ 
	  echo "style='visibility:hidden'";}?>
	 /> 
      
      
      
      <input type="hidden" name="factura" value="<?=$_GET["factura"]?>"  >
      <input type="hidden" name="serie" value="<?=$_GET["serie"]?>"  >
      <input type="hidden" name="fecha" value="<?=$_GET["fecha"]?>"  >
      <input type="hidden" name="cliente" value="<?=$_GET["cliente"]?>"  >
      <input type="hidden" name="tipo" value="<?=$_GET["tipo"]?>"  >
      <input type="hidden" name="direccion" value="<?=$_GET["direccion"]?>"  >
      <input type="hidden" name="telefono" value="<?=$_GET["telefono"]?>"  >
      <input type="hidden" name="nombres" value="<?=$_GET["nombres"]?>"  >
      <input type="hidden" name="sub" value="<?=$_GET["sub"]?>"  >
      <input type="hidden" name="iva" value="<?=$_GET["iva"]?>"  >
      <input type="hidden" name="total" value="<?=$_GET["total"]?>"  >      </td>
  </tr>
</table>


</form>
</body>
</html>