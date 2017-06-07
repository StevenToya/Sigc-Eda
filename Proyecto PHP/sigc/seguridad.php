



<h2>MODULO PROTEGIDO CON SEGUNDA CLAVE </h2>

<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<table align="center" width=60%>
		<tr>
			 <td valign=top>
			
				<div class=" form-group input-group input-group-lg" style="width:100%">
				  <span class="input-group-addon">Segunda Contrase&ntilde;a</span>
				  <input  name="clave_dos"  id="clave_dos" 	  type="password"  class="form-control" placeholder="Ingrese la segunda clave" required />
				</div>  
				
					
					<?php if($error){?>
					<h2>
					<div class="alert alert-info">
						<center><?php echo $error; ?></center>
					</div>
					</h2>
				<?php } ?>
				
					<center><input type="submit" class="btn btn-primary" value="INGRESAR"  onClick="return compara();"></center>
			</td>
		</tr>
				
	</table>
	<h4><br><br>
		<font color =red>NOTA:</font> Si usted no tiene segunda clave, gesti&oacute;nela en la opci&oacute;n "Cambiar clave"
	</h4>
</form>			
