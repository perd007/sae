<?php
include("fphp.php");
//mysql_select_db($database_conexion, $conexion);

if ($accion == "requerimiento_detalles_insertar") {
	if ($Tipo == "item") {
		
		$readonly = "readonly";
		$sql = "SELECT * FROM productos where id_productos='$Codigo'";
		$disabled_descripcion = "disabled";
		$id = $Codigo;
		
	} 
	
	$query = mysql_query($sql) or die($sql.mysql_error());
	
	if (mysql_num_rows($query) != 0) {
		$field_detalles = mysql_fetch_array($query);
		 $nombre = $field_detalles['nombre'];
		
		?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_detalles');" id="detalles_<?=$nrodetalle?>">
            <th align="center">
                <?=$nrodetalle?>
            </th>
            <td align="center">
            	<?=$field_detalles['id_productos']?>
                <input type="hidden" name="id<?=$nrodetalle?>" class="cell2" style="text-align:center;" value="<?=$id?>" readonly />
                <input type="hidden" name="nrodetalle" class="cell2" style="text-align:center;" value="<?=$nrodetalle?>" readonly />
                 <input type="hidden" name="candetalle" class="cell2" style="text-align:center;" value="<?=$candetalle?>" readonly />
                
               
            </td>
            <td align="center">
                <textarea name="descripcion<?=$nrodetalle?>" disabled="disabled" style="height:30px;" class="cell" onBlur="this.style.height='30px';" onFocus="this.style.height='60px';"  ><?=$nombre?></textarea>
                 <input type="hidden" name="nombre<?=$nrodetalle?>" class="cell2" style="text-align:center;" value="<?=$nombre?>" readonly />
            </td>
            <td align="center">
                <input type="text" name="Cantidad<?=$nrodetalle?>"  id="Cantidad<?=$nrodetalle?>" onchange="sumaTotal(<?=$nrodetalle?>)" class="cell" style="text-align:right; font-weight:bold;" value="0" />
            </td>
             <td align="center">
                <input type="text" name="costo<?=$nrodetalle?>" id="costo<?=$nrodetalle?>" class="cell" style="text-align:right; font-weight:bold;" value="0" onchange="sumaTotal2(<?=$nrodetalle?>)" onFocus="numeroFocus(this);" />
            </td>
             <td align="center">
                <input type="text" name="total<?=$nrodetalle?>" id="total<?=$nrodetalle?>" disabled="disabled"  class="amt" style="text-align:right; font-weight:bold; background-color:transparent; border:none; width:100%;"  />  
            </td>
		</tr>
         
       <?
	}
}

//	--------------------------



