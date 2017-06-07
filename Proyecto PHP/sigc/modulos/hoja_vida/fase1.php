<?php
if($PERMISOS_GC["hv_cre"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}
if($_GET["conf"])
{
	$sql = "UPDATE hv_persona SET estado = '2'  WHERE id ='".$_GET["id"]."' LIMIT 1 ;";
	mysql_query($sql);	
	if(!mysql_error())
	{
		 echo '<script >alert("Hoja de vida enviada para su auditoria");</script>';
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_pendiente'>";
		 die();
	}else{
		
		$error= "ERROR BASE DE DATOS:".mysql_error();
	}
}

if($_GET["rf"])
{
	$sql_ins = "INSERT INTO `hv_referencia` (`id_persona` ,`tipo` ,`nombre` ,`celular` ,`fecha_registro`)
			VALUES ('".$_GET["id"]."', '".$_GET["rf"]."', '".ucwords(strtolower(trim($_POST["nombre"])))."', '".$_POST["celular"]."', '".date("Y-m-d G:i:s")."');";
	mysql_query($sql_ins);	
}

if($_GET["lab"])
{
	 $sql_ins = "INSERT INTO `hv_laboral` (`id_persona` ,`empresa` ,`cargo` ,`jefe`, `jefe_telefono`, `fecha_inicial`, `fecha_final`)
			VALUES ('".$_GET["id"]."', '".ucwords(strtolower(trim($_POST["empresa"])))."', '".ucwords(strtolower(trim($_POST["cargo"])))."', '".ucwords(strtolower(trim($_POST["jefe"])))."', '".$_POST["jefe_telefono"]."', '".$_POST["fecha_inicial"]."', '".$_POST["fecha_final"]."');";
	mysql_query($sql_ins);	
}

if($_GET["est"])
{
	$sql_ins = "INSERT INTO `hv_estudio` (`id_persona` ,`nivel` ,`instituto` ,`direccion`, `nombre`, `fecha_inicial`, `fecha_final`)
			VALUES ('".$_GET["id"]."', '".$_POST["nivel"]."', '".ucwords(strtolower(trim($_POST["instituto"])))."', '".$_POST["direccion"]."', '".ucwords(strtolower(trim($_POST["nombre"])))."', '".$_POST["fecha_inicial"]."', '".$_POST["fecha_final"]."');";
	mysql_query($sql_ins);	
}

if($_GET["eli_ref"])
{
	$sql_eli = "DELETE FROM `hv_referencia` WHERE `id` = '".$_GET["eli_ref"]."' LIMIT 1;";
	mysql_query($sql_eli);	
}

if($_GET["eli_est"])
{
	$sql_eli = "DELETE FROM `hv_estudio` WHERE `id` = '".$_GET["eli_est"]."' LIMIT 1;";
	mysql_query($sql_eli);
}

if($_GET["eli_lab"])
{
	$sql_eli = "DELETE FROM `hv_laboral` WHERE `id` = '".$_GET["eli_lab"]."' LIMIT 1;";
	mysql_query($sql_eli);
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

<h2>HOJA DE VIDA INICIALIZADA</h2>

<div align=right>
	<a href="?cmp=lista_pendiente"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de hojas de vida </a>
</div>
<br>
<?php if($row["estado"]=='6'){ ?>
	<div class="alert alert-info">
		<b>HOJA DE VIDA RECHAZADA</b><br>
		<?php echo $row["motivo_rechazo"]; ?>
	</div>
<?php } ?>

<?php if($error){ ?>
	<div class="alert alert-info">
		<?php echo $error; ?>
	</div>
<?php } ?>

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
					<div class="panel-footer">
						<a href="?cmp=actualizar&id=<?php echo $_GET["id"] ?>"><i class="fa fa-pencil-square fa-2x"></i> Modificar datos basicos</a>
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
										<th>Quitar</th>
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
										<td align=center><a href="?id=<?php echo $_GET["id"]; ?>&eli_ref=<?php echo $row_ref["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR  <?php echo $row_ref["nombre"]; ?> como referencia?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a></td>
										
									</tr>
								<?php
								}
								?>
								
								</tbody>
							</table>									
						</div>
						<!-- --->
					</div>
					<div class="panel-footer ">					
						<form action="?id=<?php echo $_GET["id"] ?>&rf=1"  method="post" name="form" id="form"  enctype="multipart/form-data">
							<table width="100%">
								<tr>
									<td>
										<div class="form-group input-group">
											<span class="input-group-addon">Nombre</span>
											<input type="text" name="nombre" class="form-control" placeholder="Ingresar Apellido y Nombre " required>
											<span class="input-group-addon">Tel</span>
											<input type="text" name="celular" class="form-control" placeholder="Ingresar telefono o Celular " required>						
										</div>			
									</td>
									<td valign=top>
										<input type="submit" value="Agregar" class="btn btn-primary">
									</td>
								</tr>
							</table>
						</form>
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
										<th>Quitar</th>
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
										<td align=center><a href="?id=<?php echo $_GET["id"]; ?>&eli_ref=<?php echo $row_ref["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR  <?php echo $row_ref["nombre"]; ?> como referencia?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a></td>
										
									</tr>
								<?php
								}
								?>
								
								</tbody>
							</table>									
						</div>
						<!-- --->
					</div>
					<div class="panel-footer ">					
						<form action="?id=<?php echo $_GET["id"] ?>&rf=2"  method="post" name="form" id="form"  enctype="multipart/form-data">
							<table width="100%">
								<tr>
									<td>
										<div class="form-group input-group">
											<span class="input-group-addon">Nombre</span>
											<input type="text" name="nombre" class="form-control" placeholder="Ingresar Apellido y Nombre " required>
											<span class="input-group-addon">Tel</span>
											<input type="text" name="celular" class="form-control" placeholder="Ingresar telefono o Celular " required>						
										</div>			
									</td>
									<td valign=top>
										<input type="submit" value="Agregar" class="btn btn-primary">
									</td>
								</tr>
							</table>
						</form>
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
										<th>Quitar</th>
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
										<td align=center><a href="?id=<?php echo $_GET["id"]; ?>&eli_est=<?php echo $row_ref["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR  <?php echo $row_ref["nivel"]; ?> como un estudio realizado?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a></td>
										
									</tr>
								<?php
								}
								?>
								
								</tbody>
							</table>									
						</div>			
					</div>
					<div class="panel-footer">
						<form action="?id=<?php echo $_GET["id"] ?>&est=1"  method="post" name="form" id="form"  enctype="multipart/form-data">
						<table width="100%">
								<tr>
									<td>
										<div class="form-group input-group">
											<span class="input-group-addon">Nivel</span>
											<select name="nivel" class="form-control" required >
												<option value="">Seleccione un nivel</option>
												<option>Primaria</option>
												<option>Secundaria</option>
												<option>Tecnica</option>
												<option>Tecnologica</option>
												<option>Universitaria</option>
												<option>Especializacion</option>
											</select>
											
											<span class="input-group-addon">Institucion</span>
											<input type="text" name="instituto" class="form-control" placeholder="Nombre de la institucion" required >

											<span class="input-group-addon">Direccion</span>
											<input type="text" name="direccion" class="form-control" placeholder="Lugar o direccion" required >	

											<span class="input-group-addon">Titulo</span>
											<input type="text" name="nombre" class="form-control" placeholder="Titulo obtenido" required >	

											<span class="input-group-addon">Fecha inicial</span>
											<input type="date" name="fecha_inicial" size="5" id="fecha_inicial" class="form-control" required >
											<script type="text/javascript">											   
												var opts = {                            
												formElements:{"fecha_inicial":"Y-ds-m-ds-d"},
												showWeeks:true,																
												statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
												};      
												datePickerController.createDatePicker(opts);
											</script>
											
											<span class="input-group-addon">Fecha final</span>
											<input type="date" name="fecha_final" id="fecha_final" size="5" class="form-control" required >
											<script type="text/javascript">											   
												var opts = {                            
												formElements:{"fecha_final":"Y-ds-m-ds-d"},
												showWeeks:true,																
												statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
												};      
												datePickerController.createDatePicker(opts);
											</script>
											
										</div>			
									</td>
									<td valign=top>
										<input type="submit" value="Agregar" class="btn btn-primary">
									</td>
								</tr>
							</table>
							</form>
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
										<th>Quitar</th>
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
										<td align=center><a href="?id=<?php echo $_GET["id"]; ?>&eli_lab=<?php echo $row_ref["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR  <?php echo $row_ref["empresa"]; ?> como experiencia laboral?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a></td>
										
									</tr>
								<?php
								}
								?>
								
								</tbody>
							</table>									
						</div>			
					</div>
					<div class="panel-footer">
						<form action="?id=<?php echo $_GET["id"] ?>&lab=1"  method="post" name="form" id="form"  enctype="multipart/form-data">
						<table width="100%">
								<tr>
									<td>
										<div class="form-group input-group">
											
											<span class="input-group-addon">Empresa</span>
											<input type="text" name="empresa" class="form-control" placeholder="Nombre de la empresa" required >
											
											<span class="input-group-addon">Cargo</span>
											<input type="text" name="cargo" class="form-control" placeholder="Nombre del cargo" required >

											<span class="input-group-addon">Jefe</span>
											<input type="text" name="jefe" class="form-control" placeholder="Jefe inmediato" required >	

											<span class="input-group-addon">Telefono jefe</span>
											<input type="text" name="jefe_telefono" class="form-control" placeholder="Telefono del jefe" required >	

											<span class="input-group-addon">Fecha inicial</span>
											<input type="date" name="fecha_inicial"  size="5" id="fecha_inicial2"  class="form-control" required >
											<script type="text/javascript">											   
												var opts = {                            
												formElements:{"fecha_inicial2":"Y-ds-m-ds-d"},
												showWeeks:true,																
												statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
												};      
												datePickerController.createDatePicker(opts);
											</script>
											
											<span class="input-group-addon">Fecha final</span>
											<input type="date" name="fecha_final" size="5"  id="fecha_final2" class="form-control" required >
											<script type="text/javascript">											   
												var opts = {                            
												formElements:{"fecha_final2":"Y-ds-m-ds-d"},
												showWeeks:true,																
												statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
												};      
												datePickerController.createDatePicker(opts);
											</script>
											
										</div>			
									</td>
									<td valign=top>
										<input type="submit" value="Agregar" class="btn btn-primary">
									</td>
								</tr>
							</table>
							</form>
					</div>
				</div>
			</div>
		</td>		
	</tr>
</table>

<center>
	<a href="?id=<?php echo $_GET["id"] ?>&conf=1" onclick="if(confirm('¿ Esta seguro de enviar la hoja de vida para la auditoria ?') == false){return false;}">
		<b><font color=red>Enviar la informacion para su confirmacion <i class="fa fa-external-link fa-2x"></i></font></b>
	</a>
</center>

	<br><br>

