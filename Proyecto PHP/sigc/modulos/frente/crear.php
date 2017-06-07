<?php
/*
if($PERMISOS_GC["hv_cre"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}
*/

if($_GET["ingresar"])
{					
	$_POST["nombre_1"] =  limpiar(strtoupper (trim($_POST["nombre_1"])));
	$_POST["nombre_2"] =  limpiar(strtoupper (trim($_POST["nombre_2"])));			
			
	$sql = "INSERT INTO `frente_trabajo` (`id_municipio`, `nombre_1`, `nombre_2`, `fecha_registro`, `id_instancia`, `tecnologia`) VALUES 
	('".$_POST["municipio"]."', '".$_POST["nombre_1"]."', '".$_POST["nombre_2"]."', '".date("Y-m-d G:i:s")."', '".$_SESSION["nst"]."', '".$_POST["tecnologia"]."');";			
	mysql_query($sql);
	$id_frente = mysql_insert_id();
	if($id_frente)
	{
		$carpeta = "documentos/frente/".$id_frente;
		@mkdir($carpeta, 0777);
		chmod($carpeta, 0777);
					
		if($_FILES['archivo_1']['name'])
		{				
			$trozos = explode(".", $_FILES['archivo_1']['name']); 
			$extension = end($trozos);	
			if($extension=='JPG' || $extension=='jpg' || $extension=='JPEG' || $extension=='jpeg' || $extension=='GIF' || $extension=='gif' || $extension=='PNG' || $extension=='png')
			{
				$ruta = $carpeta."/frente.".$extension;			
				move_uploaded_file($_FILES['archivo_1']['tmp_name'], $ruta);
				if(file_exists($ruta))
				{
					 $sql = "UPDATE frente_trabajo SET archivo_1 = '".$ruta."'  WHERE id ='".$id_frente."' LIMIT 1 ;";
					 mysql_query($sql);						 
				}
				else
				{
					$error = $error.'Error al cargar la foto del frente \n';
				}
			}
			else
			{
					$error = $error.'La foto de -Frente- debe ser una imagen \n';
			}				
		}
		else
		{
				$error = $error.'No se ingreso la foto -Frente- \n';
		}

		//FOTO DE TRANSPORTE
		if($_FILES['archivo_2']['name'])
		{				
			$trozos = explode(".", $_FILES['archivo_2']['name']); 
			$extension = end($trozos);	
			if($extension=='JPG' || $extension=='jpg' || $extension=='JPEG' || $extension=='jpeg' || $extension=='GIF' || $extension=='gif' || $extension=='PNG' || $extension=='png')
			{
				$ruta = $carpeta."/transporte.".$extension;			
				move_uploaded_file($_FILES['archivo_2']['tmp_name'], $ruta);
				if(file_exists($ruta))
				{
					 $sql = "UPDATE frente_trabajo SET archivo_2 = '".$ruta."'  WHERE id ='".$id_frente."' LIMIT 1 ;";
					 mysql_query($sql);						 
				}
				else
				{
					$error = $error.'Error al cargar la foto del transporte \n';
				}
			}
			else
			{
					$error = $error.'La foto de -Transporte- debe ser una imagen \n';
			}				
		}
		else
		{
				$error = $error.'No se ingreso la foto -Transporte- \n';
		}
		
		//FOTO DE HERRAMIENTA
		if($_FILES['archivo_3']['name'])
		{				
			$trozos = explode(".", $_FILES['archivo_3']['name']); 
			$extension = end($trozos);	
			if($extension=='JPG' || $extension=='jpg' || $extension=='JPEG' || $extension=='jpeg' || $extension=='GIF' || $extension=='gif' || $extension=='PNG' || $extension=='png')
			{
				$ruta = $carpeta."/herramienta.".$extension;			
				move_uploaded_file($_FILES['archivo_3']['tmp_name'], $ruta);
				if(file_exists($ruta))
				{
					 $sql = "UPDATE frente_trabajo SET archivo_3 = '".$ruta."'  WHERE id ='".$id_frente."' LIMIT 1 ;";
					 mysql_query($sql);						 
				}
				else
				{
					$error = $error.'Error al cargar la foto de las herramientas \n';
				}
			}
			else
			{
					$error = $error.'La foto de -Herramienta- debe ser una imagen \n';
			}				
		}
		else
		{
				$error = $error.'No se ingreso la foto -Herramienta- \n';
		}
		
		//FOTO DE EPP
		if($_FILES['archivo_4']['name'])
		{				
			$trozos = explode(".", $_FILES['archivo_4']['name']); 
			$extension = end($trozos);	
			if($extension=='JPG' || $extension=='jpg' || $extension=='JPEG' || $extension=='jpeg' || $extension=='GIF' || $extension=='gif' || $extension=='PNG' || $extension=='png')
			{
				$ruta = $carpeta."/epp.".$extension;			
				move_uploaded_file($_FILES['archivo_4']['tmp_name'], $ruta);
				if(file_exists($ruta))
				{
					 $sql = "UPDATE frente_trabajo SET archivo_4 = '".$ruta."'  WHERE id ='".$id_frente."' LIMIT 1 ;";
					 mysql_query($sql);						 
				}
				else
				{
					$error = $error.'Error al cargar la foto de EPP \n';
				}
			}
			else
			{
					$error = $error.'La foto de -EPP- debe ser una imagen \n';
			}				
		}
		else
		{
				$error = $error.'No se ingreso la foto -EPP- \n';
		}
		
	
	}
	else
	{
		$error_g = 'ERROR BASE DE DATOS <br> '.mysql_error();
	}


	if(!$error_g)
	{
		if($error)
		{
			  echo '<script> alert("'.$error.'");</script>';
			  echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista'>";
			  die();
		}
		else
		{
			 echo '<script> alert("El frente se creo correctamente");</script>';
			  echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista'>";
			  die();
		}
		
	}
	
	
}

