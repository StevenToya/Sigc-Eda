<?php
if($PERMISOS_GC["cap_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


if($_POST["guardar"])
{		
	$sql_10 = "SELECT id_programa, documento, id FROM capacitacion	WHERE id = '".$_GET["id"]."' LIMIT 1 ";
	$res_10 = mysql_query($sql_10);
	$row_10 = mysql_fetch_array($res_10);
	
	$hi = $_POST["hora_inicial"];
	$hf = $_POST["hora_final"];
	$hi = str_replace(':', '.', $hi); $hi = str_replace('30', '5', $hi);
	$hf = str_replace(':', '.', $hf); $hf = str_replace('30', '5', $hf);	
	$hi = $hi + 0; $hf = $hf + 0;	
	$total_hora =  $hf - $hi;	
	if($hi<$hf)
	{
		$sql ="UPDATE `capacitacion` SET 
		`id_usuario` = '".$_SESSION["user_id"]."', 
		`fecha` = '".$_POST["fecha"]."', 
		`total_hora` = '".$total_hora."', 
		`hora_inicial` = '".$_POST["hora_inicial"]."', 
		`hora_final` = '".$_POST["hora_final"]."', 
		`fecha_registro` = '".date("Y-m-d G:i:s")."', 
		`lugar` = '".limpiar($_POST["lugar"])."', 
		`observacion` = '".limpiar($_POST["observacion"])."'
		WHERE `id` = '".$_GET["id"]."';";
		mysql_query($sql);		
		if(!mysql_error())
		{
			
			$sql_eli = "DELETE FROM `capacitacion_persona` WHERE `id_capacitacion` = '".$_GET["id"]."' ";
			mysql_query($sql_eli);	
			
			$sql="SELECT hv_persona.id	FROM  hv_persona
					WHERE 
						id_cargo IN (SELECT id_cargo FROM programa_cargo WHERE id_programa = '".$row_10["id_programa"]."' GROUP BY id_cargo)
						AND hv_persona.estado = '5'
					ORDER BY nombre, apellido ;";
			 $res = mysql_query($sql);
			 while($row = mysql_fetch_array($res))
			 {
				$var_tem = "asi_".$row["id"];			
				if($_POST[$var_tem])
				{				
					 $sql_asi="INSERT INTO capacitacion_persona (id_capacitacion, id_persona) VALUES ('".$row_10["id"]."' , '".$row["id"]."'); ";
					  mysql_query($sql_asi);
				}
			 }
			 
			 
			if($_FILES['documento']['name'])
			{				
				unlink($row_10["documento"]);
				
				$carpeta = "documentos/capacitacion";				
				$trozos = explode(".", $_FILES['documento']['name']); 
				$extension = end($trozos);					
				$ruta = $carpeta."/".$row_10["id"].".".$extension;			
				move_uploaded_file($_FILES['documento']['tmp_name'], $ruta);
				if(file_exists($ruta))
				{
					 $sql = "UPDATE capacitacion SET documento = '".$ruta."'  WHERE id ='".$row_10["id"]."' LIMIT 1 ;";
					 mysql_query($sql);	
					 echo '<script >alert("La capacitacion se actualizo correctamente!");</script>';
					 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=mis_capacitaciones'>";
					 die();
					 
				}
				else
				{
					 echo '<script >alert("La capacitacion se actualizo correctamente, pero sin cargar el archivo");</script>';
					echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=mis_capacitaciones'>";
					die();
				}
								
			}
			else
			{
				 echo '<script >alert("La capacitacion se actualizo correctamente!");</script>';
				echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=mis_capacitaciones'>";
				die();
			} 
			 
			 
			 
			
		}
		else
		{
			$error = "ERROR: No se pudo actualizar la capacitacion en la base de datos";
		}
	}
	else
	{
		$error = "ERROR:La hora inicial debe ser menor que la hora final";
	}
			
}

$sql = "SELECT programa.nombre, capacitacion.fecha, capacitacion.hora_inicial, capacitacion.hora_final, capacitacion.lugar, capacitacion.id, capacitacion.documento, 
			capacitacion.total_hora, capacitacion.observacion, programa.id AS id_prog
		FROM capacitacion
		INNER JOIN programa ON capacitacion.id_programa = programa.id							
		WHERE capacitacion.id = '".$_GET["id"]."' LIMIT 1 ";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);

?> 
<h2> EDITAR CAPACITACION	(<?php echo $row["nombre"] ?>) </h2>  
	
		<div align=right>
			<a href="?cmp=mis_capacitaciones"> <i class="fa fa-reply fa-2x"></i> Volver al listado de programas </a>
		</div>
		
		<form  method="post" action="?id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data"> 
		
				<br>
				<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" border=0 width="90%">

					<tr>
						<td colspan=2>
							<div class=" form-group input-group input-group-lg" style="width:100%">
								   <span class="input-group-addon">Lugar  </span>
								  <input  name="lugar" value="<?php echo $row["lugar"] ?>" id="lugar" type="text" class="form-control" placeholder="Lugar de la capacitacion" required />
							</div> 
						</td>
					</tr>
					<tr>
						<td width=50%  valign=top>	
							<div class=" form-group input-group input-group-lg" style="width:100%">
								   <span class="input-group-addon">Fecha</span>
								  <input  name="fecha" value="<?php echo $row["fecha"] ?>" id="fc" type="date" class="form-control"  required />
								  <script type="text/javascript">
									var opts = {                            
									formElements:{"fc":"Y-ds-m-ds-d"},
									showWeeks:true,
									statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
									};      
									datePickerController.createDatePicker(opts);					
								</script>
							</div> 
						</td>
						<td  width=50% valign=top>
							<div class=" form-group input-group input-group-lg" style="width:100%">
								   <span class="input-group-addon"><a href="<?php echo $row["documento"] ?>" target="blank"> Documento </a>   </span>
								  <input  name="documento" id="documento" type="file" class="form-control" placeholder="Acta de asistencia" />
							</div> 
						</td>
					</tr>
					<tr>
						<td>	
							<div class=" form-group input-group input-group-lg" style="width:100%">
								   <span class="input-group-addon">Hora Inicial</span>
								   <?php 
										$tem_ini = explode(':',$row["hora_inicial"]);
										$tem_comp = $tem_ini[0].':'.$tem_ini[1];										
								   ?>								   
									<select  name="hora_inicial"  class="form-control" required >
										<option value="">Seleccione La hora inicial</option>
										<?php
										$hh = '1';
										while($hh <= 23){	
											if($hh<10){$hh = '0'.$hh;}
											$tem_comp_1 = $hh.':00';
											$tem_comp_2 = $hh.':30';
										?>
											<option <?php if($tem_comp==$tem_comp_1){ ?> selected="selected" <?php } ?> ><?php echo $tem_comp_1 ?></option>
											<option <?php if($tem_comp==$tem_comp_2){ ?> selected="selected" <?php } ?> ><?php echo $hh ?>:30</option>
										<?php
											$hh++;
										}
										?>
																
									</select>
							</div> 
						</td>
						<td>	
							<div class=" form-group input-group input-group-lg" style="width:100%">
								   <span class="input-group-addon">Hora Final</span>
								   <?php
										$tem_fin = explode(':',$row["hora_final"]);
										$tem_comp = $tem_fin[0].':'.$tem_fin[1];								   
								   ?>
									<select  name="hora_final"  class="form-control" required >
										<option value="">Seleccione La hora inicial</option>
										<?php
										$hh = '1';
										while($hh <= 23)
										{											
											if($hh<10){$hh = '0'.$hh;}
											$tem_comp_1 = $hh.':00';
											$tem_comp_2 = $hh.':30';
										?>
											<option <?php if($tem_comp==$tem_comp_1){ ?> selected <?php } ?> ><?php echo $hh ?>:00</option>
											<option <?php if($tem_comp==$tem_comp_2){ ?> selected <?php } ?> ><?php echo $hh ?>:30</option>
										<?php
											$hh++;
										}
										?>
																
									</select>
							</div> 
						</td>
					</tr>
					
					<tr>
						<td colspan=2>
							<div class=" form-group input-group input-group-lg" style="width:100%">
								   <span class="input-group-addon">Observacion de la <br>capacitacion</span>
								  <textarea  name="observacion" id="observacion" style="height:100px" class="form-control" placeholder="" required ><?php echo $row["observacion"] ?></textarea>
							</div> 
						</td>
					</tr>

					<tr>
						<td colspan=2 align=center><span class="input-group-addon">Seleccione los asistentes</span></td>
					</tr>
					<tr>
						<?php
							$vector_persona[] = 'NO_VACIO';
							$sql_cap_per = "SELECT id_persona FROM capacitacion_persona WHERE id_capacitacion = '".$_GET["id"]."'; ";
							$res_cap_per = mysql_query($sql_cap_per);
							while($row_cap_per = mysql_fetch_array($res_cap_per))
							{	$vector_persona[] = $row_cap_per["id_persona"];	}
						
						
							$cont = 1;
						  $sql_per="SELECT hv_persona.id, hv_persona.nombre, hv_persona.apellido, hv_persona.identificacion 
								FROM  hv_persona
								WHERE 
									id_cargo IN (SELECT id_cargo FROM programa_cargo WHERE id_programa = '".$row["id_prog"]."' GROUP BY id_cargo)
									AND hv_persona.estado = '5'
								ORDER BY nombre, apellido ; ";
						  $res_per = mysql_query($sql_per);
						  while($row_per = mysql_fetch_array($res_per))
						  {
							
								if($cont> 2)
								{
									echo "</tr><tr>";
									$cont = 1;			
								}
								
												
						?>
						   <td ><br>
														  
								<input type="checkbox" <?php if(in_array($row_per["id"], $vector_persona)){ ?> checked <?php } ?>  name="asi_<?php echo $row_per["id"]; ?>" />
								<font size="3"><?php echo $row_per["apellido"]; ?>  <?php echo $row_per["nombre"]; ?> - <?php echo $row_per["identificacion"]; ?> </font>								
						   </td>			
						
						<?php 
						   $cont ++;
						  }
						  
						   ?>
					</tr>

				</table>

				<center>
				<?php if($error){?>
									<div class="alert alert-info">
										<?php echo $error; ?>
									</div>
								<?php } ?>
				<br><input class="btn btn-primary" type="submit" value="Actualizar capacitacion" name="guardar" /></center>
		</form>

