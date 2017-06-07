<?php
if($PERMISOS_GC["sto_coor"]!='Si')
{
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


		

function recorrido_fecha($fechauno, $fechados)
{
	$fechaaamostar = $fechauno;
	while(strtotime($fechados) >= strtotime($fechauno))
	{
		if(strtotime($fechados) != strtotime($fechaaamostar))
		{
			$vector[] = $fechaaamostar;
			$fechaaamostar = date("Y-m-d", strtotime($fechaaamostar . " + 1 day"));				
		}
		else
		{
			$vector[] = $fechaaamostar; 
			break;
		}	
	}	
	return $vector;
}


$sql_per = "SELECT * FROM sto_periodo WHERE estado = 1 LIMIT 1";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);
$vector = recorrido_fecha($row_per["fecha_inicial"],  $row_per["fecha_final"]);

$vector_rol[] = "Iniciar";
$sql_rol = "SELECT sto_ejecutado.fecha 
FROM  `sto_ejecutado`
INNER JOIN sto_item ON sto_ejecutado.id_item = sto_item.id 
WHERE 
fecha >= '".$row_per["fecha_inicial"]."' 
AND sto_ejecutado.fecha <= '".$row_per["fecha_final"]."' 
AND sto_ejecutado.id_periodo = '".$row_per["id"]."'
AND sto_item.tipo=1
AND id_persona = '".$_GET["idp"]."' ;  ";
$res_rol = mysql_query($sql_rol);
while($row_rol = mysql_fetch_array($res_rol))
{$vector_rol[] = $row_rol["fecha"];}


if($_POST["guardar"])
{	
	
	$sql_del = "DELETE FROM `sto_ejecutado_cmv` WHERE `id_periodo` = '".$row_per["id"]."'  AND id_persona = '".$_GET["idp"]."'  ";
	mysql_query($sql_del);	

	
	foreach ($vector AS $k => &$valor) 
	{
		$tem =  'ch_'.$valor.'_ad';
		if($_POST[$tem])
		{
				$valor_ad =   12393.60;
			 $sql_g = "INSERT INTO `sto_ejecutado_cmv` 
				(`id_persona`,`id_periodo`, `valor`, `fecha`, `tipo`) 
				VALUES ('".$_GET["idp"]."', '".$row_per["id"]."', '".$valor_ad."', '".$valor."', '1');";
			mysql_query($sql_g);			
		}

		$tem =  'ch_'.$valor.'_ah';
		if($_POST[$tem])
		{
				$valor_ah =   76427.2;
			 $sql_g = "INSERT INTO `sto_ejecutado_cmv` 
				(`id_persona`,`id_periodo`, `valor`, `fecha`, `tipo`) 
				VALUES ('".$_GET["idp"]."', '".$row_per["id"]."', '".$valor_ah."', '".$valor."', '2');";
			mysql_query($sql_g);			
		}
		
	}					
}



$sql_person = "SELECT sto_persona.nombre ,  sto_persona.fecha_registro, sto_sap.cuenta AS cue_sap,
						sto_plataforma.nombre AS nom_plataforma, sto_sap.nombre AS nom_sap, sto_car.nombre AS nom_car, sto_rubro.nombre AS nom_rubro,
						sto_persona.id, sto_persona.identificacion, municipio.nombre AS nom_municipio
			   FROM sto_persona 
				INNER JOIN sto_plataforma ON sto_persona.id_plataforma = sto_plataforma.id
				INNER JOIN sto_sap ON sto_persona.id_sap = sto_sap.id
				LEFT JOIN sto_car ON sto_persona.id_car = sto_car.id
				INNER JOIN sto_rubro ON sto_persona.id_rubro = sto_rubro.id	
				LEFT JOIN municipio ON sto_persona.id_municipio = municipio.id
				WHERE sto_persona.id = '".$_GET["idp"]."'
				LIMIT 1 ";
$res_person = mysql_query($sql_person);
$row_person = mysql_fetch_array($res_person);

