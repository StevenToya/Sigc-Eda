<?php
if($_POST["enviar"])
{
	 $sql_ing = "INSERT INTO aud_solicitud 
		(id_base, id_creador, id_sra_tramite, id_realizado, fecha_registro, observacion)
		VALUES
		('".$_POST["id_base"]."', '".$_SESSION["user_id"]."', '".$_GET["id"]."', '".$_POST["id_realizado"]."', '".date("Y-m-d G:i:s")."', '".limpiar($_POST["observacion"])."')";
	mysql_query($sql_ing);
	
	if(mysql_insert_id())
	{
		 echo "<script>alert('La auditoria fue asignada correctamente')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_gestion'>";
		 die();
	}
	
}


	$sql = " SELECT *	FROM sra_tramite WHERE id = '".$_GET["id"]."' LIMIT 1 ; ";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	
		
?>


	<h2>ASIGNAR AUDITORIA PARA ESTE TRAMITE</h2>
	<div align=right>
		<a href="?cmp=online"> <i class="fa fa-reply fa-2x"></i> Volver al listado de tramites </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
	</div>
	
	<h5>
		<table width="70%" align=center>
			<tr>
				<td width="100%" valign=top>
					<div class="col-md-100 col-sm-100">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Datos Basicos
							</div>
							<div class="panel-body" align=center>
								<table width=100%   class="table-striped table-bordered">
									<tr>
										<td><b>Pedido: </b></td><td><?php echo $row["pedido"] ?></td>
									</tr>
									<tr>
										<td><b>Clase de servicio: </b></td><td><?php echo $row["clase_servicio"] ?></td>
									</tr>
									<tr>
										<td><b>Tipo de trabajo: </b></td><td><?php echo $row["tipo_trabajo"] ?></td>
									</tr>
									<tr>
										<td><b>Cliente: </b></td><td><?php echo $row["cliente"] ?></td>
									</tr>
									<tr>
										<td><b>Zona: </b></td><td><?php echo $row["zona"] ?></td>
									</tr>
									<tr>
										<td><b>Localidad: </b></td><td><?php echo $row["localidad"] ?></td>
									</tr>
									<tr>
										<td><b>Barrio: </b></td><td><?php echo $row["barrio"] ?></td>
									</tr>
									<tr>
										<td><b>Direccion: </b></td><td><?php echo $row["direccion"] ?></td>
									</tr>
									<tr>
										<td><b>Fecha de la cita: </b></td><td><?php echo $row["fecha_cita"] ?> <?php echo $row["franja_horaria"] ?> </td>
									</tr>								
								</table>
								

								
							</div>					
						</div>
					</div>
					
				</td>
		
		</table>
		<center>
		<div style="width:70%;align" class="col-md-100 col-sm-100">
				<div class="panel panel-danger">
					<div class="panel-heading" align=left>
						Datos para asignar auditoria
					</div>
						<div class="panel-body" align=center>
								<form action="?id=<?php echo $_GET["id"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">
								
										<div class=" form-group input-group input-group-lg" style="width:100%">
										   <span class="input-group-addon"><div align=left>Auditor</div></span>
											 <select  class="form-control" name="id_realizado" id="id_realizado" required>
												<option value="">Seleccione al auditor</option> 
												 <?php
												 $sql_usu = "SELECT nombre, apellido, id FROM usuario WHERE estado=1 ORDER BY apellido, nombre";
												 $res_usu = mysql_query($sql_usu);
												 while($row_usu = mysql_fetch_array($res_usu)){
												 ?>
													<option value="<?php echo $row_usu["id"] ?>"><?php echo $row_usu["apellido"] ?> <?php echo $row_usu["nombre"] ?></option>
												 <?php
												 }
												 ?>										 
											 </select>										
										</div>
										
										<div class=" form-group input-group input-group-lg" style="width:100%">
										   <span class="input-group-addon"><div align=left>Plantila a llenar</div></span>
											 <select  class="form-control" name="id_base" id="id_base" required>
												<option value="">Seleccione una plantilla</option> 
												 <?php
												 $sql_base = "SELECT nombre, id FROM aud_base WHERE estado=1 ORDER BY  nombre";
												 $res_base = mysql_query($sql_base);
												 while($row_base = mysql_fetch_array($res_base)){
												 ?>
													<option value="<?php echo $row_base["id"] ?>"><?php echo $row_base["nombre"] ?></option>
												 <?php
												 }
												 ?>										 
											 </select>										
										</div>

										<div class=" form-group input-group input-group-lg" style="width:100%">
										   <span class="input-group-addon"><div align=left>Observacion</div></span>
											 <textarea  class="form-control" name="observacion" id="observacion" required></textarea>										
										</div>
										
										
										<input type="submit" name="enviar" class="btn btn-danger" value="Asignar auditoria">
								</form>
						</div>
				</div>
			</div>
		</center>
