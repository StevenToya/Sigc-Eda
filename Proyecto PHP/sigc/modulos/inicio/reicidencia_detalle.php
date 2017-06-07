<?php

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


<h2>REICIDENCIAS EN REPARACION</h2>
<div align=right> 						
	<a href="?cmp=index"> <i class="fa fa-reply fa-2x"></i> Volvel al panel inicial </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>



<div class="panel panel-default">
	<div class="panel-heading">
		 Listado de tramites en reincidencia
	</div>
	<div class="panel-body">
		<div class="table-responsive">
		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Orden</th>
						<th>Tipo de trabajo</th>	
						<th>Localidad</th>
						<th>Cantidad </th>
						<th>Fecha</th>
						<th>Det.</th>
					
					</tr>
				</thead>
				<tbody>	
					<?php
					$sql = "SELECT internet, COUNT(internet) AS ti,
							voz, COUNT(voz) AS tv, television, COUNT(television) AS tt, 
							max(tramite.id) AS cod_tramite
							FROM tramite
							INNER JOIN tipo_trabajo 
								ON tramite.id_tipo_trabajo = tipo_trabajo.id
							WHERE 
							(
								internet IS NOT NULL OR 
								voz IS NOT NULL OR
								television IS NOT NULL
							) AND codigo_unidad_operativa =2000 AND tramite.ultimo='s'  AND descripcion_dano  NOT LIKE  '%o masivo%' AND descripcion_dano NOT LIKE  '%Infundado%'
							AND fecha_atencion_orden >= DATE_SUB( CONCAT( CURDATE( ) ,  ' ', CURTIME( ) ) , INTERVAL 30 DAY ) AND tipo_trabajo.tipo=3
							AND tramite.fecha_atencion_orden >= '2016-01-01 00:00:00'
							GROUP BY internet, voz, television 
							HAVING COUNT( * ) >2  ";
					$res = mysql_query($sql);
					while($row = mysql_fetch_array($res))
					{
						$cantidad = 0;
						if($row["ti"]>1){$cantidad = $row["ti"];}
						if($row["tv"]>1){$cantidad = $row["tv"];}
						if($row["tt"]>1){$cantidad = $row["tt"];}
						
						
						$sql_ot = "SELECT tramite.ot , tipo_trabajo.nombre AS nom_tt, localidad.nombre AS nom_localidad, tramite.id, tramite.fecha_atencion_orden
						FROM tramite 
						LEFT JOIN tecnico ON 
							tramite.id_tecnico = tecnico.id
						LEFT JOIN tipo_trabajo ON 
							tramite.id_tipo_trabajo = tipo_trabajo.id
						LEFT JOIN localidad ON 
							tramite.id_localidad = localidad.id
						WHERE tramite.id = '".$row["cod_tramite"]."' LIMIT 1 ";
						$res_ot = mysql_query($sql_ot);
						$row_ot = mysql_fetch_array($res_ot);	?>
							<tr class="odd gradeX">
								<td><b><?php echo $row_ot["ot"] ?></b></td>
								<td><?php echo $row_ot["nom_tt"] ?> </td>
								<td><?php echo $row_ot["nom_localidad"] ?>  </td>
								<td><?php echo $cantidad; ?>  </td>
								<td><?php echo $row_ot["fecha_atencion_orden"] ?></td>
								<td align=center><a href="javascript:popUpmensaje('modulos/liquidacion/detalle.php?id=<?php echo $row_ot["id"]; ?>')"> <i class="fa fa-eye fa-2x"> </i> </a></td>								
						   </tr>
				<?php
				}
				?>
				   
				</tbody>
			</table>

