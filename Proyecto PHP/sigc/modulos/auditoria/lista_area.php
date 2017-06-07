<?php
if($PERMISOS_GC["aud_conf"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_POST["guardar"])
{		
	 $sql = "INSERT INTO `aud_area` (`nombre` , `estado`)
			VALUES ('".limpiar($_POST["nombre"])."', '1');";
	mysql_query($sql);
	if(!mysql_error())
	{		
		 echo "<script>alert('El area se guardo correctamente')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?id_base=".$_GET["id_base"]."'>";
		 die();
	}
	else
	{
		$error = "ERROR: No se pudo ingresar el area en la base de datos";
	}
			
}




if($_GET["eliminar"])
{
	$sql = "UPDATE `aud_area` SET estado=2 WHERE `id` = '".$_GET["eliminar"]."' LIMIT 1;";
	mysql_query($sql);		
}


?>

<h2> INGRESAR AREAS PARA LAS AUDITORIA </h2>
<form  method="post" action="?" enctype="multipart/form-data"> 
	<table width=100%>
		<tr>
			<td> 
				<input  name="nombre" value="<?php echo $_POST["nombre"] ?>" id="text" type="text" class="form-control" placeholder="Ingresar categoria" required />
			</td>
			
			<td>
				<input class="btn btn-primary" type="submit" value="Guardar area" name="guardar" />
			</td>
			<td align=right>
				<a href="?cmp=lista_base"> <i class="fa fa-reply fa-2x"></i> Volver al listado de auditorias </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
			</td>
		</tr>
	</table>
</form>
<br>

<center>	
<div class="panel panel-default"  style="width:60%">
	<div class="panel-heading" align=left>
		 listado de area
	</div>
	<div class="panel-body" >
		<div class="table-responsive" >
		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Area</th>							
						<th>Quitar</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$sql = "SELECT *
				FROM aud_area
				WHERE aud_area.estado = 1  ORDER BY aud_area.nombre ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{						
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["nombre"] ?> </b></td>
						<td align=center><a href="?id_base=<?php echo $_GET["id_base"] ?>&eliminar=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR esta area <?php echo $row["nombre"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a></td>
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