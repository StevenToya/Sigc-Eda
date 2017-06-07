<?php
if($PERMISOS_GC["amb_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_POST["agregar"])
{
				
		$sql = "INSERT INTO `documento` (`descripcion`, `fecha_vencimiento`, `fecha_revision`, `revision_mes`, `estado`, `tipo`, `id_instancia`) 
		VALUES ('".limpiar($_POST["descripcion"])."', 'n',  's', '".$_POST["revision_mes"]."', '1','5', '".$_SESSION["nst"]."');";
		mysql_query($sql);
		$id_doc = mysql_insert_id();
		if($id_doc)
		{
			if($_FILES["archivo"]['name'])
			{
				$structure = "documentos/ambiental/plantilla";
				$trozos = explode(".", $_FILES["archivo"]['name']); 
				$extension = end($trozos);
				$ruta = $structure."/".$id_doc.".".$extension;
				move_uploaded_file($_FILES["archivo"]['tmp_name'], $ruta);
				if(!file_exists($ruta))
				{
					echo '<script> alert("Se guardo el registro, pero no se pudo subir el archivo \n") </script>';
					echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_regla'>";die();
				}
				else
				{
					$sql_arc = "UPDATE `documento` SET `archivo` = '".$ruta."' 	WHERE  id = '".$id_doc."' ;";
					mysql_query($sql_arc);	

					echo '<script>alert("Se guardo registrado correctamente.")</script>';
					echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_regla'>";die();
				}
			}
			else
			{
				echo '<script> alert("Se guardo el registro, pero no se pudo subir el archivo \n") </script>';
				echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_regla'>";die();
			}		
			
		}
		else
		{
			echo '<script>alert("Error interno al ingresarlo en la base de datos.")</script>';
		}
		
	
}

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
?>

<h2>CREAR DOCUMENTO AMBIENTAL </h2>
	
<div align="right">
	<a href="?cmp=lista_regla"> <i class="fa fa-reply fa-2x"></i> Volvel al listado documento </a>
</div>

<form action="?tip=<?php echo $_GET["tip"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">

<table border='0' width='70%' align=center>
	<tr>
		<td>
			<div class=" form-group input-group input-group-lg"   style="width:100%"   >
			  <span class="input-group-addon">Descripcion del documento</span>
			  <textarea  name="descripcion" id="descripcion"  class="form-control" style="height:150px"  required ><?php echo $_POST["descripcion"] ?></textarea>
			</div> 
			
					
			<div class=" form-group input-group input-group-lg" style="width:100%" >
			  <span class="input-group-addon">Meses para la revision</span>
			  <input  name="revision_mes" id="revision_mes" value="<?php echo $_POST["revision_mes"] ?>"  type="number"  class="form-control" placeholder="" required />
			</div> 

			<div class=" form-group input-group input-group-lg" style="width:100%" >
			  <span class="input-group-addon">Plantilla</span>
			  <input  name="archivo" id="archivo"    type="file"  class="form-control" placeholder="" required />
			</div> 
			
		</td>
		
	</tr>

	
</table>
<br>
	<center>
		<input type="submit" value="Agregar documento" name="agregar" class="btn btn-primary">
		
	</center>
</form>