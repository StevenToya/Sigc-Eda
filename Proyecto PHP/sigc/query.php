<?php
$cuo = '2000';

$sql_inc = "SELECT valor FROM incremento WHERE ano = '".date("Y")."' LIMIT 1";
$res_inc = mysql_query($sql_inc);
$row_inc = mysql_fetch_array($res_inc);

$incrementado = $row_inc["valor"];

function tramite_liquidacion($select, $where, $select_sup, $where_sup, $tipo)
{
	global $cuo;
	global $incrementado;
	
	if(!$select){$select = " * ";}
	if(!$where){$where = " 1=1 ";}	
						
					

					
	if($tipo == 1)
	{				
		$sql = "SELECT 
					SUM(total_total) AS valor_total ".$select_sup."
				FROM( ";
	}
	
	if($tipo == 2)
	{				
		$sql = "";
	}


	
	$sql = $sql." SELECT tramite.id AS tramite_principal, tramite.departamento AS tem_zona, tramite.id_tipo_trabajo AS tem_tt, ot_antecesor AS idantecesor,
	 tramite.fecha_atencion_orden AS tem_fecha, tramite.direccion_codigo AS tem_dirid, fecha_ot_antecesor AS tem_fecha2, tipo_trabajo.tipo AS tem_tipo_trabajo,
	 cantidad_cpe AS cantidad_cpe2, cantidad_stbox AS cantidad_stbox2,
			
			 IFNULL((SELECT  			
					IF(tem_tipo_trabajo=7, IF( ( (IFNULL(cantidad_cpe2,0)+IFNULL(cantidad_stbox2,0))-2)<0,0, (IFNULL(cantidad_cpe2,0)+IFNULL(cantidad_stbox2,0))-2  ),								
							IF(tramite.id_tipo_trabajo IN ('38','108'),
									COUNT(*),
									IF((COUNT(*) - 2)<0,0,(COUNT(*) - 2) )					
								) 				
				     )				
					FROM `tramite`					
					INNER JOIN serial_traza
					  ON tramite.id = serial_traza.id_tramite
					INNER JOIN equipo_serial
					  ON serial_traza.id_equipo_serial = equipo_serial.id
					WHERE 
						tramite.id = tramite_principal AND
						(
							(equipo_serial.id_equipo_material IN ('645','653','647')  AND serial_traza.estado ='2' ) OR tipo_trabajo.tipo=7
						)
			),0) AS tem_extension, 

			IFNULL((SELECT  			
					IF(tem_tipo_trabajo=7, IF( ( (IFNULL(cantidad_cpe2,0)+IFNULL(cantidad_stbox2,0))-2)<0,0, (IFNULL(cantidad_cpe2,0)+IFNULL(cantidad_stbox2,0))-2  ),								
							IF(tramite.id_tipo_trabajo IN ('38','108'),
									COUNT(*),
									IF((COUNT(*) - 2)<0,0,(COUNT(*) - 2) )					
								) 				
				     )				
					FROM `tramite`					
					INNER JOIN serial_traza
					  ON tramite.id = serial_traza.id_tramite
					INNER JOIN equipo_serial
					  ON serial_traza.id_equipo_serial = equipo_serial.id
					WHERE 
						tramite.id = tramite_principal AND
						(
							(equipo_serial.id_equipo_material IN ('645','653','647')  AND serial_traza.estado ='2' ) OR tipo_trabajo.tipo=7
						)
			),0) AS tem_adicional,
			

			IFNULL((SELECT  
			IF(tem_tipo_trabajo=7, IFNULL(cantidad_cpe2,0) ,COUNT(*))			 
			FROM `tramite` 
			INNER JOIN serial_traza
			  ON tramite.id = serial_traza.id_tramite
			INNER JOIN equipo_serial
			  ON serial_traza.id_equipo_serial = equipo_serial.id
			WHERE tramite.id = tramite_principal AND
			equipo_serial.id_equipo_material IN ('645','647')  AND 
			serial_traza.estado ='2'  
			),0) AS cpe_xdsl,
			
			IFNULL((SELECT  
			IF(tem_tipo_trabajo=7, IFNULL(cantidad_stbox2,0) ,COUNT(*)) 
			FROM `tramite` 
			INNER JOIN serial_traza
			  ON tramite.id = serial_traza.id_tramite
			INNER JOIN equipo_serial
			  ON serial_traza.id_equipo_serial = equipo_serial.id
			WHERE tramite.id = tramite_principal AND
			equipo_serial.id_equipo_material = '653'  AND 
			serial_traza.estado ='2'  
			),0) AS stbox,
		
		
			
			(((IFNULL((SELECT liquidacion_caja_extension.valor
					FROM liquidacion_caja_extension
					INNER JOIN zona ON
						liquidacion_caja_extension.id_zona = zona.id	
					WHERE 
						zona.nombre = tem_zona AND
						liquidacion_caja_extension.id_tipo_trabajo = tem_tt AND
						liquidacion_caja_extension.caja_adicional = tem_adicional AND
						liquidacion_caja_extension.extension = tem_extension		
					 LIMIT 1
				),0) +  liquidacion_zona.valor
				
				) *		(SELECT IF(
									(datediff(tem_fecha,tem_fecha2)<31 AND tem_tipo_trabajo = 3)
									OR descripcion_dano  LIKE  '%o masivo%' 
									OR descripcion_dano  LIKE  '%Infundado%'
									,0,1
									)
								  )
			) *  ".$incrementado.")	AS total_total,			
			
			(SELECT IF(datediff(tem_fecha,tem_fecha2)<31 AND tem_tipo_trabajo = 3 ,idantecesor,'SIN GARANTIA')) AS garantia			
			
			".$select."		
			FROM tramite
			LEFT JOIN tipo_trabajo ON 
				tramite.id_tipo_trabajo = tipo_trabajo.id
			LEFT JOIN localidad ON 
				tramite.id_localidad = localidad.id
			
			LEFT JOIN zona ON 
				tramite.departamento = zona.nombre
			LEFT JOIN liquidar_tramite ON 
				tramite.id = liquidar_tramite.id_tramite
			LEFT JOIN tecnologia ON 
				tramite.id_tecnologia = tecnologia.id
			LEFT JOIN tecnico ON 
				tramite.id_tecnico = tecnico.id
			LEFT JOIN liquidacion_zona ON 
				zona.id = liquidacion_zona.id_zona AND
				tramite.id_tecnologia = liquidacion_zona.id_tecnologia	AND	
				tramite.id_tipo_trabajo = liquidacion_zona.id_tipo_trabajo 
			WHERE  tramite.codigo_unidad_operativa = '".$cuo."' AND ultimo='s' AND ".$where."  ";
			
		if($tipo == 1)
		{
			$sql = $sql." ) AS temporal ".$where_sup;

		}	
		//echo $sql;
		$res = mysql_query($sql);
		return $res;
}


function tramite_liquidacion_individual($id_ot)
{
	global $incrementado;	
	
	$sql = "SELECT  tramite.ot , tramite.fecha_atencion_orden , tramite.tipo_paquete , tramite.tipo_producto , tipo_trabajo.nombre AS nom_tt, tipo_trabajo.codigo AS cod_tt,
	tramite.numero_servicio , tramite.tecnologia , tramite.departamento , tramite.region , tramite.zona , tramite.nombre_cliente, tramite.codigo_dano, tramite.solicitud, tipo_garantia,
	tramite.contrato , tramite.direccion ,tramite.tipo_cliente ,tramite.numero_orden ,tramite.valor_liquidado, tecnologia.nombre AS nom_tecnologia, tipo_trabajo.tipo AS tem_tipo_trabajo,
	liquidacion_zona.valor, (liquidacion_zona.valor * ".$incrementado.") AS valor_basico, tecnico.nombre AS nom_tecnico, tecnico.codigo AS cod_tecnico, localidad.nombre AS nom_localidad, ot_antecesor, tramite.producto, tramite.	unidad_operativa,
	tramite.id AS tramite_principal, tramite.departamento AS tem_zona, tramite.id_tipo_trabajo AS tem_tt,  tipo_trabajo.tipo AS ttt, codigo_unidad_operativa,
	tramite.fecha_atencion_orden AS tem_fecha, tramite.direccion_codigo AS tem_dirid, contratista_valor, observacion_contratista, fecha_ot_antecesor AS tem_fecha2,
	tramite.fecha_reportada, tramite.descripcion_dano, cantidad_cpe AS cantidad_cpe2, cantidad_stbox AS cantidad_stbox2,
		IFNULL((SELECT  
			IF(tem_tipo_trabajo=7, IF( ( (IFNULL(cantidad_cpe2,0)+IFNULL(cantidad_stbox2,0))-2)<0,0, (IFNULL(cantidad_cpe2,0)+IFNULL(cantidad_stbox2,0))-2  ),
					IF(tramite.id_tipo_trabajo IN ('38','108'),
							COUNT(*),
							IF((COUNT(*) - 2)<0,0,(COUNT(*) - 2) )					
						)  
			)
		FROM `tramite` 
		INNER JOIN serial_traza
		  ON tramite.id = serial_traza.id_tramite
		INNER JOIN equipo_serial
		  ON serial_traza.id_equipo_serial = equipo_serial.id
		WHERE tramite.id = tramite_principal AND
		 (
			(equipo_serial.id_equipo_material IN ('645','653','647')  AND serial_traza.estado ='2' ) OR tem_tipo_trabajo=7
		 )
		),0) AS tem_extension, 

		IFNULL((SELECT  
			IF(tem_tipo_trabajo=7, IF( ( (IFNULL(cantidad_cpe2,0)+IFNULL(cantidad_stbox2,0))-2)<0,0, (IFNULL(cantidad_cpe2,0)+IFNULL(cantidad_stbox2,0))-2  ),
					IF(tramite.id_tipo_trabajo IN ('38','108'),
							COUNT(*),
							IF((COUNT(*) - 2)<0,0,(COUNT(*) - 2) )					
						)  
			)
		FROM `tramite` 
		INNER JOIN serial_traza
		  ON tramite.id = serial_traza.id_tramite
		INNER JOIN equipo_serial
		  ON serial_traza.id_equipo_serial = equipo_serial.id
		WHERE tramite.id = tramite_principal AND
		 (
			(equipo_serial.id_equipo_material IN ('645','653','647')  AND serial_traza.estado ='2' ) OR tem_tipo_trabajo=7
		 )
		),0) AS tem_adicional,
			
		
		(IFNULL((SELECT 
		liquidacion_caja_extension.valor
			FROM liquidacion_caja_extension
			INNER JOIN zona ON
				liquidacion_caja_extension.id_zona = zona.id
			WHERE 
				zona.nombre = tem_zona AND
				liquidacion_caja_extension.id_tipo_trabajo = tem_tt AND
				liquidacion_caja_extension.caja_adicional = tem_adicional AND
				liquidacion_caja_extension.extension = tem_extension		
			 LIMIT 1
		),0)  *  ".$incrementado.")AS total_adicion_estencion,
		
		(((IFNULL((SELECT 
		liquidacion_caja_extension.valor
			FROM liquidacion_caja_extension
			INNER JOIN zona ON
				liquidacion_caja_extension.id_zona = zona.id
			WHERE 
				zona.nombre = tem_zona AND
				liquidacion_caja_extension.id_tipo_trabajo = tem_tt AND
				liquidacion_caja_extension.caja_adicional = tem_adicional AND
				liquidacion_caja_extension.extension = tem_extension		
			 LIMIT 1
		),0) +  liquidacion_zona.valor)*
		
		(SELECT IF(	
					(datediff(tem_fecha,tem_fecha2)<31 AND ttt = 3)
					OR descripcion_dano  LIKE  '%o masivo%' 
					OR descripcion_dano  LIKE  '%Infundado%'
				,0,1
				)
			  )
		
		)  *  ".$incrementado.") AS total_total
	
		FROM tramite
		LEFT JOIN tipo_trabajo ON 
			tramite.id_tipo_trabajo = tipo_trabajo.id
		LEFT JOIN zona ON 
			tramite.departamento = zona.nombre
		LEFT JOIN localidad ON 
			tramite.id_localidad = localidad.id
		LEFT JOIN liquidar_tramite ON 
			tramite.id = liquidar_tramite.id_tramite
		LEFT JOIN tecnologia ON 
			tramite.id_tecnologia = tecnologia.id
		LEFT JOIN tecnico ON 
			tramite.id_tecnico = tecnico.id
		LEFT JOIN liquidacion_zona ON 
			zona.id = liquidacion_zona.id_zona AND
			tramite.id_tecnologia = liquidacion_zona.id_tecnologia	AND	
			tramite.id_tipo_trabajo = liquidacion_zona.id_tipo_trabajo 
		WHERE tramite.id = '".$id_ot."' LIMIT 1 ; ";	
		//echo $sql;
		$res = mysql_query($sql);
		return $res;
}


?>

