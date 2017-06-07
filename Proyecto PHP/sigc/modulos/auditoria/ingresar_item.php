<?php
if($PERMISOS_GC["aud_conf"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

$sql = "SELECT * FROM aud_base WHERE id = '".$_GET["id_base"]."' LIMIT 1 ";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);

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
	 $sql = "INSERT INTO `aud_item` (`pregunta` ,`tipo`, `id_categoria`, `id_base`, `estado`)
			VALUES ('".limpiar($_POST["pregunta"])."', '".$_POST["tipo"]."', '".$_POST["id_categoria"]."', '".$_GET["id_base"]."', '1');";
	mysql_query($sql);
	if(!mysql_error())
	{		
		 echo "<script>alert('El item se guardo correctamente')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_item&id_base=".$_GET["id_base"]."'>";
		 die();
	}
	else
	{
		$error = "ERROR: No se pudo ingresar la auditoria en la base de datos";
	}
			
}




?> 
<h2>INGRESAR ITEM PARA LA  AUDITORIA  <b><?php echo $row["nombre"] ?></b> </h2>  
<div align=right>
<a href="?cmp=lista_item&id_base=<?php echo $_GET["id_base"]; ?>"> <i class="fa fa-reply fa-2x"></i> Volver al listado de items</a>
</div>
<form  method="post" action="?id_base=<?php echo $_GET["id_base"]; ?>" enctype="multipart/form-data"> 
<br>
<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" width="60%">

	<tr>
		<td>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon"><div align=left >Pregunta</div></span>
				<input  name="pregunta" value="<?php echo $_POST["pregunta"] ?>" id="text" type="text" class="form-control" placeholder="Ingrese la pregunta para este item" required />
			</div> 
		</td>
	</tr>

	<tr>
		<td>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon"><div align=left >Tipo</div></span>
				<select name="tipo" class="form-control" required >
					<option value="">Seleccione un tipo</option>
					<option value="1">Dos opciones -Si- o -No-</option>
					<option value="2">Texto libre</option>
					<option value="3">Cargar imagen</option>
				</select>
			</div> 
		</td>
	</tr>
	
	<tr>
		<td>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon"><div align=left >Categoria</div></span>
				<select name="id_categoria" class="form-control" required >
					<option value="">Seleccione una categoria</option>
					<?php
					$sql_categoria = "SELECT id, nombre FROM aud_categoria WHERE estado = 1";
					$res_categoria = mysql_query($sql_categoria);
					while($row_categoria = mysql_fetch_array($res_categoria)){
					?>
						<option value="<?php echo $row_categoria["id"] ?>"><?php echo $row_categoria["nombre"] ?></option>
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
<br><input class="btn btn-primary" type="submit" value="Guardar item" name="guardar" /></center>
</form>