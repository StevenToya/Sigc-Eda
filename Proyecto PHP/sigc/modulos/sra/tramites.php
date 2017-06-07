<?php

if($_GET["sra_localidad"]){$_SESSION["sra_localidad"] = $_GET["sra_localidad"];}
if($_GET["nsra_localidad"]){$_SESSION["sra_localidad"] = "";}


if(!$_SESSION["sra_localidad"])
{
?>
	
		
<h2>TRAMITES POR LOCALIDAD</h2>
<div align=right>			
			<a href="modulos/sra/excel.php" target=blank> <i class="fa fa-cloud-download fa-2x"></i> Descargar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="?cmp=carga"> <i class="fa fa-download fa-2x"></i> Carga de tramites</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
	<br>
<center>
<div class="panel panel-default" style="width:70%;">
   <div align=left class="panel-heading">Mostrar todas la localidades pendientes</div>
  <table class="table" align=center>
   <tr>
		<th>ZONA</th>
		<th>LOCALIDAD</th>
		<th>TRAMITES</th>
		<th align=center>---</th>		
		
   </tr>
   <?php
   
   $sql = "SELECT zona, localidad, COUNT(*) AS cantidad 
   FROM sra_tramite WHERE estado=1 
   GROUP BY localidad ORDER BY zona, localidad ASC ";
   $res = mysql_query($sql);
   while($row = mysql_fetch_array($res))
   {			
		 ?>
		<tr>			
			<td> <?php echo $row["zona"] ?></td>
			<td> <?php echo $row["localidad"] ?></td>
			<td align=center> <?php echo $row["cantidad"] ?></td>
			<td align=center><a href="?sra_localidad=<?php echo $row["localidad"] ?>"> <i class="fa fa-eye fa-2x"> </i> </a></td></td>		
	   </tr>
	   <?php   
		 
	 
   }   
  
?>   
  </table>
</div>
</center>


<?php
}else{
?>


<h2>AGRUPAR TRAMITES</h2>
<br>
<div align=right>
<a href="?nsra_localidad=1"> <i class="fa fa-reply fa-2x"></i> Volver al listado de localidades</a>
</div>
<br>
<center>
<div class="panel panel-default" style="width:100%;">
   <div align=left class="panel-heading">Tramites de <b><?php echo $_SESSION["sra_localidad"]; ?></b></div>
  <table class="table" align=center>
   <tr>
		<th>PEDIDO</th>
		<th>F.H.</th>
		<th>DISTRIBUIDOR</th>
		<th>ARMARIO</th>		
		<th>CAJA</th>
		<th>DIRECCION</th>
		<th>BARRIO</th>
		<th>CLIENTE</th>
   </tr>
   <?php
   $line = ''; $linea2 = ''; $i=1;
   $sql = "SELECT * 
   FROM sra_tramite WHERE estado=1 AND  localidad = '".$_SESSION["sra_localidad"]."' AND distribuidor IS NOT NULL
   ORDER BY franja_horaria, CAST(distribuidor as unsigned) ASC, CAST(armario as unsigned), CAST(caja as unsigned) ASC ";
   $res = mysql_query($sql);
   while($row = mysql_fetch_array($res))
   {	
		if(!$line){$line = $row["distribuidor"];}
		if($line!=$row["distribuidor"])
		{
			$line =$row["distribuidor"];
			?>
			<tr><td bgcolor='#6E6E6E' align=center colspan=8></td></tr>
			<?php
		}
		
		if(!$linea2){$linea2 = $row["armario"]; if(($i % 2)==0){$color = "bgcolor = '#D8F6CE'";}else{$color = "bgcolor = '#FBF5EF'";} $i ++;}
		if($linea2!=$row["armario"])
		{
			$linea2 =$row["armario"];
			if(($i % 2)==0){$color = "bgcolor = '#D8F6CE'";}else{$color = "bgcolor = '#FBF5EF'";} $i ++;
		}
		
		$sql_si = "SELECT id FROM sra_tramite WHERE 
		distribuidor = '".$row["distribuidor"]."' AND
		armario = '".$row["armario"]."' AND
		caja = '".$row["caja"]."' AND
		id != '".$row["id"]."' AND estado=1 AND  localidad = '".$_SESSION["sra_localidad"]."' AND distribuidor IS NOT NULL
		LIMIT 1	";
		$res_si = mysql_query($sql_si);
		$row_si = mysql_fetch_array($res_si);
		if($row_si["id"]){$igual = '<font color=#0000FF><i class="fa fa-map-marker fa-2x"></i>';}else{$igual = '';}
		
			 ?>
			<tr <?php echo $color; ?>>
				<td><b><?php echo $row["pedido"] ?></b></td>
				<td><b><?php echo $row["franja_horaria"] ?></b></td>
				<td> <?php echo $row["distribuidor"] ?></td>
				<td> <?php echo $row["armario"] ?></td>
				<td valign=top> <?php echo $row["caja"] ?> <?php echo $igual; ?></td>
				<td> <?php echo $row["direccion"] ?></td>
				<td> <?php echo $row["barrio"] ?></td>			
				<td> <?php echo $row["cliente"] ?></td>			
		   </tr>
		   <?php   
		 
	 
   }   
  
?>   
  </table>
</div>
</center>

<br>

<center>
<div class="panel panel-default" style="width:100%;">
   <div align=left class="panel-heading">Tramites de <b><?php echo $_SESSION["sra_localidad"]; ?> <font color=red>SIN DISTRIBUIDOR</b></font></div>
  <table class="table" align=center>
   <tr>
		<th>PEDIDO</th>
		<th>ARMARIO</th>		
		<th>CAJA</th>
		<th>DIRECCION</th>
		<th>BARRIO</th>
		<th>CLIENTE</th>
   </tr>
   <?php
   $linea2 = ''; $i=1;
   $sql = "SELECT * 
   FROM sra_tramite WHERE estado=1 AND  localidad = '".$_SESSION["sra_localidad"]."' AND distribuidor IS NULL
   ORDER BY barrio ASC, CAST(armario as unsigned), CAST(caja as unsigned) ASC ";
   $res = mysql_query($sql);
   while($row = mysql_fetch_array($res))
   {	
				
		if(!$linea2){$linea2 = $row["barrio"]; if(($i % 2)==0){$color = "bgcolor = '#D8F6CE'";}else{$color = "bgcolor = '#FBF5EF'";} $i ++;}
		if($linea2!=$row["barrio"])
		{
			$linea2 =$row["barrio"];
			if(($i % 2)==0){$color = "bgcolor = '#D8F6CE'";}else{$color = "bgcolor = '#FBF5EF'";} $i ++;
		}
		
			
			 ?>
			<tr <?php echo $color; ?>>
				<td><b><?php echo $row["pedido"] ?></b></td>
				<td> <?php echo $row["barrio"] ?></td>	
				<td> <?php echo $row["armario"] ?></td>
				<td> <?php echo $row["caja"] ?></td>
				<td> <?php echo $row["direccion"] ?></td>						
				<td> <?php echo $row["cliente"] ?></td>			
		   </tr>
		   <?php   
		 
	 
   }   
  
?>   
  </table>
</div>
</center>




<?php
}
?>