if ($accion == "requerimiento_insertar_producto_apartado") {
	if ($Tipo == "item") {
		
		$readonly = "readonly";
		$sql = "SELECT * FROM productos where id_productos='$Codigo'";
		$disabled_descripcion = "disabled";
		$id = $Codigo;
		
	} 
	
	$query = mysql_query($sql) or die($sql.mysql_error());
	
	if (mysql_num_rows($query) != 0) {
		$field_detalles = mysql_fetch_array($query);
		 $nombre = $field_detalles['nombre'];
		 //evitamos que se aparten mas de un producto a la vez
		if($nrodetalle==1){
		?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_detalles');" id="detalles_<?=$nrodetalle?>">
            <td align="center">
            	<input type="text" name="nombre<?=$nrodetalle?>"  id="nombre<?=$nrodetalle?>"  class="cell" style="text-align:right; font-weight:bold;" value="<?=$field_detalles['nombre']?>" />
                <input type="hidden" name="id<?=$nrodetalle?>" class="cell2" style="text-align:center;" value="<?=$id?>" readonly />
                <input type="hidden" name="nrodetalle" class="cell2" style="text-align:center;" value="<?=$nrodetalle?>" readonly />
                 <input type="hidden" name="candetalle" class="cell2" style="text-align:center;" value="<?=$candetalle?>" readonly />
                
               
            </td>
            <td align="center">
                <input type="text" name="marca<?=$nrodetalle?>"  id="marca<?=$nrodetalle?>"  class="cell" style="text-align:right; font-weight:bold;" value="<?=$field_detalles['marca']?>" />
            </td>
             <td align="center">
                <input type="text" name="modelo<?=$nrodetalle?>"  id="modelo<?=$nrodetalle?>"  class="cell" style="text-align:right; font-weight:bold;" value="<?=$field_detalles['modelo']?>" />
            </td>
            <td align="center">
                <input type="text" name="Cantidad<?=$nrodetalle?>"  id="Cantidad<?=$nrodetalle?>" onchange="sumaTotal(<?=$nrodetalle?>)" class="cell" style="text-align:right; font-weight:bold;" value="0" />
            </td>
             <td align="center">
                <input type="text" name="precio<?=$nrodetalle?>" id="precio<?=$nrodetalle?>" class="cell" style="text-align:right; font-weight:bold;" value="0"  onFocus="numeroFocus(this);" class="amt" />
            </td>
              <td align="center">
                <input type="text" name="abono<?=$nrodetalle?>" id="abono<?=$nrodetalle?>" class="cell" style="text-align:right; font-weight:bold;" value="0" onFocus="numeroFocus(this);"  onchange="sumaTotalApartados(<?=$nrodetalle?>)" />
            </td>
             <td align="center">
             <input type="text" name="restan<?=$nrodetalle?>" id="restan<?=$nrodetalle?>" disabled="disabled"  style="text-align:right; font-weight:bold; background-color:transparent; border:none; width:100%;"  /> 
            
            </td>
		</tr>
         
       <?
		}//fin de la validacion de mas de un producto
	}
}

//	--------------------------

if ($accion == "requerimiento_pedido") {
	if ($Tipo == "item") {
		
		$readonly = "readonly";
		$sql = "SELECT * FROM productos where id_productos='$Codigo'";
		$disabled_descripcion = "disabled";
		$id = $Codigo;
		
	} 
	
	$query = mysql_query($sql) or die($sql.mysql_error());
	
	if (mysql_num_rows($query) != 0) {
		$field_detalles = mysql_fetch_array($query);
		 $nombre = $field_detalles['nombre'];
		
		?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_detalles');" id="detalles_<?=$nrodetalle?>">
            <td align="center">
            	<div style="color: #000;"><?=$field_detalles['id_productos']?></div>
                <input type="hidden" name="id<?=$nrodetalle?>" class="cell2" style="text-align:center;" value="<?=$id?>" readonly />
                <input type="hidden" name="nrodetalle" class="cell2" style="text-align:center;" value="<?=$nrodetalle?>" readonly />
                 <input type="hidden" name="candetalle" class="cell2" style="text-align:center;" value="<?=$candetalle?>" readonly />
                
               
            </td>
            <td align="center">
            <input type="text" name="tipo<?=$nrodetalle?>" id="tipo<?=$nrodetalle?>"  class="cell" disabled="disabled"  value="<?=$field_detalles['tipo']?>"  style="height:30px;"  onBlur="this.style.height='30px';" onFocus="this.style.height='60px';" />
                
            </td>
            <td align="center">
                <input type="text"  name="descripcion<?=$nrodetalle?>" disabled="disabled" style="height:30px;" class="cell" onBlur="this.style.height='30px';" onFocus="this.style.height='60px';" value="<?=$nombre?>"  />
                 <input type="hidden" name="nombre<?=$nrodetalle?>" class="cell2" style="text-align:center;" value="<?=$nombre?>" readonly />
            </td>
            <td align="center">
                <input type="text" name="Cantidad<?=$nrodetalle?>" id="Cantidad<?=$nrodetalle?>"  class="cell" style="text-align:right; font-weight:bold;" onchange="sumaTotal(<?=$nrodetalle?>)" value="0" />
            </td>
             <td align="center">
            <input type="text" name="disponible<?=$nrodetalle?>" id="disponible<?=$nrodetalle?>" class="cell2" style="text-align:center;" value="<?=$disponible?>" readonly />
            </td>
             <td align="center">
                <input type="text" name="costo<?=$nrodetalle?>" disabled="disabled"  class="cell" style="text-align:right; font-weight:bold;" value="<?=$precio?>"  onFocus="numeroFocus(this);" />
                <input type="hidden" name="precio<?=$nrodetalle?>" id="precio<?=$nrodetalle?>"  style="text-align:center;" value="<?=$precio?>" readonly />
            </td>
             <td align="center">
                <input type="text" name="total<?=$nrodetalle?>" id="total<?=$nrodetalle?>"  disabled="disabled"  class="amt" style="text-align:right; font-weight:bold; background-color:transparent; border:none; width:100%;"  />  
            </td>
		</tr>
         
       <?
	}
}


