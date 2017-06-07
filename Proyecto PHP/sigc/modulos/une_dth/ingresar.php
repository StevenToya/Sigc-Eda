<?php
if($PERMISOS_GC["une_dth_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


if($_GET["ingresar"])
{
	
	$pedido = limpiar_numero($_POST["pedido"]);
	$cliente_identificacion = limpiar(strtoupper($_POST["cliente_identificacion"]));
	$cliente_nombre = limpiar(strtoupper($_POST["cliente_nombre"]));
	$cliente_direccion = limpiar(strtoupper($_POST["cliente_direccion"]));
	$zona = limpiar(strtoupper($_POST["zona"]));
	$cable_rg6 = limpiar_numero($_POST["cable_rg6"]);
	$correa_rg6 = limpiar_numero($_POST["correa_rg6"]);
	$correa_plastica = limpiar_numero($_POST["correa_plastica"]);
	$grapa = limpiar_numero($_POST["grapa"]);
	$deco_1 = limpiar(strtoupper($_POST["deco_1"]));
	$deco_2 = limpiar(strtoupper($_POST["deco_2"]));
	$smart_card_1 = limpiar(strtoupper($_POST["smart_card_1"]));
	$smart_card_2 = limpiar(strtoupper($_POST["smart_card_2"]));
	
	
	$altura = limpiar($_POST["altura"]);
	$plato = limpiar_numero($_POST["plato"]);
	$lnb = limpiar_numero($_POST["lnb"]);
	$conector_security = limpiar_numero($_POST["conector_security"]);
	$conector_carga = limpiar_numero($_POST["conector_carga"]);

	
	if(!$_POST["tiempo_transporte_d"]){$tiempo_transporte_d = '0';}else{$tiempo_transporte_d = $_POST["tiempo_transporte_d"];}
	if(!$_POST["tiempo_transporte_h"]){$tiempo_transporte_h = '0';}else{$tiempo_transporte_h = $_POST["tiempo_transporte_h"];}
	if(!$_POST["tiempo_transporte_m"]){$tiempo_transporte_m = '0';}else{$tiempo_transporte_m = $_POST["tiempo_transporte_m"];}
	$tiempo_transporte = $tiempo_transporte_d.'-'.$tiempo_transporte_h.'-'.$tiempo_transporte_m;
	
	if(!$_POST["tiempo_antena_d"]){$tiempo_antena_d = '0';}else{$tiempo_antena_d = $_POST["tiempo_antena_d"];}
	if(!$_POST["tiempo_antena_h"]){$tiempo_antena_h = '0';}else{$tiempo_antena_h = $_POST["tiempo_antena_h"];}
	if(!$_POST["tiempo_antena_m"]){$tiempo_antena_m = '0';}else{$tiempo_antena_m = $_POST["tiempo_antena_m"];}
	$tiempo_antena = $tiempo_antena_d.'-'.$tiempo_antena_h.'-'.$tiempo_antena_m;
	
	
	if(!$_POST["tiempo_cableado_d"]){$tiempo_cableado_d = '0';}else{$tiempo_cableado_d = $_POST["tiempo_cableado_d"];}
	if(!$_POST["tiempo_cableado_h"]){$tiempo_cableado_h = '0';}else{$tiempo_cableado_h = $_POST["tiempo_cableado_h"];}
	if(!$_POST["tiempo_cableado_m"]){$tiempo_cableado_m = '0';}else{$tiempo_cableado_m = $_POST["tiempo_cableado_m"];}
	$tiempo_cableado = $tiempo_cableado_d.'-'.$tiempo_cableado_h.'-'.$tiempo_cableado_m;
	
	
	if(!$_POST["tiempo_aprovisionamiento_d"]){$tiempo_aprovisionamiento_d = '0';}else{$tiempo_aprovisionamiento_d = $_POST["tiempo_aprovisionamiento_d"];}
	if(!$_POST["tiempo_aprovisionamiento_h"]){$tiempo_aprovisionamiento_h = '0';}else{$tiempo_aprovisionamiento_h = $_POST["tiempo_aprovisionamiento_h"];}
	if(!$_POST["tiempo_aprovisionamiento_m"]){$tiempo_aprovisionamiento_m = '0';}else{$tiempo_aprovisionamiento_m = $_POST["tiempo_aprovisionamiento_m"];}
	$tiempo_aprovisionamiento = $tiempo_aprovisionamiento_d.'-'.$tiempo_aprovisionamiento_h.'-'.$tiempo_aprovisionamiento_m;
	
	
	$coordenada_n = $_POST["coordenada_n_g"].'-'.$_POST["coordenada_n_m"].'-'.$_POST["coordenada_n_s"];
	$coordenada_w = $_POST["coordenada_w_g"].'-'.$_POST["coordenada_w_m"].'-'.$_POST["coordenada_w_s"];
	
	$error = '';
	$sql = "INSERT INTO `une_dth` 
	(`pedido`, `cliente_identificacion`, `cliente_nombre`, `cliente_direccion`, `zona`, 
	`id_municipio`, `cable_rg6`, `correa_rg6`, `correa_plastica`, `grapa`, 
	`deco_1`, `deco_2`, `smart_card_1`, `smart_card_2`,  `tiempo_transporte`, 
	`tiempo_antena`, `tiempo_cableado`, `tiempo_aprovisionamiento`, `id_usuario`, `fecha_registro`,
	`coordenada_n`,`coordenada_w`,`altura`,`conector_carga`,`plato`,
	`lnb`,`conector_security`) 
	VALUES 
	('".$pedido."', '".$cliente_identificacion."', '".$cliente_nombre."', '".$cliente_direccion."', '".$zona."', 
	'".$_POST["municipio"]."', '".$cable_rg6."', '".$correa_rg6."', '".$correa_plastica."', '".$grapa."', 
	'".$deco_1."', '".$deco_2."', '".$smart_card_1."', '".$smart_card_2."', '".$tiempo_transporte."', 
	'".$tiempo_antena."', '".$tiempo_cableado."', '".$tiempo_aprovisionamiento."', '".$_SESSION["user_id"]."', '".date("Y-m-d G:i:s")."',
	'".$coordenada_n."', '".$coordenada_w."', '".$altura."', '".$conector_carga."', '".$plato."', 
	'".$lnb."', '".$conector_security."');";
	mysql_query($sql);
	$id_dth = mysql_insert_id();
	if($id_dth)
	{
		$carpeta = "documentos/une_dth/".$id_dth;
		@mkdir($carpeta, 0777);
		chmod($carpeta, 0777);
		
		
		if($_FILES['archivo_1']['name'])
		{				
			$trozos = explode(".", $_FILES['archivo_1']['name']); 
			$extension = end($trozos);	
			if($extension=='JPG' || $extension=='jpg' || $extension=='JPEG' || $extension=='jpeg' || $extension=='GIF' || $extension=='gif' || $extension=='PNG' || $extension=='png')
			{
				$ruta = $carpeta."/antena.".$extension;			
				move_uploaded_file($_FILES['archivo_1']['tmp_name'], $ruta);
				if(file_exists($ruta))
				{
					 $sql = "UPDATE une_dth SET archivo_1 = '".$ruta."'  WHERE id ='".$id_dth."' LIMIT 1 ;";
					 mysql_query($sql);	
					 if(mysql_error())
					 {
						  $error = $error." Error al guardar la  foto de la antena en la base de datos <br>";	
					 }
								 
				}
				else
				{
					 $error = $error."Error al cargar la foto de la antena<br>";	
				}
			}
			else
			{
					$error = $error."La foto de la antena debe ser una imagen<br>";
			}				
		}
		else
		{
				$error = $error."Debe ingresar la foto de la antena<br>";
		}
		
		
		if($_FILES['archivo_2']['name'])
		{				
			$trozos = explode(".", $_FILES['archivo_2']['name']); 
			$extension = end($trozos);	
			if($extension=='JPG' || $extension=='jpg' || $extension=='JPEG' || $extension=='jpeg' || $extension=='GIF' || $extension=='gif' || $extension=='PNG' || $extension=='png')
			{
				$ruta = $carpeta."/equipos.".$extension;			
				move_uploaded_file($_FILES['archivo_2']['tmp_name'], $ruta);
				if(file_exists($ruta))
				{
					 $sql = "UPDATE une_dth SET archivo_2 = '".$ruta."'  WHERE id ='".$id_dth."' LIMIT 1 ;";
					 mysql_query($sql);	
					 if(mysql_error())
					 {
						  $error = $error." Error al guardar la  foto de los equipos en la base de datos<br>";	
					 }
								 
				}
				else
				{
					 $error = $error."Error al cargar la foto de los equipos<br>";	
				}
			}
			else
			{
					$error = $error."La foto de los equipos debe ser una imagen<br>";
			}				
		}
		else
		{
				$error = $error."Debe ingresar la foto de los equipos<br>";
		}
		
		
		
		if($_FILES['archivo_3']['name'])
		{				
			$trozos = explode(".", $_FILES['archivo_3']['name']); 
			$extension = end($trozos);	
			if($extension=='JPG' || $extension=='jpg' || $extension=='JPEG' || $extension=='jpeg' || $extension=='GIF' || $extension=='gif' || $extension=='PNG' || $extension=='png')
			{
				$ruta = $carpeta."/cableado.".$extension;			
				move_uploaded_file($_FILES['archivo_3']['tmp_name'], $ruta);
				if(file_exists($ruta))
				{
					 $sql = "UPDATE une_dth SET archivo_3 = '".$ruta."'  WHERE id ='".$id_dth."' LIMIT 1 ;";
					 mysql_query($sql);	
					 if(mysql_error())
					 {
						  $error = $error." Error al guardar la  foto  del cableado en la base de datos<br>";	
					 }
								 
				}
				else
				{
					 $error = $error."Error al cargar la foto  del cableado <br>";	
				}
			}
			else
			{
					$error = $error."La foto  del cableado  debe ser una imagen<br>";
			}				
		}
		else
		{
				$error = $error."Debe ingresar la foto  del cableado <br>";
		}
		
		if($_FILES['archivo_4']['name'])
		{				
			$trozos = explode(".", $_FILES['archivo_4']['name']); 
			$extension = end($trozos);	
			if($extension=='JPG' || $extension=='jpg' || $extension=='JPEG' || $extension=='jpeg' || $extension=='GIF' || $extension=='gif' || $extension=='PNG' || $extension=='png')
			{
				$ruta = $carpeta."/predio.".$extension;			
				move_uploaded_file($_FILES['archivo_4']['tmp_name'], $ruta);
				if(file_exists($ruta))
				{
					 $sql = "UPDATE une_dth SET archivo_4 = '".$ruta."'  WHERE id ='".$id_dth."' LIMIT 1 ;";
					 mysql_query($sql);	
					 if(mysql_error())
					 {
						  $error = $error." Error al guardar la  foto  del predio en la base de datos<br>";	
					 }
								 
				}
				else
				{
					 $error = $error."Error al cargar la foto  del predio <br>";	
				}
			}
			else
			{
					$error = $error."La foto  del predio  debe ser una imagen<br>";
			}				
		}
		else
		{
				$error = $error."Debe ingresar la foto  del predio <br>";
		}
		
		
		if($_FILES['archivo_5']['name'])
		{				
			$trozos = explode(".", $_FILES['archivo_5']['name']); 
			$extension = end($trozos);	
			if($extension=='JPG' || $extension=='jpg' || $extension=='JPEG' || $extension=='jpeg' || $extension=='GIF' || $extension=='gif' || $extension=='PNG' || $extension=='png')
			{
				$ruta = $carpeta."/coordenada.".$extension;			
				move_uploaded_file($_FILES['archivo_5']['tmp_name'], $ruta);
				if(file_exists($ruta))
				{
					 $sql = "UPDATE une_dth SET archivo_5 = '".$ruta."'  WHERE id ='".$id_dth."' LIMIT 1 ;";
					 mysql_query($sql);	
					 if(mysql_error())
					 {
						  $error = $error." Error al guardar la  foto  de las coordenadas en la base de datos<br>";	
					 }
								 
				}
				else
				{
					 $error = $error."Error al cargar la foto de las coordenadas <br>";	
				}
			}
			else
			{
					$error = $error."La foto  de las coordenadas debe ser una imagen<br>";
			}				
		}
		else
		{
				$error = $error."Debe ingresar la foto  las coordenadas <br>";
		}
		
		
		
		
		
	}
	else
	{
		$error = $error."No se pudo ingresar el registro en base de datos";		
	}
	
	
	if($error)
	{
		 $sql_del = "DELETE FROM  `une_dth` WHERE id = '".$id_dth."' LIMIT 1";
		 mysql_query($sql_del);
	}
	else
	{
		echo '<script >alert("Informacion ingresada correctamente!");</script>';
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_tugestion'>";
		die();
	}
		
	
}

?>

<h2>INGRESAR INSTALACIONES DTH </h2>

<div align=right>
	<a href="?cmp=lista_tugestion"> <i class="fa fa-reply fa-2x"></i> Volver a tus instalaciones</a>
</div>
<form action="?ingresar=1"  method="post" name="form" id="form"  enctype="multipart/form-data">
	
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
						  <input  name="cliente_nombre" style="width:100%" id="cliente_nombre" value="<?php echo $_POST["cliente_nombre"] ?>" type="text"  class="form-control" placeholder="Nombre del cliente" required />
						</div>  
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Ident. Cliente</span>
						  <input name="cliente_identificacion" style="width:100%" id="cliente_identificacion" value="<?php echo $_POST["cliente_identificacion"] ?>"  type="text" class="form-control" placeholder="Identificacion del cliente" required />
						</div> 
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Direccion</span>
						  <input  name="cliente_direccion" value="<?php echo $_POST["cliente_direccion"] ?>" id="cliente_direccion" type="text" class="form-control" placeholder="Direccion del cliente" required />
						</div> 
				
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Departamento</span>
							 <select  class="form-control" name="departamento" id="departamento">
								<option value="">Seleccione un departamento</option>
								<?php
								$sql_dep = "SELECT * FROM departamento ORDER BY nombre";
								$res_dep = mysql_query($sql_dep);
								while($row_dep = mysql_fetch_array($res_dep))
								{
								?>
									<option value="<?php echo $row_dep["id"] ?>" ><?php echo utf8_encode($row_dep["nombre"]) ?></option>
								<?php
								}
								?>										
							 </select>
						</div> 
					  
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Coordenadas<br> - N -</span>
						  <input  name="coordenada_n_g" value="<?php echo $_POST["coordenada_n_g"] ?>" id="coordenada_n_g" type="text" class="form-control" placeholder="Grados" required />
						  <input  name="coordenada_n_m" value="<?php echo $_POST["coordenada_n_m"] ?>" id="coordenada_n_m" type="text" class="form-control" placeholder="Minutos" required />
						  <input  name="coordenada_n_s" value="<?php echo $_POST["coordenada_n_s"] ?>" id="coordenada_n_s" type="text" class="form-control" placeholder="Segundos" required />
						</div> 
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Atura</span>
						  <input  name="altura" value="<?php echo $_POST["altura"] ?>" id="altura" type="text" class="form-control" placeholder="Altura de la coordenada" required />
						 </div> 
					  
					 
					</td>
					
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					
					<td valign=top width='50%'>
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Pedido</span>
						  <input name="pedido" id="pedido" value="<?php echo $_POST["pedido"] ?>" type="text" class="form-control" placeholder="Numero del pedido" required />
						</div> 
						
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Zona</span>
						  <input name="zona" id="zona" value="<?php echo $_POST["zona"] ?>" required class="form-control" placeholder="Zona del tramite" />
						</div> 	
						
												
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Telefono</span>
						  <input name="telefono" id="telefono" value="<?php echo $_POST["telefono"] ?>" type="text" class="form-control" placeholder="Telefono del cliente"  />
						</div> 
						
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Municipio</span>
							 <select  class="form-control" name="municipio" id="municipio" required>
								<option value="">Seleccione un municipio</option>							
							 </select>
						</div>	

						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Coordenadas<br> - W -</span>
						  <input  name="coordenada_w_g" value="<?php echo $_POST["coordenada_w_g"] ?>" id="coordenada_w_g" type="text" class="form-control" placeholder="Grados" required />
						  <input  name="coordenada_w_m" value="<?php echo $_POST["coordenada_w_m"] ?>" id="coordenada_w_m" type="text" class="form-control" placeholder="Minutos" required />
						  <input  name="coordenada_w_s" value="<?php echo $_POST["coordenada_w_s"] ?>" id="coordenada_w_s" type="text" class="form-control" placeholder="Segundos" required />
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
						  <input  name="deco_1" style="width:100%" id="deco_1" value="<?php echo $_POST["deco_1"] ?>" type="text" class="form-control" placeholder="Deco instalado" required />
						</div>  
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">DECO 2</span>
						  <input name="deco_2" style="width:100%" id="deco_2" value="<?php echo $_POST["deco_2"] ?>"  type="text" class="form-control" placeholder="Segundo deco instalado"  />
						</div> 			  
					 
					</td>
					
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					
					<td valign=top width='50%'>
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Smart card 1</span>
						  <input name="smart_card_1" id="smart_card_1" value="<?php echo $_POST["smart_card_1"] ?>" type="text" class="form-control" placeholder="Smart card instalado" required />
						</div> 
						
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Smart card 2</span>
						  <input name="smart_card_2" id="smart_card_2" value="<?php echo $_POST["smart_card_2"] ?>" class="form-control" placeholder="Segundo smart card Instalado" />
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
						  <input  name="cable_rg6" style="width:100%" id="cable_rg6" value="<?php echo $_POST["cable_rg6"] ?>" type="number"  class="form-control" placeholder="Cantidad de cable RG-6 gastada" required />
						</div>  
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Conector RG-6</span>
						  <input name="correa_rg6"  id="correa_rg6" value="<?php echo $_POST["correa_rg6"] ?>"  type="number" class="form-control" placeholder="Cantidad de correa RG-6 gastada" required />
						</div> 	

						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Conector carga Terminal</span>
						  <input name="conector_carga"  id="conector_carga" value="<?php echo $_POST["conector_carga"] ?>"  type="number" class="form-control" placeholder="Cantidad de conectores" required />
						</div>

						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Antena (Plato)</span>
						  <input name="plato" id="plato" value="<?php echo $_POST["plato"] ?>"  type="number" class="form-control" placeholder="Cantidad de antenas" required />
						</div>
					 
					</td>
					
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					
					<td valign=top width='50%'>
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Correa Plastica</span>
						  <input name="correa_plastica" id="correa_plastica" value="<?php echo $_POST["correa_plastica"] ?>" type="number" class="form-control" placeholder="Cantidad de correa plastica gastada" required />
						</div> 
						
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Grapas</span>
						  <input name="grapa" id="grapa" value="<?php echo $_POST["grapa"] ?>"  type="number" class="form-control" placeholder="Cantidad de grapas gastada" required />
						</div> 

						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">LNB</span>
						  <input name="lnb" id="lnb" value="<?php echo $_POST["lnb"] ?>"  type="number" class="form-control" placeholder="Cantidad de LNB" required />
						</div> 
						
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Conector security</span>
						  <input name="conector_security" id="conector_security" value="<?php echo $_POST["conector_security"] ?>"  type="number" class="form-control" placeholder="Cantidad de conectores security" required />
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
	
			<table align="center">
				<tr>
					<td valign=top  width='25%'>					
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Tiempo <br> Transporte</span>
							<input  name="tiempo_transporte_d" id="tiempo_transporte_d" value="<?php echo $_POST["tiempo_transporte_d"] ?>" type="number"  class="form-control" placeholder="Dias" required />
							<input  name="tiempo_transporte_h" id="tiempo_transporte_h" value="<?php echo $_POST["tiempo_transporte_h"] ?>" type="number"  class="form-control" placeholder="Horas" required />
							<input  name="tiempo_transporte_m" id="tiempo_transporte_m" value="<?php echo $_POST["tiempo_transporte_m"] ?>" type="number"  class="form-control" placeholder="Minutos" required />
						</div>  					
					</td>
					<td valign=top  width='25%'>					
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Tiempo <br> Instalar <br> Antena</span>
							<input  name="tiempo_antena_d" id="tiempo_antena_d" value="<?php echo $_POST["tiempo_antena_d"] ?>" type="number"  class="form-control" placeholder="Dias" required />
							<input  name="tiempo_antena_h" id="tiempo_antena_h" value="<?php echo $_POST["tiempo_antena_h"] ?>" type="number"  class="form-control" placeholder="Horas" required />
							<input  name="tiempo_antena_m" id="tiempo_antena_m" value="<?php echo $_POST["tiempo_antena_m"] ?>" type="number"  class="form-control" placeholder="Minutos" required />
						</div>  					
					</td>
					<td valign=top  width='25%'>					
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Tiempo <br> Instalar <br> Cableado</span>
							<input  name="tiempo_cableado_d" id="tiempo_cableado_d" value="<?php echo $_POST["tiempo_cableado_d"] ?>" type="number"  class="form-control" placeholder="Dias" required />
							<input  name="tiempo_cableado_h" id="tiempo_cableado_h" value="<?php echo $_POST["tiempo_cableado_h"] ?>" type="number"  class="form-control" placeholder="Horas" required />
							<input  name="tiempo_cableado_m" id="tiempo_cableado_m" value="<?php echo $_POST["tiempo_cableado_m"] ?>" type="number"  class="form-control" placeholder="Minutos" required />
						</div>  					
					</td>
					<td valign=top  width='25%'>					
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Tiempo <br> Aprovisio.</span>
							<input  name="tiempo_aprovisionamiento_d" id="tiempo_aprovisionamiento_d" value="<?php echo $_POST["tiempo_aprovisionamiento_d"] ?>" type="number"  class="form-control" placeholder="Dias" required />
							<input  name="tiempo_aprovisionamiento_h" id="tiempo_aprovisionamiento_h" value="<?php echo $_POST["tiempo_aprovisionamiento_h"] ?>" type="number"  class="form-control" placeholder="Horas" required />
							<input  name="tiempo_aprovisionamiento_m" id="tiempo_aprovisionamiento_m" value="<?php echo $_POST["tiempo_aprovisionamiento_m"] ?>" type="number"  class="form-control" placeholder="Minutos" required />
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
	
			<table align="center">
				<tr>
					<td valign=top  width='50%'>
					
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Antena</span>
						  <input  name="archivo_1"  id="archivo_1"  type="file"  class="form-control"  required />
						</div>  
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Equipos</span>
						  <input name="archivo_2"  id="archivo_2"   type="file" class="form-control"  required />
						</div> 

						<div class=" form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Coordenadas</span>
						  <input name="archivo_5"  id="archivo_5"   type="file" class="form-control"  required />
						</div> 
					 
					</td>
					
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					
					<td valign=top width='50%'>
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Cableado</span>
						  <input name="archivo_3" id="archivo_3"  type="file" class="form-control" required />
						</div> 
						
						<div class="form-group input-group input-group-lg" style="width:100%">
						   <span class="input-group-addon">Predio</span>
						  <input name="archivo_4" id="archivo_4"   type="file" class="form-control"  required />
						</div> 	
						
					</td>
				</tr>							
			</table>	
		</div>
					
	</div>
</div>


<center><input type="submit" value="Agregar tramite DTH" class="btn btn-primary"></center>

	
</form>			
