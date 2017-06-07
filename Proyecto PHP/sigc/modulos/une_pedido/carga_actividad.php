<?php


function limpiar_fecha($dato)
{
	$fecha_x = explode('/',$dato);
	$fecha_ok = $fecha_x[2].'-'.$fecha_x[1].'-'.$fecha_x[0];
	return $fecha_ok; 
}


if($_POST["cargar"])
{
	
	$archivo = date("Y_m_d_G_i_s").".csv";
	$destino = "documentos/hv/".$archivo;
	if (copy($_FILES['excel']['tmp_name'],$destino))
	{
		$i = 1;
		$trozos = explode(".", $_FILES['excel']['name']); 
		$extension = end($trozos);
		if($extension=='CSV' || $extension=='csv')
		{
			$fp = fopen ($_FILES['excel']['tmp_name'],"r");
			while ($tramite = fgetcsv ($fp, 1000, ";")) 
			{
				
				if($i==1)
				{
					$forma = '';
					if($tramite[1]=='lngIdPedido' && $tramite[19]=='actividadHomologada'){$forma=1;}
					if(strpos($tramite[1], 'EDIDO') && $tramite[30]=='INCONSISTENCIAS'){$forma=2;}
					
					if(!$forma)
					{
						$error = $error."<b>Linea ".$i.$sql_ing_materia."</b>: Encabezado incorrecto<br>";
						break;
					}
				}
				
						
				///ARCHIVO DE ACTIVIDADES INSTALACIONES
				if( ($i>1) && $forma ==1 )
				{					
						$id_pedido = '';									
						$numero = limpiar_numero($tramite[1]);
						$codigo = limpiar_numero($tramite[19]);
						$repetir = limpiar_numero($tramite[16]);	

						if($numero && $codigo)
						{																						
							// BUSCAR NUMERO 
							$sql_bus_pedido = "SELECT id FROM  une_pedido WHERE numero = '".$numero."' AND une_pedido.tipo='1' LIMIT 1 ";
							$res_bus_pedido = mysql_query($sql_bus_pedido);
							$row_bus_pedido = mysql_fetch_array($res_bus_pedido);					
							if($row_bus_pedido["id"])
							{
								$id_pedido = $row_bus_pedido["id"];
								//LIQUIDACION
								$sql_item = "SELECT id FROM une_item WHERE codigo = '".$codigo."' LIMIT 1";
								$res_item = mysql_query($sql_item);
								$row_item = mysql_fetch_array($res_item);
								
								if($row_item["id"])
								{
									$sql_bus_liq = "SELECT id FROM une_liquidacion 
									WHERE id_pedido = '".$id_pedido."'  AND id_item = '".$row_item["id"]."' LIMIT 1";
									$res_bus_liq = mysql_query($sql_bus_liq);
									$row_bus_liq = mysql_fetch_array($res_bus_liq);
									if(!$row_bus_liq["id"])
									{																										
																			
										if($codigo=='104' || $codigo=='105' || $codigo=='106' || $codigo=='107')
										{
											$i = 1;
											while($i < $repetir)
											{
												$sql_ing_liq = "INSERT INTO `une_liquidacion` (`id_pedido`, `id_item`) 
												VALUES ('".$id_pedido."', '".$row_item["id"]."');";
												mysql_query($sql_ing_liq);										
												$i++;												
											}										
											
										}
																				
										$sql_ing_liq = "INSERT INTO `une_liquidacion` (`id_pedido`, `id_item`) 
										VALUES ('".$id_pedido."', '".$row_item["id"]."');";
										mysql_query($sql_ing_liq);
										if(mysql_error())
										{
											$error = $error."<b>Linea ".$i."</b>: No se pudo ingresar esta actividad a la base de datos<br>";															
										}else{
											$sql_ped = "UPDATE `une_pedido` SET `estado_liquidacion` = '1' WHERE `id` = '".$id_pedido."' LIMIT 1;";
											mysql_query($sql_ped);
										}
										 
									} 
								}
								else
								{
									$sql_bus_liq = "SELECT id FROM une_liquidacion 
									WHERE id_pedido = '".$id_pedido."'  AND codigo = '".$codigo."' LIMIT 1";
									$res_bus_liq = mysql_query($sql_bus_liq);
									$row_bus_liq = mysql_fetch_array($res_bus_liq);
									if(!$row_bus_liq["id"])
									{																										
										
										if($codigo=='104' || $codigo=='105' || $codigo=='106' || $codigo=='107')
										{
											$i = 1;
											while($i < $repetir)
											{
												$sql_ing_liq = "INSERT INTO `une_liquidacion` (`id_pedido`, `codigo`) 
												VALUES ('".$id_pedido."', '".$codigo."');";
												mysql_query($sql_ing_liq);										
												$i++;												
											}										
											
										}									
										
										$sql_ing_liq = "INSERT INTO `une_liquidacion` (`id_pedido`, `codigo`) 
										VALUES ('".$id_pedido."', '".$codigo."');";
										mysql_query($sql_ing_liq);
										if(mysql_error())
										{
											$error = $error."<b>Linea ".$i."</b>: No se pudo ingresar esta actividad a la base de datos<br>";															
										}
										else
										{
											$sql_ped = "UPDATE `une_pedido` SET `estado_liquidacion` = '1' WHERE `id` = '".$id_pedido."' LIMIT 1;";
											mysql_query($sql_ped);
										}
										
									}
								}												 																													
								
							}										
											
						}									
							
						
				}
				
				///ARCHIVO DE ACTIVIDADES REPARACIONES
				if( ($i>1) && $forma ==2 )
				{					
						$id_pedido = '';									
						$numero = limpiar_numero($tramite[1]);
						$codigo = limpiar_numero($tramite[7]);
						$repetir = limpiar_numero($tramite[9]);	
						$descripcion_falla = limpiar($tramite[29]);	
						$inconsistencia = limpiar($tramite[30]);	
						

						if($numero && $codigo)
						{																						
							// BUSCAR NUMERO
							$sql_bus_pedido = "SELECT id FROM  une_pedido WHERE numero = '".$numero."' AND une_pedido.tipo='1' LIMIT 1 ";
							$res_bus_pedido = mysql_query($sql_bus_pedido);
							$row_bus_pedido = mysql_fetch_array($res_bus_pedido);					
							if($row_bus_pedido["id"])
							{
								$id_pedido = $row_bus_pedido["id"];
								//LIQUIDACION
								$sql_item = "SELECT id FROM une_item WHERE codigo = '".$codigo."' LIMIT 1";
								$res_item = mysql_query($sql_item);
								$row_item = mysql_fetch_array($res_item);
								
								if($row_item["id"])
								{
									$sql_bus_liq = "SELECT id FROM une_liquidacion 
									WHERE id_pedido = '".$id_pedido."'  AND id_item = '".$row_item["id"]."' LIMIT 1";
									$res_bus_liq = mysql_query($sql_bus_liq);
									$row_bus_liq = mysql_fetch_array($res_bus_liq);
									if(!$row_bus_liq["id"])
									{																										
																			
										if($codigo=='201')
										{
											$i = 1;
											while($i < $repetir)
											{
												$sql_ing_liq = "INSERT INTO `une_liquidacion` (`id_pedido`, `id_item`) 
												VALUES ('".$id_pedido."', '".$row_item["id"]."');";
												mysql_query($sql_ing_liq);										
												$i++;												
											}										
											
										}
																				
										$sql_ing_liq = "INSERT INTO `une_liquidacion` (`id_pedido`, `id_item`) 
										VALUES ('".$id_pedido."', '".$row_item["id"]."');";
										mysql_query($sql_ing_liq);
										if(mysql_error())
										{
											$error = $error."<b>Linea ".$i."</b>: No se pudo ingresar esta actividad a la base de datos<br>";															
										}else{
											$sql_ped = "UPDATE `une_pedido` SET 
											`descripcion_falla` = '".$descripcion_falla."',
											`inconsistencia` = '".$inconsistencia."',
											`estado_liquidacion` = '1'
											WHERE `id` = '".$id_pedido."' LIMIT 1;";
											mysql_query($sql_ped);
										}
										 
									} 
								}
								else
								{
									$sql_bus_liq = "SELECT id FROM une_liquidacion 
									WHERE id_pedido = '".$id_pedido."'  AND codigo = '".$codigo."' LIMIT 1";
									$res_bus_liq = mysql_query($sql_bus_liq);
									$row_bus_liq = mysql_fetch_array($res_bus_liq);
									if(!$row_bus_liq["id"])
									{																										
										
										if($codigo=='201')
										{
											$i = 1;
											while($i < $repetir)
											{
												$sql_ing_liq = "INSERT INTO `une_liquidacion` (`id_pedido`, `codigo`) 
												VALUES ('".$id_pedido."', '".$codigo."');";
												mysql_query($sql_ing_liq);										
												$i++;												
											}										
											
										}									
										
										$sql_ing_liq = "INSERT INTO `une_liquidacion` (`id_pedido`, `codigo`) 
										VALUES ('".$id_pedido."', '".$codigo."');";
										mysql_query($sql_ing_liq);
										if(mysql_error())
										{
											$error = $error."<b>Linea ".$i."</b>: No se pudo ingresar esta actividad a la base de datos<br>";															
										}
										else
										{
											$sql_ped = "UPDATE `une_pedido` SET 
											`descripcion_falla` = '".$descripcion_falla."',
											`inconsistencia` = '".$inconsistencia."',
											`estado_liquidacion` = '1' WHERE `id` = '".$id_pedido."' LIMIT 1;";
											mysql_query($sql_ped);
										}
										
									}
								}												 																													
								
							}										
											
						}									
							
						
				}
				
				
			$i++;	
			}
			
		}
		else
		{
			$error = 'El archivo de ser un CSV (Delimitado por comas) ';	
		}
		
	}
	else
	{
		$error = 'No se pudo cargar el archivo';	
	}		
	 echo '<script> alert("Se termino de cargar el archivo");</script>';
}



?>

<h2>CARGA DE ACTIVIDADES <b>HFC</b></h2>
<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<div align=right>
		<a href="?cmp=liquidacion"> <i class="fa fa-reply fa-2x"></i> Volver al listado de pedidos </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
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
</form>		<br><br><br><br><br><br><br><br><br>


<a href="?cmp=carga_borrar2"> Actualizar datos antiguos (Enlace temporal) </a>
