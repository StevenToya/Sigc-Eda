<?php


if($_GET["id"])
{
	$arc = 'documentos/tramites/'.$_GET["id"];
	@unlink($arc);
}

if($_GET['del']){
   $archivo=fopen("documentos/tramites/block_error.txt","w");
   fclose($archivo);
}


function listar_archivos($carpeta)
{
    if(is_dir($carpeta)){
        if($dir = opendir($carpeta)){
            while(($archivo = readdir($dir)) !== false){
                if($archivo != '.' && $archivo != '..' && $archivo != '.htaccess' && $archivo != 'block_error.txt'  && $archivo != 'block_error' ){
                    echo '<li><a target="_blank" href="'.$carpeta.'/'.$archivo.'">'.$archivo.'</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href=?id='.$archivo.'> <font color=red>Eliminar</font></a></li><br>';
                }
            }
            closedir($dir);
        }
    }
}

function limpiar_fecha($dato)
{
	$fecha_x = explode(' ',$dato);
	$fecha_x_hora = trim($fecha_x[1]);
	$fecha_x_dia = explode('.',trim($fecha_x[0]));
	$fecha_ok = $fecha_x_dia[2].'-'.$fecha_x_dia[1].'-'.$fecha_x_dia[0].' '.$fecha_x_hora;
	return $fecha_ok; 
}


function borrar_caracteres($dato)
{
	$dato = str_replace('"',"",$dato);
	$dato = str_replace("'","",$dato);
	$dato = str_replace("=","",$dato);
	return $dato; 
}


if($_POST["cargar"])
{
	$file = fopen("documentos/tramites/block_error.txt", "a");
	
	$archivo = date("Y_m_d_G_i_s").".csv";
	
	$destino = "documentos/tramites/".$archivo;
	if (copy($_FILES['excel']['tmp_name'],$destino))
	{
		
		$i = 1;
		$trozos = explode(".", $_FILES['excel']['name']); 
		$extension = end($trozos);
		if($extension=='CSV' || $extension=='csv')
		{
			$ult_carga = date("Y-m-d G:i:s");
			$fp = fopen ($_FILES['excel']['tmp_name'],"r");
			while ($tramite = fgetcsv ($fp, 1000, ";")) 
			{
				if($i>1)
				{																												
					$sqlxx = "UPDATE `front_tecnico` SET `id_torre` = '".$tramite[0]."' WHERE `front_tecnico`.`cedula` = '".$tramite[1]."';";
					mysql_query($sqlxx);
				}				
				
			$i++;	
			}
		
		}
		else
		{
			$error = 'El archivo de ser un CSV (Delimitado por comas) ';
			$error_block = "El archivo de ser un CSV -Delimitado por comas-  (".$archivo.")";
			fwrite($file, $error_block.PHP_EOL);
			
		}
		
	}
	else
	{
		$error = 'No se pudo cargar el archivo';
		$error_block = "No se pudo cargar el archivo [".date("Y-m-d G:i:s")."]";
		fwrite($file, $error_block.PHP_EOL);
		
	}		
	fclose($file);
}


?>

<h2>CARGA DE TORRE </h2>

<center>
	<a href="documentos/tramites/block_error.txt" target=blank> Descargar Block de Errores de la carga de archivos</a>
	
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="?del=1">Limpiar archivo Block de Errores </a> 
</center>

<form action="?ingresar=1"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<table align="center" width=60%>
		<tr>
			 <td valign=top>
			
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Cargar tamites CSV</span>
				  <input  name="excel"  id="excel" type="file" class="form-control" placeholder="Cargar tramites" required />
				</div> 
	
					<?php if($error){?>
					<div class="alert alert-info">
						<?php echo $error; ?>
					</div>
				<?php } ?>
					<center><input type="submit" class="btn btn-primary" value="Cargar" name="cargar" ></center>
			</td>
		</tr>
				
	</table>
</form>		

<h4>
<?php echo listar_archivos("documentos/tramites/"); ?>	
</h4>