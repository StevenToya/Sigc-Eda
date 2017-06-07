<?php
if($PERMISOS_GC["hv_cre"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_GET["ingresar"])
{	
	$error = ''; $ll = '1';
	$destino = "bak_".date("Y_m_d_G_i_s");	
	if (copy($_FILES['excel']['tmp_name'],$destino))
	{
		$trozos = explode(".", $_FILES['excel']['name']); 
		$extension = end($trozos);
		if($extension=='CSV' || $extension=='csv')
		{
			$fp = fopen ($_FILES['excel']['tmp_name'],"r");
			while ($data = fgetcsv ($fp, 1000, ";")) 
			{
				if($data[0] && $data[1] && $data[2] && $data[3] && $data[4] && $data[5] && $data[6] && $data[7])
				{
					$sql_car = "SELECT id FROM cargo WHERE id = '".trim($data[7])."' AND estado='1' AND id_instancia='".$_SESSION["nst"]."' LIMIT 1";
					$res_car = mysql_query($sql_car);
					$row_car = mysql_fetch_array($res_car);
					
					if($row_car["id"])
					{
						$cedula = intval(preg_replace('/[^0-9]+/', '', trim($data[2])), 10);
						$sql_ced = "SELECT id FROM hv_persona WHERE identificacion = '".$cedula."' AND  id_instancia='".$_SESSION["nst"]."' LIMIT 1";
						$res_ced = mysql_query($sql_ced);
						$row_ced = mysql_fetch_array($res_ced);
						if(!$row_ced["id"])
						{
							$sql = "INSERT INTO `hv_persona` (`id_cargo` ,`telefono` ,`nombre` ,`apellido` ,`identificacion` ,`correo`  ,`estado`,`direccion`,`id_instancia`,`id_municipio`,`id_usuario_creador`)
									VALUES ('".trim($data[7])."', '".limpiar($data[4])."', '".ucwords(strtolower(limpiar($data[1])))."', '".ucwords(strtolower(limpiar($data[0])))."', '".$cedula."', 
									'".limpiar($data[6])."', '5', '".limpiar($data[3])."' ,'".$_SESSION["nst"]."', '".$_POST["municipio"]."','".$_SESSION["user_id"]."');";
							mysql_query($sql);
							if(mysql_error())
							{
								$error = $error .'Linea '.$ll.': ERROR al ingresar en base de datos <br>';
							}
							
						}
						else
						{
							$error = $error .'Linea '.$ll.': La cedula ya esta registrada <br>';					
						}
						
					}
					else
					{
						$error = $error .'Linea '.$ll.': La identicacion del cargo es incorrecta <br>';					
					}
				}
				else
				{
					$error = $error .'Linea '.$ll.': Faltan datos por llenar';					
				}
				
				$ll ++;
			}
		}
		else
		{
			$error = 'El archivo de ser un CSV (Delimitado por comas) ';
			
		}
		unlink($destino);
	}
	else
	{
		$error = 'No se pudo cargar el archivo';
		
	}		
}

?>

<h2>INGRESAR HOJAS DE VIDA POR <b> CSV Delimitado por comas </b> </h2>

<div align=right>
	<a href="?cmp=lista_habilitar"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de hojas de vida </a>
</div>
<form action="?ingresar=1"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<table align="center">
		<tr>
			 <td valign=top>			
							
					<div class=" form-group input-group input-group-lg" style="width:100%">
					   <span class="input-group-addon">Departamento</span>
						 <select  class="form-control" name="departamento" id="departamento">
							<option value="">Seleccione un departamento</option>
							<?php
							$sql_dep = "SELECT * FROM departamento ORDER BY nombre";
							$res_dep = mysql_query($sql_dep);
							while($row_dep = mysql_fetch_array($res_dep))
							{
							?>
								<option value="<?php echo $row_dep["id"] ?>" ><?php echo utf8_encode($row_dep["nombre"]) ?></option>
							<?php
							}
							?>										
						 </select>
					</div> 
				  
					<div class="form-group input-group input-group-lg" style="width:100%">
					   <span class="input-group-addon">Municipio</span>
						 <select  class="form-control" name="municipio" id="municipio" required>
							<option value="">Seleccione un municipio</option>							
						 </select>
					</div> 
				
				
				<div class="form-group input-group input-group-lg" style="width:100%">
					   <span class="input-group-addon">Cargar archivo</span>				   
						<input name="excel" id="excel" value="<?php echo $_POST["excel"] ?>" type="file" class="form-control"  required />
						
				</div> 
				
			
					<?php if($error){?>
					<div class="alert alert-info">
						<?php echo $error; ?>
					</div>
				<?php } ?>
					
					
				<center>	<input type="submit" class="btn btn-primary" value="Cargar archivo"> </center>
			</td>
		</tr>
				
	</table>
</form><br>

<table align=center width=95%>
	<tr>
		<td width='44%' valign=top>
			 <div class="col-md-100 col-sm-100">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<b>Columnas del archivo CSV</b>
								</div>
								<div class="panel-body">
									<h6>
										<b>Columna A: </b> Apellidos <br><br>
										<b>Columna B: </b> Nombre <br><br>
										<b>Columna C: </b> Cedula <br><br>
										<b>Columna D: </b> Direccion <br><br>
										<b>Columna E: </b> Telefono <br><br>
										<b>Columna F: </b> Celular <br><br>
										<b>Columna G: </b> Correo <br><br>			
										<b>Columna H: </b> Codigo del cargo <br>
									</h6>									
								</div>
							</div>
			</div>
		</td>
		<td width='2%'> </td>
		<td width='44%' valign=top>
			 <div class="col-md-100 col-sm-100">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<b>Codigos de los cargos</b>
								</div>
								<div class="panel-body" align=center>
									<h6>
									<table width='95%' align=center>
										<tr>
											<th>Codigo</th>
											<th>Nombre</th>
										</tr>
										<td>
											<br>
										</td>
										<?php
											$sql = "SELECT cargo.nombre,  cargo.descripcion, cargo.id 	
											FROM cargo 			
											WHERE cargo.estado='1' AND cargo.id_instancia='".$_SESSION["nst"]."'  ORDER BY cargo.nombre";
											$res = mysql_query($sql);
											while($row = mysql_fetch_array($res))
										{						
										?>
											<tr>
												<td><b><?php echo $row["id"] ?></b></td>
												<td><?php echo $row["nombre"] ?></td>
											</tr>
											<tr>
												<td><br></td>
											</tr>
										<?php
										}
										?>
									</table>
								</div>
							</div>
			</div>
		</td>
	</tr>
</table>

			
