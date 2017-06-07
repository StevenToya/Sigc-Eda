<?php
if($PERMISOS_GC["amb_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_POST["agregar"])
{		
	$sql = "UPDATE `documento` SET 
	`descripcion` = '".limpiar($_POST["descripcion"])."', 
	`revision_mes` = '".$_POST["revision_mes"]."'
	WHERE id = '".$_GET["id"]."';";
	mysql_query($sql);	
	
	if(!mysql_error())
	{
		if($_FILES["archivo"]['name'])
		{

			$structure = "documentos/ambiental/plantilla";
			$trozos = explode(".", $_FILES["archivo"]['name']); 
			$extension = end($trozos);
			$ruta = $structure."/".$_GET["id"].".".$extension;
			move_uploaded_file($_FILES["archivo"]['tmp_name'], $ruta);
			if(file_exists($ruta))
			{
				$sql_arc = "UPDATE `documento` SET `archivo` = '".$ruta."' 	WHERE  id = '".$_GET["id"]."' ;";
				mysql_query($sql_arc);	
				
				echo '<script>alert("Se actualizo el documento correctamente.")</script>';
				echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_regla'>";die();
			}
			else
			{
				echo '<script>alert("Se actualizaron los datos, pero no se actualizo el archivo.")</script>';
				echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_regla'>";die();
			}
		}
		else
		{
			echo '<script>alert("Se actualizaron los datos, pero no se actualizo el archivo.")</script>';
			echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_regla'>";die();
		}
	}
	else
	{
		echo '<script>alert("Error interno al actualizar en la base de datos.")</script>';
	}
	
}


$sql = "SELECT * FROM documento WHERE id = '".$_GET["id"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
?>

<h2>EDITAR DOCUMENTO AMBIENTAL</h2>
<div align="right">
	<a href="?cmp=lista_regla"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de los documento </a>
</div>

<form action="?id=<?php echo $_GET["id"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">

<table border='0' width='70%' align=center>
	<tr>
		<td>
			<div class=" form-group input-group input-group-lg" style="width:100%">
			  <span class="input-group-addon">Nombre del documento</span>
			  <textarea  name="descripcion" id="descripcion"  type="text" style="height:150px" class="form-control" required><?php echo $row["descripcion"] ?></textarea>
			</div> 
			
			<div class=" form-group input-group input-group-lg" style="width:100%">
			  <span class="input-group-addon">Meses para la revision</span>
			 
			  <input  name="revision_mes"  id="revision_mes" value="<?php echo $row["revision_mes"] ?>"  type="text" size="10" class="form-control" placeholder="" required />
			</div> 
			
			<div class=" form-group input-group input-group-lg" style="width:100%" >
			  <span class="input-group-addon">
				<a href="<?php echo $row["archivo"] ?>" target="blank">Plantilla</a>
			</span>
			  <input  name="archivo" id="archivo"   type="file"  class="form-control" placeholder="" />
			</div> 
			
		</td>
		
	</tr>

	
</table>
<br>
	<center>
		<input type="submit" value="Actualizar documento" name="agregar" class="btn btn-primary">
		
	</center>
</form>