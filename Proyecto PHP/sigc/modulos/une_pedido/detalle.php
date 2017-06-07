<?php
	 
	if($_POST["pedido"])
	{
		$sql = "SELECT * FROM une_pedido WHERE une_pedido.numero = '".$_POST["pedido"]."' AND une_pedido.tipo='1' LIMIT 1	";
	}else{
		
		$sql = "SELECT * FROM une_pedido WHERE une_pedido.id = '".$_GET["id"]."' AND une_pedido.tipo='1' LIMIT 1	";
	}
	
	
	if($_GET["conf"]==1)
	{
		$sql_upd = "UPDATE `une_pedido` SET `estado_material` = '4', `observacion_edatel` = '".$_POST["observacion_edatel"]."' WHERE 
		`une_pedido`.`id` = '".$_GET["id"]."' AND une_pedido.tipo='1' LIMIT 1; ";
		mysql_query($sql_upd);	
	}
		
	
	if($_GET["eliminar"])
	{
		$sql_eli = "DELETE FROM `une_pedido_material` WHERE `id` = '".$_GET["eliminar"]."'  LIMIT 1;";
		mysql_query($sql_eli);		
	}
	
	if($_GET["eliminare"])
	{
		$sql_eli = "DELETE FROM `une_pedido_equipo` WHERE `id` = '".$_GET["eliminare"]."' LIMIT 1;";
		mysql_query($sql_eli);		
	}
	
	if($_POST["ingresar"])
	{
		$sql_bus_material = "SELECT id FROM une_pedido_material 
		WHERE id_material = '".$_POST["id_material"]."' AND id_pedido = '".$_GET["id"]."' LIMIT 1";
		$res_bus_material = mysql_query($sql_bus_material);
		$row_bus_material = mysql_fetch_array($res_bus_material);
		if(!$row_bus_material["id"])
		{
				 $sql_agr = "INSERT INTO `une_pedido_material` (`id_pedido`, `id_material`, `cantidad`) 
					VALUES ( '".$_GET["id"]."', '".$_POST["id_material"]."', '".$_POST["cantidad"]."');";
				mysql_query($sql_agr);	
		}
		else{
			echo '<script> alert("Este material ya esta ingresado");</script>';
		}
	}
	
	if($_POST["ingresare"])
	{
		$sql_bus_equipo = "SELECT id FROM une_pedido_equipo 
		WHERE id_equipo = '1' AND id_pedido = '".$_GET["id"]."' AND serial = '".$_POST["serial"]."'  LIMIT 1";
		$res_bus_equipo = mysql_query($sql_bus_equipo);
		$row_bus_equipo = mysql_fetch_array($res_bus_equipo);
		if(!$row_bus_equipo["id"])
		{
				 $sql_agr = "INSERT INTO `une_pedido_equipo` (`id_pedido`, `id_equipo`, `serial`, `mac`) 
					VALUES ( '".$_GET["id"]."', '1', '".$_POST["serial"]."', '".$_POST["mac"]."');";
				mysql_query($sql_agr);	
		}
		else{
			echo '<script> alert("Este serial ya esta ingresado");</script>';
		}
	}
	
	
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	
	$estado_material = "";
	if($row["estado_material"]==1){ $estado_material = "<font color=red>Pend. EDATEL</font>";}
	if($row["estado_material"]==4){ $estado_material = "<font color=#000000>Pend. EIA</font>";}
	if($row["estado_material"]==2){ $estado_material = "<font color=green>Confirmado</font>";}
	if($row["estado_material"]==3){ $estado_material = "<font color=red>Rechazado</font>";}

		
?>


<h2>DETALLES DEL PEDIDO <b><?php echo $row["numero"] ?></b> EN <b>HFC</b></h2>

<div align=right>
	<a href="?cmp=lista_pendiente"> <i class="fa fa-reply fa-2x"></i> Volver al listado pedidos </a>
</div>
	
