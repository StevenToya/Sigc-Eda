<?php

 if($PERMISOS_GC["mat_ges"]!='Si'){
	 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}  

function dias_diferencia($inicial, $final)
{
	$datetime1 = new DateTime($inicial);
	$datetime2 = new DateTime($final);
	$interval = $datetime1->diff($datetime2);
	return $interval->format('%a');	
}

if($_GET["xid_localidad"])
{
	$_SESSION["id_localidad"] = "";
	
}


if($_GET["id_localidad"])
{
	$_SESSION["id_localidad"] = $_GET["id_localidad"];
	
}


?>

<h2>ESTADO DE MATERIALES</h2>

<form action="?cmp=buscar_consumo"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<table width=100%>
		<tr>
			<td valign="middle">
					 <input  name="fecha_ini" value="<?php echo date("Y-m-d") ?>" id="fecha_ini" type="text" size="8" readonly /> 
					 <script type="text/javascript">
						var opts = {                            
						formElements:{"fecha_ini":"Y-ds-m-ds-d"},
						showWeeks:true,
						statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
						};      
						datePickerController.createDatePicker(opts);					
					</script>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					<input  name="fecha_fin" value="<?php echo date("Y-m-d") ?>" id="fecha_fin" type="text" size="8" readonly  /> 
					 <script type="text/javascript">
						var opts = {                            
						formElements:{"fecha_fin":"Y-ds-m-ds-d"},
						showWeeks:true,
						statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
						};      
						datePickerController.createDatePicker(opts);					
					</script>		
								
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary"  type="submit">Buscar consumos</button>	
			</td>
			<?php
			$sql_r = "SELECT COUNT(*) AS cantidad FROM tramite	WHERE tramite.estado_liquidacion=2 AND tramite.contratista_material=3 ";
			$res_r = mysql_query($sql_r);
			$row_r = mysql_fetch_array($res_r);
			if($row_r["cantidad"]>0)
			{ $color_f="red";	}else{ $color_f="";	}
			?>
			<td  align=right>
				<a href="?cmp=rechazado"> <font color="<?php echo $color_f ?>"><i class="fa fa-exclamation-triangle fa-2x"></i></font> Tramites rechazados por material <b>( <?php echo $row_r["cantidad"]; ?> )</b></a>
			</td>
			<td  align=right><a href="?cmp=lista_pedido"> <i class="fa fa-download fa-2x"></i> Gestion de numeros de pedidos y carga de seriales</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
	</table>
</form>
<br>
<?php
if(!$_SESSION["id_localidad"]){$boton = 'primary';}else{$boton = 'info';}
?>
<a href="?xid_localidad=1">
		<button  class="btn btn-<?php echo $boton; ?>" type="button">
		 <font size=1px>TODAS</font>
		</button>
	</a>

<?php

$sql_conteo = "SELECT  nom_localidad, total, codigo_1, cod_localidad
	FROM 
		(
				SELECT 
				equipo_material.nombre AS nom_material, localidad.nombre AS nom_localidad, equipo_material.codigo_1, localidad.id AS cod_localidad,
				SUM(IF (material_traza.tipo = 1 AND tipo_trabajo.tipo IN ('1','2'), material_traza.cantidad, 0) ) AS instalado,
				SUM(IF (material_traza.tipo = 2, material_traza.cantidad, 0) ) AS cargado , 
				SUM(IF (material_traza.tipo = 2, material_traza.cantidad, 0) )  - SUM(IF (material_traza.tipo = 1 AND tipo_trabajo.tipo IN ('1','2') AND codigo_unidad_operativa = '".$cuo."', material_traza.cantidad, 0) ) AS total
			FROM material_traza 
			INNER JOIN equipo_material ON  material_traza.id_equipo_material = equipo_material.id
			INNER JOIN localidad ON  material_traza.id_localidad_carga = localidad.id
			LEFT JOIN tramite ON material_traza.id_tramite = tramite.id 
			LEFT JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
			WHERE material_traza.id_localidad_carga IS NOT NULL  
			GROUP BY material_traza.id_localidad_carga
		) AS tabla_temporal
	WHERE total > 0 " ;
$res_conteo = mysql_query($sql_conteo);
while($row_conteo = mysql_fetch_array($res_conteo))
{
		
if($_SESSION["id_localidad"]==$row_conteo["cod_localidad"]){$boton = 'primary';}else{$boton = 'info';}
			
?> <a href="?id_localidad=<?php echo $row_conteo["cod_localidad"] ?>">
		<button class="btn btn-<?php echo $boton; ?>" type="button">
		 <font size=1px><?php echo $row_conteo["nom_localidad"] ?> </font>
		</button>
	</a> 
<?php
		
}
?>

<br><br>
<center>
<div class="panel panel-default" style="width:70%;">
   <div align=left class="panel-heading">Bodegas con existencia de materiales </div>
  <table class="table" align=center>
   <tr>
		<th> </th>
		<th>Bodega</th>
		<th>Material</th>	
		<th>Codigo</th>	
		<th>Cantidad</th>		
   </tr>
   <?php
   
	if($_SESSION["id_localidad"])
	{	$query = " AND localidad.id = '".$_SESSION["id_localidad"]."' ";}
	else
	{ $query = "";}
   
	//  AND codigo_unidad_operativa = '".$cuo."' 
	$sql_pedido = "SELECT nom_material, nom_localidad, total, codigo_1
	FROM 
		(
				SELECT 
				equipo_material.nombre AS nom_material, localidad.nombre AS nom_localidad, equipo_material.codigo_1,
				SUM(IF (material_traza.tipo = 1 AND tipo_trabajo.tipo IN ('1','2'), material_traza.cantidad, 0) ) AS instalado,
				SUM(IF (material_traza.tipo = 2, material_traza.cantidad, 0) ) AS cargado , 
				SUM(IF (material_traza.tipo = 2, material_traza.cantidad, 0) )  - SUM(IF (material_traza.tipo = 1 AND tipo_trabajo.tipo IN ('1','2') AND codigo_unidad_operativa = '".$cuo."', material_traza.cantidad, 0) ) AS total
			FROM material_traza 
			INNER JOIN equipo_material ON  material_traza.id_equipo_material = equipo_material.id
			INNER JOIN localidad ON  material_traza.id_localidad_carga = localidad.id
			LEFT JOIN tramite ON material_traza.id_tramite = tramite.id 
			LEFT JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
			WHERE material_traza.id_localidad_carga IS NOT NULL ".$query."
			GROUP BY material_traza.id_localidad_carga, material_traza.id_equipo_material
		) AS tabla_temporal
	WHERE total > 0 ";	  
   $res_pedido = mysql_query($sql_pedido);
   while($row_pedido = mysql_fetch_array($res_pedido))
   {	
	 ?>
			<tr>
				<td><b><!-- <font color="<?php echo $color_aviso ?>"><i class="fa fa-exclamation-triangle fa-2x"> </i></font> --></b></td>
				<td><b><?php echo $row_pedido["nom_localidad"] ?></b></td>
				<td><?php echo $row_pedido["nom_material"] ?></td>
				<td><?php echo $row_pedido["codigo_1"] ?></td>
				<td align=center><b><?php echo $row_pedido["total"] ?></b> </td>				
		   </tr>
		   <?php				   
	 
   }   
 
   
   
?>
   

  </table>
</div>
</center>
