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

	//validamos que no se repitan los datos basicos

	if($_POST["tipo_producto"]=="Vehiculo"){
	  	mysql_select_db($database_conexion, $conexion);
		$query_datos = "SELECT chasis, motor, placa FROM ventas_productos where chasis='$_POST[chasis]' or motor='$_POST[motor]' or placa='$_POST[placa]'  ";
		$datos = mysql_query($query_datos, $conexion) or die(mysql_error());
		$row_datos = mysql_fetch_assoc($datos);
		$totalRows_datos = mysql_num_rows($datos);
		
		
	
		
			if($totalRows_datos>=1 and $_POST["placa"]!="-" and $_POST["chasis"]!="-" ){
				echo "<script type=\"text/javascript\">alert ('Uno de los datos del Vehiculo ya se encuentra registrado');  location.href='facturar_pedidos.php?pedido=$_POST[pedido]' </script>";
  				exit;
			}
	} 
	
	
	
	
	///////////////////////////////////////////////




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
			$efectivo = str_replace(".","",$efectivo);
			$efectivo = str_replace(",",".",$efectivo);
			
		$debito=$_POST["debito"];
			$debito = str_replace(".","",$debito);
			$debito = str_replace(",",".",$debito);
			
		$cheque=$_POST["cheque"];
			$cheque = str_replace(".","",$cheque);
			$cheque = str_replace(",",".",$cheque);
			
		$deposito=$_POST["deposito"];
			$deposito = str_replace(".","",$deposito);
			$deposito = str_replace(",",".",$deposito);
			
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
	  
	  	$num=$_POST["precio".$i];
		$num = str_replace(".","",$num);
		$num = str_replace(",",".",$num);
	  
  $insertSQL2 = sprintf("INSERT INTO ventas_productos (id_producto, factura, serie, cantidad, precio) VALUES (%s, %s, %s, %s, %s)",
                       
                       GetSQLValueString($_POST['id_prod'.$i], "int"),
                       GetSQLValueString($numero, "text"),
					   GetSQLValueString($serie, "text"),
                       GetSQLValueString($_POST['cantidad'.$i], "int"),
                       GetSQLValueString($num, "double"));

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
		$sql="delete from pedidos where id_pedidos='$_POST[pedido]'";
		$verificar=mysql_query($sql,$conexion) or die(mysql_error());
		
		if($verificar==1){
			
			$fec=date("d-m-Y");
		mysql_select_db($database_conexion, $conexion);
		$query_clientes = "SELECT * FROM clientes where cedula='$_POST[cedula]'";
		$clientes = mysql_query($query_clientes, $conexion) or die(mysql_error());
		$row_clientes = mysql_fetch_array($clientes);
	
if($_POST["tipo_producto"]=="servicio" or $_POST["tipo_producto"]=="producto" ){
		  echo "<script type=\"text/javascript\">alert ('FACTURADO');   window.open('factura_html.php?factura=$numero&serie=$serie&fecha=$fec&cliente=$_POST[cedula]&tipo=$row_clientes[tipo]&direccion=$row_clientes[direccion]&telefono=$row_clientes[telefono]&nombres=$row_clientes[nombres]&sub=$_POST[subtotal]&iva=$_POST[iva]&total=$_POST[total]','ventana1','width=800,height=900,scrollbars=YES')</script>";	
		    echo "<script type=\"text/javascript\"> location.href='lista_pedidos.php' </script>";
}//fin del if de facturacion
else{
		if($_POST["tipo_producto"]=="Vehiculo"){
		  	echo "<script type=\"text/javascript\">alert ('FACTURADO');   window.open('factura_html_motos.php?factura=$numero&serie=$serie&fecha=$fec&cliente=$_POST[cedula]&tipo=$row_clientes[tipo]&direccion=$row_clientes[direccion]&telefono=$row_clientes[telefono]&nombres=$row_clientes[nombres]&sub=$_POST[subtotal]&iva=$_POST[iva]&total=$_POST[total]&serial=$_POST[serial]&motor=$_POST[motor]&chasis=$_POST[chasis]&placa=$_POST[placa]&color=$_POST[color]','ventana1','width=800,height=900,scrollbars=YES')</script>";	
		//fin del if interno
		}

}//fin del else
		}
	}
//

}//fin del procesamiento del formulario


$pedido=$_GET["pedido"];
mysql_select_db($database_conexion, $conexion);
$query_pedido = "SELECT * FROM pedidos where id_pedidos='$pedido'";
$pedido = mysql_query($query_pedido, $conexion) or die(mysql_error());
$row_pedido = mysql_fetch_assoc($pedido);
$totalRows_pedido = mysql_num_rows($pedido);


