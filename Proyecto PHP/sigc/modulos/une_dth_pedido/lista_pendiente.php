<?php
if($_GET["excel"])
{
	include("../../cnx.php");
		
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	?>
		<table border=1>
			<tr>
				<th bgcolor=red>Pedido</th>
				<?php if($_GET["excel"]=='s'){ ?>	<th bgcolor=red>Alarma</th> <?php } ?>
				<th bgcolor=red>Ciudad</th>
				<th bgcolor=red>Tipo de trabajo</th>
				<th bgcolor=red>Nombre Funcionario</th>
				<th bgcolor=red>Codigo Funcionario</th>
				<th bgcolor=red>Segmento</th>
				<th bgcolor=red>Producto</th>
				<th bgcolor=red>Producto homologado</th>				
				<th bgcolor=red>Tecnologia</th>
				<th bgcolor=red>Proceso</th>
				<th bgcolor=red>Empresa</th>
				<th bgcolor=red>Fecha</th>	
				<?php
				$sql_bus_material = "SELECT id, nombre FROM une_material WHERE  tipo=2 ORDER BY nombre";
				$res_bus_material = mysql_query($sql_bus_material);
				while($row_bus_material = mysql_fetch_array($res_bus_material))
				{	
					$vec_mat[$row_bus_material["id"]] = ' ';
				?>
						<th bgcolor=red><?php echo $row_bus_material["nombre"] ?></th>
				<?php
				}
				?>
				<th bgcolor=red>Seial 1</th>
				<th bgcolor=red>Seial 2</th>
				<th bgcolor=red>Seial 3</th>
				<th bgcolor=red>Seial 4</th>
				<th bgcolor=red>Seial 5</th>
				<th bgcolor=red>Seial 6</th>
				<th bgcolor=red>Seial 7</th>				
			</tr>		
	<?php
	if($_GET["ee"])
	{	$query = " estado_material = '".$_GET["ee"]."' AND ";	}else{	$query = ""; }
	
	
	
	$sql = "SELECT * FROM une_pedido 
	WHERE ".$query ." fecha >= '".$_GET["aa"]."-".$_GET["mm"]."-01' AND fecha <= '".$_GET["aa"]."-".$_GET["mm"]."-31' AND une_pedido.tipo='2' ORDER BY numero";
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		$sql_alarma = "SELECT alarma FROM une_pedido_material WHERE id_pedido = '".$row["id"]."' ORDER BY alarma DESC LIMIT 1";
		$res_alarma = mysql_query($sql_alarma);
		$row_alarma = mysql_fetch_array($res_alarma);
		
		$alarma = "<b>SIN ALARMA</b>"; 
		if($row_alarma["alarma"]==2){$alarma = "<font color=orange><b>BAJA</b></font>"; }
		if($row_alarma["alarma"]==3){$alarma = "<font color=red><b>ALTA</b></font>"; }
		
		
		$vec_mat_tem = $vec_mat;
		$sql_traza = "SELECT  une_pedido_material.cantidad, une_material.id AS idmaterial, une_pedido_material.alarma
		FROM une_pedido_material 
		INNER JOIN une_material ON une_pedido_material.id_material = une_material.id									
		WHERE une_pedido_material.id_pedido = '".$row["id"]."' ORDER BY une_material.nombre ";
		$res_traza = mysql_query($sql_traza);
		while($row_traza = mysql_fetch_array($res_traza))
		{
			$color = "#000000";
			if($_GET["excel"]=='s'){ 
				if($row_traza["alarma"] == '2'){$color = "orange";}
				if($row_traza["alarma"] == '3'){$color = "red";}	
			}				
			$vec_mat_tem[$row_traza["idmaterial"]] = '<font color='.$color.'>'.$row_traza["cantidad"].'</font>';
		}
		
	?>
		<tr>
			<td><?php echo $row["numero"] ?></td>
			<?php if($_GET["excel"]=='s'){ ?> <td><?php echo $alarma  ?></td> <?php } ?>
			<td><?php echo $row["ciudad"] ?></td>
			<td><?php echo $row["tipo_trabajo"] ?></td>
			<td><?php echo $row["nombre_funcionario"] ?></td>
			<td><?php echo $row["codigo_funcionario"] ?></td>
			<td><?php echo $row["segmento"] ?></td>
			<td><?php echo $row["producto"] ?></td>
			<td><?php echo $row["producto_homologado"] ?></td>
			<td><?php echo $row["tecnologia"] ?></td>
			<td><?php echo $row["proceso"] ?></td>	
			<td><?php echo $row["empresa"] ?></td>	
			<td><?php echo $row["fecha"] ?></td>
			<?php
			foreach ($vec_mat_tem as &$valor) {
			?>
				<td align=center><?php echo $valor ?></td>
			<?php
			}			
			?>
			
			<?php
			$i = 7;
			 $sql_traza = "SELECT une_pedido_equipo.serial, une_pedido_equipo.mac
			FROM une_pedido_equipo 
			WHERE une_pedido_equipo.id_pedido = '".$row["id"]."'  ";
			$res_traza = mysql_query($sql_traza);
			while($row_traza = mysql_fetch_array($res_traza))
			{			
			?>
				<td>'<?php echo $row_traza["serial"]; ?>'</td>			
			<?php
				$i--;
			}
			?>
			
			<?php
			while($i >0)
			{
			?>
				<td> </td>	
			<?php
				$i--;
			}
			?>
			
		</tr>
		
	<?php	
	}
