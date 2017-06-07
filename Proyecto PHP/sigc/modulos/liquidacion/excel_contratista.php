<?php
	session_start();
	include("../../cnx.php");
	include("../../query.php");
	
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

 $sql_permisos = "SELECT componente.codigo FROM componente 
		INNER JOIN permiso ON componente.id = permiso.id_componente
		WHERE permiso.id_usuario = '".$_SESSION["user_id"]."' ";
	$res_permisos = mysql_query($sql_permisos);
	while($row_permisos = mysql_fetch_array($res_permisos))
	{			
		$PERMISOS_GC[$row_permisos["codigo"]] = 'Si';
	}	
	
function moneda($dato)
{	return number_format($dato, 2, ',', '.'); }
	
if(!$_SESSION["ss_fecha"]){$ss_fecha = date("Y-m");}else{$ss_fecha = $_SESSION["ss_fecha"];}
$where = " tramite.fecha_atencion_orden >= '".$ss_fecha."-01 00:00:00' AND  tramite.fecha_atencion_orden  <= '".$ss_fecha."-31 23:59:59' ";
if($_SESSION["ss_tecnologia"]){ $where = $where." AND tramite.id_tecnologia = '".$_SESSION["ss_tecnologia"]."' ";}
if($_SESSION["ss_tipo_trabajo"]){ $where = $where." AND tramite.id_tipo_trabajo = '".$_SESSION["ss_tipo_trabajo"]."' ";}
if($_SESSION["ss_zona"]){$where = $where." AND tramite.departamento = '".$_SESSION["ss_zona"]."' "; }
if($_SESSION["ss_tipo_tramite"])
{
	if($_SESSION["ss_tipo_tramite"]==1){ $where = $where." AND (tipo_trabajo.tipo = '1' && tramite.tipo_paquete!='Traslado') ";}
	if($_SESSION["ss_tipo_tramite"]==2){ $where = $where." AND (tipo_trabajo.tipo = '2' && tramite.tipo_paquete!='Traslado') ";}
	if($_SESSION["ss_tipo_tramite"]==3){ $where = $where." AND (tipo_trabajo.tipo = '3' && tramite.tipo_paquete!='Traslado') ";}
	if($_SESSION["ss_tipo_tramite"]==4){ $where = $where." AND (tipo_trabajo.tipo = '4' && tramite.tipo_paquete!='Traslado') ";}
	if($_SESSION["ss_tipo_tramite"]==5){ $where = $where." AND (tipo_trabajo.tipo = '5' && tramite.tipo_paquete!='Traslado') "; }
	if($_SESSION["ss_tipo_tramite"]==6){ $where = $where." AND (tipo_trabajo.tipo = '6' && tramite.tipo_paquete!='Traslado') "; }
	if($_SESSION["ss_tipo_tramite"]==7){ $where = $where." AND ((tipo_trabajo.tipo = '7' || tramite.tipo_paquete='Traslado')) "; }	
	if($_SESSION["ss_tipo_tramite"]==8){ $where = $where." AND ((tipo_trabajo.tipo = '7' || tramite.tipo_paquete='Traslado') || tipo_trabajo.tipo = '1' ) "; } 	
}
	


if($_GET["tip"]==5)
{	$where_tem = $where." AND tramite.id_tipo_trabajo =  '".$_GET["criterio"]."' "; 	}

if($_GET["tip"]==10)
{	$where_tem = $where; 	}

	
	?>
		<table border=1>
			<tr>
			<th>OT</th>
			<th>Fecha</th>
			<th>Tipo de Trabajo</th>
			<th>Tecnologia</th>
			<th>Tipo tramite</th>
			<th>Item</th>
			<th>Tecnico</th>
			<th>Identificacion tecnico</th>
			<th>Region</th>
			<th>Zona</th>
			<th>Localidad</th>
			
				<th>Total caja</th>
				<th>Caja adic.</th>
			
		<?php if($PERMISOS_GC["liq_cont"]=='Si'){ ?> <th>Valor EDATEL</th> <?php } ?>
		<?php if($PERMISOS_GC["liq_cont"]=='Si'){ ?> <th>Estado Liquidacion</th> <?php } ?>
        <?php if($PERMISOS_GC["liq_mat_cont"]=='Si'){ ?> <th>Estado Material</th> <?php } ?>
        <?php if($PERMISOS_GC["liq_equ_cont"]=='Si'){ ?> <th>Estado Equipo</th> <?php } ?>
		<?php if($PERMISOS_GC["liq_cont"]=='Si'){ ?> <th>Observacion Liquidacion</th> <?php } ?>
        <?php if($PERMISOS_GC["liq_mat_cont"]=='Si'){ ?> <th>Observacion Material</th> <?php } ?>
        <?php if($PERMISOS_GC["liq_equ_cont"]=='Si'){ ?> <th>Observacion Equipo</th> <?php } ?>
		</tr>		
	<?php
				
