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
					if($tramite[1]=='PEDIDO_ID' && $tramite[19]=='FECHA'){$forma=1;}
					if($tramite[4]=='PEDIDO'){$forma=2;}
					
					if(!$forma)
					{
						$error = $error."<b>Linea ".$i.$sql_ing_materia."</b>: Encabezado incorrecto<br>";
						break;
					}
				}
				
				$producto = limpiar($tramite[11]);				
				$empresa = limpiar($tramite[0]);
				$tecnologia = limpiar($tramite[12]);
				if( ($i>1) && ( ($empresa == 'EIA_EDATEL ANTIOQUIA APROVISIONAMIENTO NCA' && $tecnologia == 'DTH' && $forma ==1) || ($forma ==2) )  )
				{							
							///ARCHIVO DE MATERIALES
							if($forma==1)
							{
									$id_pedido = '';
									
									$nombre_empresa = limpiar($tramite[0]);
									$numero = limpiar_numero($tramite[1]);
									$ciudad = limpiar($tramite[2]);
									$tipo_trabajo = limpiar($tramite[3]);	
									
									$codigo_material = limpiar_numero($tramite[4]);
									$cantidad_material = limpiar_numero($tramite[17]);
									
									$nombre_funcionario = limpiar($tramite[7]);
									$codigo_funcionario = limpiar_numero($tramite[8]);
									$segmento = limpiar($tramite[9]);
									$producto = limpiar($tramite[10]);
									$producto_homologado = limpiar($tramite[11]);
									
									$proceso = limpiar($tramite[13]);
									$empresa = limpiar($tramite[15]);
									$fecha = limpiar_fecha($tramite[19]);
									/*
									$var_1 = limpiar($tramite[25]);
									$var_2 = limpiar($tramite[29]);
									$var_3 = limpiar($tramite[31]);														
									*/												
									if($nombre_empresa && $numero && $codigo_material && $ciudad && $tipo_trabajo && $producto)
									{
																						
													// BUSCAR NUMERO
													$sql_bus_pedido = "SELECT id FROM  une_pedido WHERE numero = '".$numero."' AND une_pedido.tipo='2' LIMIT 1 ";
													$res_bus_pedido = mysql_query($sql_bus_pedido);
													$row_bus_pedido = mysql_fetch_array($res_bus_pedido);					
													if($row_bus_pedido["id"])
													{
														$id_pedido = $row_bus_pedido["id"];
													}
													else
													{																				
														//INGRESAR PEDIDO
														$sql_ing_ot = "INSERT INTO `une_pedido` (`numero`, `ciudad`, `tipo_trabajo`, `nombre_funcionario`, `codigo_funcionario`, 
														`segmento`, `producto`, `producto_homologado`, `tecnologia`, `proceso`, 
														`empresa`, `fecha`, `tipo`) 
														VALUES ('".$numero."', '".$ciudad."', '".$tipo_trabajo."', '".$nombre_funcionario."', '".$codigo_funcionario."', 
														'".$segmento."', '".$producto."', '".$producto_homologado."', '".$tecnologia."', '".$proceso."', 
														'".$empresa."', '".$fecha."', '2');";
														mysql_query($sql_ing_ot);
														$id_pedido = mysql_insert_id();	
													}
													
													
													if($id_pedido)
													{
															
															//MATERIAL
															if($codigo_material)
															{
																$sql_bus_material = "SELECT id FROM une_material WHERE codigo = '".$codigo_material."' LIMIT 1";
																$res_bus_material = mysql_query($sql_bus_material);
																$row_bus_material = mysql_fetch_array($res_bus_material);
																if($row_bus_material["id"])
																{
																	$id_material = $row_bus_material["id"];
																	$sql_bus_material = "SELECT id FROM une_pedido_material 
																	WHERE id_material = '".$id_material."' AND id_pedido = '".$id_pedido."' LIMIT 1";
																	$res_bus_material = mysql_query($sql_bus_material);
																	$row_bus_material = mysql_fetch_array($res_bus_material);
																	if(!$row_bus_material["id"])
																	{
																		
																		$alarma = 1;																		
																		
																		if( (strpos($var_1, 'MENOR')!==false) || (strpos($var_2, 'MENOR')!==false) || (strpos($var_3, 'MENOR')!==false))
																		{$alarma = 2;}																			
																		
																		if( (strpos($var_1, 'MAYOR')!==false) || (strpos($var_2, 'MAYOR')!==false) || (strpos($var_3, 'MAYOR')!==false))
																		{$alarma = 3;}	
																		
																		$sql_ing_material = "INSERT INTO `une_pedido_material` 
																		(`id_pedido`, `id_material`, `cantidad`, `alarma`) 
																		VALUES ( '".$id_pedido."', '".$id_material."', '".$cantidad_material."', '".$alarma."');";
																		mysql_query($sql_ing_material);
																		if(mysql_error())
																		{
																			$error = $error."<b>Linea ".$i.$sql_ing_materia."</b>: No se pudo ingresar este material a la base de datos<br>";															
																		}
																		
																	} 
																	
																}
																
															
															}

															
													}
													else
													{
														$error = $error."<b>Linea ".$i."</b>: No se pudo ingresar el tramite a la base de datos<br>";							
													}
															
									}				
									else
									{
										$error = $error."<b>Linea ".$i."</b>: Informacion Incompleta<br>";											
									}								
									
							}
							
							///ARCHIVO DE EQUIPOS
							if($forma==2)
							{
									$id_pedido = '';
									
									$numero = limpiar_numero($tramite[4]);
									$serial = limpiar($tramite[8]);
									$mac = limpiar($tramite[10]);
																															
									if($numero && ($serial || $mac ))
									{
																						
										// BUSCAR NUMERO
										$sql_bus_pedido = "SELECT id FROM  une_pedido WHERE numero = '".$numero."' AND une_pedido.tipo='2' LIMIT 1 ";
										$res_bus_pedido = mysql_query($sql_bus_pedido);
										$row_bus_pedido = mysql_fetch_array($res_bus_pedido);					
										if($row_bus_pedido["id"])
										{
											$id_pedido = $row_bus_pedido["id"];
											//EQUIPO									
											$sql_bus_equipo = "SELECT id FROM une_pedido_equipo 
											WHERE id_equipo = '1' AND id_pedido = '".$id_pedido."'  AND serial = '".$serial."' AND mac = '".$mac."' LIMIT 1";
											$res_bus_equipo = mysql_query($sql_bus_equipo);
											$row_bus_equipo = mysql_fetch_array($res_bus_equipo);
											if(!$row_bus_equipo["id"])
											{																										
												$sql_ing_equipo = "INSERT INTO `une_pedido_equipo` 
												(`id_pedido`, `id_equipo`, `serial`, `mac`) 
												VALUES ( '".$id_pedido."', '1', '".$serial."', '".$mac."');";
												mysql_query($sql_ing_equipo);
												if(mysql_error())
												{
													$error = $error."<b>Linea ".$i."</b>: No se pudo ingresar este equipo a la base de datos<br>";															
												}
												
											} 																													
											
										}
										else
										{																				
											$error = $error."<b>Linea ".$i."</b>: Pedido no encontrado<br>";
										}
														
									}				
									else
									{
										$error = $error."<b>Linea ".$i."</b>: Informacion Incompleta<br>";											
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

<h2>CARGA DE MATERIALES Y EQUIPOS <b>DTH</b></h2>
<form action="?ingresar=1"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<div align=right>
		<a href="?cmp=lista_pendiente"> <i class="fa fa-reply fa-2x"></i> Volver al listado de pedidos </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
</form>		