?>

<h2>INGRESAR FRENTE </h2>

<div align=right>
	<a href="?cmp=lista"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de frente </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<form action="?ingresar=1"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<table align="center">
		<tr>
			 <td valign=top width=49%> 
			
				<div class=" form-group input-group input-group-lg">
				  <span class="input-group-addon"><div align=left>Nombre Oficial</div></span>
				  <input  name="nombre_1" style="width:100%" id="nombre_1" value="<?php echo $_POST["nombre_1"] ?>" type="text" size="50" class="form-control" placeholder="Primer tecnico" required />
				</div>  
			</td>
			<td width=2%> </td>
			<td valign=top width=49%>
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon"><div align=left>Nombre Auxiliar</div></span>
				  <input name="nombre_2" style="width:100%" id="nombre_2" value="<?php echo $_POST["nombre_2"] ?>"  type="text" class="form-control" placeholder="Segundo tecnico" required />
				</div> 
			</td>	
			
		</tr>
		
		<tr>
			<td>
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon"><div align=left>Departamento</div></span>
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
			 </td>
			 <td width=2%> </td>
			<td>
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon"><div align=left>Municipio</div></span>
					 <select  class="form-control" name="municipio" id="municipio" required>
						<option value="">Seleccione un municipio</option>							
					 </select>
				</div> 
			</td>
		</tr>
		<tr>
			<td>
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon"><div align=left>Tecnologia</div></span>
					 <select  class="form-control" name="tecnologia" id="tecnologia" required>
						<option value="">Seleccione la tecnologia</option>
						<option>Cobre</option>
						<option>DTH TigoUne</option>
						<option>HFC</option>
						<option>HFC TigoUne</option>
						<option>UMTS</option>												
					 </select>
				</div> 
			 </td>
			 <td width=2%> </td>
			<td></td>
		</tr>
		<tr>					
			<td>				
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon"><div align=left>Foto Frente</div></span>				   
					<input name="archivo_1" id="archivo_1"  type="file" class="form-control"  required />					
				</div> 	
			</td>
			<td width=2%> </td>
			<td>				
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon"><div align=left>Foto Transporte</div></span>				   
					<input name="archivo_2" id="archivo_2"  type="file" class="form-control"  required />					
				</div> 	
			</td>
		</tr>
		
		<tr>					
			<td>				
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon"><div align=left>Foto Herramienta</div></span>				   
					<input name="archivo_3" id="archivo_3"  type="file" class="form-control"  required />					
				</div> 	
			</td>
			<td width=2%> </td>
			<td>				
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon"><div align=left>Foto EPP</div></span>				   
					<input name="archivo_4" id="archivo_4"  type="file" class="form-control"  required />					
				</div> 	
			</td>
		</tr>

		<tr>
			<td colspan=3 align=center>
					<?php if($error_g){?>
					<div class="alert alert-info">
						<?php echo $error_g; ?>
					</div>
				<?php } ?>
					<input type="submit" class="btn btn-primary" value="Ingresar frente">
			</td>
		</tr>
				
	</table>
</form>			
