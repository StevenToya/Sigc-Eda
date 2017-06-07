<?php
$sql = "SELECT id FROM usuario WHERE id = '".$_SESSION["user_id"]."' AND clave_dos IS NULL LIMIT 1 ";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);	
if(!$row["id"]){
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_POST["cambiar"])
{
	$sql = "SELECT id FROM usuario WHERE id = '".$_SESSION["user_id"]."' AND clave='".md5($_POST["password_principal"])."' LIMIT 1 ";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	
	if($row["id"])
	{
		$sql = "UPDATE usuario SET clave_dos = '".md5($_POST["password_dos"])."'  WHERE id ='".$_SESSION["user_id"]."' LIMIT 1 ;";
		 mysql_query($sql);	
		 echo '<script >alert("Segunda clave creada correctamente!");</script>';
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index'>";
			
	}
	else
	{
		$error = "Clave principal incorrecta !!";
	}
	
}

?>

<script>
function compara() 
{

	if (document.form.password_dos.value != document.form.password_dos2.value) 
	{
		alert('Las segundas contraseñas no son identicas, por favor reintente.');
		return false; 
	}
	else {
		return true;
	}
}

function validate(){
	if(document.getElementById('minchar').value.length < 8) {
		alert('El campo debe tener al menos 8 carácteres.');
		}else{
		document.getElementById('form').submit();
	}
}
</script>

<h2>CREAR O CAMBIAR SEGUNDA CONTRASE&Ntilde;A </h2>
<div align=right>
	<a href="?cmp=index"> <i class="fa fa-reply fa-2x"></i> Volver</a>
</div>

<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<table align="center" width=60%>
		<tr>
			 <td valign=top>
			
				<div class=" form-group input-group input-group-lg" style="width:100%">
				  <span class="input-group-addon">Contrase&ntilde;a Principal</span>
				  <input  name="password_principal"  id="password_principal" 	  type="password"  class="form-control" placeholder="Ingrese la contrase&ntilde;a que esta usando actualmente" required />
				</div>  
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Segunda Contrase&ntilde;a </span>
				  <input name="password_dos"  id="password_dos"  type="password"  class="form-control" placeholder="Ingrese la segunda contrase&ntilde;a " required />
				</div> 
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Repetir contrase&ntilde;a</span>
				  <input  name="password_dos2"  id="password_dos2" type="password"  class="form-control" placeholder="Repetir la segunda contrase&ntilde;a " required />
				</div> 
	
					<?php if($error){?><h2>
					<div class="alert alert-info">
						<center><?php echo $error; ?></center>
					</div>
					</h2>
				<?php } ?>
					<center><input type="submit" class="btn btn-primary"  value="Cambiar contrase&ntilde;a"  name="cambiar" onClick="return compara(),validate();"></center>
			</td>
		</tr>
				
	</table>
	
</form>			
