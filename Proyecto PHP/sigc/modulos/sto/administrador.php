<?php
if($_GET["excel"])
{
	include("../../cnx.php");
		
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	$sql_per = "SELECT * FROM sto_periodo WHERE id = '".$_GET["excel_periodo"]."' LIMIT 1";
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

	$i = 0;
	$sql = "SELECT sto_item.id, sto_item.nombre, sto_item.valor 
			FROM `sto_persona_item`
			INNER JOIN sto_persona ON sto_persona_item.id_persona = sto_persona.id
			INNER JOIN sto_item ON sto_persona_item.id_item = sto_item.id
			GROUP BY sto_item.id";
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		$vec_item[$row["id"]] = 'n';
		$vec_col[$row["id"]] = $row["nombre"];
		$vec_val[$row["id"]] = $row["valor"];
	}
	
	?>
	
<table border=1>				
	<tr>
		<th bgcolor=red><font color='#FFFFFF'>Nombre</font></th>
		<th bgcolor=red><font color='#FFFFFF'>Identificacion</font></th>
		<th bgcolor=red><font color='#FFFFFF'>localidad</font></th>		
		<th bgcolor=red><font color='#FFFFFF'>Coordinador</font></th>				
		<th bgcolor=red><font color='#FFFFFF'>Plataforma </font></th>
		<th bgcolor=red><font color='#FFFFFF'>SAP </font></th>
		<th bgcolor=red><font color='#FFFFFF'>Cuenta SAP </font></th>
		<th bgcolor=red><font color='#FFFFFF'>CAR </font></th>
		<th bgcolor=red><font color='#FFFFFF'>RUBRO </font></th>
		<?php
		foreach ($vec_item AS $k => &$valor) {
		?>
			<th bgcolor=red><font color='#FFFFFF'><center>Valor presupuestado: <?php echo $vec_col[$k]; ?></font></center></th>
			<th bgcolor=red><font color='#FFFFFF'><center>Valor consumido: <?php echo $vec_col[$k]; ?></font></center></th>
		<?php
		}						
		?>
		<th bgcolor=red><font color='#FFFFFF'>OBSERVACION - NOVEDADES </font></th>
	</tr>		
				<?php
				$vec_tem = $vec_item; 
				$sql = "SELECT sto_persona.nombre , sto_persona.zona ,  sto_persona.identificacion , sto_persona.localidad , sto_persona.fecha_registro, sto_sap.cuenta AS cue_sap,
						sto_plataforma.nombre AS nom_plataforma, sto_sap.nombre AS nom_sap, sto_car.nombre AS nom_car, sto_rubro.nombre AS nom_rubro,
						sto_persona.identificacion, municipio.nombre AS nom_municipio, sto_persona.id AS idp, usuario.nombre AS usu_nombre, usuario.apellido AS usu_apellido						
			   FROM sto_persona 
				INNER JOIN usuario ON sto_persona.id_usuario = usuario.id
				INNER JOIN sto_plataforma ON sto_persona.id_plataforma = sto_plataforma.id
				INNER JOIN sto_sap ON sto_persona.id_sap = sto_sap.id
				LEFT JOIN sto_car ON sto_persona.id_car = sto_car.id
				INNER JOIN sto_rubro ON sto_persona.id_rubro = sto_rubro.id	
				LEFT JOIN municipio ON sto_persona.id_municipio = municipio.id	
				ORDER BY sto_persona.nombre ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{		
			
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo utf8_decode($row["nombre"]); ?> </b></td>
						<td> <?php echo $row["identificacion"] ?></td>
						<td><?php echo $row["nom_municipio"] ?></td>	
						<td><?php echo utf8_decode($row["usu_apellido"]) ?> <?php echo utf8_decode($row["usu_nombre"]) ?></td>	
						<td><?php echo utf8_decode($row["nom_plataforma"]) ?></td>	
						<td><?php echo $row["nom_sap"] ?></td>
						<td><?php echo $row["cue_sap"] ?></td>
						<td><?php echo $row["nom_car"] ?></td>
						<td><?php echo $row["nom_rubro"] ?></td>
						<?php
												
						$sql_bb = "SELECT sto_persona_item.id_item 
						FROM sto_persona_item 						
						WHERE sto_persona_item.id_persona = '".$row["idp"]."' ";
						$res_bb = mysql_query($sql_bb);
						while($row_bb = mysql_fetch_array($res_bb))
						{$vec_tem[$row_bb["id_item"]] = $row_bb["id_item"];;}					
						?>
						
						<?php
						foreach ($vec_tem AS $k => &$valor) {
						?>
						
							<?php if($valor=='n'){?>							
								<td><center>---</center></td>
								<td><center>---</center></td>
							<?php }else{ 
									
									$sql_cont = "SELECT SUM(valor * ".$incrementado.") AS cantidad 
										FROM sto_ejecutado  WHERE id_persona = '".$row["idp"]."' AND id_item = '".$valor."' AND id_periodo = '".$row_per["id"]."' ";
									$res_cont = mysql_query($sql_cont);
									$row_cont = mysql_fetch_array($res_cont);									
									$presupuestado = $cant_dia  *( ($vec_val[$k]/30) * $incrementado);
									
							?>
								<td  align=right><?php echo moneda($presupuestado) ?></td>
								<td  align=right><b><?php echo moneda($row_cont["cantidad"]) ?></b></td>
							<?php }?>
						<?php
							$vec_tem[$k] = 'n';							
						}
						?>
							<td><?php
										$sql_nov = "SELECT * FROM  `sto_no_ejecutado` 
										WHERE id_periodo = '".$row_per["id"]."' AND  id_persona = '".$row["idp"]."' GROUP BY fecha ORDER BY fecha";
										$res_nov = mysql_query($sql_nov);
										while($row_nov = mysql_fetch_array($res_nov)){					
									?>
										<b><?php echo $row_nov["fecha"]; ?></b>:<?php echo utf8_decode($row_nov["observacion"]); ?> 
										
											&nbsp;&nbsp;&nbsp; 

										
									<?php
										}
									?>
									
							</td>
				   </tr>
				<?php
				}
				?>	
		
			</table>
		<?php
