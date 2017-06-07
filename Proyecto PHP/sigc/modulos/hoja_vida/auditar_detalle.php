<?php
if($PERMISOS_GC["hv_aud"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_POST["guardar"])
{
	$sql = "UPDATE `hv_persona` 
	SET `estado` = '".$_POST["estado"]."', 
	`motivo_rechazo` = '".$_POST["motivo_rechazo"]."' 
	WHERE `id` = '".$_GET["id"]."' LIMIT 1;";
	mysql_query($sql);
	
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_habilitar'>";
	die();
}



$sql = "SELECT hv_persona.nombre, hv_persona.apellido, hv_persona.identificacion, hv_persona.correo, municipio.nombre AS nom_municipio,
		hv_persona.telefono, hv_persona.foto, 	cargo.nombre AS nom_cargo, hv_persona.direccion, hv_persona.estado, hv_persona.motivo_rechazo
FROM hv_persona 
INNER JOIN municipio ON hv_persona.id_municipio = municipio.id
INNER JOIN cargo ON hv_persona.id_cargo = cargo.id
WHERE hv_persona.id = '".$_GET["id"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);

?>

<h2>HOJA DE VIDA</h2>

<div align=right>
	<a href="?cmp=lista_habilitar"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de hojas de vida </a>
</div>
<br>


<table width="60%" border=0 align=center>
	<tr>
		<td colspan=3  valign=top>
			 <div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Datos Basicos
					</div>
					<div class="panel-body" align=center>
						<table>
							<tr>
								<td width="30%">
									<?php
									$tmp = rand(1,100);
									if(!$row["foto"]){$row["foto"] = 'img/find_user.png';}
									?>
									<img src="<?php echo $row["foto"] ?>?tem=<?php echo $tmp; ?>" width="210">
								</td>
								<td valign=top width="70%">
										<table width="100%">
											<tr>
												<td align=right> Nombre</td>
												<td>: <b><?php echo $row["nombre"] ?></b></td>
												<td align=right> Apellido</td>
												<td>: <b><?php echo $row["apellido"] ?></b></td>
											</tr>											
											<tr>
												<td colspan=4><br></td>
											</tr>											
											<tr>
												<td align=right> Identificacion</td>
												<td>: <b><?php echo $row["identificacion"] ?></b></td>
												<td align=right> Direccion</td>
												<td>: <b><?php echo $row["direccion"] ?></b></td>
											</tr>
											<tr>
												<td colspan=4><br></td>
											</tr>
											<tr>
												<td align=right> Telefono</td>
												<td>: <b><?php echo $row["telefono"] ?></b></td>
												<td align=right> E-mail</td>
												<td>: <b><?php echo $row["correo"] ?></b></td>
											</tr>
											<tr>
												<td colspan=4><br></td>
											</tr>
											<tr>
												<td align=right> Municipio</td>
												<td>: <b><?php echo $row["nom_municipio"] ?></b></td>
												<td align=right> Cargo</td>
												<td>: <b><?php echo $row["nom_cargo"] ?></b></td>
											</tr>
																						
										</table>
										
										
								</td>
							</tr>
						</table>
						
						
					</div>
					
				</div>
			</div>
		</td>		
	</tr>
</table>	
	
<table width="100%" align=center>
	<tr>
		<td width="45%" valign=top>
			 <div class="col-md-33 col-sm-33">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Referencias familiares
					</div>
					<div class="panel-body">
						<!-- --->
						<div class="panel panel-default">									
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Nombre Apellido</th>
										<th>Telefono</th>
										
									</tr>
								</thead>
								<tbody>
								<?php
								$sql_ref = "SELECT * FROM hv_referencia WHERE tipo=1  AND id_persona = '".$_GET["id"]."' ORDER BY nombre";
								$res_ref = mysql_query($sql_ref);
								while($row_ref = mysql_fetch_array($res_ref))
								{
								?>
									<tr>
										<td><?php echo $row_ref["nombre"] ?></td>
										<td><?php echo $row_ref["celular"] ?></td>										
									</tr>
								<?php
								}
								?>
								
								</tbody>
							</table>									
						</div>
						<!-- --->
					</div>
					
				</div>
			</div>
		</td>
		
		<td width="5%" valign=top>
		
		</td>
		
		<td width="45%" valign=top>
			<div class="col-md-33 col-sm-33">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Referencias personales
					</div>
					<div class="panel-body">
						<!-- --->
						<div class="panel panel-default">									
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Nombre Apellido</th>
										<th>Telefono</th>									
									</tr>
								</thead>
								<tbody>
								<?php
								$sql_ref = "SELECT * FROM hv_referencia WHERE tipo=2 AND id_persona = '".$_GET["id"]."' ORDER BY nombre";
								$res_ref = mysql_query($sql_ref);
								while($row_ref = mysql_fetch_array($res_ref))
								{
								?>
									<tr>
										<td><?php echo $row_ref["nombre"] ?></td>
										<td><?php echo $row_ref["celular"] ?></td>
										
									</tr>
								<?php
								}
								?>
								
								</tbody>
							</table>									
						</div>
						<!-- --->
					</div>
				
				</div>
			</div>
		</td>
		
	</tr>
</table>



<!-- ESTUDIOS -->

<table width="100%" border=0 align=center>
	<tr>
		<td colspan=3  valign=top>
			 <div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Estudios realizados
					</div>
					<div class="panel-body" align=center>
						<div class="panel panel-default">									
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Nivel de estudio</th>
										<th>Nombre institucion</th>
										<th>Lugar - direccion</th>
										<th>Titulo</th>
										<th>Fecha inicial</th>
										<th>Fecha final</th>
									
									</tr>
								</thead>
								<tbody>
								<?php
								$sql_ref = "SELECT * FROM hv_estudio WHERE id_persona = '".$_GET["id"]."' ORDER BY fecha_inicial";
								$res_ref = mysql_query($sql_ref);
								while($row_ref = mysql_fetch_array($res_ref))
								{
								?>
									<tr>
										<td><?php echo $row_ref["nivel"] ?></td>
										<td><?php echo $row_ref["instituto"] ?></td>
										<td><?php echo $row_ref["direccion"] ?></td>
										<td><?php echo $row_ref["nombre"] ?></td>
										<td><?php echo $row_ref["fecha_inicial"] ?></td>
										<td><?php echo $row_ref["fecha_final"] ?></td>
										
									</tr>
								<?php
								}
								?>
								
								</tbody>
							</table>									
						</div>			
					</div>
				
				</div>
			</div>
		</td>		
	</tr>
</table>



<!-- LABORAL -->

<table width="100%" border=0 align=center>
	<tr>
		<td colspan=3  valign=top>
			 <div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Experiencia laboral
					</div>
					<div class="panel-body" align=center>
						<div class="panel panel-default">									
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Empresa</th>
										<th>Cargo</th>
										<th>Jefe Inmediato</th>
										<th>Telefono jefe</th>
										<th>Fecha inicial</th>
										<th>Fecha final</th>
								
									</tr>
								</thead>
								<tbody>
								<?php
								$sql_ref = "SELECT * FROM hv_laboral WHERE id_persona = '".$_GET["id"]."' ORDER BY fecha_inicial";
								$res_ref = mysql_query($sql_ref);
								while($row_ref = mysql_fetch_array($res_ref))
								{
								?>
									<tr>
										<td><?php echo $row_ref["empresa"] ?></td>
										<td><?php echo $row_ref["cargo"] ?></td>
										<td><?php echo $row_ref["jefe"] ?></td>
										<td><?php echo $row_ref["jefe_telefono"] ?></td>
										<td><?php echo $row_ref["fecha_inicial"] ?></td>
										<td><?php echo $row_ref["fecha_final"] ?></td>
										
									</tr>
								<?php
								}
								?>
								
								</tbody>
							</table>									
						</div>			
					</div>
				
				</div>
			</div>
		</td>		
	</tr>
</table>



