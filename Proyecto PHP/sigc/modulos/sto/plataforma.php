<?php
if($PERMISOS_GC["sto_conf"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_GET["eliminar"])
{
	$sql = "DELETE FROM `sto_plataforma` WHERE `id` = '".$_GET["eliminar"]."' LIMIT 1;";
	mysql_query($sql);		
}

if($_POST["agregar"])
{	
	
	$sql = "INSERT INTO `sto_plataforma` (nombre, fecha_registro)
	VALUES ('".$_POST["nombre"]."', '".date("Y-m-d G:i:s")."') ;";
	mysql_query($sql);
	
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?'>";
	die();
}
?>

<h2> PLATAFORMA CREADAS PARA LAS S.T.O. </h2>

<?php include("submenu.php");?>

<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<table align=right>
		<tr>
			<td>
				<input  name="nombre" value=""  id="nombre" type="text"  required />				
				<input type="submit" name="agregar" value="Agregar">
			</td>
		</tr>
	</table>
	
</form><br><br>
<center>	
<div class="panel panel-default"  style="width:90%">
	<div class="panel-heading" align=left>
		 listado de plataforma
	</div>
	<div class="panel-body" >
		<div class="table-responsive" >
		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Plataforma</th>	
						<th>Fecha registrada </th>							
						<th>Quitar</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$sql = "SELECT * FROM sto_plataforma ORDER BY nombre ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
									
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["nombre"] ?> </b></td>
						<td><?php echo $row["fecha_registro"] ?></td>							
						<?php
						$sql_bb = "SELECT id FROM sto_persona WHERE id_plataforma = '".$row["id"]."' LIMIT 1 ";
						$res_bb = mysql_query($sql_bb);
						$row_bb = mysql_fetch_array($res_bb);
						?>
						<td align=center>
							<?php if(!$row_bb["id"]){ ?>
								<a href="?eliminar=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR la plataforma de <?php echo $row["nombre"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a>
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


