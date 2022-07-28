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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
//validamos que se seleecionen productos y posean cantidad y precio

	$i=1;
			if($_POST["can_detalles"]==0){
				 echo "<script type=\"text/javascript\">alert ('Debe seleccionar al menos un Producto');  location.href='' </script>";
			}
			
			
 while($_POST["nro_detalles"]>=$i){
	  
	 if($_POST["id".$i]!=""){
			if($_POST["Cantidad".$i]=="" or $_POST["Cantidad".$i]<=0){
				 echo "<script type=\"text/javascript\">alert ('La Cantidad de los Productos debe ser Mayor a Cero');  location.href='' </script>";
			}
			if($_POST['costo'.$i]=="" or $_POST['costo'.$i]<=0 ){
				 echo "<script type=\"text/javascript\">alert ('El costo debe ser mayor a Cero');  location.href='' </script>";
			}
	 }
	 $i++;
 }//fin del while
 
////////////////////////////////////////////////////////////////////
	  
//Eliminamos los productos sacados
		 //productos comprados
            $sql_cp = "SELECT * FROM compra_productos where factura=$_POST[factura] ";
			$query_cp = mysql_query($sql_cp,$conexion) or die ($sql_cp.mysql_error());
			$rows_lista_cp = mysql_num_rows($query_cp);
			
			//
			
			//comparamos productos almacenados con enviados
			while ($field_cp = mysql_fetch_array($query_cp)) {
				$eli_prod=0;
				$eli_prod2=0;
				$i=1;
				
				 while($_POST["nro_detalles"]>=$i){
						if($field_cp["producto"]!=$_POST["id".$i] and $_POST["id".$i]>=1){
							$eli_prod=1;	
						}//fin del if
						if($field_cp["producto"]==$_POST["id".$i] and $_POST["id".$i]>=1){
							$eli_prod2=1;	
						}//fin del if
						$i++;
				  }//fin del segundo while
					
						if($eli_prod==1 and $eli_prod2==0){
							
							//consultamos disponibilidad
							$query_almacen = "SELECT sum(cantida) FROM almacen where id_producto='$field_cp[producto]' and (transaccion='COMPRA' or transaccion='COMPRA-MODIFICADA') ";
							$almacen = mysql_query($query_almacen, $conexion) or die(mysql_error());
							$row_almacen = mysql_fetch_assoc($almacen);
							
							$query_almacen2 = "SELECT sum(cantida) FROM almacen where  id_producto='$field_cp[producto]' and transaccion='VENTA' ";
							$almacen2 = mysql_query($query_almacen2, $conexion) or die(mysql_error());
							$row_almacen2 = mysql_fetch_assoc($almacen2);
							
							$query_pedido = "SELECT sum(cantidad) FROM pedido_productos where  id_producto='$field_cp[producto]' ";
							$pedido = mysql_query($query_pedido, $conexion) or die(mysql_error());
							$row_pedido = mysql_fetch_assoc($pedido);
							
							$query_almacen3 = "SELECT sum(cantida) FROM almacen where  id_producto='$field_cp[producto]' and transaccion='EXTRAIDO' ";
							$almacen3 = mysql_query($query_almacen3, $conexion) or die(mysql_error());
							$row_almacen3 = mysql_fetch_assoc($almacen3);
							
							$query_apartado = "SELECT sum(cantidad) FROM productos_apartados where  id_producto='$field_cp[producto]' ";
							$apartado = mysql_query($query_apartado, $conexion) or die(mysql_error());
							$row_apartado = mysql_fetch_assoc($apartado);
							
							$disponible=$row_almacen["sum(cantida)"]-$row_almacen2["sum(cantida)"]-$row_pedido["sum(cantidad)"]-$row_almacen3["sum(cantida)"]-$row_apartado["sum(cantidad)"];
						
							//
							
							//consultamos la cantidad del producto a eliminar y la comparamos 
							$query_almacen3 = "SELECT * FROM almacen where id_producto='$field_cp[producto]' and factura_compra=$_POST[factura]  and transaccion='COMPRA' ";
							$almacen3 = mysql_query($query_almacen3, $conexion) or die(mysql_error());
							$row_almacen3 = mysql_fetch_assoc($almacen3);
							
							
							
							if($row_almacen3["cantida"]>$disponible){
								echo "<script type=\"text/javascript\">alert ('No se puede procesar la transacion debido a que la cantidad a eliminar de un producto supera a la Disponible');  location.href='' </script>";
  							exit;
								
							}else{
								
							//
							//actualizamos campo para indicar que se modifico
							$actualizar = "UPDATE compra_productos SET transaccion='ELIMINADO POR MODIFICACION' WHERE producto='$field_cp[producto]'  and factura=$_POST[factura]";
							$$actualizar2 = mysql_query($actualizar, $conexion) or die(mysql_error());
							//
							//eliminamos registros del almacen simpre y cuando no hayan sido vendido
							$eliminar = "UPDATE almacen SET transaccion='ELIMINADO POR MODIFICACION' WHERE id_producto='$field_cp[producto]'  and factura_compra=$_POST[factura]";
							$eliminar2 = mysql_query($eliminar, $conexion) or die(mysql_error());
							//
							}//fin del else
						
						}//fin del if
			}//fin del primer while
