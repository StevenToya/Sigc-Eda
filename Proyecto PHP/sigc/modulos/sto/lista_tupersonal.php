<?php
if($PERMISOS_GC["sto_coor"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_GET["cerrar"])
{
	$sql_cerrar = "INSERT INTO `sto_periodo_usuario` (`id_usuario`, `id_periodo`, `fecha_registro`) 
	VALUES ('".$_SESSION["user_id"]."', '".$_GET["cerrar"]."', '".date("Y-m-d G:i:s")."');";
	mysql_query($sql_cerrar);
}

$sql_per = "SELECT * FROM sto_periodo WHERE estado = 1 LIMIT 1";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);

?>

<h2> SU PERSONAL ASIGNADO DEL PERIODO <b><?php echo $row_per["fecha_inicial"]; ?></b> AL <b><?php echo  $row_per["fecha_final"]; ?></b>	 </h2>

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
			WHERE sto_persona.id_usuario = '".$_SESSION["user_id"]."' 
			GROUP BY sto_item.id";
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		$vec_item[$row["id"]] = 'n';
?>
	<tr>
		<td><b><?php echo $row["id"]; ?></b>	</td>
		<td><?php echo $row["nombre"]; ?></td>
	</tr>
<?php
	}
	
?>
</table>

<?php
 $sql_cerrado = "SELECT id FROM sto_periodo_usuario WHERE id_periodo = '".$row_per["id"]."' AND id_usuario = '".$_SESSION["user_id"]."' LIMIT 1";
$res_cerrado = mysql_query($sql_cerrado);
$row_cerrado = mysql_fetch_array($res_cerrado);

?>


<?php if(!$row_cerrado["id"] && $row_per["estado"]==1){ ?>
	<center> 
		<a href="?cerrar=<?php echo $row_per["id"] ?>"  onclick="if(confirm('¿ Realmente desea ENVIAR y CERRAR la gestion de este periodo  ?') == false){return false;}">
		<input class="btn btn-danger" type="button" value="Enviar y cerrar la gestion de fechas e Items" name="guardar" />
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
						<?php
						foreach ($vec_item AS $k => &$valor) {
						?>
							<th><center><?php echo $k ?></center></th>
						<?php
						}
						
						?>
						
					</tr>
				</thead>
				<tbody>
				<?php
				$vec_tem = $vec_item; 
				$sql = "SELECT sto_persona.nombre , sto_persona.zona , sto_persona.localidad , sto_persona.fecha_registro, sto_sap.cuenta AS cue_sap,
						sto_plataforma.nombre AS nom_plataforma, sto_sap.nombre AS nom_sap, sto_car.nombre AS nom_car, sto_rubro.nombre AS nom_rubro,
						sto_persona.id, sto_persona.identificacion, municipio.nombre AS nom_municipio
			   FROM sto_persona 
				INNER JOIN sto_plataforma ON sto_persona.id_plataforma = sto_plataforma.id
				INNER JOIN sto_sap ON sto_persona.id_sap = sto_sap.id
				LEFT JOIN sto_car ON sto_persona.id_car = sto_car.id
				INNER JOIN sto_rubro ON sto_persona.id_rubro = sto_rubro.id	
				LEFT JOIN municipio ON sto_persona.id_municipio = municipio.id	
				WHERE sto_persona.id_usuario = '".$_SESSION["user_id"]."'
				ORDER BY sto_persona.nombre ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{		
			
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["nombre"] ?> </b></td>
						<td><b> <?php echo $row["identificacion"] ?> </b></td>
						<td><?php echo utf8_encode($row["nom_municipio"]) ?></td>	
						<td><?php echo $row["nom_plataforma"] ?></td>	
						<td><?php echo $row["cue_sap"] ?></td>	
						<?php
												
						$sql_bb = "SELECT sto_persona_item.id_item 
						FROM sto_persona_item 						
						WHERE sto_persona_item.id_persona = '".$row["id"]."' ";
						$res_bb = mysql_query($sql_bb);
						while($row_bb = mysql_fetch_array($res_bb))
						{$vec_tem[$row_bb["id_item"]] = $row_bb["id_item"];;}					
						?>
						
						<?php
						foreach ($vec_tem AS $k => &$valor) {
						?>
						
							<?php if($valor=='n'){?>							
								<td><center>---</center></td>
							<?php }else{ 
									$sql_cont = "SELECT COUNT(*) AS cantidad 
										FROM sto_ejecutado  WHERE id_persona = '".$row["id"]."' AND id_item = '".$valor."' AND id_periodo = '".$row_per["id"]."' ";
									$res_cont = mysql_query($sql_cont);
									$row_cont = mysql_fetch_array($res_cont);
							?>
								<td><center><h5><a href="?cmp=gestionar&idp=<?php echo $row["id"]; ?>&idi=<?php echo $valor; ?>"><b><?php echo $row_cont["cantidad"] ?></b></a></h5></center></td>
							<?php }?>
						<?php
							$vec_tem[$k] = 'n';
						}
						?>
						
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


