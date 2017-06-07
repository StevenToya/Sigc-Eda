<?php
/*
if($PERMISOS_GC["hv_doc"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}
*/

function estado_documento($id, $tipo, $nst)
{
	$ok = $ven = $exp = $esp =  $rec = 0;
	
	if($tipo==2)
	{
		$sql = "SELECT documento_vehiculo.fecha_vencimiento, documento_vehiculo.fase, documento_vehiculo.fecha_expedicion, documento.revision_mes,
			documento.fecha_vencimiento AS fecha_vencimiento2, documento.fecha_revision
			FROM documento_vehiculo 
			INNER JOIN documento ON documento_vehiculo.id_documento  = documento.id
			WHERE id_instancia='".$nst."' AND documento_vehiculo.estado=1 AND documento_vehiculo.id_vehiculo = '".$id."' ";
	}
	
	if($tipo==3)
	{
		 $sql = "SELECT hv_documento_persona.fecha_vencimiento, hv_documento_persona.fase, hv_documento_persona.fecha_expedicion, documento.revision_mes,
			documento.fecha_vencimiento AS fecha_vencimiento2, documento.fecha_revision
			FROM hv_documento_persona 
			INNER JOIN documento ON hv_documento_persona.id_documento  = documento.id
			WHERE id_instancia='".$nst."' AND hv_documento_persona.estado=1 AND hv_documento_persona.id_persona = '".$id."' ";
	}
	
	
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		if($row["fase"]==1){$esp ++;}
		if($row["fase"]==2)
		{
			if((strtotime(date("Y-m-d")) > strtotime($row["fecha_vencimiento"])) && ($row["fecha_vencimiento2"]=='s'))
			{
				$ven ++;
			}
			else
			{
				if(($row["revision_mes"]>0) && ($row["fecha_revision"]=='s') )
				{
					$dias = $row["revision_mes"] * 30;
					$cant_dias = $dias.' days';
					$fecha = date_create($row["fecha_expedicion"]);				
					
					date_add($fecha, date_interval_create_from_date_string($cant_dias));
					 $fecha_expedicion =  date_format($fecha, 'Y-m-d');
					
					if(strtotime(date("Y-m-d")) > strtotime($fecha_expedicion))
					{ 
						$exp ++;
					}
					else
					{
						$ok++;
					}
				}
				else
				{
					$ok++;
				}			
			}
				
		}
		if($row["fase"]==3){$rec ++;}
		
			
	}
	

		$sql_total = "SELECT COUNT(*) AS cantidad FROM documento WHERE id_instancia='".$nst."' AND tipo= '".$tipo."'  ";
	
	
	
	$res_total = mysql_query($sql_total);
	$row_total = mysql_fetch_array($res_total);
	
	if($ok == $row_total["cantidad"])
	{$resultado["apto"]='s';}
	else
	{$resultado["apto"]='n';}
	
	
	$resultado["total"] = $row_total["cantidad"];	
	 $resultado["ok"] = $ok;
	$resultado["ven"] = $ven;
	$resultado["exp"] = $exp;
	$resultado["esp"] = $esp;
	$resultado["rec"] = $rec;
	

	return $resultado;
	
}

?>
<h2>VEHICULOS</h2>

