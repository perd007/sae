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
	
	    $num1=$_POST["precioCompra"];
		$num1 = str_replace(",",".",$num1);

	
		$num=$_POST['precio'];
		$num = str_replace(".","",$num);
		$num = str_replace(",",".",$num);
		
	if($num1>=$num){
				 echo "<script type=\"text/javascript\">alert ('El precio de Venta no puede ser menor ni igaul al de compra');  location.href='' </script>";
			}
	
	
		
		
  $insertSQL = sprintf("INSERT INTO precios (id_producto, precio, fecha) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['id_productos'], "int"),
                       GetSQLValueString($num, "double"),
					   GetSQLValueString(date("Y-m-d"), "date"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  if($Result1==1){
  echo "<script type=\"text/javascript\">alert ('Datos Guardados');  location.href='' </script>";
  }else{
  echo "<script type=\"text/javascript\">alert ('Ocurrio un Error');  location.href='' </script>";
  exit;
  }
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
.Letras1 {font-size:14px;
font-style:italic;

background-color:transparent;
border:none;
}
.Letras1 {font-size:14px;
font-style:italic;

background-color:transparent;
border:none;
}
</style>
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
<style> 
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
font-style:italic;

background-color:transparent;
border:none;
 
}
.boton {
	font-size: 14px;
	font-style: italic;
	font-weight: bold; 
}
</style>
<script language="javascript">

function validaFloat()
{
	var numero=document.form1.precio.value;
  if (!/^([0-9])*[,]?[0-9]*$/.test(numero))
   alert("El valor " + numero + " no es un número");
   return false;
}

function validar(){
	
		if(document.form1.id_productos.value==""){
						alert("Debe Seleccionar un Producto");
						return false;
				}
				
		if(document.form1.precio.value==""){
						alert("Debe Ingresar un Precio");
						return false;
				}
		if(document.form1.precio.value<=0){
						alert("El Precio debe ser Mayor a Cero");
						return false;
				}		
		
				
		
		
			
}


</script>
<body>
<!-- pretty -->
<span class="gallery clearfix"></span>
<form action="<?php echo $editFormAction; ?>" method="post" onsubmit="return validar()" name="form1" id="form1">
  <table border="0" cellpadding="0" cellspacing="0" width="775">
    <tbody>
      <tr>
        <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
        <td class="tituloDOSE_3" align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita">Ajuste de Precios para Productos</span></td>
        <td align="right" height="1" width="12"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
      </tr>
      <tr>
        <td width="13" height="140" rowspan="2" align="right" background="imagenes/v_back_izq.gif"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
        <td valign="baseline" align="right"  class="gallery clearfix">
        <a  href="listado_servicios_ajuste.php?iframe=true&amp;width=950&amp;height=525" target="_blank" rel="prettyPhoto[iframe1]" >
        <input type="button" class="btLista" value="Servicios" id="btItem"  /></a></td>
        <td width="12" rowspan="2" background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><table width="694" align="center" class="tblLista">
          <thead>
            <tr >
              <th width="502" >Servicio</th>
              <th  width="180">Precio</th>
            </tr>
            <tr>
              <td ><input type="text" name="nombre" id="nombre" readonly="readonly"  class="dato" value="" size="32" style="text-align:center; font-weight:bold;" /></td>
              <td align="right"><input type="text" name="precio" class="Letras"  style="text-align:right; font-weight:bold;" value="0" onchange="return validaFloat()" onBlur="numeroBlur(this);" onFocus="numeroFocus(this);" /></td>
            </tr>
          </thead>
          <tbody id="lista_detalles">
          
          
          </tbody>
        </table></td>
      </tr>
      <tr>
        <td height="19" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
        <td valign="top">&nbsp;</td>
        <td background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <tr>
        <td height="19" align="right" background="imagenes/v_back_izq.gif">&nbsp;</td>
        <td align="center" valign="top"><input name="button" type="submit" class="boton"  id="button" value="GUARDAR" C /></td>
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
    <input type="hidden" name="MM_insert" value="form1" />
     <input type="hidden" name="id_productos" id="id_productos" value="" />
  </p>
</form>
</body>
</html>
<?php
mysql_free_result($compras);

mysql_free_result($compra_Productos);
?>
