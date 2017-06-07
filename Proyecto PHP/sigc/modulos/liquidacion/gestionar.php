<?php  
if($PERMISOS_GC["liq_ges"]!='Si'){   
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}  


if($_GET["detalle"]=='s')
{	$_SESSION["detalle"] = 1; }

if($_GET["detalle"]=='n')
{	$_SESSION["detalle"] = ""; }

function numero_decimal($str) 
{ 
  if(strstr($str, ",")) { 
    $str = str_replace(".", "", $str); 
    $str = str_replace(",", ".", $str);
  }   
  if(preg_match("#([0-9\.]+)#", $str, $match)) { 
    return floatval($match[0]); 
  } else { 
    return floatval($str); 
  } 
}

if(!$_SESSION["ss_fecha_inicial"]){$ss_fecha_inicial = date("Y-m-d");}else{$ss_fecha_inicial = $_SESSION["ss_fecha_inicial"];}
if(!$_SESSION["ss_fecha_final"]){$ss_fecha_final = date("Y-m-d");}else{$ss_fecha_final = $_SESSION["ss_fecha_final"];}
$where = " tramite.fecha_atencion_orden >= '".$ss_fecha_inicial." 00:00:00' AND  tramite.fecha_atencion_orden  <= '".$ss_fecha_final." 23:59:59' ";
if(!$_SESSION["ss_tecnologia"]){$ss_tecnologia = "TODAS";}else{$ss_tecnologia = $_SESSION["ss_tecnologia"]; $where = $where." AND tramite.id_tecnologia = '".$_SESSION["ss_tecnologia"]."' ";}
if(!$_SESSION["ss_item"]){$ss_item = "TODAS";}else{$ss_item = $_SESSION["ss_item"];  $where = $where." AND liquidacion_zona.item = '".$_SESSION["ss_item"]."' ";}
if(!$_SESSION["ss_tipo_trabajo"]){ $ss_tipo_trabajo = "TODAS";}else{$ss_tipo_trabajo = $_SESSION["ss_tipo_trabajo"];   $where = $where." AND tramite.id_tipo_trabajo = '".$_SESSION["ss_tipo_trabajo"]."' ";}
if(!$_SESSION["ss_zona"]){$ss_zona = "TODAS";}else{$ss_zona = $_SESSION["ss_zona"];  $where = $where." AND tramite.departamento = '".$_SESSION["ss_zona"]."' "; }
if(!$_SESSION["ss_servicio"]){$ss_servicio= "TODAS";}else{$ss_servicio = $_SESSION["ss_servicio"];  $where = $where." AND liquidacion_zona.servicio = '".$_SESSION["ss_servicio"]."' ";  }
if(!$_SESSION["ss_tipo_tramite"]){ $ss_tipo_tramite = "TODAS";}
else
{
	if($_SESSION["ss_tipo_tramite"]==1){$ss_tipo_tramite = "Instalacion"; $where = $where." AND (tipo_trabajo.tipo = '1' && tramite.tipo_paquete!='Traslado') ";}
	if($_SESSION["ss_tipo_tramite"]==2){$ss_tipo_tramite = "Reconexion"; $where = $where." AND (tipo_trabajo.tipo = '2' && tramite.tipo_paquete!='Traslado') ";}
	if($_SESSION["ss_tipo_tramite"]==3){$ss_tipo_tramite = "Reparacion"; $where = $where." AND (tipo_trabajo.tipo = '3' && tramite.tipo_paquete!='Traslado') ";}
	if($_SESSION["ss_tipo_tramite"]==4){$ss_tipo_tramite = "Suspension"; $where = $where." AND (tipo_trabajo.tipo = '4' && tramite.tipo_paquete!='Traslado') ";}
	if($_SESSION["ss_tipo_tramite"]==5){$ss_tipo_tramite = "Retiro"; $where = $where." AND (tipo_trabajo.tipo = '5' && tramite.tipo_paquete!='Traslado') "; }
	if($_SESSION["ss_tipo_tramite"]==6){$ss_tipo_tramite = "Prematricula"; $where = $where." AND (tipo_trabajo.tipo = '6' && tramite.tipo_paquete!='Traslado') "; }
	if($_SESSION["ss_tipo_tramite"]==7){$ss_tipo_tramite = "Traslado"; $where = $where." AND (tipo_trabajo.tipo = '7' || tramite.tipo_paquete='Traslado') "; }  
	if($_SESSION["ss_tipo_tramite"]==8){$ss_tipo_tramite = "Instalacion y Traslado"; $where = $where." AND ((tipo_trabajo.tipo = '7' || tramite.tipo_paquete='Traslado') || tipo_trabajo.tipo = '1' ) "; } 	
}

