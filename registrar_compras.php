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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
			
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
			if($_POST['costo'.$i]=="" or $_POST['costo'.$i]<=0){
				 echo "<script type=\"text/javascript\">alert ('El costo debe ser mayor a Cero');  location.href='' </script>";
			}
			}
			$i++;
 }//fin del while
 
			//probamos los valores de la etiques para saber quien manda los diferentes valores de los productos
			/*
			echo "el numero de detalle: ".$_POST["nrodetalle"];
			//echo "<br> el cantidad de detalle: ".$_POST["candetalle"];
			echo "<br> la etiqueta can_detalles: ".$_POST["can_detalles"];
	
			$i=1;
			while($_POST["nrodetalle"]>=$i){
				if($_POST["id".$i]>=1){
				echo "<br> los id de los productos: ".$_POST["id".$i];
				echo "<br> Producto:  ".$_POST['nombre'.$i].", cantidad:  ".$_POST["Cantidad".$i];
				}
				$i++;
			}
			exit;
			
			//imprimir productos para verlos
			while($_POST["nrodetalle"]>=$i){
				echo "<br> Producto:  ".$_POST['nombre'.$i].", cantidad:  ".$_POST["Cantidad".$i];
				$i++;
			}
			exit;
			
			*/
			
			
			
	//validamos que la factura no haya sido introducida con el mismo proveedor
			mysql_select_db($database_conexion, $conexion);
			$query_Recordset1 = "SELECT * FROM compras where num_fac='$_POST[num_fac]' and proveedor='$_POST[rif]'";
			$Recordset1 = mysql_query($query_Recordset1, $conexion) or die(mysql_error());
			$row_Recordset1 = mysql_fetch_assoc($Recordset1);
			$totalRows_Recordset1 = mysql_num_rows($Recordset1);

	
	if($totalRows_Recordset1>=1){
	  echo "<script type=\"text/javascript\">alert ('Este numero de factura ya existe para este proveedor');  location.href='' </script>";
  exit;
	}
	//
	
	
	 //validamos que el producto no se encuentre regestrado mas de una vez
		
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
/////////////////////////////////////////////////////////////

			//

//insertamos los datos de la compra

if($_POST['rif2']==""){	
$estatus=" ";
}
if($_POST['rif2']!=""){	
$estatus="PENDIENTE";
}

		
	
  $insertSQL = sprintf("INSERT INTO compras ( fecha, num_fac, proveedor, fecha_limite, fiador, estatus, subtotal, iva, total) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['fecha'], "date"),
                       GetSQLValueString($_POST['num_fac'], "text"),
                       GetSQLValueString($_POST['rif'], "text"),
					   GetSQLValueString($_POST['fecha2'], "date"),
                       GetSQLValueString($_POST['rif2'], "text"),
					    GetSQLValueString($estatus, "text"),
						GetSQLValueString($_POST['subtotal'], "float"),
						GetSQLValueString($_POST['iva'], "float"),
						GetSQLValueString($_POST['total'], "float"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  
 //insertamos los productos
  if($Result1){

	  $i=1;
	 while($_POST["nrodetalle"]>=$i){ 
	
	
	 //instarmos compra
	 if($_POST["id".$i]>=1){
		
		$num=$_POST['costo'.$i];
		$num = str_replace(".","",$num);
		$num = str_replace(",",".",$num);
	

    		$insertSQL2 = sprintf("INSERT INTO compra_productos (producto, precio, factura, cantidad, transaccion) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST["id".$i], "int"),
					   GetSQLValueString($num, "double"),
					   GetSQLValueString($_POST['num_fac'], "text"),
					   GetSQLValueString($_POST["Cantidad".$i], "int"),
					   GetSQLValueString('INGRESO POR REGISTRO', "text"));

  			mysql_select_db($database_conexion, $conexion);
 	 		$Result2 = mysql_query($insertSQL2, $conexion) or die(mysql_error());
			
			
	//cargamos el almacen
	$insertSQL3 = sprintf("INSERT INTO almacen (id_producto, factura_compra, cantida, transaccion) VALUES (%s, %s, %s, %s)",
                       
                       GetSQLValueString($_POST["id".$i], "int"),
                       GetSQLValueString($_POST['num_fac'], "text"),
                       GetSQLValueString($_POST["Cantidad".$i], "int"),
                       GetSQLValueString("COMPRA", "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result3 = mysql_query($insertSQL3, $conexion) or die(mysql_error());
	//
	 }
		$i++;
			
	 }
  
  }//fin del if
 //---------------------
 

	if($Result1==1 and $Result2==1 and $Result2==3){
  echo "<script type=\"text/javascript\">alert ('Carga Exitosa');  location.href='' </script>";
  }else{
  echo "<script type=\"text/javascript\">alert ('Ocurrio un Error');  location.href='' </script>";
  exit;
  }
  
  
}


$desactivar="disabled='disabled'";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
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
<script language="javascript">


function activarFecha(){
	
	if(document.getElementById('rif2').value){
		document.form1.fecha2.value="<?=date("Y/m/d");?>";
		document.form1.botonfecha.disabled=false;
	}
	
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
				if(document.form1.can_detalles.value==0){
						alert("Debe Seleccionar al menos un Producto");
						return false;
			}
		
		
		
			
}




function sumaTotal(nro) {
var costo=0;
var cantida=0;
var total=0;

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

	var numero = new Number(setNumero(total));
	if (numero == 0)  total = "0,00";
	else  var numero2 = setNumeroFormato(total, 2, ".", ",");

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

document.getElementById('total').value=total2;
document.getElementById('iva').value= iva2;
document.getElementById('subtotal').value= subtotal2;
if(document.getElementById('can_detalles').value==0){document.getElementById('total').value=0; total=0;}
$("#para1").text("Sub Total: " + subtotal2);
$("#para2").text("IVA: " + iva2 );
$("#para3").text("Total a Pagar: " + total2);
});
});
///////////////////////////////////////////
//funcion para redondeod de decimales
function redondeo2decimales(numero) {
var original = parseFloat(numero);
var result = Math.round(original * 100) / 100;
return result;
}

