<?php
/*
if($PERMISOS_GC["sto_coor"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}
*/
if($_POST["guardar"])
{

	$sql = "SELECT * FROM front_tecnico WHERE front_tecnico.id_usuario = '".$_SESSION["user_id"]."'
	ORDER BY front_tecnico.nombre ASC";
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		$tem_inst = "inst_".$row["id"];
		if($_POST[$tem_inst]){	$inst = 's'; }else{$inst = 'n';}
		
		$tem_rep = "rep_".$row["id"];
		if($_POST[$tem_rep]){	$rep = 's'; }else{$rep = 'n';}
		
		$tem_ret = "ret_".$row["id"];
		if($_POST[$tem_ret]){	$ret = 's'; }else{$ret = 'n';}
		
		$sql_act = "UPDATE `front_tecnico` SET 
		`instalacion` = '".$inst."', 
		`reparacion` = '".$rep."', 
		`retiro` = '".$ret."', 
		`fecha` = '".date("Y-m-d")."', 
		`fecha_registro` = '".date("Y-m-d G:i:s")."'				
		WHERE `id` = '".$row["id"]."' LIMIT 1 ;";
		mysql_query($sql_act);
		
	}

}

if($_GET["quit"])
{
	$sql_act = "UPDATE `front_tecnico` SET `id_usuario` =  NULL WHERE `id` = '".$_GET["quit"]."' LIMIT 1 ;";
		mysql_query($sql_act);
		
		 echo "<script>alert('El tecnico fue retirado de su lista')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?'>";
		 die();
}

if($_POST["agregar"])
{

	$_POST["cedula"] = limpiar_numero($_POST["cedula"]);	
	$_POST["codigo"] = limpiar_numero($_POST["codigo"]);	
	$_POST["nombre"] = limpiar($_POST["nombre"]);	
	
	$sql_bus = "SELECT id FROM front_tecnico 
	WHERE codigo = '".$_POST["codigo"]."' || cedula = '".$_POST["cedula"]."' 
	LIMIT 1";
	$res_bus = mysql_query($sql_bus);
	$row_bus = mysql_fetch_array($res_bus);
	
	if($row_bus["id"])
	{
		$sql_act = "UPDATE `front_tecnico` SET `id_usuario` = '".$_SESSION["user_id"]."' WHERE `id` = '".$row_bus["id"]."' LIMIT 1 ;";
		mysql_query($sql_act); 
		
		 echo "<script>alert('Este tecnico ya se encuentra en la base de datos, se le asigno a su lista de tecnicos')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?'>";
		 die();
	}
	else
	{
		if($_POST["cedula"] && $_POST["nombre"] && $_POST["codigo"])
		{
			$sql_ins = "INSERT INTO `front_tecnico` (`id_usuario`, `nombre`, `cedula`, `codigo`) 
			VALUES ('".$_SESSION["user_id"]."', '".$_POST["nombre"]."', '".$_POST["cedula"]."', '".$_POST["codigo"]."');";
			mysql_query($sql_ins);
			
			 echo "<script>alert('Tecnico creado y asignado a su lista')</script>";
			echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?'>";
			die();
		}
		else
		{
			 echo "<script>alert('ERROR, Debe llenar todos los campos para ingresar el tecnico')</script>";			
		}
	}
	
	

}

?>

<h2> SU PERSONAL ASIGNADO	 </h2>


<form action="?ing=1"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<table>
		<tr>
			<td><input type="number" value="<?php echo $_POST["codigo"]; ?>" class="form-control" name="codigo" placeholder="Ingresar codigo"> </td>
			<td><input type="number" value="<?php echo $_POST["cedula"]; ?>" class="form-control" name="cedula" placeholder="Ingresar cedula"> </td>
			<td><input type="text"  value="<?php echo $_POST["nombre"]; ?>" class="form-control" name="nombre" placeholder="Ingresar nombre-apellido"> </td>
			<td><input class="btn btn-primary" type="submit" value="Agregar tecnico" name="agregar" /> </td>
		</tr>
	</table>
</form>





<form  method="post" action="?" enctype="multipart/form-data"> 
<div class="panel panel-default"  style="width:99%">
	<div class="panel-heading" align=left>
		<b> Personal para gestionar </b>
	</div>
	<div class="panel-body" >
		<div class="table-responsive" >
		
			<table class="table table-striped table-bordered table-hover" >
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Cedula</th>
						<th>Instalaciones</th>
						<th>Reparacion</th>
						<th>Retiro </th>
						<th>Localidades</th>						
						<th>Fecha actualizacion</th>
						<th>Gst. Localidad</th>
						<th>Quitar</th>					
					</tr>
				</thead>
				<tbody>
				<?php
				$vec_tem = $vec_item; 
				$sql = "SELECT * FROM front_tecnico WHERE front_tecnico.id_usuario = '".$_SESSION["user_id"]."'
				ORDER BY front_tecnico.nombre ASC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{						
					if($row["instalacion"]=='s'){$instalacion = ' checked'; $instalacion_col = 'style="background-color:#A9F5A9" ';}else{$instalacion = ''; $instalacion_col = "";}
					if($row["reparacion"]=='s'){$reparacion = ' checked ';  $reparacion_col = ' style="background-color:#A9F5A9" '; }else{$reparacion = ''; $reparacion_col = "";}
					if($row["retiro"]=='s'){$retiro = ' checked ';  $retiro_col = ' style="background-color:#A9F5A9" '; }else{$retiro = '';  $retiro_col = ""; }
					
				?>
					<tr class="odd gradeX">
						<td> <?php echo $row["nombre"] ?> </td>
						<td><b> <?php echo $row["cedula"] ?> </b></td>
						<td  align=center <?php echo $instalacion_col ?>  ><input type='checkbox' style="width:20px;height:20px;" <?php echo $instalacion ?> name="inst_<?php echo $row["id"] ?>"></td>
						<td align=center <?php echo $reparacion_col ?> ><input type='checkbox' style="width:20px;height:20px;" <?php echo $reparacion ?> name="rep_<?php echo $row["id"] ?>"></td>
						<td align=center <?php echo $retiro_col ?> ><input type='checkbox' style="width:20px;height:20px;" <?php echo $retiro ?> name="ret_<?php echo $row["id"] ?>"></td>
						<td>
							<?php  $sql_cont = " SELECT front_tecnico_localidad.id_tecnico , front_localidad.nombre
									FROM front_tecnico_localidad
									INNER JOIN front_localidad 
										ON  front_tecnico_localidad.id_localidad = front_localidad.id
									WHERE
										front_tecnico_localidad.id_tecnico = '".$row["id"]."' 
									ORDER BY front_localidad.nombre ";
									$res_cont = mysql_query($sql_cont);
									while($row_cont = mysql_fetch_array($res_cont))
									{
							?>
									<?php echo $row_cont["nombre"]; ?>, 
							<?php
									}
							?>
						</td>	
						<td><center><?php echo $row["fecha_registro"] ?></center></td>
						<td><center><a href="?cmp=localidad&id=<?php echo $row["id"]; ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a><b></center></td>
						<td><center><a href="?quit=<?php echo $row["id"]; ?>"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a><b></center></td>
				   </tr>
				<?php
				}
				?>				   
				</tbody>
			</table>
		</div>		
	</div>
</div>

<center><br><input class="btn btn-primary" type="submit" value="Guardar configuracion" name="guardar" /></center>
</form>

