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
	
			$sqlt = "SELECT * FROM tramite WHERE ultimo='s' ORDER BY fecha_atencion_orden ASC";
			$rest = mysql_query($sqlt);
			while ($rowt = mysql_fetch_array($rest)) 
			{								
					$id_ot = '';					
					$solicitud = limpiar_numero($tramite[0]);
					$ot = limpiar_numero($tramite[1]);
					$numero_orden = limpiar_numero($tramite[2]);					
										 
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
						
						
						$voz = limpiar_numero($tramite[44]);
						$internet = limpiar_numero($tramite[45]);
						$television = limpiar_numero($tramite[46]);
						
						$descripcion_dano = limpiar($tramite[47]);
						$localizacion_dano = limpiar($tramite[48]);
						
						$cantidad_cpe	 = 0;
						$cantidad_stbox = 0;
					
				
													
					if($ot && $tipo_trabajo && $localidad && $codigo_direccion && $fecha_obligatoria)
					{
																
									
									// TRAMITE
									$sql_bus_ot = "SELECT id, cantidad_cpe, cantidad_stbox FROM  tramite WHERE ot = '".$ot."' LIMIT 1 ";
									$res_bus_ot = mysql_query($sql_bus_ot);
									$row_bus_ot = mysql_fetch_array($res_bus_ot);					
									if($row_bus_ot["id"])
									{
										$id_ot = $row_bus_ot["id"];										
									}
									else
									{
										
																	
										/////BUSCAR ANTECESOR					
										
											$sql_antecesor = "SELECT fecha_atencion_orden, id, id_tipo_trabajo, producto FROM tramite WHERE
												(producto IN ('".$voz."','".$internet."','".$television."','".$producto."') OR 
												voz IN ('".$voz."','".$internet."','".$television."','".$producto."') OR 
												internet IN ('".$voz."','".$internet."','".$television."','".$producto."') OR 
												television IN ('".$voz."','".$internet."','".$television."','".$producto."')
											) AND 	codigo_unidad_operativa = '".$cuo."' AND solicitud <>  '".$solicitud."' AND descripcion_dano  NOT LIKE  '%o masivo%' 
											
											ORDER BY fecha_atencion_orden DESC LIMIT 1	";										
											$res_antecesor = mysql_query($sql_antecesor);
											$row_antecesor = mysql_fetch_array($res_antecesor);
										
																													
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
									
									
										
					}				
					else
					{
						$error = $error."<b>Linea ".$i."</b>: Informacion Incompleta<br>";
						$error_block = "Linea ".$i.": Datos incompletos(".$archivo.")";
						fwrite($file, $error_block.PHP_EOL);
						
					}
					
					
				
				
				
			$i++;	
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
