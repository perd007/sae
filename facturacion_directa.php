<?php require_once('Connections/conexion.php'); ?>
<?php 
//validar usuario

if($_COOKIE["val"]==true){
	if($_COOKIE["f"]!=1){
	echo "<script type=\"text/javascript\">alert ('Usted no posee permisos para Realizar Pedidos');location.href='fondo.php' </script>";
    exit;
	}
}
else{
echo "<script type=\"text/javascript\">alert ('Error usuario invalido'); location.href='fondo.php' </script>";
 exit;
}
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


$valor=0;

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	//si se preciona el boton de envio=
	if($_POST['bus']==2  ){
		
		//validamos que se seleecionen productos y posean cantidad y precio
	$i=1;
			if($_POST["can_detalles"]==0){
				 echo "<script type=\"text/javascript\">alert ('Debe seleccionar al menos un Producto');  location.href='' </script>";
			}
 while($_POST["nrodetalle"]>=$i){ 
 			if($_POST["id".$i]>=1){
			if($_POST["Cantidad".$i]=="" or $_POST["Cantidad".$i]<=0 ){
				 echo "<script type=\"text/javascript\">alert ('La Cantidad de los Productos debe ser Mayor a Cero');  location.href='' </script>";
			}
			if($_POST['precio'.$i]=="" or $_POST['precio'.$i]<=0){
				 echo "<script type=\"text/javascript\">alert ('El Precio debe ser mayor a Cero');  location.href='' </script>";
			}
			}
			$i++;
 }//fin del while
////////////////////////////////////////////////
//validamos que el producto no se encuentre registrado mas de una vez
		
		for($i=1;$i<=$_POST["nrodetalle"];$i++){
			$x=0;
			$Prod_selec[$i]=$_POST["id".$i];
			
			for($j=1;$j<=$_POST["nrodetalle"];$j++){
				if($_POST["id".$j]>=1){
				if($Prod_selec[$i]==$_POST["id".$j]){ $x++; }
				if($x > 1){ echo "<script type=\"text/javascript\">alert ('No se puede repetir el producto para una misma factura');  location.href='' </script>";
  exit;}}
				
					
			 }
		}		
/////////////////////////////////////////////////////////////////////
		
	//si el cliente no esta registrado lo insertamos en la tabla
		if($_POST['existe']==0){
  $insertSQL = sprintf("INSERT INTO clientes (nombres, telefono, cedula, direccion, tipo) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['nombres'], "text"),
                       GetSQLValueString($_POST['telefono'], "text"),
                       GetSQLValueString($_POST['cedula'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['tipo'], "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
		}// fin del if existe
		
	//	FUNCION PARA GENERAR UN NUEVO CODIGO PARA LA FACTURA
	
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
/*
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
	*/
	/////////////////////////
	
	
	$insertSQL = sprintf("INSERT INTO facturas ( id_cliente, fecha, fecha_fac, excento, serie, numero, sub_total, iva, total, transaccion, efectivo, debito, cheque, retencion, deposito, vauche, banco) VALUES (%s, %s, NOW(), %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['cedula'], "text"),
					   GetSQLValueString($_POST['fecha'], "date"),
					   GetSQLValueString($_POST['excento'], "text"),
                       GetSQLValueString($serie, "text"),
                       GetSQLValueString($numero, "text"),
                       GetSQLValueString($_POST['subtotal'], "double"),
                       GetSQLValueString($_POST['iva'], "double"),
                       GetSQLValueString($_POST['total'], "double"),
					   GetSQLValueString("FACTURADO", "text"),
					   GetSQLValueString($_POST['total'], "double"),
                       GetSQLValueString($debito, "double"),
                       GetSQLValueString($cheque, "double"),
					   GetSQLValueString($_POST['retencionVal'], "double"),
					   GetSQLValueString($deposito, "double"),
                       GetSQLValueString($_POST['vauche'], "int"),
                       GetSQLValueString($banco, "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  
  
  $i=1;
  while($_POST["nrodetalle"]>=$i){
	  
	  	$num=$_POST["precio".$i];
		$num = str_replace(".","",$num);
		$num = str_replace(",",".",$num);
	  
  $insertSQL2 = sprintf("INSERT INTO ventas_productos (id_producto, factura, serie, cantidad, precio) VALUES (%s, %s, %s, %s, %s)",
                       
                       GetSQLValueString($_POST["id".$i], "int"),
                       GetSQLValueString($numero, "text"),
					   GetSQLValueString($serie, "text"),
                       GetSQLValueString($_POST['Cantidad'.$i], "int"),
                       GetSQLValueString($num, "double"));

  mysql_select_db($database_conexion, $conexion);
  $Result2 = mysql_query($insertSQL2, $conexion) or die(mysql_error());

  if($Result2==1){
	  //actualizamos el almacen
		$eliminar = sprintf("INSERT INTO almacen (id_producto, factura_venta, serie_venta, cantida, transaccion) VALUES (%s, %s, %s, %s, %s)",
                       
                       GetSQLValueString($_POST["id".$i], "int"),
                       GetSQLValueString($numero, "text"),
					   GetSQLValueString($serie, "text"),
                       GetSQLValueString($_POST["Cantidad".$i], "int"),
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
  

	
	
  	//
	 }//fin del if que valida si el producto fue borrado
		$i++;
			
//generamos nuestra factura html
$fec=$_POST['fecha'];
if($_POST['excento']!="si"){

//FACTURAMOS CON PDF O HTML
/*
echo "<script type=\"text/javascript\">alert ('FACTURADO');  location.href='factura_pdf.php?factura=$numero&serie=$serie&fecha=$fec&cliente=$_POST[cedula]&tipo=$row_clientes[tipo]&direccion=$row_clientes[direccion]&telefono=$row_clientes[telefono]&nombres=$row_clientes[nombres]&sub=$_POST[subtotal]&iva=$_POST[iva]&total=$_POST[total]' </script>";	

*/
		  echo "<script type=\"text/javascript\">alert ('FACTURADO');   window.open('factura_html.php?factura=$numero&serie=$serie&fecha=$fec&cliente=$_POST[cedula]&tipo=$_POST[tipo]&direccion=$_POST[direccion]&telefono=$_POST[telefono]&nombres=$_POST[nombres]&sub=$_POST[subtotal]&iva=$_POST[iva]&total=$_POST[total]','ventana1','width=800,height=900,scrollbars=YES')</script>";	
		    echo "<script type=\"text/javascript\"> location.href='facturacion_directa.php' </script>"; 

}else{
	echo "<script type=\"text/javascript\">alert ('FACTURADO');  location.href='facturacion_directa.php' </script>";
}
//fin del html
	
	
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

<script  type="text/javascript" src="js/AjaxRequest.js"></script>
<style type="text/css"> 
    @import url("jscalendar-1.0/calendar-win2k-cold-1.css");
	
    </style>
<title>Documento sin título</title>
</head>


<script language="javascript">
var chek=0;


function validarBuscar(){
	
	
		if(document.form1.buscar.value!=""){
			 var filtro = /^(\d)+$/i;
		      if (!filtro.test(document.getElementById('buscar').value)){
				alert('EL NUMERO DE CEDULA  A BUSCAR DEBE SER NUMERICO');
				return false;
		   		}
				}
	if(document.form1.buscar.value==""){
	alert('DEBE INTRODUCIR UN NUMERO DE CEDULA A BUSCAR');
	return false;
	}
	
	chek=1;
	document.form1.bus.value=1;

	 	 
}

function validarEnviar(){
	
	 chek=2;
	document.form1.bus.value=2;
	document.form1.val.value=2;
	 
}

function validar(){

		
if(chek==2){
		
			
		if(document.form1.val.value==0){
						alert("Debe Ingresar un Cliente");
						return false;
		}		
			
		
		if(document.form1.can_detalles.value==0){
						alert("Debe Seleccionar al menos un Producto");
						return false;
			}		
			
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

			
}
</script>
<script type="text/javascript">


//funcion que se activa con el clik del boton para calcular totales finales
$(function() {
$("#addAll").click(function() {
var add = 0;
var iva= 0;
var subtotal= 0;
var total2= 0;
$(".amt").each(function() {
		
var total = $(this).val().split('.').join('');
total2 +=Number(total.split(',').join('.'));
subtotal = total2 / 1.16;
iva = total2 -subtotal;


});

var total3= setNumeroFormato(total2, 2, ".", ",");
var iva2= setNumeroFormato(iva, 2, ".", ",");
var subtotal2= setNumeroFormato(subtotal, 2, ".", ",");

document.getElementById('total').value=total2;
document.getElementById('iva').value= iva;
document.getElementById('subtotal').value= subtotal;
if(document.getElementById('can_detalles').value==0){document.getElementById('total').value=0; total=0;}
$("#para1").text("Sub Total: " + subtotal2);
$("#para2").text("IVA: " + iva2 );
$("#para3").text("Total: " + total3);
});
});
//////////////////////////

//funcion que se activa con el clik del boton para calcular totales
$(function() {
$("#addAll2").click(function() {
var add = 0;
var iva= 0;
var subtotal= 0;
var total2= 0;
$(".amt").each(function() {
		
var total = $(this).val().split('.').join('');
total2 +=Number(total.split(',').join('.'));
subtotal = total2 / 1.16;
iva = total2 -subtotal;



});

var total3= setNumeroFormato(total2, 2, ".", ",");
var iva2= setNumeroFormato(iva, 2, ".", ",");
var subtotal2= setNumeroFormato(subtotal, 2, ".", ",");


document.getElementById('total').value=total2;
document.getElementById('iva').value= iva;
document.getElementById('subtotal').value= subtotal;
if(document.getElementById('can_detalles').value==0){document.getElementById('total').value=0; total=0;}
$("#para1").text("Sub Total: " + subtotal2);
$("#para2").text("IVA: " + iva2 );
$("#para3").text("Total: " + total3);
});
});
////////////////////////////////////////////////////




function sumaTotal(nro) {
	
	
var disponible=0;
var cantida=0;
var tipo=0;

cantida=document.getElementById("Cantidad" + nro).value;
disponible=document.getElementById("disponible" + nro).value;
tipo=document.getElementById("tipo" + nro).value;
var cero=disponible-cantida;	
if(cero<0 && tipo=="producto"){
	alert('La Cantidad Supera lo Disponible');
	document.getElementById("Cantidad" + nro).value=0;
	return false;
	
}
var cantidad=document.getElementById("Cantidad" + nro).value;
var precio=document.getElementById("precio" + nro).value;

precio = precio.split('.').join('');	
precio =precio.split(',').join('.');

var total=cantidad * precio;

var numero = new Number(setNumero(total));
	if (numero == 0)  total = "0,00";
	else  var numero2 = setNumeroFormato(total, 2, ".", ",");


if(cantida==0){
	alert('La Cantidad No puede ser Cero');
	document.getElementById("Cantidad" + nro).value=1;
	document.getElementById("total" + nro).value = setNumeroFormato(precio, 2, ".", ",");
	return false
	}


document.getElementById("total" + nro).value = numero2;



}


function redondeo2decimales(numero) {
var original = parseFloat(numero);
var result = Math.round(original * 100) / 100;
return result;
}
</script>


<script>


function manda_al_servidor()
{
	 
//limpiamos campo
document.getElementById('cedula').value = "";
document.getElementById('nombres').value = "";
document.getElementById('telefono').value = "";
document.getElementById('direccion').value = "";
   
  //url del servidor donde voy a buscar la informacion
  var url = "transaccion_cliente.php";
 
  //traemos el valor de la caja de texto 1
  var op = document.getElementById('buscar').value;

	 //implementamos clase
     AjaxRequest.get

     (       

      {//CTAX

       'parameters':{'cedula':op}//le asignamos el valor de la caja de texto al parametro opcion

       ,'onSuccess':respServidor//funcion que va a recibir la respuesta del servidor

       ,'url':url//variable declarada arriba

       ,'onError':function(req)//funcion que recibe y muestra los errores de comunicacion

        { 

         alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);

        }

      }

     );
}

//funcion de respuesta de servidor
//req objeto de datos que recibe del servidor
function respServidor(req)

{

 if(req.responseText == 0)//no hay respuesto

   {


  alert("ERROR EL SERVIDOR NO RESPONDE");

   }

 else//asignamos el valor al campo 2

   {
	var parametros = req.responseText;
	var parametros2= parametros.split('-');

    document.getElementById('cedula').value = parametros2[0];
	document.getElementById('nombres').value = parametros2[1];
	document.getElementById('telefono').value = parametros2[2];
	document.getElementById('direccion').value = parametros2[3];
	document.getElementById('tipo').value = parametros2[4];
	
	if(parametros2[0]!=0){
	document.getElementById('existe').value = 1;
	}
	else{
	document.getElementById('existe').value = 0;
	}
  	

   }

}


</script>

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
<span class="gallery clearfix"></span>
   
<form id="form1"   name="form1" method="post" onsubmit="return validar()" action="<?php echo $editFormAction; ?>" >
<input type="hidden" name="sel_detalles" id="sel_detalles" />
  <input type="hidden" name="MM_insert" value="form1" />
<table border="0" cellpadding="0" cellspacing="0" width="776">
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td colspan="2" align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Facturacion Directa</span></td>
      <td align="right" height="1" width="13"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr>
      <td width="13" height="140" rowspan="4" background="imagenes/v_back_izq.gif"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td colspan="2" valign="top" class="tituloDOSE_2">Excento: 
        
        <label for="checkbox">
          <input type="checkbox" name="excento" id="excento" value="si" checked="checked" />
        </label></td>
      <td width="13" rowspan="4" background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" valign="top" class="tituloDOSE_2">Fecha de Factura: 
        <input name="fecha" type="text" id="fecha" value="<?=date("Y/m/d");?>" size="20" maxlength="10" readonly="readonly" />
        <button type="submit" id="cal-button-1" title="Clic Para Escoger la fecha">Fecha</button>
        <script type="text/javascript">
							Calendar.setup({
							  inputField    : "fecha",
							  ifFormat   : "%Y-%m-%d",
							  button        : "cal-button-1",
							  align         : "Tr"
							});
						  </script></td>
      </tr>
    <tr>
      <td colspan="2" valign="top" class="tituloDOSE_2">
        
        Cedula o Rif:
        
        <input name="buscar" type="text" id="buscar" value="<?php echo $_POST['buscar']; ?>" maxlength="15" />
        <input  type="hidden" name="buscar2" id="buscar2"  />
        <input type="button" name="bt_enviar" onClick="manda_al_servidor();" id="bt_enviar" value="Buscar" />
        <input type="hidden" name="TipoClasificacion" id="FlagCompras" value="C" />
        
      </td>
      </tr>
    <tr>
      <td class="tituloDOSE_2" valign="top" width="375">
     
      <table border="0" cellpadding="0" cellspacing="0" width="594">
    <tbody>
      <tr>
        <td width="13" align="left"  ></td>
        <td  height="1" class="tituloDOSE_2">
 
        <table border="0" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
              <td align="right"></td>
              </tr>
            <tr>
              <td align="center" width="140"><table cellpadding="3" cellspacing="0" width="566">
                <tbody>
                  <tr>
                    <td width="62"align="right" valign="baseline">Nombre:</td>
                    <td width="214" valign="baseline"><input name="nombres" type="text" id="nombres" value="<?php echo $row_clientes['nombres']; ?>"  size="50" maxlength="50" /></td>
                    <td width="93" align="right" valign="baseline" nowrap="nowrap">Cedula o Rif:</td>
                    <td width="171" valign="baseline"><input name="cedula" type="text" id="cedula" value="<?php echo $row_clientes['cedula']; ?>" size="32" maxlength="15" <?=$disable?>/>
                    </td>
                    </tr>
                  <tr>
                    <td align="right" valign="baseline" nowrap="nowrap">Telefono:</td>
                    <td valign="baseline"><input name="telefono" type="text" id="telefono" value="<?php echo $row_clientes['telefono']; ?>" size="32" maxlength="11" <?=$disable?>/></td>
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
                      <textarea name="direccion" id="direccion"  cols="40" rows="4" <?=$disable?>><?php echo $row_clientes['direccion']; ?></textarea>
                      </label></td>
                  </tr>
                  </tbody>
                </table></td>
              </tr>
            <tr>
              <td align="right" width="80%"></td>
              </tr>
            </tbody>
        </table></td>
        <td width="12"></td>
      </tr>
    </tbody>
  </table>
    
</td>
      <td class="tituloDOSE_2" valign="top" width="375">
        <p id="para1"  class="negrita"/>
      <p id="para2" class="negrita"/>
      <p id="para3" class="negrita"/>
      </td>
    </tr>
    <tr>
      <td align="left" height="12" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
      <td width="750" height="12" colspan="2" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
      <td align="right" height="12" width="13"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
    </tr>
  </tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="775">
  <tbody>
    <tr>
      <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
      <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita">Seleccion de Productos</span></td>
      <td align="right" height="1" width="12"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr>
      <td width="13" height="140" rowspan="2" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td valign="baseline" align="right"  class="gallery clearfix">
        <a id="aItem" href="listado_productos_ventas.php?filtrar=default&ventana=requerimiento_detalles_insertar&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]" style="display:none;"></a>
        <a id="aItem2" href="listado_servicios_ventas.php?filtrar=default&ventana=requerimiento_detalles_insertar&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]" style="display:none;"></a>
     <input type="button" class="btLista" value="Producto" id="btItem" onclick="document.getElementById('aItem').click();"  />
     <input type="button" class="btLista" value="Servicios" id="btItem2" onclick="document.getElementById('aItem2').click();"  />
     <input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'detalles');"  />
        
        </td>
      <td width="12" rowspan="2" background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr style=" color: #FFF;">
      <td valign="top">
      <table width="700" align="center" class="tblLista">
        <thead>
          <tr >
       <th width="80" scope="col">Codigo</th>
        <th width="77" scope="col">Tipo</th>
        <th width="212" scope="col">Nombre</th>
        <th width="40" scope="col">Cantidad</th>
        <th width="40" scope="col">Disponible</th>
        <th width="86" scope="col">Precio Bs.F</th>
         <th width="86" scope="col">Total</th>
          </tr>
        </thead>
        
        <tbody id="lista_detalles">
        
        </tbody>
      </table>
      </td>
    </tr>
    <tr>
      <td height="19" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td align="center" valign="top"><input type="submit" name="addAll2" id="addAll2" value="FACTURAR" onclick="return validarEnviar()"/>
      <input id="addAll" type="button" value="CALCULAR TOTAL" />
</td>
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
   <input type="hidden" name="total" id="total" value=""  />
    <input type="hidden" name="iva" id="iva" value=""  />
     <input type="hidden" name="subtotal" id="subtotal" value=""  />
</p>
   

</form>
<p>&nbsp;</p>

</body>
</html>
<?php
mysql_free_result($clientes);

mysql_free_result($caja);
?>
