<?php
if($PERMISOS_GC["aud_ejec"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}



$sql = "SELECT aud_base.nombre AS nom_base, usuario.nombre AS nom_usuario, usuario.apellido AS ape_usuario, aud_solicitud.estado, aud_solicitud.id,
				aud_solicitud.fecha_registro, tramite.ot, tramite.id AS offline, sra_tramite.id AS online, sra_tramite.pedido, tecnico.nombre AS nom_tecnico, 
				tramite.fecha_atencion_orden, tramite.departamento, localidad.nombre AS nom_localidad, tramite.nombre_cliente, tramite.direccion,
				sra_tramite.zona AS zona_on, sra_tramite.localidad AS localidad_on, sra_tramite.barrio AS barrio_on, sra_tramite.direccion AS direccion_on,
				sra_tramite.fecha_cita , sra_tramite.franja_horaria, aud_solicitud.observacion, aud_solicitud.id_base, aud_solicitud.fecha_inicio, sra_tramite.cliente,
				solicitante.nombre AS nom_solicitante, solicitante.apellido AS ape_solicitante, aud_solicitud.fecha_finalizado, une_pedido.numero,
				une_pedido.id AS offlinehfc, une_pedido.ciudad, une_pedido.cliente_nombre, une_pedido.cliente_direccion, une_pedido.tipo_trabajo, 
				une_pedido.nombre_funcionario, une_pedido.segmento, une_pedido.producto, une_pedido.producto_homologado, une_pedido.tecnologia, 
				une_pedido.proceso, une_pedido.empresa, une_pedido.fecha, une_pedido.codigo_funcionario, aud_base.material,
				une_pedido.telefono_contacto, une_pedido.celular_contacto, une_pedido.microzona
			FROM aud_solicitud 
		LEFT JOIN aud_base ON aud_solicitud.id_base = aud_base.id
		LEFT JOIN sra_tramite ON aud_solicitud.id_sra_tramite = sra_tramite.id
		LEFT JOIN tramite ON aud_solicitud.id_tramite = tramite.id
		LEFT JOIN une_pedido ON aud_solicitud.id_une_pedido = une_pedido.id
		LEFT JOIN localidad ON tramite.id_localidad = localidad.id
		LEFT JOIN tecnico ON tramite.id_tecnico = tecnico.id
		LEFT JOIN usuario ON aud_solicitud.id_creador = usuario.id
		LEFT JOIN usuario AS solicitante ON aud_solicitud.id_solicitud = solicitante.id
		WHERE aud_solicitud.id = '".$_GET["id"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);

if($_POST["guardar1"] || $_POST["guardar2"])
{
	if($row["material"]==1)
	{
		$sql_borrar_mat = "DELETE FROM `aud_solicitud_material` WHERE `id_solicitud` =  '".$row["id"]."' ";
		mysql_query($sql_borrar_mat);
		
		if($_POST["respuesta_material"]=='s' || $_POST["respuesta_material"]=='n')
			{ 
				$sql_insert_mat = "INSERT INTO `aud_solicitud_material` (`id_solicitud`, `respuesta`) VALUES ('".$row["id"]."', '".$_POST["respuesta_material"]."'); ";
				mysql_query($sql_insert_mat);
			}
		
		
		
		if($row["offline"])
		{
			 $sql_traza = "SELECT material_traza.cantidad, material_traza.tipo, equipo_material.nombre, equipo_material.codigo_1, 
			 material_traza.id, cantidad_auditor
			FROM material_traza 
			INNER JOIN equipo_material ON material_traza.id_equipo_material = equipo_material.id									
			WHERE material_traza.id_tramite = '".$row["offline"]."' ORDER BY equipo_material.nombre ";
			$res_traza = mysql_query($sql_traza);
			while($row_traza = mysql_fetch_array($res_traza))
			{  
				$temp = 'materia_'.$row_traza["id"];
				$sql_insert = "UPDATE  `material_traza` SET  `cantidad_auditor` =  '".$_POST[$temp]."' WHERE  `id` ='".$row_traza["id"]."' LIMIT 1";
				mysql_query($sql_insert);
			}
		}
					
		if($row["offlinehfc"])
		{
			$sql_traza = "SELECT une_material.nombre, une_material.codigo, une_pedido_material.cantidad, une_pedido_material.id,
					 une_pedido_material.alarma, cantidad_auditor
					FROM une_pedido_material 
					INNER JOIN une_material ON une_pedido_material.id_material = une_material.id									
					WHERE une_pedido_material.id_pedido = '".$row["offlinehfc"]."' ORDER BY une_material.nombre ";
			$res_traza = mysql_query($sql_traza);
			while($row_traza = mysql_fetch_array($res_traza))
			{ 
				$temp = 'materia_'.$row_traza["id"];
				$sql_insert = "UPDATE  `une_pedido_material` SET  `cantidad_auditor` =  '".$_POST[$temp]."' WHERE  `id` ='".$row_traza["id"]."' LIMIT 1";
				mysql_query($sql_insert)	;			
			}
		}
					
	}
	
	
	
	if($_POST["fecha_inicio"])
	{
		$sql_act = "UPDATE aud_solicitud SET fecha_inicio = '".$_POST["fecha_inicio"]."', estado=2 WHERE  aud_solicitud.id = '".$_GET["id"]."' LIMIT 1";
		mysql_query($sql_act);
	}

	$sql_del  = "DELETE FROM aud_solicitud_item 
			WHERE id_solicitud = '".$_GET["id"]."' AND id_item IN 
			(
				SELECT id FROM aud_item WHERE id_base='".$row["id_base"]."' AND tipo IN ('1','2')
			) ";
	mysql_query($sql_del);
		
	
	$sql_i = "SELECT * FROM aud_item WHERE 
				aud_item.estado=1 AND 
				aud_item.id_base='".$row["id_base"]."'
				ORDER BY tipo, pregunta";
	$res_i = mysql_query($sql_i);
	while($row_i = mysql_fetch_array($res_i))
	{
		$tem = "pre_".$row_i["id"];
		if($row_i["tipo"]=='1')
		{				
			
			if($_POST[$tem]=='s' || $_POST[$tem]=='n')
			{ 
				$sql_ins = "INSERT INTO aud_solicitud_item (id_solicitud, id_item, 	si_no) VALUES ('".$_GET["id"]."', '".$row_i["id"]."', '".$_POST[$tem]."')";
				mysql_query($sql_ins);
			}
		}

		if($row_i["tipo"]=='2')
		{				
			if($_POST[$tem])
			{
				$sql_ins = "INSERT INTO aud_solicitud_item (id_solicitud, id_item, 	respuesta) VALUES ('".$_GET["id"]."', '".$row_i["id"]."', '".$_POST[$tem]."')";
				mysql_query($sql_ins);
			}
		}
		
		if($row_i["tipo"]=='3')
		{				
			if($_FILES[$tem]['name'])
			{
				$sql_del  = "DELETE FROM aud_solicitud_item  WHERE id_solicitud = '".$_GET["id"]."' AND id_item = '".$row_i["id"]."' LIMIT 1 ";
				mysql_query($sql_del);
				
				$trozos = explode(".", $_FILES[$tem]['name']); 
				$extension = end($trozos);					
				$ruta = "documentos/auditoria/".$_GET["id"].'_'.$row_i["id"].".".$extension;
				move_uploaded_file($_FILES[$tem]['tmp_name'], $ruta);
				if(file_exists($ruta))
				{
					$sql_ins = "INSERT INTO aud_solicitud_item (id_solicitud, id_item, 	archivo) VALUES 
					('".$_GET["id"]."', '".$row_i["id"]."', '".$ruta."')";
					mysql_query($sql_ins);
				}
				else
				{
					$error = "El archivo no se pudo cargar";
				}
			}			
			
		}
	}	
	
	if($_POST["guardar2"] && !$error)
	{
		$sql_act = "UPDATE aud_solicitud SET fecha_finalizado = '".date("Y-m-d G:i:s")."', estado=3 WHERE  aud_solicitud.id = '".$_GET["id"]."' LIMIT 1";
		mysql_query($sql_act);		
		echo '<script >alert("Auditoria guardada, enviada y cerrada");</script>';
		
	}
	else
	{
		if(!$error)
		{
			echo '<script >alert("Auditoria guardada");</script>';
		}
		else
		{
			echo '<script >alert("Error al guardar los archivos");</script>';
		}
	}
	
	 
	 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?id=".$_GET["id"]."'>";
	 die();
	
}




$estado = '';
if($row["estado"]==1){$estado = '<font color=red><b>Sin gestionar</b></font>';}
if($row["estado"]==2){$estado = '<b>Inicializado</b>';}
if($row["estado"]==3){$estado = '<font color=green><b>Finalizado</b></font>';}	

?>

<h2> AUDITAR </h2>
<form  method="post" action="?" enctype="multipart/form-data"> 
	<table width=100%>
		<tr>			
			<td align=right>
				<a href="?cmp=lista_ejecutar"> <i class="fa fa-reply fa-2x"></i> Volver al listado de auditorias </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
			</td>
		</tr>
	</table>
</form>
<br>




<div class="col-md-100 col-sm-100">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<font size="4px"><b>Datos de la auditoria</b></font>
			</div>
			<div class="panel-body" align=center>
				<font size="4px">
						<table width=100%>		
						<tr>
							<td ><b>Estado auditoria: </b></td><td><?php echo $estado; ?></td>
						</tr>	
						
					<?php if($row["nom_solicitante"]){ ?>	
						<tr>
							<td ><b>Auditoria solicitada: </b></td><td><?php echo $row["nom_solicitante"] ?> <?php echo $row["ape_solicitante"] ?> </td>
						</tr>
					<?php } ?>	
					
					<?php if($row["offlinehfc"]){ ?>
						<tr>
							<td><b>Numero: </b></td><td><?php echo $row["numero"] ?></td>
						</tr>								
						<tr>
							<td><b>Ciudad: </b></td><td><?php echo $row["ciudad"] ?></td>
						</tr>
						<tr>
							<td><b>Cliente: </b></td><td><?php echo $row["cliente_nombre"] ?></td>
						</tr>
						<tr>
							<td><b>Direccion: </b></td><td><?php echo $row["cliente_direccion"] ?></td>
						</tr>
						
						<tr>
							<td><b>Telefono: </b></td><td><?php echo $row["telefono_contacto"] ?></td>
						</tr>
						<tr>
							<td><b>Celular: </b></td><td><?php echo $row["celular_contacto"] ?></td>
						</tr>
						<tr>
							<td><b>Microzona: </b></td><td><?php echo $row["microzona"] ?></td>
						</tr>
						
						
						<tr>
							<td><b>Tipo trabajo: </b></td><td><?php echo $row["tipo_trabajo"] ?></td>
						</tr>
						<tr>
							<td><b>Funcionario: </b></td><td><?php echo $row["nombre_funcionario"] ?> (<?php echo $row["codigo_funcionario"] ?>) </td>
						</tr>
						<tr>
							<td><b>Segmento: </b></td><td><?php echo $row["segmento"] ?></td>
						</tr>
						<tr>
							<td><b>Producto: </b></td><td><?php echo $row["producto"] ?></td>
						</tr>
						<tr>
							<td><b>Producto homologado: </b></td><td><?php echo $row["producto_homologado"] ?></td>
						</tr>
						<tr>
							<td><b>Tecnologia: </b></td><td><?php echo $row["tecnologia"] ?></td>
						</tr>
						<tr>
							<td><b>Proceso: </b></td><td><?php echo $row["proceso"] ?></td>
						</tr>
						<tr>
							<td><b>Empresa: </b></td><td><?php echo $row["empresa"] ?></td>
						</tr>
						<tr>
							<td><b>Fecha: </b></td><td><?php echo $row["fecha"] ?></td>
						</tr>					
					<?php } ?>	
						
					<?php if($row["offline"]){ ?>
						<tr>
							<td ><b>OT: </b></td><td><?php echo $row["ot"] ?></td>
						</tr>									
						<tr>
							<td><b>Tecnico: </b></td><td><?php echo $row["nom_tecnico"] ?></td>
						</tr>
							
						<tr>
							<td><b>Fecha de atencion: </b></td><td><?php echo $row["fecha_atencion_orden"] ?></td>
						</tr>
																								
						<tr>
							<td><b>Departamento: </b></td><td><?php echo $row["departamento"] ?></td>
						</tr>
					
						<tr>
							<td><b>Localidad: </b></td><td><?php echo $row["nom_localidad"] ?></td>
						</tr>
														
						<tr>
							<td><b>Nombre Cliente: </b></td><td><?php echo $row["nombre_cliente"] ?></td>
						</tr>
						<tr>
							<td><b>Direccion: </b></td><td><?php echo $row["direccion"] ?></td>
						</tr>
					<?php } ?>
					
					<?php if($row["online"]){ ?>
						<tr>
							<td ><b>Pedido: </b></td><td><?php echo $row["pedido"] ?></td>
						</tr>									
						<tr>
							<td><b>Zona: </b></td><td><?php echo $row["zona_on"] ?></td>
						</tr>
						<tr>
							<td><b>Localidad: </b></td><td><?php echo $row["localidad_on"] ?></td>
						</tr>
						<tr>
							<td><b>Barrio: </b></td><td><?php echo $row["barrio_on"] ?></td>
						</tr>
						<tr>
							<td><b>Direccion: </b></td><td><?php echo $row["direccion_on"] ?></td>
						</tr>
						<tr>
							<td><b>Cliente: </b></td><td><?php echo $row["cliente"] ?></td>
						</tr>
						<tr>
							<td><b>Fecha de la cita: </b></td><td><?php echo $row["fecha_cita"] ?> <?php echo $row["franja_horaria"] ?> </td>
						</tr>
					
					<?php } ?>
					
						<tr>
							<td ><b>Inicio auditoria: </b></td><td><?php echo $row["fecha_inicio"] ?></td>
						</tr>
						
						<tr>
							<td ><b>Fin auditoria: </b></td><td><?php echo $row["fecha_finalizado"] ?></td>
						</tr>
					
						<tr>
							<td ><b>Observacion: </b></td><td><?php echo $row["observacion"] ?></td>
						</tr>
					</table>
				</font>
		</div>
		
</div>
<form action="?id=<?php echo $_GET["id"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">			
<?php
if($row["material"]==1){
 ?>	
<div class="col-md-100 col-sm-100">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<font size="4px"><b>Material</b></font>
			</div>
			<div class="panel-body" align=center>
			<h4>
				<table width=100% border=0 >
					<tr>
						<th></th>	
						<th></th>					
						<th ><center>Cant. Tec.</center></th>
						<th ><center>Cant. Aud.</center></th>
					</tr>
					<?php
					if($row["offline"])
					{
							 $sql_traza = "SELECT material_traza.cantidad, material_traza.tipo, equipo_material.nombre, equipo_material.codigo_1, 
							 material_traza.id, cantidad_auditor
							FROM material_traza 
							INNER JOIN equipo_material ON material_traza.id_equipo_material = equipo_material.id									
							WHERE material_traza.id_tramite = '".$row["offline"]."' ORDER BY equipo_material.nombre ";
							$res_traza = mysql_query($sql_traza);
							while($row_traza = mysql_fetch_array($res_traza))
							{ ?>
								<tr>
									<td valign=top> </td>	
									<td valign=top><?php echo $row_traza["nombre"] ?><br></td>							
									<td valign=top align=center><?php echo $row_traza["cantidad"]; ?></td>
									<td align=center> <input type="number" <input <?php if($row["estado"]==3){ ?> readonly <?php } ?> style="width:80px" value="<?php echo $row_traza["cantidad_auditor"]; ?>" name="materia_<?php echo $row_traza["id"]; ?>" class="form-control"><br> </td>
								</tr>
								<input type="hidden" >
							<?php
									
							}
					}
					
					if($row["offlinehfc"])
					{
							$sql_traza = "SELECT une_material.nombre, une_material.codigo, une_pedido_material.cantidad, une_pedido_material.id,
									 une_pedido_material.alarma, cantidad_auditor
									FROM une_pedido_material 
									INNER JOIN une_material ON une_pedido_material.id_material = une_material.id									
									WHERE une_pedido_material.id_pedido = '".$row["offlinehfc"]."' ORDER BY une_material.nombre ";
							$res_traza = mysql_query($sql_traza);
							while($row_traza = mysql_fetch_array($res_traza))
							{ 
								$alarma = "";
								if($row_traza["alarma"]==2){$alarma = "<font color=yellow><i class='fa fa-exclamation-triangle fa'> </i></font>";}
								if($row_traza["alarma"]==3){$alarma = "<font color=red><i class='fa fa-exclamation-triangle fa'> </i></font>";}
							?>
								<tr>
									<td valign=top><?php echo $alarma; ?><br></td>	
									<td valign=top><?php echo $row_traza["nombre"] ?><br></td>							
									<td valign=top align=center><?php echo $row_traza["cantidad"]; ?></td>
									<td align=center> <input <?php if($row["estado"]==3){ ?> readonly <?php } ?> type="number" style="width:80px" value="<?php echo $row_traza["cantidad_auditor"]; ?>" name="materia_<?php echo $row_traza["id"]; ?>" class="form-control"><br> </td>
								</tr>
							<?php
							}
					}
					?>
				</table>
						
				<table width=100%>
					<tr>
						<td width=70%>											
							CUMPLE CON LOS MATERIALES Y SUS CANTIDADES							
						</td>
						<td width=30% align=right>		
								<?php
								
								$sql_material = "SELECT respuesta FROM  `aud_solicitud_material` WHERE id_solicitud = '".$row["id"]."' LIMIT 1";
								$res_material = mysql_query($sql_material);
								$row_material = mysql_fetch_array($res_material);
								?>
									<?php if($row["estado"]!=3){ ?>
											<div class="btn-group" data-toggle="buttons">
											  <label class="btn btn-primary<?php if(!$row_material["respuesta"]){ ?> active<?php } ?>">
												<input  type="radio" value="1" <?php if(!$row_material["respuesta"]){ ?> checked <?php } ?> name="respuesta_material" id="respuesta_material" autocomplete="off" > NA
											  </label>
											  
											  <label class="btn btn-primary<?php if($row_material["respuesta"]=='s'){ ?> active<?php } ?>">
												<input type="radio" value="s" <?php if($row_material["respuesta"]=='s'){ ?> checked <?php } ?> name="respuesta_material" id="respuesta_material" autocomplete="off"> Si cum
											  </label>
											  
											  <label class="btn btn-primary<?php if($row_material["respuesta"]=='n'){ ?> active<?php } ?>">
												<input type="radio" value="n" <?php if($row_material["respuesta"]=='n'){ ?> checked <?php } ?>name="respuesta_material" id="respuesta_material" autocomplete="off"> No cum
											  </label>
											</div>	
									<?php }else{ ?>	
											<div class="btn-group" data-toggle="buttons">
											<label disabled class="btn btn-<?php if(!$row_material["respuesta"]){ ?>danger<?php }else{ ?>primary<?php } ?>">
												 NA
											  </label>											  
											  <label disabled class="btn btn-<?php if($row_material["respuesta"]=='s'){ ?>danger<?php }else{ ?>primary<?php } ?>">
												Si cum
											  </label>											  
											  <label disabled class="btn btn-<?php if($row_material["respuesta"]=='n'){ ?>danger<?php }else{ ?>primary<?php } ?>">
												 No cum
											  </label>
											</div>						
									
									<?php } ?>	
						</td>								
					</tr>
				</table>
				</h4>
			</div>
		</div>
</div>				
								
<?php } 


$sql_cate = "SELECT aud_categoria.id, aud_categoria.nombre FROM aud_categoria 
LEFT JOIN aud_item ON aud_categoria.id = aud_item.id_categoria
WHERE aud_categoria.estado = 1 AND aud_item.id_base='".$row["id_base"]."' GROUP BY aud_categoria.id 
ORDER BY orden ASC ";

$res_cate = mysql_query($sql_cate);
while($row_cate = mysql_fetch_array($res_cate))
{
?>
	<div class="col-md-100 col-sm-100">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<font size="4px"><b><?php echo $row_cate["nombre"] ?></b></font>
			</div>
			<div class="panel-body" align=center>
				<table width=100% border=0>
						<?php 
							$sql_pre = "SELECT * FROM aud_item WHERE 
								aud_item.estado=1 AND 
								aud_item.id_base='".$row["id_base"]."' AND
								aud_item.id_categoria ='".$row_cate["id"]."'
								ORDER BY tipo, pregunta";
							$res_pre = mysql_query($sql_pre);
							while($row_pre = mysql_fetch_array($res_pre))
							{								
								$sql_item = "SELECT si_no, respuesta, archivo FROM aud_solicitud_item 
									WHERE id_solicitud = '".$row["id"]."' AND id_item = '".$row_pre["id"]."'  LIMIT 1";
								$res_item = mysql_query($sql_item);
								$row_item = mysql_fetch_array($res_item);								
								?>
								
								<?php if($row_pre["tipo"]==1){ ?>						
										<tr>
												<td width=60%>											
													<h4><?php echo $row_pre["pregunta"] ?></h4>									
												</td>
												<td align=right width=40%>								
														
													<?php if($row["estado"]!=3){ ?>
														<div class="btn-group" data-toggle="buttons">
														  <label class="btn btn-primary<?php if(!$row_item["si_no"]){ ?> active<?php } ?>">
															<input  type="radio" value="1" <?php if(!$row_item["si_no"]){ ?> checked <?php } ?> name="pre_<?php echo $row_pre["id"]; ?>" id="pre_<?php echo $row_pre["id"]; ?>" autocomplete="off" > NA
														  </label>
														  
														  <label class="btn btn-primary<?php if($row_item["si_no"]=='s'){ ?> active<?php } ?>">
															<input type="radio" value="s" <?php if($row_item["si_no"]=='s'){ ?> checked <?php } ?> name="pre_<?php echo $row_pre["id"]; ?>" id="pre_<?php echo $row_pre["id"]; ?>" autocomplete="off"> Si cum
														  </label>
														  
														  <label class="btn btn-primary<?php if($row_item["si_no"]=='n'){ ?> active<?php } ?>">
															<input type="radio" value="n" <?php if($row_item["si_no"]=='n'){ ?> checked <?php } ?>name="pre_<?php echo $row_pre["id"]; ?>" id="pre_<?php echo $row_pre["id"]; ?>" autocomplete="off"> No cum
														  </label>
														</div>
													<?php }else{ ?>														
														<div class="btn-group" data-toggle="buttons">
														  <label disabled class="btn btn-<?php if(!$row_item["si_no"]){ ?>danger<?php }else{ ?>primary<?php } ?>">
															 NA
														  </label>
														  
														  <label disabled class="btn btn-<?php if($row_item["si_no"]=='s'){ ?>danger<?php }else{ ?>primary<?php } ?>">
															Si cum
														  </label>
														  
														  <label disabled class="btn btn-<?php if($row_item["si_no"]=='n'){ ?>danger<?php }else{ ?>primary<?php } ?>">
															 No cum
														  </label>
														</div>
													<?php } ?>
												
												</td>								
											</tr>
								<?php } ?>
								
								<?php if($row_pre["tipo"]==2){ ?>						
										<tr>
												<td width=100% colspan=2>												
													<h4><?php echo $row_pre["pregunta"]; ?></h4>	
													<textarea <?php if($row["estado"]==3){ ?> readonly <?php } ?> style="width:100%" rows="5" name="pre_<?php echo $row_pre["id"]; ?>"><?php echo $row_item["respuesta"]; ?></textarea>
												</td>
																			
											</tr>
								<?php } ?>
								
								<?php if($row_pre["tipo"]==3){ ?>						
										<tr>
												<td >											
													<h4>
														<?php echo $row_pre["pregunta"]; ?> 
													    <?php if($row_item["archivo"] && $row["estado"]!=3){ ?>
														  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo $row_item["archivo"]; ?>" target=blank>  <i class="fa fa-cloud-download fa-2x"></i> Descargar</a>
														<?php } ?>
													</h4>			 						
												</td>
												<td  align=right>
													<h4>
														<?php if($row["estado"]!=3){ ?>
															<input type="file" name="pre_<?php echo $row_pre["id"]; ?>" class="form-control">
														<?php }else{ ?>
															<center>
																<?php if($row_item["archivo"]){ ?>
																	<a href="<?php echo $row_item["archivo"]; ?>" target=blank>  <i class="fa fa-cloud-download fa-2x"></i> Descargar</a>
																<?php }else{ ?>
																	----------
																<?php } ?>
															</center>
														<?php } ?>
													</h4>
												</td>								
											</tr>
								<?php } ?>
						<?php
							}
						?>
				</table>			
			</div>
		</div>
	</div>	

<?php
}
?>


<?php if($row["estado"]==1){ ?>
	<input type="hidden" name="fecha_inicio" value="<?php echo date("Y-m-d G:i:s"); ?>">
<?php } ?>


<?php if($row["estado"]!=3){ ?>
	<table align=center width=100%>
		<tr>
			<td align=center width=50%>
				<input class="btn btn-primary" type="submit" value="Guardar auditoria SIN enviar" name="guardar1" />
			<td>
			<td align=center width=50%>
				<input class="btn btn-primary" type="submit" value="Guardar auditoria, enviar y cerrar" name="guardar2" />
			</td>
		</tr>
	</table>
</form>
<?php } ?>