die();
}


if($PERMISOS_GC["sto_adm"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

$sql_inc = "SELECT valor FROM incremento WHERE ano = '".date("Y")."' LIMIT 1";
$res_inc = mysql_query($sql_inc);
$row_inc = mysql_fetch_array($res_inc);

$incrementado = $row_inc["valor"];

$sql_per = "SELECT * FROM sto_periodo WHERE estado = 1 LIMIT 1";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);


if($_GET["abrir"])
{
	$sql = "DELETE FROM `sto_periodo_usuario` WHERE `id_usuario` = '".$_GET["abrir"]."' AND id_periodo = '".$row_per["id"]."' ";
	mysql_query($sql);
}

$sql_per = "SELECT * FROM sto_periodo WHERE estado = 1 LIMIT 1";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);

if(!$_GET["excel_periodo"]){$_GET["excel_periodo"] = $row_per["id"];}

$sql_per = "SELECT * FROM sto_periodo WHERE id = '".$_GET["excel_periodo"]."' LIMIT 1";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);

?>




<h2> STATUS DE LOS COORDINADORES EN EL PERIODO <b><?php echo $row_per["fecha_inicial"]; ?></b> AL <b><?php echo  $row_per["fecha_final"]; ?></b>	 </h2>
<br>
<?php



$sql_excel_anterior = "SELECT id  FROM sto_periodo WHERE id < '".$_GET["excel_periodo"]."' ORDER BY id DESC LIMIT 1 ";
$res_excel_anterior = mysql_query($sql_excel_anterior);
$row_excel_anterior = mysql_fetch_array($res_excel_anterior);

$sql_excel_siguiente = "SELECT id  FROM sto_periodo WHERE id > '".$_GET["excel_periodo"]."' ORDER BY id ASC LIMIT 1 ";
$res_excel_siguiente = mysql_query($sql_excel_siguiente);
$row_excel_siguiente = mysql_fetch_array($res_excel_siguiente);

$sql_excel_actual = "SELECT fecha_inicial, fecha_final FROM sto_periodo WHERE id = '".$_GET["excel_periodo"]."' ORDER BY id ASC LIMIT 1 ";
$res_excel_actual  = mysql_query($sql_excel_actual);
$row_excel_actual  = mysql_fetch_array($res_excel_actual);

?>


