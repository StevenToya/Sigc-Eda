<?php
if($_POST["enviar"])
{
	 $sql_ing = "INSERT INTO aud_solicitud 
		(id_base, id_creador, id_tramite, id_realizado, fecha_registro, observacion)
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


	$sql = "SELECT  tramite.ot , tramite.fecha_atencion_orden , tramite.tipo_paquete , tramite.tipo_producto , tipo_trabajo.nombre AS nom_tt, tipo_trabajo.codigo AS cod_tt,
	tramite.numero_servicio , tramite.tecnologia , tramite.departamento , tramite.region , tramite.zona , tramite.nombre_cliente, tramite.codigo_dano, tramite.solicitud, tipo_garantia,
	tramite.contrato , tramite.direccion ,tramite.tipo_cliente ,tramite.numero_orden ,tramite.valor_liquidado, tecnologia.nombre AS nom_tecnologia, tecnico.nombre AS nom_tecnico, 
	tecnico.codigo AS cod_tecnico, localidad.nombre AS nom_localidad, ot_antecesor, tramite.producto, tramite.	unidad_operativa,
	tramite.id AS tramite_principal, tramite.departamento AS tem_zona, tramite.id_tipo_trabajo AS tem_tt,  tipo_trabajo.tipo AS ttt, codigo_unidad_operativa,
	tramite.fecha_atencion_orden AS tem_fecha, tramite.direccion_codigo AS tem_dirid, contratista_valor, observacion_contratista, fecha_ot_antecesor AS tem_fecha2,
	tramite.fecha_reportada, tramite.descripcion_dano
		FROM tramite
		LEFT JOIN tipo_trabajo ON 
			tramite.id_tipo_trabajo = tipo_trabajo.id
		LEFT JOIN zona ON 
			tramite.departamento = zona.nombre
		LEFT JOIN localidad ON 
			tramite.id_localidad = localidad.id
		LEFT JOIN liquidar_tramite ON 
			tramite.id = liquidar_tramite.id_tramite
		LEFT JOIN tecnologia ON 
			tramite.id_tecnologia = tecnologia.id
		LEFT JOIN tecnico ON 
			tramite.id_tecnico = tecnico.id
		LEFT JOIN liquidacion_zona ON 
			zona.id = liquidacion_zona.id_zona AND
			tramite.id_tecnologia = liquidacion_zona.id_tecnologia	AND	
			tramite.id_tipo_trabajo = liquidacion_zona.id_tipo_trabajo 
		WHERE tramite.id = '".$_GET["id"]."' LIMIT 1 ; ";
		$res = mysql_query($sql);
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	
	
		
?>


	<h2>ASIGNAR AUDITORIA PARA ESTE TRAMITE</h2>
	<div align=right>
		<a href="?cmp=offline&criterio=<?php echo $_GET["criterio"]; ?>&id=<?php echo $_GET["id"]; ?>"> <i class="fa fa-reply fa-2x"></i> Volver al listado de tramites </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
	</div>
	
	<h5>
		<table width="95%" align=center>
			<tr>
				<td width="50%" valign=top>
					<div class="col-md-100 col-sm-100">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Datos Basicos
							</div>
							<div class="panel-body" align=center>
								<table width=100%   class="table-striped table-bordered">
									<tr >
										<td ><b>OT: </b></td><td><?php echo $row["ot"] ?></td>
									</tr>
									<tr>
										<td><b>Tipo trabajo: </b></td><td><?php echo $row["cod_tt"] ?> - <?php echo $row["nom_tt"] ?></td>
									</tr>
									
									<tr>
										<td><b>Solicitud: </b></td><td><?php echo $row["solicitud"] ?></td>
									</tr>
									<tr>
										<td><b>Codigo da&ntilde;o: </b></td><td><?php echo $row["codigo_dano"] ?></td>
									</tr>
									<tr>
										<td><b>Producto: </b></td><td><?php echo $row["producto"] ?></td>
									</tr>
									
									
									<tr>
										<td><b>Tipo reparacion: </b></td><td><?php echo $row["descripcion_dano"] ?></td>
									</tr>
								
									<tr>
										<td><b>Unidad operativa: </b></td><td><?php echo $row["codigo_unidad_operativa"] ?> - <?php echo $row["unidad_operativa"] ?></td>
									</tr>
									<tr>
										<td><b>Tecnico: </b></td><td><?php echo $row["cod_tecnico"] ?> - <?php echo $row["nom_tecnico"] ?></td>
									</tr>
									<tr>
										<td><b>Tecnologia: </b></td><td><?php echo $row["nom_tecnologia"] ?></td>
									</tr>
									
									
									<tr>
										<td><b>Fecha de registro: </b></td><td><?php echo $row["fecha_reportada"] ?></td>
									</tr>
									
									<tr>
										<td><b>Fecha de atencion: </b></td><td><?php echo $row["fecha_atencion_orden"] ?></td>
									</tr>
										
									<tr>
										<td><b>Tipo Paquete: </b></td><td><?php echo $row["tipo_paquete"] ?></td>
									</tr>
									
									<tr>
										<td><b>Desc. producto: </b></td><td><?php echo $row["tipo_producto"] ?>  </td>
									</tr>
									
									<tr>
										<td><b>Departamento: </b></td><td><?php echo $row["departamento"] ?></td>
									</tr>
									
									<tr>
										<td><b>Zona: </b></td><td><?php echo $row["zona"] ?></td>
									</tr>
								
									<tr>
										<td><b>Localidad: </b></td><td><?php echo $row["nom_localidad"] ?></td>
									</tr>
									
									<tr>
										<td><b>tipo Cliente: </b></td><td><?php echo $row["tipo_cliente"] ?></td>
									</tr>
									
									<tr>
										<td><b>Nombre Cliente: </b></td><td><?php echo $row["nombre_cliente"] ?></td>
									</tr>
									<tr>
										<td><b>Direccion: </b></td><td><?php echo $row["direccion"] ?></td>
									</tr>
									
									
								</table>
								

								
							</div>					
						</div>
					</div>
					
				</td>
				
				<td width="1%"> </td>
				<td width="49%"  valign=top>
					<div class="col-md-100 col-sm-100">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Materiales usados
							</div>
							<div class="panel-body" align=center>
								<table width="99%" class="table-striped table-bordered ">
								
									<tr bgcolor=#BDBDBD>
										<th><center>Codigo</center></th>
								
										<th>Material</th>
									
										<th><center>Cantidad</center></th>																			
									</tr>
									
									<?php
									$i =0;
									 $sql_traza = "SELECT material_traza.cantidad, material_traza.tipo, equipo_material.nombre, equipo_material.codigo_1
									FROM material_traza 
									INNER JOIN equipo_material ON material_traza.id_equipo_material = equipo_material.id									
									WHERE material_traza.id_tramite = '".$_GET["id"]."' ORDER BY equipo_material.nombre ";
									$res_traza = mysql_query($sql_traza);
									while($row_traza = mysql_fetch_array($res_traza))
									{										
										if($i%2 == 0){$color= "";}else{$color='#E6E6E6';}
																				
									?>
										<tr bgcolor=<?php echo $color; ?>>
											<td valign=top><?php echo $row_traza["codigo_1"] ?></td>
											
											<td valign=top><?php echo $row_traza["nombre"] ?></td>
											
											<td valign=top><?php echo $row_traza["cantidad"]; ?></td>											
										</tr>
										
									<?php
										$i++;
									}
									?>
								</table>
								
							</div>					
						</div>
					</div>
					
					
					<div class="col-md-100 col-sm-100">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Equipos 
							</div>
							<div class="panel-body" align=center>
								<table width="99%"  class="table-striped table-bordered ">
									
								
									<tr  bgcolor=#BDBDBD>
										<th>Equipo</th>										
										<th>Serial</th>
										<th>Estado</th>																			
									</tr>
									
									<?php
									$i =0;
									$sql_traza = "SELECT  serial_traza.estado, equipo_material.nombre, equipo_serial.serial
									FROM serial_traza 
									INNER JOIN equipo_serial ON serial_traza.id_equipo_serial = equipo_serial.id
									INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id									
									WHERE serial_traza.id_tramite = '".$_GET["id"]."' ORDER BY equipo_material.nombre ";
									$res_traza = mysql_query($sql_traza);
									while($row_traza = mysql_fetch_array($res_traza))
									{
										if($row_traza["estado"]==1){$estado= "<font color=red><b>Libre</b></font>";}
										if($row_traza["estado"]==2){$estado= "<font color=green><b>Instalado</b></font>";}
										if($row_traza["estado"]==3){$estado= "<font color=red><b>Malo</b></font>";}
										if($row_traza["estado"]==4){$estado= "<font color=red><b>Perdido</b></font>";}										
										if($row_traza["estado"]==5){$estado= "<font>Instalado antes</font>";}
										if($row_traza["estado"]==6){$estado= "<font>Retirado</font>";}
									?>
										<tr>
											<td valign=top><?php echo $row_traza["nombre"] ?></td>
											<td valign=top><?php echo $row_traza["serial"] ?></td>
											<td valign=top align=center><?php echo $estado; ?></td>
									
										</tr>
										
									<?php
										$i++;
									}
									?>
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
								<form action="?criterio=<?php echo $_GET["criterio"]; ?>&id=<?php echo $_GET["id"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">
								
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
