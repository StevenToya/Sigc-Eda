<?php
if($PERMISOS_GC["une_dth_ges"]!='Si')
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



$sql = "SELECT * 
FROM  une_dth 	
INNER JOIN municipio ON une_dth.id_municipio = municipio.id
WHERE une_dth.id = '".$_GET["id"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);


?>

<h2>INSTALACIONES DTH </h2>

<div align=right>
	<a href="?cmp=lista_tugestion"> <i class="fa fa-reply fa-2x"></i> Volver a tus instalaciones</a>
</div>

	
	<?php if($error){?>
					<div class="alert alert-info">
						<?php echo $error; ?>
					</div>
				<?php } ?>
	
 <div class="col-md-100 col-sm-100">
	<div class="panel panel-primary">
		<div class="panel-heading">
			DATOS BASICOS DEL CLIENTE
		</div>
		<div class="panel-body" align=center>	
	
			<table align="center">
				<tr>
					 <td valign=top  width='50%'>
					
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Nombre Cliente</span>
						  <input  name="cliente_nombre" readonly id="cliente_nombre" value="<?php echo $row["cliente_nombre"] ?>" type="text"  class="form-control" placeholder="Nombre del cliente" required />
						</div>  
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Ident. Cliente</span>
						  <input name="cliente_identificacion" readonly id="cliente_identificacion" value="<?php echo $row["cliente_identificacion"] ?>"  type="text" class="form-control" placeholder="Identificacion del cliente" required />
						</div> 
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Direccion</span>
						  <input  readonly value="<?php echo $row["cliente_direccion"] ?>" id="cliente_direccion" type="text" class="form-control" placeholder="Identificacion del usuario" required />
						</div> 
						
						<?php $coordenada_n = explode('-',$row["coordenada_n"]); ?>
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Coordenadas<br> - N -</span>
						  <input readonly name="coordenada_n_g" value="<?php echo $coordenada_n[0] ?>" id="coordenada_n_g" type="text" class="form-control" placeholder="Grados" required />
						  <input readonly name="coordenada_n_m" value="<?php echo $coordenada_n[1] ?>" id="coordenada_n_m" type="text" class="form-control" placeholder="Minutos" required />
						  <input readonly name="coordenada_n_s" value="<?php echo $coordenada_n[2] ?>" id="coordenada_n_s" type="text" class="form-control" placeholder="Segundos" required />
						</div> 
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Atura</span>
						  <input readonly name="altura" value="<?php echo $row["altura"] ?>" id="altura" type="text" class="form-control" placeholder="Altura de la coordenada" required />
						 </div> 				  
					 
					</td>
					
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					
					<td valign=top width='50%'>
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Pedido</span>
						  <input readonly id="pedido" value="<?php echo $row["pedido"] ?>" type="text" class="form-control" placeholder="Numero del pedido"  />
						</div> 
						
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Zona</span>
						  <input readonly id="zona" value="<?php echo $row["zona"] ?>"  class="form-control" placeholder="Zona del tramite" />
						</div> 											
												
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Municipio</span>
							  <input readonly id="zona" value="<?php echo utf8_encode($row["nombre"]) ?>"  class="form-control" placeholder="Zona del tramite" />
						</div>	
						
						<?php $coordenada_w = explode('-',$row["coordenada_w"]); ?>
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Coordenadas<br> - W -</span>
						  <input  readonly name="coordenada_w_g" value="<?php echo $coordenada_w[0] ?>" id="coordenada_w_g" type="text" class="form-control" placeholder="Grados" required />
						  <input readonly name="coordenada_w_m" value="<?php echo $coordenada_w[1] ?>" id="coordenada_w_m" type="text" class="form-control" placeholder="Minutos" required />
						  <input readonly name="coordenada_w_s" value="<?php echo $coordenada_w[2] ?>" id="coordenada_w_s" type="text" class="form-control" placeholder="Segundos" required />
						</div> 	
						
					</td>
				</tr>					
			</table>
	
	
		</div>
					
	</div>
