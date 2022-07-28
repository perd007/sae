<?php 
//validar usuario

if($_COOKIE["val"]==true){
	if($_COOKIE["cl"]!=1){
	echo "<script type=\"text/javascript\">alert ('Usted no posee permisos para Acceder al Modulo de Clientes');location.href='fondo.php' </script>";
    exit;
	}
}
else{
echo "<script type=\"text/javascript\">alert ('Error usuario invalido'); location.href='fondo.php' </script>";
 exit;
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">

<html>
        <head>
                <title>Sliding Doors Preview</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <style type="text/css">
<!--
    body {
        margin:0;
        padding:0;
        font: bold 11px/1.5em Verdana;
}

h2 {
        font: bold 14px Verdana, Arial, Helvetica, sans-serif;
        color: #000;
        margin: 0px;
        padding: 0px 0px 0px 15px;
}

img {
border: none;
}

/*- Menu Tabs 1--------------------------- */


    #tabs1 {
      float:left;
      width:100%;
          background:#F4F7FB;
      font-size:93%;
      line-height:normal;
          border-bottom:1px solid #BCD2E6;
      }
    #tabs1 ul {
          margin:0;
          padding:10px 10px 0 5px;
          list-style:none;
      }
    #tabs1 li {
      display:inline;
      margin:0;
      padding:0;
      }
    #tabs1 a {
      float:left;
      background:url("tableft1.gif") no-repeat left top;
      margin:0;
      padding:0 0 0 4px;
      text-decoration:none;
      }
    #tabs1 a span {
      float:left;
      display:block;
      background:url("tabright1.gif") no-repeat right top;
      padding:5px 15px 4px 6px;
      color:#627EB7;
      }
    /* Commented Backslash Hack hides rule from IE5-Mac \*/
    #tabs1 a span {float:none;}
    /* End IE5-Mac hack */
    #tabs a:hover span {
      color:#627EB7;
      }
    #tabs1 a:hover {
      background-position:0% -42px;
      }
    #tabs1 a:hover span {
	background-position: 100% -42px;
	width: auto;
      }

      #tabs1 #current a {
              background-position:0% -42px;
      }
      #tabs1 #current a span {
	background-position: 100% -42px;
	width: auto;
      }
-->
                </style>
        </head>

        <body>
               <div id="tabs1">
                        <ul>
                                <!-- CSS Tabs -->
<li ><a href="../registro_clientes.php" target="productos" ><span>Registro de Clientes</span></a></li>
<li ><a href="../consultar_clientes.php" target="productos" ><span>Consultar Clientes</span></a></li>
<li ><a href="../registrar_ecos.php" target="productos" ><span>Pacientes Ecograficos</span></a></li>
<li ><a href="../lista_pacientes.php" target="productos" ><span>Listado de Pacientes</span></a></li>

                        </ul>
                </div>
 <iframe name="productos" id="productos" style="display:block" align="left" frameborder="0" scrolling="no"  width="800" height="600"  ></iframe>
                
                
        </body>
</html>