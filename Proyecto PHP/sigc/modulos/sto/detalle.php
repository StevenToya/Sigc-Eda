<?php
if($PERMISOS_GC["sto_coor"]!='Si')
{
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}
$sql_inc = "SELECT valor FROM incremento WHERE ano = '".date("Y")."' LIMIT 1";
$res_inc = mysql_query($sql_inc);
$row_inc = mysql_fetch_array($res_inc);

$incrementado = $row_inc["valor"];

		

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
$sql_item = "SELECT nombre, (valor * ".$incrementado.") AS valor
			   FROM sto_item 							
				WHERE id = '".$_GET["idi"]."'
				LIMIT 1 ";
$res_item = mysql_query($sql_item);
$row_item = mysql_fetch_array($res_item);

$sql_per = "SELECT * FROM sto_periodo WHERE estado = 1 LIMIT 1";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);
$vector = recorrido_fecha($row_per["fecha_inicial"],  $row_per["fecha_final"]);


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


$sql_cerrado = "SELECT id FROM sto_periodo_usuario WHERE id_periodo = '".$row_per["id"]."' AND id_usuario = '".$_SESSION["user_id"]."' LIMIT 1";
$res_cerrado = mysql_query($sql_cerrado);
$row_cerrado = mysql_fetch_array($res_cerrado);
if($row_cerrado["id"] || $row_per["estado"]==2){
	$bloqueo = ' disabled ';
 } 

?> 
<h2>GESTIONAR PERIODO DE <b><?php echo $row_per["fecha_inicial"] ?></b> 
AL <b><?php echo $row_per["fecha_final"] ?></b> PARA EL ITEM <b><?php echo $row_item["nombre"] ?></b> </h2> 

<div align=right>
	<a href="?cmp=lista_personal&id=<?php echo $_GET["id"]; ?>"> <i class="fa fa-reply fa-2x"></i> Volvel al listado del personal </a>
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
<?php
$valor_item = $row_item["valor"] / 30;
$valor_item = round($valor_item , 2);
?>
<h4>
	<table width=100%>
		<tr>
			<td>Tecnico: <b><?php echo $row_person["nombre"] ?></b></td>
			<td>Valor dia: <b>$ <?php echo moneda($valor_item) ?></b></td>		
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
									
									$sql_eje = "SELECT id FROM sto_ejecutado WHERE id_item = '".$_GET["idi"]."' AND 
									id_persona = '".$_GET["idp"]."' AND fecha ='".$fecha_comp."' LIMIT 1";
									$res_eje = mysql_query($sql_eje);
									$row_eje = mysql_fetch_array($res_eje);
									$color_cel = ""; $checked = "";
									if($row_eje["id"])
									{$celda = "<font color=green><i class='fa fa-check fa-3x'></i></font>";}
									else{ $celda = "<font color=red><i class='fa fa-times fa-3x'></i> </font>";}									
									?>
									<td <?php echo $color_cel ?>> 
										<?php echo $day; ?>  <br>
										<?php if (in_array($fecha_comp, $vector)) { ?>					
												<center><?php echo $celda; ?></center>
										<?php }else{  ?>
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
									
									$sql_eje = "SELECT id FROM sto_ejecutado WHERE id_item = '".$_GET["idi"]."' AND 
									id_persona = '".$_GET["idp"]."' AND fecha ='".$fecha_comp."' LIMIT 1";
									$res_eje = mysql_query($sql_eje);
									$row_eje = mysql_fetch_array($res_eje);
									$color_cel = ""; $checked = "";
									if($row_eje["id"])
									{$celda = "<font color=green><i class='fa fa-check fa-3x'></i></font>";}
									else{ $celda = "<font color=red><i class='fa fa-times fa-3x'></i> </font>";}									
									?>
									<td <?php echo $color_cel ?>> 
										<?php echo $day; ?>  <br>
										<?php if (in_array($fecha_comp, $vector)) { ?>					
												<center><?php echo $celda; ?></center>
										<?php }else{  ?>
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
	
</form>




	<br><br>
<div class="panel panel-default"  style="width:99%">
	<div class="panel-heading" align=left>
		<b>MOTIVOS DE LOS DIAS NO EJECUTADOS </b>
	</div>
	<div class="panel-body" >
		<div class="table-responsive" >
	
			<table class="table table-striped table-bordered table-hover" >
				<thead>
					<tr>
						<th>Fecha</th>
						<th>Observacion</th>					
						
					</tr>
				</thead>
				<tbody>
				<?php
				 $sql_neje = "SELECT * FROM sto_no_ejecutado WHERE id_item = '".$_GET["idi"]."' AND 
									id_persona = '".$_GET["idp"]."' AND id_periodo ='".$row_per["id"]."'";
				$res_neje = mysql_query($sql_neje);				
				while($row_neje = mysql_fetch_array($res_neje))
				{		
			
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row_neje["fecha"] ?> </b></td>
						<td><?php echo utf8_encode($row_neje["observacion"]) ?></td>						
						
				   </tr>
				<?php
				}
				?>	
			
				
				</tbody>
			</table>
		</div>		
	</div>
</div>
</center>



