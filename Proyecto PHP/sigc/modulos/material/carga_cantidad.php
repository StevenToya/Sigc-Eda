<?php
$sql_pedido = "SELECT pedido_equipo_material.numero, pedido_equipo_material.estado, pedido_equipo_material.fecha, pedido_equipo_material.fecha_registro, 
		pedido_equipo_material.id, usuario.nombre, usuario.apellido, localidad.nombre AS nombre_localidad, localidad.id AS id_localidad
	FROM pedido_equipo_material  
	INNER JOIN usuario ON pedido_equipo_material.id_usuario = usuario.id 
	INNER JOIN localidad ON pedido_equipo_material.id_localidad = localidad.id 
	WHERE pedido_equipo_material.id='".$_GET["id"]."'  LIMIT 1;";
	$res_pedido = mysql_query($sql_pedido);
	$row_pedido = mysql_fetch_array($res_pedido);

if(!$row_pedido["id"])
{		
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_pedido'>";
	 die();
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
	$destino = "documentos/hv/bak_".date("Y_m_d_G_i_s");	
	if (copy($_FILES['excel']['tmp_name'],$destino))
	{
		$i = 1;
		$trozos = explode(".", $_FILES['excel']['name']); 
		$extension = end($trozos);
		if($extension=='CSV' || $extension=='csv')
		{
			$fp = fopen ($_FILES['excel']['tmp_name'],"r");
			while ($dato = fgetcsv ($fp, 1000, ";")) 
			{
					$dato[0] = limpiar_numero($dato[0]);
					$dato[1] = limpiar_numero($dato[1]);
					if($dato[0]>0 && $dato[1])
					{					
							$sql = "SELECT * FROM equipo_material WHERE tipo=2 AND id='".$dato[1]."'  LIMIT 1";
							$res = mysql_query($sql);
							$row = mysql_fetch_array($res);				
							if($row["id"])
							{								
								$sql_ing_material = "INSERT INTO `material_traza` 
								(`id_pedido`, `id_equipo_material`, `id_localidad`, `id_localidad_carga`,  `cantidad`,  `tipo`,  `fecha`, `fecha_registro`) 
								VALUES ('".$row_pedido["id"]."', '".$row["id"]."', '".$row_pedido["id_localidad"]."', '".$row_pedido["id_localidad"]."', '".$dato[0]."',  '2',  '".$row_pedido["fecha"]."', '".date("Y-m-d G:i:s")."');";
								mysql_query($sql_ing_material);					
							}
							else
							{
								$error = $error."Linea ".$i.": El identificador <b>".$dato[1]."</b> no se encuentra registrado<br>";
							}
					}else
					{
						$error = $error."Linea ".$i.": Faltan campos por llenar<br>";
					}
				$i++;	
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
	
	 if(!$error)
	 {				
		 echo "<script>alert('Se registraron los seriales correctamente')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_pedido'>";					
	 } 
}

?>

<h2>CARGA DE CANTIDADES DE MATERIALES A LA ORDEN <b><?php echo $row_pedido["numero"]; ?></b> - BODEGA <b><?php echo $row_pedido["nombre_localidad"]; ?></b></h2>
<div align=right>
<a href="?cmp=lista_pedido"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de pedidos </a>
</div>

<form action="?id=<?php echo $_GET["id"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">
	
	<h4>
	<table align=center>
		<tr>
			<td colspan=2>Informacion que debe tener cada columna en el archivo CSV<br>(Delimitado por comas)<br><br></td>
		</tr>
		<tr>
			<td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A1</b></td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cantidad de material</td>
		</tr>
		
		<tr>
			<td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B1</b></td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Identificador del material</td>
		</tr>
	</table>
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

<br>
Identificacion del equipo con su descripcion:<br><br>

<table width=40% class="table table-striped table-bordered table-hover">		
		<tr>
			<td>Idenficador</td>
			<td>Material</td>
		</tr>
		<?php
		$sql_equipo = "SELECT * FROM equipo_material WHERE tipo=2 AND codigo_1 NOT IN ('5','1005','4','100616','10026','100614','2','623','100505','100508','100506','100510','757') ORDER BY id ";
		$res_equipo = mysql_query($sql_equipo);
		while($row_equipo = mysql_fetch_array($res_equipo)){
		?>
			<tr>
				<td><b><?php echo $row_equipo["id"]; ?></b></td>
				<td><?php echo $row_equipo["nombre"]; ?></td>
			</tr>
		<?php
		}
		?>
		
	</table>
		