</script>
<body>

<!-- pretty -->
<span class="gallery clearfix"></span>
<form action="<?php echo $editFormAction; ?>" method="post" onsubmit="return validar()" name="form1" id="form1">
<input type="hidden" name="sel_detalles" id="sel_detalles" />
  <input type="hidden" name="MM_insert" value="form1" />



<table align="left" border="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="759"><table border="0" cellpadding="0" cellspacing="0" width="742">
        <tbody>
          <tr>
            <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
            <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita">Registro de Compras</span></td>
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
                        <td width="119" align="right" valign="baseline" nowrap="nowrap">Fecha de Compras:</td>
                        <td width="211" valign="baseline"><input name="fecha" type="text" id="fecha" value="<?=date("Y/m/d");?>" size="20" maxlength="10" readonly="readonly" />
        <button type="submit" id="cal-button-1" title="Clic Para Escoger la fecha">Fecha</button>
      <script type="text/javascript">
							Calendar.setup({
							  inputField    : "fecha",
							  ifFormat   : "%Y-%m-%d",
							  button        : "cal-button-1",
							  align         : "Tr"
							});
						  </script></td>
                        <td width="55" align="right" valign="baseline" nowrap="nowrap">Factura:</td>
                        <td width="297" valign="baseline"><input type="text" name="num_fac" id="num_fac" value="" size="32" /></td>
                      </tr>
                      <tr>
                        <td align="right" valign="baseline" nowrap="nowrap">Fecha Limite de Pago:</td>
                        <td valign="baseline"><input name="fecha2"  type="text" id="fecha2" value="" size="20" maxlength="10" readonly="readonly" />
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
                        <td align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right" valign="baseline" nowrap="nowrap">Proveedor:</td>
                        <td colspan="3" valign="baseline" class="gallery clearfix">
                          <input name="rif" id="rif" readonly="readonly" type="text" size="17" maxlength="12" /><input name="proveedor" id="proveedor" readonly="readonly" type="text" size="70" maxlength="46" />
                          <a  href="listaproveedores.php?iframe=true&amp;width=950&amp;height=525" target="_blank" rel="prettyPhoto[iframe1]" ><img src="imagenes/f_boton.png" alt="" width="20" style="cursor:pointer;" title="Seleccionar" align="absbottom" /></a> 
                         <input type="hidden" name="TipoClasificacion" id="FlagCompras" value="C" /></td>
                      </tr>
                      <tr>
                        <td align="right" valign="baseline" nowrap="nowrap">Fiador:</td>
                        <td colspan="3" valign="baseline" class="gallery clearfix"><input name="rif2" id="rif2" readonly="readonly" type="text" size="17" maxlength="12" onclick="activarFecha()"  /><input name="proveedor2" id="proveedor2" readonly="readonly" type="text" size="70" maxlength="46" />
                          <a  href="listadoFiadores.php?iframe=true&amp;width=950&amp;height=525" target="_blank" rel="prettyPhoto[iframe1]" ><img src="imagenes/f_boton.png" alt="" width="20" style="cursor:pointer;" title="Seleccionar" align="absbottom" /></a>
                          <input type="hidden" name="FlagCompras" id="FlagCompras2" value="C" /></td>
                      </tr>
                      <tr>
                        <td colspan="4" align="center"  class="negrita"><table width="699" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <th scope="col"><div id="para1"  class="negrita"/></th>
                            <th scope="col"><div id="para2" class="negrita"/></th>
                            <th scope="col"><div id="para3" class="negrita"/></th>
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
                        <td valign="baseline" align="right"  class="gallery clearfix">
                        <a id="aItem" href="listado_productos.php?filtrar=default&ventana=requerimiento_detalles_insertar&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]" style="display:none;"></a>
                          <input type="button" class="btLista" value="Producto" id="btItem" onclick="document.getElementById('aItem').click();"  />
                          <input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'detalles');"  />
                         
                       </td>
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
    
    			<tbody id="lista_detalles">
		

   				 </tbody>
</table>
                        
                        </td>
                        </tr>
                      <tr >
                      <td colspan="4">
                   	 
                       </td>
                        </tr>
                      <tr>
                        <td colspan="4" align="center"><input type="submit" id="addAll1" value="CARGAR COMPRAS" /></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td align="right" width="80%"></td>
                </tr>
              </tbody>
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
<p>

<input type="hidden" id="nro_detalles" value="<?=$nrodetalles?>" />
<input type="hidden" name="can_detalles" id="can_detalles" value="<?=$nrodetalles?>" />
<input type="hidden" name="total" id="total" value=""  />
<input type="hidden" name="iva" id="iva" value=""  />
<input type="hidden" name="subtotal" id="subtotal" value=""  />

</p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
  

</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($consulta);
?>
