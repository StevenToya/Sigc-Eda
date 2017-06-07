<?php

if($PERMISOS_GC["usu_edi"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

function clave(){
    $an = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_.:,;";
    $su = strlen($an) - 1;
    return  substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1);
}

if($_GET["reset"])
{
	
	$acceso = clave();
	$sql = "UPDATE usuario SET clave = '".md5($acceso)."' WHERE id ='".$_GET["reset"]."';";
	 mysql_query($sql);
	 
	if(!mysql_error())
	{		
		$sql = "SELECT apellido, nombre, correo, usuario FROM usuario WHERE id = '".$_GET["reset"]."' LIMIT 1 ";
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res);	
		
		$mensaje = "<b>Buenos dias</b><br><br>
			Se le cambio la clave para ingresar al sistema <b>SIGC</b><br><br>
			Usuario: <b>".$row["usuario"]."</b><br>
			Clave: <b>".$acceso."</b><br>
			Instancia: <b>".$row_index["nombre_instancia"]."</b><br><br><br><br>			 
			 
			ATENTAMENTE, 
			Sistema S.I.G.C ";
			 
			 enviar_correo(trim($row["correo"]), $row["nombre"], $mensaje, "Cambio de clave, SIGC", NULL);
		
		
		
		echo '<script >	alert("Contraseña reseteada y enviada al correo");</script>';
		echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista'>"; die();
		
	}else{
		$error = 'ERROR BASE DE DATOS <br> '.mysql_error();
		$_GET["id"] = $_GET["reset"];
	}
}


if($_GET["reset2"])
{
	
	
	$sql = "UPDATE usuario SET clave_dos = NULL WHERE id ='".$_GET["reset2"]."';";
	 mysql_query($sql);
	 
	if(!mysql_error())
	{		
		$sql = "SELECT apellido, nombre, correo, usuario FROM usuario WHERE id = '".$_GET["reset2"]."' LIMIT 1 ";
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res);	
		
		$mensaje = "<b>Buenos dias</b><br><br>
			Su segunda clave fue borrada en el sistema <b>SIGC</b>, usted debe crearla nuevamente<br><br>
					 
			ATENTAMENTE, 
			Sistema S.I.G.C ";
			 
			 enviar_correo(trim($row["correo"]), $row["nombre"], $mensaje, "Clave dos eliminada, SIGC", NULL);		
		
		
		echo '<script >	alert("Segunda clave eliminada y notificada al correo");</script>';
		echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista'>"; die();
		
	}else{
		$error = 'ERROR BASE DE DATOS <br> '.mysql_error();
		$_GET["id"] = $_GET["reset2"];
	}
}


if($_GET["editar"])
{
		
	$sql = "SELECT id FROM usuario WHERE  identificacion = '".$_POST["identificacion"]."'  AND id != '".$_GET["id"]."'   LIMIT 1";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	if($row["id"])
	{
		$error = 'La identificacion  "'.$_POST["identificacion"].'" ya lo tiene otro usuario ';
	}
	else
	{
		
		$sql = "UPDATE usuario SET 
		nombre = '".ucwords(strtolower($_POST["nombre"]))."',  
		apellido = '".ucwords(strtolower($_POST["apellido"]))."', 
		identificacion = '".$_POST["identificacion"]."', 
		id_municipio = '".$_POST["municipio"]."', 
		correo = '".$_POST["email"]."',
		direccion = '".$_POST["direccion"]."' 		
		WHERE id ='".$_GET["id"]."';";
		mysql_query($sql);
		
		if(!mysql_error())
		{
			$sql = "INSERT INTO seguimiento (`id_usuario`, `tipo`, `tabla`, `ejecucion`, `descripcion`, `fecha`,`id_tabla` ) 
			VALUES ( '".$_SESSION["user"]."', '1', 'usuario', '2', 'Editar usuario ".ucwords(strtolower($_POST["apellido"]))." ".ucwords(strtolower($_POST["nombre"]))." ', '".date("Y-m-d G:i:s")."', '".$_GET["id"]."');";
			mysql_query($sql);
			  echo '<script >	alert("Usuario editado correctamente!");</script>';
			  echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista'>";
		
		}else
		{
			$error = 'ERROR BASE DE DATOS <br> '.mysql_error();
		}
		
	}
	
	
	
}

$sql = "SELECT * FROM usuario WHERE id = '".$_GET["id"]."' LIMIT 1 ";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
?>

<h2> EDITAR DATOS DEL USUARIO </h2>
<div align=right>
	<a href="?cmp=lista"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de usuario </a>
</div>

<form action="?editar=1&id=<?php echo $row["id"]; ?>"  method="post" name="form" id="form"> 
	<table align="center" width="70%">
		<tr>
			 <td>
			
				<div class=" form-group input-group input-group-lg" style="width:100%">
				  <span class="input-group-addon">Apellido</span>
				  <input  name="apellido" id="apellido" value="<?php echo $row["apellido"] ?>" type="text"  class="form-control" placeholder="Apellido del usuario" required />
				</div>  
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Nombre</span>
				  <input name="nombre" id="nombre" value="<?php echo $row["nombre"] ?>"  type="text" class="form-control" placeholder="Nombre del usuario" required />
				</div> 
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Identificacion</span>
				  <input  name="identificacion" value="<?php echo $row["identificacion"] ?>" id="identificacion" type="number" class="form-control" placeholder="Identificacion del usuario" required />
				</div> 
				
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Departamento</span>
					 <select  class="form-control" name="departamento" id="departamento">
						<option value="">Seleccione un departamento</option>
						<?php
						$sql_dep = "SELECT * FROM departamento ORDER BY nombre";
						$res_dep = mysql_query($sql_dep);
						while($row_dep = mysql_fetch_array($res_dep))
						{
						?>
							<option value="<?php echo $row_dep["id"] ?>" ><?php echo utf8_encode($row_dep["nombre"]) ?></option>
						<?php
						}
						?>										
					 </select>
				</div> 
			  
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Municipio</span>
					 <select  class="form-control" name="municipio" id="municipio" required>
						<?php
						$sql_mun = "SELECT id, nombre FROM municipio WHERE id = '".$row["id_municipio"]."' LIMIT 1";
						$res_mun = mysql_query($sql_mun);
						$row_mun = mysql_fetch_array($res_mun);
						?>
						<option value="<?php echo $row_mun["id"] ?>"><?php echo $row_mun["nombre"] ?></option>							
					 </select>
				</div> 
			
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Direccion</span>
				  <input name="direccion" id="direccion" value="<?php echo $row["direccion"] ?>" type="text" class="form-control" placeholder="Direccion del usuario" required />
				</div> 
				
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">E - mail</span>
				  <input name="email" id="email" value="<?php echo $row["correo"] ?>" required  type="email" class="form-control" placeholder="E-mail del usuario" />
				</div> 	

				<?php if($error){?>
					<div class="alert alert-info">
						<?php echo $error; ?>
					</div>
				<?php } ?>
				
				
				<center><input type="submit" class="btn btn-primary" value="Editar Usuario"></center>
				
				<div align=right>
					<a href="?reset=<?php echo $_GET["id"]; ?>"> <font color=red><i class="fa fa-refresh fa-2x"></i></font> Resetear clave primera clave</a> &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 
					<a href="?reset2=<?php echo $_GET["id"]; ?>"> <font color=red><i class="fa fa-refresh fa-2x"></i></font> Borrar segunda clave</a> 
				</div>
				
			</td>
		</tr>
	</table>
</form>			