$select = ", tramite.valor_liquidado AS vl"; 
$select_sup = ", SUM(vl) AS total_liquidado "; 
$res = tramite_liquidacion($select, $where, $select_sup, "", '1');
$row = mysql_fetch_array($res);
?>

<h2>TRAMITES REALIZADOS</h2>

<h5>
<center>
	<div class="col-md-100 col-sm-100" style="width:90%">
		<div class="panel panel-warning" >
			<div class="panel-heading"  align=left>
				<table width=100%>
					<tr>
						<td><b>Configuracion de la busqueda</b></td>
						<td align=right>  <a href="?cmp=cambio_busqueda"><i class="fa fa-pencil-square-o fa-2x"></i></td>
					</tr>
				</table>
			</div>
			<div class="panel-body">
				<table align=center  width=100%>
					<tr>
						<?php
							if($ss_tecnologia!="TODAS")
							{
								$sql_tt = "SELECT nombre  FROM `tecnologia` WHERE id = '".$ss_tecnologia."' LIMIT 1";
								$res_tt = mysql_query($sql_tt);
								$row_tt = mysql_fetch_array($res_tt);
								$ss_tecnologia = $row_tt["nombre"];
							}
						?>
						<td  width=5% >Tecnologia: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width=11%><b><?php echo $ss_tecnologia; ?></b></td>
						<td  width=5% >Departamento: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width=11%><b><?php echo $ss_zona; ?></b></td>
						<td  width=5% >Item: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width=11%><b><?php echo $ss_item; ?></b></td>					
					</tr>
					
					<tr>
						<td  >Servicio: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b><?php echo $ss_servicio; ?></b></td>	
						<?php
							if($ss_tipo_trabajo!="TODAS")
							{
								$sql_tt = "SELECT nombre  FROM `tipo_trabajo` WHERE id = '".$ss_tipo_trabajo."' LIMIT 1";
								$res_tt = mysql_query($sql_tt);
								$row_tt = mysql_fetch_array($res_tt);
								$ss_tipo_trabajo = $row_tt["nombre"];
							}
						?>
						<td>Tipo de trabajo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan=3><b><?php echo $ss_tipo_trabajo; ?></b></td>	
							
					</tr>
					
					<tr>
						<td >Tramite: </td><td><b><?php echo $ss_tipo_tramite; ?></b></td>
						<td >Fecha Inicial: </td><td><b><font color=red><?php echo $ss_fecha_inicial; ?></font></b></td>
						<td >Fecha Final: </td><td><b><font color=red><?php echo $ss_fecha_final; ?></font></b></td>				
					</tr>
				</table>
				<hr>
					<table width=100%>
						<td align=center width=33%>
						<center>
						Tramites pre liquidadas (Automatico)<br>
								<font color=red><b>$ <?php echo moneda($row["valor_total"]);  ?></b></font>
						</center>
						</td>
						<td align=center width=33%>
						<center>
						Tramites Liquidadas (Oficial)<br>
								<font color=red><b>$ <?php echo moneda($row["total_liquidado"]);  ?></b></font>
						</center>
						</td>
						
						<td align=center width=33%>
						<?php
						$sql = " SELECT SUM(tramite.valor_liquidado) AS liquidado,  COUNT(*) AS cantidad_liq, 
								SUM(IF(contratista_valor=2,tramite.valor_liquidado,0)) AS aceptado,
								SUM(IF(contratista_valor=2,1,0)) AS cantidad_acep 
								FROM tramite WHERE tramite.estado_liquidacion=2 AND ".$where." ";
								$res = mysql_query($sql);
								$row = mysql_fetch_array($res);
						?>
						
						<center>
						Tramites Aceptadas EIA (<b><?php echo $row["cantidad_acep"];  ?></b> de <b><?php echo $row["cantidad_liq"];  ?></b>)<br>
								<font color=red><b>$ <?php echo moneda($row["aceptado"]);  ?></b></font>
						</center>
						</td>
						
					</table>
				
			</div>
		</div>
	</div>
</center>

<table width=100%>
	<tr>
		<td>
			<form  action="modulos/liquidacion/detalle.php" target="blank" method="post" name="form" id="form"  enctype="multipart/form-data">
				<div class="row">
				   <div class="col-lg-6">
					<div class="input-group">
					  <input type="text" class="form-control" name="ot" placeholder="Ingresar OT..." required>
					  <span class="input-group-btn">
						<button class="btn btn-primary"  type="submit">Buscar</button>
					  </span>
					</div>
				  </div>
				</div>
			</form>
		</td>
		<td align=right>
			<a href="modulos/liquidacion/excel.php?tip=10" target="blank"><i class="fa fa-cloud-download fa-2x"></i> Descargar toda la consulta </a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php if(!$_SESSION["detalle"]){ ?>
				<a href="?detalle=s"> <i class="fa fa-search-plus fa-2x"></i> Ver mas detalles de lo tramites </a> 
			<?php }else{ ?>
				<a href="?detalle=n"> <i class="fa fa-search-minus fa-2x"></i> Compactar detalles de lo tramites </a> 
			<?php } ?>
		</td>
	</tr>
