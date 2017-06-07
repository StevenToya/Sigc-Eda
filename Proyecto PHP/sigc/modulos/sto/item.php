<?php
if($PERMISOS_GC["sto_conf"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_GET["eliminar"])
{
	$sql = "DELETE FROM `sto_item` WHERE `id` = '".$_GET["eliminar"]."' LIMIT 1;";
	mysql_query($sql);		
}


?>

<h2> ITEM CREADOS PARA LAS S.T.O. </h2>

<?php include("submenu.php");?>
<div align=right> 						
		<a href='?cmp=ingresar_item'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Crear nuevo item</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			
</div>
<center>	
<div class="panel panel-default"  style="width:90%">
	<div class="panel-heading" align=left>
		 listado de items
	</div>
	<div class="panel-body" >
		<div class="table-responsive" >
		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Item</th>
						<th>Valor</th>						
						<th>Fecha registrada </th>	
						<th>Editar</th>							
						<th>Quitar</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$sql = "SELECT * FROM sto_item WHERE tipo = 2 ORDER BY nombre ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{									
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["nombre"] ?> </b></td>
						<td align=right><b> <?php echo moneda($row["valor"]) ?> </b></td>
						<td><?php echo $row["fecha_registro"] ?></td>	
						<td align=center><a href="?cmp=editar_item&id=<?php echo $row["id"]; ?>" ><i class="fa fa-pencil-square-o fa-2x"></i></a></td>
						<?php
						$sql_bb = "SELECT id FROM sto_persona_item WHERE id_item = '".$row["id"]."' LIMIT 1 ";
						$res_bb = mysql_query($sql_bb);
						$row_bb = mysql_fetch_array($res_bb);
						?>
						<td align=center>
							<?php if(!$row_bb["id"]){ ?>
								<a href="?eliminar=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR el item de <?php echo $row["nombre"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a>
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
</center>