$sql_per = "SELECT * FROM sto_periodo WHERE estado = 1 LIMIT 1";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);


$sql_cerrado = "SELECT id FROM sto_periodo_usuario_cmv WHERE id_periodo = '".$row_per["id"]."' AND id_usuario = '".$_SESSION["user_id"]."' LIMIT 1";
$res_cerrado = mysql_query($sql_cerrado);
$row_cerrado = mysql_fetch_array($res_cerrado);
if($row_cerrado["id"] || $row_per["estado"]==2){
	$bloqueo = ' disabled ';
 } 

?> 
<h2>GESTIONAR PERIODO DE <b><?php echo $row_per["fecha_inicial"] ?></b> 
AL <b><?php echo $row_per["fecha_final"] ?></b> PARA VIATICOS <b><?php echo $row_item["nombre"] ?></b> </h2> 

<div align=right>
	<a href="?cmp=lista_tupersonal_cmv"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de tu personal </a>
</div> 

<?php
$meses=array(1=>"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio","Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
?>	
<style>
	#calendar {
		font-family:Arial;
		font-size:12px;
		
	
	}
	#calendar caption {
		text-align:left;
		padding:5px 10px;
		background-color:#003366;
		color:#fff;
		font-weight:bold;
	}
	#calendar th {
		background-color:#006699;
		color:#fff;
		width:70px;
		
	}
	#calendar td {
		text-align:right;
		padding:2px 5px;
		background-color:silver;
		border: 1px solid #FFFFFF;
	}
	#calendar .hoy {
		background-color:red;
	}
</style>



<h4>
	<table width=100%>
		<tr>
			<td>Tecnico: <b><?php echo $row_person["nombre"] ?></b></td>
			<td>Plataforma: <b><?php echo $row_person["nom_plataforma"] ?></b></td>		
		</tr>		
		<tr>
			<td>Identificacion: <b><?php echo $row_person["identificacion"] ?></b></td>
			<td>Localidad: <b><?php echo utf8_encode($row_person["nom_municipio"]) ?></b></td>		
		</tr>
		<tr><td colspan=2><br></td></tr>
		<tr>
			<td colspan=2>
			<font color=red>Ali.Dia</font>: ALIMENTACION DIA<br> 
			<font color=red>Ali+Hot.</font>: ALIEMENTACION + ALOJAMIENTO PERNOCTADO<br>
			</td>
		</tr>
		
	</table>
</h4>

<?php


