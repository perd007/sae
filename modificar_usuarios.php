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


//validamos que no exista dos cedula iguales
mysql_select_db($database_conexion, $conexion);
$query_valEmp = "SELECT * FROM empleados where cedula='$_POST[cedula]' and id_empleado!='$_POST[id_empleado]'";
$valEmp = mysql_query($query_valEmp, $conexion) or die(mysql_error());
$row_valEmp = mysql_fetch_assoc($valEmp);
$totalRows_valEmp = mysql_num_rows($valEmp);

if($totalRows_valEmp>=1){
echo "<script type=\"text/javascript\">alert ('Cedula ya Registrada'); location.href='consultar_usuarios.php' </script>";
 exit;
}
//

//validamos que  no existan dos usuarios iguales'
mysql_select_db($database_conexion, $conexion);
$query_valUsu = "SELECT * FROM usuarios where login='$_POST[login]' and id_usuarios!='$_POST[id_usuarios]'";
$valUsu = mysql_query($query_valUsu, $conexion) or die(mysql_error());
$row_valUsu = mysql_fetch_assoc($valUsu);
$totalRows_valUsu = mysql_num_rows($valUsu);

if($totalRows_valUsu>=1){
echo "<script type=\"text/javascript\">alert ('Usuario ya Registrado'); location.href='consultar_usuarios.php' </script>";
 exit;
}
//
	
  $updateSQL1 = sprintf("UPDATE empleados SET cedula=%s, nombres=%s, cargo=%s, direccion=%s, telefono=%s WHERE id_empleado=%s",
                       GetSQLValueString($_POST['cedula'], "int"),
                       GetSQLValueString($_POST['nombres'], "text"),
                       GetSQLValueString($_POST['cargo'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['telefono'], "text"),
                       GetSQLValueString($_POST['id_empleado'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL1, $conexion) or die(mysql_error());

  $updateSQL2 = sprintf("UPDATE usuarios SET id_empleado=%s, login=%s, clave=%s WHERE id_usuarios=%s",
                       GetSQLValueString($_POST['cedula'], "int"),
                       GetSQLValueString($_POST['login'], "text"),
                       GetSQLValueString($_POST['clave'], "text"),
                       GetSQLValueString($_POST['id_usuarios'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result2 = mysql_query($updateSQL2, $conexion) or die(mysql_error());
  
    $updateSQL3 = sprintf("UPDATE permisos_usuarios SET  p=%s, f=%s, c=%s, a=%s, v=%s, r=%s, cl=%s, prv=%s, s=%s, u=%s, ac=%s, cc=%s, d=%s WHERE id_permisos_usuarios=%s",
                      
                       GetSQLValueString($_POST['p'], "int"),
                       GetSQLValueString($_POST['f'], "int"),
                       GetSQLValueString($_POST['c'], "int"),
                       GetSQLValueString($_POST['a'], "int"),
                       GetSQLValueString($_POST['v'], "int"),
                       GetSQLValueString($_POST['r'], "int"),
                       GetSQLValueString($_POST['cl'], "int"),
                       GetSQLValueString($_POST['prv'], "int"),
                       GetSQLValueString($_POST['s'], "int"),
                       GetSQLValueString($_POST['u'], "int"),
					   GetSQLValueString($_POST['ac'], "int"),
                       GetSQLValueString($_POST['cc'], "int"),
					   GetSQLValueString($_POST['d'], "int"),
                       GetSQLValueString($_POST['id_permisos_usuarios'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result3 = mysql_query($updateSQL3, $conexion) or die(mysql_error());
  
   if($Result1==1 and $Result2==1 and $Result3==1){
	   if($_COOKIE["usr"]==$_POST['login']){
 echo "<script type=\"text/javascript\">alert ('INICIE SESION NUEVAMENTE');  location.href='cerrarSesion.php' </script>";		
 }else
  echo "<script type=\"text/javascript\">alert ('USUARIO ACTUALIZADO');  location.href='consultar_usuarios.php' </script>";
  }else{
  echo "<script type=\"text/javascript\">alert ('Ocurrio un Error');  location.href='consultar_usuarios.php' </script>";
  exit;
  }
}


mysql_select_db($database_conexion, $conexion);
$query_empleado = "SELECT * FROM empleados where cedula='$_GET[cedula]'";
$empleado = mysql_query($query_empleado, $conexion) or die(mysql_error());
$row_empleado = mysql_fetch_assoc($empleado);
$totalRows_empleado = mysql_num_rows($empleado);

mysql_select_db($database_conexion, $conexion);
$query_usuario = "SELECT * FROM usuarios where id_empleado='$_GET[cedula]'";
$usuario = mysql_query($query_usuario, $conexion) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

mysql_select_db($database_conexion, $conexion);
$query_permisos = "SELECT * FROM permisos_usuarios where id_usuario='$_GET[cedula]'";
$permisos = mysql_query($query_permisos, $conexion) or die(mysql_error());
$row_permisos = mysql_fetch_assoc($permisos);
$totalRows_permisos = mysql_num_rows($permisos);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css.css" rel="stylesheet" type="text/css" />
<title>Documento sin título</title>
</head>
<script type="text/javascript">
function validar(){
	
			if(document.form1.telefono.value!=""){
			 var filtro = /^(\d)+$/i;
		      if (!filtro.test(document.getElementById('telefono').value)){
				alert('EL NUMERO DE TELEFONO DEBE SER NUMERICO');
				return false;
		   		}
				}
		   		
				if(document.form1.cedula.value!=""){
			 var filtro = /^(\d)+$/i;
		      if (!filtro.test(document.getElementById('cedula').value)){
				alert('EL NUMERO DE CEDULA DEBE SER NUMERICO');
				return false;
		   		}
				}
		   		
				if(document.form1.login.value=='ADMINISTRADOR'){
						alert('DEBE INGRESAR OTRO NOMBRE DE USUARIO');
						return false;
			}
			
			

			if(document.form1.cedula.value==''){
						alert('Debe Ingresar un Numero de Cedula');
						return false;
			}


			if(document.form1.nombres.value==''){
						alert('Debe Ingresar un Nombre');
						return false;
			}
			if(document.form1.telefono.value==''){
						alert('Debe Ingresar un Telefono');
						return false;
			}
			
			if(document.form1.login.value==''){
						alert('Debe Ingresar un Usuario');
						return false;
			}
			if(document.form1.clave.value==''){
						alert('Debe Ingresar una Clave y Repetirla');
						return false;
			}
			if(document.form1.clave2.value==''){
						alert('Debe Ingresar una Clave y Repetirla');
						return false;
			}

if(document.form1.clave2.value!=document.form1.clave.value){
						alert('Las claves no coinciden');
						return false;
			}
			
		
			if(document.form1.p.checked==false) { 
			 	
			  		if(document.form1.f.checked==false){
						
			 				if(document.form1.c.checked==false){ 
							
								if(document.form1.a.checked==false){ 
			 	
									if(document.form1.v.checked==false){ 
			 									
										if(document.form1.r.checked==false){ 
			 					
											if(document.form1.cl.checked==false){ 
			 					
												if(document.form1.prv.checked==false){ 
			 					
													if(document.form1.s.checked==false){ 
			 					
														if(document.form1.u.checked==false){ 
			 													
																if(document.form1.ac.checked==false){ 
															
																	if(document.form1.cc.checked==false){

			 															if(document.form1.d.checked==false){ 

			 																alert("DEBE INGRESAR ALGUN PERMISO PARA ESTE USUARIO");
		   																	return false;	
																		}	
																			}
																	}	
															}
													
														}
													
													}	
												}	
										
											}
										
										}
									}
								}
							
						}
					
				
			}
	
		
		   						
}
</script>
<body>
<form action="<?php echo $editFormAction; ?>" method="post" onsubmit="return validar()" name="form1" id="form1">
  <table border="0" cellpadding="0" cellspacing="0" width="696">
    <tbody>
      <tr>
        <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
        <td align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Modificacion  de Usuarios</span></td>
        <td align="right" height="1" width="12"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
      </tr>
      <tr>
        <td width="13" height="140" rowspan="2" background="imagenes/v_back_izq.gif">&nbsp;</td>
        <td valign="top" class="tituloDOSE_2">&nbsp;</td>
        <td width="12" rowspan="2" background="imagenes/v_back_der.gif">&nbsp;</td>
      </tr>
      <tr>
        <td class="tituloDOSE_2" valign="top" width="671"><table border="0" cellpadding="0" cellspacing="0" width="671">
          <tbody>
            <tr>
              <td width="1" align="left"  ></td>
              <td width="763"  height="1" class="tituloDOSE_2"><table border="0" cellpadding="0" cellspacing="0">
                <tbody>
                  <tr>
                    <td align="right"></td>
                  </tr>
                  <tr>
                    <td align="center" width="659"><table cellpadding="3" cellspacing="0" width="660">
                      <tbody>
                        <tr>
                          <td width="61" height="28"align="right" valign="baseline">Nombre:</td>
                          <td width="222" valign="baseline"><input type="text" name="nombres" value="<?php echo $row_empleado['nombres']; ?>" size="32" /></td>
                          <td width="79" align="right" valign="baseline" nowrap="nowrap"><p>Cedula :</p></td>
                          <td width="272" valign="baseline"><input name="cedula" id="cedula" type="text" value="<?php echo $row_empleado['cedula']; ?>" size="15" maxlength="8" /></td>
                        </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap">Telefono:</td>
                          <td valign="baseline"><input name="telefono" id="telefono" type="text" value="<?php echo $row_empleado['telefono']; ?>" size="20" maxlength="11" /></td>
                          <td align="right" valign="baseline">Cargo:</td>
                          <td valign="baseline"><label for="tipo3"></label>
                            <input name="cargo" type="text" id="cargo" value="<?php echo $row_empleado['cargo']; ?>" size="32" /></td>
                        </tr>
                        <tr>
                          <td class="tituloDOSE_2" align="left">Direccion</td>
                          <td colspan="3" valign="baseline"><label for="direccion">
                            <textarea name="direccion" cols="60" rows="4" id="direccion" ><?php echo $row_empleado['direccion']; ?></textarea>
                          </label></td>
                        </tr>
                        <tr >
                          <td colspan="4" align="center" background="imagenes/v_back_sup.jpg"  class="tituloDOSE_2"><strong>Usuario del Sistema</strong></td>
                        </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap">Usuario:</td>
                          <td valign="baseline"><input name="login" type="text" value="<?php echo $row_usuario['login']; ?>" size="30" maxlength="20" /></td>
                          <td align="right" valign="baseline" nowrap="nowrap">&nbsp;</td>
                          <td valign="baseline">&nbsp;</td>
                        </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap">Clave:</td>
                          <td valign="baseline"><input name="clave" type="password" value="<?php echo $row_usuario['clave']; ?>" size="20" maxlength="10" /></td>
                          <td align="right" valign="baseline" nowrap="nowrap">Repetir Clave:</td>
                          <td valign="baseline"><input name="clave2" type="password" id="clave2" value="<?php echo $row_usuario['clave']; ?>" size="20" maxlength="10" /></td>
                        </tr>
                        <tr>
                          <td colspan="4" align="center" valign="baseline" background="imagenes/v_back_sup.jpg" nowrap="nowrap"><strong>Permisos del Usuario</strong></td>
                        </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap"><input <?php if (!(strcmp($row_permisos['p'],1))) {echo "checked=\"checked\"";} ?> name="p" type="checkbox" id="p" value="1" /></td>
                          <td valign="baseline">Productos</td>
                          <td align="right" valign="baseline" nowrap="nowrap">&nbsp;</td>
                          <td valign="baseline">&nbsp;</td>
                        </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap"><input <?php if (!(strcmp($row_permisos['d'],1))) {echo "checked=\"checked\"";} ?> name="d" type="checkbox" id="d" value="1" /></td>
                          <td valign="baseline">Devolucion</td>
                          <td align="right" valign="baseline" nowrap="nowrap"><input <?php if (!(strcmp($row_permisos['r'],1))) {echo "checked=\"checked\"";} ?> name="r" type="checkbox" id="r" value="1" /></td>
                          <td valign="baseline">Reportes</td>
                          </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap"><input <?php if (!(strcmp($row_permisos['f'],1))) {echo "checked=\"checked\"";} ?> name="f" type="checkbox" id="f" value="1" /></td>
                          <td valign="baseline">Facturacion </td>
                          <td align="right" valign="baseline" nowrap="nowrap"><input <?php if (!(strcmp($row_permisos['cl'],1))) {echo "checked=\"checked\"";} ?> name="cl" type="checkbox" id="cl" value="1" /></td>
                          <td valign="baseline">Clientes</td>
                        </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap"><input <?php if (!(strcmp($row_permisos['c'],1))) {echo "checked=\"checked\"";} ?> name="c" type="checkbox" id="c" value="1" /></td>
                          <td valign="baseline">Compras</td>
                          <td align="right" valign="baseline" nowrap="nowrap"><input <?php if (!(strcmp($row_permisos['prv'],1))) {echo "checked=\"checked\"";} ?> name="prv" type="checkbox" id="prv" value="1" /></td>
                          <td valign="baseline">Proveedores</td>
                        </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap"><input <?php if (!(strcmp($row_permisos['a'],1))) {echo "checked=\"checked\"";} ?> name="a" type="checkbox" id="a" value="1" /></td>
                          <td valign="baseline">Almacen</td>
                          <td align="right" valign="baseline" nowrap="nowrap"><input <?php if (!(strcmp($row_permisos['s'],1))) {echo "checked=\"checked\"";} ?> name="s" type="checkbox" id="s" value="1" /></td>
                          <td valign="baseline">Seguros</td>
                        </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap"><input <?php if (!(strcmp($row_permisos['v'],1))) {echo "checked=\"checked\"";} ?> name="v" type="checkbox" id="v" value="1" /></td>
                          <td valign="baseline">Pedidos</td>
                          <td align="right" valign="baseline" nowrap="nowrap"><input <?php if (!(strcmp($row_permisos['u'],1))) {echo "checked=\"checked\"";} ?> name="u" type="checkbox" id="u" value="1" /></td>
                          <td valign="baseline">Usuarios</td>
                        </tr>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap"><input <?php if (!(strcmp($row_permisos['ac'],1))) {echo "checked=\"checked\"";} ?> name="ac" type="checkbox" id="ac" value="1" />
                            <label for="ac"></label></td>
                          <td valign="baseline">Abrir Caja</td>
                          <td align="right" valign="baseline" nowrap="nowrap"><input <?php if (!(strcmp($row_permisos['cc'],1))) {echo "checked=\"checked\"";} ?> name="cc" type="checkbox" id="cc" value="1" /></td>
                          <td valign="baseline">Cerrar Caja</td>
                        </tr>
                        <tr>
                          <td colspan="4" align="center" class="tituloDOSE_2"><input type="submit" name="button" id="button" value="ACTUALIZAR" /></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                  <tr>
                    <td align="right" width="659"></td>
                  </tr>
                </tbody>
              </table></td>
              <td width="1"></td>
            </tr>
          </tbody>
        </table></td>
      </tr>
      <tr>
        <td align="left" height="12" width="13"><img src="imagenes/v_esq_izq_inf.gif" alt="5" height="12" width="13" /></td>
        <td width="671" height="12" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
        <td align="right" height="12" width="12"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id_empleado" value="<?php echo $row_empleado['id_empleado']; ?>" />
  <input type="hidden" name="id_usuarios" value="<?php echo $row_usuario['id_usuarios']; ?>" />
  <input type="hidden" name="id_permisos_usuarios" value="<?php echo $row_permisos['id_permisos_usuarios']; ?>" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($empleado);

mysql_free_result($usuario);

mysql_free_result($permisos);

mysql_free_result($valEmp);

mysql_free_result($valUsu);
?>
