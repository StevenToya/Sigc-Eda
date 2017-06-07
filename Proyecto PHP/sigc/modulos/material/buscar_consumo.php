<?php
if($_GET["excel"])
{
	include("../../cnx.php");
		
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	?>
		<table border=1>
			<tr>
				<th bgcolor=red>OT</th>
				<th bgcolor=red>Localidad</th>
				<th bgcolor=red>Tipo de trabajo</th>
				<th bgcolor=red>Material</th>
				<th bgcolor=red>Fecha</th>
				<th bgcolor=red>Instalador</th>
				<th bgcolor=red>Cantidad</th>				
				
			</tr>	
	
	<?php
	
	//AND codigo_unidad_operativa = '".$_GET["cuo"]."'
	//AND  tipo_trabajo.tipo IN ('1','2') 
	$sql = "SELECT 
				equipo_material.nombre AS nom_material, localidad.nombre AS nom_localidad, equipo_material.codigo_1, material_traza.id_localidad_carga,
				material_traza.cantidad, tipo_trabajo.nombre AS nom_trabajo, tramite.fecha_atencion_orden,	tecnico.nombre AS nom_tecnico, tramite.ot
			FROM material_traza 
			INNER JOIN equipo_material ON  material_traza.id_equipo_material = equipo_material.id
			INNER JOIN localidad ON  material_traza.id_localidad = localidad.id			
			LEFT JOIN tramite ON material_traza.id_tramite = tramite.id 
			LEFT JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
			LEFT JOIN tecnico  ON tramite.id_tecnico = tecnico.id
			WHERE 	material_traza.id_localidad_carga IS NOT NULL  
					AND material_traza.tipo = 1 									
					AND tramite.fecha_atencion_orden >= '".$_GET["fi"]." 00:00:00'
					AND tramite.fecha_atencion_orden <= '".$_GET["ff"]." 23:59:59'
					AND material_traza.id_localidad_carga = '".$_GET["id_localidad"]."'
					AND material_traza.id_equipo_material = '".$_GET["id_material"]."'
			ORDER BY  tramite.fecha_atencion_orden ;";		
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		
		?>
			<tr>
				<td><?php echo $row["ot"] ?></td>
				<td><?php echo $row["nom_localidad"] ?></td>
				<td><?php echo $row["nom_trabajo"]; ?></td>
				<td><?php echo $row["nom_material"]; ?></td>
				<td><?php echo $row["fecha_atencion_orden"]; ?></td>
				<td><?php echo $row["nom_tecnico"]; ?></td>
				<td><?php echo $row["cantidad"]; ?></td>
			</tr>
		
		<?php
		
	}
	?>
	</table>
	<?php
	die();
}

if($_GET["xid_localidad"])
{
	$_SESSION["id_localidad"] = "";
	
}


if($_GET["id_localidad"])
{
	$_SESSION["id_localidad"] = $_GET["id_localidad"];
	
}

if($_POST["fecha_ini"]){	$_SESSION["sfi"] =  $_POST["fecha_ini"]; }
if($_POST["fecha_fin"]){	$_SESSION["sff"] =  $_POST["fecha_fin"]; }

if(!$_SESSION["sfi"]){$_SESSION["sfi"] = date("Y-m-d");}
if(!$_SESSION["sff"]){$_SESSION["sff"] = date("Y-m-d");}

function dias_diferencia($inicial, $final)
{
	$datetime1 = new DateTime($inicial);
	$datetime2 = new DateTime($final);
	$interval = $datetime1->diff($datetime2);
	return $interval->format('%a');	
}

?>

<h2>MATERIALES CONSUMIDOS DESDE <b><?php echo $_SESSION["sfi"] ?></b> HASTA <b><?php echo $_SESSION["sff"] ?></b></h2>

<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<table width=100%>
		<tr>
			<td valign="middle">
					 <input  name="fecha_ini" value="<?php echo $_SESSION["sfi"] ?>" id="fecha_ini" type="text" size="8" readonly /> 
					 <script type="text/javascript">
						var opts = {                            
						formElements:{"fecha_ini":"Y-ds-m-ds-d"},
						showWeeks:true,
						statusFormat:"l-cc-sp-d-sp-F-sp-Y"                    
						};      
						datePickerController.createDatePicker(opts);					
					</script>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					<input  name="fecha_fin" value="<?php echo $_SESSION["sff"] ?>" id="fecha_fin" type="text" size="8" readonly  /> 
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
			<td  align=right><a href="?cmp=panel_material"> <i class="fa fa-reply fa-2x"></i> Volver a las bodegas con existencia </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
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

