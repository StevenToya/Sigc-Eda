<?php
if($PERMISOS_GC["aud_conf"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_GET["eliminar"])
{
	$sql = "UPDATE `aud_base` SET estado=2 WHERE `id` = '".$_GET["eliminar"]."' LIMIT 1;";
	mysql_query($sql);		
}


?>

<h2> AUDITORIAS CREADAS </h2>


<div align=right> 	
		<a href='?cmp=lista_area'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Crear area</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
			<a href='?cmp=lista_categoria'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Ingresar categoria</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
		<a href='?cmp=ingresar_auditoria'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Crear auditoria</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			
</div>

<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	
</form><br><br>
<center>	
<div class="panel panel-default"  style="width:90%">
	<div class="panel-heading" align=left>
		 listado de auditorias
	</div>
	<div class="panel-body" >
		<div class="table-responsive" >
		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Nombre</th>	
						<th>Descripcion</th>	
						<th>Area</th>
						<th>Material</th>						
						<th>Item</th>								
						<th>Vista</th>
						<th>Editar</th>						
						<th>Quitar</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$sql = "SELECT aud_base.nombre, aud_base.descripcion, aud_area.nombre AS nom_area, aud_base.id, aud_base.material
				FROM aud_base 
				INNER JOIN aud_area ON aud_base.id_area = aud_area.id
				WHERE aud_base.estado = 1 ORDER BY aud_base.nombre ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{	
					if($row["material"]==1){$material = 'Si';}else{$material = 'No';}
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["nombre"] ?> </b></td>
						<td> <?php echo $row["descripcion"] ?> </td>
						<td><?php echo $row["nom_area"] ?></td>			
						<td align=center><?php echo $material; ?></td>		
						<?php
						$sql_bb = "SELECT COUNT(*) AS cant_item  FROM aud_item WHERE id_base = '".$row["id"]."' LIMIT 1 ";
						$res_bb = mysql_query($sql_bb);
						$row_bb = mysql_fetch_array($res_bb);
						?>
						<td align=center><a href="?cmp=lista_item&id_base=<?php echo $row["id"]; ?>" ><b><?php echo $row_bb["cant_item"] ?></b></a>	</td>
						<td align=center><a href="?cmp=vista&id_base=<?php echo $row["id"]; ?>" ><i class="fa fa-eye fa-2x"> </i></a>	</td>
						<td align=center><a href="?cmp=editar_auditoria&id_base=<?php echo $row["id"]; ?>" ><i class="fa fa-pencil-square-o fa-2x"></i></a></td>
						<td align=center><a href="?eliminar=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR la auditoria  <?php echo $row["nombre"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a></td>
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