mysql_select_db($database_conexion, $conexion);
$query_productos_pedidos = "SELECT * FROM pedido_productos where id_pedido='$row_pedido[correlativo]'";
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
	document.getElementById('efectivo').value=setNumeroFormato(document.form1.total.value, 2, ".", ",");
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
	document.getElementById('debito').value=setNumeroFormato(document.form1.total.value, 2, ".", ",");
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
	document.getElementById('cheque').value=setNumeroFormato(document.form1.total.value, 2, ".", ",");
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
	document.getElementById('deposito').value=setNumeroFormato(document.form1.total.value, 2, ".", ",");
	
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
			
			//si el producto es un vehiculo validamos que ingrse los datos
	      if(document.form1.tipo_producto.value=="Vehiculo"){
			  	
				if(document.form1.chasis.value==""){
				alert("Debe Ingresar el Chasis de la Moto");
				return false;
				}	
				if(document.form1.motor.value==""){
				alert("Debe Ingresar el Motor de la Moto");
				return false;
				}	
				if(document.form1.placa.value==""){
				alert("Debe Ingresar la Placa de la Moto");
				return false;
				}	
				if(document.form1.color.value==""){
				alert("Debe Ingresar el Color de la Moto");
				return false;
				}	
					
			 }
		//------------------------------------------------------------	
				
	