?>
<form action="?idp=<?php echo $_GET["idp"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<table width=100%>
		<tr>
			<td width=49%  valign=top>
			<?php
				$month =  0 + substr($row_per["fecha_inicial"], 5, 2);
				$year = substr($row_per["fecha_inicial"], 0, 4);;
				$diaSemana=date("w",mktime(0,0,0,$month,1,$year))+7;
				$ultimoDiaMes=date("d",(mktime(0,0,0,$month+1,1,$year)-1)); 
			?>
					<table id="calendar" style="border=30px">
						<caption><?php echo $meses[$month]." ".$year?></caption>
						<tr>
							<th><center>Lun</center></th><th><center>Mar</center></th><th><center>Mie</center></th><th><center>Jue</center></th><th><center>Vie</center></th><th><center>Sab</center></th><th><center>Dom</center></th>
						</tr>
						<tr bgcolor="silver">
							<?php
							$last_cell=$diaSemana+$ultimoDiaMes;
							for($i=1;$i<=42;$i++)
							{			
								if($i==$diaSemana)
								{	$day=1;	}
								if($i<$diaSemana || $i>=$last_cell)
								{	echo "<td>&nbsp;</td>";	}
								else
								{
									if($day<10){$ii = '0'.$day;}else{$ii = $day;}
									if($month<10){$mm = '0'.$month;}else{$mm = $month;}
									$fecha_comp = $year.'-'.$mm.'-'.$ii ;
									
									$sql_eje = "SELECT id, tipo FROM sto_ejecutado_cmv WHERE 
									id_persona = '".$_GET["idp"]."' AND fecha ='".$fecha_comp."' LIMIT 1";
									$res_eje = mysql_query($sql_eje);
									$row_eje = mysql_fetch_array($res_eje);
									$color_cel = ""; $checked1 = ""; $checked2 = ""; 
									if($row_eje["id"])
									{
										$color_cel = " style='background-color:#81F781;'"; 
										if($row_eje["tipo"]==1){$checked1 = " checked ";}
										if($row_eje["tipo"]==2){$checked2 = " checked ";}							
									}	
									$campo = 'x'.str_replace("-", "", $fecha_comp);	
									
									$sql_neje = "SELECT id, observacion FROM sto_no_ejecutado WHERE id_item = '".$_GET["idi"]."' AND 
									id_persona = '".$_GET["idp"]."' AND fecha ='".$fecha_comp."' LIMIT 1";
									$res_neje = mysql_query($sql_neje);
									$row_neje = mysql_fetch_array($res_neje);
									$observacion_dis[$fecha_comp] = $row_neje["observacion"]; 
									
									?>
									<td <?php echo $color_cel ?> valign=top> 
										<b><?php echo $day; ?></b>  										
										<?php if (in_array($fecha_comp, $vector) && in_array($fecha_comp, $vector_rol)) { ?>					
										<div align=left>		
													<input onclick="javascript:valida_<?php echo $campo ?>_ad(this.checked)" <?php echo $checked1; ?> <?php echo $bloqueo; ?> style="width:15px;height:15px;" type='checkbox' name='ch_<?php echo $fecha_comp ?>_ad' id="ch_<?php echo $campo ?>_ad">Ali.Dia<br>
													<input onclick="javascript:valida_<?php echo $campo ?>_ah(this.checked)" <?php echo $checked2; ?> <?php echo $bloqueo; ?> style="width:15px;height:15px;" type='checkbox' name='ch_<?php echo $fecha_comp ?>_ah' id="ch_<?php echo $campo ?>_ah">Ali+Hot.
										</div>		
										<?php
										}else{  							
										?>
												<center><br><br></center>
										<?php } ?>
									</td>
									<?php
									$day++;
								}
								if($i%7==0)
								{	echo "</tr><tr>\n";	}
							}
						?>
						</tr>
					</table>
			</td>
			<td width=2%> </td>		
		<?php

	if(substr($row_per["fecha_inicial"], 5, 2) != substr($row_per["fecha_final"], 5, 2))
	{
		?>
			<td width=49% valign=top>
				<?php
					$month= 0 + substr($row_per["fecha_final"], 5, 2);
					$year= 0 + substr($row_per["fecha_final"], 0, 4);
					$diaActual=date("j"); 
					$diaSemana=date("w",mktime(0,0,0,$month,1,$year))+7;
					$ultimoDiaMes=date("d",(mktime(0,0,0,$month+1,1,$year)-1)); 
				?>
					<table id="calendar" style="border=30px">
						<caption><?php echo $meses[$month]." ".$year?> </caption>
						<tr>
							<th><center>Lun</center></th><th><center>Mar</center></th><th><center>Mie</center></th><th><center>Jue</center></th><th><center>Vie</center></th><th><center>Sab</center></th><th><center>Dom</center></th>
						</tr>
						<tr bgcolor="silver">
							<?php
							$last_cell=$diaSemana+$ultimoDiaMes;
							for($i=1;$i<=42;$i++)
							{			
								if($i==$diaSemana)
								{	$day=1;	}
								if($i<$diaSemana || $i>=$last_cell)
								{	echo "<td>&nbsp;</td>";	}
								else
								{
									if($day<10){$ii = '0'.$day;}else{$ii = $day;}
									if($month<10){$mm = '0'.$month;}else{$mm = $month;}
									$fecha_comp = $year.'-'.$mm.'-'.$ii ;
									
									$sql_eje = "SELECT id, tipo FROM sto_ejecutado_cmv WHERE 
									id_persona = '".$_GET["idp"]."' AND fecha ='".$fecha_comp."' LIMIT 1";
									$res_eje = mysql_query($sql_eje);
									$row_eje = mysql_fetch_array($res_eje);
									$color_cel = ""; $checked1 = ""; $checked2 = ""; 
									if($row_eje["id"])
									{
										$color_cel = " style='background-color:#81F781;'"; 
										if($row_eje["tipo"]==1){$checked1 = " checked ";}
										if($row_eje["tipo"]==2){$checked2 = " checked ";}							
									}	
									$campo = 'x'.str_replace("-", "", $fecha_comp);
									
										$sql_neje = "SELECT id, observacion FROM sto_no_ejecutado WHERE id_item = '".$_GET["idi"]."' AND 
									id_persona = '".$_GET["idp"]."' AND fecha ='".$fecha_comp."' LIMIT 1";
									$res_neje = mysql_query($sql_neje);
									$row_neje = mysql_fetch_array($res_neje);
									$observacion_dis[$fecha_comp] = $row_neje["observacion"]; 
									?>
									<td <?php echo $color_cel ?> valign=top> 
										<b><?php echo $day; ?></b>  
										<?php if (in_array($fecha_comp, $vector) && in_array($fecha_comp, $vector_rol)) { ?>					
												<div align=left>		
													<input onclick="javascript:valida_<?php echo $campo ?>_ad(this.checked)" <?php echo $checked1; ?> <?php echo $bloqueo; ?> style="width:15px;height:15px;" type='checkbox' name='ch_<?php echo $fecha_comp ?>_ad' id="ch_<?php echo $campo ?>_ad">Ali.Dia<br>
													<input onclick="javascript:valida_<?php echo $campo ?>_ah(this.checked)" <?php echo $checked2; ?> <?php echo $bloqueo; ?> style="width:15px;height:15px;" type='checkbox' name='ch_<?php echo $fecha_comp ?>_ah' id="ch_<?php echo $campo ?>_ah">Ali+Hot.
										</div>	
										<?php }else{  
											$sql_neje = "SELECT id, observacion FROM sto_no_ejecutado WHERE id_item = '".$_GET["idi"]."' AND 
											id_persona = '".$_GET["idp"]."' AND fecha ='".$fecha_comp."' LIMIT 1";
											$res_neje = mysql_query($sql_neje);
											$row_neje = mysql_fetch_array($res_neje);
											$observacion_dis[$fecha_comp] = $row_neje["observacion"];
										
										?>
												<center><br><br></center>
										<?php } ?>
									</td>
									<?php
									$day++;
								}
								if($i%7==0)
								{	echo "</tr><tr>\n";	}
							}
						?>
						</tr>
					</table>
			</td>
	<?php } ?>
		</tr>
		</table>
	
		
		
		<br><br>
		<?php if(!$row_cerrado["id"] && $row_per["estado"]==1){ ?>
			<center><input class="btn btn-primary" type="submit" value="Guardar Fechas" name="guardar" /></center>
		<?php }else{ ?>
			<h2><center>
			<div style="width:50%" class="alert alert-info">
						<?php if($row_cerrado["id"]){ ?>Este periodo ya fue finalizado por usted <?php } ?>
						<?php if($row_per["estado"]==2){ ?>Este periodo ya esta cerrado <?php } ?>
			</div>
			</center></h2>
		<?php } ?>
		
		
</form>

<script language="javascript">
<?php
$i = 0;
while($vector[$i])
{	
	 $campo = 'x'.str_replace("-", "", $vector[$i]);
?>	
		function valida_<?php echo $campo ?>_ad(esto)
		{ document.forms['form'].ch_<?php echo $campo ?>_ah.checked=0; }

		function valida_<?php echo $campo ?>_ah(esto)
		{ document.forms['form'].ch_<?php echo $campo ?>_ad.checked=0; }
<?php
	$i++;
}
?>	
 </script> 
 
	