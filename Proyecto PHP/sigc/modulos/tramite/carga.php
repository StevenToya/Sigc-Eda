<?php
$VECTOR_INSTALACION = array("67","75","76","161","163","164","194","195","200","201","228","291","464","473","486","554","568","583","584","592","713","714","921","922","923","2002","2003","2006","2014","2019","2020","2021","2022","2023","2027","2035","2038","2039","2044","2045","2046","2047","2048","2049","2050","2051","2052","2057","2058","2059","2060","2071","2073","2081","2082","2083","2100","2120","2121","2122","2123","2124","2125","2126","2127","2500","2501","2502","2503","2504","2505","2506","2507","2508","2509","2510","2511","2512","2513","2514","2515","2516","2517","2518","2519","2520","2521","2522","2523","2525","2527","2528","2529","2541","2542","2543","2627","9105","9998","9999");

$vec_mat[5] = 524;
$vec_mat[1005] = 524;
$vec_mat[4] = 524;

$vec_mat[100616] = 616;
$vec_mat[10026] = 616;

$vec_mat[100614] = 614;

$vec_mat[2] = 3;

$vec_mat[623] = 622;

$vec_mat[100505] = 505;

$vec_mat[100508] = 508;

$vec_mat[100506] = 506;

$vec_mat[100510] = 510;

$vec_mat[757] = 10029;