if(document.form1.combinado.value==1){
	
	if(document.form1.efectivo.value==""){
		var efectivo=1;
		var  efe=0;
	}
	if(document.form1.efectivo.value!=""){	
		var efectivo=0;
		var efe= document.form1.efectivo.value.split('.').join('');
			efe= parseFloat(efe.split(',').join('.'));
	
		
	}
	
	
	if(document.form1.debito.value==""){
		var debito=1;
		var deb=0;
	}
	if(document.form1.debito.value!=""){
		var debito=0;
		var deb= document.form1.debito.value.split('.').join('');
			deb= deb.split(',').join('.');
			deb=parseFloat(deb);
		
	}
	
	
	if(document.form1.cheque.value==""){
		var cheque=1;
		var che=0;
	}
	if(document.form1.cheque.value!=""){
		var cheque=0;
		var che= document.form1.cheque.value.split('.').join('');
			che= che.split(',').join('.');
			che=parseFloat(che);
		
	}
	
	
	if(document.form1.deposito.value==""){
		var deposito=1;
		var dep=0;
	}
	if(document.form1.deposito.value!=""){
		var deposito=0;
			var dep= document.form1.deposito.value.split('.').join('');
			dep= dep.split(',').join('.');
			dep=parseFloat(dep);
		
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
				
		
				
				
}//fin de la funcion validar






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
      <td width="223" align="center" valign="top" class="tituloDOSE_2"> <div id="para1"  class="negrita">&nbsp;&nbsp;SUB TOTAL: <?php echo number_format($row_pedido['subtotal'],"2",",","."); ?></div></td>
      <td width="176" align="center" valign="top" class="tituloDOSE_2"> <div id="para2"  class="negrita">&nbsp;&nbsp;IVA: <?php echo number_format($row_pedido['iva'],"2",",","."); ?></div></td>
      <td width="351" align="center" valign="top" class="tituloDOSE_2"> <div id="para3"  class="negrita">&nbsp;&nbsp;TOTAL A PAGAR: <?php echo number_format($row_pedido['total'],"2",",","."); ?></div></td>
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
          <input name="efectivo" type="text"  disabled="disabled" id="efectivo" value="<?php echo number_format($row_pedido['total'],"2",",","."); ?>" size="11" maxlength="11" onBlur="numeroBlur(this);"  />
          <input type="radio" name="radio" id="radio2" onclick="activarDebito()" value="deb" />
          
        Debito
        <input name="debito" type="text"  disabled="disabled" id="debito" size="11" maxlength="11" onBlur="numeroBlur(this);"  /> <input type="radio" name="radio" id="radio3" onclick="activarCheque()"  value="che"  />
        Cheque
        <input name="cheque" type="text"  disabled="disabled" id="cheque" size="11" maxlength="11" onBlur="numeroBlur(this);"   />
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
<input name="deposito" type="text" id="deposito" size="11"   disabled="disabled" maxlength="11" onBlur="numeroBlur(this);" /> 
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
<table border="0" cellpadding="0" cellspacing="0" width="776">
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
      <table  align="center" class="tblLista">
        <thead>
          <tr >
          <? 
		 
		 	mysql_select_db($database_conexion, $conexion);
			$query_productos_pedidos2 = "SELECT * FROM pedido_productos where id_pedido='$row_pedido[correlativo]'";
			$productos_pedidos2 = mysql_query($query_productos_pedidos2, $conexion) or die(mysql_error());
			$row_productos_pedidos2 = mysql_fetch_assoc($productos_pedidos2);
		 
		 	mysql_select_db($database_conexion, $conexion);
			$query_tipo_productos = "SELECT * FROM productos where id_productos='$row_productos_pedidos2[id_producto]'";
			$tipo_productos = mysql_query($query_tipo_productos, $conexion) or die(mysql_error());
			$row_tipo_productos = mysql_fetch_assoc($tipo_productos);
		
		
		
		  ?>
       <th width="200" scope="col"><?php if($row_tipo_productos['tipo']=="Repuesto") echo "Codigo"; else echo "Nombre"; ?></th>
        <th width="70" scope="col"><?php if($row_tipo_productos['tipo']=="Repuesto") echo "Tipo"; else echo "Marca"; ?></th>
        <th width="100" scope="col"><?php if($row_tipo_productos['tipo']=="Repuesto") echo "Nombre"; else echo "Modelo"; ?></th>
        <th width="50" scope="col">Cantidad</th>
        <th width="70" scope="col">Precio Bs.F</th>
         <th width="70" scope="col">Total</th>
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
            <td width="150" scope="col"><? if($row_tipo_productos['tipo']=="Repuesto") echo $row_productos['codigo']; else echo $row_productos['nombre'];?>
            <input type="hidden" name="codigo<?=$i?>" value="<?=$row_productos['codigo']?>" />
            <input type="hidden" name="id_prod<?=$i?>" value="<?=$row_productos['id_productos']?>" />
            </td>
            <td width="70" scope="col"><?php if($row_tipo_productos['tipo']=="Repuesto")  echo $row_productos['tipo']; else echo $row_productos['marca']; ?></td>
            <td width="100" scope="col"><?php if($row_tipo_productos['tipo']=="Repuesto")  echo $row_productos['nombre']; else echo $row_productos['modelo']; ?></td>
            <td width="50" scope="col"><?php echo $row_productos_pedidos['cantidad']; ?>
             <input type="hidden" name="cantidad<?=$i?>" value="<?=$row_productos_pedidos['cantidad']?>" />
            </td>
            <td width="70" scope="col"><input type="hidden" name="precio<?=$i?>" id="precio<?=$i?>" value="<?=number_format($row_productos_pedidos['precio'],"2",",",".")?>" />
              <?php echo number_format($row_productos_pedidos['precio'],"2",",","."); ?></td>
            <td width="70" scope="col"><? echo number_format($total,"2",",","."); ?>
            </td>
          </tr>
          <?php 
		  $i++;
		  }while($row_productos_pedidos = mysql_fetch_assoc($productos_pedidos));  ?>
      </table>
      </td>
    </tr>
     <?php if($row_tipo_productos['tipo']=="Vehiculo"){ ?>
    <tr valign="top">
      <td height="19" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td align="center" valign="top">   
      <table width="673" align="center" class="tblLista">
        <thead>
          <tr >
            <th scope="col">Chasis</th>
            <th scope="col">Motor</th>
            <th scope="col">Placa</th>
            <th scope="col">Color</th>
            <? 
		 
		 	mysql_select_db($database_conexion, $conexion);
			$query_productos_pedidos2 = "SELECT * FROM pedido_productos where id_pedido='$row_pedido[correlativo]'";
			$productos_pedidos2 = mysql_query($query_productos_pedidos2, $conexion) or die(mysql_error());
			$row_productos_pedidos2 = mysql_fetch_assoc($productos_pedidos2);
		 
		 	mysql_select_db($database_conexion, $conexion);
			$query_tipo_productos = "SELECT * FROM productos where id_productos='$row_productos_pedidos2[id_producto]'";
			$tipo_productos = mysql_query($query_tipo_productos, $conexion) or die(mysql_error());
			$row_tipo_productos = mysql_fetch_assoc($tipo_productos);
		
		
		
		  ?>
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
        <tr id="lista_detalles2">
          <td align="center"><input name="chasis" type="text" id="chasis" value="" size="30" maxlength="20" /></td>
          <td align="center"><input name="motor" type="text" id="motor" value="" size="30" maxlength="20" /></td>
          <td align="center"><input name="placa" type="text" id="placa" value="" size="30" maxlength="10" /></td>
           <td align="center"><input name="color" type="text" id="color" value="" size="30" maxlength="10" /></td>
          </tr>
        <?php 
		  $i++;
		  }while($row_productos_pedidos = mysql_fetch_assoc($productos_pedidos));  ?>
      </table></td>
      <td background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
      <?php }?>
    <tr>
      <td height="19" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td align="center" valign="top"><input  type="submit" name="button2" id="button2" value="FACTURAR MERCANCIA" <?php if($row_clientes['cedula']<=0){ echo "disabled='disabled'";} ?> /></td>
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
      <td background="imagenes/v_back_inf.gif" height="12" width="786"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
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
     <input type="hidden" name="pedido" value="<?php echo $row_pedido['id_pedidos']; ?>" />
      <input type="hidden" name="tipo_producto" id="tipo_producto" value="<?php echo $row_tipo_productos['tipo']; ?>" />
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

mysql_free_result($caja);

mysql_free_result($datos);

mysql_free_result($facturas);

mysql_free_result($pedido);

mysql_free_result($clientes);
?>
