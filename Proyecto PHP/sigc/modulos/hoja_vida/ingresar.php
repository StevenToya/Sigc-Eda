<?php
if($PERMISOS_GC["hv_cre"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


if($_GET["ingresar"])
{
	$sql = "SELECT id FROM hv_persona WHERE  identificacion = '".$_POST["identificacion"]."' AND id_instancia = '".$_SESSION["nst"]."'  LIMIT 1";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	if($row["id"])
	{		
		$error = 'La identificacion  "'.$_POST["identificacion"].'"  ya esta registrada';
	}	
	else
	{
				
		$_POST["nombre"] =  strtolower(trim($_POST["nombre"]));
		$_POST["apellido"] =  strtolower(trim($_POST["apellido"]));			
				
		$sql = "INSERT INTO `hv_persona` (`id_cargo` ,`telefono` ,`nombre` ,`apellido` ,`identificacion` ,`correo`  ,`estado`,`direccion`,`id_instancia`,`id_municipio`,`id_usuario_creador`)
				VALUES ('".$_POST["cargo_postular"]."', '".$_POST["telefono"]."', '".ucwords(strtolower(trim($_POST["nombre"])))."', '".ucwords(strtolower(trim($_POST["apellido"])))."', '".$_POST["identificacion"]."', 
				'".$_POST["email"]."', '1', '".trim($_POST["direccion"])."' ,'".$_SESSION["nst"]."', '".$_POST["municipio"]."','".$_SESSION["user_id"]."');";
		mysql_query($sql);
		$id_hv = mysql_insert_id();
		if($id_hv)
		{
			$carpeta = "documentos/hv/".$id_hv;
			@mkdir($carpeta, 0777);
			chmod($carpeta, 0777);
						
			if($_FILES['foto']['name'])
			{				
				$trozos = explode(".", $_FILES['foto']['name']); 
				$extension = end($trozos);	
				if($extension=='JPG' || $extension=='jpg' || $extension=='JPEG' || $extension=='jpeg' || $extension=='GIF' || $extension=='gif' || $extension=='PNG' || $extension=='png')
				{
					$ruta = $carpeta."/foto.".$extension;			
					move_uploaded_file($_FILES['foto']['tmp_name'], $ruta);
					if(file_exists($ruta))
					{
						 $sql = "UPDATE hv_persona SET foto = '".$ruta."'  WHERE id ='".$id_hv."' LIMIT 1 ;";
						 mysql_query($sql);	
						 echo '<script >alert("Hoja de vida inicializada correctamente!");</script>';
						 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_pendiente'>";
						 die();
						 
					}
					else
					{
						echo '<script >alert("Hoja de vida inicializada, pero sin cargar la foto");</script>';
						echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_pendiente'>";
						die();
					}
				}
				else
				{
						 echo '<script >alert("Hoja de vida inicializada , pero sin cargar la foto!");</script>';
						 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_pendiente'>";
						 die();
				}				
			}
			else
			{
					 echo '<script >alert("Hoja de vida inicializada , pero sin cargar la foto!");</script>';
					 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_pendiente'>";
					 die();
			}
					
		
		}
		else
		{
			$error = 'ERROR BASE DE DATOS <br> '.mysql_error();
		}
		
	}
	
	
	
}

?>

<h2>INICIALIZAR HOJA DE VIDA </h2>

<div align=right>
	<a href="?cmp=lista_pendiente"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de hojas de vida </a>
</div>
<form action="?ingresar=1"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<table align="center">
		<tr>
			 <td valign=top>
			
				<div class=" form-group input-group input-group-lg">
				  <span class="input-group-addon">Apellido</span>
				  <input  name="apellido" style="width:100%" id="apellido" value="<?php echo $_POST["apellido"] ?>" type="text" size="50" class="form-control" placeholder="Apellido del usuario" required />
				</div>  
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Nombre</span>
				  <input name="nombre" style="width:100%" id="nombre" value="<?php echo $_POST["nombre"] ?>"  type="text" class="form-control" placeholder="Nombre del usuario" required />
				</div> 
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Identificacion</span>
				  <input  name="identificacion" value="<?php echo $_POST["identificacion"] ?>" id="identificacion" type="number" class="form-control" placeholder="Identificacion del usuario" required />
				</div> 
				
				
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
			</td>
			
			<td>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
			
			<td valign=top>
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Direccion</span>
				  <input name="direccion" id="direccion" value="<?php echo $_POST["direccion"] ?>" type="text" class="form-control" placeholder="Direccion del usuario" required />
				</div> 
				
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">E - mail</span>
				  <input name="email" id="email" value="<?php echo $_POST["email"] ?>" required  type="email" class="form-control" placeholder="E-mail de la persona" />
				</div> 	
				
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Cargo a postular</span>
					 <select  class="form-control" name="cargo_postular" id="cargo_postular" required>
						<option value="">Seleccione un cargo</option>	
						<?php
						$sql_cargo = "SELECT id, nombre FROM cargo WHERE estado = 1 AND id_instancia = '".$_SESSION["nst"]."' ORDER BY nombre";
						$res_cargo = mysql_query($sql_cargo);
						while($row_cargo = mysql_fetch_array($res_cargo)){						
						?>
							<option value="<?php echo $row_cargo["id"] ?>"><?php echo $row_cargo["nombre"] ?></option>
						<?php
						}
						?>
					 </select>
				</div> 
				
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Telefono</span>
				  <input name="telefono" id="telefono" value="<?php echo $_POST["telefono"] ?>" type="text" class="form-control" placeholder="Telefono de la persona" required />
				</div> 
				
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Foto rostro</span>
				   
					<input name="foto" id="foto" value="<?php echo $_POST["telefono"] ?>" type="file" class="form-control"  required />
					
				</div> 

				
			</td>
		</tr>

		<tr>
			<td colspan=3 align=center>
					<?php if($error){?>
					<div class="alert alert-info">
						<?php echo $error; ?>
					</div>
				<?php } ?>
					<input type="submit" class="btn btn-primary" value="Inicializar">
			</td>
		</tr>
				
	</table>
</form>			