<?php if(!$row["id"]){ ?>
	<br><br>
	<center>
		<div class="alert alert-info" style="width:50%">
			<center><h3> <i class="fa fa-exclamation-triangle fa-2x"></i> <br><br>Pedido <b><?php echo $_POST["pedido"]; ?></b> no fue encontrado </h3></center>
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
										<td><b>Numero: </b></td><td><?php echo $row["numero"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Estado: </b></td><td><b><?php echo $estado_material ?></b></td>
									</tr>
									
									<?php if($row["estado_material"]==3){ ?>
										<tr><td colspan=2><br></td></tr>
										<tr>
											<td colspan=2>
												<font color=red>
												<b>Motivo  del rechazo: </b><br>
												<?php echo $row["observacion_material"] ?>
												</font>
											</td>
										</tr>									
									<?php } ?>
									
									<?php if($row["observacion_edatel"]){ ?>
										<tr><td colspan=2><br></td></tr>
										<tr>
											<td colspan=2>
												<font color=red>
												<b>Observacion Edatel: </b><br>
												<?php echo $row["observacion_edatel"] ?>
												</font>
											</td>
										</tr>									
									<?php } ?>
									
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Ciudad: </b></td><td><?php echo $row["ciudad"] ?></td>
									</tr>
									
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Cliente: </b></td><td><?php echo $row["cliente_nombre"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Direccion: </b></td><td><?php echo $row["cliente_direccion"] ?></td>
									</tr>
									
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Tipo trabajo: </b></td><td><?php echo $row["tipo_trabajo"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Funcionario: </b></td><td><?php echo $row["nombre_funcionario"] ?> (<?php echo $row["codigo_funcionario"] ?>) </td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Segmento: </b></td><td><?php echo $row["segmento"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Producto: </b></td><td><?php echo $row["producto"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Producto homologado: </b></td><td><?php echo $row["producto_homologado"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Tecnologia: </b></td><td><?php echo $row["tecnologia"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Proceso: </b></td><td><?php echo $row["proceso"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Empresa: </b></td><td><?php echo $row["empresa"] ?></td>
									</tr>
									<tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Fecha: </b></td><td><?php echo $row["fecha"] ?></td>
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
								Materiales usados
							</div>
							<div class="panel-body" align=center>
								<table width="99%">
									<?php if($row["estado_material"]==3 || $row["estado_material"]==1){ ?>
									<tr>
										<td colspan=7>
											<form action="?id=<?php echo $_GET["id"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">
												<center>
													<select name="id_material" required>
														<option value="">Seleccione un material</option>
														<?php
														$sql_mat = "SELECT id, nombre FROM une_material WHERE tipo=1 ORDER BY nombre";
														$res_mat = mysql_query($sql_mat);
														while($row_mat = mysql_fetch_array($res_mat))
														{
														?>
															<option value="<?php echo $row_mat["id"] ?>"><?php echo $row_mat["nombre"] ?></option>						
														<?php
														}
														?>
													</select>										
													<input type="number" name="cantidad" required>
													<input type="submit" class="btn btn-primary" name="ingresar" value="Ingresar"><br><br>
												</center>
											</form>
										</td>
									</tr>
									<?php	}	?>
								
									<tr bgcolor=#BDBDBD>
										<th> </th>
										<th width=2%> </th>
										<th><center>Codigo</center></th>
										<th width=2%> </th>
										<th>Material</th>
										<th width=2%> </th>
										<th><center>Cantidad</center></th>
										<?php if($row["estado_material"]==3 || $row["estado_material"]==1){ ?>
											<th><center>Quitar</center></th>
										<?php } ?>										
									</tr>
									
									<?php
									$i =0;
									 $sql_traza = "SELECT une_material.nombre, une_material.codigo, une_pedido_material.cantidad, une_pedido_material.id,
									 une_pedido_material.alarma
									FROM une_pedido_material 
									INNER JOIN une_material ON une_pedido_material.id_material = une_material.id									
									WHERE une_pedido_material.id_pedido = '".$row["id"]."' ORDER BY une_material.nombre ";
									$res_traza = mysql_query($sql_traza);
									while($row_traza = mysql_fetch_array($res_traza))
									{										
										if($i%2 == 0){$color= "";}else{$color='#E6E6E6';}
										$alarma = "";
										if($row_traza["alarma"]==2){$alarma = "<font color=yellow><i class='fa fa-exclamation-triangle fa'> </i></font>";}
										if($row_traza["alarma"]==3){$alarma = "<font color=red><i class='fa fa-exclamation-triangle fa'> </i></font>";}
										
										
									?>
										<tr bgcolor=<?php echo $color; ?>>
											<td valign=top><center><?php echo $alarma; ?></center></td>
											<td valign=top width=2%> </td>
											<td valign=top><?php echo $row_traza["codigo"] ?></td>
											<td valign=top width=2%> </td>
											<td valign=top><?php echo $row_traza["nombre"]; ?></td>
											<td valign=top width=2%> </td>
											<td valign=top><b><center><?php echo $row_traza["cantidad"]; ?></center></b></td>
											<?php if($row["estado_material"]==3 || $row["estado_material"]==1){ ?>
												<td><center><a href="?id=<?php echo $row["id"] ?>&eliminar=<?php echo $row_traza["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR el material <?php echo $row_traza["nombre"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a></center></td>
											<?php } ?>	
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
								Equipos usados
							</div>
							<div class="panel-body" align=center>
								<table width="99%">
									<?php if($row["estado_material"]==3 || $row["estado_material"]==1){ ?>
									<tr>
										<td colspan=7>
											<form action="?id=<?php echo $_GET["id"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">
												<center>
													<input type="text" name="serial" placeholder="Ingresar serial" required>
													<input type="text" name="mac" placeholder="Ingresar mac" >
													<input type="submit" class="btn btn-primary" name="ingresare" value="Ingresar"><br><br>
												</center>
											</form>
										</td>
									</tr>
									<?php	}	?>
								
									<tr  bgcolor=#BDBDBD>
										<th>Serial</th>
										<th width=2%> </th>
										<th>Mac</th>
										<th width=2%> </th>
										<th>Equipo</th>
										<th width=2%> </th>
										<?php if($row["estado_material"]==3 || $row["estado_material"]==1){ ?>
											<th><center>Quitar</center></th>
										<?php } ?>										
									</tr>
									
									<?php
									$i =0;
									 $sql_traza = "SELECT une_equipo.nombre,  une_pedido_equipo.serial, une_pedido_equipo.mac, une_pedido_equipo.id
									FROM une_pedido_equipo 
									INNER JOIN une_equipo ON une_pedido_equipo.id_equipo = une_equipo.id									
									WHERE une_pedido_equipo.id_pedido = '".$row["id"]."' ORDER BY une_equipo.nombre ";
									$res_traza = mysql_query($sql_traza);
									while($row_traza = mysql_fetch_array($res_traza))
									{
										$suceso = '';
										if($i%2 == 0){$color= "";}else{$color='#E6E6E6';}
										
									?>
										<tr  bgcolor=<?php echo $color; ?>>
											<td valign=top><?php echo $row_traza["serial"] ?></td>
											<td valign=top width=2%> </td>
											<td valign=top><?php echo $row_traza["mac"] ?></td>
											<td valign=top width=2%> </td>
											<td valign=top><?php echo $row_traza["nombre"]; ?></td>
											<td valign=top width=2%> </td>
											<?php if($row["estado_material"]==3 || $row["estado_material"]==1){ ?>
												<td><center><a href="?id=<?php echo $row["id"] ?>&eliminare=<?php echo $row_traza["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR el serial <?php echo $row_traza["serial"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a></center></td>
											<?php } ?>	
										</tr>
										
									<?php
										$i++;
									}
									?>
								</table>
								
							</div>					
						</div>
					</div>
					
					
					
					<?php if($row["estado_material"]==3 || $row["estado_material"]==1){ ?>
						<center>						
							<form action="?conf=1&id=<?php echo $row["id"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">
							
									<div class=" form-group input-group input-group-lg" style="width:100%">
									   <span class="input-group-addon"><div align=left>Observacion</div></span>
										 <textarea  class="form-control" name="observacion_edatel" id="observacion_edatel" required></textarea>										
									</div> 
									
									<input type="submit" name="enviar" class="btn btn-danger" value="Enviar confirmacion" onclick="if(confirm('¿ Desea ENVIAR el pedido <?php echo $row["numero"]; ?> para su confirmacion ?') == false){return false;}">

								
							</form>
						</center>
					<?php } ?>
				</td>
		</table>
<?php } ?>