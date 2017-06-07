<?php
	 
	if($_POST["pedido"])
	{
		$sql = "SELECT * FROM une_pedido WHERE une_pedido.numero = '".$_POST["pedido"]."' AND une_pedido.tipo='1' AND une_pedido.estado_liquidacion IS NOT NULL  LIMIT 1	";
	}else{
		
		$sql = "SELECT * FROM une_pedido WHERE une_pedido.id = '".$_GET["id"]."' AND une_pedido.estado_liquidacion IS NOT NULL  LIMIT 1 	";
	}
	
	
	if($_POST["enviar"])
	{
		$sql_upd = "UPDATE `une_pedido` SET `estado_liquidacion` = '".$_POST["estado"]."', `observacion_material_liquidacion` = '".$_POST["observacion_material"]."',
		`fecha_liquidacion_eia` = '".date("Y-m-d G:i:s")."', `usuario_liquidacion_eia` = '".$_SESSION["user_id"]."'
		WHERE `id` = '".$_GET["id"]."' LIMIT 1; ";
		mysql_query($sql_upd);	
		if(!mysql_error())
		{
			if($_POST["estado"]==2){$info = "Aceptado";}
			if($_POST["estado"]==3){$info = "Rechazado";}
			
			echo '<script> alert(" Pedido '.$info.' correctamente ");</script>';
			echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=gestionar_liquidacion'>";
			die();
		}
		else
		{
			echo '<script> alert(" ERROR al ingresar a la base de datos ");</script>';
		}
	
		
	}
		
	
	
	
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	
	$estado_liquidacion = "";
	if($row["estado_liquidacion"]==1){ $estado_liquidacion = "<font color=#000000>Pendiente por  EDATEL</font>";}
	if($row["estado_liquidacion"]==2){ $estado_liquidacion = "<font color=green>Confirmado</font>";}
	if($row["estado_liquidacion"]==3){ $estado_liquidacion = "<font color=red>Rechazado</font>";}
	if($row["estado_liquidacion"]==4){ $estado_liquidacion = "<font color=red>Pendiente por  EIA</font>";}

		
?>
<script>
	function mostrar(valor) 
	{		
		if(valor == '2')
		{	document.form.observacion_material.disabled=true;}
		
		if(valor == '3')
		{	document.form.observacion_material.disabled=false;}		
		
	}
</script>
 

<h2>DETALLES DEL PEDIDO <b><?php echo $row["numero"] ?></b> EN <b>HFC</b></h2>

<div align=right>
	<a href="?cmp=gestionar_liquidacion"> <i class="fa fa-reply fa-2x"></i> Volver al listado pedidos </a>
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
				<td width="56%" valign=top>
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
										<td><b>Estado: </b></td><td><b><?php echo $estado_liquidacion ?></b></td>
									</tr>
									
									<?php if($row["estado_liquidacion"]==3){ ?>
										<tr><td colspan=2><br></td></tr>
										<tr>
											<td colspan=2>
												<font color=red>
												<b>Motivo  del rechazo: </b><br>
												<?php echo $row["observacion_material_liquidacion"] ?>
											</td>
										</tr>									
									<?php } ?>
									
									<?php if($row["observacion_edatel_liquidacion"]){ ?>
										<tr><td colspan=2><br></td></tr>
										<tr>
											<td colspan=2>
												<font color=red>
												<b>Observacion Edatel: </b><br>
												<?php echo $row["observacion_edatel_liquidacion"] ?>
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
									
									
									<?php if($row["descripcion_falla"]){ ?>
										<tr><td colspan=2><br></td></tr>
										<tr>
											<td><b>Desc. Falla: </b></td><td><?php echo $row["descripcion_falla"] ?></td>
										</tr>
									<?php } ?>
									
									<?php if($row["inconsistencia"]){ ?>
										<tr><td colspan=2><br></td></tr>
										<tr>
											<td><b>Inconsistencia: </b></td><td><?php echo $row["inconsistencia"] ?></td>
										</tr>
									<?php } ?>
									
									
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
				<td width="40%"  valign=top>
					<div class="col-md-100 col-sm-100">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Actividades realizadas
							</div>
							<div class="panel-body" align=center>
								<table width="99%">
															
									<tr bgcolor=#BDBDBD>
										<th> </th>
										<th width=2%> </th>
										<th><center>Codigo</center></th>
										<th width=2%> </th>
										<th><center>Valor</center></th>
																			
									</tr>
									
									<?php
									$i =0;
									$sql_item = "SELECT une_liquidacion.id, une_item.codigo, une_item.valor, une_liquidacion.id_item, une_liquidacion.codigo AS cc
										FROM une_liquidacion 
										LEFT JOIN une_item ON une_liquidacion.id_item = une_item.id
										WHERE (une_item.tipo=1 || une_liquidacion.id_item IS NULL) AND une_liquidacion.id_pedido = '".$row["id"]."'  ";
										$res_item = mysql_query($sql_item);
									while($row_item = mysql_fetch_array($res_item))
									{										
										if($i%2 == 0){$color= "";}else{$color='#E6E6E6';}
										$alarma = "";
										if(!$row_item["id_item"]){$alarma = "<font color=red><i class='fa fa-exclamation-triangle fa'> </i></font>";}									
										if(!$row_item["valor"]){$row_item["valor"] = 0;};
									?>
										<tr bgcolor=<?php echo $color; ?>>
											<td valign=top ><center><?php echo $alarma; ?></center></td>
											<td valign=top width=2%> </td>
											<td valign=top ><?php echo $row_item["codigo"] ?> <font color=red><?php echo $row_item["cc"] ?></font> </td>
											<td valign=top width=2%> </td>
											<td valign=top align=right><?php echo moneda($row_item["valor"]); ?></td>
										
										</tr>
										
									<?php
										$i++;
									}
									?>
								</table>
								
							</div>					
						</div>
					</div>
				
					
					
					<?php if($row["estado_liquidacion"]==4){ ?>
						<center>
														
							<form action="?id=<?php echo $row["id"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">
												
								<div class=" form-group input-group input-group-lg" style="width:100%">
								   <span class="input-group-addon"><div align=left>Confirmar</div></span>
									 <select onchange="mostrar(this.value)"  class="form-control" name="estado" id="estado" required>
										<option value="">Seleccione una opcion</option>
										<option value="2">Aceptar</option>	
										<option value="3">Rechazar</option>
									 </select>
								</div> 
								
								<div class=" form-group input-group input-group-lg" style="width:100%">
								   <span class="input-group-addon"><div align=left>Observacion</div></span>
									 <textarea  class="form-control" name="observacion_material" id="observacion_material" required></textarea>										
								</div> 
												
												
											<input type="submit" name="enviar" class="btn btn-primary" value="Enviar confirmacion">	
												
												
							</form>
							
						</center>
					<?php } ?>
					
					
				</td>
		</table>
<?php } ?>