<h2>GENERACION DE CONTRATO & ASIGNACION DE SUPERVISOR/INTERVENTOR </h2>


<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<table align="left" width="96%">
		<tr>
			 <td align=left valign=top>
				
				<div class="form-group input-group input-group-lg" style="width:50%;">
				   <span style="width:40%" class="input-group-addon">Contrato Numero </span>
					 <input  style="width:100%" type="number" class="form-control" name="pasajeros" id="pasajeros" required>						
				</div> 

				<div class="form-group input-group input-group-lg" style="width:100%;">
				   <span style="width:20%" class="input-group-addon">Objeto de contrato</span>
					 <textarea  cols="20"  class="form-control" style="height: 158px; "> </textarea>						
				</div> 
							
				<div class=" form-group input-group input-group-lg" style="width:100%;">
				   <span  style="width:20%" class="input-group-addon">Supervisor</span>
					 <select   class="form-control" name="id_tipo_vehiculo" id="id_tipo_vehiculo" required>
						<option value="">Seleccione un tipo</option>
						<?php
						$sql_dep = "SELECT * FROM usuario ORDER BY apellido, nombre";
						$res_dep = mysql_query($sql_dep);
						while($row_dep = mysql_fetch_array($res_dep))
						{
						?>
							<option value="<?php echo $row_dep["id"] ?>" ><?php echo utf8_encode($row_dep["apellido"]) ?> <?php echo utf8_encode($row_dep["nombre"]) ?> </option>
						<?php
						}
						?>										
					 </select>
				</div> 
				
				<div class=" form-group input-group input-group-lg" style="width:50%;">
				   <span style="width:40%" class="input-group-addon">Estado</span>
					 <select   class="form-control" name="id_tipo_vehiculo" id="id_tipo_vehiculo" required>
						<option value="">Escoger estado</option>
						<option value="">Activo</option>
						<option value="">Supendido</option>
						<option value="">Liquidacion</option>						
					 </select>
				</div> 
			  </td>
		<tr>	

		<tr>
			<td  align=center>
					<?php if($error){?>
					<div class="alert alert-info">
						<?php echo $error; ?>
					</div>
				<?php } ?>
					<input type="button" class="btn btn-primary"  name="guardar" value="GENERAR & NOTIFICAR">
			</td>
		</tr>
				
	</table>
</form>			