<?php
if($_GET["excel"])
{
	include("../../cnx.php");
		
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
$sql_per = "SELECT * FROM stohome_periodo WHERE  id = '".$_GET["excel_periodo"]."' LIMIT 1";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);	

$datetime1 = date_create($row_per["fecha_inicial"]);
$datetime2 = date_create($row_per["fecha_final"]);
$interval = date_diff($datetime1, $datetime2);
$cant_dia = 1 + $interval->format('%a');

	
	$sql_inc = "SELECT valor FROM incremento WHERE ano = '".date("Y")."' LIMIT 1";
	$res_inc = mysql_query($sql_inc);
	$row_inc = mysql_fetch_array($res_inc);
	$incrementado = $row_inc["valor"];
	
	function moneda($dato)
{	return number_format($dato, 2, ',', '.'); }


	?>
	
	
<table border=1>				
	<tr>
		<th>Nombre</th>
		<th>Cedula</th>
		<th>Coordinador</th>
		<th>localidad</th>						
		<th>Plataforma </th>
		<th>Sap </th>
		<th>Cantidad alimentacion dia </th>
		<th>Valor  alimentacion dia </th>
		<th>Cantidad  alimentacion  y aloj. pernoctado</th>
		<th>Valor  alimentacion  y  aloj. pernoctado</th>				
	</tr>
				
				<?php
			
				$sql = "SELECT stohome_persona.nombre , stohome_persona.zona , stohome_persona.localidad , stohome_persona.fecha_registro, stohome_sap.cuenta AS cue_sap,
						stohome_plataforma.nombre AS nom_plataforma, stohome_sap.nombre AS nom_sap, stohome_car.nombre AS nom_car, stohome_rubro.nombre AS nom_rubro,
						stohome_persona.id, stohome_persona.identificacion, municipio.nombre AS nom_municipio, usuario.nombre AS nom_coordinador, usuario.apellido AS ape_coordinador
			   FROM stohome_persona 
			    INNER JOIN usuario ON stohome_persona.id_usuario = usuario.id
				INNER JOIN stohome_persona_item ON stohome_persona.id = stohome_persona_item.id_persona
				INNER JOIN stohome_plataforma ON stohome_persona.id_plataforma = stohome_plataforma.id
				INNER JOIN stohome_sap ON stohome_persona.id_sap = stohome_sap.id
				LEFT JOIN stohome_car ON stohome_persona.id_car = stohome_car.id
				INNER JOIN stohome_rubro ON stohome_persona.id_rubro = stohome_rubro.id	
				LEFT JOIN municipio ON stohome_persona.id_municipio = municipio.id	
				WHERE  stohome_persona_item.id_item=13
				GROUP BY stohome_persona.id
				ORDER BY stohome_persona.nombre ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{		
					 $sql_cont = " SELECT SUM(IF(tipo=1,1,0)) AS ad, SUM(IF(tipo=2,1,0)) AS ah, SUM(IF(tipo=1,valor,0)) AS v_ad , SUM(IF(tipo=2,valor,0)) AS v_ah 
									FROM stohome_ejecutado_cmv
								WHERE
									id_persona = '".$row["id"]."'
									AND id_periodo = '".$row_per["id"]."'	";
					$res_cont = mysql_query($sql_cont);
					$row_cont = mysql_fetch_array($res_cont);
					
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["nombre"] ?> </b></td>
						<td><b> <?php echo $row["identificacion"] ?> </b></td>
						<td> <?php echo utf8_decode($row["nom_coordinador"]) ?> <?php echo utf8_decode($row["ape_coordinador"]) ?> </td>
						<td><?php echo utf8_decode($row["nom_municipio"]) ?></td>	
						<td><?php echo $row["nom_plataforma"] ?></td>	
						<td><?php echo $row["cue_sap"] ?></td>
						<td align=center><?php echo $row_cont["ad"] ?></td>
						<td align=right>$ <?php echo moneda($row_cont["v_ad"]) ?></td>
						<td align=center><?php echo $row_cont["ah"] ?></td>						
						<td align=right>$ <?php echo moneda($row_cont["v_ah"]) ?></td>	
				   </tr>
				<?php
				}
				?>				   
				
			</table>
		<?php
die();
}


