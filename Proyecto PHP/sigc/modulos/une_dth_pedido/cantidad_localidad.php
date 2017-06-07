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

<h2>ESTADO GENERAL POR MES</h2>
<center>
	<h5>
		<a href="?mesg=<?php echo $mes_a;?>"><i class="fa fa-arrow-left"></i> Mes anterior</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo mes($mes_ges); ?></b> DEL <b><?php echo $ano_ges; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="?mesg=<?php echo $mes_s; ?>">Mes siguiente <i class="fa fa-arrow-right"></i></a>
	</h5>
</center>

<table width=100%>
<tr>
	<td width='42%'  valign=top>
			<div class="panel panel-default" style="width:100%;">
				<div align=left class="panel-heading">Localidades por estados del mes de <b><font color=red><?php echo mes($mes_ges); ?></font></b> del <font color=red><b><?php echo $ano_ges; ?></b></font></div>
			 <table align=center width=50% class="table">
			   <tr>
					<th>Ciudad</th>		
					<th>Tipo trabajo</th>	
					<th>Producto</th>			
					<th><center>Cantidad</center></th>			
			   </tr>
				<?php
				$tem = ""; $sum = 0;
			   $sql_pedido = "SELECT ciudad, tipo_trabajo,  producto_homologado, COUNT(*) AS cantidad FROM  une_pedido 
								WHERE fecha >= '".$ano_ges."-".$mm."-01' AND fecha <= '".$ano_ges."-".$mm."-31' AND une_pedido.tipo='2'
								GROUP BY ciudad, tipo_trabajo, producto_homologado ";
			   $res_pedido = mysql_query($sql_pedido);
			   while($row_pedido = mysql_fetch_array($res_pedido))
			   {			
						if(!$tem){$tem = $row_pedido["ciudad"];}
						if($tem != $row_pedido["ciudad"])
						{
							?>
							<tr bgcolor=blue>
								<td colspan=3><font color=#FFFFFF><b>TOTAL <?php echo $tem; ?> </b></font></td>
								<td><font color=#FFFFFF><center><b><?php echo $sum ?></b></center></font></td>
							</tr>
							<?php
							$tem  = $row_pedido["ciudad"];
							$sum = 0;
						}
				?>
						<tr>
							<td><b><?php echo $row_pedido["ciudad"] ?></b> </td>
							<td><?php echo $row_pedido["tipo_trabajo"] ?></td>
							<td><?php echo $row_pedido["producto_homologado"] ?> </td>
							<td><b><center><?php echo $row_pedido["cantidad"] ?></center></b> </td>				
					   </tr>
					   <?php 
						$sum = $sum + $row_pedido["cantidad"];
			   }     
			?>
				<tr bgcolor=blue	>
					<td colspan=3><font color=#FFFFFF><b>TOTAL <?php echo $tem; ?> </b></font></td>
					<td><font color=#FFFFFF><center><b><?php echo $sum ?></b></center></font></td>
				</tr>
			 
			  </table>
			</div>
	</td>
	<td width=2%><br></td>
	<td width='54%' valign=top >
						<div class="panel panel-default" style="width:100%;">
				<div align=left class="panel-heading">Cantidad por responsable del mes de <b><font color=red><?php echo mes($mes_ges); ?></font></b> del <font color=red><b><?php echo $ano_ges; ?></b></font></div>
			 <table align=center width=100% class="table">
			   <tr>
					<th>Nombre</th>		
					<th>Codigo</th>	
					<th>Ciudad</th>	
					<th>Cantidad</th>			
					<th><center>----</center></th>			
			   </tr>
				<?php
				$sql_total = "SELECT COUNT(*) AS cantidad, ciudad FROM une_pedido 
				WHERE fecha >= '".$ano_ges."-".$mm."-01' AND fecha <= '".$ano_ges."-".$mm."-31' AND une_pedido.tipo='2'
				GROUP BY ciudad ";
				$res_total = mysql_query($sql_total);
				while($row_total = mysql_fetch_array($res_total))
				{	
					$total_pedido = $row_total["cantidad"];
				
									$sql_pedido = "SELECT codigo_funcionario, nombre_funcionario,  COUNT(*) AS cantidad, 
								   ( (COUNT(*)*100)/".$total_pedido." ) AS promedio, (100 - ( (COUNT(*)*100)/".$total_pedido." ) ) AS falta
								   FROM  une_pedido 
													WHERE fecha >= '".$ano_ges."-".$mm."-01' AND fecha <= '".$ano_ges."-".$mm."-31' AND ciudad = '".$row_total["ciudad"]."' AND une_pedido.tipo='2'
													GROUP BY codigo_funcionario ORDER BY cantidad DESC ";
								   $res_pedido = mysql_query($sql_pedido);
								   while($row_pedido = mysql_fetch_array($res_pedido))
								   {						
									?>
											<tr>
												<td><b><?php echo $row_pedido["nombre_funcionario"] ?></b> </td>
												<td><?php echo $row_pedido["codigo_funcionario"] ?></td>
												<td><?php echo $row_total["ciudad"] ?></td>
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
						<tr>
							<td colspan=5 bgcolor=#E6E6E6></td>						
						</tr>

						<?php
								   
				}
								?>
				
			  </table>
			</div>

	</td>
</tr>
</table>
