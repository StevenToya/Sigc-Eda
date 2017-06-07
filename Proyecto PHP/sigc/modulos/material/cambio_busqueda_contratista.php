<?php

if($PERMISOS_GC["liq_cont"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


if($_POST["guardar"])
{
	if($_POST["tecnlogia"]){$_SESSION["ss_tecnologia"] = $_POST["tecnlogia"];}else{$_SESSION["ss_tecnologia"] = "";}
	if($_POST["tipo_trabajo"]){$_SESSION["ss_tipo_trabajo"] = $_POST["tipo_trabajo"];}else{$_SESSION["ss_tipo_trabajo"] = "";}
	if($_POST["zona"]){$_SESSION["ss_zona"] = $_POST["zona"];}else{$_SESSION["ss_zona"] = "";}
	if($_POST["servicio"]){$_SESSION["ss_servicio"] = $_POST["servicio"];}else{$_SESSION["ss_servicio"] = "";}
	if($_POST["tipo_tramite"]){$_SESSION["ss_tipo_tramite"] = $_POST["tipo_tramite"];}else{$_SESSION["ss_tipo_tramite"] = "";}
	
	 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=contratista'>";
	 die();	
}



?>

<h2>MODIFICAR CONFIGURACION DE BUSQUEDA PARA LA REVISAR LIQUIDACION </h2>

<div align=right>
	<a href="?cmp=contratista"> <i class="fa fa-reply fa-2x"></i> Volver al panel inicial</a>
</div>
<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	
	<?php if($error){?>
		<div class="alert alert-info">
			<?php echo $error; ?>
		</div>
	<?php } ?>
	
			<table align="center">
				<tr>
					 <td valign=top  width='50%'>
					
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Tecnologia</span>
							<select name="tecnlogia" class="form-control">
								<option value="">Todas</option>
								<?php
								$sql = "SELECT nombre, id FROM tecnologia ORDER BY nombre";
								$res = mysql_query($sql);
								while($row = mysql_fetch_array($res)){
								?>
									<option value="<?php echo $row["id"]; ?>" <?php if($row["id"]==$_SESSION["ss_tecnologia"]){ ?> selected <?php } ?>><?php echo $row["nombre"]; ?></option>
								<?php
								}
								?>
							</select>
						</div>  
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Tipo de tramite</span>
							<select name="tipo_tramite" class="form-control">
								<option value="">Todas</option>
								<option <?php if($_SESSION["ss_tipo_tramite"]==1){ ?> selected <?php } ?> value="1">Instalaci&oacute;n</option>
								<option <?php if($_SESSION["ss_tipo_tramite"]==8){ ?> selected <?php } ?> value="8">Instalacion y Traslado</option>
								<option <?php if($_SESSION["ss_tipo_tramite"]==2){ ?> selected <?php } ?> value="2">Reconexi&oacute;n</option>
								<option <?php if($_SESSION["ss_tipo_tramite"]==3){ ?> selected <?php } ?> value="3">Reparaci&oacute;n</option>
								<option <?php if($_SESSION["ss_tipo_tramite"]==4){ ?> selected <?php } ?> value="4">Suspensi&oacute;n</option>
								<option <?php if($_SESSION["ss_tipo_tramite"]==5){ ?> selected <?php } ?> value="5">Retiro</option>
								<option <?php if($_SESSION["ss_tipo_tramite"]==6){ ?> selected <?php } ?> value="6">Prematricula</option>
								<option <?php if($_SESSION["ss_tipo_tramite"]==7){ ?> selected <?php } ?> value="7">Traslado</option>
							</select>
						</div>
						
						
						
						
					 
					</td>
					
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					
					<td valign=top width='50%'>
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Zona</span>
							<select name="zona" class="form-control">
								<option value="">Todas</option>
								<?php
								$sql = "SELECT nombre FROM zona ORDER BY nombre";
								$res = mysql_query($sql);
								while($row = mysql_fetch_array($res)){
								?>
									<option <?php if($row["nombre"]==$_SESSION["ss_zona"]){ ?> selected <?php } ?>><?php echo $row["nombre"]; ?></option>
								<?php
								}
								?>
							</select>
						</div> 
						
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Tipo de trabajo</span>
							<select name="tipo_trabajo" class="form-control">
								<option value="">Todas</option>
								<?php
								$sql = "SELECT id, nombre  FROM `tipo_trabajo` ORDER BY nombre";
								$res = mysql_query($sql);
								while($row = mysql_fetch_array($res))
								{
								?>
									<option value="<?php echo $row["id"]; ?>" <?php if($row["id"]==$_SESSION["ss_tipo_trabajo"]){ ?> selected <?php } ?>><?php echo utf8_encode($row["nombre"]); ?></option>
								<?php
								}
								?>
							</select>
						</div>
						
						
						
					</td>
				</tr>					
			</table>
	
	

<center><input type="submit" value="Guardar configuracion de busqueda" name="guardar" class="btn btn-primary"></center>

	
</form>			
