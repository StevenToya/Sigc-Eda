<?php
if($PERMISOS_GC["hv_aud_doc"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

$o = $r = $a = 0;
if($_POST["guardar"])
{
	$sql = "SELECT id FROM hv_documento_persona WHERE hv_documento_persona.id_persona = '".$_GET["idvehiculo"]."' AND hv_documento_persona.estado='1' AND hv_documento_persona.fase='1' ";
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		$tem = "gestion_".$row["id"];
		if($_POST[$tem] == 'n')
		{	$o++;	}
		
		if($_POST[$tem] == '2')
		{	
			$a++;
			$sql_act = "UPDATE `hv_documento_persona` SET `fase` = '2' WHERE  id = '".$row["id"]."' ;";
			mysql_query($sql_act);
		}
		
		if($_POST[$tem] == '3')
		{	
			$r++;
			$sql_act = "UPDATE `hv_documento_persona` SET `fase` = '3' WHERE  id = '".$row["id"]."' ;";
			mysql_query($sql_act);
		}
	}		
		echo '<script> alert("Se gestiono correctamente con estos caso: \n Omitidos:'.$o.' \n Aceptados:'.$a.' \n Rechazados:'.$r.' ");</script>';
		echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?idvehiculo=".$_GET["idvehiculo"]."'>";die();
}






function tipo_archivo($archivo)
{
	if($archivo && file_exists($archivo))
	{
		
		$alea = rand(1,1000);
		$imagen = '';
		$trozos = explode(".", $archivo); 
		$extension = end($trozos);
		
		if($extension=='gif' || $extension=='GIF' || $extension=='jpg' || $extension=='JPG' || $extension=='jpeg' || $extension=='JPEG' || $extension=='png' || $extension=='PNG')
		{$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="libreria/phpThumb/phpThumb.php?src=/'.$archivo.'&amp;h=150&amp;w=150" ></a>';}
		if($extension=='doc' || $extension=='DOC'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/doc.jpg" width="100px"></a>';}
		if($extension=='docx' || $extension=='DOCX'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/doc.jpg" width="100px"></a>';}
		if($extension=='pdf' || $extension=='PDF'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/pdf.jpg" width="100px"></a>';}
		if($extension=='zip' || $extension=='ZIP'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/zip.jpg" width="100px"></a>';}
		if($extension=='rar' || $extension=='RAR'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/rar.jpg" width="100px"></a>';}
		if($extension=='xls' || $extension=='XLS'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/xls.jpg" width="100px"></a>';}
		if($extension=='xlsx'  ||  $extension=='XLSX'  ||  $extension=='csv' || $extension=='CSV'){$imagen = '<a href="'.$archivo.'"  target="_blank"><img src="img/xls.jpg" width="100px"></a>';}
		if($extension=='ppt' || $extension=='PPT'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/ppt.jpg" width="100px"></a>';}
		if($extension=='pptx' || $extension=='PPTX'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/ppt.jpg" width="100px"></a>';}
		if($extension=='bmp' || $extension=='BMP'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/bmp.jpg" width="100px"></a>';}
		if($extension=='avi' || $extension=='AVI'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/avi.jpg" width="100px"></a>';}

		if(!$imagen)
		{$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/inusual_archivo.png" width="100px"></a>';}
	}
	else
	{
		$imagen = '<img src="img/sin_archivo.png" width="100px">';
	}
	
	return $imagen ;
	
}

$sql = "SELECT hv_persona.nombre, hv_persona.apellido, hv_persona.id, hv_persona.identificacion, hv_persona.direccion, hv_persona.telefono,
hv_persona.correo, cargo.nombre AS nom_cargo, hv_persona.foto,  municipio.nombre AS nom_municipio
FROM hv_persona 
INNER JOIN municipio ON hv_persona.id_municipio = municipio.id
INNER JOIN cargo ON hv_persona.id_cargo = cargo.id
WHERE hv_persona.id = '".$_GET["idvehiculo"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
?>


<h2>AUDITAR DOCUMENTOS</h2>
<div align=right>
	<a href="?cmp=auditar_pendiente"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de hojas de vida </a>
</div>


 <form action="?idvehiculo=<?php echo $_GET["idvehiculo"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">

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
<br>
<table width='95%' align='center' border=0><tr><td width='30%' valign=top>

<?php
$cont_td = 1;
$sql = "SELECT * FROM documento WHERE tipo = 3 AND id_instancia = '".$_SESSION["nst"]."' ";
$res = mysql_query($sql);
while($row = mysql_fetch_array($res))
{	
	$sql_cond = "SELECT * FROM hv_documento_persona 
	WHERE id_persona = '".$_GET["idvehiculo"]."' AND id_documento = '".$row["id"]."' AND estado=1 LIMIT 1 ";
	$res_cond = mysql_query($sql_cond);
	$row_cond = mysql_fetch_array($res_cond);
		
		
						if($cont_td>3)
						{
							$cont_td = 1;
					?>
								</td>
							</tr>
							<tr>
								<td  width='30%' valign=top> 
					<?php
						}
					?>	
								<?php
								
									//fecha de vencimiento
									$color_venc ='';
									if(strtotime(date("Y-m-d")) > strtotime($row_cond["fecha_vencimiento"]))
									{ $color_venc = "red";}
									
									if(strtotime(date("Y-m-d")) == strtotime($row_cond["fecha_vencimiento"]))
									{ $color_venc = "yellow";}
									
									//Fecha de expedicion
									$color_revi ='';
									
									if($row["revision_mes"]>0)
									{
										$dias = $row["revision_mes"] * 30;
										$cant_dias = $dias.' days';
										$fecha = date_create($row_cond["fecha_expedicion"]);				
										
										date_add($fecha, date_interval_create_from_date_string($cant_dias));
										 $fecha_expedicion =  date_format($fecha, 'Y-m-d');
										
										if(strtotime(date("Y-m-d")) > strtotime($fecha_expedicion))
										{ $color_revi = "red";}
										
										if(strtotime(date("Y-m-d")) == strtotime($fecha_expedicion))
										{ $color_revi = "yellow";}
									}
								
								?>
								
								<table  width='100%' align='center' border=0>
								<tr>
									<td colspan=2 valign=top>			
									
									 <div class="col-md-100 col-sm-100">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<?php echo $row["descripcion"]; ?>  
											</div>
											<div class="panel-body" align=center>
											<table  width='100%' align='center' border=0>
												<tr>
													<td valign=top align=right width="40%">
														<?php echo tipo_archivo($row_cond["archivo"]); ?>				    
													</td>
													<td width="5%">	</td>
													<td valign=top  width="55%">
														
															<?php if($row["fecha_vencimiento"]=='s'){ ?>
																<font color='<?php echo $color_venc; ?>'>
																	Fec. Venc: 
																	<b><?php echo $row_cond["fecha_vencimiento"]?></b> <br><br>
																</font>
															<?php } ?>
															
															<?php if($row["fecha_revision"]=='s'){ ?>
																<font color='<?php echo $color_revi; ?>'>
																	Fec. Expe: 
																	<b><?php echo $row_cond["fecha_expedicion"]; ?></b>	<br><br>
																</font>
															<?php } ?>
																						
													
															<?php
															$estado_doc = '<b><font color=red>Sin Informacion</font></b>';
															if($row_cond["fase"]==1 ){$estado_doc = '<b><font color=#DF7401>Sin revision</font></b>';}
															if($row_cond["fase"]==2 ){$estado_doc = '<b><font color=green>Aceptada</font></b>';}
															if($row_cond["fase"]==3 ){$estado_doc = '<b><font color=red>Rechazada</font></b>';}
															?>
															
															<?php if($row_cond["fase"]<>1){ ?>
																Estado: <?php echo $estado_doc; ?>
															<?php }else{ ?>
															<select name="gestion_<?php echo $row_cond["id"] ?>">
																	 <option value="n">Tomar una opcion</option>
																	 <option value="2">Aceptar</option>
																	 <option value="3">Rechazar</option>								
																</select>
															<?php } ?>
														
														
														
													</td>
												</tr>
												<tr>
													<td colspan=3 align=center valign=top>
														<div id="div_edi_img_<?php echo $row["id"]; ?>"  style="display: none;">
															<input type="file"  name="archivo_<?php echo $row["id"]; ?>" style=" heigth:24px; width:250px;" >
														</div>
														
													
														
													</td>
												</tr>

											</table>
												
											</div>
											
										</div>
									</div>									
									</td>
								</tr>
								
								</table><br>
								
							</td>
							<td width='3%' valign=top> 
							<br>
							</td>
							
							<td  width='30%' valign=top>	

					<?php
						$cont_td ++;
		
}
?>
</td></tr></table>

	<center>
			<input type="submit" value="Auditar documentos"  name="guardar" class="btn btn-primary" />
	</center>
	
	
</form>