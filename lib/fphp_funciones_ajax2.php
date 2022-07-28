<?php
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);
//	--------------------------
if ($accion == "requerimiento_detalles_insertar") {
	if ($Tipo == "item") {
		$readonly = "readonly";
		$sql = "SELECT * 
				FROM productos
				WHERE id_productos = '".$Codigo."'";
		$disabled_descripcion = "disabled";
		$CodItem = $Codigo;
	} 
	$query = mysql_query($sql,$conexion) or die($sql.mysql_error());
	if (mysql_num_rows($query) != 0) {
		$field_detalles = mysql_fetch_array($query);
		if ($Tipo == "item" ) $Descripcion = $field_detalles['Descripcion'];
		?>
        <tr class="trListaBody" onclick="mClk(this, 'sel_detalles');" id="detalles_<?=$nrodetalle?>">
            <th align="center">
                <?=$nrodetalle?>
            </th>
            <td align="center">
            	<?=$Codigo?>
                <input type="hidden" name="CodItem" class="cell2" style="text-align:center;" value="<?=$id_productos?>" readonly />
                <input type="hidden" name="CommoditySub" class="cell2" style="text-align:center;" value="<?=$CommoditySub?>" readonly />
            </td>
            <td align="center">
                <textarea name="Descripcion" style="height:30px;" class="cell" onBlur="this.style.height='30px';" onFocus="this.style.height='60px';" <?=$disabled_descripcion?>><?=($Descripcion)?></textarea>
            </td>
            <td align="center">
                <input type="text" name="CantidadPedida" class="cell" style="text-align:right; font-weight:bold;" value="0,00" onBlur="numeroBlur(this);" onFocus="numeroFocus(this);" />
            </td>
		</tr>
       <?
	}
}



?>