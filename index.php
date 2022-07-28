<?php require_once('Connections/conexion.php'); ?>
<?php
session_start();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

//recepcion de datos
$usuario= $_POST["usuario"];
$contrasena= $_POST["clave"];
$administrador="ADMINISTRADOR";
$claveAdm="ADMIN1511";

mysql_select_db($database_conexion, $conexion);
//ejecucuion de la sentemcia sql
$sql="select * from usuarios where login='$usuario' and clave='$contrasena'";
$resultado= mysql_query($sql)or die(mysql_error());
$fila=mysql_fetch_array($resultado);

//verificar si  son validos los datos
if($fila["login"]!=$usuario){

	
if($administrador==$usuario and $claveAdm==$contrasena){
setcookie("usr",$administrador,time()+7776000);
setcookie("clv",$claveAdm,time()+7776000);

$_SESSION["usuario"]=$administrador;
	header("Location:inicio.php");
	
}
echo "<script type=\"text/javascript\">alert ('Usted no es un usuario registrado');  location.href='index.php' </script>";
exit;
}
else{

setcookie("usr",$usuario,time()+7776000);
setcookie("clv",$contrasena,time()+7776000);

$_SESSION["usuario"]=$fila["login"];


if (isset($_SESSION["usuario"])){
header("Location:inicio.php");
}else{
echo "<script type=\"text/javascript\">alert ('Ocurrio un error vuelva a iniciar sesion');  location.href='index.php' </script>";
exit;
}

}

}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="./logo de innovaciones.jpg" type="image/x-icon" />
<link rel="shortcut icon" href="./logo de innovaciones.jpg" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.bordes_laterales {
	border-right-width: thin;
	border-right-style: solid;
	border-left-style: solid;
	border-right-color: #1c6cdb;
	border-left-width: thin;
	border-left-color: #1c6cdb;
}
</style>
<title>SAE</title>
</head>
<script type="text/javascript">
function validar(){
	
			

			if(document.form1.usuario.value==''){
						alert('Debe Ingresar un Usuario');
						return false;
			}
			if(document.form1.clave.value==''){
						alert('Debe Ingresar una Clave');
						return false;
			}
		
		   						
}
</script>

<body>
<table width="823" height="452" border="0" align="center" cellpadding="0" cellspacing="0" class="bordes_laterales">
  <tr>
    <td width="823" height="28" valign="top"><img src="imagenes/cabeza2.jpg" width="824" height="50" /></td>
  </tr>
  <tr valign="bottom">
    <td height="28" valign="top"><form id="form1" name="form1" onsubmit="return validar()" method="post" action="<?php echo $editFormAction; ?>">
      <p align="center"><img src="imagenes/logoHeader2.jpg" alt="" width="486" height="77" /></p>
      <table width="559" border="0" align="center" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
            <td align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Ingreso al Sistema</span></td>
            <td align="right" height="1" width="12"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
          </tr>
          <tr>
            <td width="13" height="140" background="imagenes/v_back_izq.gif"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
            <td class="tituloDOSE_2" valign="top" width="534"><table border="0" cellpadding="0" cellspacing="0" width="534">
              <tbody>
                <tr>
                  <td width="1" align="left"  ></td>
                  <td width="650"  height="1" class="tituloDOSE_2"><table border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td align="right"></td>
                        </tr>
                      <tr>
                        <td align="center" width="520"><table width="518" align="center" cellpadding="3" cellspacing="0">
                          <tbody>
                            <tr>
                              <td width="209"align="right" valign="baseline"><strong>Introduzca su Usuario:</strong></td>
                              <td width="295" valign="baseline"><input name="usuario" type="text" id="usuario" value=""  size="20" maxlength="20"/></td>
                              </tr>
                            <tr>
                              <td align="right" valign="baseline" nowrap="nowrap"><strong>Introduzca su Clave:</strong></td>
                              <td valign="baseline"><input name="clave" type="password" id="clave" value="" size="20" maxlength="10" /></td>
                              </tr>
                            <tr>
                              <td colspan="2" align="center" class="tituloDOSE_2">&nbsp;</td>
                              </tr>
                            <tr>
                              <td colspan="2" align="center" class="tituloDOSE_2"><input type="submit" name="button" id="button" value="ACCEDER" /><input type="reset" name="button2" id="button2" value="BORRAR"></td>
                              </tr>
                            </tbody>
                          </table></td>
                        </tr>
                      <tr>
                        <td width="520" height="2" valign="bottom" align="center"><strong>Desarrollado por Ing. Jose Carlos Perdomo</strong></td>
                        </tr>
                      </tbody>
                    </table></td>
                  <td width="1"></td>
                  </tr>
                </tbody>
              </table></td>
            <td width="12" background="imagenes/v_back_der.gif">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" height="12" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
            <td width="534" height="12" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
            <td align="right" height="12" width="12"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
          </tr>
        </tbody>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form></td>
  </tr>
  <tr valign="top">
    <td height="50" valign="bottom"><img src="imagenes/pie2.jpg" width="824" height="50" /></td>
  </tr>
</table>
</body>
</html>