<?php

include("../../cnx.php");


$sql = "SELECT aud_base.nombre AS nom_base, usuario.nombre AS nom_usuario, usuario.apellido AS ape_usuario, aud_solicitud.estado, aud_solicitud.id,
				aud_solicitud.fecha_registro, tramite.ot, tramite.id AS offline, sra_tramite.id AS online, sra_tramite.pedido, tecnico.nombre AS nom_tecnico, 
				tramite.fecha_atencion_orden, tramite.departamento, localidad.nombre AS nom_localidad, tramite.nombre_cliente, tramite.direccion,
				sra_tramite.zona AS zona_on, sra_tramite.localidad AS localidad_on, sra_tramite.barrio AS barrio_on, sra_tramite.direccion AS direccion_on,
				sra_tramite.fecha_cita , sra_tramite.franja_horaria, aud_solicitud.observacion, aud_solicitud.id_base, aud_solicitud.fecha_inicio, sra_tramite.cliente,
				solicitante.nombre AS nom_solicitante, solicitante.apellido AS ape_solicitante, aud_solicitud.fecha_finalizado, une_pedido.numero,
				une_pedido.id AS offlinehfc, une_pedido.ciudad, une_pedido.cliente_nombre, une_pedido.cliente_direccion, une_pedido.tipo_trabajo, 
				une_pedido.nombre_funcionario, une_pedido.segmento, une_pedido.producto, une_pedido.producto_homologado, une_pedido.tecnologia, 
				une_pedido.proceso, une_pedido.empresa, une_pedido.fecha, une_pedido.codigo_funcionario, aud_base.material					
			FROM aud_solicitud 
		LEFT JOIN aud_base ON aud_solicitud.id_base = aud_base.id
		LEFT JOIN sra_tramite ON aud_solicitud.id_sra_tramite = sra_tramite.id
		LEFT JOIN tramite ON aud_solicitud.id_tramite = tramite.id
		LEFT JOIN une_pedido ON aud_solicitud.id_une_pedido = une_pedido.id
		LEFT JOIN localidad ON tramite.id_localidad = localidad.id
		LEFT JOIN tecnico ON tramite.id_tecnico = tecnico.id
		LEFT JOIN usuario ON aud_solicitud.id_creador = usuario.id
		LEFT JOIN usuario AS solicitante ON aud_solicitud.id_solicitud = solicitante.id
		WHERE aud_solicitud.id = '".$_GET["id"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);




$estado = '';
if($row["estado"]==1){$estado = '<font color=red><b>Sin gestionar</b></font>';}
if($row["estado"]==2){$estado = '<b>Inicializado</b>';}
if($row["estado"]==3){$estado = '<font color=green><b>Finalizado</b></font>';}	

?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIGC</title>
    <link href="../../css/bootstrap.css" rel="stylesheet" />
  
	<script language="javascript">
	function imprimir() {
	if ((navigator.appName == "Netscape")) { window.print() ;
	}
	else {
	var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
	document.body.insertAdjacentHTML('beforeEnd', WebBrowser); WebBrowser1.ExecWB(6, -1); WebBrowser1.outerHTML = "";
	}
	}
</script>
</head>
<body  ONLOAD="imprimir()">
<!-- <body> -->

<table width="100%">
	<tr>
		<td align=left>
			<img width="130" src="../../img/tigoune_edatel.png">
		</td>
		<td align=center >
			<h3> ADITORIAS EDATEL-TIGOUNE</H3>
		</td>
	
	</tr>
</table>

