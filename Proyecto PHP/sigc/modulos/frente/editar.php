<?php
/*
if($PERMISOS_GC["usu_cre"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}
*/

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
	$_POST["nombre_1"] = limpiar(trim($_POST["nombre_1"]));
	$_POST["nombre_2"] = limpiar(trim($_POST["nombre_2"]));
	
	$sql_act = "UPDATE frente_trabajo SET 
	nombre_1 = '".$_POST["nombre_1"]."',
	nombre_2 = '".$_POST["nombre_2"]."',
	tecnologia = '".$_POST["tecnologia"]."',
	id_municipio = '".$_POST["municipio"]."'
	WHERE id ='".$_GET["id"]."' LIMIT 1 ;";
	mysql_query($sql_act);
	
	
	$carpeta = "documentos/frente/".$_GET["id"];
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
				 $sql = "UPDATE frente_trabajo SET archivo_1 = '".$ruta."'  WHERE id ='".$_GET["id"]."' LIMIT 1 ;";
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
				 $sql = "UPDATE frente_trabajo SET archivo_2 = '".$ruta."'  WHERE id ='".$_GET["id"]."' LIMIT 1 ;";
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
				 $sql = "UPDATE frente_trabajo SET archivo_3 = '".$ruta."'  WHERE id ='".$_GET["id"]."' LIMIT 1 ;";
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
				 $sql = "UPDATE frente_trabajo SET archivo_4 = '".$ruta."'  WHERE id ='".$_GET["id"]."' LIMIT 1 ;";
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
	
	
	if($error)
	{
				 echo '<script> alert("'.$error.'");</script>';
	}else{
		 echo '<script> alert("Frente actualizado correctamente");</script>';
	}
	
	
}



?>

<h2>DETALLE FRENTE</h2>

<div align=right>
	<a href="?cmp=lista"> <i class="fa fa-reply fa-2x"></i> Volvel al listado</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<br>

<?php
	$sql = "SELECT frente_trabajo.nombre_1, frente_trabajo.nombre_2, municipio.nombre, frente_trabajo.id, archivo_1, archivo_2, archivo_3, archivo_4, 
	id_municipio, frente_trabajo.tecnologia
	FROM frente_trabajo 	
	INNER JOIN municipio ON frente_trabajo.id_municipio = municipio.id
	WHERE frente_trabajo.id_instancia='".$_SESSION["nst"]."'  AND frente_trabajo.id = '".$_GET["id"]."' LIMIT 1";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);

?>

<form action="?ingresar=1&id=<?php echo $_GET["id"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<table align="center">
		<tr>
			 <td valign=top width=49%> 			
				<div class="form-group input-group input-group-lg" >
				  <span class="input-group-addon" ><div align=left>Nombre Oficial</div></span>
				  <input  name="nombre_1" style="width:100%" id="nombre_1" value="<?php echo strtoupper ($row["nombre_1"]) ?>" type="text" size="50" class="form-control" placeholder="Primer tecnico" required />
				</div>  
			</td>
			<td width=2%> </td>
			<td valign=top width=49%>
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon"><div align=left>Nombre Auxiliar</div></span>
				  <input name="nombre_2" style="width:100%" id="nombre_2" value="<?php echo strtoupper ($row["nombre_2"]) ?>"  type="text" class="form-control" placeholder="Segundo tecnico" required />
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
						<?php
							$sql_mun = "SELECT id, nombre FROM municipio WHERE id = '".$row["id_municipio"]."' LIMIT 1";
							$res_mun = mysql_query($sql_mun);
							$row_mun = mysql_fetch_array($res_mun);
						?>
						<?php if($row_mun["id"]){ ?>
							<option value="<?php echo $row_mun["id"] ?>"><?php echo utf8_encode($row_mun["nombre"]) ?></option>	
						<?php } ?>						
						<option value="">Seleccione un municipio</option>
					 </select>
				</div> 
			</td>
		</tr>
		<tr>
			<td>
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon"><div align=left>Tecnologia</div></span>
					 <select  class="form-control" name="tecnologia" id="tecnologia">
						<option><?php echo $row["tecnologia"]; ?></option>
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
			<td colspan=3 align=center>
					<?php if($error_g){?>
					<div class="alert alert-info">
						<?php echo $error_g; ?>
					</div>
				<?php } ?>
					
			</td>
		</tr>
				
	</table>

<table width=70% align=center>
	<tr>
		<td width="45%" valign=top>		
			<div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						FOTO DEL FRENTE
					</div>
					<div class="panel-body" align=center>
						<?php echo tipo_archivo($row["archivo_1"]); ?><br>
						<input name="archivo_1" id="archivo_1"  type="file" class="form-control"  />
					</div>					
				</div>
			</div>		
		</td>
		<td width="10%"></td>
		<td width="45%"  valign=top>
			<div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						FOTO DEL TRANSPORTE
					</div>
					<div class="panel-body" align=center>
						<?php echo tipo_archivo($row["archivo_2"]); ?>
						<input name="archivo_2" id="archivo_2"  type="file" class="form-control"  />
					</div>					
				</div>
			</div>	
		</td>
	</tr>
	<tr>
		<td colspan=3><br></td>
	</tr>
	<tr>
		<td  valign=top>		
			<div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						FOTO HERRAMIENTAS
					</div>
					<div class="panel-body" align=center>
						<?php echo tipo_archivo($row["archivo_3"]); ?><br>
						<input name="archivo_3" id="archivo_3"  type="file" class="form-control"  />
					</div>					
				</div>
			</div>		
		</td>
		<td width="10%"></td>
		<td  valign=top>
			<div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						FOTO EPP
					</div>
					<div class="panel-body" align=center>
						<?php echo tipo_archivo($row["archivo_4"]); ?><br>
						<input name="archivo_4" id="archivo_4"  type="file" class="form-control"  />
					</div>					
				</div>
			</div>	
		</td>
	</tr>
	</table>
<br>
<center>
	<input type="submit" name="guardar" class="btn btn-primary" value="Actualizar frente">
</center>

</form>	