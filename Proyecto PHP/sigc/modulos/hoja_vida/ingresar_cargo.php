<?php

if($PERMISOS_GC["hv_cre"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


if($_POST["guardar"])
{		
	
		$sql = "INSERT INTO `cargo` (`nombre` ,`descripcion` ,`estado`, `id_instancia`)
				VALUES ('".limpiar($_POST["nombre"])."', '".limpiar($_POST["descripcion"])."', '1', '".$_SESSION["nst"]."');";
		mysql_query($sql);
		if(!mysql_error())
		{	 
			 echo '<script >alert("El cargo se guardo correctamente!");</script>';
			 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_cargo'>";
			 die();	
		}
		else
		{
			$error = "ERROR: No se pudo ingresar el cargo en la base de datos (".mysql_error().")";
		}
}



?> 
<h2>INGRESAR CARGO</h2>  
		
<div align=right>
	<a href="?cmp=lista_cargo"> <i class="fa fa-reply fa-2x"></i> Volver al listado de cargos </a>
</div>
		
<form  method="post" action="?" enctype="multipart/form-data"> 
		<br>
		<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" border=0 width="90%">

			<tr>
				<td >
					<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Nombre  </span>
						  <input  name="nombre" value="<?php echo $_POST["nombre"] ?>" id="lugar" type="text" class="form-control" placeholder="Ingresar nombre del cargo" required />
					</div> 
				</td>
			</tr>
			
			<tr>
				<td >
					<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Descripcion <br> del cargo</span>
						  <textarea  name="descripcion" id="descripcion" style="height:100px" class="form-control" placeholder="" required ><?php echo $_POST["descripcion"] ?></textarea>
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
		<br><input class="btn btn-primary" type="submit" value="Guardar cargo" name="guardar" /></center>
</form>
