<?php
if($PERMISOS_GC["cap_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


if($_POST["guardar"])
{		
	$hi = $_POST["hora_inicial"];
	$hf = $_POST["hora_final"];
	$hi = str_replace(':', '.', $hi); $hi = str_replace('30', '5', $hi);
	$hf = str_replace(':', '.', $hf); $hf = str_replace('30', '5', $hf);	
	$hi = $hi + 0; $hf = $hf + 0;	
	$total_hora =  $hf - $hi;	
	if($hi<$hf)
	{
		$sql = "INSERT INTO `capacitacion` (`id_usuario` ,`id_programa` ,`fecha`, `hora_inicial`, `hora_final`, `total_hora`, `fecha_registro`, `lugar`, `observacion`)
				VALUES ('".$_SESSION["user_id"]."', '".$_GET["id_prog"]."', '".$_POST["fecha"]."' ,'".$_POST["hora_inicial"]."','".$_POST["hora_final"]."',
				'".$total_hora."', '".date("Y-m-d G:i:s")."', '".limpiar($_POST["lugar"])."', '".limpiar($_POST["observacion"])."');";
		mysql_query($sql);
		$id_cap = mysql_insert_id();
		if($id_cap)
		{
			 $sql="SELECT hv_persona.id	FROM  hv_persona
					WHERE 
						id_cargo IN (SELECT id_cargo FROM programa_cargo WHERE id_programa = '".$_GET["id_prog"]."' GROUP BY id_cargo)
						AND hv_persona.estado = '5'
					ORDER BY nombre, apellido ;";
			 $res = mysql_query($sql);
			 while($row = mysql_fetch_array($res))
			 {
				$var_tem = "asi_".$row["id"];			
				if($_POST[$var_tem])
				{				
					 $sql_asi="INSERT INTO capacitacion_persona (id_capacitacion, id_persona) VALUES ('".$id_cap."' , '".$row["id"]."'); ";
					  mysql_query($sql_asi);
				}
			 }
			 
			 	 
			 
			 
			if($_FILES['documento']['name'])
			{				
				$carpeta = "documentos/capacitacion";
				@mkdir($carpeta, 0777);
				chmod($carpeta, 0777);
				
				$trozos = explode(".", $_FILES['documento']['name']); 
				$extension = end($trozos);					
				$ruta = $carpeta."/".$id_cap.".".$extension;			
				move_uploaded_file($_FILES['documento']['tmp_name'], $ruta);
				if(file_exists($ruta))
				{
					 $sql = "UPDATE capacitacion SET documento = '".$ruta."'  WHERE id ='".$id_cap."' LIMIT 1 ;";
					 mysql_query($sql);	
					 echo '<script >alert("La capacitacion se guardo correctamente!");</script>';
					 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=mis_capacitaciones'>";
					 die();
					 
				}
				else
				{
					 echo '<script >alert("La capacitacion se guardo correctamente, pero sin cargar el archivo");</script>';
					echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=mis_capacitaciones'>";
					die();
				}
								
			}
			else
			{
				 echo '<script >alert("La capacitacion se guardo correctamente, pero sin cargar el archivo!");</script>';
				echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=mis_capacitaciones'>";
				die();
			} 
			 
			 
			 
			
		}
		else
		{
			$error = "ERROR: No se pudo ingresar la capacitacion en la base de datos";
		}
	}
	else
	{
		$error = "ERROR:La hora inicial debe ser menor que la hora final";
	}
			
}


?> 
<h2>
	INGRESAR CAPACITACION
	<?php
	if($_GET["id_prog"])
	{	
		$sql_pr = "SELECT nombre FROM programa WHERE id = '".$_GET["id_prog"]."' LIMIT 1 ";
		$res_pr = mysql_query($sql_pr);
		$row_pr = mysql_fetch_array($res_pr);
	?>
			(<?php echo $row_pr["nombre"] ?>)
	<?php } ?>
	
	

</h2>  


<?php
if($_GET["id_prog"]){
?>
		
		<div align=right>
			<a href="?"> <i class="fa fa-reply fa-2x"></i> Volver al listado de programas </a>
		</div>
		
		<form  method="post" action="?id_prog=<?php echo $_GET['id_prog']; ?>" enctype="multipart/form-data"> 
				<br>
				<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" border=0 width="90%">

					<tr>
						<td colspan=2>
							<div class=" form-group input-group input-group-lg" style="width:100%">
								   <span class="input-group-addon">Lugar  </span>
								  <input  name="lugar" value="<?php echo $_POST["lugar"] ?>" id="lugar" type="text" class="form-control" placeholder="Lugar de la capacitacion" required />
							</div> 
						</td>
					</tr>
					<tr>
						<td width=50%  valign=top>	
							<div class=" form-group input-group input-group-lg" style="width:100%">
								   <span class="input-group-addon">Fecha</span>
								  <input  name="fecha" value="<?php echo $_POST["fecha"] ?>" id="fc" type="date" class="form-control"  required />
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
								   <span class="input-group-addon">Documento  </span>
								  <input  name="documento" id="documento" type="file" class="form-control" placeholder="Acta de asistencia" required />
							</div> 
						</td>
					</tr>
					<tr>
						<td>	
							<div class=" form-group input-group input-group-lg" style="width:100%">
								   <span class="input-group-addon">Hora Inicial</span>
									<select  name="hora_inicial"  class="form-control" required >
										<option value="">Seleccione La hora inicial</option>
										<?php
										$hh = '1';
										while($hh <= 23){
										?>
											<option><?php echo $hh ?>:00</option><option><?php echo $hh ?>:30</option>
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
									<select  name="hora_final"  class="form-control" required >
										<option value="">Seleccione La hora inicial</option>
										<?php
										$hh = '1';
										while($hh <= 23){
										?>
											<option><?php echo $hh ?>:00</option><option><?php echo $hh ?>:30</option>
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
								  <textarea  name="observacion" id="observacion" style="height:100px" class="form-control" placeholder="" required ><?php echo $_POST["observacion"] ?></textarea>
							</div> 
						</td>
					</tr>

					<tr>
						<td colspan=2 align=center><span class="input-group-addon">Seleccione los asistentes</span></td>
					</tr>
					<tr>
						<?php
							$cont = 1;
						  $sql="SELECT hv_persona.id, hv_persona.nombre, hv_persona.apellido, hv_persona.identificacion 
								FROM  hv_persona
								WHERE 
									id_cargo IN (SELECT id_cargo FROM programa_cargo WHERE id_programa = '".$_GET["id_prog"]."' GROUP BY id_cargo)
									AND hv_persona.estado = '5'
								ORDER BY nombre, apellido ; ";
						  $res = mysql_query($sql);
						  while($row = mysql_fetch_array($res))
						  {
							
								if($cont> 2)
								{
									echo "</tr><tr>";
									$cont = 1;			
								}
								
												
						?>
						   <td ><br>
								<?php $tem_t = "asi_".$row["id"]; ?>							  
								<input type="checkbox" <?php if($_POST[$tem_t]){ ?> checked <?php } ?>  name="asi_<?php echo $row["id"]; ?>" />
								<font size="3"><?php echo $row["apellido"]; ?>  <?php echo $row["nombre"]; ?> - <?php echo $row["identificacion"]; ?> </font>								
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
				<br><input class="btn btn-primary" type="submit" value="Guardar capacitacion" name="guardar" /></center>
		</form>
<?php
}else{
?>		
		<div align=right>
			<a href="?cmp=mis_capacitaciones"> <i class="fa fa-reply fa-2x"></i> Volver al listado mis capacitaciones </a>
		</div>
		<div class="panel panel-default">
                        <div class="panel-heading">
                             Seleccionar el programa que se expuso en la capacitaci&oacute;n
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
							
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Programa</th>
                                            <th>Descripcion</th>
                                            <th> </th>											
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "SELECT programa.nombre, programa.descripcion, programa.hora, programa.id
											FROM programa 
											WHERE id_instancia='".$_SESSION["nst"]."' ORDER BY programa.nombre ;";
									$res = mysql_query($sql);
									while($row=mysql_fetch_array($res))
									{									
									?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $row["nombre"] ?></td>
                                            <td><?php echo $row["descripcion"] ?></td>
                                           	<td align=center> <a href="?id_prog=<?php echo $row["id"]; ?>">Tomar</a></td> 											
									   </tr>
									<?php
									}
									?>
                                       
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>

<?php
}
?>