die();	
}
?>


<?php


if($_GET["eli"])
{	
	$sql_eli = "DELETE FROM `une_pedido_equipo` WHERE `id_pedido` = '".$_GET["eli"]."' ;";
	mysql_query($sql_eli);	
	
	$sql_eli = "DELETE FROM `une_pedido_material` WHERE `id_pedido` = '".$_GET["eli"]."' ;";
	mysql_query($sql_eli);		
	
	$sql_eli = "DELETE FROM `une_pedido` WHERE `id` = '".$_GET["eli"]."' LIMIT 1;";
	mysql_query($sql_eli);	
}



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
			<form action="?cmp=detalle"  method="post" name="form" id="form"  enctype="multipart/form-data">
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
			<a href="?cmp=carga"> <i class="fa fa-download fa-2x"></i> Carga de materiales y equipos</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
</table>
<br>

<table width="100%">
	<tr>
		<td>
			<?php
			
			
			$sql_tot = "SELECT COUNT(*) AS cantidad FROM une_pedido 
				WHERE fecha >= '".$ano_ges."-".$mm."-01' AND fecha <= '".$ano_ges."-".$mm."-31' AND une_pedido.tipo='2'";
			$res_tot = mysql_query($sql_tot);
			$row_tot = mysql_fetch_array($res_tot);
			?>
			<a href="modulos/une_dth_pedido/lista_pendiente.php?excel=s&mm=<?php echo $mm ?>&aa=<?php echo $ano_ges	 ?>" target=blank>
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
				
				if($row_cont["estado_material"]=='1')$ees = "Pend. EDATEL";
				if($row_cont["estado_material"]=='4')$ees = "Pend. EIA";
				if($row_cont["estado_material"]=='2')$ees = "Confirmado";
				if($row_cont["estado_material"]=='3')$ees = "Rechazado";	
			?>
				<a href="modulos/une_dth_pedido/lista_pendiente.php?excel=s&mm=<?php echo $mm ?>&aa=<?php echo $ano_ges	?>&ee=<?php echo $row_cont["estado_material"] ?>" target=blank>
					<button class="btn btn-primary" type="button">
						<font size=1px color=#FFFFFF><?php echo $ees ?> <span class="badge"><font color=red><?php echo $row_cont["cantidad"] ?></font></span></font>
					</button>	
				</a>
			<?php	
			}
			?>
		</td>
		<td>
			<h5>
			<a href="?mesg=<?php echo $mes_a;?>"><i class="fa fa-arrow-left"></i> Mes anterior</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo mes($mes_ges); ?></b> DEL <b><?php echo $ano_ges; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="?mesg=<?php echo $mes_s; ?>">Mes siguiente <i class="fa fa-arrow-right"></i></a>
			</h5>
		</td>
		<td align=right>
			<a href="modulos/une_dth_pedido/lista_pendiente.php?excel=s&mm=<?php echo $mm ?>&aa=<?php echo $ano_ges	 ?>" target=blank> <i class="fa fa-cloud-download fa-2x"></i> Descargar excel </a>		
		<td>
	</tr>
