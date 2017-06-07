<?php
if($_GET["tipo"]==1){$tit = "LIGERAMENTE ALTO";}
if($_GET["tipo"]==2){$tit = "ALTO";}
if($_GET["tipo"]==3){$tit = "MUY ALTO";}


if($_GET["excel"])
{
	session_start();
	include("../../cnx.php");
	include("../../query.php");
	
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0"); 	
	?>
	<table border=1>
		<tr>
			<th>Orden</th>
			<th>Tipo de trabajo</th>	
			<th>Localidad</th>
			<th>Material detectado</th>
			<th>Cantidad</th>
			<th>Fecha</th>		
		</tr>
	<?php
					$sql = "SELECT tipo_trabajo.nombre AS nom_tt, tramite.ot,  localidad.nombre AS nom_localidad, equipo_material.nombre AS nom_material, 
					material_traza.cantidad, tramite.fecha_atencion_orden, equipo_material.codigo_1, tramite.id, reporte_alarma_equipo_material.id AS rid
					FROM reporte_alarma_equipo_material
					INNER JOIN conf_alarma_equipo_material ON 
						reporte_alarma_equipo_material.id_conf_equipo_material = conf_alarma_equipo_material.id
					INNER JOIN equipo_material ON 
						conf_alarma_equipo_material.id_equipo_material = equipo_material.id
					INNER JOIN tramite ON 
						reporte_alarma_equipo_material.id_tramite = tramite.id
					INNER JOIN material_traza ON 
						tramite.id = material_traza.id_tramite AND equipo_material.id = material_traza.id_equipo_material 
					LEFT JOIN tecnico ON 
						tramite.id_tecnico = tecnico.id
					LEFT JOIN tipo_trabajo ON 
						tramite.id_tipo_trabajo = tipo_trabajo.id
					LEFT JOIN localidad ON 
						tramite.id_localidad = localidad.id
					WHERE 
						reporte_alarma_equipo_material.estado='1' 
						AND conf_alarma_equipo_material.tipo='".$_GET["tipo"]."' 
						AND tramite.fecha_atencion_orden >= '2016-01-01 00:00:00'
						";
					$res = mysql_query($sql);
					while($row = mysql_fetch_array($res))
					{
	?>
							<tr >
								<td><b><?php echo $row["ot"] ?></b></td>
								<td><?php echo $row["nom_tt"] ?> </td>
								<td><?php echo $row["nom_localidad"] ?> <?php echo $row["apellido"] ?> </td>
								<td><?php echo $row["nom_material"] ?> (<?php echo $row["codigo_1"] ?>) </td>
								<td><?php echo $row["cantidad"] ?></td>
								<td><?php echo $row["fecha_atencion_orden"] ?></td>								
						   </tr>
				<?php
				}
				?>
	
		</table>
	
	<?php
	die();
}






if($_GET["quitar"])
{
	$sql_q = "UPDATE `reporte_alarma_equipo_material` SET `estado` = '2' WHERE `id` = '".$_GET["quitar"]."' LIMIT 1";
	mysql_query($sql_q);
}

?>

<script type="text/javascript">

function popUpmensaje(URL) 
{
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1350,height=650,left = 200,top = 5');");
}

</script>


<h2>MATERIALES FUERA DEL CONSUMO NORMAL</h2>
<div align=right> 						
	<a href="modulos/inicio/material_detalle.php?excel=1&tipo=<?php echo $_GET["tipo"]; ?>" target="blank"><i class="fa fa-cloud-download fa-2x"></i> Descargar en Excel </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="?cmp=index"> <i class="fa fa-reply fa-2x"></i> Volvel al panel inicial </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>



<div class="panel panel-default">
	<div class="panel-heading">
		 Listado de tramites <b><font color=red><?php echo $tit ?></font></b> en materiales
	</div>
	<div class="panel-body">
		<div class="table-responsive">
		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Orden</th>
						<th>Tipo de trabajo</th>	
						<th>Localidad</th>
						<th>Material detectado</th>
						<th>Cantidad</th>
						<th>Fecha</th>
						<th>Det.</th>
						<th>Quitar<br>Alarma</th>
					</tr>
				</thead>
				<tbody>	
					<?php
					$sql = "SELECT tipo_trabajo.nombre AS nom_tt, tramite.ot,  localidad.nombre AS nom_localidad, equipo_material.nombre AS nom_material, 
					material_traza.cantidad, tramite.fecha_atencion_orden, equipo_material.codigo_1, tramite.id, reporte_alarma_equipo_material.id AS rid
					FROM reporte_alarma_equipo_material
					INNER JOIN conf_alarma_equipo_material ON 
						reporte_alarma_equipo_material.id_conf_equipo_material = conf_alarma_equipo_material.id
					INNER JOIN equipo_material ON 
						conf_alarma_equipo_material.id_equipo_material = equipo_material.id
					INNER JOIN tramite ON 
						reporte_alarma_equipo_material.id_tramite = tramite.id
					INNER JOIN material_traza ON 
						tramite.id = material_traza.id_tramite AND equipo_material.id = material_traza.id_equipo_material 
					LEFT JOIN tecnico ON 
						tramite.id_tecnico = tecnico.id
					LEFT JOIN tipo_trabajo ON 
						tramite.id_tipo_trabajo = tipo_trabajo.id
					LEFT JOIN localidad ON 
						tramite.id_localidad = localidad.id
					WHERE reporte_alarma_equipo_material.estado='1' 
					AND conf_alarma_equipo_material.tipo='".$_GET["tipo"]."' 
					AND tramite.fecha_atencion_orden >= '2016-01-01 00:00:00'";
					$res = mysql_query($sql);
					while($row = mysql_fetch_array($res))
					{
	?>
							<tr class="odd gradeX">
								<td><b><?php echo $row["ot"] ?></b></td>
								<td><?php echo $row["nom_tt"] ?> </td>
								<td><?php echo $row["nom_localidad"] ?> <?php echo $row["apellido"] ?> </td>
								<td><?php echo $row["nom_material"] ?> (<?php echo $row["codigo_1"] ?>) </td>
								<td><?php echo $row["cantidad"] ?></td>
								<td><?php echo $row["fecha_atencion_orden"] ?></td>
								<td align=center><a href="javascript:popUpmensaje('modulos/liquidacion/detalle.php?id=<?php echo $row["id"]; ?>')"> <i class="fa fa-eye fa-2x"> </i> </a></td>
								<td align=center><a  onclick="if(confirm('¿ Realmente desea QUITAR la alarma a este tramite  ?') == false){return false;}" href="?tipo=<?php echo $_GET["tipo"]; ?>&quitar=<?php echo $row["rid"]; ?>"><font color=red><i class="fa fa-eye-slash fa-2x"></i></font></a></td>				
						   </tr>
				<?php
				}
				?>
				   
				</tbody>
			</table>

