<?php

if($PERMISOS_GC["usu_cre"]!='Si')
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



if($_GET["ingresar"])
{
	$sql = "SELECT id FROM usuario WHERE  identificacion = '".$_POST["identificacion"]."' AND id_instancia = '".$_SESSION["nst"]."'  LIMIT 1";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	if($row["id"])
	{		
		$error = 'La identificacion  "'.$_POST["identificacion"].'"  ya esta registrada';
	}	
	else
	{
		$temp = explode('@',strtolower(trim($_POST["email"])));
		$usuario_ingreso = $temp[0];
		$rep = 's';		
		while($rep == 's')
		{
			$sql_cont = "SELECT id FROM usuario WHERE usuario = '".$usuario_ingreso."' LIMIT 1 ";
			$res_cont = mysql_query($sql_cont);
			$row_cont = mysql_fetch_array($res_cont);
			
			if($row_cont["id"])
			{$usuario_ingreso = $usuario_ingreso.'1';}
			else
			{$rep = 'n';}
		}		
		
		$_POST["nombre"] =  strtolower(trim($_POST["nombre"]));
		$_POST["apellido"] =  strtolower(trim($_POST["apellido"]));			
				
		$acceso = clave();
		
		$sql = "INSERT INTO `usuario` (`usuario` ,`clave` ,`nombre` ,`apellido` ,`identificacion` ,`correo`  ,`estado`,`direccion`,`id_instancia`,`id_municipio`)
				VALUES ('".$usuario_ingreso."', '".md5($acceso)."', '".ucwords(strtolower(trim($_POST["nombre"])))."', '".ucwords(strtolower(trim($_POST["apellido"])))."', '".$_POST["identificacion"]."', '".trim($_POST["email"])."', '1', '".trim($_POST["direccion"])."' ,'".$_SESSION["nst"]."', '".$_POST["municipio"]."');";
		mysql_query($sql);
		if(!mysql_error())
		{
			$mensaje = "<b>Buenos dias</b><br><br>
			Usted ya tiene acceso a la plataforma <b>SIGC</b><br><br>
			URL: <b>173.254.57.157/~eduatlan/sigc</b><br>
			Usuario: <b>".$usuario_ingreso."</b><br>
			Clave: <b>".$acceso."</b><br>
			Instancia: <b>".$row_index["nombre_instancia"]."</b><br><br><br><br>			 
			 
			ATENTAMENTE, 
			Sistema S.I.G.C ";
			 
			 enviar_correo(trim($_POST["email"]), ucwords(strtolower(trim($_POST["nombre"]))), $mensaje, "Usuario para ingresar a SIGC", NULL);
			
			echo '<script >alert("Usuario ingresado correctamente!");</script>';
			echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista'>";
			die();
		
		}else
		{
			$error = 'ERROR BASE DE DATOS <br> '.mysql_error();
		}
		
	}
	
	
	
}

?>

<h2>INGRESAR NUEVO USUARIO </h2>

<div align=right>
	<a href="?cmp=lista"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de usuario </a>
</div>

<form action="?ingresar=1"  method="post" name="form" id="form">
 
	<table align="center">
		<tr>
			 <td>
			
				<div class=" form-group input-group input-group-lg" style="width:100%">
				  <span class="input-group-addon">Apellido</span>
				  <input  name="apellido" id="apellido" value="<?php echo $_POST["apellido"] ?>" type="text" size="50" class="form-control" placeholder="Apellido del usuario" required />
				</div>  
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Nombre</span>
				  <input name="nombre" id="nombre" value="<?php echo $_POST["nombre"] ?>"  type="text" class="form-control" placeholder="Nombre del usuario" required />
				</div> 
				
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Identificacion</span>
				  <input  name="identificacion" value="<?php echo $_POST["identificacion"] ?>" id="identificacion" type="number" class="form-control" placeholder="Identificacion del usuario" required />
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
						<option value="">Seleccione un municipio</option>							
					 </select>
				</div> 
			
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Direccion</span>
				  <input name="direccion" id="direccion" value="<?php echo $_POST["direccion"] ?>" type="text" class="form-control" placeholder="Direccion del usuario" required />
				</div> 
				
				<div class="form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">E - mail</span>
				  <input name="email" id="email" value="<?php echo $_POST["email"] ?>" required  type="email" class="form-control" placeholder="E-mail del usuario" />
				</div> 	

				<?php if($error){?>
					<div class="alert alert-info">
						<?php echo $error; ?>
					</div>
				<?php } ?>
				
				
				<center><input type="submit" class="btn btn-primary" value="Ingresar"></center>
				
				
			</td>
		</tr>
	</table>
</form>			