<div class="col-md-100 col-sm-100">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<b>Datos de la auditoria</b>
			</div>
			<div class="panel-body" align=center>
				
						<table width=100% style="font-size:80%;">		
						<tr>
							<td ><b>Estado auditoria: </b></td><td><?php echo $estado; ?></td>
						</tr>	
						
					<?php if($row["nom_solicitante"]){ ?>	
						<tr>
							<td ><b>Auditoria solicitada: </b></td><td><?php echo $row["nom_solicitante"] ?> <?php echo $row["ape_solicitante"] ?> </td>
						</tr>
					<?php } ?>	
					
					<?php if($row["offlinehfc"]){ ?>
						<tr>
							<td><b>Numero: </b></td><td><?php echo $row["numero"] ?></td>
						</tr>								
						<tr>
							<td><b>Ciudad: </b></td><td><?php echo $row["ciudad"] ?></td>
						</tr>
						<tr>
							<td><b>Cliente: </b></td><td><?php echo $row["cliente_nombre"] ?></td>
						</tr>
						<tr>
							<td><b>Direccion: </b></td><td><?php echo $row["cliente_direccion"] ?></td>
						</tr>
						<tr>
							<td><b>Tipo trabajo: </b></td><td><?php echo $row["tipo_trabajo"] ?></td>
						</tr>
						<tr>
							<td><b>Funcionario: </b></td><td><?php echo $row["nombre_funcionario"] ?> (<?php echo $row["codigo_funcionario"] ?>) </td>
						</tr>
						<tr>
							<td><b>Segmento: </b></td><td><?php echo $row["segmento"] ?></td>
						</tr>
						<tr>
							<td><b>Producto: </b></td><td><?php echo $row["producto"] ?></td>
						</tr>
						<tr>
							<td><b>Producto homologado: </b></td><td><?php echo $row["producto_homologado"] ?></td>
						</tr>
						<tr>
							<td><b>Tecnologia: </b></td><td><?php echo $row["tecnologia"] ?></td>
						</tr>
						<tr>
							<td><b>Proceso: </b></td><td><?php echo $row["proceso"] ?></td>
						</tr>
						<tr>
							<td><b>Empresa: </b></td><td><?php echo $row["empresa"] ?></td>
						</tr>
						<tr>
							<td><b>Fecha: </b></td><td><?php echo $row["fecha"] ?></td>
						</tr>					
					<?php } ?>	
						
					<?php if($row["offline"]){ ?>
						<tr>
							<td ><b>OT: </b></td><td><?php echo $row["ot"] ?></td>
						</tr>									
						<tr>
							<td><b>Tecnico: </b></td><td><?php echo $row["nom_tecnico"] ?></td>
						</tr>
							
						<tr>
							<td><b>Fecha de atencion: </b></td><td><?php echo $row["fecha_atencion_orden"] ?></td>
						</tr>
																								
						<tr>
							<td><b>Departamento: </b></td><td><?php echo $row["departamento"] ?></td>
						</tr>
					
						<tr>
							<td><b>Localidad: </b></td><td><?php echo $row["nom_localidad"] ?></td>
						</tr>
														
						<tr>
							<td><b>Nombre Cliente: </b></td><td><?php echo $row["nombre_cliente"] ?></td>
						</tr>
						<tr>
							<td><b>Direccion: </b></td><td><?php echo $row["direccion"] ?></td>
						</tr>
					<?php } ?>
					
					<?php if($row["online"]){ ?>
						<tr>
							<td ><b>Pedido: </b></td><td><?php echo $row["pedido"] ?></td>
						</tr>									
						<tr>
							<td><b>Zona: </b></td><td><?php echo $row["zona_on"] ?></td>
						</tr>
						<tr>
							<td><b>Localidad: </b></td><td><?php echo $row["localidad_on"] ?></td>
						</tr>
						<tr>
							<td><b>Barrio: </b></td><td><?php echo $row["barrio_on"] ?></td>
						</tr>
						<tr>
							<td><b>Direccion: </b></td><td><?php echo $row["direccion_on"] ?></td>
						</tr>
						<tr>
							<td><b>Cliente: </b></td><td><?php echo $row["cliente"] ?></td>
						</tr>
						<tr>
							<td><b>Fecha de la cita: </b></td><td><?php echo $row["fecha_cita"] ?> <?php echo $row["franja_horaria"] ?> </td>
						</tr>
					
					<?php } ?>
					
						<tr>
							<td ><b>Inicio auditoria: </b></td><td><?php echo $row["fecha_inicio"] ?></td>
						</tr>
						
						<tr>
							<td ><b>Fin auditoria: </b></td><td><?php echo $row["fecha_finalizado"] ?></td>
						</tr>
					
					</table>
				</font>
		</div>
		
</div>
		
