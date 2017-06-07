<?php


include("../../cnx.php");


function mes($mes)
{
	if($mes == 1){return ' ENERO';}
	if($mes == 2){return ' FEBRERO';}
	if($mes == 3){return ' MARZO';}
	if($mes == 4){return ' ABRIL';}
	if($mes == 5){return ' MAYO';}
	if($mes == 6){return ' JUNIO';}
	if($mes == 7){return ' JULIO';}
	if($mes == 8){return ' AGOSTO';}
	if($mes == 9){return ' SEPTIEMBRE';}
	if($mes == 10){return ' OCTUBRE';}
	if($mes == 11){return ' NOVIEMBRE';}
	if($mes == 12){return ' DICIEMBRE';} 
}


	/*
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte_material_".mes($_GET["mes"])."_".$_GET["ayo"].".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	*/
	
		
	$sql = "SELECT aud_base.nombre AS nom_base, usuario.nombre AS nom_usuario, usuario.apellido AS ape_usuario, aud_solicitud.estado, aud_solicitud.id,
		aud_solicitud.fecha_registro, tramite.ot, tramite.id AS offline, sra_tramite.id AS online, sra_tramite.pedido, tecnico.nombre AS nom_tecnico,
		une_pedido.id AS offlinehfc, une_pedido.numero, une_pedido.producto_homologado, une_pedido.proceso, une_pedido.empresa, aud_solicitud.fecha_inicio,
		aud_solicitud.fecha_finalizado, localidad.nombre AS nom_localidad, 	une_pedido.tecnologia,	une_pedido.producto_homologado, une_pedido.ciudad
		FROM aud_solicitud 
		LEFT JOIN aud_base ON aud_solicitud.id_base = aud_base.id
		LEFT JOIN sra_tramite ON aud_solicitud.id_sra_tramite = sra_tramite.id
		LEFT JOIN une_pedido ON aud_solicitud.id_une_pedido = une_pedido.id
		LEFT JOIN tramite ON aud_solicitud.id_tramite = tramite.id
		LEFT JOIN localidad ON tramite.id_localidad = localidad.id
		LEFT JOIN tecnico ON tramite.id_tecnico = tecnico.id
		LEFT JOIN usuario ON aud_solicitud.id_realizado = usuario.id				
		WHERE aud_solicitud.fecha_registro >= '".$_GET["ayo"]."-".$_GET["mes"]."-01 00:00:00' AND 
		aud_solicitud.fecha_registro <= '".$_GET["ayo"]."-".$_GET["mes"]."-31 23:59:59'
		ORDER BY aud_solicitud.fecha_registro DESC";
		
		?>
		<table border=1>
			<tr>
				 <th>Pedido</th>
				 <th>EDATEL-TIGOUNE</th>
				 <th>Ciudad Empresa</th>
				 <th>Nombre Funcionario</th>				
				 <th>Tecnología Homologada</th>
				  <th>Tecnologia</th>
				 <th>Proceso</th>				
				 <th>Empresa</th>
				 <th>Nombre material</th>
				 <th>Cantidad material tecnico</th>
				 <th>Cantidad material auditor</th>
				 <th>Cumple con los materiales y sus cantidades</th>
				 <th>Acompañamiento del contrato</th>
				 <th>Autorización ingreso residencia</th>
				 <th>Local cerrado</th>
				 <th>Auditor</th>
				 <th>Nombre supervisor</th>
				<th>Observaciones del material</th>
				<th>Fecha Inicio Auditoria</th>
				<th>Fecha fin Auditoria</th>			
			</tr>
		<?php
		$res = mysql_query($sql);
		while($row = mysql_fetch_array($res))
		{
			$sql_material = "SELECT respuesta FROM  `aud_solicitud_material` WHERE id_solicitud = '".$row["id"]."' LIMIT 1";
			$res_material = mysql_query($sql_material);
			$row_material = mysql_fetch_array($res_material);			
			$resp = ""; 
			if(!$row_material["respuesta"]){ $resp = ""; }
			if($row_material["respuesta"]=='s'){ $resp = "<font color=green>Si cumple</font>"; }
			if($row_material["respuesta"]=='n'){ $resp = "<font color=red>No cumple</font>"; }
			
			
			$sql_material_acom = "SELECT si_no 
				FROM  `aud_solicitud_item` 
				INNER JOIN aud_item ON aud_solicitud_item.id_item = aud_item.id
				WHERE 
					aud_solicitud_item.id_solicitud = '".$row["id"]."'
					AND aud_item.id_categoria = 59
				   AND aud_item.estado=1
				   AND `pregunta` LIKE  '%Acompa&ntilde;amiento%'
				   AND aud_item.tipo = '1' LIMIT 1;";
			$res_material_acom = mysql_query($sql_material_acom);
			$row_material_acom = mysql_fetch_array($res_material_acom);
			$resp_acom = ""; 
			if(!$row_material_acom["si_no"]){ $resp_acom = ""; }
			if($row_material_acom["si_no"]=='s'){ $resp_acom = "<font color=green>Si cumple</font>"; }
			if($row_material_acom["si_no"]=='n'){ $resp_acom = "<font color=red>No cumple</font>"; }
			
			$sql_material_aut = "SELECT si_no 
				FROM  `aud_solicitud_item` 
				INNER JOIN aud_item ON aud_solicitud_item.id_item = aud_item.id
				WHERE 
					aud_solicitud_item.id_solicitud = '".$row["id"]."'
					AND aud_item.id_categoria = 59
				   AND aud_item.estado=1
				   AND `pregunta` LIKE  '%Autorizaci&oacute;n%'
				   AND aud_item.tipo = '1' LIMIT 1;";
			$res_material_aut = mysql_query($sql_material_aut);
			$row_material_aut = mysql_fetch_array($res_material_aut);
			$resp_aut = ""; 
			if(!$row_material_aut["si_no"]){ $resp_aut = ""; }
			if($row_material_aut["si_no"]=='s'){ $resp_aut = "<font color=green>Si cumple</font>"; }
			if($row_material_aut["si_no"]=='n'){ $resp_aut = "<font color=red>No cumple</font>"; }
			
			$sql_material_loc = "SELECT si_no 
				FROM  `aud_solicitud_item` 
				INNER JOIN aud_item ON aud_solicitud_item.id_item = aud_item.id
				WHERE 
					aud_solicitud_item.id_solicitud = '".$row["id"]."'
					AND aud_item.id_categoria = 59
				   AND aud_item.estado=1
				   AND `pregunta` LIKE  '%local%'
				   AND aud_item.tipo = '1' LIMIT 1;";
			$res_material_loc = mysql_query($sql_material_loc);
			$row_material_loc = mysql_fetch_array($res_material_loc);
			$resp_loc = ""; 
			if(!$row_material_loc["si_no"]){ $resp_loc = ""; }
			if($row_material_loc["si_no"]=='s'){ $resp_loc = "<font color=green>Si cumple</font>"; }
			if($row_material_loc["si_no"]=='n'){ $resp_loc = "<font color=red>No cumple</font>"; }
			
			$sql_material_obs = "SELECT respuesta 
				FROM  `aud_solicitud_item` 
				INNER JOIN aud_item ON aud_solicitud_item.id_item = aud_item.id
				WHERE 
					aud_solicitud_item.id_solicitud = '".$row["id"]."'
					AND aud_item.id_categoria = 59
				   AND aud_item.estado=1
				   AND `pregunta` LIKE  '%Observaci%'
				   AND aud_item.tipo = '2' LIMIT 1;";
			$res_material_obs = mysql_query($sql_material_obs);
			$row_material_obs = mysql_fetch_array($res_material_obs);
			
										
								
			if($row["offline"])
			{
					 $sql_traza = "SELECT material_traza.cantidad, material_traza.tipo, equipo_material.nombre, equipo_material.codigo_1, cantidad_auditor
					FROM material_traza 
					INNER JOIN equipo_material ON material_traza.id_equipo_material = equipo_material.id									
					WHERE material_traza.id_tramite = '".$row["offline"]."' ORDER BY equipo_material.nombre ";
					$res_traza = mysql_query($sql_traza);
					while($row_traza = mysql_fetch_array($res_traza))
					{ ?>
						<tr>
							<td><?php echo $row["ot"] ?></td>
							<td>EDATEL</td>
							<td><?php echo $row["nom_localidad"] ?></td>
							<td><?php echo $row["nombre_funcionario"] ?> (<?php echo $row["codigo_funcionario"] ?>)</td>
							<td><?php echo $row["producto_homologado"] ?></td>
							<td><?php echo $row["tecnologia"] ?></td>
							<td><?php echo $row["proceso"] ?></td>
							<td><?php echo $row["empresa"] ?></td>
							<td><?php echo $row_traza["nombre"] ?></td>
							<td valign=top align=center><?php echo $row_traza["cantidad"]; ?></td>
							<td valign=top align=center><?php echo $row_traza["cantidad_auditor"]; ?></td>						
							<td><?php echo $resp; ?></td>
							<td><?php echo $resp_acom; ?></td>
							<td><?php echo $resp_aut; ?></td>
							<td><?php echo $resp_loc; ?></td>
							<td><?php echo $row["ape_usuario"] ?>  <?php echo $row["nom_usuario"] ?></td>
							<td> </td>
							<td><?php echo $row_material_obs["respuesta"] ?></td>
							<td><?php echo $row["fecha_inicio"] ?></td>
							<td><?php echo $row["fecha_finalizado"] ?></td>
							
						</tr>
					<?php
					}
			}
			
			if($row["offlinehfc"])
			{
					$sql_traza = "SELECT une_material.nombre, une_material.codigo, une_pedido_material.cantidad, une_pedido_material.id,
							 une_pedido_material.alarma, cantidad_auditor
							FROM une_pedido_material 
							INNER JOIN une_material ON une_pedido_material.id_material = une_material.id									
							WHERE une_pedido_material.id_pedido = '".$row["offlinehfc"]."' ORDER BY une_material.nombre ";
					$res_traza = mysql_query($sql_traza);
					while($row_traza = mysql_fetch_array($res_traza))
					{ 
						
					?>
						<tr>
							<td><?php echo $row["numero"] ?></td>
							<td>TIGOUNE</td>
							<td><?php echo $row["ciudad"] ?></td>
							<td><?php echo $row["nombre_funcionario"] ?> (<?php echo $row["codigo_funcionario"] ?>)</td>
							<td><?php echo $row["producto_homologado"] ?></td>
							<td><?php echo $row["tecnologia"] ?></td>
							<td><?php echo $row["proceso"] ?></td>
							<td><?php echo $row["empresa"] ?></td>
							<td ><?php echo $row_traza["nombre"] ?><br></td>							
							<td align=center><?php echo $row_traza["cantidad"]; ?></td>
							<td align=center> <?php echo $row_traza["cantidad_auditor"]; ?> </td>
							<td><?php echo $resp; ?></td>
							<td><?php echo $resp_acom; ?></td>
							<td><?php echo $resp_aut; ?></td>
							<td><?php echo $resp_loc; ?></td>
							<td><?php echo $row["ape_usuario"] ?>  <?php echo $row["nom_usuario"] ?></td>
							<td> </td>
							<td><?php echo $row_material_loc["respuesta"] ?></td>
							<td><?php echo $row["fecha_inicio"] ?></td>
							<td><?php echo $row["fecha_finalizado"] ?></td>
						</tr>
					<?php
					}
			}
			
			
			
			
			
		
		}
		
		?>
	
	
		</table>

	
