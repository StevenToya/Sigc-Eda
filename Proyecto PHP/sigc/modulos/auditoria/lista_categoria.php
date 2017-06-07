<?php
if($PERMISOS_GC["aud_conf"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_POST["guardar"])
{		
	 $sql = "INSERT INTO `aud_categoria` (`nombre` ,`orden`, `estado`)
			VALUES ('".limpiar($_POST["nombre"])."', '".$_POST["orden"]."', '1');";
	mysql_query($sql);
	if(!mysql_error())
	{		
		 echo "<script>alert('La categoria se guardo correctamente')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?id_base=".$_GET["id_base"]."'>";
		 die();
	}
	else
	{
		$error = "ERROR: No se pudo ingresar la categoria en la base de datos";
	}
			
}





$sql = "SELECT * FROM aud_base WHERE id = '".$_GET["id_base"]."' LIMIT 1 ";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);

if($_GET["eliminar"])
{
	$sql = "UPDATE `aud_categoria` SET estado=2 WHERE `id` = '".$_GET["eliminar"]."' LIMIT 1;";
	mysql_query($sql);		
}


?>

<h2> INGRESAR CATEGORIAS PARA LA AUDITORIA  <b><?php echo $row["nombre"] ?></b></h2>
<form  method="post" action="?id_base=<?php echo $_GET["id_base"]; ?>" enctype="multipart/form-data"> 
	<table width=100%>
		<tr>
			<td> 
				<input  name="nombre" value="<?php echo $_POST["nombre"] ?>" id="text" type="text" class="form-control" placeholder="Ingresar categoria" required />
			</td>
			<td>
					
					<select name="orden" class="form-control" required >
						<option value="">Nivel de aparcion</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>	
						<option value="11">11</option>	
						<option value="12">12</option>	
						<option value="13">13</option>	
						<option value="14">14</option>	
						<option value="15">15</option>	
						<option value="16">16</option>	
						<option value="17">17</option>	
						<option value="18">18</option>	
						<option value="19">19</option>	
						<option value="20">20</option>	
						
					</select>				
			</td>
			<td>
				<input class="btn btn-primary" type="submit" value="Guardar categoria" name="guardar" />
			</td>
			<td align=right>
				<a href="?cmp=lista_base"> <i class="fa fa-reply fa-2x"></i> Volver al listado de auditorias </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
			</td>
		</tr>
	</table>
</form>
<br>

<center>	
<div class="panel panel-default"  style="width:70%">
	<div class="panel-heading" align=left>
		 listado de categorias
	</div>
	<div class="panel-body" >
		<div class="table-responsive" >
		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Categoria</th>	
						<th>Orden de aparicion</th>	
						<th>Quitar</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$sql = "SELECT aud_categoria.nombre, aud_categoria.orden, aud_categoria.id
				FROM aud_categoria 
				WHERE aud_categoria.estado = 1  ORDER BY aud_categoria.orden ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{	
					
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["nombre"] ?> </b></td>
						<td><?php echo $row["orden"] ?></td>							
						<td align=center><a href="?id_base=<?php echo $_GET["id_base"] ?>&eliminar=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR esta categoria   <?php echo $row["nombre"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a></td>
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