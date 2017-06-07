<?php  
if($PERMISOS_GC["aud_gest"]!='Si'){   
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}  

?>

<h2>TRAMITES POR REALIZAR</h2>

<table width=100%>
	<tr>
		<td>
			
		</td>
		<td align=right>
			<a href="?cmp=lista_gestion"> <i class="fa fa-reply fa-2x"></i> Volver al listado de auditorias </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
		</td>
	</tr>
</table>
<br>

<center>					
	<div class="col-md-100 col-sm-100" style="width:100%">
		<div class="panel panel-primary" >
			<div class="panel-heading"  align=left>
				<b>Tramites por tipo de trabajo</b>							
			</div>
			<div class="panel-body">
				<table  class="table table-striped table-bordered table-hover" align=center width=100%>
					<tr>
						<th>Tipo de Trabajo</th>
						<th>Pedido</th>
						<th>Tecnologia</th>
						<th>Zona</th>
						<th>Localidad</th>
						<th>Barrio</th>
						<th><center>Ver</center></th>
					</tr>
					<?php
					
						$sql = "SELECT * FROM sra_tramite WHERE fecha_cita = '".date("Y-m-d")."' ORDER BY franja_horaria, zona, localidad";
						$res = mysql_query($sql);
						while($row = mysql_fetch_array($res))
						{									
							?>
							<tr>
								<td><?php echo $row["tipo_trabajo"] ?></td>
								<td><?php echo $row["pedido"] ?></td>
								<td><?php echo $row["fecha_cita"] ?> (<?php echo $row["franja_horaria"] ?>)</td>
								<td><?php echo $row["zona"] ?></td>
								<td><?php echo $row["localidad"] ?></td>	
								<td><?php echo $row["barrio"] ?></td>										
								<td align=center> <a href="?cmp=online_asignar&id=<?php echo $row["id"]; ?>"> <i class="fa fa-eye fa-2x"> </i> </a></td>
							</tr>
							<?php
						}							
						?>		
				</table>
			</div>
		</div>
	</div>						
</center>