</div>


 <div class="col-md-100 col-sm-100">
	<div class="panel panel-primary">
		<div class="panel-heading">
			EQUIPOS INSTALADOS
		</div>
		<div class="panel-body" align=center>	
	
			<table align="center">
				<tr>
					<td valign=top  width='50%'>
					
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">DECO 1</span>
						  <input readonly  name="deco_1" style="width:100%" id="deco_1" value="<?php echo $row["deco_1"] ?>" type="text" class="form-control" placeholder="Deco instalado" required />
						</div>  
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">DECO 2</span>
						  <input readonly name="deco_2" style="width:100%" id="deco_2" value="<?php echo $row["deco_2"] ?>"  type="text" class="form-control" placeholder="Segundo deco instalado"  />
						</div> 			  
					 
					</td>
					
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					
					<td valign=top width='50%'>
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Smart card 1</span>
						  <input readonly name="smart_card_1" id="smart_card_1" value="<?php echo $row["smart_card_1"] ?>" type="text" class="form-control" placeholder="Smart card instalado" required />
						</div> 
						
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Smart card 2</span>
						  <input readonly name="smart_card_2" id="smart_card_2" value="<?php echo $row["smart_card_2"] ?>" class="form-control" placeholder="Segundo smart card Instalado" />
						</div> 	
						
					</td>
				</tr>							
			</table>	
		</div>
					
	</div>
</div>

 <div class="col-md-100 col-sm-100">
	<div class="panel panel-primary">
		<div class="panel-heading">
			MATERIALES GASTADOS
		</div>
		<div class="panel-body" align=center>	
	
			<table align="center">
				<tr>
					<td valign=top  width='50%'>
					
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Cable RG-6</span>
						  <input  readonly name="cable_rg6" style="width:100%" id="cable_rg6" value="<?php echo $row["cable_rg6"] ?>" type="number"  class="form-control" placeholder="Cantidad de cable RG-6 gastada" required />
						</div>  
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Conector RG-6</span>
						  <input readonly name="correa_rg6" style="width:100%" id="correa_rg6" value="<?php echo $row["correa_rg6"] ?>"  type="number" class="form-control" placeholder="Cantidad de correa RG-6 gastada" required />
						</div> 		

						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Conector carga Terminal</span>
						  <input readonly value="<?php echo $row["conector_carga"] ?>"  type="number" class="form-control" placeholder="Cantidad de conectores" required />
						</div>

						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Antena (Plato)</span>
						  <input readonly value="<?php echo $row["plato"] ?>"  type="number" class="form-control" placeholder="Cantidad de antenas" required />
						</div>
					 
					</td>
					
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					
					<td valign=top width='50%'>
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Correa Plastica</span>
						  <input readonly name="correa_plastica" id="correa_plastica" value="<?php echo $row["correa_plastica"] ?>" type="number" class="form-control" placeholder="Cantidad de correa plastica gastada" required />
						</div> 
						
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Grapas</span>
						  <input readonly name="grapa" id="grapa" value="<?php echo $row["grapa"] ?>"  type="number" class="form-control" placeholder="Cantidad de grapas gastada" required />
						</div> 	
						
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">LNB</span>
						  <input readonly name="lnb" id="lnb" value="<?php echo $row["lnb"] ?>"  type="number" class="form-control" placeholder="Cantidad de LNB" required />
						</div> 
						
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Conector security</span>
						  <input readonly name="conector_security" id="conector_security" value="<?php echo $row["conector_security"] ?>"  type="number" class="form-control" placeholder="Cantidad de conectores security" required />
						</div> 
						
					</td>
				</tr>							
			</table>	
		</div>
					
	</div>
</div>


