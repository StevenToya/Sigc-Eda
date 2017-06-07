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
	$sql = "INSERT INTO `sto_item` (`nombre` ,`valor` ,`tipo` , `fecha_registro`)
			VALUES ('".limpiar($_POST["nombre"])."', '".numero_decimal($_POST["valor"])."', '2', '".date("Y-m-d G:i:s")."');";
	mysql_query($sql);
	if(!mysql_error())
	{		
		 echo "<script>alert('El item se guardo correctamente')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=item'>";
		 die();
	}
	else
	{
		$error = "ERROR: No se pudo ingresar itemen la base de datos";
	}
			
}


?> 
<h2>INGRESAR NUEVO ITEM</h2>  
<div align=right>
<a href="?"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de items </a>
</div>
<form  method="post" action="?" enctype="multipart/form-data"> 
<br>
<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" width="60%">

	<tr>
		<td>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Nombre del item  </span>
				<input  name="nombre" value="<?php echo $_POST["nombre"] ?>" id="text" type="text" class="form-control" placeholder="Ingrese el item" required />
			</div> 
		</td>
	</tr>
	<tr>
		<td>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Valor del item</span>
				<input  name="valor" value="<?php echo $_POST["valor"] ?>" id="valor" type="text" class="form-control" placeholder="Ingrese el valor del item (mes)" required />
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
<br><input class="btn btn-primary" type="submit" value="Guardar Item" name="guardar" /></center>
</form>