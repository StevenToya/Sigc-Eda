<?php
if($_GET["excel"])
{
	include("../../cnx.php");
		
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	function moneda($dato)
{	return number_format($dato, 2, ',', '.'); }
	
	
	?>
		<table border=1>
			<tr>				
				<th bgcolor=red><font color='#FFFFFF'>Nombre</font></th>
				<th bgcolor=red><font color='#FFFFFF'>Cedula</font></th>
				<th bgcolor=red><font color='#FFFFFF'>Municipio</font></th>
				<th bgcolor=red><font color='#FFFFFF'>Plataforma</font></th>
				<th bgcolor=red><font color='#FFFFFF'>Rol</font></th>
				<th bgcolor=red><font color='#FFFFFF'>Sap</font></th>
				<th bgcolor=red><font color='#FFFFFF'>Cuenta SAP</font></th>				
				<th bgcolor=red><font color='#FFFFFF'>Rubro</font></th>
				<th bgcolor=red><font color='#FFFFFF'>Rubro Codigo</font></th>
				<th bgcolor=red><font color='#FFFFFF'>Coordinador</font></th>
				<?php
				$sql_item = "SELECT * FROM stohome_item ORDER BY tipo, nombre ";
				$res_item = mysql_query($sql_item);
				while($row_item = mysql_fetch_array($res_item))
				{
					$vec_item[$row_item["id"]] = $row_item["valor"];
				?>
						<th bgcolor=red><font color='#FFFFFF'><?php echo $row_item["nombre"] ?></font></th>				
				<?php
				}
				?>				
			</tr>
		
			
		<?php	
		$sql = "SELECT stohome_persona.nombre , stohome_persona.zona , stohome_persona.localidad , stohome_persona.fecha_registro, stohome_sap.id AS sap_id, stohome_car.id AS car_id, stohome_rubro.codigo AS cod_rubro,
							stohome_plataforma.nombre AS nom_plataforma, stohome_sap.nombre AS nom_sap, stohome_sap.cuenta AS cue_sap, stohome_car.nombre AS nom_car, stohome_rubro.nombre AS nom_rubro,
							stohome_item.nombre AS nom_rol, usuario.nombre AS nom_usuario, usuario.apellido AS ape_usuario, stohome_persona.id, stohome_rubro.id AS rubro_id,
							stohome_persona.identificacion, stohome_plataforma.id AS plataforma_id, usuario.id AS usuario_id, stohome_item.id AS item_id,
							municipio.nombre AS nom_municipio, municipio.id AS municipio_id
				   FROM stohome_persona 
					INNER JOIN stohome_plataforma ON stohome_persona.id_plataforma = stohome_plataforma.id
					INNER JOIN stohome_sap ON stohome_persona.id_sap = stohome_sap.id
					LEFT JOIN stohome_car ON stohome_persona.id_car = stohome_car.id
					INNER JOIN stohome_rubro ON stohome_persona.id_rubro = stohome_rubro.id
					LEFT JOIN usuario ON stohome_persona.id_usuario = usuario.id
					LEFT JOIN stohome_persona_item ON stohome_persona.id = stohome_persona_item.id_persona
					LEFT JOIN stohome_item ON stohome_persona_item.id_item = stohome_item.id
					LEFT JOIN municipio ON stohome_persona.id_municipio = municipio.id
					WHERE  stohome_item.tipo = '1' AND stohome_persona.estado='1'";
					$res = mysql_query($sql);
					while($row = mysql_fetch_array($res))
					{	
							$vec_item_per[] = "";
							$sql_item_per = "SELECT * FROM  `stohome_persona_item` WHERE id_persona = '".$row["id"]."' ";
							$res_item_per = mysql_query($sql_item_per);
							while($row_item_per = mysql_fetch_array($res_item_per))
							{$vec_item_per[$row_item_per["id_item"]] = $vec_item[$row_item_per["id_item"]];}
					?>
						<tr class="odd gradeX">
							<td><b> <?php echo $row["nombre"] ?> </b></td>
							<td> <?php echo $row["identificacion"] ?> </td>
							<td> <?php echo $row["nom_municipio"] ?> </td>
							<td> <?php echo $row["nom_plataforma"] ?> </td>
							<td> <?php echo $row["nom_rol"] ?> </td>
							<td> <?php echo $row["nom_sap"] ?> </td>
							<td> <?php echo $row["cue_sap"] ?> </td>
							<td> <?php echo $row["nom_rubro"] ?> </td>
							<td> <?php echo $row["cod_rubro"] ?> </td>
							<td> <?php echo $row["nom_usuario"] ?> <?php echo $row["ape_usuario"] ?> </td>		
							<?php
								$total_fila = "0";
								foreach ($vec_item AS $k => &$valor) {
							?>
								<td> 
									<?php if($vec_item_per[$k]) { ?>
										<?php echo moneda($vec_item_per[$k]) ?> 
									<?php }else{ ?>
										--
									<?php } ?>
								</td>
						
							<?php
								$vec_item_per[$k] = '';
							}
							?>	
							
					   </tr>
					<?php
					}
					?>		
		
<?php
die();
}
?>








