<?php
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

$sql_coor = "SELECT nombre, apellido FROM usuario WHERE id = '".$_GET["id"]."' LIMIT 1";
$res_coor = mysql_query($sql_coor);
$row_coor = mysql_fetch_array($res_coor);

?>

<h2> PERSONAL ASIGNADO A <b><?php echo $row_coor["apellido"] ?> <?php echo $row_coor["nombre"] ?> </b> PERIODO <b><?php echo $row_per["fecha_inicial"]; ?></b> AL <b><?php echo  $row_per["fecha_final"]; ?></b>	 </h2>

<?php
$datetime1 = date_create($row_per["fecha_inicial"]);
$datetime2 = date_create($row_per["fecha_final"]);
$interval = date_diff($datetime1, $datetime2);
$cant_dia = 1 + $interval->format('%a');
?>


<div align=right>
	<a href="?cmp=administrador"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de coordinadores </a>
</div> 


<table style="width:70%;" align=center class="table  table-bordered table-hover">
<tr>
	<th>Codigo</th>
	<th>Rol-Item</th>
</tr>
<?php
	$i = 0;
	$sql = "SELECT sto_item.id, sto_item.nombre 
			FROM `sto_persona_item`
			INNER JOIN sto_persona ON sto_persona_item.id_persona = sto_persona.id
			INNER JOIN sto_item ON sto_persona_item.id_item = sto_item.id
			WHERE sto_persona.id_usuario = '".$_GET["id"]."' 
			GROUP BY sto_item.id";
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		$vec_item[$row["id"]] = 'n';
		$vec_col[$row["id"]] = 0;
?>
	<tr>
		<td><b><?php echo $row["id"]; ?></b>	</td>
		<td><?php echo $row["nombre"]; ?></td>
	</tr>
<?php
	}
	
?>
</table>





<center>	
<div class="panel panel-default"  style="width:99%">
	<div class="panel-heading" align=left>
		<b> Personal con sus item </b>
	</div>
	<div class="panel-body" >
		<div class="table-responsive" >
		
			<table class="table table-striped table-bordered table-hover" >
				<thead>
					<tr>
						<th>Nombre</th>
						<th>localidad</th>						
						<th>Plataforma </th>
						<th>Presupuestado </th>
						<?php
						foreach ($vec_item AS $k => &$valor) {
						?>
							<th><center><?php echo $k ?></center></th>
						<?php
						}						
						?>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$vec_tem = $vec_item; 
				$sql = "SELECT sto_persona.nombre , sto_persona.zona , sto_persona.localidad , sto_persona.fecha_registro, sto_sap.cuenta AS cue_sap,
						sto_plataforma.nombre AS nom_plataforma, sto_sap.nombre AS nom_sap, sto_car.nombre AS nom_car, sto_rubro.nombre AS nom_rubro,
						sto_persona.identificacion, municipio.nombre AS nom_municipio, sto_persona.id AS idp,
						(
							SELECT SUM( ((sto_item.valor * ".$incrementado.")/30) * ".$cant_dia.")
							FROM sto_persona_item
							INNER JOIN sto_item
								ON sto_persona_item.id_item = sto_item.id
							WHERE sto_persona_item.id_persona = idp
						) AS meta
						
			   FROM sto_persona 
				INNER JOIN sto_plataforma ON sto_persona.id_plataforma = sto_plataforma.id
				INNER JOIN sto_sap ON sto_persona.id_sap = sto_sap.id
				LEFT JOIN sto_car ON sto_persona.id_car = sto_car.id
				INNER JOIN sto_rubro ON sto_persona.id_rubro = sto_rubro.id	
				LEFT JOIN municipio ON sto_persona.id_municipio = municipio.id	
				WHERE sto_persona.id_usuario = '".$_GET["id"]."'
				ORDER BY sto_persona.nombre ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{		
			
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["nombre"] ?> </b></td>
						<td><?php echo utf8_encode($row["nom_municipio"]) ?></td>	
						<td><?php echo $row["nom_plataforma"] ?></td>	
						<td align=right>$ <b><?php echo moneda($row["meta"]); ?> </b></td>
						<?php
												
						$sql_bb = "SELECT sto_persona_item.id_item 
						FROM sto_persona_item 						
						WHERE sto_persona_item.id_persona = '".$row["idp"]."' ";
						$res_bb = mysql_query($sql_bb);
						while($row_bb = mysql_fetch_array($res_bb))
						{$vec_tem[$row_bb["id_item"]] = $row_bb["id_item"];;}					
						?>
						
						<?php
						$total_fila = "0";
						foreach ($vec_tem AS $k => &$valor) {
						?>
						
							<?php if($valor=='n'){?>							
								<td><center>---</center></td>
							<?php }else{ 
									
									$sql_cont = "SELECT SUM(valor * ".$incrementado.") AS cantidad 
										FROM sto_ejecutado  WHERE id_persona = '".$row["idp"]."' AND id_item = '".$valor."' AND id_periodo = '".$row_per["id"]."' ";
									$res_cont = mysql_query($sql_cont);
									$row_cont = mysql_fetch_array($res_cont);
									$total_fila = $total_fila + $row_cont["cantidad"];
									$vec_col[$k] =  $vec_col[$k] + $row_cont["cantidad"];
							?>
								<td  align=right><a href="?cmp=detalle&id=<?php echo $_GET["id"]; ?>&idp=<?php echo $row["idp"]; ?>&idi=<?php echo $valor; ?>"><b><?php echo moneda($row_cont["cantidad"]) ?></b></a></td>
							<?php }?>
						<?php
							$vec_tem[$k] = 'n';
							
						}
						?>
						<td align=right><b><?php echo moneda($total_fila) ?></b></td>
				   </tr>
				<?php
				}
				?>	
				<tr>
					<td colspan=4 align=right>TOTALES </td>
					<?php
					foreach ($vec_col AS $k => &$valor) {
					?>
							<td align=right><b><?php echo moneda($valor); ?></b></td>
					<?php
							$total_total = $total_total + $valor;
					}
					?>
					<td align=right><b><font color=red><?php echo moneda($total_total); ?></font></b></td>
				</tr>
				
				</tbody>
			</table>
		</div>		
	</div>
</div>
</center>


