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



mysql_select_db($database_conexion, $conexion);
$query_clientes = "SELECT * FROM clientes where cedula='$_GET[cedula]'";
$clientes = mysql_query($query_clientes, $conexion) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<title>Documento sin título</title>

<style type="text/css">
.letra15 {	font-size: 20px;
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
<form id="form1" name="form1" method="get" action="recibo-apartado.php">

<table width="974" border="0" align="left" class="letra15">
  <tr>
    <td colspan="9"><img src="imagenes/logoHeader2.jpg" width="409" height="43" /></td>
  </tr>
  <tr>
    <td width="542" ><strong>Moto Empire Puerto Ayacucho C.A  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
    <td width="42">&nbsp;</td>
    <td width="376" colspan="2"><strong>Rif: J-31598828-3</strong></td>
  </tr>
  <tr>
    <td ><strong>NOMBRE O RAZON SOCIAL: </strong>&nbsp;<?php echo $row_clientes['nombres']; ?></td>
    <td>&nbsp;</td>
    <td colspan="2"><strong>CEDULA O RIF: </strong><?php echo $row_clientes['cedula']; ?></td>
  </tr>
  <tr>
    <td ><strong>FECHA:</strong> <? echo date("d-m-Y");?>&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2"><strong>TELEFONO: </strong><?php echo $row_clientes['telefono']; ?></td>
  </tr>
  <tr>
    <td colspan="9"><strong>DIRECCION:</strong> <?php echo $row_clientes['direccion']; ?></td>
  </tr>
 
  <tr align="center">
    <td height="127" colspan="9" align="right"><table border="0" align="left">
      <tr align="center">
        <th width="179" scope="col">Nombre</th>
        <th width="97" scope="col">Marca</th>
        <th width="119" scope="col">Modelo</th>
        <th width="75" scope="col">Cantidad</th>
        <th width="110" scope="col">Precio Bs.F</th>
        <th width="110" scope="col">Abono Bs.F</th>
        <th width="110" scope="col">Restan Bs.F</th>
        <th width="134" scope="col">&nbsp;</th>
        </tr>
      <tr align="left">
        <td colspan="8"><table border="1" cellpadding="0" cellspacing="0">
          <tr>
            <td width="983" align="center"></td>
            </tr>
          </table></td>
        </tr>
      <tr align="center">
      <br>
        <td><?=$_GET["nombre"]?></td>
        <td><?=$_GET["marca"]?></td>
        <td><?=$_GET["modelo"]?></td>
        <td><?=$_GET["cantidad"]?></td>
        <td><?=number_format($_GET["precio"],"2",",",".")?></td>
        <td><?=number_format($_GET["abono"],"2",",",".")?></td>
        <td><?=number_format($_GET["precio"]-$_GET["abono"],"2",",",".")?></td>
        <td>&nbsp;</td>
        </tr>
    </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
  </tr>
  <tr align="center">
    <td colspan="9" align="center"><strong>&nbsp;&nbsp;Av 23 de Enero al Lado de la Bomba de Gasolina, Municipio Atures, Puerto Ayacucho Estado Amazonas&nbsp;&nbsp;</strong></td>
  </tr>
  <tr align="center">
    <td height="26" colspan="9" align="left"><table width="968" height="10" border="0" align="left">
      <tr align="left">
        <td width="772" height="6" colspan="7"><table border="1" cellpadding="0" cellspacing="0">
          <tr>
            <td width="983" align="center"></td>
            </tr>
          </table></td>
      </tr>
      </table></td>
  </tr>
  <tr align="center">
    <td colspan="9" align="center"> 
      
      
      <input type="hidden" name="ver" value="1"  >
      
      <input type="submit" onclick="imprimir()" name="imprimir" value="Imprimir" <? if($_GET["ver"]==1){ 
	  echo "style='visibility:hidden'";}?>
	 /> 
      
      
      
      <input type="hidden" name="abono" value="<?=$_GET["abono"]?>"  >
      <input type="hidden" name="modelo" value="<?=$_GET["modelo"]?>"  >
      <input type="hidden" name="cantidad" value="<?=$_GET["cantidad"]?>"  >
      <input type="hidden" name="marca" value="<?=$_GET["marca"]?>"  >
      <input type="hidden" name="cedula" value="<?=$_GET["cedula"]?>"  >
      <input type="hidden" name="nombre" value="<?=$_GET["nombre"]?>"  >
      <input type="hidden" name="precio" value="<?=$_GET["precio"]?>"  >
      
      </td>
  </tr>
</table>


</form>
</body>
</html>
<?php
mysql_free_result($clientes);
?>
