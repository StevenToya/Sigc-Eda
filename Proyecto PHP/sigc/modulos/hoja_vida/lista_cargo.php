<?php
if($PERMISOS_GC["hv_cre"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_GET["eliminar"])
{	
	$sql_eli = "UPDATE `cargo` SET `estado` = '2' WHERE `id` = '".$_GET["eliminar"]."' LIMIT 1;";
	mysql_query($sql_eli);	
}

?>
<h2>CARGOS O ROLES</h2>
<div align=right> 						
	<a href='?cmp=ingresar_cargo'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Crear nuevo cargo</a>					
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		 listado de cargos activos
	</div>
	<div class="panel-body">
		<div class="table-responsive">
		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Nombre</th>	
						<th>Descripcion</th>						
						<th>Estado</th>
						<th>Editar</th>										
					</tr>
				</thead>
				<tbody>
				<?php
				$sql = "SELECT cargo.nombre,  cargo.descripcion, cargo.id 	
				FROM cargo 			
				WHERE cargo.estado='1' AND cargo.id_instancia='".$_SESSION["nst"]."'  ORDER BY cargo.nombre";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{						
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["nombre"] ?> </b></td>
						<td><?php echo $row["descripcion"]; ?> </td>					
						<td><a href="?cmp=editar_cargo&id=<?php echo $row["id"] ?>"><i class="fa fa-pencil-square-o fa-2x"></i> </a></td></td>
						<td align='center'><a href="?eliminar=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR el cargo <?php echo $row["nombre"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a></td>						
				   </tr>
				<?php
				}
				?>
				   
				</tbody>
			</table>
		</div>		
	</div>
</div>



