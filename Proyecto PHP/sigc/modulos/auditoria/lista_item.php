<?php
if($PERMISOS_GC["aud_conf"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

$sql = "SELECT * FROM aud_base WHERE id = '".$_GET["id_base"]."' LIMIT 1 ";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);


if($_GET["eliminar"])
{
	$sql = "UPDATE `aud_item` SET estado=2 WHERE `id` = '".$_GET["eliminar"]."' LIMIT 1;";
	mysql_query($sql);		
}


?>

<h2> GESTIONAR ITEMS DE LA AUDITORIA  <b><?php echo $row["nombre"] ?></b></h2>
<div align=right> 						
		<a href='?cmp=ingresar_item&id_base=<?php echo $_GET["id_base"] ?>'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Ingresar item</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			
		<a href="?cmp=lista_base"> <i class="fa fa-reply fa-2x"></i> Volver al listado de auditorias</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
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
						<th>Pregunta</th>	
						<th>Tipo</th>	
						<th>Categoria</th>
						<th>Quitar</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$sql = "SELECT aud_item.pregunta, aud_item.tipo, aud_categoria.nombre AS nom_categoria, aud_item.id
				FROM aud_item 
				INNER JOIN aud_categoria ON aud_item.id_categoria = aud_categoria.id
				WHERE aud_item.estado = 1 AND aud_item.id_base = '".$_GET["id_base"]."' ORDER BY aud_item.pregunta ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{	
					$tipo = '';
					if($row["tipo"]==1){$tipo = 'Dos opciones -Si- o -No-';}
					if($row["tipo"]==2){$tipo = 'Texto libre';}
					if($row["tipo"]==3){$tipo = 'Cargar imagen';}
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["pregunta"] ?> </b></td>
						<td> <?php echo $tipo ?> </td>
						<td><?php echo $row["nom_categoria"] ?></td>							
						<td align=center><a href="?id_base=<?php echo $_GET["id_base"] ?>&eliminar=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR este item   <?php echo $row["pregunta"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a></td>
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