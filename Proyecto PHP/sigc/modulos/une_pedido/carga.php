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
					if($tramite[1]=='PEDIDO_ID' && $tramite[32]=='FECHA'){$forma=1;}
					if($tramite[4]=='PEDIDO'){$forma=2;}
					if($tramite[2]=='PEDIDO AGENDA'){$forma=3;}
					
					if(!$forma)
					{
						$error = $error."<b>Linea ".$i.$sql_ing_materia."</b>: Encabezado incorrecto<br>";
						break;
					}
				}
				
				$ciudad = limpiar($tramite[2]);				
				
				/* if( ($i>1) && ( $ciudad=='Valledupar' || $ciudad=='Sincelejo' || $ciudad=='Monteria' || $forma ==2  || $forma ==3 ) ) */
				if( ($i>1) && ( $forma ==1  || $forma ==2  || $forma ==3 ) ) 
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

$extexiones_tv = 	limpiar($tramite[14]);	
$tipo_direccion = 	limpiar($tramite[33]);	
$cliente_id = 	limpiar($tramite[43]);	
$servicio_instalado = 	limpiar($tramite[44]);	
$reparacion = 	limpiar($tramite[45]);	
$servicio_insta = 	limpiar($tramite[46]);	
$estado_pedido = 	limpiar($tramite[47]);	
$municipio_dane = 	limpiar($tramite[48]);	
$departamento_dane = 	limpiar($tramite[49]);	

$numero_componente = 	limpiar($tramite[6]);	
$unidad_material = 	limpiar($tramite[16]);	
$identificador = 	limpiar($tramite[18]);	
$min_ct = 	limpiar($tramite[19]);	
$max_ct = 	limpiar($tramite[20]);	
$min_pr = 	limpiar($tramite[21]);	
$max_pr = 	limpiar($tramite[22]);	
$nuevo_min = 	limpiar($tramite[23]);	
$nuevo_max = 	limpiar($tramite[24]);	
$alerta_cable_ct = 	limpiar($tramite[25]);	
$calc_puntos_instalado = 	limpiar($tramite[26]);	
$alerta_puntos_instalados = 	limpiar($tramite[27]);	
$calc_conectores_inst = 	limpiar($tramite[28]);	
$alerta_conectores_inst = 	limpiar($tramite[29]);	
$calc_filtros_inst = 	limpiar($tramite[30]);	
$alerta_filtro = 	limpiar($tramite[31]);	
$precio = 	limpiar($tramite[34]);	
$precio_x_cant_rep = 	limpiar($tramite[35]);	
$difer_precio_x_cant_rep = 	limpiar($tramite[36]);	
$li = 	limpiar($tramite[37]);	
$ls = 	limpiar($tramite[38]);	
$precio_x_dif_sobre_lim = 	limpiar($tramite[39]);	
$alerta_cable_limites = 	limpiar($tramite[40]);	
$alertado = 	limpiar($tramite[41]);	
$reiterativo = 	limpiar($tramite[42]);	
$direccion = 	limpiar($tramite[50]);	

