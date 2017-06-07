<?php
if($PERMISOS_GC["stohome_coor"]!='Si')
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
$sql_item = "SELECT nombre, valor
			   FROM stohome_item 							
				WHERE id = '".$_GET["idi"]."'
				LIMIT 1 ";
$res_item = mysql_query($sql_item);
$row_item = mysql_fetch_array($res_item);

$sql_per = "SELECT * FROM stohome_periodo WHERE estado = 1 LIMIT 1";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);
$vector = recorrido_fecha($row_per["fecha_inicial"],  $row_per["fecha_final"]);

if($_POST["guardar"])
{	
	
	$sql_del = "DELETE FROM `stohome_ejecutado` WHERE `id_periodo` = '".$row_per["id"]."' AND id_item = '".$_GET["idi"]."'  AND id_persona = '".$_GET["idp"]."'  ";
	mysql_query($sql_del);
	
	$sql_del = "DELETE FROM `stohome_no_ejecutado` WHERE `id_periodo` = '".$row_per["id"]."' AND id_item = '".$_GET["idi"]."'  AND id_persona = '".$_GET["idp"]."'  ";
	mysql_query($sql_del);
	
	foreach ($vector AS $k => &$valor) 
	{
		$campo_obs = 'x'.str_replace("-", "", $valor);
		$valor_item = $row_item["valor"] / 30;
		$valor_item = round($valor_item , 2);
		$tem =  'ch_'.$valor;
		if($_POST[$tem])
		{
			 $sql_g = "INSERT INTO `stohome_ejecutado` 
				(`id_persona`, `id_item`, `id_periodo`, `valor`, `fecha`) 
				VALUES ('".$_GET["idp"]."', '".$_GET["idi"]."', '".$row_per["id"]."', '".$valor_item."', '".$valor."');";
			mysql_query($sql_g);
			
		}	
		else
		{
			$sql_g = "INSERT INTO `stohome_no_ejecutado` 
				(`id_persona`, `id_item`, `id_periodo`,  `fecha`, `observacion`) 
				VALUES ('".$_GET["idp"]."', '".$_GET["idi"]."', '".$row_per["id"]."', '".$valor."', '".$_POST[$campo_obs]."');";
			mysql_query($sql_g);
		}
	}					
}



$sql_person = "SELECT stohome_persona.nombre ,  stohome_persona.fecha_registro, stohome_sap.cuenta AS cue_sap,
						stohome_plataforma.nombre AS nom_plataforma, stohome_sap.nombre AS nom_sap, stohome_car.nombre AS nom_car, stohome_rubro.nombre AS nom_rubro,
						stohome_persona.id, stohome_persona.identificacion, municipio.nombre AS nom_municipio
			   FROM stohome_persona 
				INNER JOIN stohome_plataforma ON stohome_persona.id_plataforma = stohome_plataforma.id
				INNER JOIN stohome_sap ON stohome_persona.id_sap = stohome_sap.id
				LEFT JOIN stohome_car ON stohome_persona.id_car = stohome_car.id
				INNER JOIN stohome_rubro ON stohome_persona.id_rubro = stohome_rubro.id	
				LEFT JOIN municipio ON stohome_persona.id_municipio = municipio.id
				WHERE stohome_persona.id = '".$_GET["idp"]."'
				LIMIT 1 ";
$res_person = mysql_query($sql_person);
$row_person = mysql_fetch_array($res_person);

$sql_per = "SELECT * FROM stohome_periodo WHERE estado = 1 LIMIT 1";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);


$sql_cerrado = "SELECT id FROM stohome_periodo_usuario WHERE id_periodo = '".$row_per["id"]."' AND id_usuario = '".$_SESSION["user_id"]."' LIMIT 1";
$res_cerrado = mysql_query($sql_cerrado);
$row_cerrado = mysql_fetch_array($res_cerrado);
if($row_cerrado["id"] || $row_per["estado"]==2){
	$bloqueo = ' disabled ';
 } 

?> 
<h2>GESTIONAR PERIODO DE <b><?php echo $row_per["fecha_inicial"] ?></b> 
AL <b><?php echo $row_per["fecha_final"] ?></b> PARA EL ITEM <b><?php echo $row_item["nombre"] ?></b> </h2> 

<div align=right>
	<a href="?cmp=lista_tupersonal"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de tu personal </a>
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
		
		<tr><td colspan=2><br></td></tr>
		
		<tr>
			<td>Identificacion: <b><?php echo $row_person["identificacion"] ?></b></td>
			<td>Localidad: <b><?php echo utf8_encode($row_person["nom_municipio"]) ?></b></td>		
		</tr>
	</table>
</h4>

<?php


