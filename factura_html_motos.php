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
.letra10 {
	font-size: 15px;
}
.letra15 {
	font-size: 20px;
}
.letra18 {
	font-size: 18px;
}
.negrita {	font-weight: bold;
}
</style>
</head>
<script>
function imprimir(){
window.print();  

}
</script>

<body class="letra12" <? if($_GET["ver"]==1){ 
	  echo "onload='imprimir();'";	
	  } ?>>
<form id="form1" name="form1" method="get" action="factura_html_motos.php">

<table width="995" border="0" align="center">
  <tr>
    <td colspan="2"><img src="imagenes/logoHeader2.jpg" width="409" height="43" /></td>
  </tr>
  <tr>
    <td width="603"><strong>Moto Empire Puerto Ayacucho C.A  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
    <td width="382" colspan="-2"><strong>Rif: J-31598828-3</strong></td>
  </tr>
  <tr>
    <td height="26"><strong>FACTURA:</strong> <? echo $_GET["factura"]?>&nbsp;</td>
    <td colspan="-2"><strong>SERIE:</strong> <? echo $_GET["serie"]?></td>
  </tr>
  <tr>
    <td><strong>NOMBRE O RAZON SOCIAL: </strong><? echo $_GET["nombres"]?>&nbsp;</td>
    <td colspan="-2"><strong>CEDULA O RIF:</strong> <? echo $_GET["cliente"]?></td>
  </tr>
  <tr>
    <td><strong>FECHA:</strong> <? echo $_GET["fecha"]?>&nbsp;&nbsp;&nbsp;</td>
    <td colspan="-2"><strong>TELEFONO: </strong><? echo $_GET["telefono"]?></td>
  </tr>
  <tr>
    <td colspan="2"><strong>DIRECCION: </strong><? echo $_GET["direccion"]?></td>
  </tr>
  <tr>
    <td colspan="2">
     <table border="1" cellpadding="0" cellspacing="0">
      <tr>
        <td width="983" align="center"></td>
      </tr>
    </table>
    </td>
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
	$precioNeto = ($fila['precio'] * 12)/100;
	$precioNeto = $fila['precio'] - $precioNeto;
	$total=round($precioNeto, 2)*$fila['cantidad'];
	
		
?>
  <?php 
  }//fin del for
?>

  <tr align="center" valign="top">
    <td height="219"  colspan="2" align="right"><table border="0" align="left">
      <tr>
        <th width="93" align="left" scope="col">Marca</th>
        <th width="100" align="center" scope="col">Modelo</th>
        <th width="139" align="center" scope="col">Nombre</th>
        <th width="89" align="center" scope="col">Chasis</th>
        <th width="95" align="center" scope="col">Año</th>
        <th width="76" align="center" scope="col">Motor</th>
        <th width="76" align="center" scope="col">Placa</th>
        <th width="94" align="center" scope="col">Color</th>
        <th width="106" align="center" scope="col">Precio </th>
        <th width="79" align="center" scope="col">&nbsp;</th>
        </tr>
      <tr align="left">
        <td colspan="10">
        <table border="1" cellpadding="0" cellspacing="0">
      <tr>
        <td width="983" align="center"></td>
      </tr>
    </table>
        </td>
        </tr>
      <tr class="letra18">
        <td align="left"><?=$fila2["marca"]?></td>
        <td align="center"><?=$fila2["modelo"]?></td>
        <td align="center"><?=$fila2["nombre"]?></td>
        <td align="center" width="89">&nbsp;&nbsp;&nbsp;<?=$_GET["chasis"]?></td>
        <td align="center">&nbsp;&nbsp;<?=$fila2["ano"]?></td>
        <td align="center"width="76">&nbsp;&nbsp;&nbsp;<?=$_GET["motor"]?>&nbsp;&nbsp;</td>
        <td align="center"><?=$_GET["placa"]?></td>
        <td align="center"><?=$_GET["color"]?></td>
        <td align="center"><?=number_format($total,"2",",",".")?></td>
        <td align="center">&nbsp;</td>
        </tr>
    </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
  </tr>
  <tr align="center" valign="bottom">
    <td colspan="2" align="left" valign="bottom">
    <table width="983" border="1" cellpadding="0" cellspacing="0">
      <tr>
        <td width="983" align="left"></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr align="center">
    <td colspan="2" align="right"><strong>Sub-Total: 
      <?php echo number_format($_GET["sub"],"2",",","."); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
  </tr>
  <tr align="center">
    <td colspan="2" align="right"><strong>IVA: 
      <?php echo number_format($_GET["iva"],"2",",","."); ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
  </tr>
  <tr align="center">
    <td colspan="2" align="right"><strong>Total: 
      <?php echo number_format($_GET["total"],"2",",","."); ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
  </tr>
  <tr align="left">
    <td colspan="2" >
     <table width="983" border="1" cellpadding="0" cellspacing="0">
      <tr>
        <td width="983" align="left"></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr align="center">
    <td colspan="2" align="center" class="letra10"><strong>Av 23 de Enero al Lado de la Bomba de Gasolina, Municipio Atures, Puerto Ayacucho Estado Amazonas Telefono: 0248-5216208. <span class="negrita">Sistema Desarrollado por Innovaciones Informatica J.P C.A- Ing. Jose Carlos Perdomo. 0416-1423360</span>&nbsp;&nbsp;&nbsp;</strong></td>
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
      <input type="hidden" name="total" value="<?=$_GET["total"]?>"  >
       <input type="hidden" name="color" value="<?=$_GET["color"]?>"  >
      <input type="hidden" name="serial" value="<?=$_GET["serial"]?>"  >
      <input type="hidden" name="chasis" value="<?=$_GET["chasis"]?>"  >
      <input type="hidden" name="motor" value="<?=$_GET["motor"]?>"  >
      <input type="hidden" name="placa" value="<?=$_GET["placa"]?>"  >
      
      </td>
  </tr>
</table>


</form>
</body>
</html>