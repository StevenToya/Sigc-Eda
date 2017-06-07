<?php
if($PERMISOS_GC["stohome_coor"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_GET["cerrar"])
{
	$sql_cerrar = "INSERT INTO `stohome_periodo_usuario_cmv` (`id_usuario`, `id_periodo`, `fecha_registro`) 
	VALUES ('".$_SESSION["user_id"]."', '".$_GET["cerrar"]."', '".date("Y-m-d G:i:s")."');";
	mysql_query($sql_cerrar);
}

$sql_per = "SELECT * FROM stohome_periodo WHERE estado = 1 LIMIT 1";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);

?>

<h2> SU PERSONAL ASIGNADO DEL PERIODO <b><?php echo $row_per["fecha_inicial"]; ?></b> AL <b><?php echo  $row_per["fecha_final"]; ?></b>	 </h2>



<?php
 $sql_cerrado = "SELECT id FROM stohome_periodo_usuario_cmv WHERE id_periodo = '".$row_per["id"]."' AND id_usuario = '".$_SESSION["user_id"]."' LIMIT 1";
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
				$sql = "SELECT stohome_persona.nombre , stohome_persona.zona , stohome_persona.localidad , stohome_persona.fecha_registro, stohome_sap.cuenta AS cue_sap,
						stohome_plataforma.nombre AS nom_plataforma, stohome_sap.nombre AS nom_sap, stohome_car.nombre AS nom_car, stohome_rubro.nombre AS nom_rubro,
						stohome_persona.id, stohome_persona.identificacion, municipio.nombre AS nom_municipio
			   FROM stohome_persona 
				INNER JOIN stohome_persona_item ON stohome_persona.id = stohome_persona_item.id_persona
				INNER JOIN stohome_plataforma ON stohome_persona.id_plataforma = stohome_plataforma.id
				INNER JOIN stohome_sap ON stohome_persona.id_sap = stohome_sap.id
				LEFT JOIN stohome_car ON stohome_persona.id_car = stohome_car.id
				INNER JOIN stohome_rubro ON stohome_persona.id_rubro = stohome_rubro.id	
				LEFT JOIN municipio ON stohome_persona.id_municipio = municipio.id	
				WHERE stohome_persona.id_usuario = '".$_SESSION["user_id"]."'  AND stohome_persona_item.id_item=13
				GROUP BY stohome_persona.id
				ORDER BY stohome_persona.nombre ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{		
					 $sql_cont = " SELECT SUM(IF(tipo=1,1,0)) AS ad, SUM(IF(tipo=2,1,0)) AS ah  
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
						<td><?php echo utf8_encode($row["nom_municipio"]) ?></td>	
						<td><?php echo $row["nom_plataforma"] ?></td>	
						<td><?php echo $row["cue_sap"] ?></td>
						<td align=center><?php echo $row_cont["ad"] ?></td>
						<td align=center><?php echo $row_cont["ah"] ?></td>						
						<td><center><a href="?cmp=gestionar_cmv&idp=<?php echo $row["id"]; ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a><b></center></td>
				   </tr>
				<?php
				}
				?>				   
				</tbody>
			</table>
		</div>		
	</div>
</div>

<?php if(!$row_cerrado["id"] && $row_per["estado"]==1){ ?>
	<center> 
		<a href="?cerrar=<?php echo $row_per["id"] ?>"  onclick="if(confirm('¿ Realmente desea ENVIAR y CERRAR la gestion de este periodo  ?') == false){return false;}">
		<input class="btn btn-danger" type="button" value="Enviar y cerrar la gestion de fechas en viaticos" name="guardar" />
		</a>
	</center><br>
<?php }else{ ?>
	<h2><center>
	<div style="width:50%" class="alert alert-info">
				<?php if($row_cerrado["id"]){ ?>Este periodo ya fue finalizado por usted <?php } ?>
				<?php  if($row_per["estado"]==2){ ?>Este periodo ya esta cerrado <?php } ?>
	</div>
	</center></h2>
<?php } ?>

</center>


