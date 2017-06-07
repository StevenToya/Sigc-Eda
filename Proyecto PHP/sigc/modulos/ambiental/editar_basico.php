<?php
if($PERMISOS_GC["amb_act"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_POST["guardar"])
{
	$sql = "SELECT id FROM empresa WHERE id_instancia = '".$_SESSION["nst"]."' LIMIT 1";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	$id_empresa = $row["id"];
	
	$sql ="UPDATE `empresa` SET 
	`nombre` = '".$_POST["nombre"]."', 
	`contrato` = '".$_POST["contrato"]."', 
	`telefono` = '".$_POST["telefono"]."', 
	`correo` = '".$_POST["correo"]."', 
	`direccion` = '".$_POST["direccion"]."', 
	`email` = '".$_POST["email"]."', 
	`representante_nombre` = '".$_POST["representante_nombre"]."', 
	`representante_cargo` = '".$_POST["representante_cargo"]."', 
	`coordinador_1_amb` = '".$_POST["coordinador_1_amb"]."', 
	`coordinador_1_amb_licencia` = '".$_POST["coordinador_1_amb_licencia"]."', 
	`coordinador_2_amb` = '".$_POST["coordinador_2_amb"]."', 
	`coordinador_2_amb_licencia` = '".$_POST["coordinador_2_amb_licencia"]."', 
	`coordinador_3_amb` = '".$_POST["coordinador_3_amb"]."', 
	`coordinador_3_amb_licencia` = '".$_POST["coordinador_3_amb_licencia"]."', 
	`trabajadores_cantidad` = '".$_POST["trabajadores_cantidad"]."', 
	`programa_salud_amb` = '".$_POST["programa_salud_amb"]."', 
	`sistema_gestion_amb` = '".$_POST["sistema_gestion_amb"]."', 
	`estado_ambiental` = '1', 
	`fecha_registro_ambiental` = '".date("Y-m-d G:i:s")."'
	WHERE `id` = '".$id_empresa."' LIMIT 1;";	
	mysql_query($sql);
	
	if(!mysql_error())
	{		
		if($_FILES["coordinador_1_amb_archivo"]['name'])
		{
			$structure = "documentos/ambiental";
			$trozos = explode(".", $_FILES["coordinador_1_amb_archivo"]['name']); 
			$extension = end($trozos);
			$ruta = $structure."/s_o_1_".$id_empresa.".".$extension;
			move_uploaded_file($_FILES["coordinador_1_amb_archivo"]['tmp_name'], $ruta);
			if(file_exists($ruta))
			{
				$sql_arc = "UPDATE `empresa` SET `coordinador_1_amb_archivo` = '".$ruta."' 	WHERE  id = '".$id_empresa."' ;";
				mysql_query($sql_arc);			
			}			
		}		
				
		
		if($_FILES["coordinador_2_amb_archivo"]['name'])
		{
			$structure = "documentos/ambiental";
			$trozos = explode(".", $_FILES["coordinador_2_amb_archivo"]['name']); 
			$extension = end($trozos);
			$ruta = $structure."/s_o_2_".$id_empresa.".".$extension;
			move_uploaded_file($_FILES["coordinador_2_amb_archivo"]['tmp_name'], $ruta);
			if(file_exists($ruta))
			{
				$sql_arc = "UPDATE `empresa` SET `coordinador_2_amb_archivo` = '".$ruta."' 	WHERE  id = '".$id_empresa."' ;";
				mysql_query($sql_arc);			
			}			
		}
		
		
		if($_FILES["coordinador_3_amb_archivo"]['name'])
		{
			$structure = "documentos/ambiental";
			$trozos = explode(".", $_FILES["coordinador_3_amb_archivo"]['name']); 
			$extension = end($trozos);
			$ruta = $structure."/s_o_3_".$id_empresa.".".$extension;
			move_uploaded_file($_FILES["coordinador_3_amb_archivo"]['tmp_name'], $ruta);
			if(file_exists($ruta))
			{
				$sql_arc = "UPDATE `empresa` SET `coordinador_3_amb_archivo` = '".$ruta."' 	WHERE  id = '".$id_empresa."' ;";
				mysql_query($sql_arc);			
			}			
		}
		
		echo '<script>alert("Se guardo la actualizacion correctamente.")</script>';
		echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_amb_actualizar'>";die();
		
		
	}
	else
	{
		echo '<script>alert("ERROR al guardar en base de datos")</script>';
	}	
	
}




$sql = "SELECT * FROM empresa WHERE id_instancia = '".$_SESSION["nst"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
?>

<h2>DATOS BASICOS DE LA EMPRESA</h2>
<div align=right> 						
		<a href='?cmp=lista_amb_actualizar&tip=3'><i class="fa fa-reply fa-2x"></i> Volver lista de documentos entregados</a> &nbsp;&nbsp;&nbsp;&nbsp;	
</div>

 <form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
 
 
 
	<div class="col-md-100 col-sm-100">
		<div class="panel panel-primary">
			<div class="panel-heading"> 
				<center><b>INFORMACI&Oacute;N GENERAL DE LA EMPRESA CONTRATISTA</b> </center>
			</div>
			<div class="panel-body" align=center>
				<table width='100%'>
					<tr>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Actualizada</span>							 
							  <input  name="fecha_registro_ambiental" readonly  id="fecha_registro_ambiental" value="<?php echo $row["fecha_registro_ambiental"] ?>"  type="text"  class="form-control" placeholder="" required />
							</div> 
						</td>
						<td width=4%></td>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Contrato Nº</span>							 
							  <input  name="contrato"  id="contrato" value="<?php echo $row["contrato"] ?>"  type="text"  class="form-control" placeholder="" required />
							</div> 
						</td>
					</tr>
					
					<tr>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Empresa</span>							 
							  <input  name="nombre"  id="nombre" value="<?php echo $row["nombre"] ?>"  type="text"  class="form-control" placeholder="" required />
							</div> 
						</td>
						<td width=4%></td>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Direccion</span>							 
							  <input  name="direccion"  id="direccion" value="<?php echo $row["direccion"] ?>"  type="text"  class="form-control" placeholder="" required />
							</div> 
						</td>
					</tr>
					
					<tr>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Telefono</span>							 
							  <input  name="telefono"  id="telefono" value="<?php echo $row["telefono"] ?>"  type="text"  class="form-control" placeholder="" required />
							</div> 
						</td>
						<td width=4%></td>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">E-mail</span>							 
							  <input  name="email"  id="email" value="<?php echo $row["email"] ?>"  type="text" class="form-control" placeholder="" required />
							</div> 
						</td>
					</tr>
				</table>
			</div>
											
		</div>
	</div>		
 
 
	<div class="col-md-100 col-sm-100">
		<div class="panel panel-primary">
			<div class="panel-heading"> 
				<center><b>REPRESENTANTE LEGAL</b> </center>
			</div>
			<div class="panel-body" align=center>
				<table width='100%'>
					<tr>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Nombre</span>							 
							  <input  name="representante_nombre"  id="representante_nombre" value="<?php echo $row["representante_nombre"] ?>"  type="text"  class="form-control" placeholder="" required />
							</div> 
						</td>
						<td width=4%></td>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Cargo</span>							 
							  <input  name="representante_cargo"  id="representante_cargo" value="<?php echo $row["representante_cargo"] ?>"  type="text"  class="form-control" placeholder="" required />
							</div> 
						</td>
					</tr>
				</table>
			</div>
											
		</div>
	</div>	



	<div class="col-md-100 col-sm-100">
		<div class="panel panel-primary">
			<div class="panel-heading"> 
				<center><b>COORDINADOR DE SOCIO-AMBIENTAL EN EL TRABAJO PARA EL OBJETO DEL CONTRATO<br>
				(Adjuntar Hoja de vida y licencia)</b> </center>
			</div>
			<div class="panel-body" align=center>
				<table width='100%'>
					<tr>
						<td colspan=3>
							<div class=" form-group input-group input-group-lg" style="width:100%" >
							  <span class="input-group-addon" ><div align=left>1) Nombre y apellido</div></span>							 
							  <input  name="coordinador_1_amb"  id="coordinador_1_amb" value="<?php echo $row["coordinador_1_amb"] ?>"  type="text"  class="form-control" placeholder=""  />
							</div> 
						</td>						
					</tr>
					<tr>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon"><div align=left>Licencia</div></span>							 
							  <input  name="coordinador_1_amb_licencia"  id="coordinador_1_amb_licencia" value="<?php echo $row["coordinador_1_amb_licencia"] ?>"  type="text"  class="form-control" placeholder=""  />
							</div> 
						</td>
						<td width=4%></td>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">
								<?php if($row["coordinador_1_amb_archivo"]){ ?> 
									<a href="<?php echo $row["coordinador_1_amb_archivo"] ?>" target="blank"> Hoja de vida </a>
								<?php }else{ ?>
									Hoja de vida
								<?php } ?>
							 </span>							 
							  <input  name="coordinador_1_amb_archivo"  id="coordinador_1_amb_archivo"   type="file"  class="form-control" placeholder=""  />
							</div> 
						</td>
					</tr>
				</table>
							
				<table width='100%'>
					<tr>
						<td colspan=3>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon"><div align=left>2) Nombre y apellido</div></span>							 
							  <input  name="coordinador_2_amb"  id="coordinador_2_amb" value="<?php echo $row["coordinador_2_amb"] ?>"  type="text"  class="form-control" placeholder=""  />
							</div> 
						</td>						
					</tr>
					<tr>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon"><div align=left>Licencia</div></span>							 
							  <input  name="coordinador_2_amb_licencia"  id="coordinador_2_amb_licencia" value="<?php echo $row["coordinador_2_amb_licencia"] ?>"  type="text"  class="form-control" placeholder=""  />
							</div> 
						</td>
						<td width=4%></td>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">
								<?php if($row["coordinador_2_amb_archivo"]){ ?> 
									<a href="<?php echo $row["coordinador_2_amb_archivo"] ?>" target="blank"> Hoja de vida </a>
								<?php }else{ ?>
									Hoja de vida
								<?php } ?>
							  </span>							 
							  <input  name="coordinador_2_amb_archivo"  id="coordinador_2_amb_archivo"   type="file"  class="form-control" placeholder="" />
							</div> 
						</td>
					</tr>
				</table>
						
				<table width='100%'>
					<tr>
						<td colspan=3>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon"><div align=left>3) Nombre y apellido</div></span>							 
							  <input  name="coordinador_3_amb"  id="coordinador_3_amb" value="<?php echo $row["coordinador_3_amb"] ?>"  type="text"  class="form-control" placeholder="" />
							</div> 
						</td>						
					</tr>
					<tr>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon"><div align=left>Licencia</div></span>							 
							  <input  name="coordinador_3_amb_licencia"  id="coordinador_3_amb_licencia" value="<?php echo $row["coordinador_3_amb_licencia"] ?>"  type="text"  class="form-control" placeholder=""  />
							</div> 
						</td>
						<td width=4%></td>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">
								<?php if($row["coordinador_3_amb_archivo"]){ ?> 
									<a href="<?php echo $row["coordinador_3_amb_archivo"] ?>" target="blank"> Hoja de vida </a>
								<?php }else{ ?>
									Hoja de vida
								<?php } ?>							  
							  </span>							 
							  <input  name="coordinador_3_amb_archivo"  id="coordinador_3_amb_archivo"   type="file"  class="form-control" placeholder="" />
							</div> 
						</td>
					</tr>
				</table>
				
				<table width='100%'>
					<tr>
						<td width='52%'>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">N&uacute;mero estimado de trabajadores a laboral en el contrato</span>							 
							  <input  name="trabajadores_cantidad"  id="trabajadores_cantidad" value="<?php echo $row["trabajadores_cantidad"] ?>"  type="number"  class="form-control" placeholder="" required />
							</div> 
						</td>	
						<td width='4%'></td>
						<td width='44%'>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Tiene un Programa  Socio Ambiental?</span>
								<select name="programa_salud_amb" class="form-control">
									<option <?php if($row["programa_salud_amb"]=='n'){ ?> selected <?php } ?> value='n'>No</option>
									<option <?php if($row["programa_salud_amb"]=='s'){ ?> selected <?php } ?> value='s'>Si</option>
							 	</select>
							</div> 
						</td>	
					</tr>
					
					<tr>
						<td >
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Tiene un Sistema de Gestion Socio Ambiental?</span>							 
								<select name="sistema_gestion_amb" class="form-control">
									<option <?php if($row["sistema_gestion_amb"]=='n'){ ?> selected <?php } ?> value='n'>No</option>
									<option <?php if($row["sistema_gestion_amb"]=='s'){ ?> selected <?php } ?> value='s'>Si</option>
							 	</select>
							</div> 
						</td>
					</tr>
				</table>
			</div>
											
		</div>
	</div>	

		<center><br><input type="submit" value="Guardar cambios"  name="guardar" class="btn btn-primary" /></center>	
</form>
