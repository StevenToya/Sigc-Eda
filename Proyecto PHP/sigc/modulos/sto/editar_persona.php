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
	$_POST["identificacion"] = limpiar_numero($_POST["identificacion"]);
	if($_POST["id_car"]){ $car_tem = "'".$_POST["id_car"]."'"; }else{$car_tem = ' NULL ';}
	
	$sql = "UPDATE `sto_persona` SET 
	`id_usuario` = '".$_POST["id_usuario"]."',
	`id_plataforma` = '".$_POST["id_plataforma"]."',
	`id_sap` = '".$_POST["id_sap"]."',
	`id_car` = ".$car_tem.",
	`id_rubro` = '".$_POST["id_rubro"]."',
	`id_municipio` = '".$_POST["municipio"]."',
	`identificacion` = '".$_POST["identificacion"]."',
	`nombre` = '".$_POST["nombre"]."',
	`fecha_registro` = '".date("Y-m-d G:i:s")."'
	WHERE `id` = '".$_GET["id"]."' LIMIT 1 ;";
	
	mysql_query($sql);	
	if(!mysql_error())
	{		
		$sql_del = "DELETE FROM `sto_persona_item` WHERE `id_persona` = '".$_GET["id"]."' ";
		mysql_query($sql_del);	
		
		
		$sql_item = "INSERT INTO sto_persona_item (id_persona, id_item) VALUES ('".$_GET["id"]."', '".$_POST["id_rol"]."')";
		mysql_query($sql_item);
		
		$sql_reco = "SELECT * FROM sto_item WHERE tipo = 2";
		$res_reco = mysql_query($sql_reco);
		while($row_reco = mysql_fetch_array($res_reco))
		{
			$tem = 'che_'.$row_reco["id"];
			if($_POST[$tem])
			{
				$sql_item = "INSERT INTO sto_persona_item (id_persona, id_item) VALUES ('".$_GET["id"]."', '".$row_reco["id"]."')";
				mysql_query($sql_item);
			}
		}
		
		echo "<script>alert('La persona fue  actualizada correctamente')</script>";
		echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=persona'>";
		die();
	}
	else
	{
		$error = "ERROR: No se pudo actualizar los datos de la persona en la base de datos";
	}
			
}

 $sql = "SELECT sto_persona.nombre , sto_persona.zona , sto_persona.localidad , sto_persona.fecha_registro, sto_sap.id AS sap_id, sto_car.id AS car_id,
							sto_plataforma.nombre AS nom_plataforma, sto_sap.nombre AS nom_sap, sto_car.nombre AS nom_car, sto_rubro.nombre AS nom_rubro,
							sto_item.nombre AS nom_rol, usuario.nombre AS nom_usuario, usuario.apellido AS ape_usuario, sto_persona.id, sto_rubro.id AS rubro_id,
							sto_persona.identificacion, sto_plataforma.id AS plataforma_id, usuario.id AS usuario_id, sto_item.id AS item_id,
							municipio.nombre AS nom_municipio, municipio.id AS municipio_id
				   FROM sto_persona 
					INNER JOIN sto_plataforma ON sto_persona.id_plataforma = sto_plataforma.id
					INNER JOIN sto_sap ON sto_persona.id_sap = sto_sap.id
					LEFT JOIN sto_car ON sto_persona.id_car = sto_car.id
					INNER JOIN sto_rubro ON sto_persona.id_rubro = sto_rubro.id
					LEFT JOIN usuario ON sto_persona.id_usuario = usuario.id
					LEFT JOIN sto_persona_item ON sto_persona.id = sto_persona_item.id_persona
					LEFT JOIN sto_item ON sto_persona_item.id_item = sto_item.id
					LEFT JOIN municipio ON sto_persona.id_municipio = municipio.id
					WHERE  sto_item.tipo = '1' AND sto_persona.id = '".$_GET["id"]."'  LIMIT 1 ";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);

