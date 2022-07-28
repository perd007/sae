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

//	FUNCION PARA GENERAR UN NUEVO CODIGO
	
	function getCodigo1($tabla, $campo, $digitos) {
	mysql_select_db($database_conexion, $conexion);
	$sql="select max($campo) FROM $tabla";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($query);
	$codigo=(int) ($field[0]+1);
	$codigo=(string) str_repeat("0", $digitos-strlen($codigo)).$codigo;
	return ($codigo);
}
	
function getCodigo($tabla, $campo,  $correlativo, $valor, $digitos) {
	mysql_select_db($database_conexion, $conexion);
	$sql="select max($campo) FROM $tabla WHERE $correlativo = '$valor'";
	$query=mysql_query($sql) or die ($sql.mysql_error());
	$field=mysql_fetch_array($query);
	$codigo=(int) ($field[0]+1);
	$codigo=(string) str_repeat("0", $digitos-strlen($codigo)).$codigo;
	return ($codigo);
}

////	genero codigo
	if($totalRows_facturas==0){
		$serie='000001';
		$numero = getCodigo("facturas", "numero", "serie", $serie, 6);
	}else
	{
		$serie=$row_facturas["max(serie)"];
		$numero = getCodigo("facturas", "numero", "serie", $serie, 6);
		if(strlen($numero)>=7){
			
			$serie= getCodigo1("facturas", "serie", 6);
			$numero = getCodigo1("facturas",0, 6);
		}
	}
