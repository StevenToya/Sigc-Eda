<h2>LISTADO DE SERIALES</h2>

<div align=right>
	<a href="?cmp=panel_equipo"> <i class="fa fa-reply fa-2x"></i> Volver a la lista de pedidos pendientes</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div><br>
<?php
$sql = "SELECT numero FROM pedido_equipo_material WHERE id = '".$_GET["id_pedido"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
$numero_pedido = $row["numero"]; 


$sql = "SELECT nombre FROM equipo_material WHERE id = '".$_GET["id_equipo"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
$nombre_equipo = $row["nombre"]; 

?>

<center>
<div class="panel panel-default" style="width:100%;">
   <div align=left class="panel-heading">Seriales del equipo  <font color=red><b><?php echo $nombre_equipo; ?></b></font> para el pedido <font color=red><b><?php echo $numero_pedido; ?></b></font></div>
  <table class="table" align=center>
   <tr>
		<th>Serial</th>
		<th>Estado</th>
		<th>Fecha</th>
		<th>Localidad</th>
		<th>OT</th>
		<th>Usado en</th>
		<th>Tecnico</th>
		<!-- <th>Detalle</th> -->
	
   </tr>
   <?php
	$sql = "SELECT equipo_serial.serial, equipo_serial.estado, tramite.fecha_atencion_orden, localidad.nombre, tramite.ot, 
					equipo_serial.fecha_registro, tecnico.nombre AS nom_tecnico, tramite.tipo_paquete, equipo_serial.id_tramite
					FROM equipo_serial
					INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id
					LEFT JOIN tramite ON equipo_serial.id_tramite = tramite.id
					LEFT JOIN localidad ON equipo_serial.id_localidad = localidad.id
					LEFT JOIN tecnico ON tramite.id_tecnico = tecnico.id
				  WHERE equipo_material.id = '".$_GET["id_equipo"]."' AND equipo_serial.id_pedido= '".$_GET["id_pedido"]."'  ;" ;
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{			
		/*
		if($row["id_tramite"])
		{$estado = '<font color=green><b>En uso</b></font>'; $fecha = $row["fecha_atencion_orden"];}
		else
		{$estado = '<font color=red><b>Libre</b></font>'; $fecha = $row["fecha_registro"];}
		*/
		
		
		if($row["estado"]!=1)
		{$estado = '<font color=green><b>En uso</b></font>'; $fecha = $row["fecha_atencion_orden"];}
		else
		{$estado = '<font color=red><b>Libre</b></font>'; $fecha = $row["fecha_registro"];}
	

		?>
			<tr>
				<td><b><?php echo $row["serial"] ?></b></td>
				<td><b><?php echo $estado ?></b></td>
				<td><?php echo  $fecha ?></td>
				<td><?php echo $row["nombre"] ?></td>
				<td><?php echo $row["ot"] ?></td>
				<td><?php echo $row["tipo_paquete"] ?></td>
				<td><?php echo $row["nom_tecnico"] ?> </td>
				<!-- <td align=center><i class="fa fa-search-plus fa-2x"></i></td> -->				
		   </tr>
		   <?php
			
			
	}

   ?>
   

  </table>
</div>
</center>

<br><br>