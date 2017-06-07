<?php
if($_POST["enviar"])
{
	 $sql_ing = "INSERT INTO aud_solicitud 
		(id_base, id_creador, id_une_pedido, id_realizado, fecha_registro, observacion)
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


	$sql = "SELECT * FROM une_pedido	WHERE une_pedido.id = '".$_GET["id"]."' LIMIT 1 ; ";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);		
?>


	<h2>ASIGNAR AUDITORIA PARA ESTE TRAMITE HFC</h2>
	<div align=right>
		<a href="?cmp=offline&criterio=<?php echo $_GET["criterio"]; ?>&id=<?php echo $_GET["id"]; ?>"> <i class="fa fa-reply fa-2x"></i> Volver al listado de tramites </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
	</div>
	
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
										<td><b>Telefono: </b></td><td><?php echo $row["telefono_contacto"] ?></td>
									</tr><tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Celular: </b></td><td><?php echo $row["celular_contacto"] ?></td>
									</tr><tr><td colspan=2><br></td></tr>
									<tr>
										<td><b>Microzona: </b></td><td><?php echo $row["microzona"] ?></td>
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
									<tr bgcolor=#BDBDBD>
									<!-- <th> </th> -->
									<!-- <th width=2%> </th>-->
										<th>Codigo</th>
										<th width=2%> </th>
										<th>Material</th>
										<th width=2%> </th>
										<th><center>Cantidad</center></th>																	
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
										<tr  bgcolor=<?php echo $color; ?>>
											<!-- <td valign=top><center><?php echo $alarma; ?></center></td> -->
											<!-- <td valign=top width=2%> </td> -->
											<td valign=top><?php echo $row_traza["codigo"] ?></td>
											<td valign=top width=2%> </td>
											<td valign=top><?php echo $row_traza["nombre"]; ?></td>
											<td valign=top width=2%> </td>
											<td valign=top><b><center><?php echo $row_traza["cantidad"]; ?></center></b></td>												
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
																
									<tr  bgcolor=#BDBDBD>
										
										<th>Serial</th>
										<th width=2%> </th>
										<th>Mac</th>
										<th width=2%> </th>
										<th>Equipo</th>
										<th width=2%> </th>																			
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
