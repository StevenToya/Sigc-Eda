<?php
if($_GET["excel"])
{
	session_start();
	include("../../cnx.php");
	include("../../query.php");
	
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte_tecnico.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	?>
		<table border=1>
				<tr>
					<th>Nombre</th>
					<th>Cedula</th>
					<th>Coordinador</th>
					<th>Torre</th>
					<th>Instalaciones</th>
					<th>Reparacion</th>
					<th>Retiro </th>
					<th>Localidades</th>						
					<th>Fecha actualizacion</th>							
				</tr>
			<?php
			
			$sql = "SELECT instalacion, reparacion, retiro, front_tecnico.fecha, front_tecnico.fecha_registro,  front_tecnico.nombre,
			front_tecnico.cedula, usuario.nombre AS nom_usuario, usuario.apellido AS ape_usuario, front_tecnico.id, front_torre.nombre AS nom_torre
			FROM front_tecnico  
			INNER JOIN usuario ON front_tecnico.id_usuario = usuario.id
			LEFT JOIN front_torre ON front_tecnico.id_torre = front_torre.id
			ORDER BY front_tecnico.nombre ASC";
			$res = mysql_query($sql);
			while($row = mysql_fetch_array($res))
			{						
				if($row["instalacion"]=='s'){$instalacion = ' SI '; $instalacion_col = 'style="background-color:#A9F5A9" ';}else{$instalacion = ' NO '; $instalacion_col = "";}
				if($row["reparacion"]=='s'){$reparacion = ' SI ';  $reparacion_col = ' style="background-color:#A9F5A9" '; }else{$reparacion = ' NO '; $reparacion_col = "";}
				if($row["retiro"]=='s'){$retiro = ' SI ';  $retiro_col = ' style="background-color:#A9F5A9" '; }else{$retiro = ' NO ';  $retiro_col = ""; }
				
				if($row["fecha"] != date("Y-m-d")){$fecha_color = 'red';}else{$fecha_color = 'green';}
			?>
				<tr>
					<td> <?php echo $row["nombre"] ?> </td>
					<td><b> <?php echo $row["cedula"] ?> </b></td>
					<td> <?php echo $row["nom_usuario"] ?>  <?php echo $row["ape_usuario"] ?> </td>
					<td><b> <?php echo $row["nom_torre"] ?> </b></td>
					<td  align=center <?php echo $instalacion_col ?>><b> <?php echo $instalacion ?></b></td>
					<td align=center <?php echo $reparacion_col ?>><b> <?php echo $reparacion ?></b></td>
					<td align=center <?php echo $retiro_col ?> ><b> <?php echo $retiro ?> </b></td>
					<td>
						<?php  $sql_cont = " SELECT front_tecnico_localidad.id_tecnico , front_localidad.nombre
								FROM front_tecnico_localidad
								INNER JOIN front_localidad 
									ON  front_tecnico_localidad.id_localidad = front_localidad.id
								WHERE
									front_tecnico_localidad.id_tecnico = '".$row["id"]."' 
								ORDER BY front_localidad.nombre ";
								$res_cont = mysql_query($sql_cont);
								while($row_cont = mysql_fetch_array($res_cont))
								{
						?>
								<?php echo $row_cont["nombre"]; ?>, 
						<?php
								}
						?>
					</td>	
					<td><center> <font color="<?php echo $fecha_color ?>"><?php echo $row["fecha_registro"] ?></font></center></td>
					
			   </tr>
			<?php
			}
			?>				   
			
		</table>
	<?php
	die();
}




/*
if($PERMISOS_GC["sto_coor"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}
*/




?>

<h2> SU PERSONAL ASIGNADO	 </h2>
<div align=right>
	<a href="modulos/front/lista_personal.php?excel=1" target="blank"><i class="fa fa-cloud-download fa-2x"></i> Descargar en Excel </a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
</div>


<div class="panel panel-default"  style="width:99%">
	<div class="panel-heading" align=left>
		<b> Personal para gestionar </b>
	</div>
	<div class="panel-body" >
		<div class="table-responsive" >
		
			<table class="table table-striped table-bordered table-hover" >
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Cedula</th>
						<th>Coordinador</th>
						<th>Torre</th>
						<th>Instalaciones</th>
						<th>Reparacion</th>
						<th>Retiro </th>
						<th>Localidades</th>						
						<th>Fecha actualizacion</th>							
					</tr>
				</thead>
				<tbody>
				<?php
				$vec_tem = $vec_item; 
				$sql = "SELECT instalacion, reparacion, retiro, front_tecnico.fecha, front_tecnico.fecha_registro,  front_tecnico.nombre,
				front_tecnico.cedula, usuario.nombre AS nom_usuario, usuario.apellido AS ape_usuario, front_tecnico.id, front_torre.nombre AS nom_torre
				FROM front_tecnico  
				INNER JOIN usuario ON front_tecnico.id_usuario = usuario.id
				LEFT JOIN front_torre ON front_tecnico.id_torre = front_torre.id
				ORDER BY front_tecnico.nombre ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{						
					if($row["instalacion"]=='s'){$instalacion = ' SI '; $instalacion_col = 'style="background-color:#A9F5A9" ';}else{$instalacion = ' NO '; $instalacion_col = "";}
					if($row["reparacion"]=='s'){$reparacion = ' SI ';  $reparacion_col = ' style="background-color:#A9F5A9" '; }else{$reparacion = ' NO '; $reparacion_col = "";}
					if($row["retiro"]=='s'){$retiro = ' SI ';  $retiro_col = ' style="background-color:#A9F5A9" '; }else{$retiro = ' NO ';  $retiro_col = ""; }
					
					if($row["fecha"] != date("Y-m-d")){$fecha_color = 'red';}else{$fecha_color = 'green';}
				?>
					<tr class="odd gradeX">
						<td> <?php echo $row["nombre"] ?> </td>
						<td><b> <?php echo $row["cedula"] ?> </b></td>
						<td> <?php echo $row["nom_usuario"] ?>  <?php echo $row["ape_usuario"] ?> </td>
						<td><b> <?php echo $row["nom_torre"] ?> </b></td>
						<td  align=center <?php echo $instalacion_col ?>><b> <?php echo $instalacion ?></b></td>
						<td align=center <?php echo $reparacion_col ?>><b> <?php echo $reparacion ?></b></td>
						<td align=center <?php echo $retiro_col ?> ><b> <?php echo $retiro ?> </b></td>
						<td>
							<?php  $sql_cont = " SELECT front_tecnico_localidad.id_tecnico , front_localidad.nombre
									FROM front_tecnico_localidad
									INNER JOIN front_localidad 
										ON  front_tecnico_localidad.id_localidad = front_localidad.id
									WHERE
										front_tecnico_localidad.id_tecnico = '".$row["id"]."' 
									ORDER BY front_localidad.nombre ";
									$res_cont = mysql_query($sql_cont);
									while($row_cont = mysql_fetch_array($res_cont))
									{
							?>
									<?php echo $row_cont["nombre"]; ?>, 
							<?php
									}
							?>
						</td>	
						<td><center> <font color="<?php echo $fecha_color ?>"><?php echo $row["fecha_registro"] ?></font></center></td>
						
				   </tr>
				<?php
				}
				?>				   
				</tbody>
			</table>
		</div>		
	</div>
</div>