// AND  tipo_trabajo.tipo IN ('1','2') 

$sql_conteo = "SELECT 
				equipo_material.nombre AS nom_material, localidad.nombre AS nom_localidad, equipo_material.codigo_1, material_traza.id_localidad_carga, 
				SUM( material_traza.cantidad) AS instalado, equipo_material.id AS id_material, id_localidad_carga AS cod_localidad		
			FROM material_traza 
			INNER JOIN equipo_material ON  material_traza.id_equipo_material = equipo_material.id
			INNER JOIN localidad ON  material_traza.id_localidad_carga = localidad.id
			LEFT JOIN tramite ON material_traza.id_tramite = tramite.id 
			LEFT JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
			WHERE 	material_traza.id_localidad_carga IS NOT NULL  
					AND material_traza.tipo = 1 
									
					AND tramite.fecha_atencion_orden >= '".$_SESSION["sfi"]." 00:00:00'
					AND tramite.fecha_atencion_orden <= '".$_SESSION["sff"]." 23:59:59'
			GROUP BY material_traza.id_localidad_carga " ;
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
<div class="panel panel-default" style="width:80%;">
   <div align=left class="panel-heading">Bodegas con su consumo </div>
  <table class="table" align=center>
   <tr>
		<th>Bodega</th>
		<th>Codigo</th>	
		<th>Material</th>
		<th><center>Cantidad <br> Cargada</center></th>
		<th><center>Cantidad <br> Consumida</center></th>	
		<th><center>Restante</center></th>	
		<th><center>Descargar <br> detalle</center></th>			
   </tr>
   <?php
   
   if($_SESSION["id_localidad"])
	{	$query = " AND material_traza.id_localidad_carga = '".$_SESSION["id_localidad"]."' ";}
	else
	{ $query = "";}
   
   
   //AND codigo_unidad_operativa = '".$cuo."'
   //AND  tipo_trabajo.tipo IN ('1','2') 
	$sql_pedido = "
	SELECT nom_localidad, codigo_1, nom_material,  instalado,  id_material, cod_localidad,
	(
		SELECT SUM(cantidad) FROM `material_traza` 
		INNER JOIN pedido_equipo_material ON material_traza.id_pedido = pedido_equipo_material.id
		WHERE id_pedido IS NOT NULL 
		AND pedido_equipo_material.fecha >= '".$_SESSION["sfi"]."' 
		AND pedido_equipo_material.fecha <= '".$_SESSION["sff"]."'
		AND material_traza.id_localidad_carga = cod_localidad
		AND material_traza.id_equipo_material = id_material
	) AS total_pedido
	FROM(
			SELECT 	equipo_material.nombre AS nom_material, localidad.nombre AS nom_localidad, equipo_material.codigo_1, material_traza.id_localidad_carga AS cod_localidad,
				SUM( material_traza.cantidad) AS instalado, equipo_material.id AS id_material				
			FROM material_traza 
			INNER JOIN equipo_material ON  material_traza.id_equipo_material = equipo_material.id
			INNER JOIN localidad ON  material_traza.id_localidad_carga = localidad.id
			LEFT JOIN tramite ON material_traza.id_tramite = tramite.id 
			LEFT JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
			WHERE 	material_traza.id_localidad_carga IS NOT NULL  
					AND material_traza.tipo = 1 
										
					AND tramite.fecha_atencion_orden >= '".$_SESSION["sfi"]." 00:00:00'
					AND tramite.fecha_atencion_orden <= '".$_SESSION["sff"]." 23:59:59'
					".$query."
			GROUP BY material_traza.id_localidad_carga, material_traza.id_equipo_material
	) AS tabla_temporal ";   
     
   $res_pedido = mysql_query($sql_pedido);
   while($row_pedido = mysql_fetch_array($res_pedido))
   {
		$restante = $row_pedido["total_pedido"] - $row_pedido["instalado"] ;
	 ?>
			<tr>
				<td><b><?php echo $row_pedido["nom_localidad"] ?></b></td>
				<td><?php echo $row_pedido["codigo_1"] ?></td>
				<td><?php echo $row_pedido["nom_material"] ?></td>			
				<td align=center><b><?php echo $row_pedido["total_pedido"] ?></b> </td>
				<td align=center><b><?php echo $row_pedido["instalado"] ?></b> </td>
				<td align=center><b><font color=red><?php echo $restante ?></font></b> </td>
				<td align=center><a href="modulos/material/buscar_consumo.php?excel=1&cuo=<?php echo $cuo; ?>&fi=<?php echo $_SESSION["sfi"] ?>&ff=<?php echo $_SESSION["sff"] ?>&id_localidad=<?php echo $row_pedido["cod_localidad"] ?>&id_material=<?php echo $row_pedido["id_material"] ?>" target=blank> <i class="fa fa-cloud-download fa-2x"></i> </a></td>
		   </tr>
		   <?php				   
	 
   }   
 
   
   
