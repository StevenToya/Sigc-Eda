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


if($_POST["guardar"])
{	

	$_POST["identificacion"] = limpiar_numero($_POST["identificacion"]);
	$sql_bus = "SELECT estado, id FROM stohome_persona WHERE identificacion = '".$_POST["identificacion"]."' LIMIT 1";
	$res_bus = mysql_query($sql_bus);
	$row_bus = mysql_fetch_array($res_bus);
	
	if($row_bus["id"])
	{
		if($row_bus["estado"]==2)
		{
			$sql = "UPDATE `stohome_persona` SET `estado` = '1'	WHERE `id` = '".$row_bus["id"]."' LIMIT 1;";
			mysql_query($sql);	
			
			echo "<script>alert('La cedula corresponde a una  persona ya retirada, se le volvera a cargar el formulrio con los datos de la persona antes del retiro')</script>";
			echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=editar_persona&id=".$row_bus["id"]."'>";
			die();
			
		}
		else
		{
			echo "<script>alert('Esta cedula ya esta registrada en el sistema')</script>";
			
		}
	}
	else
	{
	
		if($_POST["id_car"]){ $car_tem = "'".$_POST["id_car"]."'"; }else{$car_tem = ' NULL ';}
		
		$sql = "INSERT INTO `stohome_persona` (`id_usuario` ,`id_plataforma` , `id_sap` , `id_car` , `id_rubro` , 
		`identificacion` , `nombre`  , `estado`, `id_municipio`, `fecha_registro`)
				VALUES ('".$_POST["id_usuario"]."', '".$_POST["id_plataforma"]."', '".$_POST["id_sap"]."', ".$car_tem.", '".$_POST["id_rubro"]."', 
				'".limpiar($_POST["identificacion"])."', '".limpiar(strtoupper($_POST["nombre"]))."',  '1', '".$_POST["municipio"]."',	'".date("Y-m-d G:i:s")."');";
		mysql_query($sql);
		$id_persona = mysql_insert_id();
		if($id_persona)
		{		
			$sql_item = "INSERT INTO stohome_persona_item (id_persona, id_item) VALUES ('".$id_persona."', '".$_POST["id_rol"]."')";
			mysql_query($sql_item);
			
			$sql_reco = "SELECT * FROM stohome_item WHERE tipo = 2";
			$res_reco = mysql_query($sql_reco);
			while($row_reco = mysql_fetch_array($res_reco))
			{
				$tem = 'che_'.$row_reco["id"];
				if($_POST[$tem])
				{
					$sql_item = "INSERT INTO stohome_persona_item (id_persona, id_item) VALUES ('".$id_persona."', '".$row_reco["id"]."')";
					mysql_query($sql_item);
				}
			}
			


			echo "<script>alert('La persona fue  guardada correctamente')</script>";
			 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=persona'>";
			 die();
		}
		else
		{
			$error = "ERROR: No se pudo ingresar el pedido en la base de datos";
		}
	}
	
}


?> 
<h2>INGRESAR NUEVA PERSONA</h2>  
<div align=right>
<a href="?cmp=persona"> <i class="fa fa-reply fa-2x"></i> Volvel al listado del persona </a>
</div>
<form  method="post" action="?" enctype="multipart/form-data"> 
<br>
<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" width="100%">

	<tr>
		<td width="48%">
		
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Nombre</span>
				<input  name="nombre" value="<?php echo $_POST["nombre"] ?>" id="text" type="text" class="form-control" placeholder="Ingrese el nombre" required />
			</div> 
		
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Identificacion  </span>
				<input  name="identificacion" value="<?php echo $_POST["identificacion"] ?>" id="text" type="text" class="form-control" placeholder="Ingrese identificacion" required />
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
					<option value="">Seleccione un municipio</option>							
				 </select>
			</div>
		
			<div class=" form-group input-group input-group-lg" style="width:100%">
				<span class="input-group-addon">Plataforma  </span>
					<select name="id_plataforma" class="form-control" required>
						<option value="">Seleccione una plataforma</option>
						<?php
						$sql_bus = "SELECT * FROM stohome_plataforma ORDER BY nombre";
						$res_bus = mysql_query($sql_bus);
						while($row_bus = mysql_fetch_array($res_bus)){
						?>
							<option value="<?php echo $row_bus["id"] ?>"><?php echo $row_bus["nombre"] ?></option>
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
							<option value="<?php echo $row_bus["id"] ?>"><?php echo $row_bus["nombre"] ?> <?php echo $row_bus["apellido"] ?> </option>
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
						$sql_bus = "SELECT * FROM stohome_item WHERE tipo=1 ORDER BY nombre";
						$res_bus = mysql_query($sql_bus);
						while($row_bus = mysql_fetch_array($res_bus)){
						?>
							<option value="<?php echo $row_bus["id"] ?>"><?php echo $row_bus["nombre"] ?></option>
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
						$sql_bus = "SELECT * FROM stohome_sap ORDER BY nombre";
						$res_bus = mysql_query($sql_bus);
						while($row_bus = mysql_fetch_array($res_bus)){
						?>
							<option value="<?php echo $row_bus["id"] ?>"><?php echo $row_bus["nombre"] ?> - <?php echo $row_bus["cuenta"] ?></option>
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
						$sql_bus = "SELECT * FROM stohome_car ORDER BY nombre";
						$res_bus = mysql_query($sql_bus);
						while($row_bus = mysql_fetch_array($res_bus)){
						?>
							<option value="<?php echo $row_bus["id"] ?>"><?php echo $row_bus["nombre"] ?></option>
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
						$sql_bus = "SELECT * FROM stohome_rubro ORDER BY nombre";
						$res_bus = mysql_query($sql_bus);
						while($row_bus = mysql_fetch_array($res_bus)){
						?>
							<option value="<?php echo $row_bus["id"] ?>"><?php echo $row_bus["nombre"] ?></option>
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
 <div class="col-md-100 col-sm-100" style="width:80%">
	<div class="panel panel-primary">
		<div class="panel-heading">
			Item  para asignar 
		</div>
		<div class="panel-body" align=center>
			<table border=0 width=70%>				
				<?php
				$sql = "SELECT * FROM stohome_item WHERE tipo = 2";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
				?>
					<tr>
						<td><input type="checkbox" style="width:30px;height:30px;"  name="che_<?php echo $row["id"] ?>"></td>
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
<br><input class="btn btn-primary" type="submit" value="Guardar Persona" name="guardar" /></center>
</form>