<?php
if($row["material"]==1){
 ?>	
<div class="col-md-100 col-sm-100">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<b>Material</b>
			</div>
			<div class="panel-body" align=center>
			
				<table width=100% border=0 style="font-size:80%;">
					<tr>
							
						<th></th>					
						<th ><center>Cant. Tec.</center></th>
						<th ><center>Cant. Aud.</center></th>
					</tr>
					<?php
					if($row["offline"])
					{
							 $sql_traza = "SELECT material_traza.cantidad, material_traza.tipo, equipo_material.nombre, equipo_material.codigo_1, 
							 material_traza.id, cantidad_auditor
							FROM material_traza 
							INNER JOIN equipo_material ON material_traza.id_equipo_material = equipo_material.id									
							WHERE material_traza.id_tramite = '".$row["offline"]."' ORDER BY equipo_material.nombre ";
							$res_traza = mysql_query($sql_traza);
							while($row_traza = mysql_fetch_array($res_traza))
							{ ?>
								<tr>
									<td valign=top><?php echo $row_traza["nombre"] ?></td>							
									<td valign=top align=center><?php echo $row_traza["cantidad"]; ?></td>
									<td align=center><b><?php echo $row_traza["cantidad_auditor"]; ?></b></td>
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
								$alarma = "";
								if($row_traza["alarma"]==2){$alarma = "<font color=yellow><i class='fa fa-exclamation-triangle fa'> </i></font>";}
								if($row_traza["alarma"]==3){$alarma = "<font color=red><i class='fa fa-exclamation-triangle fa'> </i></font>";}
							?>
								<tr>
									<td valign=top><?php echo $row_traza["nombre"] ?></td>							
									<td valign=top align=center><?php echo $row_traza["cantidad"]; ?></td>
									<td align=center><b> <?php echo $row_traza["cantidad_auditor"]; ?></b></td>
								</tr>
							<?php
							}
					}
					?>
				</table>
				<br>		
				<table width=100% style="font-size:80%;">
					<tr>
						<td width=70%>											
							CUMPLE CON LOS MATERIALES Y SUS CANTIDADES							
						</td>
						<td width=30% align=left>		
								<?php								
								$sql_material = "SELECT respuesta FROM  `aud_solicitud_material` WHERE id_solicitud = '".$row["id"]."' LIMIT 1";
								$res_material = mysql_query($sql_material);
								$row_material = mysql_fetch_array($res_material);
								?>
								<b>												
									<?php if(!$row_material["respuesta"]){ ?>No Aplica<?php } ?>
									<?php if($row_material["respuesta"]=='s'){ ?>Si Cumple<?php } ?>
									<?php if($row_material["respuesta"]=='n'){ ?>No Cumple<?php } ?>											
								</b>
						</td>								
					</tr>
				</table>
				
			</div>
		</div>
</div>				
								
<?php } 


$sql_cate = "SELECT aud_categoria.id, aud_categoria.nombre FROM aud_categoria 
LEFT JOIN aud_item ON aud_categoria.id = aud_item.id_categoria
WHERE aud_categoria.estado = 1 AND aud_item.id_base='".$row["id_base"]."' GROUP BY aud_categoria.id 
ORDER BY orden ASC ";

$res_cate = mysql_query($sql_cate);
while($row_cate = mysql_fetch_array($res_cate))
{
?>
	<div class="col-md-100 col-sm-100">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<b><?php echo $row_cate["nombre"] ?></b>
			</div>
			<div class="panel-body" align=center>
				<table width=100% border=0 style="font-size:80%;">
						<?php 
							$sql_pre = "SELECT * FROM aud_item WHERE 
								aud_item.estado=1 AND 
								aud_item.id_base='".$row["id_base"]."' AND
								aud_item.id_categoria ='".$row_cate["id"]."'
								ORDER BY tipo, pregunta";
							$res_pre = mysql_query($sql_pre);
							while($row_pre = mysql_fetch_array($res_pre))
							{								
								$sql_item = "SELECT si_no, respuesta, archivo FROM aud_solicitud_item 
									WHERE id_solicitud = '".$row["id"]."' AND id_item = '".$row_pre["id"]."'  LIMIT 1";
								$res_item = mysql_query($sql_item);
								$row_item = mysql_fetch_array($res_item);								
								?>
								
								<?php if($row_pre["tipo"]==1){ ?>						
										<tr>
											<td width=80%>											
												<?php echo $row_pre["pregunta"] ?>								
											</td>
											<td align=left width=20%>								
												<b>
													<?php if(!$row_item["si_no"]){ ?>No aplica<?php } ?>
													<?php if($row_item["si_no"]=='s'){ ?>Si Cumple <?php } ?>
													<?php if($row_item["si_no"]=='n'){ ?>No Cumple <?php } ?>															
												</b>
											</td>								
										</tr>
								<?php } ?>
								
								<?php if($row_pre["tipo"]==2){ ?>						
										<tr>
												<td width=100% colspan=2>												
													<?php echo $row_pre["pregunta"]; ?>	
													<textarea <?php if($row["estado"]==3){ ?> readonly <?php } ?> style="width:100%" rows="5" name="pre_<?php echo $row_pre["id"]; ?>"><?php echo $row_item["respuesta"]; ?></textarea>
												</td>
																			
											</tr>
								<?php } ?>
								
						
						<?php
							}
						?>
				</table>			
			</div>
		</div>
	</div>	

<?php
}
?>
<br><br>
<table width="100%" style="font-size:80%;">
	<tr>
		<td align=center width='50%'>
			______________________________ <br>
			Firma del tecnico			
		</td>
		<td align=center width='50%'>
			______________________________ <br>
			Firma del auditor			
		</td>
	
	</tr>
</table>


</body>
</html>