<h2>GENERACION DE CONTRATO & ASIGNACION DE SUPERVISOR/INTERVENTOR </h2>


<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<table align="left" width="96%">
		<tr>
			 <td align=left valign=top>
				
				<div class="form-group input-group input-group-lg" style="width:50%;">
				   <span style="width:20%" class="input-group-addon">Fecha Reporte Interventoría </span>
					 <input  style="width:100%" type="date" class="form-control" name="f_reporte" id="f_reporte" required>						
				</div> 

				<div class="form-group input-group input-group-lg" style="width:50%;">
				   <span style="width:40%" class="input-group-addon">Referencia</span>
					 <input  style="width:100%" type="text" class="form-control" name="referencia" id="referencia" required>						
				</div>
				
				<div class="form-group input-group input-group-lg" style="width:100%;">
				   <span style="width:20%" class="input-group-addon">Objeto de contrato</span>
					 <textarea  cols="20"  class="form-control" style="height: 158px; "> </textarea>						
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
					<input type="button" class="btn btn-primary"  name="guardar" value="CARGAR">
			</td>
		</tr>
				
	</table>
</form>			