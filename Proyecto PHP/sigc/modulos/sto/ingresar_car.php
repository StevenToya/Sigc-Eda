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
	$sql = "INSERT INTO `sto_car` (`nombre` ,`estado`, `fecha_registro`)
			VALUES ('".limpiar($_POST["nombre"])."', '".$_POST["estado"]."', '".date("Y-m-d G:i:s")."');";
	mysql_query($sql);
	if(!mysql_error())
	{		
		 echo "<script>alert('La CAR se guardo correctamente')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=car'>";
		 die();
	}
	else
	{
		$error = "ERROR: No se pudo ingresar la CAR en la base de datos";
	}
			
}


?> 
<h2>INGRESAR NUEVA CUENTA CAR</h2>  
<div align=right>
<a href="?cmp=car"> <i class="fa fa-reply fa-2x"></i> Volver al listado de las CAR</a>
</div>
<form  method="post" action="?" enctype="multipart/form-data"> 
<br>
<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" width="60%">

	<tr>
		<td>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Nombre CAR </span>
				<input  name="nombre" value="<?php echo $_POST["nombre"] ?>" id="text" type="text" class="form-control" placeholder="Ingrese nombre del CAR" required />
			</div> 
		</td>
	</tr>
	<tr>
		<td>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Estado</span>
				<select name="estado" class="form-control" >
					<option value="">Seleccione un estado</option>
					<option value="Aprobado">Aprobado</option>
					<option value="Aplazado">Aplazado</option>
					<option value="En ejecuci&oacute;n">En ejecuci&oacute;n</option>
					<option value="Entregado">Entregado</option>	
					<option value="Enviado">Enviado</option>						
				</select>
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
<br><input class="btn btn-primary" type="submit" value="Guardar CAR" name="guardar" /></center>
</form>