<div align=right> 						
	<a href='?cmp=ingresar'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Crear vehiculo</a>					
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		 Status de la documentacion
	</div>
	<div class="panel-body">
		<div class="table-responsive">
		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Placa</th>	
						<th>Conductor</th>
						<?php
							$cont_doc = 0;
							$sql = "SELECT * FROM documento WHERE id_instancia='".$_SESSION["nst"]."' AND tipo = 2 ORDER BY descripcion";
							$res = mysql_query($sql);
							while($row = mysql_fetch_array($res))
							{
								$vec_doc[$cont_doc] = $row["id"];
								$vec_car[$cont_doc] = $row["carga"];
								$vec_rev[$cont_doc] = $row["revision_mes"];
						?>
								<th>
									<?php echo $row["descripcion"] ?> 
									<?php
									if($row["fecha_vencimiento"]=='s'){ echo "(FV)";}else{ echo "(FR)";}
									?>									
								</th>
						<?php
								$cont_doc ++;
							}					
						?>					
						<!-- <th>Ver</th> -->
						<th>Estado</th>
						<th>Editar</th>										
					</tr>
				</thead>
				<tbody>
				<?php
				$sql = "SELECT vehiculo.id,  vehiculo.placa, vehiculo.estado, hv_persona.nombre AS per_nombre, hv_persona.apellido AS per_apellido,
				hv_persona.id AS per_id
				FROM vehiculo  
				LEFT JOIN hv_persona ON vehiculo.id_persona = hv_persona.id
				WHERE vehiculo.estado='1' AND vehiculo.id_instancia='".$_SESSION["nst"]."' 
				ORDER BY vehiculo.placa";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
						$resultado_vehiculo = estado_documento($row["id"],2, $_SESSION["nst"]);							
						if($resultado_vehiculo["apto"]=='s'  && $row["estado"]==1)
						{$estado ='<center><font color=green><b>Apto</b></font></center>';}
						else{$estado ='<center><font color=red><b>No apto</b></font></center>';}
						
						$resultado_conductor = estado_documento($row["per_id"],3, $_SESSION["nst"]);
						if($resultado_conductor["apto"]=='s'  && $row["estado"]==1)
						{$estado_per ='<center><font color=green>'.$row["per_apellido"].' '.$row["per_nombre"].'<br><b>Apto</b></font></center>';}
						else{$estado_per ='<center><font color=red>'.$row["per_apellido"].' '.$row["per_nombre"].'<br><b>No apto</b></font></center>';}
						
					
				?>
					<tr class="odd gradeX">
						<td><?php echo $row["placa"] ?> </td>
						<td align=center><?php echo $estado_per; ?> </font></td>
						
						<?php
							$cont_doc =0;
							while($vec_doc[$cont_doc])
							{									
						?>																			
										<?php
											$sql_doc = "SELECT * FROM documento_vehiculo 
											WHERE id_documento = '".$vec_doc[$cont_doc]."' AND id_vehiculo = '".$row["id"]."' AND estado=1 ";
											$res_doc = mysql_query($sql_doc);
											$row_doc = mysql_fetch_array($res_doc);											
										?>
								
												<?php if(!$row_doc["id"]){ ?>
													<td align="center" ><font color="red"><b>Sin gestionar</b></font></td>
												<?php }else{ ?>
													<?php if($row_doc["fecha_vencimiento"]){ ?>
															
															<?php
															if($row_doc["fase"]=='1'){$fs = '<b><font color=#DF7401>(E)</font></b>';}
															if($row_doc["fase"]=='2'){$fs = '<b><font color=green>(A)</font></b>';}
															if($row_doc["fase"]=='3'){$fs = '<b><font color=red>(R)</font></b>';}
															
														
															if(strtotime(date("Y-m-d")) > strtotime($row_doc["fecha_vencimiento"]))
															{ $color_venc = ' color="red" ';}else{$color_venc = '';}
															
															?>
															<td align="center">
																 <a href="<?php echo $row_doc["archivo"]; ?>"  target="_blank">
																	<font <?php echo $color_venc; ?> ><b><?php echo $row_doc["fecha_vencimiento"]; ?></b></font>
																</a>
																 <?php echo $fs; ?>
															</td>
													<?php }else{ ?>
															<?php
																if($vec_rev[$cont_doc]>0)
																{
																	$dias = $vec_rev[$cont_doc] * 30;
																	$cant_dias = $dias.' days';
																	$fecha = date_create($row_doc["fecha_expedicion"]);				
																	
																	date_add($fecha, date_interval_create_from_date_string($cant_dias));
																	 $fecha_expedicion =  date_format($fecha, 'Y-m-d');
																	
																	if(strtotime(date("Y-m-d")) > strtotime($fecha_expedicion))
																	{ $color_revi = ' color="red" ';}else{ $color_revi = '';}
																	
																}
																if($row_doc["fase"]=='1'){$fs = '<b><font color=#DF7401>(E)</font></b>';}
																if($row_doc["fase"]=='2'){$fs = '<b><font color=green>(A)</font></b>';}
																if($row_doc["fase"]=='3'){$fs = '<b><font color=red>(R)</font></b>';}
															?>
															<td align="center">
																<a href="<?php echo $row_doc["archivo"]; ?>"  target="_blank">
																	<font <?php echo $color_revi; ?> ><b><?php echo $row_doc["fecha_expedicion"]; ?></b></font>
																</a>
																<?php echo $fs; ?>
															</td>
															
													<?php }?>
															
												<?php }?>
									
								
																
										
						<?php
								$cont_doc++;
							}
						?>
						<!-- <td align=center><a href="?cmp=detalle_persona&idvehiculo=<?php echo $row["id"]; ?>"><i class="fa fa-eye fa-2x"></i></a></td> -->
						<td align='center'><b><?php echo $estado; ?></b></td>
						<td  align='center'><a href="?cmp=editar_documento&idvehiculo=<?php echo $row["id"] ?>"><i class="fa fa-pencil-square-o fa-2x"></i> </a></td></td>
						
						
				   </tr>
				<?php
				}
				?>
				   
				</tbody>
			</table>
		</div>
		
	</div>
</div>

<table>
	<tr>
		<td><b><font color=#DF7401>(E) </font></b> </td>
		<td> En espera para ser auditado</td>
	</tr>
	<tr>
		<td><b><font color=green>(A) </font></b> </td>
		<td>Documento aceptado por auditor</td>
	</tr>
	<tr>
		<td><b><font color=red>(R) </font></b> </td>
		<td>Documento rechazado por auditor</td>
	</tr>
</table>