if($PERMISOS_GC["stohome_adm"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

$sql_inc = "SELECT valor FROM incremento WHERE ano = '".date("Y")."' LIMIT 1";
$res_inc = mysql_query($sql_inc);
$row_inc = mysql_fetch_array($res_inc);

$incrementado = $row_inc["valor"];

if($_GET["abrir"])
{ 
	$sql = "DELETE FROM `stohome_periodo_usuario_cmv` WHERE `stohome_periodo_usuario_cmv`.`id` = '".$_GET["abrir"]."' LIMIT 1 ";
	mysql_query($sql);
}

$sql_per = "SELECT * FROM stohome_periodo WHERE estado = 1 LIMIT 1";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);

if(!$_GET["excel_periodo"]){$_GET["excel_periodo"] = $row_per["id"];}

$sql_per = "SELECT * FROM stohome_periodo WHERE id = '".$_GET["excel_periodo"]."' LIMIT 1";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);

?>

<h2> STATUS DE LOS COORDINADORES EN EL PERIODO <b><?php echo $row_per["fecha_inicial"]; ?></b> AL <b><?php echo  $row_per["fecha_final"]; ?></b> PARA CMV </h2>
<br>
<?php


$sql_excel_anterior = "SELECT id  FROM stohome_periodo WHERE id < '".$_GET["excel_periodo"]."' ORDER BY id DESC LIMIT 1 ";
$res_excel_anterior = mysql_query($sql_excel_anterior);
$row_excel_anterior = mysql_fetch_array($res_excel_anterior);

$sql_excel_siguiente = "SELECT id  FROM stohome_periodo WHERE id > '".$_GET["excel_periodo"]."' ORDER BY id ASC LIMIT 1 ";
$res_excel_siguiente = mysql_query($sql_excel_siguiente);
$row_excel_siguiente = mysql_fetch_array($res_excel_siguiente);

$sql_excel_actual = "SELECT fecha_inicial, fecha_final FROM stohome_periodo WHERE id = '".$_GET["excel_periodo"]."' ORDER BY id ASC LIMIT 1 ";
$res_excel_actual  = mysql_query($sql_excel_actual);
$row_excel_actual  = mysql_fetch_array($res_excel_actual);

?>


<div align=right>
<?php if($row_excel_anterior["id"]){ ?>
	<a href="?excel_periodo=<?php echo $row_excel_anterior["id"] ?>"><-- Anterior </a> 
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php } ?>

	<a href="modulos/sto/administrador_cmv.php?excel=s&excel_periodo=<?php echo $_GET["excel_periodo"]; ?>" target=blank> 
	<i class="fa fa-cloud-download fa-2x"></i> Descargar periodo de <b><?php echo $row_excel_actual["fecha_inicial"] ?></b> al 
	<b><?php echo $row_excel_actual["fecha_final"] ?></b></a>

<?php if($row_excel_siguiente["id"]){ ?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="?excel_periodo=<?php echo $row_excel_siguiente["id"] ?>">Siguiente --> </a>
<?php } ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
</div>
<br>

<?php
$datetime1 = date_create($row_per["fecha_inicial"]);
$datetime2 = date_create($row_per["fecha_final"]);
$interval = date_diff($datetime1, $datetime2);
$cant_dia = 1 + $interval->format('%a');
?>

<table style="width:90%;" align=center class="table table-striped table-bordered table-hover" >
<tr>
	<th>Coordinador</th>
	<th>Personal</th>
	<th>Cantidad <br> alimentacion dia </th>
	<th>Valor <br> alimentacion dia </th>
	<th>Cantidad <br> alimentacion  y <br> aloj. pernoctado</th>	
	<th>Valor <br> alimentacion  y <br> aloj. pernoctado</th>
	<th>TOTAL</th>
	<th>Estado</th>
	<th>Accion</th>
	<th>Detalles</th>
