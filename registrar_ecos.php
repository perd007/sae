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
		/*
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
 */
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
  $insertSQL = sprintf("INSERT INTO clientes (nombres, telefono, cedula, direccion, fecha_nac, tipo) VALUES (%s, %s, %s, %s, %s, 'natural')",
                       GetSQLValueString($_POST['nombres'], "text"),
                       GetSQLValueString($_POST['telefono'], "text"),
                       GetSQLValueString($_POST['cedula'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
					   GetSQLValueString($_POST['fecha_nac'], "text"));


  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  
  }// fin del if existe
  //registramos el eco
  $insertSQL2 = sprintf("INSERT INTO ecos (fecha, tipo_eco, diagnostico, moneda, monto,cliente) VALUES (%s, %s, %s, %s, %s,%s)",
                       GetSQLValueString($_POST['fecha'], "text"),
                       GetSQLValueString($_POST['tipo_eco'], "text"),
                       GetSQLValueString($_POST['diagnostico'], "text"),
                       GetSQLValueString($_POST['moneda'], "text"),
					   GetSQLValueString($_POST['monto'], "float"),
					   GetSQLValueString($_POST['cedula'], "text"));


  mysql_select_db($database_conexion, $conexion);
  $Result2 = mysql_query($insertSQL2, $conexion) or die(mysql_error());
		
		

  	//
	 }//fin del if que valida si el producto fue borrado
		
			
	
	
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