?>
<form action="?idi=<?php echo $_GET["idi"]; ?>&idp=<?php echo $_GET["idp"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">
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
									
									$sql_eje = "SELECT id, observacion FROM stohome_ejecutado WHERE id_item = '".$_GET["idi"]."' AND 
									id_persona = '".$_GET["idp"]."' AND fecha ='".$fecha_comp."' LIMIT 1";
									$res_eje = mysql_query($sql_eje);
									$row_eje = mysql_fetch_array($res_eje);
									$color_cel = ""; $checked = "";
									if($row_eje["id"]){$color_cel = " style='background-color:#81F781;'"; $checked = " checked "; $fecha_dis[$fecha_comp] = ' disabled '; }	
									$campo = 'x'.str_replace("-", "", $fecha_comp);	
									
									$sql_neje = "SELECT id, observacion FROM stohome_no_ejecutado WHERE id_item = '".$_GET["idi"]."' AND 
									id_persona = '".$_GET["idp"]."' AND fecha ='".$fecha_comp."' LIMIT 1";
									$res_neje = mysql_query($sql_neje);
									$row_neje = mysql_fetch_array($res_neje);
									$observacion_dis[$fecha_comp] = $row_neje["observacion"]; 
									
									?>
									<td <?php echo $color_cel ?>> 
										<?php echo $day; ?>  <br>
										<?php if (in_array($fecha_comp, $vector)) { ?>					
												<center><input <?php echo $checked; ?> onclick="javascript:valida_<?php echo $campo ?>(this.checked)" <?php echo $bloqueo; ?> style="width:30px;height:30px;" type='checkbox' name='ch_<?php echo $fecha_comp ?>'></center>
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
									
									$sql_eje = "SELECT id FROM stohome_ejecutado WHERE id_item = '".$_GET["idi"]."' AND 
									id_persona = '".$_GET["idp"]."' AND fecha ='".$fecha_comp."' LIMIT 1";
									$res_eje = mysql_query($sql_eje);
									$row_eje = mysql_fetch_array($res_eje);
									$color_cel = ""; $checked = "";
									if($row_eje["id"]){$color_cel = " style='background-color:#81F781;'"; $checked = " checked "; $fecha_dis[$fecha_comp] = ' disabled '; }	
									$campo = 'x'.str_replace("-", "", $fecha_comp);
									
										$sql_neje = "SELECT id, observacion FROM stohome_no_ejecutado WHERE id_item = '".$_GET["idi"]."' AND 
									id_persona = '".$_GET["idp"]."' AND fecha ='".$fecha_comp."' LIMIT 1";
									$res_neje = mysql_query($sql_neje);
									$row_neje = mysql_fetch_array($res_neje);
									$observacion_dis[$fecha_comp] = $row_neje["observacion"]; 
									?>
									<td <?php echo $color_cel ?>> 
										<?php echo $day; ?>  <br>
										<?php if (in_array($fecha_comp, $vector)) { ?>					
												<center><input <?php echo $checked; ?> <?php echo $bloqueo; ?> style="width:30px;height:30px;" type='checkbox'  onclick="javascript:valida_<?php echo $campo ?>(this.checked)" name='ch_<?php echo $fecha_comp ?>'></center>
										<?php }else{  
											$sql_neje = "SELECT id, observacion FROM stohome_no_ejecutado WHERE id_item = '".$_GET["idi"]."' AND 
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
		
		<h3>
			Seleccionar o quitar todos <input type="checkbox"  style="width:30px;height:30px;" onclick="javascript:seleccionar_todo();" name="seleccionar_t">  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
		</h3>
		
		<h4><b>Motivos para los dias no ejecutados</b></h4>
		<table width='100%'>
			<tr>
				<td>
					<?php			
					$i = 0;	$j =0;	
					while($vector[$i])
					{
						$campo = 'x'.str_replace("-", "", $vector[$i]);	
						if(($i % 3)==0)
						{echo '</tr><tr>';}
						
						?>	<td>
							<div class=" form-group input-group input-group-lg" style="width:100%">
								<span class="input-group-addon"><?php echo $vector[$i]; ?></span>
								<input class="form-control" name="<?php echo $campo ?>" type="text" <?php echo $fecha_dis[$vector[$i]]; ?> value="<?php echo $observacion_dis[$vector[$i]]; ?>" id="<?php echo $campo ?>" required />
							</div>
							</td>
						<?php
						$i++; $j++;
					}
					?>
				</td>
			</tr>
		</table>
		
		
		
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

<?php
$i = 0;
while($vector[$i])
{	
	$campo = 'x'.str_replace("-", "", $vector[$i]);
?>
	<script language="javascript">
		function valida_<?php echo $campo ?>(esto)
		{document.forms['form'].<?php echo $campo ?>.disabled=esto;}	
	</script>  
<?php
	$i++;
}
?>	

<script language="javascript">
function seleccionar_todo(){ 
   for (i=0;i<document.forms['form'].elements.length;i++) 
      if(document.forms['form'].elements[i].type == "checkbox")	
         document.forms['form'].elements[i].checked=!document.forms['form'].elements[i].checked

   for (i=0;i<document.forms['form'].elements.length;i++) 
      if(document.forms['form'].elements[i].type == "text")	
         document.forms['form'].elements[i].disabled=!document.forms['form'].elements[i].disabled
	 
} 
</script>  	