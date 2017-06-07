<?php
	session_start();
	include("../../cnx.php");
	include("../../query.php");
	
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0"); 

	?>
		<table border=1>
			<tr>
				<th>ORDEN</th>
				<th>F.H.</th>
				<th>LOCALIDAD</th>
				<th>ZONA</th>
				
				<th>PEDIDO</th>
				<th>DISTRIBUIDOR</th>
				<th>ARMARIO</th>		
				<th>CAJA</th>
				<th>DIRECCION</th>
				<th>BARRIO</th>
				<th>CLIENTE</th>		
				<th>TIPO DE TRABAJO</th>	
				<th>TECNOLOGIA</th>
				<th>CLASE DE SERVICIO</th>
				<th>TIPO CLIENTE</th>
				<th>FECHA CITA</th>
				<th>AGENDADOR</th>
				<th>OBSERVACION</th>
				
				
			</tr>		
	<?php
	
	$sql_localidad = "SELECT zona, localidad, COUNT(*) AS cantidad 
   FROM sra_tramite WHERE estado=1 
   GROUP BY localidad ORDER BY zona, localidad ASC ";
   $res_localidad = mysql_query($sql_localidad);
   while($row_localidad = mysql_fetch_array($res_localidad))
   {
		   $line = ''; $linea2 = ''; $i=1; $cont_linea = 1; $fh = '';
		   $sql = "SELECT * , 
		   IF(distribuidor IS NULL,'99999999999', distribuidor) AS distribuidor2	   
		   FROM sra_tramite WHERE estado=1 AND  localidad = '".$row_localidad["localidad"]."' 
		   ORDER BY franja_horaria, orden ASC, CAST(distribuidor2 as unsigned) ASC, CAST(armario as unsigned), CAST(caja as unsigned) ASC ";
		   $res = mysql_query($sql);
		   while($row = mysql_fetch_array($res))
		   {	
				if(!$line){$line = $row["distribuidor2"];}
				if($line!=$row["distribuidor2"])
				{	$line =$row["distribuidor2"];	}
			
				if(!$fh){$fh = $row["franja_horaria"];}
				if($fh!=$row["franja_horaria"])
				{	$cont_linea = 1; $fh =$row["franja_horaria"];	}
				
				if(!$linea2){$linea2 = $row["armario"]; if(($i % 2)==0){$color = "bgcolor = '#D8F6CE'";}else{$color = "bgcolor = '#FBF5EF'";} $i ++;}
				if($linea2!=$row["armario"])
				{
					$linea2 =$row["armario"];
					if(($i % 2)==0){$color = "bgcolor = '#D8F6CE'";}else{$color = "bgcolor = '#FBF5EF'";} $i ++;
				}
				
				$sql_si = "SELECT id FROM sra_tramite WHERE 
				distribuidor = '".$row["distribuidor2"]."' AND
				armario = '".$row["armario"]."' AND
				caja = '".$row["caja"]."' AND
				id != '".$row["id"]."' AND estado=1 AND  localidad = '".$row_localidad["localidad"]."' AND distribuidor IS NOT NULL
				LIMIT 1	";
				$res_si = mysql_query($sql_si);
				$row_si = mysql_fetch_array($res_si);
				if($row_si["id"]){$igual = '*';}else{$igual = '';}
				
				if($row["distribuidor2"]=='99999999999')
				{	$color_cont = "red"; $row["distribuidor2"]= '';		}
				else
				{$color_cont = "";	}
			
				$color_ampm = '';
				if($row["franja_horaria"]=='AM'){$color_ampm = '#FFBF00';}
				if($row["franja_horaria"]=='PM'){$color_ampm = '#08088A';}
				
				if($row["tipo_cliente"]=='20 - PYMES' || $row["tipo_cliente"]=='10 - GRANDES CLIENTES')
				{
					$tipo_cliente_color = ' bgcolor=yellow ';
				}else
				{
					$tipo_cliente_color = '';
				}	
				
				
					 ?>
					<tr <?php echo $color; ?>>
						<td <?php echo $tipo_cliente_color ?> align=center><b><font size="11" color='<?php echo $color_cont ?>' ><?php echo $cont_linea ?></font></b></td>
						<td> <font color='<?php echo $color_ampm ?>' > <b><?php echo $row["franja_horaria"] ?></b></font></td>	
						<td> <?php echo $row["localidad"] ?></td>	
						<td> <?php echo $row["zona"] ?></td>	
						
						<td><b><?php echo $row["pedido"] ?></b></td>
						<td> <?php echo $row["distribuidor2"] ?></td>
						<td> <?php echo $row["armario"] ?></td>
						<td> <?php echo $row["caja"] ?> <?php echo $igual; ?></td>
						<td> <?php echo $row["direccion"] ?></td>
						<td> <?php echo $row["barrio"] ?></td>			
						<td> <?php echo $row["cliente"] ?></td>	
							<td> <?php echo $row["tipo_trabajo"] ?></td>	
						<td> <?php echo $row["tecnologia"] ?></td>	
						<td> <?php echo $row["clase_servicio"] ?></td>	
						<td> <?php echo $row["tipo_cliente"] ?></td>	
						<td> <?php echo $row["fecha_cita"] ?></td>	
						
						<td> <?php echo $row["agendador"] ?></td>	
						<td> <?php echo $row["observacion"] ?></td>							
				   </tr>
				   <?php   
				 $cont_linea ++;
			 
		   }
				?>
				<tr bgcolor="#6E6E6E">
					<td>-</td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td> 	
				
				</tr>
			<?php
			
	}
?>
</table>