// funcion de ajax
function manda_al_servidor()
{
	 
//limpiamos campo
document.getElementById('cedula').value = "";
document.getElementById('nombres').value = "";
document.getElementById('telefono').value = "";
document.getElementById('direccion').value = "";
document.getElementById('tipo').value = "";
document.getElementById('fecha_nac').value = "";
//document.getElementById('fecha').value = "";
//document.getElementById('tipo_eco').value = "";
//document.getElementById('moneda').value = "";
//document.getElementById('monto').value = "";
   
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
	var fecha_nac=parametros2[9] + "-" + parametros2[10] + "-" + parametros2[11];
	//var fecha=parametros2[12] + "-" + parametros2[13] + "-" + parametros2[14];
	

	document.getElementById('cedula').value = parametros2[0];
	document.getElementById('nombres').value = parametros2[1];
	document.getElementById('telefono').value = parametros2[2];
	document.getElementById('direccion').value = parametros2[3];
	document.getElementById('tipo').value = parametros2[4];
	//document.getElementById('tipo_eco').value = parametros2[5];
	//document.getElementById('diagnostico').value = parametros2[6];
	//document.getElementById('moneda').value = parametros2[7];
	//document.getElementById('monto').value = parametros2[8];
	document.getElementById('fecha_nac').value = fecha_nac;
	//document.getElementById('fecha').value = fecha;
	
	if(parametros2[0]!=0){
	document.getElementById('existe').value = 1;
	}
	else{
	document.getElementById('existe').value = 0;
	}
  	

   }

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
      <td colspan="2" align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Pacientes Ecograficos</span></td>
      <td align="right" height="1" width="14"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
    </tr>
    <tr>
      <td width="13" height="140" rowspan="4" background="imagenes/v_back_izq.gif"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
      <td colspan="2" valign="top" class="tituloDOSE_2">Buscar por 
        
        Cedula:
        <input name="buscar" type="text" id="buscar" value="<?php echo $_POST['buscar']; ?>" maxlength="15" />
        <input  type="hidden" name="buscar2" id="buscar2"  />
        <input type="button" name="bt_enviar" onclick="manda_al_servidor();" id="bt_enviar" value="Buscar" />
        <input type="hidden" name="TipoClasificacion" id="FlagCompras" value="C" /></td>
      <td width="14" rowspan="4" background="imagenes/v_back_der.gif">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" valign="top" class="tituloDOSE_2">Fecha de Consulta:
        <input name="fecha" type="text" id="fecha" value="<?=date("Y/m/d");?>" size="20" maxlength="30" readonly="readonly" />
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
      <td colspan="2" valign="top" class="tituloDOSE_2">Fecha de Nacimiento:
        <input name="fecha_nac" type="text" id="fecha_nac" value="<?=date("Y/m/d");?>" size="20" maxlength="30" readonly="readonly" />
        <button type="submit" id="cal-button-2" title="Clic Para Escoger la fecha">Fecha</button>
        <script type="text/javascript">
							Calendar.setup({
							  inputField    : "fecha_nac",
							  ifFormat   : "%Y-%m-%d",
							  button        : "cal-button-2",
							  align         : "Tr"
							});
						  </script></td>
    </tr>
    <tr>
      <td class="tituloDOSE_2" valign="top" width="638">
        
        <table border="0" cellpadding="0" cellspacing="0" width="623">
          <tbody>
            <tr>
              <td width="13" align="left"  ></td>
              <td width="609"  height="1" class="tituloDOSE_2">
                
                <table border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td align="right"></td>
                      </tr>
                    <tr>
                      <td align="center" width="714"><table cellpadding="3" cellspacing="0" width="679">
                        <tbody>
                          <tr>
                            <td width="157"align="right" valign="baseline">Nombre:</td>
                            <td width="242" valign="baseline"><input name="nombres" type="text" id="nombres" value="<?php echo $row_clientes['nombres']; ?>"  size="50" maxlength="50" /></td>
                            <td width="109" align="right" valign="baseline" nowrap="nowrap">Cedula:</td>
                            <td width="145" valign="baseline"><input name="cedula" type="text" id="cedula" value="<?php echo $row_clientes['cedula']; ?>" size="32" maxlength="15" <?=$disable?>/>
                              </td>
                            </tr>
                          <tr>
                            <td align="right" valign="middle">Direccion</td>
                            <td colspan="3" valign="baseline"><textarea name="direccion" id="direccion"  cols="100" rows="4" <?=$disable?>><?php echo $row_clientes['direccion']; ?></textarea></td>
                            </tr>
                          <tr>
                            <td align="right" valign="baseline">Servicio Medico:</td>
                            <td valign="baseline"><select name="tipo_eco" id="tipo_eco" <?=$disable?>>
                              <option value="Abdominal" <?php if (!(strcmp("Abdominal", $row_ecos['tipo_eco']))) {echo "selected=\"selected\"";} ?>>Eco Abdominal</option>
                              <option value="Obstetrico1" <?php if (!(strcmp("Obstetrico1", $row_ecos['tipo_eco']))) {echo "selected=\"selected\"";} ?>>Eco Obstetrico</option>
                              <option value="Obstetrico2" <?php if (!(strcmp("Obstetrico2", $row_ecos['tipo_eco']))) {echo "selected=\"selected\"";} ?>>Eco Obstetrico2</option>
                              <option value="Gemelar" <?php if (!(strcmp("Gemelar", $row_ecos['tipo_eco']))) {echo "selected=\"selected\"";} ?>>Eco Gemelar</option>
                              <option value="Pelvico" <?php if (!(strcmp("Pelvico", $row_ecos['tipo_eco']))) {echo "selected=\"selected\"";} ?>>Eco Pelvico</option>
                              <option value="Partes_Blandas" <?php if (!(strcmp("Partes_Blandes", $row_eco['tipo_eco']))) {echo "selected=\"selected\"";} ?>>Partes_Blandas</option>
                              <option value="Mamario" <?php if (!(strcmp("Mamario", $row_ecos['tipo_eco']))) {echo "selected=\"selected\"";} ?>>Eco Mamario</option>
                              <option value="Citologia" <?php if (!(strcmp("Citologia", $row_ecos['tipo_eco']))) {echo "selected=\"selected\"";} ?>>Citologia</option>
                            </select></td>
                            <td align="right" valign="baseline" nowrap="nowrap">Telefono:</td>
                            <td valign="baseline"><input name="telefono" type="text" id="telefono" value="<?php echo $row_clientes['telefono']; ?>" size="32" maxlength="11" <?=$disable?>/></td>
                            </tr>
                          <tr>
                            <td align="right" valign="baseline" nowrap="nowrap">Forma de Pago:</td>
                            <td valign="baseline"><select name="moneda" id="moneda" <?=$disable?>>
                              <option value="Pesos" <?php if (!(strcmp("Pesos", $row_ecos['moneda']))) {echo "selected=\"selected\"";} ?>>Pesos</option>
                              <option value="Bolivares" <?php if (!(strcmp("Bolivares",$row_ecos['moneda']))) {echo "selected=\"selected\"";} ?>>Bolivares</option>
                              
                              <option value="Dolares" <?php if (!(strcmp("Dolares", $row_ecos['moneda']))) {echo "selected=\"selected\"";} ?>>Dolares</option>
                              </select></td>
                            <td align="right" valign="baseline">Monto:</td>
                            <td valign="baseline"><label for="tipo_eco">
                              <input name="monto" type="text" id="monto" value="<?php echo $row_ecos['monto']; ?>" size="32" maxlength="11" <?=$disable?>/>
                              </label></td>
                            </tr>
                          <tr>
                            <td class="tituloDOSE_2" align="left">&nbsp;</td>
                            <td colspan="3" valign="baseline">&nbsp;</td>
                            </tr>
                          <tr>
                            <td  colspan="4" align="center" class="tituloDOSE_2"><input type="submit" name="addAll2" id="addAll2" value="GUARDAR" onclick="return validarEnviar()"/></td>
                          </tr>
                          </tbody>
                        </table></td>
                      </tr>
                    <tr>
                      <td align="right" width="714"></td>
                      </tr>
                    </tbody>
                </table></td>
              <td width="1"></td>
              </tr>
            </tbody>
          </table>
        
  </td>
      <td class="tituloDOSE_2" valign="top" width="111">
        <p id="para1"  class="negrita"/>
        <p id="para2" class="negrita"/>
        <p id="para3" class="negrita"/>
        </td>
    </tr>
    <tr>
      <td align="left" height="12" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
      <td height="12" colspan="2" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
      <td align="right" height="12" width="14"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
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
          <input type="hidden" name="tipo" id="tipo" value=""  />
      
</p>
   

</form>
<p>&nbsp;</p>

</body>
</html>
<?php
mysql_free_result($clientes);

mysql_free_result($caja);
?>