$sql = " SELECT tramite.ot, tipo_trabajo.nombre AS nom_tt , tipo_trabajo.codigo, tecnologia.nombre, tipo_trabajo.tipo, tramite.id, valor_liquidado,
tecnologia.nombre AS nom_tecnologia, tipo_paquete, 	contratista_valor, contratista_equipo, contratista_material, observacion_contratista, 
observacion_contratista_material, observacion_contratista_equipo, tecnico.nombre AS nom_tecnico,  tecnico.cedula AS ced_tecnico, tramite.fecha_atencion_orden,
localidad.nombre AS nom_localidad, tramite.zona, tramite.region, liquidacion_zona.item
			FROM tramite 
			INNER JOIN tipo_trabajo ON 
				tramite.id_tipo_trabajo = tipo_trabajo.id
			LEFT JOIN tecnologia ON 
				tramite.id_tecnologia = tecnologia.id
			LEFT JOIN tecnico ON 
				tramite.id_tecnico = tecnico.id
			LEFT JOIN localidad ON 
				tramite.id_localidad = localidad.id
			LEFT JOIN zona ON 
				tramite.departamento = zona.nombre
			LEFT JOIN liquidacion_zona ON 
				zona.id = liquidacion_zona.id_zona AND
				tramite.id_tecnologia = liquidacion_zona.id_tecnologia	AND	
				tramite.id_tipo_trabajo = liquidacion_zona.id_tipo_trabajo 
			WHERE tramite.estado_liquidacion=2 AND ".$where_tem." 
			ORDER BY tramite.id_tipo_trabajo ";
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		$tramite = "";
		if($row["tipo"]==1){$tramite = 'Instalacion';}
		if($row["tipo"]==2){$tramite = 'Reconexion';}
		if($row["tipo"]==3){$tramite = 'Reparacion';}
		if($row["tipo"]==4){$tramite = 'Suspension';}
		if($row["tipo"]==5){$tramite = 'Retiro';}
		if($row["tipo"]==6){$tramite = 'Prematricula';}
		if($row["tipo"]==7){$tramite = 'Traslado';}
		
		$contratista_valor = '';
		if($row["contratista_valor"]==1){ $contratista_valor = "<b>Omitido</b>";} 
		if($row["contratista_valor"]==2){ $contratista_valor = "<font color=green><b>Aceptado</b></font>";} 
		if($row["contratista_valor"]==3){ $contratista_valor = "<font color=red><b>Rechazado</b></font>";}
		if($row["contratista_valor"]==4){ $contratista_valor = "<font color=red><b>Volver a revisar</b></font>";}

		$contratista_equipo = '';
		if($row["contratista_equipo"]==1){ $contratista_equipo = "<b>Omitido</b>";} 
		if($row["contratista_equipo"]==2){ $contratista_equipo = "<font color=green><b>Aceptado</b></font>";} 
		if($row["contratista_equipo"]==3){ $contratista_equipo = "<font color=red><b>Rechazado</b></font>";}
		if($row["contratista_equipo"]==4){ $contratista_equipo = "<font color=red><b>Volver a revisar</b></font>";}
		
		$contratista_material = '';
		if($row["contratista_material"]==1){ $contratista_material = "<b>Omitido</b>";} 
		if($row["contratista_material"]==2){ $contratista_material = "<font color=green><b>Aceptado</b></font>";} 
		if($row["contratista_material"]==3){ $contratista_material = "<font color=red><b>Rechazado</b></font>";}
		if($row["contratista_material"]==4){ $contratista_material = "<font color=red><b>Volver a revisar</b></font>";}
		
		
		$equipos = ''; $tc = 0;
				$sql_traza = "SELECT  serial_traza.estado, equipo_material.nombre, equipo_serial.serial
				FROM serial_traza 
				INNER JOIN equipo_serial ON serial_traza.id_equipo_serial = equipo_serial.id
				INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id									
				WHERE serial_traza.id_tramite = '".$row["id"]."' AND serial_traza.estado ='2' ORDER BY equipo_material.nombre ";
				$res_traza = mysql_query($sql_traza);
				while($row_traza = mysql_fetch_array($res_traza))
				{	$tc++; $equipos = $equipos.$row_traza["nombre"]."(".$row_traza["serial"]."), ";}
?>
		<tr>
			<td><?php echo $row["ot"] ?></td>
			<td><?php echo $row["fecha_atencion_orden"] ?></td>
			<td><?php echo $row["nom_tt"] ?></td>
			<td><?php echo $row["nom_tecnologia"] ?></td>
			<td><?php echo $row["tipo_paquete"]; ?></td>
			<td><?php echo $row["item"]; ?></td>
			<td><?php echo $row["nom_tecnico"]; ?></td>
			<td><?php echo $row["ced_tecnico"]; ?></td>
			<td><?php echo $row["region"]; ?></td>
			<td><?php echo $row["zona"]; ?></td>
			<td><?php echo $row["nom_localidad"]; ?></td>
			
			<td  align=center><?php echo $tc ?> Cajas</td>				
			<td  align=center><?php echo $row["tem_adicional"] ?> Cajas Adicional</td>
			
			
			<?php if($PERMISOS_GC["liq_cont"]=='Si'){ ?>  <td align=right><b> $<?php echo moneda($row["valor_liquidado"]); ?></b></td> <?php } ?>
			<?php if($PERMISOS_GC["liq_cont"]=='Si'){ ?> <td><?php echo $contratista_valor ?></td> <?php } ?>
			<?php if($PERMISOS_GC["liq_mat_cont"]=='Si'){ ?> <td><?php echo $contratista_material ?></td>	<?php } ?>
			<?php if($PERMISOS_GC["liq_equ_cont"]=='Si'){ ?> <td><?php echo $contratista_equipo ?></td> <?php } ?>
			

			<?php if($PERMISOS_GC["liq_cont"]=='Si'){ ?> <td><?php echo $row["observacion_contratista"]; ?></td> <?php } ?>
			<?php if($PERMISOS_GC["liq_mat_cont"]=='Si'){ ?> <td><?php echo $row["observacion_contratista_material"]; ?></td> <?php } ?>
			<?php if($PERMISOS_GC["liq_equ_cont"]=='Si'){ ?> <td><?php echo $row["observacion_contratista_equipo"]; ?></td>	<?php } ?>						
		</tr>
		
	<?php	
	}
?>
</table>