///////			
	//verificamos si el pago fue combinado
	if($_POST["radio"]=="comb"){
		$efectivo=$_POST["efectivo"];
		$debito=$_POST["debito"];
		$cheque=$_POST["cheque"];
		$deposito=$_POST["deposito"];
	}elseif($_POST["radio"]=="efe"){
		$efectivo=-1;
		$debito="";
		$cheque="";
		$deposito="";
	}elseif($_POST["radio"]=="deb"){
		$efectivo="";
		$debito=-1;
		$cheque="";
		$deposito="";
	}elseif($_POST["radio"]=="che"){
		$efectivo="";
		$debito="";
		$cheque=-1;
		$deposito="";
	}elseif($_POST["radio"]=="dep"){
		$efectivo="";
		$debito="";
		$cheque="";
		$deposito=-1;
		if($_POST['vauche']!=""){
	$banco=$_POST['banco'];	
	}
	}
	
	/////////////////////////////////////////
	
	$insertSQL = sprintf("INSERT INTO facturas ( id_cliente, fecha, serie, numero, sub_total, iva, total, transaccion, efectivo, debito, cheque, retencion, deposito, vauche, banco) VALUES (%s, NOW(), %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['cedula'], "text"),
                       GetSQLValueString($serie, "text"),
                       GetSQLValueString($numero, "text"),
                       GetSQLValueString($_POST['subtotal'], "double"),
                       GetSQLValueString($_POST['iva'], "double"),
                       GetSQLValueString($_POST['total'], "double"),
					   GetSQLValueString("FACTURADO", "text"),
					   GetSQLValueString($efectivo, "double"),
                       GetSQLValueString($debito, "double"),
                       GetSQLValueString($cheque, "double"),
					   GetSQLValueString($_POST['retencionVal'], "double"),
					   GetSQLValueString($deposito, "double"),
                       GetSQLValueString($_POST['vauche'], "int"),
                       GetSQLValueString($banco, "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  
  
  $i=1;
  while($_POST["cant_prod"]>=$i){
  $insertSQL2 = sprintf("INSERT INTO ventas_productos (id_producto, factura, serie, cantidad, precio) VALUES (%s, %s, %s, %s, %s)",
                       
                       GetSQLValueString($_POST['id_prod'.$i], "int"),
                       GetSQLValueString($numero, "text"),
					   GetSQLValueString($serie, "text"),
                       GetSQLValueString($_POST['cantidad'.$i], "int"),
                       GetSQLValueString($_POST['precio'.$i], "double"));

  mysql_select_db($database_conexion, $conexion);
  $Result2 = mysql_query($insertSQL2, $conexion) or die(mysql_error());

  if($Result2==1){
	  //actualizamos el almacen
		$eliminar = sprintf("INSERT INTO almacen (id_producto, factura_venta, serie_venta, cantida, transaccion) VALUES (%s, %s, %s, %s, %s)",
                       
                       GetSQLValueString($_POST['id_prod'.$i], "int"),
                       GetSQLValueString($numero, "text"),
					   GetSQLValueString($serie, "text"),
                       GetSQLValueString($_POST['cantidad'.$i], "int"),
                       GetSQLValueString("VENTA", "text"));
		mysql_select_db($database_conexion, $conexion);
		$eliminar2 = mysql_query($eliminar, $conexion) or die(mysql_error());
		
	  	if($eliminar2==1){ $totaleliminar=+1; }else{  $totaleliminar= -1000000; }
	  ////////////////////////////////////////////////
	
	$totalResult=+1;
	  }
	  
  if($Result2==0){$totalResult= -1000000;}
  
  $i++;
  }//fin del while
  

//validamos si fue exitosa la carga para eliminar los pedidos e insertar venta en el almacen	
	if($Result1==1 and $totalResult>=1 and $totaleliminar>=1){
		mysql_select_db($database_conexion, $conexion);
		$sql="delete from apartados where id_apartado='$_POST[apartado]'";
		$verificar=mysql_query($sql,$conexion) or die(mysql_error());
		
		if($verificar==1){
			
			$fec=date("d-m-Y");
		mysql_select_db($database_conexion, $conexion);
		$query_clientes = "SELECT * FROM clientes where cedula='$_POST[cedula]'";
		$clientes = mysql_query($query_clientes, $conexion) or die(mysql_error());
		$row_clientes = mysql_fetch_array($clientes);
			
		 echo "<script type=\"text/javascript\">alert ('FACTURADO');  location.href='factura_pdf.php?factura=$numero&serie=$serie&fecha=$fec&cliente=$_POST[cedula]&tipo=$row_clientes[tipo]&direccion=$row_clientes[direccion]&telefono=$row_clientes[telefono]&nombres=$row_clientes[nombres]&sub=$_POST[subtotal]&iva=$_POST[iva]&total=$_POST[total]' </script>";	
		}
	}
//

}//fin del procesamiento del formulario


$apartado=$_GET["apartado"];
mysql_select_db($database_conexion, $conexion);
$query_pedido = "SELECT * FROM apartados where id_apartado='$apartado'";
$pedido = mysql_query($query_pedido, $conexion) or die(mysql_error());
$row_pedido = mysql_fetch_assoc($pedido);
$totalRows_pedido = mysql_num_rows($pedido);


mysql_select_db($database_conexion, $conexion);
$query_productos_pedidos = "SELECT * FROM productos_apartados where id_apartado='$row_pedido[correlativo]'";
$productos_pedidos = mysql_query($query_productos_pedidos, $conexion) or die(mysql_error());
$row_productos_pedidos = mysql_fetch_assoc($productos_pedidos);
$totalRows_productos_pedidos = mysql_num_rows($productos_pedidos);


	
$disable="disabled='disabled'";


mysql_select_db($database_conexion, $conexion);
$query_clientes = "SELECT * FROM clientes where cedula='$row_pedido[id_cliente]'";
$clientes = mysql_query($query_clientes, $conexion) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

	
	


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

function activarEfectivo(){
	document.getElementById('efectivo').value=document.form1.total.value;
	document.getElementById('debito').value="";
	document.getElementById('cheque').value="";	
	document.getElementById('vauche').value="";
	document.getElementById('deposito').value="";
	
	document.getElementById('efectivo').disabled=true;
	document.getElementById('debito').disabled=true;
	document.getElementById('cheque').disabled=true;
	document.getElementById('vauche').disabled=true;
	document.getElementById('banco').disabled=true;
	document.getElementById('deposito').disabled=true;
	
	document.getElementById('combinado').value=0;
	document.getElementById('depo').value=0;
	
}
function activarDebito(){
	document.getElementById('efectivo').value="";
	document.getElementById('debito').value=document.form1.total.value;
	document.getElementById('cheque').value="";	
	document.getElementById('vauche').value="";
	document.getElementById('deposito').value="";
	
	
	document.getElementById('efectivo').disabled=true;
	document.getElementById('debito').disabled=true;
	document.getElementById('cheque').disabled=true;
	document.getElementById('vauche').disabled=true;
	document.getElementById('banco').disabled=true;
	document.getElementById('deposito').disabled=true;
	
	document.getElementById('combinado').value=0;
	document.getElementById('depo').value=0;
}

function activarCheque(){
	document.getElementById('efectivo').value="";
	document.getElementById('debito').value="";
	document.getElementById('cheque').value=document.form1.total.value;	
	document.getElementById('vauche').value="";
	document.getElementById('deposito').value="";
	
	
	document.getElementById('efectivo').disabled=true;
	document.getElementById('debito').disabled=true;
	document.getElementById('cheque').disabled=true;
	document.getElementById('vauche').disabled=true;
	document.getElementById('banco').disabled=true;
	document.getElementById('deposito').disabled=true;
	
	document.getElementById('combinado').value=0;
	document.getElementById('depo').value=0;
}

function activarCombinado(){
	
	document.getElementById('efectivo').value="";
	document.getElementById('debito').value="";
	document.getElementById('cheque').value="";
	document.getElementById('vauche').value="";
	document.getElementById('deposito').value="";
	
	document.getElementById('efectivo').disabled=false;
	document.getElementById('debito').disabled=false;
	document.getElementById('cheque').disabled=false;
	document.getElementById('deposito').disabled=false;
	document.getElementById('vauche').disabled=false;
	document.getElementById('banco').disabled=false;
	
	document.getElementById('combinado').value=1;
	document.getElementById('depo').value=0;
}


function activarDeposito(){
	document.getElementById('efectivo').value="";
	document.getElementById('debito').value="";
	document.getElementById('cheque').value="";	
	document.getElementById('vauche').value="";
	document.getElementById('deposito').value=document.form1.total.value;
	
	document.getElementById('efectivo').disabled=true;
	document.getElementById('debito').disabled=true;
	document.getElementById('cheque').disabled=true;
	document.getElementById('vauche').disabled=false;
	document.getElementById('banco').disabled=false;
	document.getElementById('deposito').disabled=true;
	
	document.getElementById('combinado').value=0;
	document.getElementById('depo').value=1;
}

function validar(){

				
		
if(document.form1.combinado.value==1){
	
	if(document.form1.efectivo.value==""){
		var efectivo=1;
		var  efe=0;
	}
	if(document.form1.efectivo.value!=""){
		var efectivo=0;
		var  efe=parseFloat(document.form1.efectivo.value);
	}
	
	
	if(document.form1.debito.value==""){
		var debito=1;
		var deb=0;
	}
	if(document.form1.debito.value!=""){
		var debito=0;
		var deb=parseFloat(document.form1.debito.value);
	}
	
	
	if(document.form1.cheque.value==""){
		var cheque=1;
		var che=0;
	}
	if(document.form1.cheque.value!=""){
		var cheque=0;
		var che=parseFloat(document.form1.cheque.value);
	}
	
	
	if(document.form1.deposito.value==""){
		var deposito=1;
		var dep=0;
	}
	if(document.form1.deposito.value!=""){
		var deposito=0;
		var dep=parseFloat(document.form1.deposito.value);
		
		if(document.form1.vauche.value!=""){
			 	var filtro = /^(\d)+$/i;
		      	if (!filtro.test(document.getElementById('vauche').value)){
				alert('EL NUMERO DE VAUCHE DEBE SER NUMERICO');
				return false;
		   		}
				}
				
				if(document.form1.vauche.value==""){
				alert("Debe Ingresar un Numero de Vauche");
				return false;
				}	
			
	}
	
	
	
	var sumar= efectivo + debito + cheque + deposito;

	
	if(sumar>=3){
		alert("Debe Ingresar al Menos 2 Montos");
		return false;
	}
	
	var monto=document.form1.total.value;
	var pago= efe + deb + che + dep;
	

					
			if(pago!=monto){
					alert("El pago no Coincide con el monto Total a Cancelar");
					return false;
			}		
				
}
				
	
			if(document.form1.depo.value==1){
				
				if(document.form1.vauche.value!=""){
			 	var filtro = /^(\d)+$/i;
		      	if (!filtro.test(document.getElementById('vauche').value)){
				alert('EL NUMERO DE VAUCHE DEBE SER NUMERICO');
				return false;
		   		}
				}
				
				if(document.form1.vauche.value==""){
				alert("Debe Ingresar un Numero de Vauche");
				return false;
				}	
			}
				
				
}






$(function() {
$("#retencion").click(function() {
	
var add = 0;
var iva= 0;
var subtotal= 0;
var retencion=0;
var totalIva=0;
	
	
if(document.getElementById('retencion').checked==true){


add=document.getElementById('total2').value;
iva=document.getElementById('iva2').value;
subtotal=document.getElementById('subtotal2').value;


retencion=redondeo2decimales((iva*75)/100);
totalIva=redondeo2decimales(iva - retencion);

document.getElementById('iva').value=totalIva;
document.getElementById('total').value=redondeo2decimales(add - retencion);
document.getElementById('retencionVal').value=retencion;

$("#para1").text("Sub Total: " + subtotal);
$("#para2").text("IVA: " + totalIva );
$("#para4").text("Retencion: " + retencion);
$("#para3").text("Total a Pagar: " + redondeo2decimales(add - retencion));

	if(document.getElementById('radio1').checked==true) document.getElementById('efectivo').value=document.getElementById('total').value;
	if(document.getElementById('radio2').checked==true)document.getElementById('debito').value=document.getElementById('total').value;
	if(document.getElementById('radio3').checked==true)	document.getElementById('cheque').value=document.getElementById('total').value;
	if(document.getElementById('radio5').checked==true)document.getElementById('deposito').value=document.getElementById('total').value;

}else{
	if(document.getElementById('retencion').checked==false){
		
		add=document.getElementById('total2').value;
		iva=document.getElementById('iva2').value;
		subtotal=document.getElementById('subtotal2').value;


		retencion=redondeo2decimales((iva*75)/100);
		totalIva=redondeo2decimales(iva - retencion);

		document.getElementById('iva').value=iva;
		document.getElementById('total').value=add;
		document.getElementById('retencionVal').value="";

		$("#para1").text("Sub Total: " + subtotal);
		$("#para2").text("IVA: " + iva );
		$("#para4").text("" );
		$("#para3").text("Total a Pagar: " + add);
		
		if(document.getElementById('radio1').checked==true) document.getElementById('efectivo').value=document.getElementById('total').value;
	if(document.getElementById('radio2').checked==true)document.getElementById('debito').value=document.getElementById('total').value;
	if(document.getElementById('radio3').checked==true)	document.getElementById('cheque').value=document.getElementById('total').value;
	if(document.getElementById('radio5').checked==true)document.getElementById('deposito').value=document.getElementById('total').value;
	}
	


}





});
});

function redondeo2decimales(numero) {
var original = parseFloat(numero);
var result = Math.round(original * 100) / 100;
return result;
}
</script>
<body>
<body>
<span class="gallery clearfix"></span>
   
<form id="form1"   name="form1" method="post" onsubmit="return validar()" action="<?php echo $editFormAction; ?>" >
<input type="hidden" name="sel_detalles" id="sel_detalles" />
  <input type="hidden" name="MM_insert" value="form1" />
<table border="0" cellpadding="0" cellspacing="0" width="776">
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Datos  del Clientes</span></td>
      <td align="right" height="1" width="13"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr>
      <td width="13" height="140" rowspan="2" background="imagenes/v_back_izq.gif"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td valign="top" class="tituloDOSE_2">&nbsp;</td>
      <td width="13" rowspan="2" background="imagenes/v_back_der.gif"><img src="imagenes/cargar_productos.php" alt="1" height="2" width="13" /></td>
    </tr>
    <tr>
      <td valign="top" class="tituloDOSE_2">
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
                      <td align="center" width="446"><table width="546" align="left" cellpadding="3" cellspacing="0">
                        <tbody>
                          <tr>
                            <td width="58"align="right" valign="baseline">Nombre:</td>
                            <td width="146" valign="baseline"><input type="text" name="nombres" id="nombres" value="<?php echo $row_clientes['nombres']; ?>"  size="32" <?=$disable?>/></td>
                            <td width="79" align="right" valign="baseline" nowrap="nowrap">Cedula o Rif:</td>
                            <td width="237" valign="baseline">
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
    </tr>
    <tr>
      <td align="left" height="12" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
      <td height="12" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
      <td align="right" height="12" width="13"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
    </tr>
  </tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="776">
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td colspan="3" align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Datos de Facturacio</span>n</td>
      <td align="right" height="1" width="13"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr>
      <td height="26" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td width="223" align="center" valign="top" class="tituloDOSE_2"> <div id="para1"  class="negrita">&nbsp;&nbsp;SUB TOTAL: <?php echo $row_pedido['subtotal']; ?></div></td>
      <td width="176" align="center" valign="top" class="tituloDOSE_2"> <div id="para2"  class="negrita">&nbsp;&nbsp;IVA: <?php echo $row_pedido['iva']; ?></div></td>
      <td width="351" align="center" valign="top" class="tituloDOSE_2"> <div id="para3"  class="negrita">&nbsp;&nbsp;TOTAL A PAGAR: <?php echo $row_pedido['total']; ?></div></td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr>
      <td height="26" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td colspan="3" align="center" valign="top" class="tituloDOSE_2"><span class="negrita">Datos del Pago</span></td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr>
      <td width="13" height="26" background="imagenes/v_back_izq.gif"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td colspan="3" valign="top" class="tituloDOSE_2">Tipo de Pago
        <input type="radio" name="radio" checked="checked"   id="radio1"  onclick="activarEfectivo()" value="efe" />
       Efectivo 
          <input name="efectivo" type="text"  disabled="disabled" id="efectivo" value="<?php echo $row_pedido['total']; ?>" size="11" maxlength="11" />
          <input type="radio" name="radio" id="radio2" onclick="activarDebito()" value="deb" />
          
        Debito
        <input name="debito" type="text"  disabled="disabled" id="debito" size="11" maxlength="11" /> <input type="radio" name="radio" id="radio3" onclick="activarCheque()"  value="che" />
        Cheque
        <input name="cheque" type="text"  disabled="disabled" id="cheque" size="11" maxlength="11" />
        <input type="radio" name="radio" onclick="activarCombinado()" id="radio4" value="comb" />
        Combinado 
        </td>
      <td width="13" background="imagenes/v_back_der.gif"><img src="imagenes/cargar_productos.php" alt="1" height="2" width="13" /></td>
    </tr>
    <tr>
      <td height="26" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td colspan="3" valign="top" class="tituloDOSE_2">	
<input type="radio" name="radio" id="radio5" onclick="activarDeposito()" value="dep" />
Deposito
<input name="deposito" type="text" id="deposito" size="11"   disabled="disabled" maxlength="11" /> 
  N° Vauche: 
        <input name="vauche" type="text" id="vauche" size="11"   disabled="disabled" maxlength="11" /> 
        Banco: 
          <select name="banco" id="banco" disabled="disabled">
		  <option value="Banco Banesco">Banco Banesco</option>
		  <option value="Banco de Venezuela">Banco de Venezuela</option>
		  <option value="Banco Provincial">Banco Provincial</option>
          <option value="Banco del Tesoro">Banco del Tesoro</option>
          <option value="Banco Bicentenario">Banco Bicentenario</option>
          <option value="Banco Caroni">Banco Caroni</option>
        </select></td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr>
      <td height="26" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td colspan="3" valign="top" class="tituloDOSE_2">Retencion del 75% del IVA: 
        <input name="retencion" type="checkbox" id="retencion"/>
       <div id="para4"  class="negrita">&nbsp;&nbsp;</div></td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" height="12" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
      <td height="12" colspan="3" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
      <td align="right" height="12" width="13"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
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
            <td width="40" scope="col"><?php echo $row_productos_pedidos['cantidad']; ?>
             <input type="hidden" name="cantidad<?=$i?>" value="<?=$row_productos_pedidos['cantidad']?>" />
            </td>
            <td width="86" scope="col"><?php echo $row_productos_pedidos['precio']; ?>  
            <input type="hidden" name="precio<?=$i?>" id="precio<?=$i?>" value="<?=$row_productos_pedidos['precio']?>" />
            </td>
            <td width="86" scope="col"><?php echo $total; ?>
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
      <td align="center" valign="top"><input type="submit" name="button2" id="button2" value="FACTURAR MERCANCIA" /></td>
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
  <input type="hidden" name="total2" id="total2" value="<?=$row_pedido['total']?>" />
    <input type="hidden" name="subtotal2" id="subtotal2" value="<?=$row_pedido['subtotal']?>" />
     <input type="hidden" name="iva2" id="iva2" value="<?=$row_pedido['iva']?>" />
   <input type="hidden" name="total" id="total" value="<?=$row_pedido['total']?>" />
    <input type="hidden" name="subtotal" id="subtotal" value="<?=$row_pedido['subtotal']?>" />
     <input type="hidden" name="iva" id="iva" value="<?=$row_pedido['iva']?>" />
     <input type="hidden" name="cant_prod" value="<?=$i-1?>" />
     <input type="hidden" name="apartado" value="<?php echo $row_pedido['id_apartado']; ?>" />
          <input type="hidden" name="combinado" id="combinado"  />
          <input type="hidden" name="depo" id="depo"  />
           <input type="hidden" name="retencionVal" id="retencionVal"  />
           
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
