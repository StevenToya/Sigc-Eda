<?php
if($PERMISOS_GC["pro_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_POST["guardar"])
{		
	$sql = "INSERT INTO `programa` (`nombre` ,`descripcion` ,`hora`, `id_instancia`)
			VALUES ('".ucwords(strtolower(trim($_POST["nombre"])))."', '".trim($_POST["descripcion"])."', '".$_POST["hora"]."' ,'".$_SESSION["nst"]."');";
	mysql_query($sql);
	$id_pr = mysql_insert_id();
	if($id_pr)
	{
		 $sql="SELECT * FROM cargo ORDER BY id ; ";
		 $res = mysql_query($sql);
		 while($row = mysql_fetch_array($res))
		 {
			$var_tem = "mod_".$row["id"];			
			if($_POST[$var_tem])
			{				
				 $sql_mod="INSERT INTO programa_cargo (id_programa, id_cargo) VALUES ('".$id_pr."' , '".$row["id"]."'); ";
				  mysql_query($sql_mod);
			}
		 }
		 echo "<script>alert('El programa se guardo correctamente')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_programa'>";
		 die();
	}
	else
	{
		$error = "ERROR: No se pudo ingresar el programa en la base de datos";
	}
			
}


?> 
<h2>INGRESAR PROGRAMA</h2>  
<div align=right>
<a href="?cmp=lista_programa"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de prorgramas </a>
</div>
<form  method="post" action="?id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data"> 
<br>
<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" width="80%">

	<tr>
		<td >
			<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Nombre  </span>
				  <input  name="nombre" value="<?php echo $_POST["nombre"] ?>" id="nombre" type="text" class="form-control" placeholder="Nombre del programa" required />
			</div> 
		</td>
		<td >
			<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Horas</span>
				  <input  name="hora" value="<?php echo $_POST["hora"] ?>" id="nombre" type="number" class="form-control" placeholder="Cantidad de horas" required />
			</div> 
		</td>
	</tr>
	
	<tr>
		<td colspan=2>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Descripcion del programa</span>
				  <textarea  name="descripcion" value="<?php echo $_POST["descripcion"] ?>" id="descripcion" style="height:100px" class="form-control" placeholder="En que consiste el programa" required ></textarea>
			</div> 
		</td>
	</tr>

	<tr>
		<td colspan=3 align=center><span class="input-group-addon">Seleccione los cargos que aplican</span></td>
	</tr>
	<tr>
		<?php
			$cont = 1;
		  $sql="SELECT * FROM cargo ORDER BY nombre ; ";
	  	  $res = mysql_query($sql);
		  while($row = mysql_fetch_array($res))
		  {
		  	
				if($cont> 2)
				{
					echo "</tr><tr>";
					$cont = 1;			
				}
				
								
		?>
		   <td width="33%"><br>
			
			  
		      	<input type="checkbox"  name="mod_<?php echo $row["id"]; ?>" />
				 <font size="3"><?php echo $row["nombre"]; ?></font>
				
		   </td>
		
		
		<?php 
		   $cont ++;
		  }
		  
		   ?>
	</tr>

</table>

<center>
<?php if($error){?>
					<div class="alert alert-info">
						<?php echo $error; ?>
					</div>
				<?php } ?>
<br><input class="btn btn-primary" type="submit" value="Guardar programa" name="guardar" /></center>
</form>