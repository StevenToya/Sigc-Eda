<?php
if($PERMISOS_GC["hv_cre"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


if($_GET["actualizar"])
{
	$sql = "SELECT id FROM hv_persona WHERE  identificacion = '".$_POST["identificacion"]."' AND id_instancia = '".$_SESSION["nst"]."' 
	AND hv_persona.id <> '".$_GET["id"]."' LIMIT 1";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	if($row["id"])
	{		
		$error = 'La identificacion  "'.$_POST["identificacion"].'"  ya esta registrada en otra hoja de vida';
	}	
	else
	{
				
		$_POST["nombre"] =  strtolower(trim($_POST["nombre"]));
		$_POST["apellido"] =  strtolower(trim($_POST["apellido"]));			
				
		$sql = "UPDATE `hv_persona` SET
		`id_cargo` = '".$_POST["cargo_postular"]."',
		`telefono` = '".$_POST["telefono"]."',
		`nombre` = '".ucwords(strtolower(trim($_POST["nombre"])))."',
		`apellido` = '".ucwords(strtolower(trim($_POST["apellido"])))."',
		`identificacion` = '".$_POST["identificacion"]."',
		`correo` =  '".$_POST["email"]."',
		`direccion` ='".trim($_POST["direccion"])."',
		`id_municipio` ='".$_POST["municipio"]."',
		`id_usuario_creador` ='".$_SESSION["user_id"]."'
		WHERE id = '".$_GET["id"]."' LIMIT 1 ;";
		mysql_query($sql);			
		
		if($_FILES['foto']['name'])
		{				
			$carpeta = "documentos/hv/".$_GET["id"];
			@mkdir($carpeta, 0777);
			chmod($carpeta, 0777);
			$trozos = explode(".", $_FILES['foto']['name']); 
			$extension = end($trozos);	
			if($extension=='JPG' || $extension=='jpg' || $extension=='JPEG' || $extension=='jpeg' || $extension=='GIF' || $extension=='gif' || $extension=='PNG' || $extension=='png')
			{
				$ruta = $carpeta."/foto.".$extension;			
				move_uploaded_file($_FILES['foto']['tmp_name'], $ruta);
				if(file_exists($ruta))
				{
					 $sql = "UPDATE hv_persona SET foto = '".$ruta."'  WHERE id ='".$_GET["id"]."' LIMIT 1 ;";
					 mysql_query($sql);	
					 echo '<script >alert("Hoja de vida actualizada correctamente!");</script>';
					 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=fase1&id=".$_GET["id"]."'>";
					 die();
					 
				}
				else
				{
					echo '<script >alert("Hoja de vida actualizada, pero sin cargar la foto");</script>';
					echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=fase1&id=".$_GET["id"]."'>";
					die();
				}
			}
			else
			{
					 echo '<script >alert("Hoja de vida actualizada , pero sin cargar la foto!");</script>';
					 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=fase1&id=".$_GET["id"]."'>";
					 die();
			}				
		}
		else
		{
				 echo '<script >alert("Hoja de vida actualizada");</script>';
				 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=fase1&id=".$_GET["id"]."'>";
				 die();
		}
					
		
		
		
	}
}

$sql = "SELECT * FROM hv_persona WHERE id = '".$_GET["id"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);


?>

<h2>ACTUALIZAR HOJA DE VIDA </h2>

<div align=right>
	<a href="?cmp=fase1&id=<?php echo $_GET["id"] ?>"> <i class="fa fa-reply fa-2x"></i> Volvel a la hoja de vida </a>
</div>
<form action="?actualizar=1&id=<?php echo $_GET["id"] ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<table align="center">
		<tr>
			 <td valign=top>
			
				<div class=" form-group input-group input-group-lg" style="width:100%">
				  <span class="input-group-addon">Apellido</span>
				  <input  name="apellido" id="apellido" value="<?php echo $row["apellido"] ?>" type="text" class="form-control" placeholder="Apellido del usuario" required />
				</div>  
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Nombre</span>
				  <input name="nombre" id="nombre" value="<?php echo $row["nombre"] ?>"  type="text" class="form-control" placeholder="Nombre del usuario" required />
				</div> 
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Identificacion</span>
				  <input  name="identificacion" value="<?php echo $row["identificacion"] ?>" id="identificacion" type="number" class="form-control" placeholder="Identificacion del usuario" required />
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
					 <?php 
						$sql_mun = "SELECT nombre FROM municipio  WHERE id = '".$row["id_municipio"]."' LIMIT 1 ";
						$res_mun = mysql_query($sql_mun);
						$row_mun = mysql_fetch_array($res_mun);
					 ?>
						<option value="<?php echo $row["id_municipio"] ?>"><?php echo $row_mun["nombre"] ?></option>							
					 </select>
				</div> 
			</td>
			
			<td>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
			
			<td valign=top>
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Direccion</span>
				  <input name="direccion" id="direccion" value="<?php echo $row["direccion"] ?>" type="text" class="form-control" placeholder="Direccion del usuario" required />
				</div> 
				
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">E - mail</span>
				  <input name="email" id="email" value="<?php echo $row["correo"] ?>" required  type="email" class="form-control" placeholder="E-mail de la persona" />
				</div> 	
				
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Cargo a postular</span>
					 <select  class="form-control" name="cargo_postular" id="cargo_postular" required>
						<?php
						$sql_cargo = "SELECT id, nombre FROM cargo WHERE estado = 1 AND id_instancia = '".$_SESSION["nst"]."' ORDER BY nombre";
						$res_cargo = mysql_query($sql_cargo);
						while($row_cargo = mysql_fetch_array($res_cargo))
						{							
						?>
							<option <?php if($row_cargo["id"]==$row["id_cargo"]){ ?> selected <?php } ?> value="<?php echo $row_cargo["id"] ?>"><?php echo $row_cargo["nombre"] ?></option>
						<?php
						}
						?>						
					 </select>
				</div> 
				
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Telefono</span>
				  <input name="telefono" id="telefono" value="<?php echo $row["telefono"] ?>" type="text" class="form-control" placeholder="Telefono de la persona" required />
				</div> 
				
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Cambiar Foto</span>
				   
					<input name="foto" id="foto"  type="file" class="form-control"  />
					
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
					<input type="submit" class="btn btn-primary" value="Actualizar">
			</td>
		</tr>
				
	</table>
</form>			
