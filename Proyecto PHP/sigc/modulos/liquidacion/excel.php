<?php
	session_start();
	include("../../cnx.php");
	include("../../query.php");
	
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

function moneda($dato)
{	return number_format($dato, 2, ',', '.'); }
	
if(!$_SESSION["ss_fecha_inicial"]){$ss_fecha_inicial = date("Y-m-d");}else{$ss_fecha_inicial = $_SESSION["ss_fecha_inicial"];}
if(!$_SESSION["ss_fecha_final"]){$ss_fecha_final = date("Y-m-d");}else{$ss_fecha_final = $_SESSION["ss_fecha_final"];}
$where = " tramite.fecha_atencion_orden >= '".$ss_fecha_inicial." 00:00:00' AND  tramite.fecha_atencion_orden  <= '".$ss_fecha_final." 23:59:59' ";
if($_SESSION["ss_tecnologia"]){ $where = $where." AND tramite.id_tecnologia = '".$_SESSION["ss_tecnologia"]."' ";}
if($_SESSION["ss_item"]){ $where = $where." AND liquidacion_zona.item = '".$_SESSION["ss_item"]."' ";}
if($_SESSION["ss_tipo_trabajo"]){ $where = $where." AND tramite.id_tipo_trabajo = '".$_SESSION["ss_tipo_trabajo"]."' ";}
if($_SESSION["ss_zona"]){$where = $where." AND tramite.departamento = '".$_SESSION["ss_zona"]."' "; }
if($_SESSION["ss_servicio"]){ $where = $where." AND liquidacion_zona.servicio = '".$_SESSION["ss_servicio"]."' ";  }
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
	
if($_GET["tip"]==1)
{ $where_tem = $where." AND tramite.id_tecnologia =  '".$_GET["criterio"]."' "; }

if($_GET["tip"]==2)
{	$where_tem = $where." AND tramite.departamento =  '".$_GET["criterio"]."' "; 	}

if($_GET["tip"]==3)
{	$where_tem = $where." AND liquidacion_zona.servicio =  '".str_replace("___", "+", $_GET["criterio"])."' "; 	}

if($_GET["tip"]==4)
{	$where_tem = $where." AND liquidacion_zona.item =  '".str_replace("___", "+", $_GET["criterio"])."' ";  	}

if($_GET["tip"]==5)
{	$where_tem = $where." AND tramite.id_tipo_trabajo =  '".$_GET["criterio"]."' "; 	}

