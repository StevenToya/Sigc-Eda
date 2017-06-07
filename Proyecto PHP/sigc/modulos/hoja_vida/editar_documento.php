<?php
if($PERMISOS_GC["hv_doc"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

?>


<script type="text/javascript">
<?php
$sql = "SELECT * FROM documento WHERE tipo = 3 AND id_instancia='".$_SESSION["nst"]."'  ";
$res = mysql_query($sql);
while($row = mysql_fetch_array($res))
{
?>   

   function mostrar_<?php echo $row["id"]; ?>() {
        element = document.getElementById("div_edi_<?php echo $row["id"]; ?>");
		element2 = document.getElementById("div_noedi_<?php echo $row["id"]; ?>");
		
		element_img = document.getElementById("div_edi_img_<?php echo $row["id"]; ?>");
		element2_img = document.getElementById("div_noedi_img_<?php echo $row["id"]; ?>");
				
        check = document.getElementById("check_<?php echo $row["id"]; ?>");
        if (check.checked) {
            element.style.display='block';
			element2.style.display='none';
			
			element_img.style.display='block';
			element2_img.style.display='none';
        }
        else 
		{
            element.style.display='none';
			element2.style.display='block';
			
			element_img.style.display='none';
			element2_img.style.display='block';
        }
    }
<?php
}
?>	
	
</script>

<?php

function limpiar2($data)
{
	$data = str_replace('  ', ' ', $data);
	$data = str_replace("<","",$data);
	$data = str_replace(">","",$data);
	$data = str_replace('"',"",$data);
	$data = str_replace("'","",$data);
	$data = str_replace("/","",$data);
	$data = str_replace ('
', '', $data);

	$data = str_replace('á', 'a', $data);	$data = str_replace('à', 'a', $data);	
	$data = str_replace('é', 'e', $data);	$data = str_replace('è', 'e', $data);	
	$data = str_replace('í', 'i', $data);	$data = str_replace('ì', 'i', $data);	
	$data = str_replace('ó', 'o', $data);	$data = str_replace('ò', 'o', $data);	
	$data = str_replace('ú', 'u', $data);	$data = str_replace('ù', 'u', $data);	
	$data = str_replace('Á', 'A', $data);	$data = str_replace('À', 'a', $data);	
	$data = str_replace('É', 'E', $data);	$data = str_replace('È', 'e', $data);	
	$data = str_replace('Í', 'I', $data);	$data = str_replace('Ì', 'i', $data);	
	$data = str_replace('Ó', 'O', $data);	$data = str_replace('Ò', 'o', $data);	
	$data = str_replace('Ú', 'U', $data);	$data = str_replace('Ù', 'u', $data);	
	$data = str_replace('Ñ', 'N', $data);	$data = str_replace('ñ', 'n', $data);	
	
	$data = str_replace(utf8_decode('á'), 'a', $data);	$data = str_replace(utf8_decode('à'), 'a', $data);	
	$data = str_replace(utf8_decode('é'), 'e', $data);	$data = str_replace(utf8_decode('è'), 'e', $data);	
	$data = str_replace(utf8_decode('í'), 'i', $data);	$data = str_replace(utf8_decode('ì'), 'i', $data);	
	$data = str_replace(utf8_decode('ó'), 'o', $data);	$data = str_replace(utf8_decode('ò'), 'o', $data);	
	$data = str_replace(utf8_decode('ú'), 'u', $data);	$data = str_replace(utf8_decode('ù'), 'u', $data);	
	$data = str_replace(utf8_decode('Á'), 'A', $data);	$data = str_replace(utf8_decode('À'), 'a', $data);	
	$data = str_replace(utf8_decode('É'), 'E', $data);	$data = str_replace(utf8_decode('È'), 'e', $data);	
	$data = str_replace(utf8_decode('Í'), 'I', $data);	$data = str_replace(utf8_decode('Ì'), 'i', $data);	
	$data = str_replace(utf8_decode('Ó'), 'O', $data);	$data = str_replace(utf8_decode('Ò'), 'o', $data);	
	$data = str_replace(utf8_decode('Ú'), 'U', $data);	$data = str_replace(utf8_decode('Ù'), 'u', $data);	
	$data = str_replace(utf8_decode('Ñ'), 'N', $data);	$data = str_replace(utf8_decode('ñ'), 'n', $data);

	$data = str_replace(utf8_encode('á'), 'a', $data);	$data = str_replace(utf8_encode('à'), 'a', $data);	
	$data = str_replace(utf8_encode('é'), 'e', $data);	$data = str_replace(utf8_encode('è'), 'e', $data);	
	$data = str_replace(utf8_encode('í'), 'i', $data);	$data = str_replace(utf8_encode('ì'), 'i', $data);	
	$data = str_replace(utf8_encode('ó'), 'o', $data);	$data = str_replace(utf8_encode('ò'), 'o', $data);	
	$data = str_replace(utf8_encode('ú'), 'u', $data);	$data = str_replace(utf8_encode('ù'), 'u', $data);	
	$data = str_replace(utf8_encode('Á'), 'A', $data);	$data = str_replace(utf8_encode('À'), 'a', $data);	
	$data = str_replace(utf8_encode('É'), 'E', $data);	$data = str_replace(utf8_encode('È'), 'e', $data);	
	$data = str_replace(utf8_encode('Í'), 'I', $data);	$data = str_replace(utf8_encode('Ì'), 'i', $data);	
	$data = str_replace(utf8_encode('Ó'), 'O', $data);	$data = str_replace(utf8_encode('Ò'), 'o', $data);	
	$data = str_replace(utf8_encode('Ú'), 'U', $data);	$data = str_replace(utf8_encode('Ù'), 'u', $data);	
	$data = str_replace(utf8_encode('Ñ'), 'N', $data);	$data = str_replace(utf8_encode('ñ'), 'n', $data);
	
	return $data;
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


if($_POST["guardar"])
{
		
			$sql = "SELECT identificacion FROM hv_persona WHERE id = '".$_GET["idvehiculo"]."' LIMIT 1";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res);
		
				$structure = "documentos/hv/".$_GET["idvehiculo"];
				@mkdir($structure,0777);			
				chmod($structure, 0777);
				
								
				$sql = "SELECT * FROM documento WHERE tipo = 3 AND id_instancia='".$_SESSION["nst"]."' ";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{	
					$tem_arc = 'archivo_'.$row["id"];
					if($_FILES[$tem_arc]['name'])
					{
						 $trozos = explode(".", $_FILES[$tem_arc]['name']); 
						$extension = end($trozos);	
						
						$ruta = $structure."/".$row["id"]."_".date("YmdGis").".".$extension;
												
						move_uploaded_file($_FILES[$tem_arc]['tmp_name'], $ruta);
						if(!file_exists($ruta))
						{
							$mensaje = $mensaje.'Error al cargar -'.$row["descripcion"].'-, vuelve a intentarlo \n';
						}
						else
						{										
							 	$sql_viejo = "UPDATE `hv_documento_persona` SET `estado` = '2' 
								WHERE `id_persona` = '".$_GET["idvehiculo"]."' AND id_documento = '".$row["id"]."' ;";
								mysql_query($sql_viejo);
								 
								$tem_fv = 'fv_'.$row["id"];
								$tem_fr = 'fr_'.$row["id"];
								
								if($_POST[$tem_fv])
								{	$fv_p = "`fecha_vencimiento`, "; $fv_r = "'".$_POST[$tem_fv]."', ";	}
								else
								{	$fv_p = ""; $fv_r = "";	}
								
								if($_POST[$tem_fr])
								{	$fr_p = "`fecha_expedicion`, "; $fr_r = "'".$_POST[$tem_fr]."', ";	}
								else
								{	$fr_p = ""; $fr_r = "";	}
																
							$sql_guardar = "INSERT INTO `hv_documento_persona` 
								( ".$fv_p." ".$fr_p." `archivo`, `estado`, `fecha_registro`, `fase`, `id_documento`, `id_usuario`, `id_persona`) 
								VALUES (".$fv_r." ".$fr_r." '".$ruta."', '1', '".date("Y-m-d G:i:s")."', '1', '".$row["id"]."', '".$_SESSION["user_id"]."', '".$_GET["idvehiculo"]."' );";				
								mysql_query($sql_guardar);	
								if(mysql_error())
								{
										$mensaje = $mensaje.'Error al guardar en base de datos -'.$row["descripcion"].'-, vuelve a intentarlo \n';
								}
						}
						
					}
				
				}
		if($mensaje)
		{
			echo '<script> alert("Debe corregir los siguientes casos: \n'.$mensaje.' ");</script>';
		}else
		{
			echo '<script> alert("Se actualizo los datos de la persona correctamente ");</script>';
		}

}

$sql = "SELECT *, cargo.nombre AS nom_cargo,  municipio.nombre AS nom_municipio 
 FROM hv_persona 
 INNER JOIN municipio ON hv_persona.id_municipio = municipio.id
INNER JOIN cargo ON hv_persona.id_cargo = cargo.id
 WHERE hv_persona.id = '".$_GET["idvehiculo"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
?>


<h2>ACTUALIZAR DOCUMENTOS</h2>
<div align=right>
	<a href="?cmp=lista_auditada"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de hojas de vida </a>
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


<font color=red><b>NOTA:</b></font> Para que se actualicen las fechas usted debe ingresar el archivo de cada documento 
<br><br>
<table width='95%' align='center' border=0><tr><td width='30%' valign=top>

<?php
$cont_td = 1;
$sql = "SELECT * FROM documento WHERE tipo = 3 AND id_instancia='".$_SESSION["nst"]."' ";
$res = mysql_query($sql);
while($row = mysql_fetch_array($res))
{	
	$sql_cond = "SELECT * FROM hv_documento_persona WHERE id_persona = '".$_GET["idvehiculo"]."' AND id_documento = '".$row["id"]."' AND estado=1 LIMIT 1 ";
	$res_cond = mysql_query($sql_cond);
	$row_cond = mysql_fetch_array($res_cond);
		
		
						if($cont_td>3)
						{
							$cont_td = 1;
					?>
								</td>
							</tr>
							<tr>
								<td  width='48%' valign=top> 
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
												<?php echo $row["descripcion"]; ?> (Editar<input type="checkbox" name="check_<?php echo $row["id"]; ?>" id="check_<?php echo $row["id"]; ?>" value="1" onchange="mostrar_<?php echo $row["id"]; ?>()" />) 
											</div>
											<div class="panel-body" align=center>
											<table  width='100%' align='center' border=0>
												<tr>
													<td valign=top align=right width="40%">
														<?php echo tipo_archivo($row_cond["archivo"]); ?>				    
													</td>
													<td width="5%">	</td>
													<td valign=top  width="55%">
														<div id="div_noedi_<?php echo $row["id"]; ?>">
															<?php if($row["fecha_vencimiento"]=='s'){ ?>
																<font color='<?php echo $color_venc; ?>'>
																	Fec. Venc: 
																	<b><?php echo $row_cond["fecha_vencimiento"]?></b> <br><br>
																</font>
															<?php } ?>
															
															<?php if($row["fecha_revision"]=='s'){ ?>
																<font color='<?php echo $color_revi; ?>'>
																	Fec. Expe: 
																	<b><?php echo $row_cond["fecha_expedicion"]; ?></b>	
																</font>
															<?php } ?>
														</div>
													
													
														<div id="div_edi_<?php echo $row["id"]; ?>"  style="display: none;">
															<?php if($row["fecha_vencimiento"]=='s'){ ?>
																	Fec. Venc. <br><input type="text" size='9' readonly  id="fv_<?php echo $row["id"]; ?>" name="fv_<?php echo $row["id"]; ?>"  value="<?php echo $row_cond["fecha_vencimiento"]?>" required />
																	<script type="text/javascript">
																		// <![CDATA[       
																		var opts = {                            
																		formElements:{"fv_<?php echo $row["id"]; ?>":"Y-ds-m-ds-d"},
																		showWeeks:true,
																		// Show a status bar and use the format "l-cc-sp-d-sp-F-sp-Y" (e.g. Friday, 25 September 2009)
																		statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
																		};      
																		datePickerController.createDatePicker(opts);
																		// ]]>
																	</script>
																<br><br>
															<?php } ?>
															
															<?php if($row["fecha_revision"]=='s'){ ?>
																	Fec. Expe. <br><input type="text" size='9'   readonly id="fr_<?php echo $row["id"]; ?>" name="fr_<?php echo $row["id"]; ?>"  value="<?php echo $row_cond["fecha_expedicion"]; ?>" required  />
																	<script type="text/javascript">
																// <![CDATA[       
																var opts = {                            
																formElements:{"fr_<?php echo $row["id"]; ?>":"Y-ds-m-ds-d"},
																showWeeks:true,
																// Show a status bar and use the format "l-cc-sp-d-sp-F-sp-Y" (e.g. Friday, 25 September 2009)
																statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
																};      
																datePickerController.createDatePicker(opts);
																// ]]>
																</script>
																	<br>
															<?php } ?>
														</div>
														
														<div id="div_noedi_img_<?php echo $row["id"]; ?>">
															<?php
															$estado_doc = '<b><font color=red>Sin Informacion</font></b>';
															if($row_cond["fase"]==1 ){$estado_doc = '<b><font color=#DF7401>Sin revision</font></b>';}
															if($row_cond["fase"]==2 ){$estado_doc = '<b><font color=green>Aceptada</font></b>';}
															if($row_cond["fase"]==3 ){$estado_doc = '<b><font color=red>Rechazada</font></b>';}
															?><br>
															Estado: <?php echo $estado_doc; ?>
														</div>
														
														
														
														
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
							<td width='4%' valign=top> 
							<br>
							</td>
							
							<td  width='48%' valign=top>	

					<?php
						$cont_td ++;
		
}
?>
</td></tr></table>

	<center>
			<input type="submit" value="Guardar cambios"  name="guardar" class="btn btn-primary" />
	</center>
	
	
</form>