?>
  </table>
</div>
</center>


<br><br>
<center>
<div class="panel panel-default" style="width:70%;">
   <div align=left class="panel-heading"><font color=red>Bodegas sin ning&uacute;n tipo de consumo en el rango de fechas</font></div>
  <table class="table" align=center>
   <tr>
		<th>Bodega</th>
		<th>Codigo</th>	
		<th>Material</th>
		<th><center>Cantidad <br> Cargada</center></th>				
   </tr>
   <?php
   
   if($_SESSION["id_localidad"])
	{	$query = " AND material_traza.id_localidad_carga = '".$_SESSION["id_localidad"]."' ";}
	else
	{ $query = "";}
   
   
   //AND codigo_unidad_operativa = '".$cuo."'
	$sql_pedido = "	
	SELECT nom_localidad, codigo_1, nom_material, cantidad,	
	(	
		SELECT 
				SUM( material_traza.cantidad) 			
			FROM material_traza 
			INNER JOIN equipo_material ON  material_traza.id_equipo_material = equipo_material.id
			INNER JOIN localidad ON  material_traza.id_localidad_carga = localidad.id
			LEFT JOIN tramite ON material_traza.id_tramite = tramite.id 
			LEFT JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
			WHERE 	material_traza.id_localidad_carga IS NOT NULL  
					AND material_traza.tipo = 1 
					AND  tipo_trabajo.tipo IN ('1','2') 
					
					AND tramite.fecha_atencion_orden >= '".$_SESSION["sfi"]." 00:00:00'
					AND tramite.fecha_atencion_orden <= '".$_SESSION["sff"]." 23:59:59'
					AND material_traza.id_localidad_carga = cod_localidad
					AND material_traza.id_equipo_material = id_material
			GROUP BY material_traza.id_localidad_carga, material_traza.id_equipo_material
	
	
	) instalado
	
	FROM(
			SELECT SUM(cantidad) AS cantidad  , equipo_material.nombre AS nom_material, localidad.nombre AS nom_localidad, 
			equipo_material.codigo_1, localidad.id AS cod_localidad, equipo_material.id AS id_material
			FROM `material_traza`
			INNER JOIN pedido_equipo_material ON material_traza.id_pedido = pedido_equipo_material.id
			INNER JOIN equipo_material ON material_traza.id_equipo_material = equipo_material.id
			INNER JOIN localidad ON  material_traza.id_localidad_carga = localidad.id
			WHERE id_pedido IS NOT NULL 
			AND pedido_equipo_material.fecha >= '".$_SESSION["sfi"]."' 
			AND pedido_equipo_material.fecha <= '".$_SESSION["sff"]."'	
			".$query."
			GROUP BY id_equipo_material
	) AS tabla_temporal
		
		
	 ";   
     
   $res_pedido = mysql_query($sql_pedido);
   while($row_pedido = mysql_fetch_array($res_pedido))
   {
		if(!$row_pedido["instalado"]){
	 ?>
			<tr>
				<td><b><?php echo $row_pedido["nom_localidad"] ?></b></td>
				<td><?php echo $row_pedido["codigo_1"] ?></td>
				<td><?php echo $row_pedido["nom_material"] ?></td>			
				<td align=center><b><?php echo $row_pedido["cantidad"] ?></b>  </td>						
		   </tr>
		   <?php
		}
	 
   }   
 
   
   
?>



   

  </table>
</div>
</center>
