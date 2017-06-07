<?php
if($PERMISOS_GC["aud_conf"]!='Si')
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
	if($_POST["material"])
	{
		$material = 1;
	}
	else
	{
		$material =0;
	}
	$sql = "INSERT INTO `aud_base` (`nombre` ,`descripcion`, `id_area`, `estado`,	`id_creador`,	`material`)
			VALUES ('".limpiar($_POST["nombre"])."', '".limpiar($_POST["descripcion"])."', '".$_POST["id_area"]."', '1', '".$_SESSION["user_id"]."', '".$material."');";
	mysql_query($sql);
	if(!mysql_error())
	{		
		 echo "<script>alert('La auditoria se guardo correctamente')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_base'>";
		 die();
	}
	else
	{
		$error = "ERROR: No se pudo ingresar la auditoria en la base de datos";
	}
			
}


?> 
<h2>INGRESAR NUEVA AUDITORIA</h2>  
<div align=right>
<a href="?cmp=lista_base"> <i class="fa fa-reply fa-2x"></i> Volver al listado de auditoria</a>
</div>
<form  method="post" action="?" enctype="multipart/form-data"> 
<br>
<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" width="60%">

	<tr>
		<td>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon"><div align=left >Nombre auditoria</div></span>
				<input  name="nombre" value="<?php echo $_POST["nombre"] ?>" id="text" type="text" class="form-control" placeholder="Ingrese nombre de la auditoria" required />
			</div> 
		</td>
	</tr>
	<tr>
		<td>
			<div  class=" form-group input-group input-group-lg" style="width:100%">
				<span  class="input-group-addon"><div align=left >Descripcion </div> </span>
				<textarea style="height: 100px;" name="descripcion"  id="text"  class="form-control" placeholder="Ingrese una descripcion auditoria" required ><?php echo $_POST["descripcion"] ?></textarea>
			</div> 
		</td>
	</tr>
	
	<tr>
		<td>
			<div  class=" form-group input-group input-group-lg" style="width:100%">
				<span  class="input-group-addon"><div align=left >Solicitar materiales </div> </span>
				<input type="checkbox" style="height: 40px;" name="material"  class="form-control"  >
			</div> 
		</td>
	</tr>
	
	<tr>
		<td>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon"><div align=left >Area o sector</div></span>
				<select name="id_area" class="form-control" >
					<option value="">Seleccione una area</option>
					<?php
					$sql_area = "SELECT id, nombre FROM aud_area WHERE estado = 1";
					$res_area = mysql_query($sql_area);
					while($row_area = mysql_fetch_array($res_area)){
					?>
						<option value="<?php echo $row_area["id"] ?>"><?php echo $row_area["nombre"] ?></option>
					<?php
					}
					?>
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
<br><input class="btn btn-primary" type="submit" value="Guardar auditoria" name="guardar" /></center>
</form>