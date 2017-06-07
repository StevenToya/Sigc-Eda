<?php
/*
if($PERMISOS_GC["usu_cre"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}
*/

function tipo_archivo($archivo)
{
	if($archivo && file_exists($archivo))
	{
		
		$alea = rand(1,1000);
		$imagen = '';
		$trozos = explode(".", $archivo); 
		$extension = end($trozos);
		
		if($extension=='gif' || $extension=='GIF' || $extension=='jpg' || $extension=='JPG' || $extension=='jpeg' || $extension=='JPEG' || $extension=='png' || $extension=='PNG')
		{$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="libreria/phpThumb/phpThumb.php?src=/'.$archivo.'&amp;h=150&amp;w=150" ></a>';}
		if($extension=='doc' || $extension=='DOC'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/doc.jpg" width="100px"></a>';}
		if($extension=='docx' || $extension=='DOCX'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/doc.jpg" width="100px"></a>';}
		if($extension=='pdf' || $extension=='PDF'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/pdf.jpg" width="100px"></a>';}
		if($extension=='zip' || $extension=='ZIP'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/zip.jpg" width="100px"></a>';}
		if($extension=='rar' || $extension=='RAR'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/rar.jpg" width="100px"></a>';}
		if($extension=='xls' || $extension=='XLS'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/xls.jpg" width="100px"></a>';}
		if($extension=='xlsx'  ||  $extension=='XLSX'  ||  $extension=='csv' || $extension=='CSV'){$imagen = '<a href="'.$archivo.'"  target="_blank"><img src="img/xls.jpg" width="100px"></a>';}
		if($extension=='ppt' || $extension=='PPT'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/ppt.jpg" width="100px"></a>';}
		if($extension=='pptx' || $extension=='PPTX'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/ppt.jpg" width="100px"></a>';}
		if($extension=='bmp' || $extension=='BMP'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/bmp.jpg" width="100px"></a>';}
		if($extension=='avi' || $extension=='AVI'){$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/avi.jpg" width="100px"></a>';}

		if(!$imagen)
		{$imagen = '<a href="'.$archivo.'?alea='.$alea.'"  target="_blank"><img src="img/inusual_archivo.png" width="100px"></a>';}
	}
	else
	{
		$imagen = '<img src="img/sin_archivo.png" width="100px">';
	}
	
	return $imagen ;
	
}



$sql = "SELECT hv_persona.nombre, hv_persona.apellido, hv_persona.identificacion, hv_persona.correo, municipio.nombre AS nom_municipio,
		hv_persona.telefono, hv_persona.foto, 	cargo.nombre AS nom_cargo, hv_persona.direccion, hv_persona.estado, hv_persona.motivo_rechazo
FROM hv_persona 
INNER JOIN municipio ON hv_persona.id_municipio = municipio.id
INNER JOIN cargo ON hv_persona.id_cargo = cargo.id
WHERE hv_persona.id = '".$_GET["id"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);


?>

<h2>DETALLE FRENTE</h2>

<div align=right>
	<a href="?cmp=lista"> <i class="fa fa-reply fa-2x"></i> Volvel al listado</a>
</div>
<br>

<?php
	$sql = "SELECT frente_trabajo.nombre_1, frente_trabajo.nombre_2, municipio.nombre, frente_trabajo.id, archivo_1, archivo_2, archivo_3, archivo_4, tecnologia
	FROM frente_trabajo 	
	INNER JOIN municipio ON frente_trabajo.id_municipio = municipio.id
	WHERE frente_trabajo.id_instancia='".$_SESSION["nst"]."' AND frente_trabajo.id = '".$_GET["id"]."' LIMIT 1";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);

?>
<table width=70% align=center>
	<tr>
		<td>
			<div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						DATOS BASICOS
					</div>
					<div class="panel-body" align=center>
						<div class="form-group input-group input-group-lg" >
						  <span class="input-group-addon" ><div align=left>Nombre Oficial</div></span>
						  <input  size=40   readonly value="<?php echo $row["nombre_1"] ?>" type="text" class="form-control" placeholder="Primer tecnico" required />
						</div> 

						<div class="form-group input-group input-group-lg" >
						  <span class="input-group-addon" ><div align=left>Nombre Auxiliar</div></span>
						  <input size=40   readonly value="<?php echo $row["nombre_2"] ?>" type="text" class="form-control" placeholder="Primer tecnico" required />
						</div> 
						
						<div class="form-group input-group input-group-lg" >
						  <span class="input-group-addon" ><div align=left>Localidad</div></span>
						  <input size=40  readonly value="<?php echo utf8_encode($row["nombre"]); ?>" type="text" class="form-control" placeholder="Primer tecnico" required />
						</div> 
						
						<div class="form-group input-group input-group-lg" >
						  <span class="input-group-addon" ><div align=left>Tecnologia</div></span>
						  <input size=40   readonly value="<?php echo $row["tecnologia"] ?>" type="text" class="form-control"  required />
						</div> 
					</div>
					
					
				</div>
			</div>
		</td>
	</tr>
	
</table>

<table width=70% align=center>
	<tr>
		<td width="45%">		
			<div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						FOTO DEL FRENTE
					</div>
					<div class="panel-body" align=center>
						<?php echo tipo_archivo($row["archivo_1"]); ?>
						</div>					
				</div>
			</div>		
		</td>
		<td width="10%"></td>
		<td width="45%">
			<div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						FOTO DEL TRANSPORTE
					</div>
					<div class="panel-body" align=center>
						<?php echo tipo_archivo($row["archivo_2"]); ?>
					</div>					
				</div>
			</div>	
		</td>
	</tr>
	<tr>
		<td colspan=3><br></td>
	</tr>
	<tr>
		<td>		
			<div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						FOTO HERRAMIENTAS
					</div>
					<div class="panel-body" align=center>
						<?php echo tipo_archivo($row["archivo_3"]); ?>
					</div>					
				</div>
			</div>		
		</td>
		<td width="10%"></td>
		<td>
			<div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						FOTO EPP
					</div>
					<div class="panel-body" align=center>
						<?php echo tipo_archivo($row["archivo_4"]); ?>
					</div>					
				</div>
			</div>	
		</td>
	</tr>
	</table>

