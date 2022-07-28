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
	
	
	
	if ($_POST['periodo']=="eventual" or $_POST['periodo']==""){ $periodo="no aplica"; } else $periodo=$_POST['periodo'];
	
		$num=$_POST['monto'];
		$num = str_replace(".","",$num);
		$num = str_replace(",",".",$num);
		
  $insertSQL = sprintf("INSERT INTO gastos (monto, descripcion, tipo, fecha, periodo, clasificacion) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($num, "double"),
                       GetSQLValueString($_POST['descripcion'], "text"),
                       GetSQLValueString($_POST['tipo'], "text"),
                       GetSQLValueString($_POST['fecha'], "date"),
                       GetSQLValueString($periodo, "text"),
					   GetSQLValueString($_POST['clasificacion'], "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  if($Result1){
  //echo "<script type=\"text/javascript\">alert ('Registro Exitoso');  location.href='' </script>";
  }else{
  echo "<script type=\"text/javascript\">alert ('Ocurrio un Error');  location.href='' </script>";
  exit;
  }
}

mysql_select_db($database_conexion, $conexion);
$query_gastos = "SELECT * FROM gastos";
$gastos = mysql_query($query_gastos, $conexion) or die(mysql_error());
$row_gastos = mysql_fetch_assoc($gastos);
$totalRows_gastos = mysql_num_rows($gastos);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
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
<script language="javascript">
function activarPeriodo(){
		
	if(document.getElementById('tipo1').checked){
		document.form1.periodo.disabled=true;
		document.getElementById('tipo').value="eventual";
	}
	if(document.getElementById('tipo2').checked){
		document.form1.periodo.disabled=false;
		document.getElementById('tipo').value="periodico";
	}
}

function validar(){
	
		
				
		if(document.form1.fecha.value==""){
						alert("Debe Ingresar una Fecha");
						return false;
				}		
		if(document.form1.descripcion.value==""){
						alert("Debe ingresar una Descripcion");
						return false;
				}
		if(document.form1.monto.value==0){
						alert("El monto debe ser mayor a Cero");
						return false;
			}
		
		if(document.form1.monto.value==""){
						alert("Debe ingresra un monto");
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
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return validar()">
  <table border="0" cellpadding="0" cellspacing="0" width="668">
    <tbody>
      <tr>
        <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
        <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita">Registros de Otros Gastos</span></td>
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
              <td align="center" width="642"><table  width="643"  cellpadding="3" cellspacing="0">
                <tbody>
                  <tr>
                    <td width="79" align="right" valign="baseline" nowrap="nowrap"><strong>Tipo:</strong></td>
                    <td colspan="3" valign="baseline"><label for="radio"><strong>Eventual</strong>
                      <input type="radio" name="tiposeleccion" id="tipo1" value="eventual"  checked="checked" onclick="activarPeriodo()"  />
                    </label>
                      <strong>
                      <label for="radio">Periodico</label>
                      </strong>
                      <label for="radio">
                        <input type="radio" name="tiposeleccion" id="tipo2" value="periodico" onclick="activarPeriodo()"  />
                    </label></td>
                  </tr>
                  <tr>
                    <td height="26" align="right" valign="baseline" nowrap="nowrap"><strong>Periodo:</strong></td>
                    <td colspan="3" valign="baseline">
                      <select disabled="disabled" name="periodo" id="periodo">
                        <option value="Diario">Diario</option>
                        <option value="Semanal">Semanal</option>
                        <option value="Quincenal">Quincenal</option>
                        <option value="Mensual">Mensual</option>
                        </select></td>
                  </tr>
                  <tr>
                    <td align="right" valign="baseline" nowrap="nowrap"><strong>Clasificacion:</strong></td>
                    <td colspan="3" align="left"><select name="clasificacion" id="clasificacion">
                      <option value="empresarial">Empresarial</option>
                      <option value="personal">Personal</option>
                      </select></td>
                  </tr>
                  <tr>
                    <td align="right" valign="baseline" nowrap="nowrap"><strong>Fecha</strong></td>
                    <td colspan="3" align="left"><input name="fecha"  type="text" id="fecha" value="" size="20" maxlength="10" readonly="readonly" />
                      <button type="submit"  id="botonfecha" name="botonfecha" title="Clic Para Escoger la fecha">Fecha</button>
                      <script type="text/javascript">
							Calendar.setup({
							  inputField    : "fecha",
							  ifFormat   : "%Y-%m-%d",
							  button        : "botonfecha",
							  align         : "Tr"
							});
						  </script></td>
                  </tr>
                  <tr>
                    <td align="right" valign="baseline" nowrap="nowrap"><strong>Monto:</strong></td>
                    <td colspan="3" align="left"><label for="clasificacion">
                      <input type="text" name="monto" class="Letras"  style="text-align:right; font-weight:bold;" value="0" onchange="return validaFloat()" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" />
                    </label></td>
                  </tr>
                  
                  <tr>
                    <td align="right" valign="middle" nowrap="nowrap"><strong>Descripcion:</strong></td>
                    <td colspan="3" valign="baseline"><label for="descripcion"></label>
                      <textarea name="descripcion" onkeydown="if(this.value.length &gt;= 500){ alert('Has superado el tamaño máximo permitido de este campo'); return false; }" id="descripcion" cols="75" rows="6"></textarea></td>
                  </tr>
                  <tr>
                    <td align="right" valign="baseline" nowrap="nowrap">&nbsp;</td>
                    <td width="550" align="left" class="tituloDOSE_2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4" align="center"><input type="submit" value="REGISTRAR GASTO" /></td>
                  </tr>
                </tbody>
              </table></td>
            </tr>
            <tr>
              <td align="right" width="642"></td>
            </tr>
          </tbody>
        </table></td>
        <td background="imagenes/v_back_der.gif" width="12">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" height="1" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
        <td background="imagenes/v_back_inf.gif" height="1" width="643"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
        <td align="right" height="1" width="12"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
      </tr>
    </tbody>
  </table>
  <p>&nbsp;</p>
  <input type="hidden" name="MM_insert" value="form1" />
  <input type="hidden" name="tipo" id="tipo" value="eventual" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($gastos);
?>
