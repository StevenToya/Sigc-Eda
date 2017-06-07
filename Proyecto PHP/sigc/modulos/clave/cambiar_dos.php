<?php
if($_GET["ingresar"])
{
	$sql = "SELECT id FROM usuario WHERE id = '".$_SESSION["user_id"]."' AND clave_dos='".md5($_POST["password_viejo"])."' LIMIT 1 ";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	
	if($row["id"])
	{
		$sql = "UPDATE usuario SET clave_dos = '".md5($_POST["password_nuevo"])."'  WHERE id ='".$_SESSION["user_id"]."' LIMIT 1 ;";
		 mysql_query($sql);	
		 echo '<script >alert("Segunda clave cambiada correctamente!");</script>';
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index'>";
			
	}
	else
	{
		$error = "Segunda clave actual incorrecta !!";
	}
	
}

?>

<script>
function compara() 
{

	if (document.form.password_nuevo.value != document.form.password_nuevo2.value) 
	{
		alert('Las contraseña no son identicas, por favor reintente.');
		return false; 
	}
	else {
		return true;
	}
}
</script>

<h2>CAMBIAR SEGUNDA CONTRASE&Ntilde;A </h2>
<div align=right>
	<a href="?cmp=index"> <i class="fa fa-reply fa-2x"></i> Volver</a>
</div>

<form action="?ingresar=1"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<table align="center" width=60%>
		<tr>
			 <td valign=top>
			
				<div class=" form-group input-group input-group-lg" style="width:100%">
				  <span class="input-group-addon">Segunda Contrase&ntilde;a actual</span>
				  <input  name="password_viejo"  id="password_viejo" 	  type="password"  class="form-control" placeholder="Ingrese la segunda contrase&ntilde;a que esta usando actualmente" required />
				</div>  
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Segunda Contrase&ntilde;a nueva</span>
				  <input name="password_nuevo"  id="password_nuevo"  type="password" class="form-control" placeholder="Ingrese la nueva segunda contrase&ntilde;a " required />
				</div> 
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Repetir segunda contrase&ntilde;a</span>
				  <input  name="password_nuevo2"  id="password_nuevo2" type="password" class="form-control" placeholder="Repetir la nueva segunda contrase&ntilde;a " required />
				</div> 
	
					<?php if($error){?>
					<div class="alert alert-info">
						<?php echo $error; ?>
					</div>
				<?php } ?>
					<center><input type="submit" class="btn btn-primary" value="Cambiar contrase&ntilde;a"  onClick="return compara();"></center>
			</td>
		</tr>
				
	</table>

	
</form>			
