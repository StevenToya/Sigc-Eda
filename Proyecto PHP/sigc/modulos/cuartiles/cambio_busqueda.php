<?php

if($_POST["guardar"])
{
	if($_POST["cuar_min"]){$_SESSION["cuar_min"] = $_POST["cuar_min"];}
	if($_POST["cuar_max"]){$_SESSION["cuar_max"] = $_POST["cuar_max"];}
	if($_POST["cuar_fecha_ini"]){$_SESSION["cuar_fecha_ini"] = $_POST["cuar_fecha_ini"];}
	if($_POST["cuar_fecha_fin"]){$_SESSION["cuar_fecha_fin"] = $_POST["cuar_fecha_fin"];}
		
	
	 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista'>";
	 die();	
}



?>

<h2>MODIFICAR CONFIGURACION DE BUSQUEDA PARA CUARTILES </h2>

<div align=right>
	<a href="?cmp=lista"> <i class="fa fa-reply fa-2x"></i> Volver al panel inicial</a>
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
						  <span class="input-group-addon">Tramites minimo</span>
								<input type="number" id="cuar_min" name="cuar_min" value="<?php echo $_SESSION["cuar_min"] ?>" class="form-control">
						</div>
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Fecha Inicial</span>
							<input type="text" id="cuar_fecha_ini" name="cuar_fecha_ini" value="<?php echo $_SESSION["cuar_fecha_ini"] ?>" class="form-control">
							<script type="text/javascript">
								var opts = {                            
								formElements:{"cuar_fecha_ini":"Y-ds-m-ds-d"},
								showWeeks:true,
								statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
								};      
								datePickerController.createDatePicker(opts);
							</script>
						</div>
					 
					</td>
					
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					
					<td valign=top width='50%'>
					
						
						
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Tramites maximo</span>
							<input type="number" id="cuar_max" name="cuar_max" value="<?php echo $_SESSION["cuar_max"] ?>" class="form-control">
						</div>
						
						<div class=" form-group input-group input-group-lg" style="width:100%">
						  <span class="input-group-addon">Fecha Final</span>
							<input type="text" id="cuar_fecha_fin" name="cuar_fecha_fin" value="<?php echo $_SESSION["cuar_fecha_fin"] ?>" class="form-control">
							<script type="text/javascript">
								var opts = {                            
								formElements:{"cuar_fecha_fin":"Y-ds-m-ds-d"},
								showWeeks:true,
								statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
								};      
								datePickerController.createDatePicker(opts);								
							</script>
						</div>
						
					</td>
				</tr>					
			</table>
	
	

<center><input type="submit" value="Guardar configuracion de busqueda" name="guardar" class="btn btn-primary"></center>

	
</form>			
