<?php
if($PERMISOS_GC["sto_adm"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_GET["cerrar"])
{
	$sql_cerrar = "INSERT INTO `sto_periodo_usuario_cmv` (`id_usuario`, `id_periodo`, `fecha_registro`) 
	VALUES ('".$_SESSION["user_id"]."', '".$_GET["cerrar"]."', '".date("Y-m-d G:i:s")."');";
	mysql_query($sql_cerrar);
}

$sql_per = "SELECT * FROM sto_periodo WHERE estado = 1 LIMIT 1";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);

$sql_coor = "SELECT nombre, apellido FROM usuario WHERE id = '".$_GET["id"]."' LIMIT 1";
$res_coor = mysql_query($sql_coor);
$row_coor = mysql_fetch_array($res_coor);

?>

<h2> PERSONAL ASIGNADO A <b><?php echo $row_coor["apellido"] ?> <?php echo $row_coor["nombre"] ?> </b> PERIODO <b><?php echo $row_per["fecha_inicial"]; ?></b> AL <b><?php echo  $row_per["fecha_final"]; ?></b>	 </h2>

<div align=right>
	<a href="?cmp=administrador_cmv"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de coordinadores </a>
</div> 


<?php
 $sql_cerrado = "SELECT id FROM sto_periodo_usuario_cmv WHERE id_periodo = '".$row_per["id"]."' AND id_usuario = '".$_SESSION["user_id"]."' LIMIT 1";
$res_cerrado = mysql_query($sql_cerrado);
$row_cerrado = mysql_fetch_array($res_cerrado);

?>


<center>	

<div class="panel panel-default"  style="width:99%">
	<div class="panel-heading" align=left>
		<b> Personal para gestionar </b>
	</div>
	<div class="panel-body" >
		<div class="table-responsive" >
		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Cedula</th>
						<th>localidad</th>						
						<th>Plataforma </th>
						<th>Sap </th>
						<th>Cantidad <br> alimentacion dia </th>
						<th>Cantidad <br> alimentacion  y <br> aloj. pernoctado</th>
						<th>Gestionar CMV </th>
						
					</tr>
				</thead>
				<tbody>
				<?php
				$vec_tem = $vec_item; 
				$sql = "SELECT sto_persona.nombre , sto_persona.zona , sto_persona.localidad , sto_persona.fecha_registro, sto_sap.cuenta AS cue_sap,
						sto_plataforma.nombre AS nom_plataforma, sto_sap.nombre AS nom_sap, sto_car.nombre AS nom_car, sto_rubro.nombre AS nom_rubro,
						sto_persona.id, sto_persona.identificacion, municipio.nombre AS nom_municipio
			   FROM sto_persona 
				INNER JOIN sto_persona_item ON sto_persona.id = sto_persona_item.id_persona
				INNER JOIN sto_plataforma ON sto_persona.id_plataforma = sto_plataforma.id
				INNER JOIN sto_sap ON sto_persona.id_sap = sto_sap.id
				LEFT JOIN sto_car ON sto_persona.id_car = sto_car.id
				INNER JOIN sto_rubro ON sto_persona.id_rubro = sto_rubro.id	
				LEFT JOIN municipio ON sto_persona.id_municipio = municipio.id	
				WHERE sto_persona.id_usuario = '".$_GET["id"]."' AND sto_persona_item.id_item=13
				GROUP BY sto_persona.id
				ORDER BY sto_persona.nombre ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{		
					 $sql_cont = " SELECT SUM(IF(tipo=1,1,0)) AS ad, SUM(IF(tipo=2,1,0)) AS ah  
									FROM sto_ejecutado_cmv
								WHERE
									id_persona = '".$row["id"]."'
									AND id_periodo = '".$row_per["id"]."'	";
					$res_cont = mysql_query($sql_cont);
					$row_cont = mysql_fetch_array($res_cont);
					
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["nombre"] ?> </b></td>
						<td><b> <?php echo $row["identificacion"] ?> </b></td>
						<td><?php echo utf8_encode($row["nom_municipio"]) ?></td>	
						<td><?php echo $row["nom_plataforma"] ?></td>	
						<td><?php echo $row["cue_sap"] ?></td>
						<td align=center><?php echo $row_cont["ad"] ?></td>
						<td align=center><?php echo $row_cont["ah"] ?></td>						
						<td><center><a href="?cmp=detalle_cmv&id=<?php echo $_GET["id"] ?>&idp=<?php echo $row["id"]; ?>"><i class="fa fa-eye fa-2x"></i></a><b></center></td>
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