//fin de la eliminacion de productos sacados
/////////////////////////////////////////////////////////////////////////////////////////	
	
//validamos que la factura no haya sido introducida Ppara otro proveedor
		mysql_select_db($database_conexion, $conexion);
		$query_Recordset1 = "SELECT * FROM compras where num_fac='$_POST[num_fac]' and proveedor='$_POST[rif]' and id_compras!='$_POST[id_compras]'";
		$Recordset1 = mysql_query($query_Recordset1, $conexion) or die(mysql_error());
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
		if($totalRows_Recordset1>=1){
	  	echo "<script type=\"text/javascript\">alert ('Este numero de factura ya existe para este proveedor');  location.href='' </script>";
  		exit;
		}
		//
		//validamos que el producto no se encuentre regestrado mas de una vez
		
		for($i=1;$i<=$_POST["can_detalles"];$i++){
			$x=0;
			$Prod_selec[$i]=$_POST["id".$i];
			
			for($j=1;$j<=$_POST["can_detalles"];$j++){
				if($Prod_selec[$i]==$_POST["id".$j]){ $x++; }
				if($x > 1){ echo "<script type=\"text/javascript\">alert ('No se puede repetir el producto para una misma factura');  location.href='' </script>";
  exit;}
				
					
			 }
		}
/////////////////////////////////////////////////////////////

  
  //modificamos los productos


	  $i=1;
	 while($_POST["nro_detalles"]>=$i){ 

		//verificamo si es insercion o actualizacion
		mysql_select_db($database_conexion, $conexion);
		$query_CP = "SELECT * FROM compra_productos where producto='".$_POST["id".$i]."' and factura=$_POST[factura] and transaccion!='ELIMINADO POR MODIFICACION' ";
		$CP = mysql_query($query_CP, $conexion) or die(mysql_error());
		$row_CP = mysql_fetch_assoc($CP);
		$totalRows_CP = mysql_num_rows($CP);
		
		if($totalRows_CP==0 and $_POST["id".$i]>=1){
			//instarmos compra
		$num = str_replace(".","",$_POST['costo'.$i]);
		$num = str_replace(",",".",$_POST['costo'.$i]);
    			$insertSQLCP = sprintf("INSERT INTO compra_productos (producto, precio, factura, cantidad, transaccion) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST["id".$i], "int"),
                       GetSQLValueString($num, "double"),
					   GetSQLValueString($_POST['num_fac'], "text"),
					   GetSQLValueString($_POST["Cantidad".$i], "int"),
					   GetSQLValueString('INGRESO POR MODIFICACION', "text"));

  				mysql_select_db($database_conexion, $conexion);
 	 			$ResultCP = mysql_query($insertSQLCP, $conexion) or die(mysql_error());
			
			
				//cargamos el almacen
				$insertSQLA = sprintf("INSERT INTO almacen (id_producto, factura_compra, cantida, transaccion) VALUES (%s, %s, %s, %s)",
                       
                       GetSQLValueString($_POST["id".$i], "int"),
                       GetSQLValueString($_POST['num_fac'], "text"),
                       GetSQLValueString($_POST["Cantidad".$i], "int"),
                       GetSQLValueString("INGRESO POR MODIFICACION", "text"));

  				mysql_select_db($database_conexion, $conexion);
  				$ResultA = mysql_query($insertSQLA, $conexion) or die(mysql_error());
	//
	
	if($ResultA==0 or $ResultCP==0){

  		echo "<script type=\"text/javascript\">alert ('Ocurrio un Error al insertar Nuevos Productos');  location.href='' </script>";
  		exit;
  		}
		//fin de la insercion de nuevos productos

	}else{
		
		
		//actualizamos datos basicos
if($_POST['rif2']==""){	
$estatus=" ";
}
if($_POST['rif2']!=""){	
$estatus="PENDIENTE";
}

  $updateSQL = sprintf("UPDATE compras SET fecha=%s, estatus=%s, num_fac=%s, proveedor=%s, fecha_limite=%s, fiador=%s, subtotal=%s, iva=%s, total=%s WHERE id_compras=%s",
                       GetSQLValueString($_POST['fecha'], "date"),
					   GetSQLValueString($estatus, "text"),
                       GetSQLValueString($_POST['num_fac'], "text"),
                       GetSQLValueString($_POST['rif'], "text"),
					   GetSQLValueString($_POST['fecha2'], "date"),
                       GetSQLValueString($_POST['rif2'], "text"),
					   GetSQLValueString($_POST['subtotal'], "float"),
					   GetSQLValueString($_POST['iva'], "float"),
					   GetSQLValueString($_POST['total'], "float"),
                       GetSQLValueString($_POST['id_compras'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
/////////////////////////////////////////////////////////////////////////////
		

//modificamos la tabla compra_productos
		$num=$_POST['costo'.$i];
		$num = str_replace(".","",$num);
		$num = str_replace(",",".",$num);
		
    		$updateSQL2 = sprintf("UPDATE compra_productos SET  precio=%s, factura=%s, cantidad=%s WHERE factura=%s and producto=%s and transaccion!='ELIMINADO POR MODIFICACION'",
                       GetSQLValueString($num, "double"),
					   GetSQLValueString($_POST['num_fac'], "text"),
					   GetSQLValueString($_POST["Cantidad".$i], "int"),
                       GetSQLValueString($_POST['factura'], "int"),
					   GetSQLValueString($_POST["id".$i], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result2 = mysql_query($updateSQL2, $conexion) or die(mysql_error());
			
			
//actualizamos el almacen
	$insertSQL3 = sprintf("UPDATE almacen SET factura_compra=%s, cantida=%s, transaccion=%s where id_producto=%s and factura_compra=%s and transaccion!='ELIMINADO POR MODIFICACION'",
                       
                       GetSQLValueString($_POST['num_fac'], "text"),
                       GetSQLValueString($_POST["Cantidad".$i], "int"),
                       GetSQLValueString("COMPRA-MODIFICADA", "text"),
					   GetSQLValueString($_POST["id".$i], "int"),
					   GetSQLValueString($_POST['factura'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result3 = mysql_query($insertSQL3, $conexion) or die(mysql_error());
//fin de la actualiacion de productos




		}//fin del else
		$i++;	
	 }//fin del while

 //---------------------
 

	if($Result1==1 and $Result2==1 and $Result2==3){
  echo "<script type=\"text/javascript\">alert ('ACTUALIZACION EXITOSA');  location.href='modificar_compras.php?factura=$_POST[num_fac]' </script>";
  }else{
  echo "<script type=\"text/javascript\">alert ('Ocurrio un Error');  location.href='modificar_compras.php?factura=$_POST[num_fac]' </script>";
  exit;
  }
  
}

$nunFac=$_GET['factura'];

mysql_select_db($database_conexion, $conexion);
$query_compras = "SELECT * FROM compras where num_fac='$nunFac'";
$compras = mysql_query($query_compras, $conexion) or die(mysql_error());
$row_compras = mysql_fetch_assoc($compras);
$totalRows_compras = mysql_num_rows($compras);

mysql_select_db($database_conexion, $conexion);
$query_proveedor = "SELECT * FROM proveedor where rif='$row_compras[proveedor]'";
$proveedor = mysql_query($query_proveedor, $conexion) or die(mysql_error());
$row_proveedor = mysql_fetch_assoc($proveedor);
$totalRows_proveedor = mysql_num_rows($proveedor);

mysql_select_db($database_conexion, $conexion);
$query_proveedor2 = "SELECT * FROM proveedor where rif='$row_compras[fiador]'";
$proveedor2 = mysql_query($query_proveedor2, $conexion) or die(mysql_error());
$row_proveedor2 = mysql_fetch_assoc($proveedor2);
$totalRows_proveedor2 = mysql_num_rows($proveedor2);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
<script language="javascript">

function activarFecha(){
	
	if(document.getElementById('rif2').value){
		document.form1.fecha2.value="<?=date("Y/m/d");?>";
		document.form1.botonfecha.disabled=false;
	}
	
}

function borrar(){
	
	document.getElementById('rif2').value="";
	document.getElementById('proveedor2').value="";
	document.form1.fecha2.value="";
	document.form1.botonfecha.disabled=true;
	
}
function validar(){
	
		if(document.form1.num_fac.value!=""){
			 var filtro = /^(\d)+$/i;
		      if (!filtro.test(document.getElementById('num_fac').value)){
				alert('EL NUMERO DE FACTURA SOLO DEBE CONTENER NUMEROS');
				return false;
		   		}
				}
				
		if(document.form1.num_fac.value==""){
						alert("Debe Ingresar un Numero de Factura");
						return false;
				}		
		if(document.form1.rif.value==""){
						alert("Debe Seleccionar un  Proveedor");
						return false;
				}
		
		
		
			
}



function sumaTotal(nro) {
	
var costo=0;
var cantida=0;

cantida=document.getElementById("Cantidad" + nro).value
costo=document.getElementById("costo" + nro).value;

costo = costo.split('.').join('');	
costo =costo.split(',').join('.');

if(cantida<=0){
	alert('La Cantidad debe ser Mayor a Cero');
	document.getElementById("Cantidad" + nro).value="";
	return false;
	
}
if(costo<=0){
	alert('Introduzca el Precio  y Luego la Cantidad');
	document.getElementById("Cantidad" + nro).value="";
	return false;
	
}
var cantidad=document.getElementById("Cantidad" + nro).value;
//var costo=document.getElementById("costo" + nro).value.split(',').join('.' );
var total=cantidad * costo;

	var numero = new Number(setNumero( total));
	if (numero == 0)  total = "0,00";
	else  var numero2 = setNumeroFormato( total, 2, ".", ",");

document.getElementById("total" + nro).value = numero2;


}


function sumaTotal2(nro) {
var costo=0;
var cantida=0;

cantida=document.getElementById("Cantidad" + nro).value
costo=document.getElementById("costo" + nro).value;	

costo = costo.split('.').join('');	
costo =costo.split(',').join('.');


var cantidad=document.getElementById("Cantidad" + nro).value;
//var costo=document.getElementById("costo" + nro).value.split(',').join('.' );
var total=cantidad * costo;

	 total = new Number(setNumero(total));
	if (total == 0)  total = "0,00";
	else  var total2 = setNumeroFormato(total, 2, ".", ",");

document.getElementById("total" + nro).value =total;

}



//funcion que se activa con el clik del boton para calcular totales finales
$(function() {
$("#addAll1").click(function() {
var add = 0;
var iva= 0;
var subtotal= 0;
$(".amt").each(function() {
		
var subtotal1 = $(this).val().split('.').join('');
	
subtotal +=Number(subtotal1.split(',').join('.'));
iva = (subtotal* 12)/100;
total = subtotal + iva;

});

var total2= setNumeroFormato(total, 2, ".", ",");
var iva2= setNumeroFormato(iva, 2, ".", ",");
var subtotal2= setNumeroFormato(subtotal, 2, ".", ",");

document.getElementById('total').value=total;
document.getElementById('iva').value= iva;
document.getElementById('subtotal').value= subtotal;
if(document.getElementById('can_detalles').value==0){document.getElementById('total').value=0; total=0;}
$("#para1").text("Sub Total: " + subtotal2);
$("#para2").text("IVA: " + iva2 );
$("#para3").text("Total a Pagar: " + total2);
});
});
//////////////////////////

//funcion que se activa con el clik del boton para calcular totales
$(function() {
$("#addAll2").click(function() {
var add = 0;
var iva= 0;
var subtotal= 0;
$(".amt").each(function() {
		
var subtotal1 = $(this).val().split('.').join('');
	
subtotal +=Number(subtotal1.split(',').join('.'));
iva = (subtotal* 12)/100;
total = subtotal + iva;

});

var total2= setNumeroFormato(total, 2, ".", ",");
var iva2= setNumeroFormato(iva, 2, ".", ",");
var subtotal2= setNumeroFormato(subtotal, 2, ".", ",");

document.getElementById('total').value=total;
document.getElementById('iva').value= iva;
document.getElementById('subtotal').value= subtotal;
if(document.getElementById('can_detalles').value==0){document.getElementById('total').value=0; total=0;}
$("#para1").text("Sub Total: " + subtotal2);
$("#para2").text("IVA: " + iva2 );
$("#para3").text("Total a Pagar: " + total2);
});
});



///////////////////////////////////////////
function redondeo2decimales(numero) {
var original = parseFloat(numero);
var result = Math.round(original * 100) / 100;
return result;
}


function totales_IVA(){
var add = 0;
var iva= 0;
var subtotal= 0;
$(".amt").each(function() {
		
var subtotal1 = $(this).val().split('.').join('');
	
subtotal +=Number(subtotal1.split(',').join('.'));
iva = (subtotal* 12)/100;
total = subtotal + iva;

});

var total2= setNumeroFormato(total, 2, ".", ",");
var iva2= setNumeroFormato(iva, 2, ".", ",");
var subtotal2= setNumeroFormato(subtotal, 2, ".", ",");

document.getElementById('total').value=total;
document.getElementById('iva').value= iva;
document.getElementById('subtotal').value= subtotal;
if(document.getElementById('can_detalles').value==0){document.getElementById('total').value=0; total=0;}
$("#para1").text("Sub Total: " + subtotal2);
$("#para2").text("IVA: " + iva2 );
$("#para3").text("Total a Pagar: " + total2);
}
</script>
<body onload="totales_IVA()">
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return validar()">
<input type="hidden" name="sel_detalles" id="sel_detalles" />
  <table align="left" border="0" cellspacing="0">
    <tbody>
      <tr>
        <td width="759"><table border="0" cellpadding="0" cellspacing="0" width="742">
          <tbody>
            <tr>
              <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
              <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita">Modificar Compras</span></td>
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
                    <td align="center" width="140"><table cellpadding="3" cellspacing="0" width="689">
                      <tbody>
                        <tr>
                          <td width="140" align="right" valign="baseline" nowrap="nowrap">Fecha de Compras:</td>
                          <td width="217" valign="baseline"><input name="fecha" type="text" id="fecha" value="<?php echo $row_compras['fecha']; ?>" size="20" maxlength="10" readonly="readonly" />
                            <button type="submit" id="cal-button-1" title="Clic Para Escoger la fecha">Fecha</button>
                            <script type="text/javascript">
							Calendar.setup({
							  inputField    : "fecha",
							  ifFormat   : "%Y-%m-%d",
							  button        : "cal-button-1",
							  align         : "Tr"
							});
						  </script></td>
                          <td width="49" align="right" valign="baseline" nowrap="nowrap">Factura:</td>
                          <td width="276" valign="baseline"><input type="text" name="num_fac" id="num_fac" value="<?php echo $row_compras['num_fac']; ?>" size="32" /></td>
                        </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap">Fecha Limite de Pago:</td>
                          <td valign="baseline"><input name="fecha2"  type="text" id="fecha2" size="20" maxlength="10" value="<?php echo $row_compras['fecha_limite']; ?>" readonly="readonly" />
                            <button type="submit" disabled="disabled" id="botonfecha" name="botonfecha" title="Clic Para Escoger la fecha">Fecha</button>
                            <script type="text/javascript">
							Calendar.setup({
							  inputField    : "fecha2",
							  ifFormat   : "%Y-%m-%d",
							  button        : "botonfecha",
							  align         : "Tr"
							});
						    </script></td>
                          <td align="left">&nbsp;</td>
                          <td align="left"></td>
                        </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap">Proveedor:</td>
                          <td colspan="3" valign="baseline" class="gallery clearfix"><input name="rif" type="text" id="rif" value="<?php echo $row_compras['proveedor']; ?>" size="17" maxlength="12" readonly="readonly" /><input name="proveedor" id="proveedor" value="<?php echo $row_proveedor['nombre']; ?>" readonly="readonly" type="text" size="70" maxlength="46" />
                            <a  href="listaproveedores.php?iframe=true&amp;width=950&amp;height=525" target="_blank" rel="prettyPhoto[iframe1]" ><img src="imagenes/f_boton.png" alt="" width="20" style="cursor:pointer;" title="Seleccionar" align="absbottom" /></a>
                            <input type="hidden" name="TipoClasificacion" id="FlagCompras" value="C" /></td>
                        </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap"><span class="gallery clearfix">
                            <input type="button" name="borrarf" class="btLista" value="Borrar Fiador" onclick="borrar();"  />
                          </span>Fiador:</td>
                          <td colspan="3" valign="baseline" class="gallery clearfix"><input value="<?php echo $row_compras['fiador']; ?>" name="rif2" id="rif2" readonly="readonly" type="text" size="17" maxlength="12" onclick="activarFecha()"  /><input name="proveedor2" id="proveedor2" readonly="readonly" type="text" size="70" maxlength="46" value="<?php echo $row_proveedor2['nombre']; ?>" />
                            <a  href="listadoFiadores.php?iframe=true&amp;width=950&amp;height=525" target="_blank" rel="prettyPhoto[iframe1]" ><img src="imagenes/f_boton.png" alt="" width="20" style="cursor:pointer;" title="Seleccionar" align="absbottom" /></a>
                            <input type="hidden" name="FlagCompras" id="FlagCompras2" value="C" /></td>
                        </tr>
                        <tr>
                          <td colspan="4" align="center" class="negrita"><table width="699" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <th scope="col"><div id="para1" class="negrita"/>Sub Total: <?=number_format($row_compras['subtotal'],"2",",",".");?></div></th>
                              <th scope="col"><div id="para2" class="negrita"/>IVA: <?=number_format($row_compras['iva'],"2",",",".");?></div></th>
                              <th scope="col"><div id="para3" class="negrita"/>
                              Total a Pagar: <?=number_format($row_compras['total'],"2",",",".")?></div></th>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td colspan="4" align="center" background="imagenes/v_back_sup.jpg" class="negrita">Productos</td>
                        </tr>
                        <tr>
                          <td class="tituloDOSE_2" align="left"><input id="addAll2" type="button" value="CALCULAR TOTAL" /></td>
                          <td valign="baseline">&nbsp;</td>
                          <td align="right" valign="baseline" nowrap="nowrap">&nbsp;</td>
                          <td valign="baseline" align="right"  class="gallery clearfix"><a id="aItem" href="listado_productos.php?filtrar=default&amp;ventana=requerimiento_detalles_insertar&amp;iframe=true&amp;width=950&amp;height=525" rel="prettyPhoto[iframe3]" style="display:none;"></a>
                            <input type="button" class="btLista" value="Producto" id="btItem" onclick="document.getElementById('aItem').click();"  />
                            <input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'detalles');"  /></td>
                        </tr>
                        <tr>
                          <td colspan="4" >
                          <table width="700" align="center" class="tblLista">
                            <thead>
                              <tr>
                                <th width="24">#</th>
                                <th width="79">Codigo</th>
                                <th width="281" >Descripcion</th>
                                <th  width="131">Cantidad</th>
                                <th  width="131">Precio Unitario</th>
                                <th  width="131">Total</th>
                              </tr>
                            </thead>
             <tbody id="lista_detalles" >
             <?php
			
			 //productos comprados
             $sql = "SELECT * FROM compra_productos where factura='$nunFac' and transaccion!='ELIMINADO POR MODIFICACION'";
			 
			$query = mysql_query($sql,$conexion) or die ($sql.mysql_error());
			$rows_lista = mysql_num_rows($query);
			$nrodetalle=$rows_lista;
			//
			$nrodetalles = 0;
			while ($field = mysql_fetch_array($query)) {
			$nrodetalles++;
				//detalles productos
				$sql2 = "SELECT * FROM productos where id_productos='$field[producto]' "; 
				$query2 = mysql_query($sql2,$conexion) or die ($sql2.mysql_error());
				$rows_lista2 = mysql_num_rows($query2);
				$field2 = mysql_fetch_array($query2);
				$nrodetalle=$i;
				//
				
				//precio con ,
				$formato = $field["precio"];
				$formato = str_replace(",","",$formato);
				$formato = str_replace(".",",",$formato);
				
				//precio total con . y ,
				
				$total_producto=$field["precio"]*$field["cantidad"];
				$total_producto2=number_format($total_producto,"2",",",".");
				
			
				?>
                 <tr  class="trListaBody" onclick="mClk(this, 'sel_detalles');" id="detalles_<?=$nrodetalles?>" >
                                <th width="24" align="center"><?=$nrodetalles?></th>
                                <td width="79" align="center"><?=$field2["id_productos"]?><input type="hidden" name="id<?=$nrodetalles?>" class="cell2" style="text-align:center;" value="<?=$field["producto"]?>"/> </td>
                                <td width="281" ><textarea name="nombre<?=$nrodetalles?>" disabled="disabled" style="height:30px;" class="cell" onBlur="this.style.height='30px';" onFocus="this.style.height='60px';"  ><?=$field2["nombre"]?></textarea></td>
                                <td  width="131"><input type="text" name="Cantidad<?=$nrodetalles?>" id="Cantidad<?=$nrodetalles?>" onchange="sumaTotal(<?=$nrodetalles?>)" class="cell" style="text-align:right; font-weight:bold;" value="<?=$field["cantidad"]?>" /></td>
                                <td  width="131"><input type="text" name="costo<?=$nrodetalles?>" id="costo<?=$nrodetalles?>" class="cell" style="text-align:right; font-weight:bold;" value="<?=$formato;?>" onchange="sumaTotal2(<?=$nrodetalles?>)"  onFocus="numeroFocus(this);" onBlur="numeroBlur(this);" /></td>
                                <td  width="131"><input type="text" name="total<?=$nrodetalles?>" id="total<?=$nrodetalles?>" disabled="disabled"  class="amt" style="text-align:right; font-weight:bold; background-color:transparent; border:none; width:100%;" value="<?=$total_producto2?>"   />
                             </tr>
                             
                     <input type="hidden" name="veri_prod<?=$nrodetalles?>" value="<?=$field["producto"]?>" />
                             <? 
							
							 }//fin del while?>
                             
                            </tbody>
                          </table>
                          </td>
                        </tr>
                        <tr >
                          <td colspan="4"></td>
                        </tr>
                        <tr>
                          <td colspan="4" align="center"><input id="addAll1" type="submit" value="MODIFICAR COMPRAS" /></td>
                        </tr>
                      
                    </table></td>
                  </tr>
                  <tr>
                    <td align="right" width="80%"></td>
                  </tr>
               
              </table></td>
              <td background="imagenes/v_back_der.gif" width="12">&nbsp;</td>
            </tr>
            <tr>
              <td align="left" height="1" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
              <td background="imagenes/v_back_inf.gif" height="1" width="717"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
              <td align="right" height="1" width="12"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
            </tr>
          </tbody>
        </table></td>
      </tr>
      <tr> </tr>
    </tbody>
  </table>
  <p>&nbsp;</p>
  <input type="hidden" name="nro_detalles" id="nro_detalles" value="<?=$nrodetalles?>" />
<input type="hidden" name="can_detalles" id="can_detalles" value="<?=$nrodetalles?>" />
 <input type="hidden" name="registrados" value="<?php echo $rows_lista; ?>" /> 
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id_compras" value="<?php echo $row_compras['id_compras']; ?>" />
  <input type="hidden" name="factura" value=" <?php echo $row_compras['num_fac']; ?>" />
   <input type="hidden" name="can_productos" value=" <?php echo $rows_lista; ?>" />
   <input type="hidden" name="total" id="total" value=""  />
<input type="hidden" name="iva" id="iva" value=""  />
<input type="hidden" name="subtotal" id="subtotal" value=""  />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($compras);

mysql_free_result($proveedor);
?>
