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
				<th bgcolor=red>Material</th>
				<th bgcolor=red>Codigo</th>
				<th bgcolor=red>Cantidad</th>			
			</tr>	
	
	<?php
	
	
	

	$sql = "SELECT material_traza.cantidad, equipo_material.nombre, equipo_material.codigo_1
	FROM material_traza 
			INNER JOIN equipo_material ON material_traza.id_equipo_material = equipo_material.id
			WHERE 	 material_traza.id_pedido = '".$_GET["id_pedido"]."'  ORDER BY equipo_material.nombre ;";		
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		$estado = '';
		if(!$row["id_tramite"]){$estado = '<font color=red>Sin ocupar</font>';}
		else{$estado = '<font color=green>Ocupado</font>';}
		?>
			<tr>
				
				<td><?php echo $row["nombre"] ?></td>
				<td><?php echo $row["codigo_1"] ?></td>
				<td><?php echo $row["cantidad"]; ?></td>
			</tr>
		
		<?php
		
	}
	?>
	</table>
	<?php
	die();
}

?>




<h2>PEDIDOS CREADOS </h2>
<?php  if($PERMISOS_GC["equ_ges"]=='Si'){   ?>
	<div align=right> 						
		<a href='?cmp=ingresar_pedido'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Crear nuevo pedido</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		
		<a href="?cmp=panel_material"> <i class="fa fa-reply fa-2x"></i> Volvel a los materiales sin usar </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
<?php  }  ?>


<?php
if($_GET["cam_est"])
{
	$sql = "UPDATE pedido_equipo_material SET estado = '".$_GET["cam_est"]."' WHERE id='".$_GET["id"]."' LIMIT 1";
	mysql_query($sql);		
}

if($_GET["eliminar"])
{
	$sql = "DELETE FROM `pedido_equipo_material` WHERE `id` = '".$_GET["eliminar"]."' LIMIT 1;";
	mysql_query($sql);		
}

?>

<div class="panel panel-default">
	<div class="panel-heading">
		 Listado de pedidos activos
	</div>
	<div class="panel-body">
		<div class="table-responsive">
		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Numero del pedido</th>
						<th>Bodega</th>
						<th>Creador</th>
						<th>Fecha</th>
						<th>Fecha de registro</th>
						<th>Material</th>
						<th>Estado actual</th>
						<th>Descarga</th>
						<th>Cargar cantidades</th>
						<?php /* if($PERMISOS_GC["usu_edi"]=='Si'){ */ ?> <th>Cambiar a</th> <?php /* } */ ?>
						<?php /* if($PERMISOS_GC["usu_eli"]=='Si'){ */ ?> <th>Quitar</th> <?php /* } */ ?>											
					</tr>
				</thead>
				<tbody>
				<?php
				$sql = "SELECT pedido_equipo_material.numero, pedido_equipo_material.estado, pedido_equipo_material.fecha, pedido_equipo_material.fecha_registro, 
							pedido_equipo_material.id, usuario.nombre, usuario.apellido, localidad.nombre AS nombre_localidad
						FROM pedido_equipo_material  
						INNER JOIN usuario ON pedido_equipo_material.id_usuario = usuario.id 
						INNER JOIN localidad ON pedido_equipo_material.id_localidad = localidad.id 
						WHERE pedido_equipo_material.tipo=2 ORDER BY pedido_equipo_material.numero DESC;";
				$res = mysql_query($sql);
				while($row=mysql_fetch_array($res))
				{				
					$sql_cantidad = "SELECT COUNT(*) AS cantidad, id_equipo_material 
					FROM material_traza WHERE id_pedido = '".$row["id"]."' ";
					$res_cantidad = mysql_query($sql_cantidad);
					$row_cantidad = mysql_fetch_array($res_cantidad);
					
					
					
					$estado = '';
					if($row["estado"]==1){$estado = "<font color=red>Abierto</font>"; $ec=2; $cambiar = "Cerrado";}
					if($row["estado"]==2){$estado = "Cerrado"; $ec=1; $cambiar = "Abierto";}
				?>
					<tr class="odd gradeX">
						<td><b><?php echo $row["numero"] ?></b></td>
						<td><?php echo $row["nombre_localidad"] ?> </td>
						<td><?php echo $row["nombre"] ?> <?php echo $row["apellido"] ?> </td>
						<td><?php echo $row["fecha"] ?></td>
						<td><?php echo $row["fecha_registro"] ?></td>
						<td><?php echo $row_cantidad["cantidad"] ?></td>
						<td><b><?php echo $estado ?></b></td>
						<td align=center><a href="modulos/material/lista_pedido.php?excel=1&id_pedido=<?php echo $row["id"] ?>" target=blank> <i class="fa fa-cloud-download fa-2x"></i> </a></td>
						<td align=center>
							<?php if($row["estado"]==1){ ?>
								<a href="?cmp=carga_cantidad&id=<?php echo $row["id"]; ?>"> <i class="fa fa-download fa-2x"></i> </a>
							<?php }else{ ?>
								---
							<?php }?>
						</td> 
						<td align=center> 
							<a href="?cam_est=<?php echo $ec ;?>&id=<?php echo $row["id"]; ?>"> <?php echo $cambiar; ?> </a>
						</td> 						
						<?php
						$sql_bb = "SELECT id FROM equipo_serial WHERE id_pedido = '".$row["id"]."' LIMIT 1 ";
						$res_bb = mysql_query($sql_bb);
						$row_bb = mysql_fetch_array($res_bb);
						?>
						<td align=center>
							<?php if(!$row_bb["id"]){ ?>
								<a href="?eliminar=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR el pedido <?php echo $row["numero"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a>
							<?php }else{ ?>
								---
							<?php }?>													
						</td> 						
				   </tr>
				<?php
				}
				?>
				   
				</tbody>
			</table>
		</div>
		
	</div>
</div>
<!--End Advanced Tables -->
              