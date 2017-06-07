<?php
if($_GET["ingresar"])
{
	$sql = "SELECT id FROM usuario WHERE id = '".$_SESSION["user_id"]."' AND clave='".md5($_POST["password_viejo"])."' LIMIT 1 ";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	
	if($row["id"])
	{
		$sql = "UPDATE usuario SET clave = '".md5($_POST["password_nuevo"])."'  WHERE id ='".$_SESSION["user_id"]."' LIMIT 1 ;";
		 mysql_query($sql);	
		 echo '<script >alert("Clave cambiada correctamente!");</script>';
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index'>";
			
	}
	else
	{
		$error = "Clave actual incorrecta !!";
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

<h2>CAMBIAR CONTRASE&Ntilde;A </h2>

<form action="?ingresar=1"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<table align="center" width=60%>
		<tr>
			 <td valign=top>
			
				<div class=" form-group input-group input-group-lg" style="width:100%">
				  <span class="input-group-addon">Contrase&ntilde;a actual</span>
				  <input  name="password_viejo"  id="password_viejo" 	  type="password"  class="form-control" placeholder="Ingrese la contrase&ntilde;a que esta usando actualmente" required />
				</div>  
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Contrase&ntilde;a nueva</span>
				  <input name="password_nuevo"  id="password_nuevo"  type="password" class="form-control" placeholder="Ingrese la nueva contrase&ntilde;a " required />
				</div> 
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Repetir contrase&ntilde;a</span>
				  <input  name="password_nuevo2"  id="password_nuevo2" type="password" class="form-control" placeholder="Repetir la nueva contrase&ntilde;a " required />
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
<h4>
<?php
	$sql = "SELECT id FROM usuario WHERE id = '".$_SESSION["user_id"]."' AND clave_dos IS NULL LIMIT 1 ";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);	
	if($row["id"]){
?>	
	<font color=red><i class='fa fa-exclamation-triangle fa-2x'> </i></font> Usted no tiene Segunda clave, La puede crear <a href="?cmp=clave_dos">aqui</a> 	
<?php
}else{
?>	
	Cambiar la segunda clave <a href="?cmp=cambiar_dos">aqui</a>
<?php
}
?>	
	
</form>			
