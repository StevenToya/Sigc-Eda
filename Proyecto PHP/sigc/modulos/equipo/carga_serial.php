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
					$dato[0] = trim(borrar_caracteres(strtoupper($dato[0])));
					$dato[1] = limpiar_numero($dato[1]);
					if($dato[0] && $dato[1])
					{					
							$sql = "SELECT * FROM equipo_material WHERE tipo=1 AND id='".$dato[1]."'  LIMIT 1";
							$res = mysql_query($sql);
							$row = mysql_fetch_array($res);
				
							if($row["id"])
							{
								$sql = "SELECT id, id_pedido, id_tramite FROM equipo_serial WHERE serial = '".$dato[0]."' AND id_equipo_material = '".$dato[1]."' LIMIT 1";
								$res = mysql_query($sql);
								$row = mysql_fetch_array($res); 
								if($row["id"])
								{
									$id_serial = $row["id"];
									if($row["id_pedido"]!=$_GET["id"] && $row["id_tramite"])
									{										
										$sql_bb = "SELECT id FROM serial_traza WHERE id_equipo_serial = '".$row["id"]."' AND 
										fecha >= '".$row_pedido["fecha"]." 00:00:00' AND actual = 's' ";
										$res_bb = mysql_query($sql_bb);
										$row_bb = mysql_fetch_array($res_bb);
										if($row_bb["id"])
										{
												$sql_act_material ="UPDATE equipo_serial SET
												`id_localidad_carga` =  '".$row_pedido["id_localidad"]."',
												`id_pedido` =  '".$_GET["id"]."'												
												WHERE id = '".$id_serial."' LIMIT 1	";
												mysql_query($sql_act_material);
												
												$sql_ing_material = "INSERT INTO `serial_traza` 
											(`id_equipo_serial`, `id_localidad`,  `estado`,  `fecha`, `fecha_registro`, `actual`) 
											VALUES ( '".$row["id"]."', '".$row_pedido["id_localidad"]."',  '1',  '".$row_pedido["fecha"]."', '".date("Y-m-d G:i:s")."', 'n');";
											mysql_query($sql_ing_material);
											
										}
										else
										{
											$sql_act_material ="UPDATE equipo_serial SET
											`id_localidad` =  '".$row_pedido["id_localidad"]."',
											`id_localidad_carga` =  '".$row_pedido["id_localidad"]."',
											`id_tramite` =  NULL,
											`estado` =  '1',
											`id_pedido` =  '".$_GET["id"]."',
											`id_usuario` = '".$_SESSION["user_id"]."',
											`fecha_registro` =  '".$row_pedido["fecha"]." 00:00:00'
											WHERE id = '".$id_serial."' LIMIT 1	";
											mysql_query($sql_act_material);
											
											$sql_act_traza ="UPDATE serial_traza SET
											`actual` =  'n'	WHERE 	id_equipo_serial = '".$row["id"]."' LIMIT 1	";
											mysql_query($sql_act_traza);
														
											$sql_ing_material = "INSERT INTO `serial_traza` 
											(`id_equipo_serial`, `id_localidad`,  `estado`,  `fecha`, `fecha_registro`) 
											VALUES ( '".$row["id"]."', '".$row_pedido["id_localidad"]."',  '1',  '".$row_pedido["fecha"]."', '".date("Y-m-d G:i:s")."');";
											mysql_query($sql_ing_material);										
											
										}
										//historial
											$sql_historial = "INSERT INTO `pedido_equipo_historial` 
											(`id_pedido`, `id_serial`) 
											VALUES ( '".$_GET["id"]."', '".$id_serial."');";
											mysql_query($sql_historial);	
										
									}
									else
									{
										$error = $error."Linea ".$i.": El serial <b>".$dato[0]."</b> esta en un pedido vigente <br>";
										
									}
										
								}
								else
								{
										$sql_ing_material = "INSERT INTO `equipo_serial` 
										(`id_equipo_material`, `id_localidad`,  `id_localidad_carga`,  `serial`, `estado`,  `fecha_registro`,  `id_usuario`,  `id_pedido`) 
										VALUES ( '".$dato[1]."', '".$row_pedido["id_localidad"]."', '".$row_pedido["id_localidad"]."',  '".$dato[0]."', '1', '".$row_pedido["fecha"]." 00:00:00',  '".$_SESSION["user_id"]."',  '".$_GET["id"]."');";
										mysql_query($sql_ing_material);	
										$id_serial = mysql_insert_id();
										
										$sql_act_traza ="UPDATE serial_traza SET
										`actual` =  'n'	WHERE 	id_equipo_serial = '".$id_serial."' LIMIT 1	";
										mysql_query($sql_act_traza);
													
										$sql_ing_material = "INSERT INTO `serial_traza` 
										(`id_equipo_serial`, `id_localidad`,  `estado`,  `fecha`, `fecha_registro`) 
										VALUES ( '".$id_serial."', '".$row_pedido["id_localidad"]."',  '1',  '".$row_pedido["fecha"]."', '".date("Y-m-d G:i:s")."');";
										mysql_query($sql_ing_material);	
										
										//historial
											$sql_historial = "INSERT INTO `pedido_equipo_historial` 
											(`id_pedido`, `id_serial`) 
											VALUES ( '".$_GET["id"]."', '".$id_serial."');";
											mysql_query($sql_historial);	
										
								}
							}
							else
							{
								$error = $error."Linea ".$i.": El identificador <b>".$dato[1]."</b> no se encuentra registrada<br>";
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

<h2>CARGA DE SERIALES A NUMERO DE ORDEN <b><?php echo $row_pedido["numero"]; ?></b> - BODEGA <b><?php echo $row_pedido["nombre_localidad"]; ?></b></h2>
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
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Serial del equipo</td>
		</tr>
		
		<tr>
			<td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B1</b></td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Identificador del equipo</td>
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
			<td>Equipo</td>
		</tr>
		<?php
		$sql_equipo = "SELECT * FROM equipo_material WHERE tipo=1 ORDER BY id ";
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
		
