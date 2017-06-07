<?php

if($PERMISOS_GC["hv_cre"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


if($_POST["guardar"])
{		
					
		$sql = "UPDATE `cargo` SET 
		`nombre` = '".limpiar($_POST["nombre"])."', 
		`descripcion` = '".limpiar($_POST["descripcion"])."' 
		WHERE `id` = '".$_GET["id"]."' LIMIT 1;";
		mysql_query($sql);
		if(!mysql_error())
		{	 
			 echo '<script >alert("El cargo se actualizo correctamente!");</script>';
			 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_cargo'>";
			 die();	
		}
		else
		{
			$error = "ERROR: No se pudo ingresar el cargo en la base de datos (".mysql_error().")";
		}
}



?> 
<h2>EDITAR CARGO</h2>  
		
<div align=right>
	<a href="?cmp=lista_cargo"> <i class="fa fa-reply fa-2x"></i> Volver al listado de cargos </a>
</div>


<?php

$sql = "SELECT * FROM cargo WHERE id = '".$_GET["id"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);


?>
		
<form  method="post" action="?id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data"> 
		<br>
		<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" border=0 width="90%">

			<tr>
				<td >
					<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Nombre  </span>
						  <input  name="nombre" value="<?php echo $row["nombre"] ?>" id="lugar" type="text" class="form-control" placeholder="Ingresar nombre del cargo" required />
					</div> 
				</td>
			</tr>
			
			<tr>
				<td >
					<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Descripcion <br> del cargo</span>
						  <textarea  name="descripcion" id="descripcion" style="height:100px" class="form-control" placeholder="" required ><?php echo $row["descripcion"] ?></textarea>
					</div> 
				</td>
			</tr>			

		</table>

		<center>
		<?php if($error){?>
							<div class="alert alert-info">
								<?php echo $error; ?>
							</div>
						<?php } ?>
		<br><input class="btn btn-primary" type="submit" value="Editar cargo" name="guardar" /></center>
</form>
