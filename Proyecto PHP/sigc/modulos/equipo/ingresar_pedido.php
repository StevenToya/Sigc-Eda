<?php
/*
if($PERMISOS_GC["pro_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}
*/
if($_POST["guardar"])
{		
	$sql = "INSERT INTO `pedido_equipo_material` (`numero` ,`estado` ,`tipo` ,`fecha`, `fecha_registro`, `id_localidad`, `id_usuario`)
			VALUES ('".limpiar($_POST["numero"])."', '1', '1' ,'".$_POST["fecha"]."','".date("Y-m-d G:i:s")."','".$_POST["id_localidad"]."','".$_SESSION["user_id"]."');";
	mysql_query($sql);
	if(!mysql_error())
	{		
		 echo "<script>alert('El pedido se guardo correctamente')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_pedido'>";
		 die();
	}
	else
	{
		$error = "ERROR: No se pudo ingresar el pedido en la base de datos";
	}
			
}


?> 
<h2>INGRESAR NUEVO PEDIDO</h2>  
<div align=right>
<a href="?cmp=lista_pedido"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de pedidos </a>
</div>
<form  method="post" action="?id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data"> 
<br>
<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" width="80%">

	<tr>
		<td >
			<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Numero del pedido  </span>
				  <input  name="numero" value="<?php echo $_POST["numero"] ?>" id="numero" type="text" class="form-control" placeholder="Ingrese el numero del pedido" required />
			</div> 
		</td>
	</tr>
	<tr>
		<td >
			<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Bodega</span>
				   <select name="id_localidad" class="form-control" required>
						<option value="">Seleccione una bodega</option>
						<?php
						$sql_localidad = "SELECT * FROM localidad WHERE id IN ('105','15','39','65','7','8','213') ORDER BY nombre";
						$res_localidad = mysql_query($sql_localidad);
						while($row_localidad = mysql_fetch_array($res_localidad)){
						?>
							<option value="<?php echo $row_localidad["id"] ?>">BODEGA <?php echo $row_localidad["nombre"] ?></option>
						<?php
						}
						?>
				   </select>
				  
			</div> 
		</td>
	</tr>
	<tr>
			<td >	
				<div class=" form-group input-group input-group-lg" style="width:100%">
					   <span class="input-group-addon">Fecha</span>
					  <input  name="fecha" value="<?php echo $_POST["fecha"] ?>" id="fc" type="text" class="form-control"  required />
					  <script type="text/javascript">
						var opts = {                            
						formElements:{"fc":"Y-ds-m-ds-d"},
						showWeeks:true,
						statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
						};      
						datePickerController.createDatePicker(opts);					
					</script>
				</div> 
			</td>			
		</tr>
	</tr>


</table>

<center>
<?php if($error){?>
					<div class="alert alert-info">
						<?php echo $error; ?>
					</div>
				<?php } ?>
<br><input class="btn btn-primary" type="submit" value="Guardar pedido" name="guardar" /></center>
</form>