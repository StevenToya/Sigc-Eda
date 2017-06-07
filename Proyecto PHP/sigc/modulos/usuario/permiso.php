<?php
if($PERMISOS_GC["usu_per"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_POST["guardar"])
{	
	$sql = "DELETE FROM `permiso` WHERE `id_usuario` = '".$_GET["id"]."' ; ";
	mysql_query($sql);
	
	 $sql="SELECT * FROM componente ORDER BY id ; ";
  	  $res = mysql_query($sql);
	  while($row = mysql_fetch_array($res))
	  {
	  		$var_tem = "mod_".$row["id"];			
			if($_POST[$var_tem])
			{				
				 $sql_mod="INSERT INTO permiso (id_usuario, id_componente) VALUES ('".$_GET["id"]."' , '".$row["id"]."'); ";
  	  			  mysql_query($sql_mod);
			}
	 }
	  
	  echo "<script>alert('Los accesos se guardaron correctamente')</script>";
	  echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=permiso&id=".$_GET["id"]."'>";
	  die();	
}

$sql_per = "SELECT  nombre, apellido FROM usuario WHERE id= '".$_GET["id"]."'  LIMIT 1 ";
$res_per = mysql_query($sql_per);
$row_per = mysql_fetch_array($res_per);

?> 
<h2>PERMISOS PARA <b><?php echo $row_per["apellido"]; ?> <?php echo $row_per["nombre"]; ?></b></h2>  
<div align=right>
<a href="?cmp=lista"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de usuario </a>
</div>
<form  method="post" action="?id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data"> 
<br>
<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" width="90%">
	<tr>
		<?php
			$cont = 1;
		  $sql="SELECT * FROM componente ORDER BY id ; ";
	  	  $res = mysql_query($sql);
		  while($row = mysql_fetch_array($res))
		  {
		  	
				if($cont> 4)
				{
					echo "</tr><tr>";
					$cont = 1;			
				}
				
				$sql_chec = "SELECT id FROM permiso WHERE id_usuario = '".$_GET["id"]."' AND id_componente = '".$row["id"]."'  LIMIT 1";
				$res_chec = mysql_query($sql_chec);
				$row_chec = mysql_fetch_array($res_chec);
				if($row_chec["id"]){$checked = ' checked="checked" '; $color_font = "green";}
				else{$checked = ''; $color_font = "";}
				
		?>
		      <td width="25%"><br>
		      	<input type="checkbox" <?php echo $checked; ?> name="mod_<?php echo $row["id"]; ?>" />
				<font color=<?php echo $color_font ?>> <?php echo $row["descripcion"]; ?>	</font>	 
<br>				
		      </td>
		
		
		<?php 
		   $cont ++;
		  }
		  
		   ?>
	</tr>

</table>

<center><br><input class="btn btn-primary" type="submit" value="Guardar accesos" name="guardar" /></center>
</form>