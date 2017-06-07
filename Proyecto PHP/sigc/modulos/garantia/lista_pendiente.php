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
				$sql_bus_material = "SELECT id, nombre FROM une_material WHERE  tipo=1 ORDER BY nombre";
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
	WHERE ".$query ." fecha >= '".$_GET["aa"]."-".$_GET["mm"]."-01' AND fecha <= '".$_GET["aa"]."-".$_GET["mm"]."-31' AND une_pedido.tipo='1' ORDER BY numero";
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
<script>
	function popUpmensaje(URL) 
	{
		day = new Date();
		id = day.getTime();
		eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1350,height=650,left = 200,top = 5');");
	}
</script>



<?php

if($_GET["ee"])
{
	$_SESSION["garantia"] = $_GET["ee"];
}

if($_GET["eli"])
{	
	$sql_eli = "UPDATE `tramite` SET `garantia_vista` = 's' WHERE `tramite`.`id` = '".$_GET["eli"]."' ;";
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
$mes_ges = $mes_ges + 0;
if($mes_ges < 9){$mm = '0'.$mes_ges; }else{$mm = $mes_ges;}


?>

<h2>TRAMITES EN GARANTIA , REITERATIVOS Y REINCIDENTES</h2>


<br>

<table width="100%">
	<tr>
		<td>					
			<?php
		$sql_cont = "SELECT COUNT(*) AS cantidad, tipo_garantia  FROM tramite  
		WHERE 
			fecha_atencion_orden >= '".$ano_ges."-".$mm."-01 00:00:00' AND 
			fecha_atencion_orden <= '".$ano_ges."-".$mm."-31 23:59:59' AND 
			tramite.codigo_unidad_operativa = '".$cuo."' AND 
			tramite.ultimo='s' AND 	
			tipo_garantia IS NOT NULL AND
			garantia_vista = 'n' 
		GROUP BY tipo_garantia  
		ORDER BY tipo_garantia";
		$res_cont = mysql_query($sql_cont);
		while($row_cont = mysql_fetch_array($res_cont))
		{
						
				if($_SESSION["garantia"]==$row_cont["tipo_garantia"]){$boton = 'primary';}else{$boton = 'info';}
				
				if($row_cont["tipo_garantia"]=='1')$ees = "Garantia";
				if($row_cont["tipo_garantia"]=='2')$ees = "Reincidente";
				if($row_cont["tipo_garantia"]=='3')$ees = "Reiterativo";
				
			?>
				<a href="?mesg=<?php echo $mm ?>&aa=<?php echo $ano_ges; ?>&ee=<?php echo $row_cont["tipo_garantia"] ?>">
					<button class="btn btn-<?php echo $boton; ?>" type="button">
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
			<!--
			<a href="modulos/une_pedido/excel_oficial.php?excel=1&mm=<?php echo $mm ?>&aa=<?php echo $ano_ges	 ?>" target=blank> <i class="fa fa-cloud-download fa-2x"></i> Descargar excel </a>		
			-->
		<td>
	</tr>
</table>
<br>
<center>
<div class="panel panel-default" style="width:100%;">
   <div align=left class="panel-heading">Pedidos sin concluir </div>
  <table align=center class="table table-striped table-bordered table-hover" id="dataTables-example">
  <thead>
   <tr>
		<th>Tecnico</th>
		<th>OT</th>
		<th>Fecha ejecucion</th>		
		<th>Tipo trabajo</th>		
		<th>Localidad</th>	
		<th>Zona</th>		
		<th>Detalle</th>
		<th>Eliminar</th>
   </tr>
   </thead>
   <tbody>
   <?php
    $query = '';
	if(!$_SESSION["garantia"]){ $_SESSION["garantia"] = '1'; }
	
   $sql = "SELECT tramite.ot, tecnico.nombre AS nom_tecnico, tramite.fecha_atencion_orden, tipo_trabajo.nombre AS nom_trabajo, 
   localidad.nombre AS nom_localidad, tramite.zona, tramite.id
   FROM tramite 
				LEFT JOIN tecnico ON 
					tramite.id_tecnico = tecnico.id
				LEFT JOIN localidad ON 
					tramite.id_localidad = localidad.id
				LEFT JOIN tipo_trabajo ON 
					tramite.id_tipo_trabajo = tipo_trabajo.id
				WHERE 		
				fecha_atencion_orden >= '".$ano_ges."-".$mm."-01 00:00:00' AND 
				fecha_atencion_orden <= '".$ano_ges."-".$mm."-31 23:59:59' AND 
				tramite.codigo_unidad_operativa = '".$cuo."' AND 
				tramite.ultimo='s'   AND 
				garantia_vista = 'n' AND
				tipo_garantia = '".$_SESSION["garantia"]."' ;";
   $res = mysql_query($sql);
   while($row = mysql_fetch_array($res))
   {		
				
	?>
			<tr>
				<td><?php echo $row["nom_tecnico"] ?></td>		
				<td><?php echo $row["ot"] ?> </td>
				<td><?php echo $row["fecha_atencion_orden"] ?></td>
				<td><?php echo $row["nom_trabajo"] ?></td>
				<td><?php echo $row["nom_localidad"] ?></td>
				<td><?php echo $row["zona"] ?> </td>						
					<td align=center> <a href="javascript:popUpmensaje('modulos/liquidacion/detalle.php?id=<?php echo $row["id"]; ?>')"> <i class="fa fa-eye fa-2x"> </i> </a></td>
				<td align=center>
				
						<a onclick="if(confirm('¿ Realmente desea QUITAR este pedido <?php echo $row["numero"] ?> de la lista?') == false){return false;}" href="?mesg=<?php echo $mm ?>&eli=<?php echo $row["id"] ?>"> <font color=red ><i class="fa fa-eraser fa-2x"> </i></font> </a>
					
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