//	--------------------------
if ($accion == "requerimiento_Moto") {
	if ($Tipo == "item") {
		
		$readonly = "readonly";
		$sql = "SELECT * FROM productos where id_productos='$Codigo'";
		$disabled_descripcion = "disabled";
		$id = $Codigo;
		
	} 
	
	$query = mysql_query($sql) or die($sql.mysql_error());
	
	if (mysql_num_rows($query) != 0) {
		$field_detalles = mysql_fetch_array($query);
		 $nombre = $field_detalles['nombre'];
		
		?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_detalles');" id="detalles_<?=$nrodetalle?>">
            <td align="center">
            	<div style="color: #000;"> <input type="text" name="nombre<?=$nrodetalle?>" class="cell2" style="text-align:center;" value="<?=$nombre?>" readonly /></div>
                <input type="hidden" name="id<?=$nrodetalle?>" class="cell2" style="text-align:center;" value="<?=$id?>" readonly />
                <input type="hidden" name="nrodetalle" class="cell2" style="text-align:center;" value="<?=$nrodetalle?>" readonly />
                 <input type="hidden" name="candetalle" class="cell2" style="text-align:center;" value="<?=$candetalle?>" readonly />
                
               
            </td>
            <td align="center">
   <input type="text" name="marca<?=$nrodetalle?>" class="cell2" style="text-align:center;" value="<?=$marca?>" readonly />
            </td>
            <td align="center">
                 <input type="text" name="modelo<?=$nrodetalle?>" class="cell2" style="text-align:center;" value="<?=$modelo?>" readonly />
            </td>
            <td align="center">
                <input type="text" name="Cantidad<?=$nrodetalle?>" id="Cantidad<?=$nrodetalle?>"  class="cell" style="text-align:right; font-weight:bold;" onchange="sumaTotal(<?=$nrodetalle?>)" value="0" />
            </td>
             <td align="center">
            <input type="text" name="disponible<?=$nrodetalle?>" id="disponible<?=$nrodetalle?>" class="cell2" style="text-align:center;" value="<?=$disponible?>" readonly />
            </td>
             <td align="center">
                <input type="text" name="costo<?=$nrodetalle?>" disabled="disabled"  class="cell" style="text-align:right; font-weight:bold;" value="<?=$precio?>"  onFocus="numeroFocus(this);" />
                <input type="hidden" name="precio<?=$nrodetalle?>" id="precio<?=$nrodetalle?>"  style="text-align:center;" value="<?=$precio?>" readonly />
            </td>
             <td align="center">
                <input type="text" name="total<?=$nrodetalle?>" id="total<?=$nrodetalle?>"  disabled="disabled"  class="amt" style="text-align:right; font-weight:bold; background-color:transparent; border:none; width:100%;"  />  
            </td>
		</tr>
         
       <?
	}
}


?>