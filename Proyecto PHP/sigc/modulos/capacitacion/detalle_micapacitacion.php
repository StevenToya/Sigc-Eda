<?php
if($PERMISOS_GC["cap_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


$sql = "SELECT programa.nombre, capacitacion.fecha, capacitacion.hora_inicial, capacitacion.hora_final, capacitacion.lugar, capacitacion.id, capacitacion.documento, 
			capacitacion.total_hora, capacitacion.observacion, programa.id AS id_prog
		FROM capacitacion
		INNER JOIN programa ON capacitacion.id_programa = programa.id							
		WHERE capacitacion.id = '".$_GET["id"]."' LIMIT 1 ";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);

?> 
<h2> DETALLES DE LA CAPACITACION </h2>  
	
		<div align=right>
			<a href="?cmp=mis_capacitaciones"> <i class="fa fa-reply fa-2x"></i> Volver al listado de programas </a>
		</div>
		<br>
		<table  align ="center" border=0 width="90%">
			<tr><td>	
					<div class="panel panel-primary">
						<div class="panel-heading">
							<font size="2">Dato General</font>
						</div>
						<div class="panel-body" align=center>
							<h4>
							<table  align ="left" border=0 width="100%"  border=1>
								<tr>
									<td > Programa dictado: </td> <td><b><?php echo $row["nombre"] ?></b></td>
								</tr>
								<tr><td><br></td></tr>
								<tr>
									<td > Lugar en que se realizo: </td> <td><b><?php echo $row["lugar"] ?></b></td>
								</tr>
								<tr><td><br></td></tr>
								<tr>
									<td  >Fecha y hora:</td> <td><b> <?php echo $row["fecha"] ?> de <?php echo $row["hora_inicial"] ?> a <?php echo $row["hora_final"] ?></b></td>									
								</tr>
								
								<?php if($row["documento"]){ ?>
								<tr><td><br></td></tr>
									<tr>
										<td  >Documento adjuntado:</td> <td><b> <a href="<?php echo $row["documento"]; ?>" target="blank">Descargar</a></b></td>									
									</tr>
								<?php } ?>
								
								<tr><td><br></td></tr>
								<tr>
									<td  >Observacion:</td> <td><b> <?php echo $row["observacion"] ?> </b></td>									
								</tr>
							</table>
							</h4>
						</div>
					</div>
			</td></tr>
		</table>
		
		<table  align ="center" border=0 width="90%">
			<tr><td>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<font size="2">Personal Presente en la Capacitacion</font>
					</div>
					<div class="panel-body" align=center>
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
								<tr>
									<th>Apellido Nombre</th>
									<th>Identificacion</th>
									<th>Rol</th>													
									</tr>
							</thead>
							<tbody>
							<?php
								$sql_per = "SELECT hv_persona.nombre, hv_persona.apellido, hv_persona.identificacion, cargo.nombre AS rol
								FROM capacitacion_persona
								INNER JOIN hv_persona ON capacitacion_persona.id_persona = hv_persona.id 
								INNER JOIN cargo ON hv_persona.id_cargo = cargo.id 
								WHERE capacitacion_persona.id_capacitacion = '".$_GET["id"]."' 
								ORDER BY hv_persona.apellido, hv_persona.nombre";
								$res_per = mysql_query($sql_per);
								while($row_per = mysql_fetch_array($res_per))
								{
							?>
								<tr>
									<td><?php echo $row_per["apellido"] ?> <?php echo $row_per["nombre"] ?> </td>
									<td><?php echo $row_per["identificacion"] ?></td>
									<td><?php echo $row_per["rol"] ?></td>
								</tr>
							<?php
								}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</td></tr>
		</table>
		