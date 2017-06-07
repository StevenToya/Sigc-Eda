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

<h2>ESTADO LIQUIDACION POR MES</h2>
<center>
	<h5>
		<a href="?mesg=<?php echo $mes_a;?>"><i class="fa fa-arrow-left"></i> Mes anterior</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo mes($mes_ges); ?></b> DEL <b><?php echo $ano_ges; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="?mesg=<?php echo $mes_s; ?>">Mes siguiente <i class="fa fa-arrow-right"></i></a>
	</h5>
</center>

<table width=80% align=center>
<tr>
	<td width='80%'  valign=top>
			<div class="panel panel-default" style="width:100%;">
				<div align=left class="panel-heading">Valores  del mes de <b><font color=red><?php echo mes($mes_ges); ?></font></b> del <font color=red><b><?php echo $ano_ges; ?></b></font> Pendientes <font color=red><b>POR CONFIRMA</b></font> </div>
			 <table align=center  class="table">
			   <tr>
					<th>Categoria</th>		
					<th>Descripcion</th>	
					<th><center>Tipo Item<center></th>			
					<th><center>Cantidad</center></th>	
					<th><center>Valor</center></th>	
			   </tr>
				<?php
				$tem = ""; $sum = 0;
			   $sql_pedido = "SELECT une_item.codigo, COUNT(*) AS cantidad, une_item.categoria, une_item.descripcion, SUM(une_item.valor) AS total
			   FROM une_pedido 
			   INNER JOIN une_liquidacion ON une_pedido.id = une_liquidacion.id_pedido
			   INNER JOIN une_item ON une_liquidacion.id_item = une_item.id
			   WHERE une_pedido.fecha >= '".$ano_ges."-".$mm."-01' AND une_pedido.fecha <= '".$ano_ges."-".$mm."-31' AND une_pedido.tipo='1' AND 
			   une_pedido.estado_liquidacion IN ('1','3','4') 
			   AND une_pedido.ciudad IN ('Monteria','Sincelejo','Valledupar')
			   GROUP BY une_item.codigo ";
			   $res_pedido = mysql_query($sql_pedido);
			   while($row_pedido = mysql_fetch_array($res_pedido))
			   {							
				?>
						<tr>
							<td><?php echo $row_pedido["categoria"] ?></td>
							<td><?php echo $row_pedido["descripcion"] ?></td>
							<td align=center><b><?php echo $row_pedido["codigo"] ?></b> </td>
							<td align=center><?php echo $row_pedido["cantidad"] ?> </td>
							<td align=right><b><?php echo moneda($row_pedido["total"]) ?></b> </td>				
					   </tr>
					   <?php 
						$sum = $sum + $row_pedido["total"];
			   }     
			?>
				<tr bgcolor=blue	>
					<td colspan=4><font color=#FFFFFF><b>TOTAL <?php echo $tem; ?> </b></font></td>
					<td align=right><font color=#FFFFFF><b><?php echo moneda($sum) ?></b></font></td>
				</tr>			 
		  </table>
		</div>
</td>
	
</tr>
</table>
<br>
<table width=80% align=center>
<tr>
	<td width='80%'  valign=top>
			<div class="panel panel-default" style="width:100%;">
				<div align=left class="panel-heading">Valores  del mes de <b><font color=red><?php echo mes($mes_ges); ?></font></b> del <font color=red><b><?php echo $ano_ges; ?></b></font> ya <font color=red><b>CONFIRMADAS</b></font> </div>
			 <table align=center class="table">
			   <tr>
					<th>Categoria</th>		
					<th>Descripcion</th>	
					<th><center>Tipo Item<center></th>			
					<th><center>Cantidad</center></th>	
					<th><center>Valor</center></th>	
			   </tr>
				<?php
				$tem = ""; $sum = 0;
			   $sql_pedido = "SELECT une_item.codigo, COUNT(*) AS cantidad, une_item.categoria, une_item.descripcion, SUM(une_item.valor) AS total
			   FROM une_pedido 
			   INNER JOIN une_liquidacion ON une_pedido.id = une_liquidacion.id_pedido
			   INNER JOIN une_item ON une_liquidacion.id_item = une_item.id
			   WHERE une_pedido.fecha >= '".$ano_ges."-".$mm."-01' 
			   AND une_pedido.fecha <= '".$ano_ges."-".$mm."-31' 
			   AND une_pedido.tipo='1' 
			   AND une_pedido.estado_liquidacion IN ('2') 
			   AND une_pedido.ciudad IN ('Monteria','Sincelejo','Valledupar')
			   GROUP BY une_item.codigo ";
			   $res_pedido = mysql_query($sql_pedido);
			   while($row_pedido = mysql_fetch_array($res_pedido))
			   {			
						
				?>
						<tr>
							<td><?php echo $row_pedido["categoria"] ?></td>
							<td><?php echo $row_pedido["descripcion"] ?></td>
							<td align=center><b><?php echo $row_pedido["codigo"] ?></b> </td>
							<td align=center><?php echo $row_pedido["cantidad"] ?> </td>
							<td align=right><b><?php echo moneda($row_pedido["total"]) ?></b> </td>				
					   </tr>
					   <?php 
						$sum = $sum + $row_pedido["total"];
			   }     
			?>
				<tr bgcolor=blue	>
					<td colspan=4><font color=#FFFFFF><b>TOTAL <?php echo $tem; ?> </b></font></td>
					<td align=right><font color=#FFFFFF><b><?php echo moneda($sum) ?></b></font></td>
				</tr>			 
		  </table>
		</div>
</td>
	
</tr>
</table>