</table>
<br>
<center>
<div class="panel panel-default" style="width:100%;">
   <div align=left class="panel-heading">Pedidos sin concluir <b>DTH</b> </div>
  <table align=center class="table table-striped table-bordered table-hover" id="dataTables-example">
  <thead>
   <tr>
		<th></th>
		<th>Estado</th>
		<th>Pedido</th>		
		<th>Tipo trabajo</th>		
		<th>Ciudad</th>	
		<th>Producto</th>		
		<th>Tecnologia</th>
		<th>Fecha</th>
		<th>Detalle</th>
		<th>Eliminar</th>
   </tr>
   </thead>
   <tbody>
   <?php
    $query = '';
	if($_SESSION["user_id"]==14){$query = " AND ciudad = 'Sincelejo' ";}
	if($_SESSION["user_id"]==31){$query = " AND ciudad = 'Monteria' ";}
	if($_SESSION["user_id"]==30){$query = " AND ciudad = 'Valledupar' ";}
	
   $sql_pedido = "SELECT * FROM une_pedido 
				WHERE estado_material IN ('1','3','4') AND 
				fecha >= '".$ano_ges."-".$mm."-01' AND fecha <= '".$ano_ges."-".$mm."-31' AND 
				une_pedido.tipo='2' ".$query." ;";
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
			if($row_pedido["estado_material"]==1){ $estado_material = "<font color=red>Pend. Edatel</font>";  $font = " color=red ";}
			if($row_pedido["estado_material"]==4){ $estado_material = "<font color=#000000>Pend. EIA</font>"; }			
			if($row_pedido["estado_material"]==2){ $estado_material = "<font color=green>Confirmado</font>"; }
			if($row_pedido["estado_material"]==3){ $estado_material = "<font color=red>Rechazado</font>";  $font = " color=red ";}
			
			
			
	?>
			<tr>
				<td align=center><b><?php echo $alarma;?></b></td>
				<td><b><?php echo $estado_material;?></b></td>
				<td><?php echo $row_pedido["numero"] ?> </td>
				<td><?php echo $row_pedido["tipo_trabajo"] ?></td>
				<td><?php echo $row_pedido["ciudad"] ?></td>
				<td><?php echo $row_pedido["producto"] ?></td>
				<td><?php echo $row_pedido["tecnologia"] ?> </td>
				<td><?php echo $row_pedido["fecha"] ?></td>				
				<td align=center><a href="?cmp=detalle&id=<?php echo $row_pedido["id"] ?>"> <font <?php echo $font ?> ><i class="fa fa-eye fa-2x"> </i></font> </a></td></td>
				<td align=center>
					<?php if($row_pedido["estado_material"]==1){ ?>
						<a onclick="if(confirm('¿ Realmente desea ELIMINAR este pedido <?php echo $row_pedido["numero"] ?> ?') == false){return false;}" href="?eli=<?php echo $row_pedido["id"] ?>"> <font color=red ><i class="fa fa-eraser fa-2x"> </i></font> </a>
					<?php }else{ ?>
						---
					<?php } ?>
				</td>

		   </tr>
		   <?php 
	 
   }     
?>
   </tbody>

  </table>
</div>
</center>

<br><br>