</table>
<br>

<!-- SIN DETALLE -->
<?php if(!$_SESSION["detalle"]){ ?>
				<table width=100%>
					<tr>
						<td width=49% valign=top>
							<div class="col-md-100 col-sm-100" style="width:100%">
								<div class="panel panel-primary" >
									<div class="panel-heading"  align=left>
										<b>Tramites por tecnologia</b>							
									</div>
									<div class="panel-body">
										<table align=center width=100% class="table-striped table-bordered table-hover">
											<tr>
												<th>Tecnologia</th>
												<th>Cant.</th>
												<th>Pre-liqui.</th>
												<th>Liqui.</th>
												<th><center>Excel</center></th>
												<th><center>Gest.</center></th>
											</tr>
											<?php
										
											$select_sup = ", idtecnologia, nomtecnologia, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado  "; 
											$select = ", tramite.valor_liquidado AS vl, tecnologia.id AS idtecnologia, tecnologia.nombre AS nomtecnologia"; 
											$where_sup = " GROUP BY idtecnologia";
											$res = tramite_liquidacion($select, $where, $select_sup,  $where_sup, '1');
											while($row = mysql_fetch_array($res))
											{
												?>
												<tr>
													<td><?php echo $row["nomtecnologia"] ?></td>
													<td><?php echo $row["cantidad"] ?></td>
													<td align=right><b>$<?php echo moneda($row["valor_total"]) ?></b></td>
													<td align=right><b><font color=red>$<?php echo moneda($row["total_liquidado"]) ?></font></b></td>
													<td align=center><a href="modulos/liquidacion/excel.php?tip=1&criterio=<?php echo $row["idtecnologia"]; ?>" target="blank"><b><i class="fa fa-cloud-download"></i></b></a></td>
													<td align=center><a href="?cmp=gestionar_lista&tip=1&criterio=<?php echo $row["idtecnologia"]; ?>"><b><i class="fa fa-pencil-square-o"></i></b></a></td>
												</tr>
												<?php
											}							
											?>							
										</table>
									</div>
								</div>
							</div><br>
							<div class="col-md-100 col-sm-100" style="width:100%">
								<div class="panel panel-primary" >
									<div class="panel-heading"  align=left>
										<b>Tramites por servicio</b>							
									</div>
									<div class="panel-body">
										<table align=center width=100% class="table-striped table-bordered table-hover">
											<tr>
												<th>Servicio</th>
												<th>Cant.</th>
												<th>Pre-liqui.</th>
												<th>Liqui.</th>
												<th><center>Excel</center></th>
												<th><center>Gest.</center></th>
											</tr>
											<?php
										
											$select_sup = ",  nomservicio, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado  "; 
											$select = ", tramite.valor_liquidado AS vl,  liquidacion_zona.servicio AS nomservicio"; 
											$where_sup = " GROUP BY nomservicio";
											$res = tramite_liquidacion($select, $where, $select_sup,  $where_sup, '1');											
											while($row = mysql_fetch_array($res))
											{
												?>
												<tr>
													<td><?php echo $row["nomservicio"] ?></td>
													<td><?php echo $row["cantidad"] ?></td>
													<td align=right><b>$<?php echo moneda($row["valor_total"]) ?></b></td>
													<td align=right><b><font color=red>$<?php echo moneda($row["total_liquidado"]) ?></font></b></td>
													<td align=center><a href="modulos/liquidacion/excel.php?tip=3&criterio=<?php echo str_replace("+", "___", $row["nomservicio"]); ?>" target="blank"><b><i class="fa fa-cloud-download"></i></b></a></td>
													<td align=center><a href="?cmp=gestionar_lista&tip=3&criterio=<?php echo str_replace("+", "___", $row["nomservicio"]); ?>"><b><i class="fa fa-pencil-square-o"></i></b></a></td>
												</tr>
												<?php
											}							
											?>
										</table>
									</div>
								</div>
							</div><br>
							
						</td>
						<td width="2%">
						
						</td>
						<td valign=top  width="49%">
							<div class="col-md-100 col-sm-100" style="width:100%">
								<div class="panel panel-primary" >
									<div class="panel-heading"  align=left>
										<b>Tramites por departamento</b>
									</div>
									<div class="panel-body">
										<table align=center width=100% class="table-striped table-bordered table-hover">
											<tr>
												<th>Departamento</th>
												<th>Cant.</th>
												<th>Pre-liqui.</th>
												<th>Liqui.</th>
												<th><center>Excel</center></th>
												<th><center>Gest.</center></th>
											</tr>
											<?php
																					
											
											$select_sup = ",  nomdepartamento, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado  "; 
											$select = ", tramite.valor_liquidado AS vl,  tramite.departamento AS nomdepartamento"; 
											$where_sup = " GROUP BY nomdepartamento";
											$res = tramite_liquidacion($select, $where, $select_sup,  $where_sup, '1');			
											

											while($row = mysql_fetch_array($res))
											{
												?>
												<tr>
													<td><?php echo $row["nomdepartamento"] ?></td>
													<td><?php echo $row["cantidad"] ?></td>
													<td align=right><b>$<?php echo moneda($row["valor_total"]) ?></b></td>
													<td align=right><b><font color=red>$<?php echo moneda($row["total_liquidado"]) ?></font></b></td>
													<td align=center><a href="modulos/liquidacion/excel.php?tip=2&criterio=<?php echo $row["nomdepartamento"]; ?>" target="blank"><b><i class="fa fa-cloud-download"></i></b></a></td>
													<td align=center><a href="?cmp=gestionar_lista&tip=2&criterio=<?php echo $row["nomdepartamento"]; ?>"><b><i class="fa fa-pencil-square-o"></i></b></a></td>
												</tr>
												<?php
											}							
											?>
										</table>
									</div>
								</div>
							</div><br>
							<div class="col-md-100 col-sm-100" style="width:100%">
								<div class="panel panel-primary" >
									<div class="panel-heading"  align=left>
										<b>Tramites por Item</b>
									</div>
									<div class="panel-body">
										<table align=center width=100% class="table-striped table-bordered table-hover">
											<tr>
												<th>Item</th>
												<th>Cant.</th>
												<th>Pre-liqui.</th>
												<th>Liqui.</th>
												<th><center>Excel</center></th>
												<th><center>Gest.</center></th>
											</tr>
											<?php
																					
											$select_sup = ",  nomitem, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado  "; 
											$select = ", tramite.valor_liquidado AS vl,  liquidacion_zona.item AS nomitem"; 
											$where_sup = " GROUP BY nomitem";
											$res = tramite_liquidacion($select, $where, $select_sup,  $where_sup, '1');
											while($row = mysql_fetch_array($res))
											{
												?>
												<tr>
													<td><?php echo $row["nomitem"] ?></td>
													<td><?php echo $row["cantidad"] ?></td>
													<td align=right><b>$<?php echo moneda($row["valor_total"]) ?></b></td>
													<td align=right><b><font color=red>$<?php echo moneda($row["total_liquidado"]) ?></font></b></td>
													<td align=center><a href="modulos/liquidacion/excel.php?tip=4&criterio=<?php echo  str_replace("+", "___", $row["nomitem"]); ?>" target="blank"><b><i class="fa fa-cloud-download"></i></b></a></td>
													<td align=center><a href="?cmp=gestionar_lista&tip=4&criterio=<?php echo  str_replace("+", "___", $row["nomitem"]); ?>"><b><i class="fa fa-pencil-square-o"></i></b></a></td>
												</tr>
												<?php
											}							
											?>
										</table>
									</div>
								</div>
							</div>
						</td>
						
					</tr>
				</table>
				
				<div class="col-md-100 col-sm-100" style="width:100%">
						<div class="panel panel-primary" >
							<div class="panel-heading"  align=left>
								<b>Tramites por tipo de trabajo</b>							
							</div>
							<div class="panel-body">
								<table  class="table table-striped table-bordered table-hover" align=center width=100%>
									<tr>
										<th>Tipo de Trabajo</th>
										<th>Codigo</th>
										<th>Cant.</th>
										<th><font color=red>Rech.<br>EIA</font></th>
										<th><font color=green>Acep.<br>EIA</font></th>
										<th>Pre-liquidado</th>
										<th>Liquidacion oficial</th>
										<th><center>Excel</center></th>
										<th><center>Gest.</center></th>
									</tr>
									<?php
															
										$select_sup = ", COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado, nomtt, codtt, idtt,  SUM( IF(contratista_valor=2,1,0)) AS aceptados,  
															SUM( IF(contratista_valor=3,1,0)) AS rechazados  "; 
										$select = ", tramite.valor_liquidado AS vl, tipo_trabajo.codigo AS codtt, tipo_trabajo.nombre AS nomtt,
										tramite.id_tipo_trabajo AS idtt, contratista_valor"; 
										$where_sup = " GROUP BY idtt";
										$res = tramite_liquidacion($select, $where, $select_sup,  $where_sup, '1');
										while($row = mysql_fetch_array($res))
										{
											
											?>
											<tr>
												<td><?php echo $row["nomtt"] ?></td>
												<td><?php echo $row["codtt"] ?></td>
												<td><?php echo $row["cantidad"] ?></td>
												<td align=center> <font color=red><?php echo $row["rechazados"] ?></font></td>
												<td align=center> <font color=green><?php echo $row["aceptados"] ?></font></td>											
												<td align=right><b>$<?php echo moneda($row["valor_total"]) ?></b></td>
												<td align=right><b><font color=red>$<?php echo moneda($row["total_liquidado"]) ?></font></b></td>
												<td align=center><a href="modulos/liquidacion/excel.php?tip=5&criterio=<?php echo $row["idtt"]; ?>" target="blank"><b><i class="fa fa-cloud-download"></i></b></a></td>
												<td align=center><a href="?cmp=gestionar_lista&tip=5&criterio=<?php echo $row["idtt"]; ?>"><b><i class="fa fa-pencil-square-o"></i></b></a></td>
											</tr>
											<?php
										}							
										?>		
								</table>
							</div>
						</div>
					</div>

			
<?php }else{ ?>

		<?php
			$select_tr = '';
			if($_SESSION["ss_tipo_tramite"]==8)
			{
				$select_tr = ", IF(tipo_trabajo.tipo = '7' OR tramite.tipo_paquete='Traslado',1,0) AS total_traslado,
				IF(tipo_trabajo.tipo = '1' AND  tramite.tipo_paquete!='Traslado',1,0 ) AS total_instalacion ";	
			}	
			
			
		?>
		<center>	
									
					<div class="col-md-100 col-sm-100" style="width:100%">
								<div class="panel panel-primary" >
									<div class="panel-heading"  align=left>
										<b>Tramites por tecnologia</b>							
									</div>
									<div class="panel-body">
										<table align=center width=100% class="table table-striped table-bordered table-hover" >
											<tr>
												<th>Tecnologia</th>
												<th>Cant.</th>
												<?php if($_SESSION["ss_tipo_tramite"]==8){ ?>
													<th>Cant. Tras.</th>
													<th>Cant. Inst.</th>
												<?php } ?>
												<th>Total Caja<br> Adicional.</th>
												<th>Prom. Caja<br> Adicional.</th>
												<th>Total Ext.</th>
												<th>Prom. Ext.</th>
												<th>Pre-liquidado</th>
												<th>Valor Prom. </th>
												<th><center>Excel</center></th>
												<th><center>Gest.</center></th>
											</tr>
											<?php
											if($_SESSION["ss_tipo_tramite"]==8)
											{									
												$select_sup = ", idtecnologia, nomtecnologia, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado, 
															SUM(tem_adicional) AS ta, SUM(tem_extension) AS te, SUM(total_traslado) AS ttt, 
															SUM(total_instalacion) AS tti	"; 
											}else
											{
												$select_sup = ", idtecnologia, nomtecnologia, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado, 
															SUM(tem_adicional) AS ta, SUM(tem_extension) AS te	"; 
											}			
															
															
															
											$select = $select_tr.", tramite.valor_liquidado AS vl, tecnologia.id AS idtecnologia, tecnologia.nombre AS nomtecnologia"; 
											$where_sup = " GROUP BY idtecnologia";
											$res = tramite_liquidacion($select, $where, $select_sup,  $where_sup, '1');
											while($row = mysql_fetch_array($res))
											{
												$promedio_valor = $row["valor_total"]/$row["cantidad"];
												$promedio_caja = $row["ta"]/$row["cantidad"];
												$promedio_ext = $row["te"]/$row["cantidad"];
												?>
												<tr>
													<td><?php echo $row["nomtecnologia"] ?></td>
													<td><b><?php echo $row["cantidad"] ?></b></td>
													<?php if($_SESSION["ss_tipo_tramite"]==8){ ?>
														<td><?php echo $row["ttt"] ?></td>
														<td><?php echo $row["tti"] ?></td>													
													<?php } ?>
													<td><b><?php echo $row["ta"] ?></b></td>
													<td><?php echo round($promedio_caja,2) ?></td>
													<td><b><?php echo $row["te"] ?></b></td>
													<td><?php echo round($promedio_ext) ?></td>
													<td align=right><b>$<?php echo moneda($row["valor_total"]) ?></b></td>
													<td align=right>$<?php echo moneda($promedio_valor) ?></td>
													<td align=center><a href="modulos/liquidacion/excel.php?tip=1&criterio=<?php echo $row["idtecnologia"]; ?>" target="blank"><b><i class="fa fa-cloud-download"></i></b></a></td>
													<td align=center><a href="?cmp=gestionar_lista&tip=1&criterio=<?php echo $row["idtecnologia"]; ?>"><b><i class="fa fa-pencil-square-o"></i></b></a></td>
												</tr>
												<?php
											}							
											?>							
										</table>
									</div>
								</div>
							</div><br>
							
							<div class="col-md-100 col-sm-100" style="width:100%">
								<div class="panel panel-primary" >
									<div class="panel-heading"  align=left>
										<b>Tramites por departamento</b>
									</div>
									<div class="panel-body">
										<table align=center width=100% class="table table-striped table-bordered table-hover" >
											<tr>
												<th>Departamento</th>
												<th>Cant.</th>
												<?php if($_SESSION["ss_tipo_tramite"]==8){ ?>
													<th>Cant. Tras.</th>
													<th>Cant. Inst.</th>
												<?php } ?>
												<th>Total Caja<br> Adicional.</th>
												<th>Prom. Caja<br> Adicional.</th>
												<th>Total Ext.</th>
												<th>Prom. Ext.</th>
												<th>Pre-liquidado</th>
												<th>Valor Prom. </th>
												<th><center>Excel</center></th>
												<th><center>Gest.</center></th>
											</tr>
											<?php
											
											
											if($_SESSION["ss_tipo_tramite"]==8)
											{		
													$select_sup = ", nomdepartamento, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado, 
															SUM(tem_adicional) AS ta, SUM(tem_extension) AS te, SUM(total_traslado) AS ttt, 
															SUM(total_instalacion) AS tti	"; 
												
											}else
											{
												$select_sup = ", nomdepartamento, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado, 
															SUM(tem_adicional) AS ta, SUM(tem_extension) AS te	"; 
											}	
											
											
											$select = $select_tr.", tramite.valor_liquidado AS vl,  tramite.departamento AS nomdepartamento"; 
											$where_sup = " GROUP BY nomdepartamento";
											$res = tramite_liquidacion($select, $where, $select_sup,  $where_sup, '1');
											while($row = mysql_fetch_array($res))
											{
												$promedio_valor = $row["valor_total"]/$row["cantidad"];
												$promedio_caja = $row["ta"]/$row["cantidad"];
												$promedio_ext = $row["te"]/$row["cantidad"];
												?>
												<tr>
													<td><?php echo $row["nomdepartamento"] ?></td>
													<td><b><?php echo $row["cantidad"] ?></b></td>
													<?php if($_SESSION["ss_tipo_tramite"]==8){ ?>
														<td><?php echo $row["ttt"] ?></td>
														<td><?php echo $row["tti"] ?></td>													
													<?php } ?>
													<td><b><?php echo $row["ta"] ?></b></td>
													<td><?php echo round($promedio_caja,2) ?></td>
													<td><b><?php echo $row["te"] ?></b></td>
													<td><?php echo round($promedio_ext) ?></td>
													<td align=right><b>$<?php echo moneda($row["valor_total"]) ?></b></td>
													<td align=right>$<?php echo moneda($promedio_valor) ?></td>
													<td align=center><a href="modulos/liquidacion/excel.php?tip=2&criterio=<?php echo $row["nomdepartamento"]; ?>" target="blank"><b><i class="fa fa-cloud-download"></i></b></a></td>
													<td align=center><a href="?cmp=gestionar_lista&tip=2&criterio=<?php echo $row["nomdepartamento"]; ?>"><b><i class="fa fa-pencil-square-o"></i></b></a></td>
												</tr>
												<?php
											}							
											?>
										</table>
									</div>
								</div>
							</div><br>
							<div class="col-md-100 col-sm-100" style="width:100%">
								<div class="panel panel-primary" >
									<div class="panel-heading"  align=left>
										<b>Tramites por Item</b>
									</div>
									<div class="panel-body">
										<table align=center width=100% class="table table-striped table-bordered table-hover" >
											<tr>
												<th>Item</th>
												<th>Cant.</th>
												<?php if($_SESSION["ss_tipo_tramite"]==8){ ?>
													<th>Cant. Tras.</th>
													<th>Cant. Inst.</th>
												<?php } ?>
												<th>Total Caja<br> Adicional.</th>
												<th>Prom. Caja<br> Adicional.</th>
												<th>Total Ext.</th>
												<th>Prom. Ext.</th>
												<th>Pre-liquidado</th>
												<th>Valor Prom. </th>
												<th><center>Excel</center></th>
												<th><center>Gest.</center></th>
											</tr>
											<?php
															
											if($_SESSION["ss_tipo_tramite"]==8)
											{		
													$select_sup = ", nomitem, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado, 
															SUM(tem_adicional) AS ta, SUM(tem_extension) AS te, SUM(total_traslado) AS ttt, 
															SUM(total_instalacion) AS tti	";  
												
											}else
											{
												$select_sup = ", nomitem, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado, 
															SUM(tem_adicional) AS ta, SUM(tem_extension) AS te	"; 
											}	
											
											
															
															
											$select = $select_tr.", tramite.valor_liquidado AS vl,  liquidacion_zona.item AS nomitem"; 
											$where_sup = " GROUP BY nomitem";
											$res = tramite_liquidacion($select, $where, $select_sup,  $where_sup, '1');
											while($row = mysql_fetch_array($res))
											{
												$promedio_valor = $row["valor_total"]/$row["cantidad"];
												$promedio_caja = $row["ta"]/$row["cantidad"];
												$promedio_ext = $row["te"]/$row["cantidad"];
												?>
												<tr>
													<td><?php echo $row["nomitem"] ?></td>
													<td><b><?php echo $row["cantidad"] ?></b></td>
													<?php if($_SESSION["ss_tipo_tramite"]==8){ ?>
														<td><?php echo $row["ttt"] ?></td>
														<td><?php echo $row["tti"] ?></td>													
													<?php } ?>
													<td><b><?php echo $row["ta"] ?></b></td>
													<td><?php echo round($promedio_caja,2) ?></td>
													<td><b><?php echo $row["te"] ?></b></td>
													<td><?php echo round($promedio_ext) ?></td>
													<td align=right><b>$<?php echo moneda($row["valor_total"]) ?></b></td>
													<td align=right>$<?php echo moneda($promedio_valor) ?></td>
													<td align=center><a href="modulos/liquidacion/excel.php?tip=4&criterio=<?php echo  str_replace("+", "___", $row["nomitem"]); ?>" target="blank"><b><i class="fa fa-cloud-download"></i></b></a></td>
													<td align=center><a href="?cmp=gestionar_lista&tip=4&criterio=<?php echo  str_replace("+", "___", $row["nomitem"]); ?>"><b><i class="fa fa-pencil-square-o"></i></b></a></td>
												</tr>
												<?php
											}							
											?>
										</table>
									</div>
								</div>
							</div>
							
							
							<div class="col-md-100 col-sm-100" style="width:100%">
								<div class="panel panel-primary" >
									<div class="panel-heading"  align=left>
										<b>Tramites por servicio</b>							
									</div>
									<div class="panel-body">
										<table align=center width=100% class="table table-striped table-bordered table-hover" >
											<tr>
												<th>Servicio</th>
												<th>Cant.</th>
												<?php if($_SESSION["ss_tipo_tramite"]==8){ ?>
													<th>Cant. Tras.</th>
													<th>Cant. Inst.</th>
												<?php } ?>
												<th>Total <br>CPE(XDSL)</th>
												<th>Prom. <br>CPE(XDSL)</th>
												<th>Total <br>STBOX</th>
												<th>Prom. <br>STBOX</th>
												<th>Pre-liquidado</th>
												<th>Valor Prom. </th>
												<th><center>Excel</center></th>
												<th><center>Gest.</center></th>
											</tr>
											<?php
											
											if($_SESSION["ss_tipo_tramite"]==8)
											{		
													$select_sup = ", nomservicio, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado, 
															SUM(tem_adicional) AS ta, SUM(tem_extension) AS te, SUM(total_traslado) AS ttt, 
															SUM(total_instalacion) AS tti	";
											}else
											{
												$select_sup = ", nomservicio, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado, 
															SUM(cpe_xdsl) AS tot_cpe, SUM(stbox) AS tot_stbox	";
											}	
										
											 
											$select = $select_tr.", tramite.valor_liquidado AS vl,  liquidacion_zona.servicio AS nomservicio"; 
											$where_sup = " GROUP BY nomservicio";
											$res = tramite_liquidacion($select, $where, $select_sup,  $where_sup, '1');
											while($row = mysql_fetch_array($res))
											{
												$promedio_valor = $row["valor_total"]/$row["cantidad"];
												$promedio_cpe = $row["tot_cpe"]/$row["cantidad"];
												$promedio_stbox = $row["tot_stbox"]/$row["cantidad"];
												?>
												<tr>
													<td><?php echo $row["nomservicio"] ?></td>
													<td><b><?php echo $row["cantidad"] ?></b></td>
													<?php if($_SESSION["ss_tipo_tramite"]==8){ ?>
														<td><?php echo $row["ttt"] ?></td>
														<td><?php echo $row["tti"] ?></td>													
													<?php } ?>
													<td><b><?php echo $row["tot_cpe"] ?></b></td>
													<td><?php echo round($promedio_cpe,2) ?></td>
													<td><b><?php echo $row["tot_stbox"] ?></b></td>
													<td><?php echo round($promedio_stbox) ?></td>
													<td align=right><b>$<?php echo moneda($row["valor_total"]) ?></b></td>
													<td align=right>$<?php echo moneda($promedio_valor) ?></td>
													<td align=center><a href="modulos/liquidacion/excel.php?tip=3&criterio=<?php echo  str_replace("+", "___", $row["nomservicio"]); ?>" target="blank"><b><i class="fa fa-cloud-download"></i></b></a></td>
													<td align=center><a href="?cmp=gestionar_lista&tip=3&criterio=<?php echo  str_replace("+", "___", $row["nomservicio"]); ?>"><b><i class="fa fa-pencil-square-o"></i></b></a></td>
												</tr>
												<?php
											}							
											?>
										</table>
									</div>
								</div>
							</div><br>
					
					<div class="col-md-100 col-sm-100" style="width:100%">
						<div class="panel panel-primary" >
							<div class="panel-heading"  align=left>
								<b>Tramites por tipo de trabajo</b>							
							</div>
							<div class="panel-body">
								<table  class="table table-striped table-bordered table-hover" align=center width=100%>
									<tr>
										<th>Tipo de Trabajo</th>
										<th>Codigo</th>
										<th>Cant.</th>
										<th><font color=red>Rech.<br>EIA</font></th>
										<th><font color=green>Acep.<br>EIA</font></th>
										
										<?php if($_SESSION["ss_tipo_tramite"]==8){ ?>
											<th>Cant. Tras.</th>
											<th>Cant. Inst.</th>
										<?php } ?>
										<th>Total Caja<br> Adicional.</th>
										<th>Prom. Caja<br> Adicional.</th>
										<th>Total Ext.</th>
										<th>Prom. Ext.</th>
										<th>Pre-liquidado</th>
										<th>Valor Prom. </th>
										<th><center>Excel</center></th>
										<th><center>Gest.</center></th>
									</tr>
									<?php
									
										if($_SESSION["ss_tipo_tramite"]==8)
											{		
													$select_sup = ", idtt, nomtt, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado, codtt,
															SUM(tem_adicional) AS ta, SUM(tem_extension) AS te, SUM(total_traslado) AS ttt, 
															SUM(total_instalacion) AS tti, SUM( IF(contratista_valor=2,1,0)) AS aceptados,  
															SUM( IF(contratista_valor=3,1,0)) AS rechazados	"; 
											}else
											{
												$select_sup = ", idtt, nomtt, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado, codtt,
															SUM(tem_adicional) AS ta, SUM(tem_extension) AS te, SUM( IF(contratista_valor=2,1,0)) AS aceptados,  
															SUM( IF(contratista_valor=3,1,0)) AS rechazados	"; 
											}
																	
										
											$select = $select_tr.", contratista_valor, tramite.valor_liquidado AS vl, tramite.id_tipo_trabajo AS idtt, tipo_trabajo.nombre AS nomtt,  tipo_trabajo.codigo AS codtt"; 
											$where_sup = " GROUP BY idtt";
											$res = tramite_liquidacion($select, $where, $select_sup,  $where_sup, '1');
										while($row = mysql_fetch_array($res))
										{
											$promedio_valor = $row["valor_total"]/$row["cantidad"];
												$promedio_caja = $row["ta"]/$row["cantidad"];
												$promedio_ext = $row["te"]/$row["cantidad"];
											?>
											<tr>
												<td><?php echo $row["nomtt"] ?></td>
												<td><?php echo $row["codtt"] ?></td>
												<td><b><?php echo $row["cantidad"] ?></b></td>
												<td align=center> <font color=red><?php echo $row["rechazados"] ?></font></td>
												<td align=center> <font color=green><?php echo $row["aceptados"] ?></font></td>												
												
													<?php if($_SESSION["ss_tipo_tramite"]==8){ ?>
														<td><?php echo $row["ttt"] ?></td>
														<td><?php echo $row["tti"] ?></td>													
													<?php } ?>
													<td><b><?php echo $row["ta"] ?></b></td>
													<td><?php echo round($promedio_caja,2) ?></td>
													<td><b><?php echo $row["te"] ?></b></td>
													<td><?php echo round($promedio_ext) ?></td>
													<td align=right><b>$<?php echo moneda($row["valor_total"]) ?></b></td>
													<td align=right>$<?php echo moneda($promedio_valor) ?></td>
												<td align=center><a href="modulos/liquidacion/excel.php?tip=5&criterio=<?php echo $row["idtt"]; ?>" target="blank"><b><i class="fa fa-cloud-download"></i></b></a></td>
												<td align=center><a href="?cmp=gestionar_lista&tip=5&criterio=<?php echo $row["idtt"]; ?>"><b><i class="fa fa-pencil-square-o"></i></b></a></td>
											</tr>
											<?php
										}							
										?>		
								</table>
							</div>
						</div>
					</div>
					
					
				</center>

<?php } ?>

