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
			



	//cargamos el almacen
	$insertSQL1 = sprintf("INSERT INTO almacen (id_producto, cantida, transaccion) VALUES ( %s, %s, %s)",
                       
                       GetSQLValueString($_POST["id_productos"], "int"),
                       GetSQLValueString($_POST["cantidad"], "int"),
                       GetSQLValueString("EXTRAIDO", "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL1, $conexion) or die(mysql_error());
	//
	

 

	if($Result1==1){
  echo "<script type=\"text/javascript\">alert ('PRODUCTO EXTRAIDO');  location.href='' </script>";
  }else{
  echo "<script type=\"text/javascript\">alert ('Ocurrio un Error');  location.href='' </script>";
  exit;
  }
  
  
}//FIN DEL PROCESAMIENTO DEL FORMULARIO

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
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
a{text-decoration:none} 
.dato {
background-color: transparent; 
border:#FFF;
font-size:14px;
font-style:italic;
width:100%; 
 
}
.Letras {
font-size:14px;


background-color:transparent;
border:none;
 
}

.boton {
	font-size: 14px;

	font-weight: bold; 
}
</style>
<script type="text/javascript">
function sumaTotal() {
var disponible=0;
var cantidad=0;
cantidad=document.getElementById("cantidad").value;
disponible=document.getElementById("disponible").value;

if(document.getElementById("disponible").value!=""){

var cero=disponible-cantidad;	

if(cero<0){
	alert('La Cantidad Supera lo Disponible');
	document.getElementById("cantidad").value=0;
	
	
}
}
}

function validar(){
	

		if(document.form1.cantidad.value!=""){
			 var filtro = /^(\d)+$/i;
		      if (!filtro.test(document.getElementById('cantidad').value)){
				alert('LA CANTIDAD DEBE SER UN NUMERO ENTERO');
				return false;
		   }
		}
			
			
		if(document.form1.codigo.value==""){
						alert("Debe Seleccionar un Producto");
						return false;
				}		
		if(document.form1.cantidad.value==""){
						alert("Debe Ingresar una Cantidad");
						return false;
				}
		if(document.form1.cantidad.value<=0){
						alert("La Cantidad debe ser Mayor a Cero");
						return false;
				}		
		
				
		
		
			
}

</script>
</head>

<body>
<span class="gallery clearfix"></span>
<form action="<?php echo $editFormAction; ?>" method="post" onsubmit="return validar()" name="form1" id="form1">
  <table width="683" border="0" align="center" cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
        <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita">Extraer Productos</span></td>
        <td align="right" height="1" width="12"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
      </tr>
      <tr>
        <td width="13" height="140" rowspan="2" align="right" background="imagenes/v_back_izq.gif"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
        <td valign="baseline" align="right"  class="gallery clearfix"><a  href="listado_productos_disponibilidad.php?iframe=true&amp;width=950&amp;height=525" target="_blank" rel="prettyPhoto[iframe1]" >
          <input type="button" class="btLista" value="Producto" id="btItem"  />
        </a></td>
        <td width="12" rowspan="2" background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><table width="658" align="center" class="tblLista">
          <thead>
            <tr>
              <th width="144">Codigo</th>
              <th width="213" >Producto</th>
              <th  width="127">Motivo</th>
              <th  width="80">Disponible</th>
              <th  width="70">Cantidad</th>
            </tr>
            <tr>
              <td><input name="codigo" type="text"   class="Letras" id="codigo" style="text-align:center; font-weight:bold;" value="" size="20" readonly="readonly"  /></td>
              <td ><input type="text" name="nombre" id="nombre" readonly="readonly"   class="Letras" value=""  style="text-align:center; font-weight:bold;" /></td>
              <td align="right">
                <select name="motivo" id="motivo" class="Letras">
                <option value="Averiado">Utilizado</option>
                  <option value="Averiado">Averiado</option>
                  <option value="Uso Interno">Uso Interno</option>
                  <option value="Donado">Donado</option>
                  <option value="Venta Manual">Venta Manual</option>
                </select></td>
              <td align="right"><input name="disponible" type="text" class="Letras" id="disponible"style="text-align:center; font-weight:bold;" size="10" readonly="readonly" /></td>
              <td align="right"><input name="cantidad" type="text" class="Letras" id="cantidad" style="text-align:center; font-weight:bold;"  onchange="sumaTotal()" size="10" /></td>
            </tr>
          </thead>
          <tbody id="lista_detalles">
          </tbody>
        </table></td>
      </tr>
      <tr>
        <td height="19" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
        <td align="center" valign="top"><input name="button" type="submit" class="boton"  id="button" value="EXTRAER" /></td>
        <td background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" height="12" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
        <td background="imagenes/v_back_inf.gif" height="12" width="658"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
        <td align="right" height="12" width="12"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
      </tr>
    </tbody>
  </table>
  <p>
    <input type="hidden" name="MM_insert" value="form1" />
    <input type="hidden" name="id_productos" id="id_productos" value="" />
  </p>
</form>
<p>&nbsp;</p>

</body>
</html>