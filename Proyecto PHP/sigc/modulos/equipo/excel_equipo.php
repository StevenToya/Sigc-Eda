<?php
	include("../../cnx.php");		
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0");	
	?>
		<table border=1>
			<tr>
				<th bgcolor=red>Serial</th>
				<th bgcolor=red>Equipo</th>
				<th bgcolor=red>Estado</th>
				<th bgcolor=red>OT</th>
				<th bgcolor=red>Unidad operativa</th>
				<th bgcolor=red>Codigo Unidad operativa</th>
				<th bgcolor=red>Fecha de atencion</th>
				<th bgcolor=red>Localidad</th>
				<th bgcolor=red>Ult Pedido</th>
			</tr>	
	
	<?php
	$query = "";
	if($_GET["id"])
	{
		$query = " AND equipo_serial.id_equipo_material='".$_GET["id"]."'  ";
	}

	$sql = "SELECT equipo_serial.serial, equipo_material.nombre AS nom_equipo, tramite.ot, tramite.unidad_operativa, tramite.codigo_unidad_operativa,
	localidad.nombre AS nom_localidad, pedido_equipo_material.numero, fecha_atencion_orden, equipo_serial.estado
	FROM equipo_serial
			LEFT JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id
			LEFT JOIN pedido_equipo_material ON equipo_serial.id_pedido = pedido_equipo_material.id
			LEFT JOIN tramite ON equipo_serial.id_tramite = tramite.id
			LEFT JOIN localidad ON tramite.id_localidad = localidad.id
			WHERE 
				equipo_serial.estado <> 1 
				AND fecha_atencion_orden >= '".$_GET["ff"]."-01 00:00:00'
				AND fecha_atencion_orden <= '".$_GET["ff"]."-31 23:59:59' 
			 	".$query." ;";		
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		if($row["estado"]==1){$estado= "<font color=red><b>Libre</b></font>";}
		if($row["estado"]==2){$estado= "<font color=green><b>Instalado</b></font>";}
		if($row["estado"]==3){$estado= "<font color=red><b>Malo</b></font>";}
		if($row["estado"]==4){$estado= "<font color=red><b>Perdido</b></font>";}										
		if($row["estado"]==5){$estado= "<font>Instalado antes</font>";}
		if($row["estado"]==6){$estado= "<font>Retirado</font>";}
		?>
			<tr>
				<td><?php echo $row["serial"] ?></td>
				<td><?php echo $row["nom_equipo"] ?></td>
				<td><?php echo $estado; ?></td>
				<td><?php echo $row["ot"]; ?></td>
				<td><?php echo $row["unidad_operativa"]; ?></td>
				<td><?php echo $row["codigo_unidad_operativa"]; ?></td>
				<td><?php echo $row["fecha_atencion_orden"]; ?></td>
				<td><?php echo $row["nom_localidad"]; ?></td>
				<td><?php echo $row["numero"]; ?></td>
			
			</tr>
		
		<?php
		
	}
	?>
	</table>

	