</tr>
<?php
	$i = 0; $fila_total = 0; $total_persona = 0;
	$total_can_ad = 0; $total_val_ad = 0; $total_val = 0;
	$total_can_ah = 0; $total_val_ah = 0;
	$sql = "SELECT COUNT(*) AS cantidad , usuario.apellido, usuario.nombre, usuario.id AS id_coordinador
			FROM stohome_persona
			INNER JOIN stohome_persona_item ON stohome_persona.id = stohome_persona_item.id_persona
			INNER JOIN usuario ON stohome_persona.id_usuario = usuario.id
			WHERE  stohome_persona_item.id_item=13
			GROUP BY usuario.id 
			ORDER BY  usuario.apellido, usuario.nombre 
			";
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		
		$sql_cont = " SELECT SUM(IF(tipo=1,1,0)) AS ad, SUM(IF(tipo=2,1,0)) AS ah ,
						SUM(IF(tipo=1,stohome_ejecutado_cmv.valor,0)) AS ad_total, SUM(IF(tipo=2,stohome_ejecutado_cmv.valor,0)) AS ah_total 
						FROM stohome_ejecutado_cmv
					INNER JOIN 
							stohome_persona ON stohome_ejecutado_cmv.id_persona = stohome_persona.id
					INNER JOIN stohome_persona_item 
							ON stohome_persona.id = stohome_persona_item.id_persona
					WHERE
						id_usuario = '".$row["id_coordinador"]."'
						 AND stohome_persona_item.id_item=13
						AND id_periodo = '".$row_per["id"]."'	
						";
		$res_cont = mysql_query($sql_cont);
		$row_cont = mysql_fetch_array($res_cont);
		
		$fila_total = $row_cont["ad_total"] + $row_cont["ah_total"];
		
		$sql_cierre = "SELECT fecha_registro, id 
		FROM stohome_periodo_usuario_cmv
		WHERE stohome_periodo_usuario_cmv.id_usuario = '".$row["id_coordinador"]."' AND stohome_periodo_usuario_cmv.id_periodo = '".$row_per["id"]."' ";
		$res_cierre = mysql_query($sql_cierre);
		$row_cierre = mysql_fetch_array($res_cierre);
		if($row_cierre["id"])
		{$estado = "<font color=green>Terminado</font>";}
		else{$estado = "<font color=red>Abierto</font>";}
		
		$total_persona = $total_persona + $row["cantidad"];
		$total_can_ad = $total_can_ad + $row_cont["ad"];
		$total_val_ad = $total_val_ad + $row_cont["ad_total"];
		$total_can_ah = $total_can_ah + $row_cont["ah"];
		$total_val_ah = $total_val_ah + $row_cont["ah_total"];
		$total_val = $total_val + $fila_total;
		
?>
	<tr>
		<td><b><?php echo $row["apellido"]; ?> <?php echo $row["nombre"]; ?> </b></td>
		<td><center><?php echo $row["cantidad"]; ?> </center></td>
		
		<td><center><?php echo $row_cont["ad"]; ?> </center></td>
		<td align=right>$ <b><?php echo moneda($row_cont["ad_total"]); ?> </b></td>
		
		<td><center><?php echo $row_cont["ah"]; ?> </center></td>
		<td align=right>$ <b><?php echo moneda($row_cont["ah_total"]); ?> </b></td>
		
		<td  align=right><b>$ <?php echo moneda($fila_total); ?></b></td>
		<td><b><?php echo $estado; ?></b></td>
		<td align=center>
			<?php if($row_cierre["id"]){ ?>
				<a href="?abrir=<?php echo $row_cierre["id"] ?>"> ABRIR </a>
			<?php }else{ ?>
			---
			<?php } ?>
		</td>	
		<td align=center><a href="?cmp=lista_personal_cmv&id=<?php echo $row["id_coordinador"] ?>"> <font <?php echo $font ?> ><i class="fa fa-eye fa-2x"> </i></font> </a></td></td>	
	</tr>
<?php
	}
	
?>
	<tr bgcolor=#F3F781>
		<td>TOTALES</td>
		<td align=center><?php echo $total_persona ?></td>
		<td><center><?php echo $total_can_ad; ?> </center></td>
		<td align=right>$ <b><?php echo moneda($total_val_ad); ?> </b></td>		
		<td><center><?php echo $total_can_ah; ?> </center></td>
		<td align=right>$ <b><?php echo moneda($total_val_ah); ?> </b></td>
		<td align=right>$ <b><?php echo moneda($total_val); ?></b></td>
		<td colspan=3> </td>
	</tr>

		
</table>