<?php
if($PERMISOS_GC["stohome_conf"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_GET["eliminar"])
{	
	$sql = "UPDATE `stohome_persona` SET 
	`estado` = '2',
	`id_usuario` = NULL
	WHERE
	`id` = '".$_GET["eliminar"]."' LIMIT 1;";
	mysql_query($sql);		
}


?>

<h2> PERSONAL CREADO PARA LAS S.T.O. </h2>

<?php include("submenu.php");?>
<div align=right> 	
		<a href="modulos/sto/persona.php?excel=s" target=blank> <i class="fa fa-cloud-download fa-2x"></i> Descargar excel </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
		<a href='?cmp=ingresar_persona'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Ingresar nueva persona</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			
</div>
<center>	
<div class="panel panel-default"  style="width:100%">
	<div class="panel-heading" align=left>
		 listado del personal
	</div>
	<div class="panel-body" >
		<div class="table-responsive">		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Nombre</th>	
						<th>Identificacion</th>	
						<th>Localidad</th>	
						<th>Plataforma</th>	
						<th>Rol</th>
						<th>Coordinador</th>
						<th>Quitar</th>	
						<th>Editar</th>						
					</tr>
				</thead>
				<tbody>
					<?php
				$sql = "SELECT stohome_persona.nombre , stohome_persona.zona , stohome_persona.localidad , stohome_persona.fecha_registro,
							stohome_plataforma.nombre AS nom_plataforma, stohome_sap.nombre AS nom_sap, stohome_car.nombre AS nom_car, stohome_rubro.nombre AS nom_rubro,
							stohome_item.nombre AS nom_rol, usuario.nombre AS nom_usuario, usuario.apellido AS ape_usuario, stohome_persona.id, 
							stohome_persona.identificacion, municipio.nombre AS nom_municipio
				   FROM stohome_persona 
					INNER JOIN stohome_plataforma ON stohome_persona.id_plataforma = stohome_plataforma.id
					INNER JOIN stohome_sap ON stohome_persona.id_sap = stohome_sap.id
					LEFT JOIN stohome_car ON stohome_persona.id_car = stohome_car.id
					INNER JOIN stohome_rubro ON stohome_persona.id_rubro = stohome_rubro.id
					LEFT JOIN usuario ON stohome_persona.id_usuario = usuario.id
					LEFT JOIN stohome_persona_item ON stohome_persona.id = stohome_persona_item.id_persona
					LEFT JOIN stohome_item ON stohome_persona_item.id_item = stohome_item.id
					LEFT JOIN municipio ON stohome_persona.id_municipio = municipio.id
					WHERE stohome_persona.estado = 1 AND stohome_item.tipo = 1
					ORDER BY stohome_persona.nombre ASC";
					$res = mysql_query($sql);
					while($row = mysql_fetch_array($res))
					{										
					?>
						<tr class="odd gradeX">
							<td><b> <?php echo $row["nombre"] ?> </b></td>
							<td> <?php echo $row["identificacion"] ?> </td>
							<td> <?php echo utf8_encode($row["nom_municipio"]) ?> </td>
							<td> <?php echo $row["nom_plataforma"] ?> </td>
							<td> <?php echo $row["nom_rol"] ?> </td>
							<td> <?php echo $row["nom_usuario"] ?> <?php echo $row["ape_usuario"] ?> </td>
							<td align=center><a href="?eliminar=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea RETIRAR la persona <?php echo $row["nombre"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a></td>
							<td align=center><a href="?cmp=editar_persona&id=<?php echo $row["id"]; ?>" ><i class="fa fa-pencil fa-2x"></i></a></td>
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


