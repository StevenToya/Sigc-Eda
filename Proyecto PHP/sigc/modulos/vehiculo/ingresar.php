<?php
/*
if($PERMISOS_GC["hv_cre"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}
*/

if($_POST["guardar"])
{
	$sql = "SELECT id FROM vehiculo WHERE  placa = '".trim(strtoupper($_POST["placa1"]))."-".trim(strtoupper($_POST["placa2"]))."' AND id_instancia = '".$_SESSION["nst"]."'  LIMIT 1";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	if($row["id"])
	{		
		$error = 'La placa  "'.trim(strtoupper ($_POST["placa1"])).'-'.trim(strtoupper ($_POST["placa2"])).'"  ya esta registrada';
	}	
	else
	{
				
		$sql = "INSERT INTO `vehiculo` (`id_tipo_vehiculo` ,`placa` ,`pasajeros` ,`modelo` ,`id_instancia` ,`estado`)
				VALUES ('".$_POST["id_tipo_vehiculo"]."', '".trim(strtoupper($_POST["placa1"]))."-".trim(strtoupper($_POST["placa2"]))."', 
				'".$_POST["pasajeros"]."', '".$_POST["modelo"]."' ,'".$_SESSION["nst"]."', '1');";
		mysql_query($sql);
		$id = mysql_insert_id();
		if($id)
		{			
			echo '<script>alert("Vehiculo ingresado correctamente.")</script>';
			echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=editar_documento&idvehiculo=".$id."'>";die();
		}
		else
		{
			$error = 'ERROR BASE DE DATOS <br> '.mysql_error();
		}
		
	}
	
	
	
}

?>

<h2>INGRESAR VEHICULO </h2>

<div align=right>
	<a href="?cmp=lista"> <i class="fa fa-reply fa-2x"></i> Volvel al listado vehiculo </a>
</div>
<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<table align="center" border=0>
		<tr>
			 <td align=center valign=top>
				<div class=" form-group input-group input-group-lg">				 
				  <input  name="placa1" style="font-size:60px;width:130px;align:center"  id="placa1" value="<?php echo $_POST["placa1"] ?>" type="text" size="5"  required /> - 
				  <input  name="placa2" style="font-size:60px;width:130px;align:center"  id="placa2" value="<?php echo $_POST["placa2"] ?>" type="text" size="5"  required />
				</div>  
				
							
				<div class=" form-group input-group input-group-lg" style="width:100%;">
				   <span class="input-group-addon">Tipo</span>
					 <select   class="form-control" name="id_tipo_vehiculo" id="id_tipo_vehiculo" required>
						<option value="">Seleccione un tipo</option>
						<?php
						$sql_dep = "SELECT * FROM vehiculo_tipo ORDER BY descripcion";
						$res_dep = mysql_query($sql_dep);
						while($row_dep = mysql_fetch_array($res_dep))
						{
						?>
							<option value="<?php echo $row_dep["id"] ?>" ><?php echo utf8_encode($row_dep["descripcion"]) ?></option>
						<?php
						}
						?>										
					 </select>
				</div> 
			  
				<div class="form-group input-group input-group-lg" style="width:100%;">
				   <span class="input-group-addon">Pasajeros</span>
					 <input  style="width:100%" type="number" class="form-control" name="pasajeros" id="pasajeros" required>						
				</div> 
				
				<div class="form-group input-group input-group-lg">
				   <span class="input-group-addon">Modelo</span>
					 <input style="width:100%" type="number" class="form-control" name="modelo" id="modelo" required>						
				</div> 
			</td>
			
			

		<tr>
			<td colspan=3 align=center>
					<?php if($error){?>
					<div class="alert alert-info">
						<?php echo $error; ?>
					</div>
				<?php } ?>
					<input type="submit" class="btn btn-primary"  name="guardar" value="Ingresar">
			</td>
		</tr>
				
	</table>
</form>			
