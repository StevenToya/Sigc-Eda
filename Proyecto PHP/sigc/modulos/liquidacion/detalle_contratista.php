<?php
session_start();
include("../../cnx.php");
$cuo = '2000';	
function moneda($dato)
{	return number_format($dato, 2, ',', '.'); }

$sql_inc = "SELECT valor FROM incremento WHERE ano = '".date("Y")."' LIMIT 1";
$res_inc = mysql_query($sql_inc);
$row_inc = mysql_fetch_array($res_inc);
$incrementado = $row_inc["valor"];

if($_POST["ot"])
{
	$sql = "SELECT id FROM tramite WHERE ot = '".$_POST["ot"]."' AND ultimo='s' LIMIT 1 ";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);	
	
	$_GET["id"] = $row["id"];
}


	$sql = "SELECT  tramite.ot , tramite.fecha_atencion_orden , tramite.tipo_paquete , tramite.tipo_producto , tipo_trabajo.nombre AS nom_tt,
	tramite.numero_servicio , tramite.tecnologia , tramite.departamento , tramite.region , tramite.zona , tramite.nombre_cliente,  tipo_trabajo.tipo AS tem_tipo_trabajo,
	tramite.contrato , tramite.direccion ,tramite.tipo_cliente ,tramite.numero_orden ,tramite.valor_liquidado, tecnologia.nombre AS nom_tecnologia,
	liquidacion_zona.valor, (liquidacion_zona.valor * ".$incrementado.") AS valor_basico, tecnico.nombre AS nom_tecnico, localidad.nombre AS nom_localidad, ot_antecesor, tramite.producto, tramite.	unidad_operativa,
	tramite.id AS tramite_principal, tramite.departamento AS tem_zona, tramite.id_tipo_trabajo AS tem_tt,  tipo_trabajo.tipo AS ttt, codigo_unidad_operativa,
	tramite.fecha_atencion_orden AS tem_fecha, tramite.direccion_codigo AS tem_dirid,tramite.id_usuario_liquida AS liquidador, estado_liquidacion, fecha_ot_antecesor AS tem_fecha2, descripcion_dano,
	cantidad_cpe AS cantidad_cpe2, cantidad_stbox AS cantidad_stbox2,
		IFNULL((
			SELECT  
				SUM( 
					IF(tramite.id_tipo_trabajo IN ('38','108'),
							material_traza.cantidad,
							IF (material_traza.cantidad > 3, 1, 0)							
					)
				)  
			FROM `tramite` 
			INNER JOIN material_traza
			  ON tramite.id = material_traza.id_tramite
			  WHERE tramite.id = tramite_principal  AND material_traza.id_equipo_material IN ('693','694') 
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
				datediff(tem_fecha,tem_fecha2)<31
				AND ttt = 3 
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
		WHERE tramite.id = '".$_GET["id"]."' LIMIT 1 ; ";
		$res = mysql_query($sql);
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	
	$estado_material = "";
	if($row["estado_material"]==1){ $estado_material = "<font color=red>Pend. EDATEL</font>";}
	if($row["estado_material"]==4){ $estado_material = "<font color=#000000>Pend. EIA</font>";}
	if($row["estado_material"]==2){ $estado_material = "<font color=green>Confirmado</font>";}
	if($row["estado_material"]==3){ $estado_material = "<font color=red>Rechazado</font>";}

		
?>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>S.I.G.C.</title>
    <link href="../../css/bootstrap.css" rel="stylesheet" />
    <link href="../../css/font-awesome.css" rel="stylesheet" />
	<link href="../../js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <link href="../../css/custom.css" rel="stylesheet" />
	 <link href="../../js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
	 <script type="text/javascript" src="js/datepicker.js"></script>	
    <link href="../../css/datepicker.css" rel="stylesheet" />
	<script src="../../js/chart.min.js"></script> 
	
		 <script src="../../js/jQuery-2.1.4.min.js"></script>
		 <script src="../../js/bootstrap.min.js"></script>
		 <script src="../../js/Chart.min.js"></script> 
	
</head>
<body>
<script type="text/javascript">


	function popUpmensaje(URL) 
	{
		day = new Date();
		id = day.getTime();
		eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1350,height=650,left = 200,top = 5');");
	}
</script>
<?php if(!$row["tramite_principal"]){ ?>
<br><br>
	<center>
		<div class="alert alert-info" style="width:50%">
			<center><h3> <i class="fa fa-exclamation-triangle fa-2x"></i> <br><br>La orden de trabajo <b><?php echo $_POST["ot"]; ?></b> no fue encontrada </h3></center>
		</div>
	</center>
	<?php die(); ?>
<?php } ?>

		<h5>
		<table width="95%" align=center>
			<tr>
				<td width="50%" valign=top>
					<div class="col-md-100 col-sm-100">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Datos Basicos
							</div>
							<div class="panel-body" align=center>
								<table width=100%   class="table-striped table-bordered">
									<tr >
										<td ><b>OT: </b></td><td><?php echo $row["ot"] ?></td>
									</tr>					
													
									<tr>
										<td><b>Tipo trabajo: </b></td><td><?php echo $row["nom_tt"] ?></td>
									</tr>
									
									<tr>
										<td><b>Producto: </b></td><td><?php echo $row["producto"] ?></td>
									</tr>
									
									<tr>
										<td><b>Unidad operativa: </b></td><td><?php echo $row["unidad_operativa"] ?></td>
									</tr>
																																			
									<tr>
										<td><b>Tecnologia: </b></td><td><?php echo $row["nom_tecnologia"] ?></td>
									</tr>			
								
																
									<tr>
										<td><b>Fecha de atencion: </b></td><td><?php echo $row["fecha_atencion_orden"] ?></td>
									</tr>

									<tr>
										<td><b>Tecnico: </b></td><td><?php echo $row["nom_tecnico"] ?></td>
									</tr>									
									
									<tr>
										<td><b>Tipo Paquete: </b></td><td><?php echo $row["tipo_paquete"] ?></td>
									</tr>
									
									<tr>
										<td><b>Tipo producto: </b></td><td><?php echo $row["tipo_producto"] ?>  </td>
									</tr>
									
									<tr>
										<td><b>Zona: </b></td><td><?php echo $row["zona"] ?></td>
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
									
									<tr>
										<td><b>tipo Cliente: </b></td><td><?php echo $row["tipo_cliente"] ?></td>
									</tr>
								
																
									<tr>
										<td><b>Plan comercial: </b></td><td><?php echo $row["proceso"] ?></td>
									</tr>
								
									<tr>
										<td><b>Numero de orden: </b></td><td><?php echo $row["numero_orden"] ?></td>
									</tr>
								
									<tr>
										<td><b>Producto: </b></td><td><?php echo $row["producto"] ?></td>
									</tr>					
									
								</table>
								

								
							</div>					
						</div>
					</div>
					<?php 
					$sql_autor = "SELECT nombre, apellido FROM usuario WHERE id ='".$row["liquidador"]."' LIMIT 1";
					$res_autor = mysql_query($sql_autor);
					$row_autor = mysql_fetch_array($res_autor);				
					?>
					
					<div class="col-md-100 col-sm-100">
						<div class="panel panel-warning">
							<div class="panel-heading">
								Liquidacion tramite (<?php echo $row_autor["apellido"] ?> <?php echo $row_autor["nombre"] ?>)
							</div>
							<div class="panel-body" >
							
							<?php 
								if($row["codigo_unidad_operativa"]!='2000'  || strpos($row["descripcion_dano"], 'fundado')  || strpos($row["descripcion_dano"], 'o masivo'))
								{
									$row["valor"] = 0;
									$row["total_adicion_estencion"] = 0;
									$row["total_total"] = 0;
								}
								
							?>
							
							
							
							<?php if($row["estado_liquidacion"]==2){ ?>
												<?php if(!$row["ot_antecesor"] || $row["ttt"]!=3){ ?>
													<table width=100%   class="table-striped table-bordered">
														<tr >
															<td>Valor basico del tramite: </td><td align=right><b>$ <?php echo moneda($row["valor_basico"]) ?></b></td>
														</tr>
														<tr >
															<td>Valor extencion (<font color=red><?php echo $row["tem_extension"] ?></font>)  y adicion de cajas (<font color=red><?php echo $row["tem_adicional"] ?></font>) </td><td align=right><b>$ <?php echo moneda($row["total_adicion_estencion"]) ?></b></td>
														</tr>
														<tr >
															<td>Valor total del tramite: </td><td align=right><b>$ <?php echo moneda($row["total_total"]) ?></b></td>
														</tr>
													</table>
												<?php }else{ ?>
												
												<?php
												$sql_garantia = "SELECT fecha_atencion_orden, id, , tipo_garantia, ot FROM tramite WHERE id = '".$row["ot_antecesor"]."' LIMIT 1";
												$res_garantia = mysql_query($sql_garantia);
												$row_garantia = mysql_fetch_array($res_garantia);
												
												$tipo_garantia = "con garantia";
												if($row_garantia["tipo_garantia"]==1){$tipo_garantia = "con garantia por instalacion";}
												if($row_garantia["tipo_garantia"]==2){$tipo_garantia = "con garantia por da&ntilde;o reincidente ";}
												if($row_garantia["tipo_garantia"]==3){$tipo_garantia = "con garantia por da&ntilde;o reiterativo";}
												
												?>
														Tramite <b><?php echo $tipo_garantia; ?></b> con la orden de trabajo numero <b><?php echo $row_garantia["ot"]; ?></b> efectuada el dia
														<b><?php echo $row_garantia["fecha_atencion_orden"]; ?></b>. Mas informacion del tramite anterior 
														<a href="javascript:popUpmensaje('?id=<?php echo $row_garantia["id"]; ?>')"> <b>aqui</b> </a>
														
												<?php } ?>
							<?php }else{ ?>
									<font color=red><center><b>TRAMITE SIN LIQUIDAR</b></center>	</font>						
							<?php } ?>
							</div>
						</div>
					</div>
					
					
					
					
				</td>
				<td width="1%"> </td>
				<td width="49%"  valign=top>
					<div class="col-md-100 col-sm-100">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Materiales usados
							</div>
							<div class="panel-body" align=center>
								<table width="99%" class="table-striped table-bordered ">
								
									<tr bgcolor=#BDBDBD>
										<th><center>Codigo</center></th>
								
										<th>Material</th>
									
										<th><center>Cantidad</center></th>																			
									</tr>
									
									<?php
									$i =0;
									 $sql_traza = "SELECT material_traza.cantidad, material_traza.tipo, equipo_material.nombre, equipo_material.codigo_1
									FROM material_traza 
									INNER JOIN equipo_material ON material_traza.id_equipo_material = equipo_material.id									
									WHERE material_traza.id_tramite = '".$_GET["id"]."' ORDER BY equipo_material.nombre ";
									$res_traza = mysql_query($sql_traza);
									while($row_traza = mysql_fetch_array($res_traza))
									{										
										if($i%2 == 0){$color= "";}else{$color='#E6E6E6';}
																				
									?>
										<tr bgcolor=<?php echo $color; ?>>
											<td valign=top><?php echo $row_traza["codigo_1"] ?></td>
											
											<td valign=top><?php echo $row_traza["nombre"] ?></td>
											
											<td valign=top><?php echo $row_traza["cantidad"]; ?></td>											
										</tr>
										
									<?php
										$i++;
									}
									?>
								</table>
								
							</div>					
						</div>
					</div>
					
					
					<div class="col-md-100 col-sm-100">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Equipos 
							</div>
							<div class="panel-body" align=center>
								<table width="99%"  class="table-striped table-bordered ">
									
								
									<tr  bgcolor=#BDBDBD>
										<th>Equipo</th>										
										<th>Serial</th>
										<th>Estado</th>																			
									</tr>
									
									<?php
									$i =0;
									$sql_traza = "SELECT  serial_traza.estado, equipo_material.nombre, equipo_serial.serial
									FROM serial_traza 
									INNER JOIN equipo_serial ON serial_traza.id_equipo_serial = equipo_serial.id
									INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id									
									WHERE serial_traza.id_tramite = '".$_GET["id"]."' ORDER BY equipo_material.nombre ";
									$res_traza = mysql_query($sql_traza);
									while($row_traza = mysql_fetch_array($res_traza))
									{
										if($row_traza["estado"]==1){$estado= "<font color=red><b>Libre</b></font>";}
										if($row_traza["estado"]==2){$estado= "<font color=green><b>Instalado</b></font>";}
										if($row_traza["estado"]==3){$estado= "<font color=red><b>Malo</b></font>";}
										if($row_traza["estado"]==4){$estado= "<font color=red><b>Perdido</b></font>";}										
										if($row_traza["estado"]==5){$estado= "<font>Instalado antes</font>";}
										if($row_traza["estado"]==6){$estado= "<font color=red><b>Retirado</b></font>";}
									?>
										<tr>
											<td valign=top><?php echo $row_traza["nombre"] ?></td>
											<td valign=top><?php echo $row_traza["serial"] ?></td>
											<td valign=top align=center><?php echo $estado; ?></td>
									
										</tr>
										
									<?php
										$i++;
									}
									?>
								</table>
								
							</div>					
						</div>
					</div>
					
					
					
				
				</td>
		</table>

</body>
</html>