function obtener_cadena($contenido,$inicio,$fin){
    $r = explode($inicio, $contenido);
    if (isset($r[1])){
        $r = explode($fin, $r[1]);
        return $r[0];
    }
    return '';
}




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
				if($i==1){$cab_16 = limpiar(borrar_caracteres($tramite[16]));}
				
				$tipo_trabajo = limpiar_numero($tramite[3]); $fecha_obligatoria = trim($tramite[5]);
				if($i>1 && (in_array($tipo_trabajo, $VECTOR_INSTALACION) || $cab_16 == 'F_ATENCION' ) )
				{																												
					$id_ot = '';					
					$solicitud = limpiar_numero($tramite[0]);
					$ot = limpiar_numero($tramite[1]);
					$numero_orden = limpiar_numero($tramite[2]);					
					//$tipo_trabajo = limpiar_numero($tramite[3]);		
					$fecha_creacion_oden = limpiar_fecha($tramite[4]);
					$fecha_atencion_orden = limpiar_fecha($tramite[5]);
					
					$codigo_material = limpiar_numero($tramite[7]);
					$nombre_material = limpiar(borrar_caracteres($tramite[8]));
					$cantidad_material = limpiar_numero($tramite[9]);					
					
					$tem_unidad_operativa = explode('-',borrar_caracteres($tramite[10]));
					$codigo_unidad_operativa = limpiar_numero($tem_unidad_operativa[0]);
					$unidad_operativa = limpiar($tem_unidad_operativa[1]).' '.limpiar($tem_unidad_operativa[2]);
					
					$tem_tecnico = explode('-',borrar_caracteres($tramite[11]));
					$cedula_tecnico = limpiar_numero($tem_tecnico[0]);
					$codigo_tecnico = limpiar_numero($tem_tecnico[1]);
					$tecnico = limpiar(borrar_caracteres($tem_tecnico[2]));
					
					$codigo_tipo_paquete = limpiar_numero($tramite[12]);
					$tipo_paquete = limpiar(borrar_caracteres($tramite[13]));
					$motivo = limpiar_numero($tramite[14]);
					$fecha_reportada = limpiar_fecha($tramite[15]);
					
					if($cab_16 == 'F_ATENCION')
					{
						//ARCHIVO DE REPARACIONES
													 
						$fecha_atencion = limpiar_fecha($tramite[16]);
						$tipo_producto = limpiar($tramite[17]);
						$producto = limpiar_numero($tramite[18]);						
							$fecha_creacion_producto =  limpiar_fecha($tramite[19]);												
						$numero_servicio = limpiar(borrar_caracteres($tramite[20]));
							$codigo_dano = limpiar_numero($tramite[21]);						
						$tecnologia = limpiar(borrar_caracteres($tramite[22]));
						$departamento = limpiar(borrar_caracteres($tramite[23]));
						$codigo_localidad = limpiar_numero($tramite[24]);
						$localidad = limpiar(borrar_caracteres($tramite[25]));						
						//$location_id = limpiar(borrar_caracteres($tramite[22]));					
						$region = limpiar(borrar_caracteres($tramite[26]));
						$zona = limpiar(borrar_caracteres($tramite[27]));
						$cliente = limpiar(borrar_caracteres($tramite[28]));
						$identificacion_cliente = limpiar(borrar_caracteres($tramite[29]));
						$contrato = limpiar_numero($tramite[30]);
						$codigo_direccion = limpiar_numero($tramite[31]);
						$direccion = limpiar(borrar_caracteres($tramite[32]));
						
						$tem_clase_servicio = explode('-',borrar_caracteres($tramite[33]));
						$codigo_clase_servicio= limpiar_numero($tem_clase_servicio[0]);
						$clase_servicio = limpiar(borrar_caracteres($tem_clase_servicio[1]));
						
						$tem_plan_comercial = explode('-',borrar_caracteres($tramite[34]));
						$codigo_plan_comercial = limpiar_numero($tem_plan_comercial[0]);
						$plan_comercial = limpiar(borrar_caracteres($tem_plan_comercial[1]));
						
						$tipo_cliente = limpiar(borrar_caracteres($tramite[35]));	
						$tem_stb_9001 =  explode(', ',trim(trim(borrar_caracteres($tramite[36])),','));
						$tem_cpe_821 =  explode(', ',trim(trim(borrar_caracteres($tramite[37])),','));
						$tem_enrutador_9005 =  explode(', ',trim(trim(borrar_caracteres($tramite[38])),','));
						$tem_ont_9012 =  explode(', ',trim(trim(borrar_caracteres($tramite[39])),','));
						$tem_umts_9000 =  explode(', ',trim(trim(borrar_caracteres($tramite[40])),','));
						$tem_extencion_486 =  explode(', ',trim(trim(borrar_caracteres($tramite[41])),','));
						$tem_imsi_9022 =  explode(', ',trim(trim(borrar_caracteres($tramite[42])),','));
						
						$voz = limpiar_numero($tramite[44]);
						$internet = limpiar_numero($tramite[45]);
						$television = limpiar_numero($tramite[46]);
						
						$descripcion_dano = limpiar($tramite[47]);
						$localizacion_dano = limpiar($tramite[48]);
						
						$cantidad_cpe	 = 0;
						$cantidad_stbox = 0;
						
						$eess = limpiar_numero($tramite[63]);
						
					}
					else
					{
						//ARCHIVO DE INSTALACIONES TRASLADOS Y RETIROS
						$fecha_asignacion = limpiar_fecha($tramite[16]);							 
						$fecha_atencion = limpiar_fecha($tramite[17]);					
						$tipo_producto = limpiar($tramite[18]);
						$producto = limpiar_numero($tramite[19]);
						$numero_servicio = limpiar(borrar_caracteres($tramite[20]));
						$tecnologia = limpiar(borrar_caracteres($tramite[21]));
						$location_id = limpiar(borrar_caracteres($tramite[22]));
						$departamento = limpiar(borrar_caracteres($tramite[23]));
						$codigo_localidad = limpiar_numero($tramite[24]);
						$localidad = limpiar(borrar_caracteres($tramite[25]));
						$region = limpiar(borrar_caracteres($tramite[26]));
						$zona = limpiar(borrar_caracteres($tramite[27]));
						$cliente = limpiar(borrar_caracteres($tramite[28]));
						$identificacion_cliente = limpiar(borrar_caracteres($tramite[29]));
						$contrato = limpiar_numero($tramite[30]);
						$codigo_direccion = limpiar_numero($tramite[31]);
						$direccion = limpiar(borrar_caracteres($tramite[32]));
						$locaprod = limpiar(borrar_caracteres($tramite[33]));
						
						$tem_clase_servicio = explode('-',borrar_caracteres($tramite[34]));
						$codigo_clase_servicio= limpiar_numero($tem_clase_servicio[0]);
						$clase_servicio = limpiar(borrar_caracteres($tem_clase_servicio[1]));
						
						$tem_plan_comercial = explode('-',borrar_caracteres($tramite[35]));
						$codigo_plan_comercial = limpiar_numero($tem_plan_comercial[0]);
						$plan_comercial = limpiar(borrar_caracteres($tem_plan_comercial[1]));						
						$tipo_cliente = limpiar(borrar_caracteres($tramite[36]));					
						
						$tem_stb_9001 =  explode(', ',trim(trim(borrar_caracteres($tramite[37])),','));
						$tem_cpe_821 =  explode(', ',trim(trim(borrar_caracteres($tramite[38])),','));
						$tem_enrutador_9005 =  explode(', ',trim(trim(borrar_caracteres($tramite[39])),','));
						$tem_ont_9012 =  explode(', ',trim(trim(borrar_caracteres($tramite[40])),','));
						$tem_umts_9000 =  explode(', ',trim(trim(borrar_caracteres($tramite[41])),','));
						$tem_extencion_486 =  explode(', ',trim(trim(borrar_caracteres($tramite[42])),','));
						$tem_imsi_9022 =  explode(', ',trim(trim(borrar_caracteres($tramite[43])),','));
						
						$voz = limpiar_numero($tramite[44]);
						$internet = limpiar_numero($tramite[45]);
						$television = limpiar_numero($tramite[46]);
						
						$descripcion_dano = "";
						$localizacion_dano = "";

						$cantidad_cpe	= limpiar_numero($tramite[47]);
						$cantidad_stbox = limpiar_numero($tramite[48]);
						
						$eess = 8;
						
					}
													
					if($ot && $tipo_trabajo && $localidad && $codigo_direccion && $fecha_obligatoria)
					{
									//LOCALIDAD
									$sql_bus_localidad = "SELECT id FROM  localidad WHERE codigo = '".$codigo_localidad."' LIMIT 1 ";
									$res_bus_localidad = mysql_query($sql_bus_localidad);
									$row_bus_localidad = mysql_fetch_array($res_bus_localidad);
									if($row_bus_localidad["id"])
									{	$id_localidad = $row_bus_localidad["id"];	}
									else
									{	$id_localidad = 229;	}
									
									
									// TRAMITE
									$sql_bus_ot = "SELECT id, cantidad_cpe, cantidad_stbox FROM  tramite WHERE ot = '".$ot."' LIMIT 1 ";
									$res_bus_ot = mysql_query($sql_bus_ot);
									$row_bus_ot = mysql_fetch_array($res_bus_ot);					
									if($row_bus_ot["id"])
									{
										$id_ot = $row_bus_ot["id"];
										
										if($eess == 8)
										{
											$sql_act = "UPDATE tramite SET 	ultimo = 's' WHERE id = '".$id_ot."' LIMIT 1 ";
											mysql_query($sql_act);
										}

										
										
										///////PARA BORRAR
										if( ($cantidad_cpe > $row_bus_ot["cantidad_cpe"]) || ( $cantidad_stbox > $row_bus_ot["cantidad_stbox"]) )
										{
											$sql_act = "UPDATE tramite SET 
											cantidad_cpe = '".$cantidad_cpe."', 
											cantidad_stbox = '".$cantidad_stbox."'
											WHERE id = '".$id_ot."' LIMIT 1 ";
											mysql_query($sql_act);
										}
										
										//////FIN PARA BORRAR
										
									}
									else
									{
										
										//TECNICO
										if($tecnico)
										{
											$sql_bus_tecnico = "SELECT id FROM  tecnico WHERE cedula = '".$cedula_tecnico."' LIMIT 1 ";
											$res_bus_tecnico = mysql_query($sql_bus_tecnico);
											$row_bus_tecnico = mysql_fetch_array($res_bus_tecnico);
											if($row_bus_tecnico["id"])
											{	$id_tecnico = "'".$row_bus_tecnico["id"]."'";	}
											else
											{
												$sql_ing_tecnico = "INSERT INTO `tecnico` (`codigo`, `cedula`, `nombre`) 
												VALUES ('".$codigo_tecnico."', '".$cedula_tecnico."', '".$tecnico."');";
												mysql_query($sql_ing_tecnico);
												$id_tecnico = "'".mysql_insert_id()."'";
											}
										}
										else
										{
											$id_tecnico =  ' NULL ';
										}
														
										//TIPO DE TRABAJO
										$sql_bus_tt = "SELECT id FROM  tipo_trabajo WHERE codigo = '".$tipo_trabajo."' LIMIT 1 ";
										$res_bus_tt = mysql_query($sql_bus_tt);
										$row_bus_tt = mysql_fetch_array($res_bus_tt);
										if($row_bus_tt["id"])
										{	$id_tt = $row_bus_tt["id"];	}
										else
										{	$id_tt = 117;	}						
										
										//TECNOLOGIA
										if($tecnologia)
										{
											$sql_bus_tec = "SELECT id FROM  tecnologia WHERE nombre = '".$tecnologia."' LIMIT 1 ";
											$res_bus_tec = mysql_query($sql_bus_tec);
											$row_bus_tec = mysql_fetch_array($res_bus_tec);
											if($row_bus_tec["id"])
											{	$id_tecnologia = "'".$row_bus_tec["id"]."'";	}
											else
											{	$id_tecnologia = "'7'";	}									
											$tecnologia_ins = "'".$tecnologia ."'";
										}
										else
										{
											$tecnologia_ins = "'Sin tecnologia'";
											$id_tecnologia = "'8'" ;
										}
										
										
																				
										/////BUSCAR ANTECESOR								
										
											$sql_antecesor = "SELECT fecha_atencion_orden, id, id_tipo_trabajo, producto FROM tramite WHERE
												(producto IN ('".$voz."','".$internet."','".$television."','".$producto."') OR 
												voz IN ('".$voz."','".$internet."','".$television."','".$producto."') OR 
												internet IN ('".$voz."','".$internet."','".$television."','".$producto."') OR 
												television IN ('".$voz."','".$internet."','".$television."','".$producto."')
											) AND 	codigo_unidad_operativa = '".$cuo."' AND solicitud <>  '".$solicitud."' AND descripcion_dano  NOT LIKE  '%o masivo%' AND descripcion_dano NOT LIKE  '%Infundado%'
											ORDER BY fecha_atencion_orden DESC LIMIT 1	";										
											$res_antecesor = mysql_query($sql_antecesor);
											$row_antecesor = mysql_fetch_array($res_antecesor);
										
										
										/////ULTIMO TRAMITES
										/*
										$sql_ult_tramite = "SELECT id FROM tramite WHERE solicitud = '".$solicitud."' AND fecha_atencion_orden > '".$fecha_atencion_orden."'";
										$res_ult_tramite = mysql_query($sql_ult_tramite);
										$row_ult_tramite = mysql_fetch_array($res_ult_tramite);
										if($row_ult_tramite["id"])
										{
											$ultimo = 'n';
										}
										else
										{
											$sql_ult = "UPDATE tramite SET ultimo = 'n' WHERE solicitud = '".$solicitud."' ";
											mysql_query($sql_ult);
											$ultimo = 's';
										}										
										*/
										if($eess != 8)
										{	$ultimo = 'n'; }
										else
										{
											$sql_ult = "UPDATE tramite SET ultimo = 'n' WHERE solicitud = '".$solicitud."' ";
											mysql_query($sql_ult);
											$ultimo = 's';
										}
										
																				
										
										if($voz == '1' || $voz == '0' || $voz == ''){$voz_g = "NULL";}else{$voz_g = "'".$voz."'";}	
										if($internet == '1' || $internet == '0' || $internet == ''){$internet_g = "NULL";}else{$internet_g = "'".$internet."'";}	
										if($television == '1' || $television == '0' || $television == ''){$television_g = "NULL";}else{$television_g = "'".$television."'";}	
										if(!$fecha_creacion_producto){$fecha_creacion_producto_g = " NULL ";}else{$fecha_creacion_producto_g = "'".$fecha_creacion_producto."'";}
										if(!$codigo_dano){$codigo_dano_g = " NULL ";}else{$codigo_dano_g = "'".$codigo_dano."'";}
																			
										//INGRESAR TRAMITE
										$sql_ing_ot = "INSERT INTO `tramite` (`id_tipo_trabajo`, `id_localidad`, `id_tecnico`, `ot`, `solicitud`, 
										`fecha_atencion`, `fecha_atencion_orden`, `fecha_reportada`, `fecha_asignacion`, `unidad_operativa`, 
										`codigo_unidad_operativa`, `codigo_tipo_paquete`, `tipo_paquete`, `tipo_producto`, `numero_servicio`, 
										 `location_id`, `departamento`, `region`, `zona`, `tecnologia`, `numero_orden`,
										`nombre_cliente`, `identificacion_cliente`, `contrato`, `direccion_codigo`, `direccion`, 
										 `tipo_cliente`, `fecha_registro`, `fecha_creacion_oden`, `producto`, `locaprod`, `id_tecnologia`,
										  `voz`, `internet`, `television`, `fecha_creacion_producto`, `codigo_dano`, `descripcion_dano`, `localizacion_dano`,
										  `cantidad_cpe`, `cantidad_stbox`, `ultimo`
										 ) 
										VALUES ('".$id_tt."', '".$id_localidad."', ".$id_tecnico.", '".$ot."', '".$solicitud."', 
										'".$fecha_atencion."', '".$fecha_atencion_orden."', '".$fecha_reportada."', '".$fecha_asignacion."', '".$unidad_operativa."', 
										'".$codigo_unidad_operativa."', '".$codigo_tipo_paquete."', '".$tipo_paquete."', '".$tipo_producto."', '".$numero_servicio."', 
										'".$location_id."', '".$departamento."', '".$region."', '".$zona."', ".$tecnologia_ins.",  '".$numero_orden."', 
										'".$cliente."', '".$identificacion_cliente."', '".$contrato."', '".$codigo_direccion."', '".$direccion."', 
										'".$tipo_cliente."', '".date("Y-m-d G-i-s")."', '".$fecha_creacion_oden."' , '".$producto."', '".$locaprod."' , ".$id_tecnologia.",
										".$voz_g." ,".$internet_g." ,".$television_g." ,".$fecha_creacion_producto_g." ,".$codigo_dano_g.", '".$descripcion_dano."', '".$localizacion_dano."',
										'".$cantidad_cpe."' ,'".$cantidad_stbox."' ,'".$ultimo."' );";
										mysql_query($sql_ing_ot);
										$id_ot = mysql_insert_id();	
										if($row_antecesor["id"])
										{
											// TIPO DE ANTECESOR
											$tipo_garantia ="";
											$sql_tipo_a = "SELECT tipo FROM tipo_trabajo WHERE id='".$row_antecesor["id_tipo_trabajo"]."' LIMIT 1 ";
											$res_tipo_a = mysql_query($sql_tipo_a);
											$row_tipo_a = mysql_fetch_array($res_tipo_a);
											if($row_tipo_a["tipo"]=='1' || $row_tipo_a["tipo"]=='7')
											{
												$tipo_garantia = 1;
											}
											else
											{
												if($row_antecesor["producto"]==$producto)
												{	$tipo_garantia = 2;	}
												else
												{	$tipo_garantia = 3;	}
											}											
											
											$sql_act = "UPDATE tramite SET 
											ot_antecesor = '".$row_antecesor["id"]."', fecha_ot_antecesor = '".$row_antecesor["fecha_atencion_orden"]."' , tipo_garantia='".$tipo_garantia."'
											WHERE id = '".$id_ot."' LIMIT 1 ";
											mysql_query($sql_act);
										}
									}
									
									
									if($id_ot)
									{
										
											//TECNOLOGIA
											$sql_bus_tecnologia = "SELECT id FROM  tramite_tecnologia WHERE nombre = '".$tecnologia."' AND id_tramite = '".$id_ot."'  LIMIT 1 ";
											$res_bus_tecnologia = mysql_query($sql_bus_tecnologia);
											$row_bus_tecnologia = mysql_fetch_array($res_bus_tecnologia);					
											if(!$row_bus_tecnologia["id"])
											{
												$sql_ing_tecnologia = "INSERT INTO `tramite_tecnologia` (`id_tramite`,  `nombre`) VALUES ( '".$id_ot."', '".$tecnologia."');";
												mysql_query($sql_ing_tecnologia);
											}						
											
											
											
											//PLAN COMERCIAL
											$sql_bus_comercial = "SELECT id FROM  tramite_comercial WHERE codigo = '".$codigo_plan_comercial."' AND id_tramite = '".$id_ot."'  LIMIT 1 ";
											$res_bus_comercial = mysql_query($sql_bus_comercial);
											$row_bus_comercial = mysql_fetch_array($res_bus_comercial);					
											if(!$row_bus_comercial["id"])
											{
												$sql_ing_comercial = "INSERT INTO `tramite_comercial` (`id_tramite`, `codigo`, `nombre`) VALUES ( '".$id_ot."', '".$codigo_plan_comercial."', '".$plan_comercial."');";
												mysql_query($sql_ing_comercial);
											}						
											
																		
											//CLASE DE SERVICIO
											$sql_bus_servicio = "SELECT id FROM  tramite_servicio WHERE codigo = '".$codigo_clase_servicio."' AND id_tramite = '".$id_ot."'  LIMIT 1 ";
											$res_bus_servicio = mysql_query($sql_bus_servicio);
											$row_bus_servicio = mysql_fetch_array($res_bus_servicio);					
											if(!$row_bus_servicio["id"])
											{
												$sql_ing_servicio = "INSERT INTO `tramite_servicio` (`id_tramite`, `codigo`, `nombre`) VALUES ( '".$id_ot."', '".$codigo_clase_servicio."', '".$clase_servicio."');";
												mysql_query($sql_ing_servicio);
											}	
											
											//MOTIVO
											$sql_bus_motivo = "SELECT id FROM  tramite_motivo WHERE codigo = '".$motivo."' AND id_tramite = '".$id_ot."'  LIMIT 1 ";
											$res_bus_motivo = mysql_query($sql_bus_motivo);
											$row_bus_motivo = mysql_fetch_array($res_bus_motivo);					
											if(!$row_bus_motivo["id"])
											{
												$sql_ing_motivo = "INSERT INTO `tramite_motivo` (`id_tramite`, `codigo`) VALUES ( '".$id_ot."', '".$motivo."');";
												mysql_query($sql_ing_motivo);
											}
											
											
											//MATERIAL
											if($codigo_material)
											{
												
												
												if($vec_mat[$codigo_material])
												{
													$codigo_material = $vec_mat[$codigo_material];
												}
												
												$sql_bus_material = "SELECT id FROM equipo_material WHERE codigo_1 = '".$codigo_material."' AND tipo=2 LIMIT 1";
												$res_bus_material = mysql_query($sql_bus_material);
												$row_bus_material = mysql_fetch_array($res_bus_material);
												if($row_bus_material["id"])
												{
													$id_material = $row_bus_material["id"];
													$sql_bus_material = "SELECT id FROM material_traza 
													WHERE id_equipo_material = '".$id_material."' AND id_tramite = '".$id_ot."' LIMIT 1";
													$res_bus_material = mysql_query($sql_bus_material);
													$row_bus_material = mysql_fetch_array($res_bus_material);
													if(!$row_bus_material["id"])
													{
														/////ALARMAR
														 $sql_alarma = "SELECT id FROM conf_alarma_equipo_material
														WHERE id_equipo_material = '".$id_material."' AND id_tecnologia = ".$id_tecnologia."
														AND ( 
																(cantidad_min <= '".$cantidad_material."' AND cantidad_max >= '".$cantidad_material."' AND cantidad_max IS NOT NULL) OR
																(cantidad_min <= '".$cantidad_material."' AND cantidad_max IS NULL)
															)
															ORDER BY tipo DESC LIMIT 1	";
														$res_alarma = mysql_query($sql_alarma);
														$row_alarma = mysql_fetch_array($res_alarma);
														if($row_alarma["id"])
														{
															$sql_ins_alarma = "INSERT INTO reporte_alarma_equipo_material (id_tramite, 	id_conf_equipo_material, fecha_registro, estado)
															VALUES ('".$id_ot."', '".$row_alarma["id"]."', '".date("Y-m-d G-i-s")."', '1')";
															mysql_query($sql_ins_alarma);
														}
														
														
														if($codigo_unidad_operativa==2000 || ($codigo_unidad_operativa==4000 && $id_material=='529')  || ($codigo_unidad_operativa==4000 && $id_material=='528')  || ($codigo_unidad_operativa==4000 && $id_material=='527')  || ($codigo_unidad_operativa==4000 && $id_material=='695')  )
														{
															$sql_bodega = "SELECT id_localidad, zona FROM material_bodega 
															WHERE codigo_localidad = '".$codigo_localidad."' LIMIT 1 ";
															$res_bodega = mysql_query($sql_bodega);
															$row_bodega = mysql_fetch_array($res_bodega);															
															if($row_bodega["id_localidad"] && ( !$row_bodega["zona"] || (  $id_material=='529' || $id_material=='528' || $id_material=='527' || $id_material=='695' )  ))
															{																
																$carga_bodega = "'".$row_bodega["id_localidad"]."'";
															}
															else
															{
																$carga_bodega = 'NULL';
															}
														}
														else
														{
															$carga_bodega = 'NULL';
														}
														
														$sql_ing_material = "INSERT INTO `material_traza` 
														(`id_equipo_material`, `id_localidad`, `id_tramite`, `cantidad`, `tipo`, `fecha`, `fecha_registro`, `id_localidad_carga`) 
														VALUES ( '".$id_material."', '".$id_localidad."', '".$id_ot."', '".$cantidad_material."', '1', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."', ".$carga_bodega.");";
														mysql_query($sql_ing_material);
														if(mysql_error())
														{
															$error = $error."<b>Linea ".$i.$sql_ing_materia."</b>: No se pudo ingresar este material a la base de datos<br>";
															$error_block = "Linea ".$i.": No se pudo ingresar este material a la base de datos -".mysql_error()."- (".$archivo.")";
															fwrite($file, $error_block.PHP_EOL);
														}
														
													} 
													
												}
												else
												{
													
													$sql_ing_material = "INSERT INTO `equipo_material` 
														(`codigo_1`, `nombre`, `tipo`) VALUES ( '".$codigo_material."', '".$nombre_material."', '2');";
													mysql_query($sql_ing_material);
													$id_material = mysql_insert_id();
													
													$sql_ing_material = "INSERT INTO `material_traza` 
													(`id_equipo_material`, `id_localidad`, `id_tramite`, `cantidad`, `tipo`, `fecha`, `fecha_registro`) 
													VALUES ( '".$id_material."', '".$id_localidad."', '".$id_ot."', '".$cantidad_material."', '1', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
													mysql_query($sql_ing_material);
													if(mysql_error())
													{
														$error = $error."<b>Linea ".$i."</b>: No se pudo ingresar este material a la base de datos<br>";
														$error_block = "Linea ".$i.": No se pudo ingresar este material a la base de datos -".mysql_error()."- (".$archivo.")";
														fwrite($file, $error_block.PHP_EOL);
													}
													
													
												}
											
											}

											//EQUIPOS
											//9001
											if($tem_stb_9001[0] )						
											{	
												foreach ($tem_stb_9001 as &$valor) 
												{
													$tem_material = explode('-',$valor);
													$id_equipo = '653';												
													$tem_material[1] = obtener_cadena($tem_material[1],'[',']');
													
													$sql_bus_serial = "SELECT equipo_material.id FROM serial_traza 
													INNER JOIN equipo_serial ON serial_traza.id_equipo_serial = equipo_serial.id 
													INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id 
													WHERE 	serial_traza.id_tramite = '".$id_ot."' AND equipo_material.id ='".$id_equipo."' AND equipo_serial.serial='".$tem_material[1]."'
													LIMIT 1	"; 
													$res_bus_serial = mysql_query($sql_bus_serial);
													$row_bus_serial = mysql_fetch_array($res_bus_serial);
													if(!$row_bus_serial)
													{
														$estado = '';
														if(trim($tem_material[0])=='I'){$estado ='2';}
														//if(trim($tem_material[0])=='U'){$estado ='1';}
														if(trim($tem_material[0])=='U'){$estado ='6';}
														if(trim($tem_material[0])=='A'){$estado ='5';}
														if(trim($tem_material[0])=='D'){$estado ='4';}
														
														$sql_bus_serial = "SELECT id, fecha_registro FROM equipo_serial 
														WHERE equipo_serial.serial='".$tem_material[1]."' AND id_equipo_material = '".$id_equipo."' LIMIT 1";
														$res_bus_serial = mysql_query($sql_bus_serial);
														$row_bus_serial = mysql_fetch_array($res_bus_serial);
														if($row_bus_serial["id"])
														{
															$id_serial = $row_bus_serial["id"];											
															
															if(strtotime($fecha_atencion) > strtotime($row_bus_serial["fecha_registro"]))
															{
																$sql_act_material ="UPDATE equipo_serial SET
																`id_equipo_material` =  '".$id_equipo."',
																`id_localidad` =  '".$id_localidad."',
																`id_tramite` =  '".$id_ot."',
																`serial` =  '".$tem_material[1]."',
																`estado` =  '".$estado."',
																`zona` =  '".$zona."',
																`region` =  '".$region."',
																`id_usuario` = NULL,
																`fecha_registro` =  '".$fecha_atencion."'
																WHERE id = '".$id_serial."' LIMIT 1	";
																mysql_query($sql_act_material);	
																
																$sql_act_traza ="UPDATE serial_traza SET
																`actual` =  'n'	WHERE 	id_equipo_serial = '".$id_serial."' LIMIT 1	";
																mysql_query($sql_act_traza);
																
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
																mysql_query($sql_ing_material);
															}
															else
															{
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`, `actual`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."', 'n');";
																mysql_query($sql_ing_material);
															}
															
																									
														}
														else
														{										
															$sql_ing_material = "INSERT INTO `equipo_serial` 
															(`id_equipo_material`, `id_localidad`, `id_tramite`, `serial`, `estado`, `zona`, `region`, `fecha_registro`) 
															VALUES ( '".$id_equipo."', '".$id_localidad."', '".$id_ot."', '".$tem_material[1]."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."');";
															mysql_query($sql_ing_material);	
															$id_serial = mysql_insert_id();
															
															if($id_serial)
															{
																															
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
																mysql_query($sql_ing_material);
																
															}
															else
															{
																$error_block = "Linea ".$i.": No se pudo ingresar el serial -".$tem_material[1]."- con codigo -".$id_equipo."- a la base de datos (".$archivo.")";
																fwrite($file, $error_block.PHP_EOL);
															}
														}
														
														
															
															
																											
													}					
												
												}	
											}								
											 //
											 
											 
											 
											 
											 //821
											if($tem_cpe_821[0])						
											{

												foreach ($tem_cpe_821 as &$valor) 
												{
													$tem_material = explode('-',$valor);
													$id_equipo = '645';
													$tem_material[1] = obtener_cadena($tem_material[1],'[',']');
													
													$sql_bus_serial = "SELECT equipo_material.id FROM serial_traza 
													INNER JOIN equipo_serial ON serial_traza.id_equipo_serial = equipo_serial.id 
													INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id 
													WHERE 	serial_traza.id_tramite = '".$id_ot."' AND equipo_material.id ='".$id_equipo."' AND equipo_serial.serial='".$tem_material[1]."'
													LIMIT 1	";
													$res_bus_serial = mysql_query($sql_bus_serial);
													$row_bus_serial = mysql_fetch_array($res_bus_serial);
													if(!$row_bus_serial)
													{
														$estado = '';
														if(trim($tem_material[0])=='I'){$estado ='2';}
														//if(trim($tem_material[0])=='U'){$estado ='1';}
														if(trim($tem_material[0])=='U'){$estado ='6';}
														if(trim($tem_material[0])=='A'){$estado ='5';}
														if(trim($tem_material[0])=='D'){$estado ='4';}
														
														$sql_bus_serial = "SELECT id, fecha_registro FROM equipo_serial 
														WHERE equipo_serial.serial='".$tem_material[1]."' AND id_equipo_material = '".$id_equipo."' LIMIT 1";
														$res_bus_serial = mysql_query($sql_bus_serial);
														$row_bus_serial = mysql_fetch_array($res_bus_serial);
														if($row_bus_serial["id"])
														{
															$id_serial = $row_bus_serial["id"];											
															
															if(strtotime($fecha_atencion) > strtotime($row_bus_serial["fecha_registro"]))
															{
																$sql_act_material ="UPDATE equipo_serial SET
																`id_equipo_material` =  '".$id_equipo."',
																`id_localidad` =  '".$id_localidad."',
																`id_tramite` =  '".$id_ot."',
																`serial` =  '".$tem_material[1]."',
																`estado` =  '".$estado."',
																`zona` =  '".$zona."',
																`region` =  '".$region."',
																`id_usuario` = NULL,
																`fecha_registro` =  '".$fecha_atencion."'
																WHERE id = '".$id_serial."' LIMIT 1	";
																mysql_query($sql_act_material);	
																
																$sql_act_traza ="UPDATE serial_traza SET
																`actual` =  'n'	WHERE 	id_equipo_serial = '".$id_serial."' LIMIT 1	";
																mysql_query($sql_act_traza);
																
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
																mysql_query($sql_ing_material);
															}
															else
															{
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`, `actual`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."', 'n');";
																mysql_query($sql_ing_material);
															}
															
																									
														}
														else
														{										
															$sql_ing_material = "INSERT INTO `equipo_serial` 
															(`id_equipo_material`, `id_localidad`, `id_tramite`, `serial`, `estado`, `zona`, `region`, `fecha_registro`) 
															VALUES ( '".$id_equipo."', '".$id_localidad."', '".$id_ot."', '".$tem_material[1]."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."');";
															mysql_query($sql_ing_material);	
															$id_serial = mysql_insert_id();
															
															if($id_serial)
															{
																															
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
																mysql_query($sql_ing_material);
																
															}
															else
															{
																$error_block = "Linea ".$i.": No se pudo ingresar el serial -".$tem_material[1]."- con codigo -".$id_equipo."- a la base de datos (".$archivo.")";
																fwrite($file, $error_block.PHP_EOL);
															}
														}
																										
													}					
												
												}
											}								
											//
											 
											//9005
											if($tem_enrutador_9005[0])						
											{
												foreach ($tem_enrutador_9005 as &$valor) 
												{
													$tem_material = explode('-',$valor);
													$id_equipo = '650';													
													$tem_material[1] = obtener_cadena($tem_material[1],'[',']');											
													
													$sql_bus_serial = "SELECT equipo_material.id FROM serial_traza 
													INNER JOIN equipo_serial ON serial_traza.id_equipo_serial = equipo_serial.id 
													INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id 
													WHERE 	serial_traza.id_tramite = '".$id_ot."' AND equipo_material.id ='".$id_equipo."' AND equipo_serial.serial='".$tem_material[1]."'
													LIMIT 1	";
													$res_bus_serial = mysql_query($sql_bus_serial);
													$row_bus_serial = mysql_fetch_array($res_bus_serial);
													if(!$row_bus_serial)
													{
														$estado = '';
														if(trim($tem_material[0])=='I'){$estado ='2';}
														//if(trim($tem_material[0])=='U'){$estado ='1';}
														if(trim($tem_material[0])=='U'){$estado ='6';}
														if(trim($tem_material[0])=='A'){$estado ='5';}
														if(trim($tem_material[0])=='D'){$estado ='4';}
														
														$sql_bus_serial = "SELECT id, fecha_registro FROM equipo_serial 
														WHERE equipo_serial.serial='".$tem_material[1]."' AND id_equipo_material = '".$id_equipo."' LIMIT 1";
														$res_bus_serial = mysql_query($sql_bus_serial);
														$row_bus_serial = mysql_fetch_array($res_bus_serial);
														if($row_bus_serial["id"])
														{
															$id_serial = $row_bus_serial["id"];											
															
															if(strtotime($fecha_atencion) > strtotime($row_bus_serial["fecha_registro"]))
															{
																$sql_act_material ="UPDATE equipo_serial SET
																`id_equipo_material` =  '".$id_equipo."',
																`id_localidad` =  '".$id_localidad."',
																`id_tramite` =  '".$id_ot."',
																`serial` =  '".$tem_material[1]."',
																`estado` =  '".$estado."',
																`zona` =  '".$zona."',
																`region` =  '".$region."',
																`id_usuario` = NULL,
																`fecha_registro` =  '".$fecha_atencion."'
																WHERE id = '".$id_serial."' LIMIT 1	";
																mysql_query($sql_act_material);	
																
																$sql_act_traza ="UPDATE serial_traza SET
																`actual` =  'n'	WHERE 	id_equipo_serial = '".$id_serial."' LIMIT 1	";
																mysql_query($sql_act_traza);
																
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
																mysql_query($sql_ing_material);
															}
															else
															{
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`, `actual`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."', 'n');";
																mysql_query($sql_ing_material);
															}
															
																									
														}
														else
														{										
															$sql_ing_material = "INSERT INTO `equipo_serial` 
															(`id_equipo_material`, `id_localidad`, `id_tramite`, `serial`, `estado`, `zona`, `region`, `fecha_registro`) 
															VALUES ( '".$id_equipo."', '".$id_localidad."', '".$id_ot."', '".$tem_material[1]."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."');";
															mysql_query($sql_ing_material);	
															$id_serial = mysql_insert_id();
															
															if($id_serial)
															{
																															
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
																mysql_query($sql_ing_material);
																
															}
															else
															{
																$error_block = "Linea ".$i.": No se pudo ingresar el serial -".$tem_material[1]."- con codigo -".$id_equipo."- a la base de datos (".$archivo.")";
																fwrite($file, $error_block.PHP_EOL);
															}
														}										
													}					
												
												}	
											}								
											 //
											 
											 
											 
											 //9012
											if($tem_ont_9012[0])						
											{
												foreach ($tem_ont_9012 as &$valor) 
												{
													$tem_material = explode('-',$valor);
													$id_equipo = '647';
													$tem_material[1] = obtener_cadena($tem_material[1],'[',']');
													
													$sql_bus_serial = "SELECT equipo_material.id FROM serial_traza 
													INNER JOIN equipo_serial ON serial_traza.id_equipo_serial = equipo_serial.id 
													INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id 
													WHERE 	serial_traza.id_tramite = '".$id_ot."' AND equipo_material.id ='".$id_equipo."' AND equipo_serial.serial='".$tem_material[1]."'
													LIMIT 1	";
													$res_bus_serial = mysql_query($sql_bus_serial);
													$row_bus_serial = mysql_fetch_array($res_bus_serial);
													if(!$row_bus_serial)
													{
														$estado = '';
														if(trim($tem_material[0])=='I'){$estado ='2';}
														//if(trim($tem_material[0])=='U'){$estado ='1';}
														if(trim($tem_material[0])=='U'){$estado ='6';}
														if(trim($tem_material[0])=='A'){$estado ='5';}
														if(trim($tem_material[0])=='D'){$estado ='4';}
														
														$sql_bus_serial = "SELECT id, fecha_registro FROM equipo_serial 
														WHERE equipo_serial.serial='".$tem_material[1]."' AND id_equipo_material = '".$id_equipo."' LIMIT 1";
														$res_bus_serial = mysql_query($sql_bus_serial);
														$row_bus_serial = mysql_fetch_array($res_bus_serial);
														if($row_bus_serial["id"])
														{
															$id_serial = $row_bus_serial["id"];											
															
															if(strtotime($fecha_atencion) > strtotime($row_bus_serial["fecha_registro"]))
															{
																$sql_act_material ="UPDATE equipo_serial SET
																`id_equipo_material` =  '".$id_equipo."',
																`id_localidad` =  '".$id_localidad."',
																`id_tramite` =  '".$id_ot."',
																`serial` =  '".$tem_material[1]."',
																`estado` =  '".$estado."',
																`zona` =  '".$zona."',
																`region` =  '".$region."',
																`id_usuario` = NULL,
																`fecha_registro` =  '".$fecha_atencion."'
																WHERE id = '".$id_serial."' LIMIT 1	";
																mysql_query($sql_act_material);	
																
																$sql_act_traza ="UPDATE serial_traza SET
																`actual` =  'n'	WHERE 	id_equipo_serial = '".$id_serial."' LIMIT 1	";
																mysql_query($sql_act_traza);
																
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
																mysql_query($sql_ing_material);
															}
															else
															{
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`, `actual`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."', 'n');";
																mysql_query($sql_ing_material);
															}
															
																									
														}
														else
														{										
															$sql_ing_material = "INSERT INTO `equipo_serial` 
															(`id_equipo_material`, `id_localidad`, `id_tramite`, `serial`, `estado`, `zona`, `region`, `fecha_registro`) 
															VALUES ( '".$id_equipo."', '".$id_localidad."', '".$id_ot."', '".$tem_material[1]."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."');";
															mysql_query($sql_ing_material);	
															$id_serial = mysql_insert_id();
															
															if($id_serial)
															{
																															
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
																mysql_query($sql_ing_material);
																
															}
															else
															{
																$error_block = "Linea ".$i.": No se pudo ingresar el serial -".$tem_material[1]."- con codigo -".$id_equipo."- a la base de datos (".$archivo.")";
																fwrite($file, $error_block.PHP_EOL);
															}
														}
														
													}					
												
												}							 
											}							
											//
											 
											//9000
											if($tem_umts_9000[0])						
											{
												foreach ($tem_umts_9000 as &$valor) 
												{
													$tem_material = explode('-',$valor);
													$id_equipo = '646';
													$tem_material[1] = obtener_cadena($tem_material[1],'[',']');
													
													
													$sql_bus_serial = "SELECT equipo_material.id FROM serial_traza 
													INNER JOIN equipo_serial ON serial_traza.id_equipo_serial = equipo_serial.id 
													INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id 
													WHERE 	serial_traza.id_tramite = '".$id_ot."' AND equipo_material.id ='".$id_equipo."' AND equipo_serial.serial='".$tem_material[1]."'
													LIMIT 1	";
													$res_bus_serial = mysql_query($sql_bus_serial);
													$row_bus_serial = mysql_fetch_array($res_bus_serial);
													if(!$row_bus_serial)
													{
														$estado = '';
														if(trim($tem_material[0])=='I'){$estado ='2';}
														//if(trim($tem_material[0])=='U'){$estado ='1';}
														if(trim($tem_material[0])=='U'){$estado ='6';}
														if(trim($tem_material[0])=='A'){$estado ='5';}
														if(trim($tem_material[0])=='D'){$estado ='4';}
														
														$sql_bus_serial = "SELECT id, fecha_registro FROM equipo_serial 
														WHERE equipo_serial.serial='".$tem_material[1]."' AND id_equipo_material = '".$id_equipo."' LIMIT 1";
														$res_bus_serial = mysql_query($sql_bus_serial);
														$row_bus_serial = mysql_fetch_array($res_bus_serial);
														if($row_bus_serial["id"])
														{
															$id_serial = $row_bus_serial["id"];											
															
															if(strtotime($fecha_atencion) > strtotime($row_bus_serial["fecha_registro"]))
															{
																$sql_act_material ="UPDATE equipo_serial SET
																`id_equipo_material` =  '".$id_equipo."',
																`id_localidad` =  '".$id_localidad."',
																`id_tramite` =  '".$id_ot."',
																`serial` =  '".$tem_material[1]."',
																`estado` =  '".$estado."',
																`zona` =  '".$zona."',
																`region` =  '".$region."',
																`id_usuario` = NULL,
																`fecha_registro` =  '".$fecha_atencion."'
																WHERE id = '".$id_serial."' LIMIT 1	";
																mysql_query($sql_act_material);	
																
																$sql_act_traza ="UPDATE serial_traza SET
																`actual` =  'n'	WHERE 	id_equipo_serial = '".$id_serial."' LIMIT 1	";
																mysql_query($sql_act_traza);
																
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
																mysql_query($sql_ing_material);
															}
															else
															{
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`, `actual`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."', 'n');";
																mysql_query($sql_ing_material);
															}
															
																									
														}
														else
														{										
															$sql_ing_material = "INSERT INTO `equipo_serial` 
															(`id_equipo_material`, `id_localidad`, `id_tramite`, `serial`, `estado`, `zona`, `region`, `fecha_registro`) 
															VALUES ( '".$id_equipo."', '".$id_localidad."', '".$id_ot."', '".$tem_material[1]."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."');";
															mysql_query($sql_ing_material);	
															$id_serial = mysql_insert_id();
															
															if($id_serial)
															{
																															
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
																mysql_query($sql_ing_material);
																
															}
															else
															{
																$error_block = "Linea ".$i.": No se pudo ingresar el serial -".$tem_material[1]."- con codigo -".$id_equipo."- a la base de datos (".$archivo.")";
																fwrite($file, $error_block.PHP_EOL);
															}
														}
													}					
												
												}							 
											}							
											//							 
											 
											 
											 //486
											if($tem_extencion_486[0])						
											{
												foreach ($tem_extencion_486 as &$valor) 
												{
													$tem_material = explode('-',$valor);
													$id_equipo = '654';
													$tem_material[1] = obtener_cadena($tem_material[1],'[',']');
													
													$sql_bus_serial = "SELECT equipo_material.id FROM serial_traza 
													INNER JOIN equipo_serial ON serial_traza.id_equipo_serial = equipo_serial.id 
													INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id 
													WHERE 	serial_traza.id_tramite = '".$id_ot."' AND equipo_material.id ='".$id_equipo."' AND equipo_serial.serial='".$tem_material[1]."'
													LIMIT 1	";
													$res_bus_serial = mysql_query($sql_bus_serial);
													$row_bus_serial = mysql_fetch_array($res_bus_serial);
													if(!$row_bus_serial)
													{
														$estado = '';
														if(trim($tem_material[0])=='I'){$estado ='2';}
														//if(trim($tem_material[0])=='U'){$estado ='1';}
														if(trim($tem_material[0])=='U'){$estado ='6';}
														if(trim($tem_material[0])=='A'){$estado ='5';}
														if(trim($tem_material[0])=='D'){$estado ='4';}
														
														$sql_bus_serial = "SELECT id, fecha_registro FROM equipo_serial 
														WHERE equipo_serial.serial='".$tem_material[1]."' AND id_equipo_material = '".$id_equipo."' LIMIT 1";
														$res_bus_serial = mysql_query($sql_bus_serial);
														$row_bus_serial = mysql_fetch_array($res_bus_serial);
														if($row_bus_serial["id"])
														{
															$id_serial = $row_bus_serial["id"];											
															
															if(strtotime($fecha_atencion) > strtotime($row_bus_serial["fecha_registro"]))
															{
																$sql_act_material ="UPDATE equipo_serial SET
																`id_equipo_material` =  '".$id_equipo."',
																`id_localidad` =  '".$id_localidad."',
																`id_tramite` =  '".$id_ot."',
																`serial` =  '".$tem_material[1]."',
																`estado` =  '".$estado."',
																`zona` =  '".$zona."',
																`region` =  '".$region."',
																`id_usuario` = NULL,
																`fecha_registro` =  '".$fecha_atencion."'
																WHERE id = '".$id_serial."' LIMIT 1	";
																mysql_query($sql_act_material);	
																
																$sql_act_traza ="UPDATE serial_traza SET
																`actual` =  'n'	WHERE 	id_equipo_serial = '".$id_serial."' LIMIT 1	";
																mysql_query($sql_act_traza);
																
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
																mysql_query($sql_ing_material);
															}
															else
															{
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`, `actual`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."', 'n');";
																mysql_query($sql_ing_material);
															}
															
																									
														}
														else
														{										
															$sql_ing_material = "INSERT INTO `equipo_serial` 
															(`id_equipo_material`, `id_localidad`, `id_tramite`, `serial`, `estado`, `zona`, `region`, `fecha_registro`) 
															VALUES ( '".$id_equipo."', '".$id_localidad."', '".$id_ot."', '".$tem_material[1]."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."');";
															mysql_query($sql_ing_material);	
															$id_serial = mysql_insert_id();
															
															if($id_serial)
															{
																															
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
																mysql_query($sql_ing_material);
																
															}
															else
															{
																$error_block = "Linea ".$i.": No se pudo ingresar el serial -".$tem_material[1]."- con codigo -".$id_equipo."- a la base de datos (".$archivo.")";
																fwrite($file, $error_block.PHP_EOL);
															}
														}				
													}					
												
												}
											}								
											 //							 
											
											
											
											//9022
											if($tem_imsi_9022[0])
											{
												foreach ($tem_imsi_9022 as &$valor) 
												{
													$tem_material = explode('-',$valor);
													$id_equipo = '657';
													$tem_material[1] = obtener_cadena($tem_material[1],'[',']');
																						
													$sql_bus_serial = "SELECT equipo_material.id FROM serial_traza 
													INNER JOIN equipo_serial ON serial_traza.id_equipo_serial = equipo_serial.id 
													INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id 
													WHERE 	serial_traza.id_tramite = '".$id_ot."' AND equipo_material.id ='".$id_equipo."' AND equipo_serial.serial='".$tem_material[1]."'
													LIMIT 1	";
													$res_bus_serial = mysql_query($sql_bus_serial);
													$row_bus_serial = mysql_fetch_array($res_bus_serial);
													if(!$row_bus_serial)
													{
														$estado = '';
														if(trim($tem_material[0])=='I'){$estado ='2';}
														//if(trim($tem_material[0])=='U'){$estado ='1';}
														if(trim($tem_material[0])=='U'){$estado ='6';}
														if(trim($tem_material[0])=='A'){$estado ='5';}
														if(trim($tem_material[0])=='D'){$estado ='4';}
														
														$sql_bus_serial = "SELECT id, fecha_registro FROM equipo_serial 
														WHERE equipo_serial.serial='".$tem_material[1]."' AND id_equipo_material = '".$id_equipo."' LIMIT 1";
														$res_bus_serial = mysql_query($sql_bus_serial);
														$row_bus_serial = mysql_fetch_array($res_bus_serial);
														if($row_bus_serial["id"])
														{
															$id_serial = $row_bus_serial["id"];											
															
															if(strtotime($fecha_atencion) > strtotime($row_bus_serial["fecha_registro"]))
															{
																$sql_act_material ="UPDATE equipo_serial SET
																`id_equipo_material` =  '".$id_equipo."',
																`id_localidad` =  '".$id_localidad."',
																`id_tramite` =  '".$id_ot."',
																`serial` =  '".$tem_material[1]."',
																`estado` =  '".$estado."',
																`zona` =  '".$zona."',
																`region` =  '".$region."',
																`id_usuario` = NULL,
																`fecha_registro` =  '".$fecha_atencion."'
																WHERE id = '".$id_serial."' LIMIT 1	";
																mysql_query($sql_act_material);	
																
																$sql_act_traza ="UPDATE serial_traza SET
																`actual` =  'n'	WHERE 	id_equipo_serial = '".$id_serial."' LIMIT 1	";
																mysql_query($sql_act_traza);
																
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
																mysql_query($sql_ing_material);
															}
															else
															{
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`, `actual`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."', 'n');";
																mysql_query($sql_ing_material);
															}
															
																									
														}
														else
														{										
															$sql_ing_material = "INSERT INTO `equipo_serial` 
															(`id_equipo_material`, `id_localidad`, `id_tramite`, `serial`, `estado`, `zona`, `region`, `fecha_registro`) 
															VALUES ( '".$id_equipo."', '".$id_localidad."', '".$id_ot."', '".$tem_material[1]."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."');";
															mysql_query($sql_ing_material);	
															$id_serial = mysql_insert_id();
															
															if($id_serial)
															{
																															
																$sql_ing_material = "INSERT INTO `serial_traza` 
																(`id_equipo_serial`, `id_localidad`, `id_tramite`, `estado`, `zona`, `region`, `fecha`, `fecha_registro`) 
																VALUES ( '".$id_serial."', '".$id_localidad."', '".$id_ot."', '".$estado."', '".$zona."', '".$region."', '".$fecha_atencion."', '".date("Y-m-d G:i:s")."');";
																mysql_query($sql_ing_material);
																
															}
															else
															{
																$error_block = "Linea ".$i.": No se pudo ingresar el serial -".$tem_material[1]."- con codigo -".$id_equipo."- a la base de datos (".$archivo.")";
																fwrite($file, $error_block.PHP_EOL);
															}
														}					
													}					
												
												}							 
											}
											 //
											 
										
									}
									else
									{
										$error = $error."<b>Linea ".$i."</b>: No se pudo ingresar el tramite a la base de datos<br>";
										$error_block = "Linea ".$i.": No se pudo ingresar el tramite a la base de datos - ".mysql_error()." - (".$archivo.")";
										fwrite($file, $error_block.PHP_EOL);
										
									}
											
					}				
					else
					{
						$error = $error."<b>Linea ".$i."</b>: Informacion Incompleta<br>";
						$error_block = "Linea ".$i.": Datos incompletos(".$archivo.")";
						fwrite($file, $error_block.PHP_EOL);
						
					}
					
					
				}
				
				
			$i++;	
			}
			$sql_fecha = "UPDATE registro_fecha SET tramite_instalacion = '".$ult_carga."' WHERE id='1' LIMIT 1";
			mysql_query($sql_fecha);
			
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

<h2>CARGA DE TRAMITES </h2>

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