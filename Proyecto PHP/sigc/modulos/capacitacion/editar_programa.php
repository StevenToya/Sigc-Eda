<?php
if($PERMISOS_GC["pro_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


if($_POST["guardar"])
{		
	$sql = "UPDATE programa SET
	nombre = '".$_POST["nombre"]."',
	descripcion = '".$_POST["descripcion"]."',
	hora = '".$_POST["hora"]."'
	WHERE id = '".$_GET["id"]." LIMIT 1';";
	mysql_query($sql);
	
	$sql = "DELETE FROM `programa_cargo` WHERE `id_programa` = '".$_GET["id"]."' ; ";
	mysql_query($sql);
	
	 $sql="SELECT * FROM cargo ORDER BY id ; ";
	 $res = mysql_query($sql);
	 while($row = mysql_fetch_array($res))
	 {
		$var_tem = "mod_".$row["id"];			
		if($_POST[$var_tem])
		{				
			 $sql_mod="INSERT INTO programa_cargo (id_programa, id_cargo) VALUES ('".$_GET["id"]."' , '".$row["id"]."'); ";
			  mysql_query($sql_mod);
		}
	 }
	 echo "<script>alert('El programa se edito correctamente')</script>";
	 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_programa'>";
	 die();
	
			
}
$sql = "SELECT * FROM programa WHERE id = '".$_GET["id"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);

?> 
<h2>EDITAR PROGRAMA</h2>  
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
				  <input  name="nombre" value="<?php echo $row["nombre"] ?>" id="nombre" type="text" class="form-control" placeholder="Nombre del programa" required />
			</div> 
		</td>
		<td >
			<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Horas</span>
				  <input  name="hora" value="<?php echo $row["hora"] ?>" id="nombre" type="number" class="form-control" placeholder="Cantidad de horas" required />
			</div> 
		</td>
	</tr>
	
	<tr>
		<td colspan=2>
			<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Descripcion del programa</span>
				  <textarea  name="descripcion" id="descripcion" style="height:100px"  class="form-control" placeholder="En que consiste el programa" required ><?php echo $row["descripcion"] ?></textarea>
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
				
				$sql_chec = "SELECT id FROM programa_cargo WHERE id_programa = '".$_GET["id"]."' AND id_cargo = '".$row["id"]."'  LIMIT 1";
				$res_chec = mysql_query($sql_chec);
				$row_chec = mysql_fetch_array($res_chec);
				if($row_chec["id"]){$checked = ' checked="checked" '; $color_font = "green";}
				else{$checked = ''; $color_font = "";}
				
								
		?>
		   <td width="33%"><br>
			
			  
		      	<input type="checkbox" <?php echo $checked; ?>  name="mod_<?php echo $row["id"]; ?>" />
				 <font size="3" color=<?php echo $color_font ?>>  <?php echo $row["nombre"]; ?></font>
				
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