?> 
<h2>ACTUALIZAR NUEVA PERSONA</h2>  
<div align=right>
<a href="?cmp=persona"> <i class="fa fa-reply fa-2x"></i> Volver al listado del persona </a>
</div>
<form  method="post" action="?id=<?php echo $_GET["id"] ?>" enctype="multipart/form-data"> 
<br>
<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" width="100%">

	<tr>
		<td width="48%">
		
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Nombre</span>
				<input  name="nombre" value="<?php echo $row["nombre"] ?>" id="text" type="text" class="form-control" placeholder="Ingrese el nombre" required />
			</div> 
		
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Identificacion  </span>
				<input  name="identificacion" value="<?php echo $row["identificacion"] ?>" id="text" type="text" class="form-control" placeholder="Ingrese identificacion" required />
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
		
					
			<div class="form-group input-group input-group-lg" style="width:100%">
			   <span class="input-group-addon">Municipio</span>
				 <select  class="form-control" name="municipio" id="municipio" required>
					<?php
					$sql_bus = "SELECT id, nombre FROM municipio WHERE id = '".$row["municipio_id"]."' LIMIT 1";
					$res_bus = mysql_query($sql_bus);
					$row_bus = mysql_fetch_array($res_bus);
					?>
					<?php if($row_bus["id"]){ ?>
						<option value="<?php echo $row_bus["id"] ?>"><?php echo $row_bus["nombre"] ?></option>	
					<?php } ?>
					<option value="">Seleccione un municipio</option>
				 </select>
			</div>
		
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Plataforma  </span>
					<select name="id_plataforma" class="form-control" required>
						<option value="">Seleccione una plataforma</option>
						<?php
						$sql_bus = "SELECT * FROM sto_plataforma ORDER BY nombre";
						$res_bus = mysql_query($sql_bus);
						while($row_bus = mysql_fetch_array($res_bus)){
						?>
							<option <?php if($row["plataforma_id"]==$row_bus["id"]){ ?> selected <?php } ?> value="<?php echo $row_bus["id"] ?>"><?php echo $row_bus["nombre"] ?></option>
						<?php
						}
						?>
					</select>
				
			</div> 
		</td>
		<td width="4%"></td>
		<td  width="48%">
		
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Coordinador  </span>
					<select name="id_usuario" class="form-control" required>
						<option value="">Seleccione un coordinador</option>
						<?php
						$sql_bus = "SELECT * FROM usuario ORDER BY nombre, apellido";
						$res_bus = mysql_query($sql_bus);
						while($row_bus = mysql_fetch_array($res_bus)){
						?>
							<option <?php if($row["usuario_id"]==$row_bus["id"]){ ?> selected <?php } ?> value="<?php echo $row_bus["id"] ?>"><?php echo $row_bus["nombre"] ?> <?php echo $row_bus["apellido"] ?> </option>
						<?php
						}
						?>
					</select>				
			</div> 
		
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Rol  </span>
					<select name="id_rol" class="form-control" required>
						<option value="">Seleccione un rol</option>
						<?php
						$sql_bus = "SELECT * FROM sto_item WHERE tipo=1 ORDER BY nombre";
						$res_bus = mysql_query($sql_bus);
						while($row_bus = mysql_fetch_array($res_bus)){
						?>
							<option <?php if($row["item_id"]==$row_bus["id"]){ ?> selected <?php } ?> value="<?php echo $row_bus["id"] ?>"><?php echo $row_bus["nombre"] ?></option>
						<?php
						}
						?>
					</select>
				
			</div> 
		
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Cuenta SAP  </span>
					<select name="id_sap" class="form-control" required>
						<option value="">Seleccione una cuenta</option>
						<?php
						$sql_bus = "SELECT * FROM sto_sap ORDER BY nombre";
						$res_bus = mysql_query($sql_bus);
						while($row_bus = mysql_fetch_array($res_bus)){
						?>
							<option <?php if($row["sap_id"]==$row_bus["id"]){ ?> selected <?php } ?> value="<?php echo $row_bus["id"] ?>"><?php echo $row_bus["nombre"] ?></option>
						<?php
						}
						?>
					</select>
				
			</div> 
		
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Car </span>
					<select name="id_car" class="form-control" >
						<option value="">Seleccione una opcion</option>
						<?php
						$sql_bus = "SELECT * FROM sto_car ORDER BY nombre";
						$res_bus = mysql_query($sql_bus);
						while($row_bus = mysql_fetch_array($res_bus)){
						?>
							<option <?php if($row["car_id"]==$row_bus["id"]){ ?> selected <?php } ?> value="<?php echo $row_bus["id"] ?>"><?php echo $row_bus["nombre"] ?></option>
						<?php
						}
						?>
					</select>
				
			</div> 
		
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Rubro </span>
					<select name="id_rubro" class="form-control" required>
						<option value="">Seleccione una opcion</option>
						<?php
						$sql_bus = "SELECT * FROM sto_rubro ORDER BY nombre";
						$res_bus = mysql_query($sql_bus);
						while($row_bus = mysql_fetch_array($res_bus)){
						?>
							<option <?php if($row["rubro_id"]==$row_bus["id"]){ ?> selected <?php } ?> value="<?php echo $row_bus["id"] ?>"><?php echo $row_bus["nombre"] ?></option>
						<?php
						}
						?>
					</select>
				
			</div> 
		</td>
	</tr>	
</table>

<h4>
<center>
 <div class="col-md-100 col-sm-100" style="width:90%">
	<div class="panel panel-primary">
		<div class="panel-heading">
			Item  para asignar 
		</div>
		<div class="panel-body" align=center>
			<table border=0 width=80%>				
				<?php
				$sql = "SELECT * FROM sto_item WHERE tipo = 2";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
					$sql_item = "SELECT id FROM sto_persona_item  WHERE id_persona ='".$_GET["id"]."' AND id_item ='".$row["id"]."' LIMIT 1";
					$res_item = mysql_query($sql_item);
					$row_item = mysql_fetch_array($res_item);				
					
				?>
					<tr>						
						<td><input <?php if($row_item["id"]){ ?> checked <?php } ?> style="width:30px;height:30px;" type="checkbox"  name="che_<?php echo $row["id"] ?>"></td>	
						<td><?php echo $row["nombre"] ?></td>
					</tr>
					<tr>
						<td><br></td>						
					</tr>
				<?php
				}
				?>			
			</table>		
		</div>
	</div>
</div>
</center>	
</h4>					

<center>
<?php if($error){?>
					<div class="alert alert-info">
						<?php echo $error; ?>
					</div>
				<?php } ?>
<br><input class="btn btn-primary" type="submit" value="Actualizar datos" name="guardar" /></center>
</form>