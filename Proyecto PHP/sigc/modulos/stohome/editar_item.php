<?php
if($PERMISOS_GC["stohome_conf"]!='Si')
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

$sql = "SELECT * FROM stohome_item WHERE id= '".$_GET["id"]."' LIMIT 1 ";
$res = mysql_query($sql);	
$row = mysql_fetch_array($res);

if($_POST["guardar"])
{		
	$sql = "UPDATE  `stohome_item` SET  
	`nombre` = '".limpiar($_POST["nombre"])."',
	`valor` = '".numero_decimal($_POST["valor"])."'
	WHERE id = '".$_GET["id"]."' LIMIT 1 ;";
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
<form  method="post" action="?id=<?php echo $_GET["id"] ?>" enctype="multipart/form-data"> 
<br>
<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" width="60%">

	<tr>
		<td>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Nombre del item  </span>
				<input  name="nombre" value="<?php echo $row["nombre"] ?>" id="text" type="text" class="form-control" placeholder="Ingrese el item" required />
			</div> 
		</td>
	</tr>
	<tr>
		<td>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Valor del item</span>
				<input  name="valor" value="<?php echo $row["valor"] ?>" id="valor" type="text" class="form-control" placeholder="Ingrese el valor del item (mes)" required />
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
<br><input class="btn btn-primary" type="submit" value="Editar Item" name="guardar" /></center>
</form>