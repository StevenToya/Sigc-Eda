<?php
	$_POST["serial"] = strtoupper(limpiar($_POST["serial"]));	
	 	$sql = "SELECT equipo_serial.id, equipo_material.nombre , equipo_material.codigo_1 , localidad.nombre AS nom_localidad, bodega.nombre AS nom_bodega,
		equipo_serial.estado, pedido_equipo_material.numero, pedido_equipo_material.fecha, tramite.ot,  tramite.solicitud, tecnico.nombre AS nom_tecnico
			FROM equipo_serial
			INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id
			LEFT JOIN tramite ON equipo_serial.id_tramite = tramite.id
			LEFT JOIN localidad ON equipo_serial.id_localidad = localidad.id
			LEFT JOIN localidad AS bodega ON equipo_serial.id_localidad_carga = bodega.id
			LEFT JOIN pedido_equipo_material  ON equipo_serial.id_pedido = pedido_equipo_material.id
			LEFT JOIN tecnico ON tramite.id_tecnico = tecnico.id
			WHERE equipo_serial.serial = '".$_POST["serial"]."' LIMIT 1	";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	
	
	$estado = '';
	if($row["estado"]==1){$estado = '<font color=red>Libre</font>';}
	if($row["estado"]==2 || $row["estado"]==5){$estado = '<font color=green>En uso</font>';}
	if($row["estado"]==3){$estado = 'Malo';}
	if($row["estado"]==4){$estado = '<font color=red>Perdido</font>';}
	
	if($row["estado"]==6){$estado = '<font color=red>Retirado</font>';}

		
?>


<h2>RESULTADO DE LA BUSQUEDA DEL SERIAL <b><?php echo $_POST["serial"] ?></b></h2>

<div align=right>
	<a href="?cmp=panel_equipo"> <i class="fa fa-reply fa-2x"></i> Volver al listado de equipos sin cerrar </a>
</div>
	
<?php if(!$row["id"]){ ?>
	<br><br>
	<center>
		<div class="alert alert-info" style="width:50%">
			<center><h3> <i class="fa fa-exclamation-triangle fa-2x"></i> <br><br>Serial <b><?php echo $_POST["serial"]; ?></b> no fue encontrado </h3></center>
		</div>
	</center>
<?php }else{ ?>
		<h5>
		<table width="95%" align=center>
			<tr>
				<td width="40%" valign=top>
					<div class="col-md-100 col-sm-100">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Datos Basicos
							</div>
							<div class="panel-body" align=center>
								<table width=98%>
									<tr>
										<td><b>Equipo: </b></td><td><?php echo $row["nombre"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Codigo: </b></td><td><?php echo $row["codigo_1"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Bodega origen: </b></td><td><?php echo $row["nom_bodega"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Estado: </b></td><td><b><?php echo $estado ?></b></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Pedido: </b></td><td><?php echo $row["numero"] ?> (<?php echo $row["fecha"] ?>)</td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Localidad: </b></td><td><?php echo $row["nom_localidad"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>OT: </b></td><td><?php echo $row["ot"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Solicitud: </b></td><td><?php echo $row["solicitud"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Tecnico: </b></td><td><?php echo $row["nom_tecnico"] ?></td>
									</tr>
									
									
									
								</table>
								

								
							</div>					
						</div>
					</div>
				</td>
				<td width="4%"> </td>
				<td width="56%"  valign=top>
					<div class="col-md-100 col-sm-100">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Historial
							</div>
							<div class="panel-body" align=center>
								<table width="98%">
									<tr>
										<th>Fecha</th>
										<th width=2%> </th>
										<th>Suceso</th>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<?php
									 $sql_traza = "SELECT serial_traza.fecha, serial_traza.estado, tramite.ot, usuario.nombre, usuario.apellido,
									tecnico.nombre AS nom_tecnico, pedido_equipo_material.numero
									FROM serial_traza 
									INNER JOIN equipo_serial ON serial_traza.id_equipo_serial = equipo_serial.id
									LEFT JOIN tramite ON serial_traza.id_tramite = tramite.id
									LEFT JOIN tecnico ON tramite.id_tecnico = tecnico.id
									LEFT JOIN usuario ON equipo_serial.id_usuario = usuario.id
									LEFT JOIN pedido_equipo_material ON equipo_serial.id_pedido = pedido_equipo_material.id
									WHERE id_equipo_serial = '".$row["id"]."' ORDER BY serial_traza.fecha ";
									$res_traza = mysql_query($sql_traza);
									while($row_traza = mysql_fetch_array($res_traza))
									{
										$suceso = '';
										
										if($row_traza["estado"]==1 && !$row_traza["ot"])
										{$suceso = "Se ingreso este serial por primera vez al sistema en el pedido <b>".$row_traza["numero"]."</b>";}
																				
										if($row_traza["estado"]==1 && $row_traza["ot"])
										{$suceso = "Se <b>retiro</b> el equipo en la orden <b>".$row_traza["ot"]."</b> por el tecnico <b>".$row_traza["nom_tecnico"]."</b>";}
										
										if($row_traza["estado"]==2 && $row_traza["ot"])
										{$suceso = "Se <b>instalo</b> el equipo en la orden <b>".$row_traza["ot"]."</b> por el tecnico <b>".$row_traza["nom_tecnico"]."</b>";}
										
										if($row_traza["estado"]==3 && $row_traza["ot"])
										{$suceso = "Se <b>retiro</b> el equipo como <b>malo</b> en la orden <b>".$row_traza["ot"]."</b> por el tecnico <b>".$row_traza["nom_tecnico"]."</b>";}
										
										if($row_traza["estado"]==4 && $row_traza["ot"])
										{$suceso = "El equipo <b>no fue encontrado</b> en el cliente en la orden <b>".$row_traza["ot"]."</b> Por el tecnico <b>".$row_traza["nom_tecnico"]."</b>";}
										
										if($row_traza["estado"]==5 && $row_traza["ot"])
										{$suceso = "El tecnico  <b>".$row_traza["nom_tecnico"]."</b> encontro el equipo <b>anteriormente ya instalado </b> cuado gestionaba  la orden <b>".$row_traza["ot"]."</b> ";}
									
										if($row_traza["estado"]==6)
										{$suceso = "Se <b>retiro</b> el equipo en la orden <b>".$row_traza["ot"]."</b> por el tecnico <b>".$row_traza["nom_tecnico"]."</b>";}
									
									?>
										<tr>
											<td valign=top><center><?php echo $row_traza["fecha"] ?></center></td>
											<td valign=top width=2%> </td>
											<td valign=top><?php echo $suceso; ?></td>
										</tr>
										<tr><td colspan=3><br></td></tr>
									<?php
									}
									?>
								</table>
							</div>					
						</div>
					</div>
				</td>
		</table>
<?php } ?>