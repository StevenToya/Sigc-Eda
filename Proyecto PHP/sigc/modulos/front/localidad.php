<?php
/*
if($PERMISOS_GC["usu_per"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}
*/

if($_POST["guardar"])
{	
	$sql = "DELETE FROM `front_tecnico_localidad` WHERE `id_tecnico` = '".$_GET["id"]."' ; ";
	mysql_query($sql);
	
	 $sql="SELECT * FROM front_localidad WHERE estado=1 ORDER BY id ; ";
  	  $res = mysql_query($sql);
	  while($row = mysql_fetch_array($res))
	  {
	  		$var_tem = "mod_".$row["id"];			
			if($_POST[$var_tem])
			{				
				 $sql_mod="INSERT INTO front_tecnico_localidad (id_tecnico, id_localidad) VALUES ('".$_GET["id"]."' , '".$row["id"]."'); ";
  	  			  mysql_query($sql_mod);
			}
	 }
	  
	  echo "<script>alert('Las localidades se guardaron correctamente')</script>";
	  echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=localidad&id=".$_GET["id"]."'>";
	  die();	
}

$sql_tec = "SELECT  nombre FROM front_tecnico WHERE id= '".$_GET["id"]."'  LIMIT 1 ";
$res_tec = mysql_query($sql_tec);
$row_tec = mysql_fetch_array($res_tec);

?> 
<h2>LOCALIDADES PARA <b><?php echo $row_tec["nombre"]; ?></b></h2>  
<div align=right>
<a href="?cmp=lista_tupersonal"> <i class="fa fa-reply fa-2x"></i> Volver al listado de tecnicos </a>
</div>
<form  method="post" action="?id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data"> 
<br>
<table  cellpadding="5" cellspacing="5" id="tabla" align ="center" width="95%">
	<tr>
		<?php
			$cont = 1;
		  $sql="SELECT * FROM front_localidad WHERE estado=1 ORDER BY nombre  ; ";
	  	  $res = mysql_query($sql);
		  while($row = mysql_fetch_array($res))
		  {
		  	
				if($cont> 4)
				{
					echo "</tr><tr>";
					$cont = 1;			
				}
				
				$sql_chec = "SELECT id FROM front_tecnico_localidad WHERE 	id_tecnico = '".$_GET["id"]."' AND id_localidad = '".$row["id"]."'  LIMIT 1";
				$res_chec = mysql_query($sql_chec);
				$row_chec = mysql_fetch_array($res_chec);
				if($row_chec["id"]){$checked = ' checked="checked" '; $color_font = "green";}
				else{$checked = ''; $color_font = "";}
				
		?>
		      <td width="25%"><br>
		      	<input type="checkbox" style="width:20px;height:20px;" <?php echo $checked; ?> name="mod_<?php echo $row["id"]; ?>" />
				<font color=<?php echo $color_font ?>> <?php echo $row["nombre"]; ?>	</font>	 
<br>				
		      </td>
		
		
		<?php 
		   $cont ++;
		  }
		  
		   ?>
	</tr>

</table>

<center><br><input class="btn btn-primary" type="submit" value="Guardar Localidades" name="guardar" /></center>
</form>