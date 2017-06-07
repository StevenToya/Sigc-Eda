<?php
if($PERMISOS_GC["stohome_conf"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_GET["at"])
{
	$sql = "UPDATE `stohome_periodo` SET `estado` = '2' ;";
	mysql_query($sql);
	
	$sql = "UPDATE `stohome_periodo` SET `estado` = '1' WHERE id = '".$_GET["at"]."' LIMIT 1;";
	mysql_query($sql);
}

if($_GET["eliminar"])
{
	$sql = "DELETE FROM `stohome_periodo` WHERE `id` = '".$_GET["eliminar"]."' LIMIT 1;";
	mysql_query($sql);		
}

if($_POST["agregar"])
{	
	$sql = "UPDATE `stohome_periodo` SET `estado` = '2' ;";
	mysql_query($sql);

	$sql = "INSERT INTO `stohome_periodo` (	fecha_inicial, fecha_final, estado, fecha_registro)
	VALUES ('".$_POST["fecha_inicial"]."', '".$_POST["fecha_final"]."', '1', '".date("Y-m-d G:i:s")."') ;";
	mysql_query($sql);
	
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?'>";
	die();
}
?>

<h2> PERIODOS CREADOS PARA LAS S.T.O. </h2>

<?php include("submenu.php");?>

<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<table align=right>
		<tr>
			<td>
				<input  name="fecha_inicial" value="<?php echo date("Y-m-d") ?>" size="10" id="fecha_inicial" type="text"  required />
				  <script type="text/javascript">
					var opts = {                            
					formElements:{"fecha_inicial":"Y-ds-m-ds-d"},
					showWeeks:true,
					statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
					};      
					datePickerController.createDatePicker(opts);					
				</script>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
				<input  name="fecha_final" value="<?php echo date("Y-m-d") ?>" size="10" id="fecha_final" type="text"  required />
				  <script type="text/javascript">
					var opts = {                            
					formElements:{"fecha_final":"Y-ds-m-ds-d"},
					showWeeks:true,
					statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
					};      
					datePickerController.createDatePicker(opts);					
				</script>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" name="agregar" value="Agregar">
			</td>
		</tr>
	</table>
	
</form>
	
<div class="panel panel-default">
	<div class="panel-heading">
		 listado de periodos
	</div>
	<div class="panel-body">
		<div class="table-responsive">
		
			<table class="table table-striped table-bordered table-hover" >
				<thead>
					<tr>
						<th>Fecha inicial</th>	
						<th>Fecha final </th>	
						<th>Fecha registrada </th>	
						<th>Estado actual </th>
						<th></th>		
						<th>Quitar</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$sql = "SELECT * FROM stohome_periodo ORDER BY id DESC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
					$estado = "";
					if($row["estado"]==1){$estado = "<font color=red><b>Abierto</b></font>"; }
					if($row["estado"]==2){$estado = "<b>Cerrado</b>";}
					
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["fecha_inicial"] ?> </b></td>
						<td><b> <?php echo $row["fecha_final"] ?> </b></td>
						<td><?php echo $row["fecha_registro"] ?></td>
						<td><?php echo $estado ?></td>
						<td align='center'>
							<?php if($row["estado"]==2){ ?>
								<a href="?at=<?php echo $row["id"]; ?>" >Abrir</a>
							<?php }else{ ?>
								---
							<?php } ?>
						</td>
						<?php
						$sql_bb = "SELECT id FROM stohome_ejecutado WHERE id_periodo = '".$row["id"]."' LIMIT 1 ";
						$res_bb = mysql_query($sql_bb);
						$row_bb = mysql_fetch_array($res_bb);
						?>
						<td align=center>
							<?php if(!$row_bb["id"] && $row["estado"]==2){ ?>
								<a href="?eliminar=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR el periodo de <?php echo $row["fecha_inicial"]; ?> al <?php echo $row["fecha_final"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a>
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



