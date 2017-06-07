<?php
if($_GET["excel"]==1)
{
	include("../../cnx.php");		
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	?>
		<table border=1>
			<tr>
				<th bgcolor=red>Serial</th>
				<th bgcolor=red>Equipo</th>
				<th bgcolor=red>Estado</th>
				<th bgcolor=red>OT</th>
			</tr>	
	
	<?php
	
	$query = '';
	
	
   if($_GET["id_pedido"])
   {
	   $query = $query ." equipo_serial.id_pedido  = '".$_GET["id_pedido"]."' ";	  
   }
   
   
    if($_GET["id_equipo"])
   {
	   $query = $query ." AND equipo_serial.id_equipo_material = '".$_GET["id_equipo"]."'  ";	  
   }
	
	if($_GET["estado"]=='l')
	{		
		$query = $query .' AND equipo_serial.id_tramite IS NULL ';
	}
	
	if($_GET["estado"]=='o')
	{		
		$query = $query .' AND equipo_serial.id_tramite IS NOT NULL ';
	}

	$sql = "SELECT equipo_serial.serial, equipo_material.nombre, equipo_serial.estado, tramite.ot
	FROM equipo_serial 
			INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id
			LEFT JOIN tramite ON equipo_serial.id_tramite = tramite.id
		WHERE 	 ".$query." ;";		
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		$estado = '';
		if($row["estado"]==1){$estado = '<font color=red>Sin ocupar</font>';}
		if($row["estado"]==2){$estado = '<font color=green>Ocupado</font>';}
		?>
			<tr>
				<td><?php echo $row["serial"] ?></td>
				<td><?php echo $row["nombre"] ?></td>
				<td><?php echo $estado; ?></td>
				<td><?php echo $row["ot"]; ?></td>
			</tr>
		
		<?php
		
	}
	?>
	</table>
	<?php
	die();
}


if($_GET["excel"]==2)
{
	include("../../cnx.php");		
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	?>
		<table border=1>
			<tr>
				<th bgcolor=red>Serial</th>
				<th bgcolor=red>Equipo</th>
				<th bgcolor=red>Estado</th>
				<th bgcolor=red>Pedido</th>
			</tr>	
	
	<?php
	
	$query = '';
	
	$sql = "SELECT equipo_serial.serial, equipo_material.nombre, equipo_serial.estado, tramite.ot, pedido_equipo_material.numero
	FROM equipo_serial 
			INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id
			INNER JOIN pedido_equipo_material ON equipo_serial.id_pedido = pedido_equipo_material.id
			LEFT JOIN tramite ON equipo_serial.id_tramite = tramite.id
		WHERE 	pedido_equipo_material.fecha < '".$_GET["fi"]."' AND  equipo_serial.id_tramite IS NULL;";		
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		$estado = '';
		if($row["estado"]==1){$estado = '<font color=red>Sin ocupar</font>';}
		if($row["estado"]==2){$estado = '<font color=green>Ocupado</font>';}
		?>
			<tr>
				<td><?php echo $row["serial"] ?></td>
				<td><?php echo $row["nombre"] ?></td>
				<td><?php echo $estado; ?></td>
				<td><?php echo $row["numero"]; ?></td>
			</tr>
		
		<?php
		
	}
	?>
	</table>
	<?php
	die();
}
?>


<h2>CONCILIAR EQUIPOS</h2>

<table width=100%>
	<tr>
		<td>
		<?php
			 if(!$_POST["fi"]){$_POST["fi"] = date("Y-m-d"); } 
			 if(!$_POST["ff"]){$_POST["ff"] = date("Y-m-d"); } 
			 
			 $sql_pedido_ant = " SELECT COUNT(*) AS cantidad FROM  
					equipo_serial 
					LEFT JOIN pedido_equipo_material ON equipo_serial.id_pedido = pedido_equipo_material.id
				  WHERE pedido_equipo_material.fecha < '".$_POST["fi"]."' AND equipo_serial.id_tramite IS NULL ;" ;
			$res_pedido_ant = mysql_query($sql_pedido_ant);
			$row_pedido_ant = mysql_fetch_array($res_pedido_ant);
		
		?>
		
			<a href="modulos/equipo/panel_conciliacion.php?excel=2&fi=<?php echo $_POST["fi"]; ?>">
				<button  class="btn btn-warning" type="button">
				 <font size=1px>LIBRES EN PEDIDOS ANTERIORES <span class="badge"><font  color=red><?php echo $row_pedido_ant["cantidad"]; ?></font></span></font>
				</button>
			</a>
		</td>
		<td align=right>
			 <form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
			 
						
				Fecha inicial <input type="text" size='9' readonly  id="fi" name="fi"  value="<?php echo $_POST["fi"]; ?>" required />
				<script type="text/javascript">
					var opts = {                            
					formElements:{"fi":"Y-ds-m-ds-d"},
					showWeeks:true,
					statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
					};      
					datePickerController.createDatePicker(opts);					
				</script>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;				
				
				Fecha inicial <input type="text" size='9' readonly  id="ff" name="ff"  value="<?php echo $_POST["ff"]; ?>" required />
				<script type="text/javascript">
					var opts = {                            
					formElements:{"ff":"Y-ds-m-ds-d"},
					showWeeks:true,
					statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
					};      
					datePickerController.createDatePicker(opts);					
				</script>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" value="Buscar periodos"  name="guardar" class="btn btn-primary" />
			</form>
			
		</td>
	</tr>
