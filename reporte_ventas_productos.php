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
<body>
<form id="form1" name="form1" method="post" action="reporte_ventas_productos_pdf.php">
  <table border="0" cellpadding="0" cellspacing="0" width="710">
    <tbody>
      <tr>
        <td align="left" height="1"><img src="imagenes/vc_esq_izq_sup.gif" alt="7" height="32" width="13" /></td>
        <td align="center" valign="center" background="imagenes/v_back_sup.jpg" class="tituloDOSE_3"><span class="negrita">Visualizar Ventas de Productos y Servicios por Fecha</span></td>
        <td align="right" height="1" width="12"><img src="imagenes/vc_esq_der_sup.gif" alt="6" height="32" width="13" /></td>
      </tr>
      <tr>
        <td width="13" height="140" rowspan="2" background="imagenes/v_back_izq.gif"><img src="imagenes/v_back_izq.gif" alt="3" height="2" width="13" /></td>
        <td valign="top" class="tituloDOSE_2">&nbsp;</td>
        <td width="12" rowspan="2" background="imagenes/v_back_der.gif"><img src="imagenes/cargar_productos.php" alt="1" height="2" width="13" /></td>
      </tr>
      <tr>
        <td class="tituloDOSE_2" valign="top" width="685"><table border="0" cellpadding="0" cellspacing="0" width="685">
          <tbody>
            <tr>
              <td width="11" align="left"  ></td>
              <td width="674"  height="85" class="tituloDOSE_2"><table border="0" cellpadding="0" cellspacing="0">
                <tbody>
                  <tr>
                    <td align="right"></td>
                  </tr>
                  <tr>
                    <td align="center" width="670"><table cellpadding="3" cellspacing="0" width="671">
                      <tbody>
                        <tr>
                          <td align="right" valign="baseline" nowrap="nowrap">Desde:</td>
                          <td valign="baseline"><input name="fecha1" type="text" id="fecha1" value="<?=date("Y-m-d");?>" size="20" maxlength="10" readonly="readonly" />
                            <button type="submit" id="cal-button-1" title="Clic Para Escoger la fecha">Fecha</button>
                            <script type="text/javascript">
							Calendar.setup({
							  inputField    : "fecha1",
							  ifFormat   : "%Y-%m-%d",
							  button        : "cal-button-1",
							  align         : "Tr"
							});
						    </script></td>
                          <td align="right" valign="baseline" nowrap="nowrap">Hasta:</td>
                          <td valign="baseline"><input name="fecha2" type="text" id="fecha2" value="<?=date("Y-m-d");?>" size="20" maxlength="10" readonly="readonly" />
                            <button type="submit" id="cal-button-2" title="Clic Para Escoger la fecha">Fecha</button>
                            <script type="text/javascript">
							Calendar.setup({
							  inputField    : "fecha2",
							  ifFormat   : "%Y-%m-%d",
							  button        : "cal-button-2",
							  align         : "Tr"
							});
						    </script></td>
                          </tr>
                        <tr>
                          <td colspan="4" align="center" class="tituloDOSE_2"><input type="submit" name="button" id="button" value="BUSCAR" /></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                  <tr>
                    <td width="670" height="2" align="right"></td>
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
        <td width="685" height="12" background="imagenes/v_back_inf.gif"><img src="imagenes/v_back_inf.gif" alt="2" height="12" width="2" /></td>
        <td align="right" height="12" width="12"><img src="imagenes/v_esq_der_inf.gif" alt="4" height="12" width="13" /></td>
      </tr>
    </tbody>
  </table>
</form>
</body>
</html>