<?php

if($_POST["agregar"])
{
	
	$_POST["descripcion"] = limpiar2($_POST["descripcion"]);
	
	$sql = "SELECT id FROM documento	WHERE descripcion = '".$_POST["descripcion"]."' AND tipo='2' LIMIT 1";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	if(!$row["id"])
	{
		if($_POST["fecha_vencimiento"]=='s')
		{$fecha_vencimiento = 's';}else{$fecha_vencimiento = 'n';}
		
		if($_POST["fecha_revision"]=='s')
		{$fecha_revision = 's';}else{$fecha_revision = 'n';}
		
		$sql = "INSERT INTO `documento` (`descripcion`, `fecha_vencimiento`, `fecha_revision`, `revision_mes`, `estado`, `tipo`, `id_instancia`) 
		VALUES ('".$_POST["descripcion"]."', '".$fecha_vencimiento ."',  '".$fecha_revision."', '".$_POST["revision_mes"]."', '1','".$_GET["tip"]."', '".$_SESSION["nst"]."');";
		mysql_query($sql);
		if(!mysql_error())
		{
			echo '<script>alert("Documento registrado correctamente.")</script>';
			echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index'>";die();
		}
		else
		{
			echo '<script>alert("Error interno al ingresarlo en la base de datos.")</script>';
		}
		
	}
	else
	{
		echo '<script>alert("Este nombre ya esta registrado en es sistema.")</script>';
	}
}

function limpiar2($data)
{
	$data = str_replace('  ', ' ', $data);
	$data = str_replace("<","",$data);
	$data = str_replace(">","",$data);
	$data = str_replace('"',"",$data);
	$data = str_replace("'","",$data);
	$data = str_replace("/","",$data);
	$data = str_replace ('
', '', $data);

	$data = str_replace('á', 'a', $data);	$data = str_replace('à', 'a', $data);	
	$data = str_replace('é', 'e', $data);	$data = str_replace('è', 'e', $data);	
	$data = str_replace('í', 'i', $data);	$data = str_replace('ì', 'i', $data);	
	$data = str_replace('ó', 'o', $data);	$data = str_replace('ò', 'o', $data);	
	$data = str_replace('ú', 'u', $data);	$data = str_replace('ù', 'u', $data);	
	$data = str_replace('Á', 'A', $data);	$data = str_replace('À', 'a', $data);	
	$data = str_replace('É', 'E', $data);	$data = str_replace('È', 'e', $data);	
	$data = str_replace('Í', 'I', $data);	$data = str_replace('Ì', 'i', $data);	
	$data = str_replace('Ó', 'O', $data);	$data = str_replace('Ò', 'o', $data);	
	$data = str_replace('Ú', 'U', $data);	$data = str_replace('Ù', 'u', $data);	
	$data = str_replace('Ñ', 'N', $data);	$data = str_replace('ñ', 'n', $data);	
	
	$data = str_replace(utf8_decode('á'), 'a', $data);	$data = str_replace(utf8_decode('à'), 'a', $data);	
	$data = str_replace(utf8_decode('é'), 'e', $data);	$data = str_replace(utf8_decode('è'), 'e', $data);	
	$data = str_replace(utf8_decode('í'), 'i', $data);	$data = str_replace(utf8_decode('ì'), 'i', $data);	
	$data = str_replace(utf8_decode('ó'), 'o', $data);	$data = str_replace(utf8_decode('ò'), 'o', $data);	
	$data = str_replace(utf8_decode('ú'), 'u', $data);	$data = str_replace(utf8_decode('ù'), 'u', $data);	
	$data = str_replace(utf8_decode('Á'), 'A', $data);	$data = str_replace(utf8_decode('À'), 'a', $data);	
	$data = str_replace(utf8_decode('É'), 'E', $data);	$data = str_replace(utf8_decode('È'), 'e', $data);	
	$data = str_replace(utf8_decode('Í'), 'I', $data);	$data = str_replace(utf8_decode('Ì'), 'i', $data);	
	$data = str_replace(utf8_decode('Ó'), 'O', $data);	$data = str_replace(utf8_decode('Ò'), 'o', $data);	
	$data = str_replace(utf8_decode('Ú'), 'U', $data);	$data = str_replace(utf8_decode('Ù'), 'u', $data);	
	$data = str_replace(utf8_decode('Ñ'), 'N', $data);	$data = str_replace(utf8_decode('ñ'), 'n', $data);

	$data = str_replace(utf8_encode('á'), 'a', $data);	$data = str_replace(utf8_encode('à'), 'a', $data);	
	$data = str_replace(utf8_encode('é'), 'e', $data);	$data = str_replace(utf8_encode('è'), 'e', $data);	
	$data = str_replace(utf8_encode('í'), 'i', $data);	$data = str_replace(utf8_encode('ì'), 'i', $data);	
	$data = str_replace(utf8_encode('ó'), 'o', $data);	$data = str_replace(utf8_encode('ò'), 'o', $data);	
	$data = str_replace(utf8_encode('ú'), 'u', $data);	$data = str_replace(utf8_encode('ù'), 'u', $data);	
	$data = str_replace(utf8_encode('Á'), 'A', $data);	$data = str_replace(utf8_encode('À'), 'a', $data);	
	$data = str_replace(utf8_encode('É'), 'E', $data);	$data = str_replace(utf8_encode('È'), 'e', $data);	
	$data = str_replace(utf8_encode('Í'), 'I', $data);	$data = str_replace(utf8_encode('Ì'), 'i', $data);	
	$data = str_replace(utf8_encode('Ó'), 'O', $data);	$data = str_replace(utf8_encode('Ò'), 'o', $data);	
	$data = str_replace(utf8_encode('Ú'), 'U', $data);	$data = str_replace(utf8_encode('Ù'), 'u', $data);	
	$data = str_replace(utf8_encode('Ñ'), 'N', $data);	$data = str_replace(utf8_encode('ñ'), 'n', $data);
	
	return $data;
}
?>

<h2>CREAR DOCUMENTO PARA <?php if($_GET["tip"]==3){?> PERSONA <?php }else{ ?>  VEHICULO <?php } ?></h2>
<div align="right">
	<a href="?cmp=index"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de documento </a>
</div>

<form action="?tip=<?php echo $_GET["tip"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">

<table border='0' width='50%' align=center>
	<tr>
		<td>
			<div class=" form-group input-group input-group-lg">
			  <span class="input-group-addon">Nombre del documento</span>
			  <input  name="descripcion" id="descripcion" value="<?php echo $_POST["descripcion"] ?>" type="text" size="50" class="form-control" placeholder="Nombre del documento que se va a exigir" required />
			</div> 
			
			<div class=" form-group input-group input-group-lg">
			  <span class="input-group-addon">Con fecha vencimiento</span>
			  <input type='checkbox' class="form-control"   name="fecha_vencimiento" value="s" >
			</div> 
			
			<div class=" form-group input-group input-group-lg">
			  <span class="input-group-addon">Con fecha expedicion</span>
			  <input type='checkbox' class="form-control"   name="fecha_revision" value="s" onclick="document.getElementById('revision_mes').disabled = !this.checked">
			</div> 
			
			<div class=" form-group input-group input-group-lg">
			  <span class="input-group-addon">Meses para la revision</span>
			  <input  name="revision_mes" id="revision_mes" value="<?php echo $_POST["revision_mes"] ?>" disabled type="text" size="50" class="form-control" placeholder="" required />
			</div> 

			
		</td>
		
	</tr>

	
</table>
<br>
	<center>
		<input type="submit" value="Agregar documento" name="agregar" class="btn btn-primary">
		
	</center>
</form>