if($_GET["tip"]==10)
{	$where_tem = $where; 	}

	
	?>
		<table border=1>
			<tr>
				<th>OT</th>
				<th>Solicitud</th>
				<th>Motivo</th>
				<th>Nro. Orden</th>
				
				<th>Tecnico</th>
				<th>Identificacion Tecnico</th>
				<th>Codigo Tecnico</th>
				
				<th>Fecha atencion </th>
				<th>Fecha atencion orden</th>
				<th>Fecha reportada</th>
				<th>Fecha asignacion</th>
				<th>Fecha creacion orden</th>
				
				<th>Cod. Unidad Operativa</th>
				<th>Unidad Operativa</th>
				
				
				<th>Codigo Tipo de Trabajo</th>
				<th>Tipo de Trabajo</th>
				<th>Tecnologia</th>
				<th>Servicio</th>
				<th>Item</th>
				<th>Tipo tramite</th>
				<th>Codigo tipo paquete</th>
				<th>Departamento</th>
				<th>Zona</th>
				<th>Region</th>
				<th>Localidad</th>
				
				 <th>Numero servicio</th>
				 <th>Producto</th>
				  <th>Tipo producto</th>
				<th>Codigo de dano</th>
				<th>Nombre cliente</th>
				<th>Identificacion cliente</th>
				<th>Contrato</th>
				<th>Direccion ID</th>
				<th>Direccion</th>
				<th>Tipo Cliente</th>
				<th>Tipo Reparacion</th>
				
				<th>Total caja</th>
				<th>Caja adic.</th>
				<th>Equipos Usados</th>
				<th>Exten.</th>
				<th>Automatico</th>
				<th>Oficial</th>
				
			</tr>		
	<?php
		$select = ", tramite.ot, tecnologia.nombre AS nom_tecnologia, tramite.fecha_atencion_orden, tipo_trabajo.nombre ,  tipo_trabajo.codigo , tipo_trabajo.tipo, 
		liquidacion_zona.valor, tramite.id, tramite.estado_liquidacion, tramite.valor_liquidado, tramite.tipo_paquete, tramite.solicitud, departamento, zona,
		liquidacion_zona.item, liquidacion_zona.servicio, tecnico.nombre AS nom_tecnico, tramite.codigo_dano, tramite.nombre_cliente, tramite.direccion_codigo, 
		tramite.tipo_cliente, tramite.descripcion_dano, tramite.fecha_atencion, tramite.fecha_reportada, tramite.fecha_asignacion, tramite.fecha_creacion_oden,
		tramite.numero_orden, unidad_operativa, codigo_unidad_operativa, tecnico.codigo AS cod_tecnico, tecnico.cedula AS ced_tecnico, localidad.nombre AS nom_localidad,
		tramite.region, tramite.identificacion_cliente, tramite.contrato, tramite.direccion, tramite.numero_servicio, tramite.producto, tramite.tipo_producto,
		tramite.codigo_tipo_paquete, tramite.tipo_garantia, tramite.cantidad_cpe, tramite.cantidad_stbox";
		$res = tramite_liquidacion($select, $where_tem,  "",  "", '2');
		while($row = mysql_fetch_array($res))
		{
			if($row["tipo_garantia"]==1){$tipo_garantia = "Garantia por instalacion";}
			if($row["tipo_garantia"]==2){$tipo_garantia = "Garantia por da&ntilde;o reincidente ";}
			if($row["tipo_garantia"]==3){$tipo_garantia = "Garantia por da&ntilde;o reiterativo";}
								
			
			$tramite = "";
			if($row["tipo"]==1){$tramite = 'Instalacion';}
			if($row["tipo"]==2){$tramite = 'Reconexion';}
			if($row["tipo"]==3){$tramite = 'Reparacion';}
			if($row["tipo"]==4){$tramite = 'Suspension';}
			if($row["tipo"]==5){$tramite = 'Retiro';}
			if($row["tipo"]==6){$tramite = 'Prematricula';}
			if($row["tipo"]==7){$tramite = 'Traslado';}
			if($row["estado_liquidacion"]==1){$valor_oficial  = '<font color =red><center>NO INGRESADO</center></font>';}else{$valor_oficial  =  '$'.moneda($row["valor_liquidado"]);}
			$row["valor"] = $row["valor"] + 0;			
			
			
?>
	<tr>
				<td><?php echo $row["ot"] ?></td>
				<td><?php echo $row["solicitud"] ?></td>
				<td>
				<?php
					$sql_motivo = "SELECT codigo FROM tramite_motivo WHERE id_tramite = '".$row["id"]."' ";
					$res_motivo = mysql_query($sql_motivo);
					while($row_motivo = mysql_fetch_array($res_motivo)){
				?>
					<?php echo $row_motivo["codigo"]; ?>,
				<?php
					}
				?>
				</td>
				<td><?php echo $row["numero_orden"] ?></td>
				
				<td><?php echo $row["nom_tecnico"] ?></td>
				<td><?php echo $row["ced_tecnico"] ?></td>
				<td><?php echo $row["cod_tecnico"] ?></td>
				
				<td><?php echo $row["fecha_atencion"] ?></td>
				<td><?php echo $row["fecha_atencion_orden"] ?></td>
				<td><?php echo $row["fecha_reportada"] ?></td>
				<td><?php echo $row["fecha_asignacion"] ?></td>
				<td><?php echo $row["fecha_creacion_oden"] ?></td>
				
				<td><?php echo $row["codigo_unidad_operativa"] ?></td>
				<td><?php echo $row["unidad_operativa"] ?></td>			
				<td><?php echo $row["codigo"] ?></td>
				<td><?php echo $row["nombre"] ?></td>
				<td><?php echo $row["nom_tecnologia"] ?></td>
				<td><?php echo $row["servicio"] ?></td>
				<td><?php echo $row["item"] ?></td>				
				<td><?php echo $row["tipo_paquete"]; ?></td>
				<td><?php echo $row["codigo_tipo_paquete"]; ?></td>
				<td><?php echo $row["departamento"]; ?></td>
				<td><?php echo $row["zona"]; ?></td>
				<td><?php echo $row["region"]; ?></td>
				<td><?php echo $row["nom_localidad"]; ?></td>
				
				<td><?php echo $row["numero_servicio"]; ?></td>
				<td><?php echo $row["producto"]; ?></td>
				<td><?php echo $row["tipo_producto"]; ?></td>
				<td><?php echo $row["codigo_dano"]; ?></td>
				<td><?php echo $row["nombre_cliente"]; ?></td>
				<td><?php echo $row["identificacion_cliente"]; ?></td>					
				<td><?php echo $row["contrato"]; ?></td>						
				<td><?php echo $row["direccion_codigo"]; ?></td>
					<td><?php echo $row["direccion"]; ?></td>
				<td><?php echo $row["tipo_cliente"]; ?></td>
				<td><?php echo $row["descripcion_dano"]; ?></td>
				
					<?php
				$equipos = ''; $tc= 0;
				$sql_traza = "SELECT  serial_traza.estado, equipo_material.nombre, equipo_serial.serial
				FROM serial_traza 
				INNER JOIN equipo_serial ON serial_traza.id_equipo_serial = equipo_serial.id
				INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id									
				WHERE serial_traza.id_tramite = '".$row["id"]."' AND serial_traza.estado ='2' ORDER BY equipo_material.nombre ";
				$res_traza = mysql_query($sql_traza);
				while($row_traza = mysql_fetch_array($res_traza))
				{	$tc++ ; $equipos = $equipos.$row_traza["nombre"]."(".$row_traza["serial"]."), ";}		

			    if($row["tipo"]==7){$tc = $row["cantidad_cpe"] + $row["cantidad_stbox"];}
							
				?>
					<td  align=center><?php echo $tc ?> Cajas</td>
				
					<td  align=center><?php echo $row["tem_adicional"] ?> Cajas Adicional</td>
					<td><?php echo $equipos; ?></td>
					<td  align=center><?php echo $row["tem_extension"] ?> mts</td>						
				<td align=right><b>
					<?php if($row["garantia"]=='SIN GARANTIA'  || !$row["garantia"]){ ?>
						$<?php echo moneda($row["total_total"]) ?>
					<?php }else{ ?>
							<font color=red><?php echo $tipo_garantia; ?></font>
					<?php } ?></b>
				</td>
				<td align=right><b> <?php echo $valor_oficial; ?></b></td>	
				
			</tr>
	</tr>
		
	<?php	
	}
?>
</table>