<div class="col-md-100 col-sm-100">
	<div class="panel panel-primary">
		<div class="panel-heading">
			DURACION DE LA GESTION DE LA INSTALACION
		</div>
		<div class="panel-body" align=center>	
			
			<?php
			$tiempo_transporte = explode('-',$row["tiempo_transporte"]);
			$tiempo_antena = explode('-',$row["tiempo_antena"]);
			$tiempo_cableado = explode('-',$row["tiempo_cableado"]);
			$tiempo_aprovisionamiento = explode('-',$row["tiempo_aprovisionamiento"]);
			
			?>
			
			<table align="center">
				<tr>
					<td valign=top  width='25%'>					
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Tiempo <br> Transporte</span>
							<input readonly name="tiempo_transporte_d" id="tiempo_transporte_d" value="<?php echo $tiempo_transporte[0] ?>" type="number"  class="form-control" placeholder="Dias" required />
							<input readonly name="tiempo_transporte_h" id="tiempo_transporte_h" value="<?php echo $tiempo_transporte[1] ?>" type="number"  class="form-control" placeholder="Horas" required />
							<input readonly  name="tiempo_transporte_m" id="tiempo_transporte_m" value="<?php echo $tiempo_transporte[2] ?>" type="number"  class="form-control" placeholder="Minutos" required />
						</div>  					
					</td>
					<td valign=top  width='25%'>					
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Tiempo <br> Instalar <br> Antena</span>
							<input readonly  name="tiempo_antena_d" id="tiempo_antena_d" value="<?php echo $tiempo_antena[0] ?>" type="number"  class="form-control" placeholder="Dias" required />
							<input readonly  name="tiempo_antena_h" id="tiempo_antena_h" value="<?php echo $tiempo_antena[1] ?>" type="number"  class="form-control" placeholder="Horas" required />
							<input readonly  name="tiempo_antena_m" id="tiempo_antena_m" value="<?php echo $tiempo_antena[2] ?>" type="number"  class="form-control" placeholder="Minutos" required />
						</div>  					
					</td>
					<td valign=top  width='25%'>					
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Tiempo <br> Instalar <br> Cableado</span>
							<input readonly name="tiempo_cableado_d" id="tiempo_cableado_d" value="<?php echo $tiempo_cableado[0] ?>" type="number"  class="form-control" placeholder="Dias" required />
							<input readonly name="tiempo_cableado_h" id="tiempo_cableado_h" value="<?php echo $tiempo_cableado[1] ?>" type="number"  class="form-control" placeholder="Horas" required />
							<input readonly name="tiempo_cableado_m" id="tiempo_cableado_m" value="<?php echo $tiempo_cableado[2] ?>" type="number"  class="form-control" placeholder="Minutos" required />
						</div>  					
					</td>
					<td valign=top  width='25%'>					
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Tiempo <br> Aprovisio.</span>
							<input readonly name="tiempo_aprovisionamiento_d" id="tiempo_aprovisionamiento_d" value="<?php echo $tiempo_aprovisionamiento[0] ?>" type="number"  class="form-control" placeholder="Dias" required />
							<input readonly  name="tiempo_aprovisionamiento_h" id="tiempo_aprovisionamiento_h" value="<?php echo $tiempo_aprovisionamiento[1] ?>" type="number"  class="form-control" placeholder="Horas" required />
							<input readonly name="tiempo_aprovisionamiento_m" id="tiempo_aprovisionamiento_m" value="<?php echo $tiempo_aprovisionamiento[2] ?>" type="number"  class="form-control" placeholder="Minutos" required />
						</div>  					
					</td>
					
					
				</tr>							
			</table>	
		</div>
					
	</div>
</div>

<div class="col-md-100 col-sm-100">
	<div class="panel panel-primary">
		<div class="panel-heading">
			REGISTRO FOTOGRAFICO
		</div>
		<div class="panel-body" align=center>	
			<?php $tmp = rand(1,100); ?>
			<table align="center" width=100%>
				<tr>
					<td valign=top  width='33%' align=center>
						<h5><b>Antena</b></h5>
						<?php echo tipo_archivo($row["archivo_1"]); ?>
										 
					</td>
					
					<td valign=top  width='33%' align=center>
						<h5><b>Equipo</b></h5>
						<?php echo tipo_archivo($row["archivo_2"]); ?>
									 
					</td>
					
					<td valign=top  width='33%' align=center>
						<h5><b>Cableado</b></h5>
						<?php echo tipo_archivo($row["archivo_3"]); ?>
								 
					</td>
					
					
				</tr>							
			</table>

			<table align="center" width=100%>
				<tr>
									
					
					<td valign=top  width='50%' align=center>
						<h5><b>Predio</b></h5>
						<?php echo tipo_archivo($row["archivo_4"]); ?>
										 
					</td>
					
					<td valign=top  width='50%' align=center>
						<h5><b>Coordenada</b></h5>
						<?php echo tipo_archivo($row["archivo_5"]); ?>
								 
					</td>
					
				</tr>							
			</table>
		</div>
					
	</div>
</div>

		
