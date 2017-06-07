<?php
if($PERMISOS_GC["sto_conf"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_GET["eliminar"])
{
	$sql = "DELETE FROM `sto_sap` WHERE `id` = '".$_GET["eliminar"]."' LIMIT 1;";
	mysql_query($sql);		
}

if($_POST["agregar"])
{	
	
	$sql = "INSERT INTO `sto_sap` (cuenta, nombre, fecha_registro)
	VALUES ('".limpiar($_POST["cuenta"])."','".limpiar($_POST["nombre"])."', '".date("Y-m-d G:i:s")."') ;";
	mysql_query($sql);
	
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?'>";
	die();
}
?>

<h2> CUENTAS SAP CREADAS PARA LAS S.T.O. </h2>

<?php include("submenu.php");?>

<div align=right> 						
		<a href='?cmp=ingresar_sap'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Crear nueva cuenta SAP</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			
</div>

<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	
</form><br><br>
<center>	
<div class="panel panel-default"  style="width:90%">
	<div class="panel-heading" align=left>
		 listado de cuentas sap
	</div>
	<div class="panel-body" >
		<div class="table-responsive" >
		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Sap</th>	
						<th>Cuenta</th>	
						<th>Fecha registrada </th>							
						<th>Quitar</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$sql = "SELECT * FROM sto_sap ORDER BY nombre ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
									
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["nombre"] ?> </b></td>
						<td><b> <?php echo $row["cuenta"] ?> </b></td>
						<td><?php echo $row["fecha_registro"] ?></td>							
						<?php
						$sql_bb = "SELECT id FROM sto_persona WHERE id_sap = '".$row["id"]."' LIMIT 1 ";
						$res_bb = mysql_query($sql_bb);
						$row_bb = mysql_fetch_array($res_bb);
						?>
						<td align=center>
							<?php if(!$row_bb["id"]){ ?>
								<a href="?eliminar=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR el SAP  <?php echo $row["nombre"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a>
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