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
	
	if ($_POST['periodo']=="eventual" or $_POST['periodo']==""){ $periodo="no aplica"; } else $periodo=$_POST['periodo'];
	
		$num=$_POST['monto'];
		$num = str_replace(".","",$num);
		$num = str_replace(",",".",$num);
	
  $updateSQL = sprintf("UPDATE gastos SET monto=%s, descripcion=%s, tipo=%s, clasificacion=%s, fecha=%s, periodo=%s WHERE id_gastos=%s",
                       GetSQLValueString($num, "double"),
                       GetSQLValueString($_POST['descripcion'], "text"),
                       GetSQLValueString($_POST['tipo'], "text"),
                       GetSQLValueString($_POST['clasificacion'], "text"),
                       GetSQLValueString($_POST['fecha'], "date"),
                       GetSQLValueString( $periodo, "text"),
                       GetSQLValueString($_POST['id_gastos'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

  $updateGoTo = "consultar_gastos.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
$id_gastos=$_GET["id"];

mysql_select_db($database_conexion, $conexion);
$query_gastos = "SELECT * FROM gastos where id_gastos='$id_gastos'";
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
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table border="0" cellpadding="0" cellspacing="0" width="668">
    <tbody>
      <tr>
        <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
        <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita">Modificar Gasto</span></td>
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
                    </label>

                      <input <?php if (!(strcmp($row_gastos['tipo'],"eventual"))) {echo "checked=\"checked\"";} ?> type="radio" name="tiposeleccion" id="tipo1" value="eventual"  onclick="activarPeriodo()" />
                            <label for="radio"> <strong>Periodico</strong>
                              <input <?php if (!(strcmp($row_gastos['tipo'],"periodico"))) {echo "checked=\"checked\"";} ?> type="radio" name="tiposeleccion" id="tipo2" value="periodico" onclick="activarPeriodo()" />
                            </label></td>
                  </tr>
                  <tr>
                    <td align="right" valign="baseline" nowrap="nowrap"><strong>Periodo:</strong></td>
                    <td colspan="3" valign="baseline"><select name="periodo" id="periodo" <?php if ($row_gastos['tipo']=="eventual") {echo "disabled=disabled";} ?>  >
                      <option value="Diario" <?php if (!(strcmp("Diario", $row_gastos['periodo']))) {echo "selected=\"selected\"";} ?>>Diario</option>
                      <option value="Semanal" <?php if (!(strcmp("Semanal", $row_gastos['periodo']))) {echo "selected=\"selected\"";} ?>>Semanal</option>
                      <option value="Quincenal" <?php if (!(strcmp("Quincenal", $row_gastos['periodo']))) {echo "selected=\"selected\"";} ?>>Quincenal</option>
                      <option value="Mensual" <?php if (!(strcmp("Mensual", $row_gastos['periodo']))) {echo "selected=\"selected\"";} ?>>Mensual</option>
               
                    </select></td>
                  </tr>
                  <tr>
                    <td align="right" valign="baseline" nowrap="nowrap"><strong>Monto:</strong></td>
                    <td colspan="3" valign="baseline"><input type="text" name="monto" class="Letras"  style="text-align:right; font-weight:bold;" value="<?=(number_format($row_gastos['monto'],"2",",","."))?>" onchange="return validaFloat()" onblur="numeroBlur(this);" onfocus="numeroFocus(this);" /></td>
                  </tr>
                  <tr>
                    <td align="right" valign="baseline" nowrap="nowrap"><strong>Clasificacion:</strong></td>
                    <td colspan="3" align="left"><label for="clasificacion"></label>
                      <select name="clasificacion" id="clasificacion">
                        <option value="empresarial" <?php if (!(strcmp("empresa", $row_gastos['clasificacion']))) {echo "selected=\"selected\"";} ?>>Empresarial</option>
                        <option value="personal" <?php if (!(strcmp("personal", $row_gastos['clasificacion']))) {echo "selected=\"selected\"";} ?>>Personal</option>
                        <?php
do {  
?>
                        <option value="<?php echo $row_gastos['clasificacion']?>"<?php if (!(strcmp($row_gastos['clasificacion'], $row_gastos['clasificacion']))) {echo "selected=\"selected\"";} ?>><?php echo $row_gastos['clasificacion']?></option>
                        <?php
} while ($row_gastos = mysql_fetch_assoc($gastos));
  $rows = mysql_num_rows($gastos);
  if($rows > 0) {
      mysql_data_seek($gastos, 0);
	  $row_gastos = mysql_fetch_assoc($gastos);
  }
?>
                      </select></td>
                  </tr>
                  <tr>
                    <td align="right" valign="baseline" nowrap="nowrap"><strong>Fecha</strong></td>
                    <td colspan="3" align="left"><input name="fecha"  type="text" id="fecha" value="<?php echo $row_gastos['fecha']; ?>" size="20" maxlength="10" readonly="readonly" />
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
                    <td align="right" valign="middle" nowrap="nowrap"><strong>Descripcion:</strong></td>
                    <td colspan="3" valign="baseline"><label for="descripcion"></label>
                      <textarea name="descripcion" onkeydown="if(this.value.length &gt;= 500){ alert('Has superado el tamaño máximo permitido de este campo'); return false; }" id="descripcion" cols="75" rows="6"><?php echo $row_gastos['descripcion']; ?></textarea></td>
                  </tr>
                  <tr>
                    <td align="right" valign="baseline" nowrap="nowrap">&nbsp;</td>
                    <td width="550" align="left" class="tituloDOSE_2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4" align="center"><input type="submit" value="MODIFICAR GASTO" /></td>
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
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id_gastos" value="<?php echo $row_gastos['id_gastos']; ?>" />
  <input type="hidden" name="tipo" id="tipo" value="eventual" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($gastos);
?>