<div align=right>
<?php if($row_excel_anterior["id"]){ ?>
	<a href="?excel_periodo=<?php echo $row_excel_anterior["id"] ?>"><-- Anterior </a> 
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php } ?>

	<a href="modulos/sto/administrador.php?excel=s&excel_periodo=<?php echo $_GET["excel_periodo"]; ?>" target=blank> 
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
	<th>Presupuestado periodo</th>
	<th>Ejecutado periodo</th>
	<th>% Ejecutado</th>
	<th>Estado</th>
	<th>Accion</th>
	<th>Detalles</th>
</tr>
<?php
	$i = 0; $cantidad_total = 0; $cantidad_meta = 0;
	$sql = "SELECT COUNT(*) AS cantidad , usuario.apellido, usuario.nombre, usuario.id AS id_coordinador,
				(
					SELECT SUM( ((sto_item.valor * ".$incrementado.")  /30) * ".$cant_dia.")
					FROM sto_persona
					INNER JOIN sto_persona_item
						ON sto_persona.id = sto_persona_item.id_persona
					INNER JOIN sto_item
						ON sto_persona_item.id_item = sto_item.id
					WHERE id_usuario = id_coordinador
				) AS meta
	
			FROM sto_persona
			INNER JOIN usuario ON sto_persona.id_usuario = usuario.id
			GROUP BY usuario.id ORDER BY  usuario.apellido, usuario.nombre ";
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		$cantidad_pesona = $cantidad_pesona + $row["cantidad"];
		$cantidad_meta = $cantidad_meta + $row["meta"];;
		$sql_tot = "SELECT SUM(valor * ".$incrementado.") AS total , ( (SUM(valor * ".$incrementado.")*100)/ ".$row["meta"]." ) AS pro_con
		FROM sto_ejecutado
		INNER JOIN sto_persona ON sto_ejecutado.id_persona = sto_persona.id
		WHERE sto_persona.id_usuario = '".$row["id_coordinador"]."' AND sto_ejecutado.id_periodo = '".$row_per["id"]."' ";
		$res_tot = mysql_query($sql_tot);
		$row_tot = mysql_fetch_array($res_tot);
		
		$sql_cierre = "SELECT fecha_registro, id 
		FROM sto_periodo_usuario
		WHERE sto_periodo_usuario.id_usuario = '".$row["id_coordinador"]."' AND sto_periodo_usuario.id_periodo = '".$row_per["id"]."' ";
		$res_cierre = mysql_query($sql_cierre);
		$row_cierre = mysql_fetch_array($res_cierre);
		if($row_cierre["id"])
		{$estado = "<font color=green>Terminado</font>";}
		else{$estado = "<font color=red>Abierto</font>";}
		
		$row_tot["pro_con"] = 0 + $row_tot["pro_con"] ;
		$cantidad_total = $cantidad_total + $row_tot["total"];
?>
	<tr>
		<td><b><?php echo $row["apellido"]; ?> <?php echo $row["nombre"]; ?> </b></td>
		<td><center><?php echo $row["cantidad"]; ?> </center></td>
		<td align=right>$ <b><?php echo moneda($row["meta"]); ?> </b></td>
		<td align=right>$ <b><?php echo moneda($row_tot["total"]); ?> </b></td>	
		<td><center><?php echo round($row_tot["pro_con"],2); ?> % </center></td>
		<td><b><?php echo $estado; ?></b></td>
		<td align=center>
			<?php if($row_cierre["id"]){ ?>
				<a href="?abrir=<?php echo $row["id_coordinador"] ?>"> ABRIR </a>
			<?php }else{ ?>
			---
			<?php } ?>
		</td>	
		<td align=center><a href="?cmp=lista_personal&id=<?php echo $row["id_coordinador"] ?>"> <font <?php echo $font ?> ><i class="fa fa-eye fa-2x"> </i></font> </a></td></td>	
	</tr>
<?php
	}
	
?>
		<tr>
			<td><b>TOTALES </b></td>
			<td><center><?php echo $cantidad_pesona; ?> </center></td>
			<td align=right>$ <b><?php echo moneda($cantidad_meta); ?> </b></td>
			<td align=right>$ <b><?php echo moneda($cantidad_total); ?> </b></td>
			<td align=center>
			<?php
					$tem_por =  (($cantidad_total * 100)/$cantidad_meta)
			?>
				 <b><?php echo round($tem_por,2); ?> % </b>
			</td>
			<td colspan=3> </td>
			
		</tr>

		
</table>