$cliente_nombre = limpiar($tramite[51]);
$telefono_contacto = 	limpiar($tramite[52]);
$celular_contacto = limpiar($tramite[53]);
$microzona = limpiar($tramite[54]);
						
									
									$cantidad_material = limpiar_numero($tramite[17]);
									
									$nombre_funcionario = limpiar($tramite[7]);
									$codigo_funcionario = limpiar_numero($tramite[8]);
									$segmento = limpiar($tramite[9]);
									$producto = limpiar($tramite[10]);
									$producto_homologado = limpiar($tramite[11]);
									$tecnologia = limpiar($tramite[12]);
									$proceso = limpiar($tramite[13]);
									$empresa = limpiar($tramite[15]);
									$fecha = limpiar_fecha($tramite[32]);
									
									$var_1 = limpiar($tramite[25]);
									$var_2 = limpiar($tramite[29]);
									$var_3 = limpiar($tramite[31]);														
																						
									if($nombre_empresa && $numero && $codigo_material && $ciudad && $tipo_trabajo && $producto)
									{
																						
													// BUSCAR NUMERO
													$sql_bus_pedido = "SELECT id FROM  une_pedido WHERE numero = '".$numero."' AND une_pedido.tipo='1' LIMIT 1 ";
													$res_bus_pedido = mysql_query($sql_bus_pedido);
													$row_bus_pedido = mysql_fetch_array($res_bus_pedido);					
													if($row_bus_pedido["id"])
													{
														$id_pedido = $row_bus_pedido["id"];
													}
													else
													{																				
														//INGRESAR PEDIDO
														echo $sql_ing_ot = "INSERT INTO `une_pedido` (`numero`, `ciudad`, `tipo_trabajo`, `nombre_funcionario`, `codigo_funcionario`, 
														`segmento`, `producto`, `producto_homologado`, `tecnologia`, `proceso`, 
														`empresa`, `fecha`, `tipo`, `extexiones_tv`, `tipo_direccion`,
														`cliente_id`, `servicio_instalado`, `reparacion`, `servicio_insta`, `estado_pedido`,
														`municipio_dane`,`departamento_dane`, `cliente_direccion`, `telefono_contacto`, `celular_contacto`, 
														`microzona`, `cliente_nombre`) 
														VALUES ('".$numero."', '".$ciudad."', '".$tipo_trabajo."', '".$nombre_funcionario."', '".$codigo_funcionario."', 
														'".$segmento."', '".$producto."', '".$producto_homologado."', '".$tecnologia."', '".$proceso."', 
														'".$empresa."', '".$fecha."', '1', '".$extexiones_tv."', '".$tipo_direccion."',
														'".$cliente_id."', '".$servicio_instalado."', '".$reparacion."', '".$servicio_insta."', '".$estado_pedido."',
														'".$municipio_dane."', '".$departamento_dane."', '".$direccion."', '".$telefono_contacto."', '".$celular_contacto."', 
														'".$microzona."', '".$cliente_nombre."');";
														mysql_query($sql_ing_ot);
														$id_pedido = mysql_insert_id();	
													}
													
													
													if($id_pedido)
													{
															
															//MATERIAL
															if($codigo_material)
															{
																$sql_bus_material = "SELECT id FROM une_material WHERE codigo = '".$codigo_material."' AND tipo=1 LIMIT 1";
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
																		(`id_pedido`, `id_material`, `cantidad`, `alarma`, 
																		`unidad_material`, `identificador`, `min_ct`, `max_ct`, `min_pr`,
																		`max_pr`, `nuevo_min`, `nuevo_max`, `alerta_cable_ct`, `calc_puntos_instalado`,
																		`alerta_puntos_instalados`, `calc_conectores_inst`, `alerta_conectores_inst`, `calc_filtros_inst`, `alerta_filtro`,
																		`precio`, `precio_x_cant_rep`, `difer_precio_x_cant_rep`, `li`, `ls`,
																		`precio_x_dif_sobre_lim`, `alerta_cable_limites`, `alertado`, `reiterativo`, `numero_componente`) 
																		VALUES ( '".$id_pedido."', '".$id_material."', '".$cantidad_material."', '".$alarma."',
																		'".$unidad_material."', '".$identificador."', '".$min_ct."', '".$max_ct."', '".$min_pr."', 
																		'".$max_pr."', '".$nuevo_min."', '".$nuevo_max."', '".$alerta_cable_ct."', '".$calc_puntos_instalado."', 
																		'".$alerta_puntos_instalados."', '".$calc_conectores_inst."', '".$alerta_conectores_inst."', '".$calc_filtros_inst."', '".$alerta_filtro."', 
																		'".$precio."', '".$precio_x_cant_rep."', '".$difer_precio_x_cant_rep."', '".$li."', '".$ls."', 
																		'".$precio_x_dif_sobre_lim."', '".$alerta_cable_limites."', '".$alertado."', '".$reiterativo."', '".$numero_componente."');";
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
										$sql_bus_pedido = "SELECT id FROM  une_pedido WHERE numero = '".$numero."' AND une_pedido.tipo='1' LIMIT 1 ";
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
							
							///ACTUALIZAR NOMBRE Y DIRECCION CLIENTE
							if($forma==3)
							{
								$numero = limpiar_numero($tramite[2]);
								$cliente_nombre = limpiar($tramite[5]);;
								$cliente_direccion = limpiar($tramite[7]);
								$sql = "UPDATE `une_pedido` 
									SET `cliente_nombre` = '".$cliente_nombre."', 
									`cliente_direccion` = '".$cliente_direccion."' 
									WHERE `numero` = '".$numero."';";
								mysql_query($sql);
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

<h2>CARGA DE MATERIALES Y EQUIPOS <b>HFC</b></h2>
<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
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
</form>	<br><br><br>

<a href="?cmp=carga_borrar"> Actualizar datos antiguos (Enlace temporal) </a>



	
