<?php
if($PERMISOS_GC["sto_conf"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


function numero_decimal($str) 
{ 
  if(strstr($str, ",")) { 
    $str = str_replace(".", "", $str); 
    $str = str_replace(",", ".", $str);
  } 
  
  if(preg_match("#([0-9\.]+)#", $str, $match)) { // search for number that may contain '.' 
    return floatval($match[0]); 
  } else { 
    return floatval($str); // take some last chances with floatval 
  } 
} 


if($_POST["guardar"])
{		
	$sql = "INSERT INTO `sto_rubro` (`nombre` ,`codigo`, `fecha_registro`)
			VALUES ('".limpiar($_POST["nombre"])."', '".limpiar($_POST["codigo"])."', '".date("Y-m-d G:i:s")."');";
	mysql_query($sql);
	if(!mysql_error())
	{		
		 echo "<script>alert('El Rubro se guardo correctamente')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=rubro'>";
		 die();
	}
	else
	{
		$error = "ERROR: No se pudo ingresar la rubro en la base de datos";
	}
			
}


?> 
<h2>INGRESAR NUEVA RUBRO</h2>  
<div align=right>
<a href="?cmp=car"> <i class="fa fa-reply fa-2x"></i> Volver al listado RUBRO</a>
</div>
<form  method="post" action="?" enctype="multipart/form-data"> 
<br>
<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" width="60%">

	<tr>
		<td>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Rubro </span>
				<input  name="nombre" value="<?php echo $_POST["nombre"] ?>" id="text" type="text" class="form-control" placeholder="Ingrese nombre del rubro" required />
			</div> 
		</td>
	</tr>
	<tr>
		<td>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Codigo</span>
				<input  name="codigo" value="<?php echo $_POST["codigo"] ?>" id="text" type="text" class="form-control" placeholder="Ingrese el codigo del rubro" required />
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
<br><input class="btn btn-primary" type="submit" value="Guardar " name="guardar" /></center>
</form>