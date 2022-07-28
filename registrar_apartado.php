<?php require_once('Connections/conexion.php'); ?>
<?php 
//validar usuario

if($_COOKIE["val"]==true){
	if($_COOKIE["f"]!=1){
	echo "<script type=\"text/javascript\">alert ('Para realizar Apartados debe Poseer permisos de Facturacion');location.href='fondo.php' </script>";
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
//validamos que el producto no se encuentre regestrado mas de una vez
		
		for($i=1;$i<=$_POST["nrodetalle"];$i++){
			$x=0;
			$Prod_selec[$i]=$_POST["id".$i];
			
			for($j=1;$j<=$_POST["nrodetalle"];$j++){
				if($_POST["id".$j]>=1){
				if($Prod_selec[$i]==$_POST["id".$j]){ $x++; }
				if($x > 1){ echo "<script type=\"text/javascript\">alert ('No se puede repetir el producto ');  location.href='' </script>";
  exit;}}
				
					
			 }
		}		
/////////////////////////////////////////////////////////////////////
		
	//si el cliente no esta registrado lo insertamos en la tabla
		if($_POST['existe']==0){
  $insertSQL = sprintf("INSERT INTO clientes (nombres, telefono, cedula, direccion, tipo) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['nombres'], "text"),
                       GetSQLValueString($_POST['telefono'], "text"),
                       GetSQLValueString($_POST['cedula'], "int"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['tipo'], "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
		}// fin del if existe
		
		//creamos un correlativo
		mysql_select_db($database_conexion, $conexion);
		$query_ped = "SELECT *,MAX(id_apartado) FROM apartados";
		$ped = mysql_query($query_ped, $conexion) or die(mysql_error());
		$row_ped = mysql_fetch_assoc($ped);
		
		if($totalRows_ped = mysql_num_rows($ped)==0){ 
		$nun=1;
		$correlativo="NApartado".$nun;
		}else{
		$nun=$row_ped["MAX(id_apartado)"]+1;
		$correlativo="NApartado".$nun;	
		}
		
		//
	//////////////////////////////////////////////////////
	//procedemos  a insertar en la tabla pedidos
	$insert_pedido= sprintf("INSERT INTO apartados (fecha, id_cliente, correlativo, total, abono) VALUES (NOW(), %s,%s,  %s, %s)",
                       
                       GetSQLValueString($_POST['cedula'], "text"),
					   GetSQLValueString($correlativo, "text"),
					   GetSQLValueString($_POST['preciob'], "double"),
						GetSQLValueString($_POST['abonob'], "double"));

  mysql_select_db($database_conexion, $conexion);
  $Result2 = mysql_query($insert_pedido, $conexion) or die(mysql_error());
	
	/////////////////////////////////////////////////////
	
	
	//procedemos  a insertar en la tabla pedidos_productos
	 if($Result2){
		 
		  $i=1;
	 while($_POST["nrodetalle"]>=$i){ 
	
	
	 //instarmos compra
	 if($_POST["id".$i]>=1){

	
	$insert_pedido_p = sprintf("INSERT INTO productos_apartados ( id_producto, id_apartado, cantidad, precio) VALUES ( %s, %s, %s, %s)",
                       GetSQLValueString($_POST["id".$i], "int"),
                       GetSQLValueString($correlativo, "text"),
                       GetSQLValueString($_POST["Cantidad".$i], "int"),
					    GetSQLValueString($_POST["preciob"], "double"));

  mysql_select_db($database_conexion, $conexion);
  $Result3 = mysql_query($insert_pedido_p, $conexion) or die(mysql_error());
  	//
	 }//fin del if que valida si el producto fue borrado
		$i++;
			
	 }//fin del while que recorre los productos
	 }//fin del if de valadicacion de insercion del pedido
	 
	 if( $Result2==1 and $Result3==1){
 
 	echo "<script type=\"text/javascript\">alert ('APARTADO');   window.open('recibo-apartado.php?nombre=$_POST[nombre1]&cedula=$_POST[cedula]&precio=$_POST[preciob]&abono=$_POST[abonob]&cantidad=$_POST[Cantidad1]&marca=$_POST[marca1]&modelo=$_POST[modelo1]','ventana1','width=800,height=900,scrollbars=YES')</script>";	
 
 
  }else{
  echo "<script type=\"text/javascript\">alert ('Ocurrio un Error');  location.href='' </script>";
  exit;
  }
	 
	}//fin del if bus
	
	
}//de lo contrario=


if($_POST['buscar']!=""){
	
mysql_select_db($database_conexion, $conexion);
$query_clientes = "SELECT * FROM clientes where cedula='$_POST[buscar]'";
$clientes = mysql_query($query_clientes, $conexion) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

$chek=1;
if($totalRows_clientes>=1){
$disable="readonly='readonly'";
$valor=1;
$existe=1;
$valBuscar="";
}
else{
$disable="";  
$existe=0;
$valor=2;
$valBuscar="
if(chek!=1){


if(document.form1.cedula.value!=''){
			 var filtro = /^(\d)+$/i;
		      if (!filtro.test(document.getElementById('cedula').value)){
				alert('EL NUMERO DE CEDULA DEBE SER NUMERICO');
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

if(document.form1.direccion.value==''){
						alert('Debe Ingrsar una Direccion');
						return false;
}



";
}

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
//-------------------------------------------------------
/*
function sumaTotalApartados(nro) {
var precio=0;
var cantidad=0;
var abono=0;

cantidad=document.getElementById("Cantidad" + nro).value;
precio=document.getElementById("precio" + nro).value;
abono=document.getElementById("abono" + nro).value;

var restan=(cantidad * precio) - abono;
document.getElementById("restan" + nro).value = redondeo2decimales(restan);

}*/
function sumaTotalApartados(nro) {
var precio=0;
var cantidad=0;
var abono=0;

cantidad=document.getElementById("Cantidad" + nro).value;
precio=document.getElementById("precio" + nro).value;
abono=document.getElementById("abono" + nro).value;

precio = precio.split('.').join('');	
precio =precio.split(',').join('.');

abono =abono.split('.').join('');	
abono =abono.split(',').join('.');

var restan=(cantidad * precio) - abono;
document.getElementById("restan" + nro).value = setNumeroFormato(restan, 2, ".", ",");

document.getElementById("preciob").value=precio;
document.getElementById("abonob").value=abono;
}


//----------------------------------------------

function redondeo2decimales(numero) {
var original = parseFloat(numero);
var result = Math.round(original * 100) / 100;
return result;
}
//----------------------------------------------
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
      <td colspan="2" align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Sistema de Apartados</span></td>
      <td align="right" height="1" width="13"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr>
      <td width="13" height="140" rowspan="2" background="imagenes/v_back_izq.gif"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td colspan="2" valign="top" class="tituloDOSE_2">
     
        Cedula o Rif:
       
          <input name="buscar" type="text" id="buscar" value="<?php echo $_POST['buscar']; ?>" maxlength="8" />
          <input  type="hidden" name="buscar2" id="buscar2"  />
          <input type="submit" name="button" id="button" value="Buscar" onclick="return validarBuscar()"/>
          <input type="hidden" name="TipoClasificacion" id="FlagCompras" value="C" />
          
    </td>
      <td width="13" rowspan="2" background="imagenes/v_back_der.gif"><img src="imagenes/cargar_productos.php" alt="1" height="2" width="13" /></td>
    </tr>
    <tr>
      <td class="tituloDOSE_2" valign="top" width="375">
        <? if($chek==1){?>
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
                    <td width="63"align="right" valign="baseline">Nombre:</td>
                    <td width="227" valign="baseline"><input name="nombres" type="text" id="nombres" value="<?php echo $row_clientes['nombres']; ?>"  size="32" maxlength="20" <?=$disable?>/></td>
                    <td width="60" align="right" valign="baseline" nowrap="nowrap">Cedula o Rif:</td>
                    <td width="192" valign="baseline"><input name="cedula" type="text" id="cedula" value="<?php echo $row_clientes['cedula']; ?>" size="32" maxlength="8" <?=$disable?>/>
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
                      <textarea name="direccion" cols="40" rows="4" <?=$disable?>><?php echo $row_clientes['direccion']; ?></textarea>
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
    <? }?>
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
      <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita"> Productos a Apartar</span></td>
      <td align="right" height="1" width="12"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr>
      <td width="13" height="140" rowspan="2" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td valign="baseline" align="right"  class="gallery clearfix">
      <a id="aItem" href="listado_productos_apartados.php?filtrar=default&ventana=requerimiento_detalles_insertar&iframe=true&width=950&height=525" rel="prettyPhoto[iframe3]" style="display:none;"></a>
        <input type="button" class="btLista" value="Producto" id="btItem" onclick="document.getElementById('aItem').click();"  />
        <input type="button" class="btLista" value="Borrar" onclick="quitarLinea(this, 'detalles');"  />
        
        </td>
      <td width="12" rowspan="2" background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr style=" color: #FFF;">
      <td valign="top">
      <table width="700" align="center" class="tblLista">
        <thead>
          <tr align="center" >
       <th width="154" scope="col">Nombre</th>
        <th width="84" scope="col">Marca</th>
        <th width="131" scope="col">Modelo</th>
        <th width="71" scope="col">Cantidad</th>
        <th width="54" scope="col">Precio</th>
        <th width="86" scope="col">Abono</th>
         <th width="88" scope="col">Restan</th>
          </tr>
        </thead>
        
        <tbody id="lista_detalles">
        
        </tbody>
      </table>
      </td>
    </tr>
    <tr>
      <td height="19" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
      <td align="center" valign="top"><input type="submit" name="addAll2" id="addAll2" value="PROCESAR" onclick="return validarEnviar()"/>
      <? //<input id="addAll" type="button" value="CALCULAR TOTAL" /> ?>

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
        <input type="hidden" name="preciob" id="preciob" value=""  />
     <input type="hidden" name="abonob" id="abonob" value=""  />
</p>
   

</form>
<p>&nbsp;</p>

</body>
</html>
<?php
mysql_free_result($clientes);

mysql_free_result($caja);
?>
