<?php

if($PERMISOS_GC["sop_pag"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


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

if($_POST["guardar"])
{
		
	$carpeta = "documentos/soporte_pago/".$_GET["id"];
	@mkdir($carpeta, 0777);
	chmod($carpeta, 0777);
				
	if($_FILES['archivo_1']['name'])
	{				
		$trozos = explode(".", $_FILES['archivo_1']['name']); 
		$extension = end($trozos);	
		
		$ruta = $carpeta."/1.".$extension;			
		move_uploaded_file($_FILES['archivo_1']['tmp_name'], $ruta);
		if(file_exists($ruta))
		{
			 $sql = "UPDATE soporte_pago SET archivo_1 = '".$ruta."'  WHERE id ='".$_GET["id"]."' LIMIT 1 ;";
			 mysql_query($sql);						 
		}
		else
		{
			$error = $error.'Error al cargar la Revisoria Fiscal \n';
		}					
	}
	
	if($_FILES['archivo_2']['name'])
	{				
		$trozos = explode(".", $_FILES['archivo_2']['name']); 
		$extension = end($trozos);	
		
		$ruta = $carpeta."/2.".$extension;			
		move_uploaded_file($_FILES['archivo_2']['tmp_name'], $ruta);
		if(file_exists($ruta))
		{
			 $sql = "UPDATE soporte_pago SET archivo_2 = '".$ruta."'  WHERE id ='".$_GET["id"]."' LIMIT 1 ;";
			 mysql_query($sql);						 
		}
		else
		{
			$error = $error.'Error al cargar el pago aportes previsionales \n';
		}					
	}
	
	if($_FILES['archivo_3']['name'])
	{				
		$trozos = explode(".", $_FILES['archivo_3']['name']); 
		$extension = end($trozos);	
		
		$ruta = $carpeta."/3.".$extension;			
		move_uploaded_file($_FILES['archivo_3']['tmp_name'], $ruta);
		if(file_exists($ruta))
		{
			 $sql = "UPDATE soporte_pago SET archivo_3 = '".$ruta."'  WHERE id ='".$_GET["id"]."' LIMIT 1 ;";
			 mysql_query($sql);						 
		}
		else
		{
			$error = $error.'Error al cargar el Pago Nomina \n';
		}					
	}
	
	if($_FILES['archivo_4']['name'])
	{				
		$trozos = explode(".", $_FILES['archivo_4']['name']); 
		$extension = end($trozos);	
		
		$ruta = $carpeta."/4.".$extension;			
		move_uploaded_file($_FILES['archivo_4']['tmp_name'], $ruta);
		if(file_exists($ruta))
		{
			 $sql = "UPDATE soporte_pago SET archivo_4 = '".$ruta."'  WHERE id ='".$_GET["id"]."' LIMIT 1 ;";
			 mysql_query($sql);						 
		}
		else
		{
			$error = $error.'Error al cargar el Acta \n';
		}					
	}
	
	if($_FILES['archivo_5']['name'])
	{				
		$trozos = explode(".", $_FILES['archivo_5']['name']); 
		$extension = end($trozos);	
		
		$ruta = $carpeta."/5.".$extension;			
		move_uploaded_file($_FILES['archivo_5']['tmp_name'], $ruta);
		if(file_exists($ruta))
		{
			 $sql = "UPDATE soporte_pago SET archivo_5 = '".$ruta."'  WHERE id ='".$_GET["id"]."' LIMIT 1 ;";
			 mysql_query($sql);						 
		}
		else
		{
			$error = $error.'Error al cargar la Factura \n';
		}					
	}
	
	
		
	if($error)
	{
				 echo '<script> alert("'.$error.'");</script>';
	}else{
		 echo '<script> alert("Periodo actualizado correctamente");</script>';
	}
	
	
}

$sql = "SELECT * FROM soporte_pago WHERE id = '".$_GET["id"]."' LIMIT 1";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);


?>

<h2>DOCUMENTACION DEL CONTRATO PARA EL PERIODO <b><?php echo $row["ano_mes"] ?> </b></h2>

<div align=right>
	<a href="?cmp=panel_inicial"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de periodos</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<br>

<form action="?ingresar=1&id=<?php echo $_GET["id"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">

<table width=90% align=center>
	<tr>
		<td width="30%" valign=top>		
			<div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Revisoria Fiscal
					</div>
					<div class="panel-body" align=center>
						<?php echo tipo_archivo($row["archivo_1"]); ?><br>
						<input name="archivo_1" id="archivo_1"  type="file" class="form-control"  />
					</div>					
				</div>
			</div>		
		</td>
		<td width="5%"></td>
		<td width="30%"  valign=top>
			<div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Pago Parafiscales
					</div>
					<div class="panel-body" align=center>
						<?php echo tipo_archivo($row["archivo_2"]); ?>
						<input name="archivo_2" id="archivo_2"  type="file" class="form-control"  />
					</div>					
				</div>
			</div>	
		</td>
		<td width="5%"></td>
		<td width="30%"  valign=top>
			<div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Pago Nomina
					</div>
					<div class="panel-body" align=center>
						<?php echo tipo_archivo($row["archivo_3"]); ?>
						<input name="archivo_3" id="archivo_3"  type="file" class="form-control"  />
					</div>					
				</div>
			</div>	
		</td>
	</tr>
	<tr>
		<td colspan=5><br></td>
	</tr>
	<tr>
		<td width="30%" valign=top>		
			<div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Acta
					</div>
					<div class="panel-body" align=center>
						<?php echo tipo_archivo($row["archivo_4"]); ?><br>
						<input name="archivo_4" id="archivo_4"  type="file" class="form-control"  />
					</div>					
				</div>
			</div>		
		</td>
		<td width="5%"></td>
		<td width="30%"  valign=top>
			<div class="col-md-100 col-sm-100">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Factura
					</div>
					<div class="panel-body" align=center>
						<?php echo tipo_archivo($row["archivo_5"]); ?>
						<input name="archivo_5" id="archivo_5"  type="file" class="form-control"  />
					</div>					
				</div>
			</div>	
		</td>
		<td width="5%"></td>
		<td width="30%"  valign=top>
			
		</td>
	</tr>
	</table>
<br>
<center>
	<input type="submit" name="guardar" class="btn btn-primary" value="Actualizar periodo">
</center>

</form>	