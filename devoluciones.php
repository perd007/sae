<?php require_once('Connections/conexion.php'); ?>
<?php 
//validar usuario
if($_COOKIE["val"]==true){
	if($row_permisos["d"]!=1){
	echo "<script type=\"text/javascript\">alert ('Usted no posee permisos para realizar Devoluciones'); location.href='fondo.php'";
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

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css.css" rel="stylesheet" type="text/css" />
<title>Documento sin título</title>
</head>
<script language="javascript">


function validar(){

			if(document.form1.numero.value!=""){
			 var filtro = /^(\d)+$/i;
		      if (!filtro.test(document.getElementById('numero').value)){
				alert('EL NUMERO DE FACTURA SOLO DEBE CONTENER NUMEROS');
				return false;
		   		}
				}
			if(document.form1.serie.value!=""){
			 var filtro = /^(\d)+$/i;
		      if (!filtro.test(document.getElementById('serie').value)){
				alert('EL NUMERO DE SERIE SOLO DEBE CONTENER NUMEROS');
				return false;
		   		}
				}
						
			
			if(document.form1.numero.value==""){
						alert("Debe ingresar un numero de factura");
						return false;
				}
			if(document.form1.serie.value==""){
						alert("Debe ingresar una serie de factura");
						return false;
				}
			
				
		}
		

		
</script>
<body>
<form action="generar_devolucion.php" method="post" onsubmit="return validar()" name="form1" id="form1">
  <table align="left" border="0" cellspacing="0">
    <tbody>
      <tr>
        <td width="625"><table border="0" cellpadding="0" cellspacing="0" width="625">
          <tbody>
            <tr>
              <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
              <td align="center" background="imagenes/v_back_sup.jpg" valign="center"><span class="negrita">Buscar Facturas</span></td>
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
                    <td align="center" width="602"><table cellpadding="3" cellspacing="0" width="600">
                      <tbody>
                        <tr>
                          <td width="133" align="left" valign="baseline" nowrap="nowrap">Serie de Factura:</td>
                          <td width="147" valign="baseline"><label for="tipo">
                            <input name="serie" type="text" id="serie" size="10" maxlength="6" />
                          </label></td>
                          <td width="151" align="right" valign="baseline" nowrap="nowrap">Numero de Factura:</td>
                          <td width="143" valign="baseline"><input  name="numero" id="numero" type="text" value="" size="10" maxlength="6" /></td>
                        </tr>
                        <tr>
                          <td class="tituloDOSE_2" align="left">&nbsp;</td>
                          <td class="tituloDOSE_2" align="left">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="4" align="center"><input name="Enviar" type="submit" value="BUSCAR" /></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                  <tr>
                    <td align="right" width="602"></td>
                  </tr>
                </tbody>
              </table></td>
              <td background="imagenes/v_back_der.gif" width="12"><img src="imagenes/cargar_productos.php" alt="1" height="2" width="13" /></td>
            </tr>
            <tr>
              <td align="left" height="1" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
              <td background="imagenes/v_back_inf.gif" height="1" width="600"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
              <td align="right" height="1" width="12"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
            </tr>
          </tbody>
        </table></td>
      </tr>
      <tr> </tr>
    </tbody>
  </table>
</form>
</body>
</html>