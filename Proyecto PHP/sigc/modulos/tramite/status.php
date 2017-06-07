<?php
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

if($_GET["anog"])
{
	$ano_ges =  $_GET["anog"];
}
else
{
	$ano_ges = date("Y");
}

if($_GET["mesg"])
{
	$mes_ges = $_GET["mesg"];
}
else
{
	$mes_ges = date("m");	
}

if($mes_ges==1)
{
	$mes_s = $mes_ges + 1;
	$ano_s = $ano_ges ;
	$mes_a = 12;
	$ano_a = $ano_ges - 1 ;
}
else
{
	if($mes_ges ==12)
	{
		$mes_s = 1 ;
		$ano_s = $ano_ges + 1 ;
		$mes_a = $mes_ges - 1;
		$ano_a = $ano_ges  ;
	}
	else
	{
		$ano_a = $ano_ges  ;
		$ano_s = $ano_ges ;
		$mes_a = $mes_ges - 1;
		$mes_s = $mes_ges + 1;
	}
}
if($mes_ges < 9){$mm = '0'.$mes_ges; }else{$mm = $mes_ges;}



?>

<h2>INSTALADOS POR MES</h2>
<center>
	<h5>
		<a href="?mesg=<?php echo $mes_a;?>"><i class="fa fa-arrow-left"></i> Mes anterior</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo mes($mes_ges); ?></b> DEL <b><?php echo $ano_ges; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="?mesg=<?php echo $mes_s; ?>">Mes siguiente <i class="fa fa-arrow-right"></i></a>
	</h5>
</center>

<div class="panel panel-default" style="width:70%;">
	<div align=left class="panel-heading">Cantidad por tramites del mes de <b><font color=red><?php echo mes($mes_ges); ?></font></b> del <font color=red><b><?php echo $ano_ges; ?></b></font></div>
 <table align=center width=100% class="table">
   <tr>
		<th>Tecnologia</th>		
		<th>Cantidad</th>			
		<th><center>----</center></th>			
   </tr>
	<?php
	$sql_total = "SELECT COUNT(*) AS cantidad, DAY(fecha_atencion_orden) AS dia FROM tramite 
	WHERE fecha_atencion_orden >= '".$ano_ges."-".$mm."-01 00:00:00' AND fecha_atencion_orden <= '".$ano_ges."-".$mm."-31 23:59:59' 
	GROUP BY YEAR(fecha_atencion_orden) , MONTH(fecha_atencion_orden), DAY(fecha_atencion_orden) ";
	$res_total = mysql_query($sql_total);
	while($row_total = mysql_fetch_array($res_total))
	{	?>
			<tr>
						<td colspan=5 bgcolor=#E6E6E6><b>FECHA: <?php echo $ano_ges ?> - <?php echo $mm ?>  - <?php echo $row_total["dia"] ?>  ( <font color=red><?php echo $row_total["cantidad"]; ?></font>) </b></td>						
					</tr>
		<?php	
			
			$total_pedido = $row_total["cantidad"];	
			 $sql_pedido = "SELECT tecnologia, COUNT(*) AS cantidad, 
		   ( (COUNT(*)*100)/".$total_pedido." ) AS promedio, (100 - ( (COUNT(*)*100)/".$total_pedido." ) ) AS falta
		   FROM  tramite 
			WHERE fecha_atencion_orden >= '".$ano_ges."-".$mm."-".$row_total["dia"]." 00:00:00' AND fecha_atencion_orden <= '".$ano_ges."-".$mm."-".$row_total["dia"]." 23:59:59' 
			GROUP BY tecnologia ORDER BY cantidad DESC ";
		   $res_pedido = mysql_query($sql_pedido);
		   while($row_pedido = mysql_fetch_array($res_pedido))
		   {				
			?>					
					<tr>
						<td><b><?php echo $row_pedido["tecnologia"] ?></b> </td>
						<td><?php echo $row_pedido["cantidad"] ?> <font color=green>(<b><?php echo round($row_pedido["promedio"]) ?> %</b>)</font> </td>
						<td width=25%>
							<div class="progress">
							  <div class="progress-bar progress-bar-success" style="width: <?php echo  $row_pedido["promedio"] ; ?>%">
								
							  </div>
							  <div class="progress-bar progress-bar-danger" style="width: <?php echo  $row_pedido["falta"]; ?>%">
								
							  </div>
							</div>
						</td>				
				   </tr>
				   <?php 
					$sum = $sum + $row_pedido["cantidad"];
		   } 
?>
			

			<?php
					   
	}
					?>
	
  </table>
</div>
