<h2>INFORMACIÓN INTERVENTORA DE CONTRATO </h2>


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

				<div class="form-group input-group input-group-lg" style="width:100%;">
				   <span style="width:20%" class="input-group-addon">Valor Contrato</span>
					 <input  style="width:100%" type="number" class="form-control" name="v_contrato" id="v_contrato" required>
				   
				   <span style="width:20%" class="input-group-addon">Moneda</span>
					 <select   class="form-control" name="id_moneda" id="id_moneda" required>
						<option value="">Escoger Moneda</option>
						<option value="">Pesos</option>
						<option value="">Dolar</option>
						<option value="">Otro</option>						
					 </select>
				</div>
				
				<div class="form-group input-group input-group-lg" style="width:100%;">
				   <span style="width:20%" class="input-group-addon">Empresa Interventora</span>
					 <input  style="width:100%" type="text" class="form-control" name="e_interventora" id="e_interventora" required>
				   
				   <span style="width:20%" class="input-group-addon">CC - NIT</span>
					 <input  style="width:100%" type="text" class="form-control" name="cc_nit" id="cc_nit" required>
				</div>
				
				<div class="form-group input-group input-group-lg" style="width:100%;">
				   <span style="width:20%" class="input-group-addon">Direccion</span>
					 <input  style="width:100%" type="text" class="form-control" name="direccion" id="direccion" required>
				   
				   <span style="width:20%" class="input-group-addon">Teléfono</span>
					 <input  style="width:100%" type="text" class="form-control" name="telefono" id="telefono" required>
				</div>
				
				<div class="form-group input-group input-group-lg" style="width:75%;">
				   <span style="width:27%" class="input-group-addon">E-mail</span>
					 <input  style="width:100%" type="text" class="form-control" name="correo" id="correo" required>						
				</div>
				
				<div class="form-group input-group input-group-lg" style="width:100%;">
				   <span style="width:20%" class="input-group-addon">Departamento</span>
					 <input  style="width:100%" type="text" class="form-control" name="departamento" id="departamento" required>						
				   
				   <span style="width:20%" class="input-group-addon">Ciudad</span>
					 <input  style="width:100%" type="text" class="form-control" name="ciudad" id="ciudad" required>
				</div>
				
				<div class="form-group input-group input-group-lg" style="width:100%;">
				   <span style="width:20%" class="input-group-addon">ING-Interventor</span>
					 <input  style="width:100%" type="text" class="form-control" name="interventor" id="interventor" required>						
				   
				   <span style="width:20%" class="input-group-addon">CC - NIT</span>
					 <input  style="width:100%" type="text" class="form-control" name="cc_nit" id="cc_nit" required>
				</div>
						
				<div class="form-group input-group input-group-lg" style="width:100%;">
				   <span style="width:20%" class="input-group-addon">Dirección</span>
					 <input  style="width:100%" type="text" class="form-control" name="direccion" id="direccion" required>						
				   
				   <span style="width:20%" class="input-group-addon">Teléfono</span>
					 <input  style="width:100%" type="text" class="form-control" name="telefono" id="telefono" required>
				</div>
				
				<div class="form-group input-group input-group-lg" style="width:100%;">
				   <span style="width:20%" class="input-group-addon">E-mail</span>
					 <input  style="width:100%" type="text" class="form-control" name="correo" id="correo" required>						
				   
				   <span style="width:20%" class="input-group-addon">Móvil</span>
					 <input  style="width:100%" type="text" class="form-control" name="movil" id="movil" required>
				</div>
				
				<div class="form-group input-group input-group-lg" style="width:50%;">
				   <span style="width:40%" class="input-group-addon">Ciudad</span>
					 <input  style="width:100%" type="text" class="form-control" name="ciudad" id="ciudad" required>						
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