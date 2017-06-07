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

<h2>ESTADO DE LOS PEDIDOS SIN FINALIZAR <b>DTH</b></h2>


<table width=100%>
	<tr>
		<td>
			<form action="?cmp=gestionar_detalle"  method="post" name="form" id="form"  enctype="multipart/form-data">
				<div class="row">
				   <div class="col-lg-6">
					<div class="input-group">
					  <input type="text" class="form-control" name="pedido" placeholder="Buscar pedido...">
					  <span class="input-group-btn">
						<button class="btn btn-primary"  type="submit">Buscar</button>
					  </span>
					</div>
				  </div>
				</div>
			</form>
		</td>
		<td align=right>			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
</table>
<br>
<table width="100%">
	<tr>
		<td>
			<?php
			
			
			$sql_tot = "SELECT COUNT(*) AS cantidad FROM une_pedido 
				WHERE fecha >= '".$ano_ges."-".$mm."-01' AND fecha <= '".$ano_ges."-".$mm."-31' AND une_pedido.tipo='2' ";
			$res_tot = mysql_query($sql_tot);
			$row_tot = mysql_fetch_array($res_tot);
			?>
			<a href="modulos/une_dth_pedido/lista_pendiente.php?excel=1&mm=<?php echo $mm ?>&aa=<?php echo $ano_ges	 ?>" target=blank>
				<button class="btn btn-primary" type="button">
						<font size=1px>Total registros <span class="badge"><font color=red><?php echo $row_tot["cantidad"] ?></font></span></font>
				</button>
			</a>
			<?php
				$sql_cont = "SELECT COUNT(*) AS cantidad, estado_material  FROM une_pedido  
				WHERE fecha >= '".$ano_ges."-".$mm."-01' AND fecha <= '".$ano_ges."-".$mm."-31' AND une_pedido.tipo='2'
				GROUP BY estado_material  ORDER BY estado_material";
				$res_cont = mysql_query($sql_cont);
				while($row_cont = mysql_fetch_array($res_cont)){
						
				
				if($row_cont["estado_material"]=='1')$ees = "Pend. por EDATEL";
				if($row_cont["estado_material"]=='4')$ees = "Pend. por EIA";
				if($row_cont["estado_material"]=='3')$ees = "Rechazado";
				if($row_cont["estado_material"]=='2')$ees = "Confirmado";
				
			?>
				<a href="modulos/une_dth_pedido/lista_pendiente.php?excel=1&mm=<?php echo $mm ?>&aa=<?php echo $ano_ges	?>&ee=<?php echo $row_cont["estado_material"] ?>" target=blank>
					<button class="btn btn-primary" type="button">
						<font size=1px color=#FFFFFF><?php echo $ees ?> <span class="badge"><font color=red><?php echo $row_cont["cantidad"] ?></font></span></font>
					</button>	
				</a>
			<?php	
			}
			?>
		</td>
		<td>
			<h5><a href="?mesg=<?php echo $mes_a;?>"><i class="fa fa-arrow-left"></i> Mes anterior</a>
				&nbsp;&nbsp;&nbsp;<b><?php echo mes($mes_ges); ?></b> DEL <b><?php echo $ano_ges; ?></b>&nbsp;&nbsp;&nbsp;
			<a href="?mesg=<?php echo $mes_s; ?>">Mes siguiente <i class="fa fa-arrow-right"></i></a>
			</h5>
		</td>
		<td align=right>
			<a href="modulos/une_dth_pedido/lista_pendiente.php?excel=1&mm=<?php echo $mm ?>&aa=<?php echo $ano_ges	 ?>" target=blank> <i class="fa fa-cloud-download fa-2x"></i> Descargar excel </a>		
		<td>
	</tr>
</table>


<center>
<div class="panel panel-default" style="width:100%;">
   <div align=left class="panel-heading">Pedidos sin confirmar <b>DTH</b></div>
  <table class="table" align=center class="table table-striped table-bordered table-hover" id="dataTables-example">
   <thead>
	   <tr>
			<!-- <th> </th> -->
			<th>Estado</th>
			<th>Pedido</th>		
			<th>Tipo trabajo</th>		
			<th>Ciudad</th>	
			<th>Producto</th>		
			<th>Tecnologia</th>
			<th>Fecha</th>
			<th>Detalle</th>	
	   </tr>
	 </thead>
	  <tbody>
   <?php
    
   $sql_pedido = "SELECT * FROM une_pedido WHERE estado_material IN ('4')  AND fecha >= '".$ano_ges."-".$mm."-01' AND fecha <= '".$ano_ges."-".$mm."-31' AND une_pedido.tipo='2'  ";
   $res_pedido = mysql_query($sql_pedido);
   while($row_pedido = mysql_fetch_array($res_pedido))
   {		
			$sql_alarma = "SELECT alarma FROM une_pedido_material WHERE id_pedido = '".$row_pedido["id"]."' ORDER BY alarma DESC LIMIT 1";
			$res_alarma = mysql_query($sql_alarma);
			$row_alarma = mysql_fetch_array($res_alarma);
			
			$alarma = "";
			if($row_alarma["alarma"]==2){$alarma = "<font color=yellow><i class='fa fa-exclamation-triangle fa-2x'> </i></font>";}
			if($row_alarma["alarma"]==3){$alarma = "<font color=red><i class='fa fa-exclamation-triangle fa-2x'> </i></font>";}
			
			$estado_material = ""; $font = "";
			if($row_pedido["estado_material"]==1){ $estado_material = "<font color=#000000>Pend. Edatel</font>"; }
			if($row_pedido["estado_material"]==4){ $estado_material = "<font color=#000000>Pend. EIA</font>"; }
			if($row_pedido["estado_material"]==2){ $estado_material = "<font color=green>Confirmado</font>"; }
			if($row_pedido["estado_material"]==3){ $estado_material = "<font color=red>Rechazado</font>";  $font = " color=red ";}
		
			
	?>
			<tr>
				<!-- <td align=center><b><?php echo $alarma;?></b></td> -->
				<td><b><?php echo $estado_material;?></b></td>
				<td><?php echo $row_pedido["numero"] ?> </td>
				<td><?php echo $row_pedido["tipo_trabajo"] ?> </td>
				<td><?php echo $row_pedido["ciudad"] ?> </td>
				<td><?php echo $row_pedido["producto"] ?> </td>
				<td><?php echo $row_pedido["tecnologia"] ?> </td>
				<td><?php echo $row_pedido["fecha"] ?> </td>				
				<td align=center><a href="?cmp=gestionar_detalle&id=<?php echo $row_pedido["id"] ?>"> <font <?php echo $font ?> ><i class="fa fa-eye fa-2x"> </i></font> </a></td></td>
		   </tr>
		   <?php 
	 
   }     
?>
    </tbody>

  </table>
</div>
</center>

<br><br>