</table>
	
<br><br>
<center>
<div class="panel panel-default" style="width:100%;">
   <div align=left class="panel-heading">Pedidos sin concluir entre  <font color=red><b><?php echo $_POST["fi"]; ?></b></font> hasta <font color=red><b><?php echo $_POST["ff"]; ?></b></font></div>
  <table class="table" border=1 align=center>
   <tr>
		<th rowspan=2><center>Pedido</center></th>
		<?php
		$seg_fila =''; $pos = 0;
		$sql_equ = "SELECT nombre, id FROM equipo_material WHERE id IN ('653','645','650','647','646','648','657') ORDER BY nombre";
		$res_equ = mysql_query($sql_equ);
		while($row_equ = mysql_fetch_array($res_equ))
		{
			?>
				<th colspan=3><center><?php echo $row_equ["nombre"] ?></center></th>				
			<?php
			$seg_fila = $seg_fila.'<th align=center>Entr.</th> <th align=center>Ocup</th> <th align=center>Libre</th>';
			$vec[$pos] = $row_equ["id"];
			$ini[$vec[$pos]]['e']='0';
			$ini[$vec[$pos]]['o']='0';
			$ini[$vec[$pos]]['l']='0';
			$total_libre[$vec[$pos]]='0';
			$pos++;
		}		
		?>		
		<th rowspan=2><center>Total libre</center></th>		
   </tr>
	<tr>
		<?php echo $seg_fila  ?>
	</th>
	
   <?php
   
	$tot_ent = $tot_ocu = $tot_lib = 0;
   $sql_pedido = "SELECT pedido_equipo_material.id, pedido_equipo_material.numero, pedido_equipo_material.fecha, localidad.nombre
   FROM pedido_equipo_material 	
      INNER JOIN localidad ON pedido_equipo_material.id_localidad = localidad.id
	  WHERE pedido_equipo_material.fecha >= '".$_POST["fi"]."'  AND  pedido_equipo_material.fecha <= '".$_POST["ff"]."' 
	  
   ";
   $res_pedido = mysql_query($sql_pedido);
   while($row_pedido = mysql_fetch_array($res_pedido))
   {		
		?>
			<tr>
				<td><b><?php echo $row_pedido["numero"] ?><br><?php echo $row_pedido["nombre"] ?></b></td>
		<?php
		$fin = $ini;$total = 0;
		$sql_conteo = "SELECT SUM( IF(id_tramite IS NULL , 1, 0) ) AS  nulos, 
							SUM( IF(id_tramite IS NOT NULL , 1, 0) ) AS  no_nulos,  
							equipo_material.nombre, equipo_material.id AS id_equipo
					 FROM  equipo_material 
					 LEFT JOIN equipo_serial ON equipo_material.id = equipo_serial.id_equipo_material
					  WHERE equipo_serial.id_pedido= '".$row_pedido["id"]."'  
					  GROUP BY id_equipo_material ;" ;
		$res_conteo = mysql_query($sql_conteo);
		while($row_conteo = mysql_fetch_array($res_conteo))
		{
			$entregada = $row_conteo["nulos"] + $row_conteo["no_nulos"];					
			$fin[$row_conteo["id_equipo"]]['e']=$entregada;
			$fin[$row_conteo["id_equipo"]]['o']=$row_conteo["no_nulos"];
			$fin[$row_conteo["id_equipo"]]['l']=$row_conteo["nulos"];  
			$total_libre[$row_conteo["id_equipo"]] = $total_libre[$row_conteo["id_equipo"]] + $row_conteo["nulos"];
		}
		$pos = 0; 
		while($vec[$pos])
		{
			$total  = $total  +  $fin[$vec[$pos]]['l'] ;
			?>
				<td><center><a href="modulos/equipo/panel_conciliacion.php?excel=1&id_pedido=<?php echo $row_pedido["id"] ?>&id_equipo=<?php echo $vec[$pos] ?>" target="blank"><b><?php echo $fin[$vec[$pos]]['e']; ?></b></a></center></td>
				<td><font color=green><center><a href="modulos/equipo/panel_conciliacion.php?excel=1&estado=o&id_pedido=<?php echo $row_pedido["id"] ?>&id_equipo=<?php echo $vec[$pos] ?>" target="blank"><b><?php echo  $fin[$vec[$pos]]['o']; ?></b></a></center></font></td>
				<td><font color=red><center><a href="modulos/equipo/panel_conciliacion.php?excel=1&	estado=l&id_pedido=<?php echo $row_pedido["id"] ?>&id_equipo=<?php echo $vec[$pos] ?>" target="blank"><b><?php echo  $fin[$vec[$pos]]['l']; ?></b></a></center></td>			
			<?php
			$pos++;
		}
		
		?>
			<td><center><font size=4 color=red><?php echo $total ?></font></center></td>
		</tr>
		<?php
   }
   ?>
   
   <tr>
		<th>Total libre</th>
		<?php
		$pos = 0; $tot_tot = 0;
		while($vec[$pos])
		{
			?>
			
			<td colspan=3>
				<center><font size=4 color=red><?php echo $total_libre[$vec[$pos]] ?></font></center>
			</td>
			<?php	
				$tot_tot = $tot_tot + $total_libre[$vec[$pos]];
				$pos++;
		}	
			?>
   
		<td><center><font size=4 color=red><?php echo $tot_tot ?></font></center></td>
   </tr>
   

  </table>
</div>
</center>

<br><br>