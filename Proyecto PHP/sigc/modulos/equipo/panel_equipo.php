<?php
 
function dias_diferencia($inicial, $final)
{
	$datetime1 = new DateTime($inicial);
	$datetime2 = new DateTime($final);
	$interval = $datetime1->diff($datetime2);
	return $interval->format('%a');	
}


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
				<th bgcolor=red>Serial</th>
				<th bgcolor=red>Equipo</th>
				<th bgcolor=red>Estado</th>
				<th bgcolor=red>OT</th>
			</tr>	
	
	<?php
	
	$query = '';
	
	
   if($_GET["id_localidad"])
   {
	   $query = "AND equipo_serial.id_localidad_carga  = '".$_GET["id_localidad"]."' ";	  
   }
	

	$sql = "SELECT equipo_serial.serial, equipo_material.nombre, equipo_serial.estado, tramite.ot
	FROM equipo_serial 
			INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id
			LEFT JOIN tramite ON equipo_serial.id_tramite = tramite.id
		WHERE 	equipo_serial.id_equipo_material = '".$_GET["id_equipo"]."' AND id_pedido = '".$_GET["id_pedido"]."' ".$query." ;";		
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		$estado = '';
		/*
		if(!$row["ot"]){$estado = '<font color=red>Sin ocupar</font>';}
		else{$estado = '<font color=green>Gestionado</font>';}
		*/
		if($row["estado"]==1){$estado = '<font color=red>Sin ocupar</font>';}
		else{$estado = '<font color=green>Gestionado</font>';}
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



if($_GET["excel2"])
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
				<th bgcolor=red>Localidad</th>
				<th bgcolor=red>Fecha pedido</th>
			</tr>	
	
	<?php
	
	if($_GET["id_localidad"])
	{	
		$query = " AND localidad.id = '".$_GET["id_localidad"]."' ";	
	}
  	

	$sql = "SELECT serial, equipo_material.nombre AS nom_equipo, localidad.id, pedido_equipo_material.numero, localidad.nombre AS nom_localidad,
			pedido_equipo_material.fecha
			 FROM equipo_serial
			 INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id
			 LEFT JOIN  pedido_equipo_material ON equipo_serial.id_pedido = pedido_equipo_material.id
			 INNER JOIN localidad ON equipo_serial.id_localidad_carga = localidad.id
			 WHERE equipo_serial.estado=1 ".$query." ;";		
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{
		$estado = '';
	
		?>
			<tr>
				<td><?php echo $row["serial"] ?></td>
				<td><?php echo $row["nom_equipo"] ?></td>
				<td>LIBRE</td>
				<td><?php echo $row["numero"]; ?></td>
				<td><?php echo $row["nom_localidad"]; ?></td>
				<td><?php echo $row["fecha"]; ?></td>
			</tr>
		
		<?php
		
	}
	?>
	</table>
	<?php
	die();
}

 if($PERMISOS_GC["equ_ges"]!='Si'){
	 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
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

  $query = '';
   if($_SESSION["id_localidad"])
   {
	   $query = "AND equipo_serial.id_localidad_carga  = '".$_SESSION["id_localidad"]."' ";
	   
	   $sql_localidad = "SELECT nombre FROM localidad WHERE id = '".$_SESSION["id_localidad"]."' LIMIT 1";
	   $res_localidad = mysql_query($sql_localidad);
	   $row_localidad = mysql_fetch_array($res_localidad);
	   
	   $localidad = '<font color=red><b>'.$row_localidad["nombre"].'</b></font>';
	   
   }
   else
	{
		$localidad = '<font color=red><b>TODAS LAS BODEGAS</b></font>';
	}
?>

<h2>ESTADO DE EQUIPOS EN PEDIDOS SIN FINALIZAR</h2>


<table width=100%>
	<tr>
		<td>
			<form action="?cmp=buscar_serial"  method="post" name="form" id="form"  enctype="multipart/form-data">
				<div class="row">
				   <div class="col-lg-6">
					<div class="input-group">
					  <input type="text" class="form-control" name="serial" placeholder="Ingresar serial...">
					  <span class="input-group-btn">
						<button class="btn btn-primary"  type="submit">Buscar</button>
					  </span>
					</div>
				  </div>
				</div>
			</form>
		</td>
			<?php
			$sql_r = "SELECT COUNT(*) AS cantidad FROM tramite	WHERE tramite.estado_liquidacion=2 AND tramite.contratista_equipo=3 ";
			$res_r = mysql_query($sql_r);
			$row_r = mysql_fetch_array($res_r);
			if($row_r["cantidad"]>0)
			{ $color_f="red";	}else{ $color_f="";	}
			?>
			<td  align=right>
				<a href="?cmp=rechazado"> <font color="<?php echo $color_f ?>"><i class="fa fa-exclamation-triangle fa-2x"></i></font> Tramites rechazados por equipos <b>( <?php echo $row_r["cantidad"]; ?> )</b></a>
			</td>
		<td align=right>			
			<a href="?cmp=lista_pedido"> <i class="fa fa-download fa-2x"></i> Gestion de numeros de pedidos y carga de seriales</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
</table>
<br>

<?php
	$sql_conteo = "SELECT SUM( IF(id_tramite IS NULL , 1, 0) ) AS  nulos
			 FROM equipo_serial
			 INNER JOIN localidad ON equipo_serial.id_localidad_carga = localidad.id
			  ;" ;
$res_conteo = mysql_query($sql_conteo);
$row_conteo = mysql_fetch_array($res_conteo);
if(!$_SESSION["id_localidad"]){$boton = 'primary';}else{$boton = 'info';}
?>
<a href="?xid_localidad=1">
		<button  class="btn btn-<?php echo $boton; ?>" type="button">
		 <font size=1px>TODAS <span class="badge"><font  color=red><?php echo $row_conteo["nulos"] ?></font></span></font>
		</button>
	</a>

<?php



$sql_conteo = "SELECT SUM( IF(id_tramite IS NULL , 1, 0) ) AS  nulos, localidad.nombre, localidad.id
			 FROM equipo_serial
			 INNER JOIN localidad ON equipo_serial.id_localidad_carga = localidad.id
			 GROUP BY localidad.id ;" ;
$res_conteo = mysql_query($sql_conteo);
while($row_conteo = mysql_fetch_array($res_conteo))
{
		if($row_conteo["nulos"]!=0)
		{
			if($_SESSION["id_localidad"]==$row_conteo["id"]){$boton = 'primary';}else{$boton = 'info';}
			
?> <a href="?id_localidad=<?php echo $row_conteo["id"] ?>">
		<button class="btn btn-<?php echo $boton; ?>" type="button">
		 <font size=1px><?php echo $row_conteo["nombre"] ?> <span class="badge"><font color=red><?php echo $row_conteo["nulos"] ?></font></span></font>
		</button>
	</a> 
<?php
		}
}
?>
<br><br>
<center>
<div class="panel panel-default" style="width:100%;">
  <div align=left class="panel-heading">
	<table width=100%>
	<tr>
		<td align=left>  Pedidos sin concluir en <?php echo $localidad; ?></td>
	
		
		<td align=right> 
			<a href="modulos/equipo/panel_equipo.php?excel2=1&id_localidad=<?php echo $_SESSION["id_localidad"] ?>" target=blank> <i class="fa fa-cloud-download fa-2x"></i> Exportar equipos libres</a>
		</td>
	</tr>
	</table>
 </div>
  <table class="table" align=center>
   <tr>
		<th> </th>
		<th>Pedido</th>
		<th>Bodega</th>
		<th>Fecha</th>
		<th>Equipo</th>		
		<th>Entregada</th>
		<th>Ocupados</th>
		<th>Libres</th>
		<th>Detalles</th>
		<th>Descarga</th>
		<th>Graficamente</th>
   </tr>
   <?php
    
   $sql_pedido = "SELECT pedido_equipo_material.id, pedido_equipo_material.numero, pedido_equipo_material.fecha 
   FROM pedido_equipo_material 
	INNER JOIN equipo_serial ON pedido_equipo_material.id = equipo_serial.id_pedido
   WHERE equipo_serial.id_tramite IS NULL ".$query." GROUP BY   pedido_equipo_material.fecha, pedido_equipo_material.id ";
   $res_pedido = mysql_query($sql_pedido);
   while($row_pedido = mysql_fetch_array($res_pedido))
   {
		/*
		$sql_conteo = "SELECT SUM( IF(id_tramite IS NULL , 1, 0) ) AS  nulos, 
							SUM( IF(id_tramite IS NOT NULL , 1, 0) ) AS  no_nulos,  
							equipo_material.nombre, equipo_material.id AS id_equipo, localidad.nombre AS nombre_bodega
					 FROM equipo_serial
					 INNER JOIN localidad ON equipo_serial.id_localidad_carga = localidad.id
					 INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id
					  WHERE equipo_serial.id_pedido= '".$row_pedido["id"]."' ".$query."  
					  GROUP BY id_equipo_material ;" ;
		*/
		
		$sql_conteo = "SELECT SUM( IF(equipo_serial.estado = 1 , 1, 0) ) AS  nulos, 
							SUM( IF(equipo_serial.estado = 1 , 0, 1) ) AS  no_nulos,  
							equipo_material.nombre, equipo_material.id AS id_equipo, localidad.nombre AS nombre_bodega
					 FROM equipo_serial
					 INNER JOIN localidad ON equipo_serial.id_localidad_carga = localidad.id
					 INNER JOIN equipo_material ON equipo_serial.id_equipo_material = equipo_material.id
					  WHERE equipo_serial.id_pedido= '".$row_pedido["id"]."' ".$query."  
					  GROUP BY id_equipo_material ;" ;
		
		
		$res_conteo = mysql_query($sql_conteo);
		while($row_conteo = mysql_fetch_array($res_conteo))
		{
			$entregada = $row_conteo["nulos"] + $row_conteo["no_nulos"];
			
			$positivo = ($row_conteo["no_nulos"] * 100)/$entregada;
			$negativo = ($row_conteo["nulos"] * 100)/$entregada;
			
			$color_aviso = '#FFFFFF';
			if(dias_diferencia($row_pedido["fecha"], date("Y-m-d"))>30){ $color_aviso = '#FFFF00';}
			if(dias_diferencia($row_pedido["fecha"], date("Y-m-d"))>40){ $color_aviso = '#FF0000';}
			
		?>
			<tr>
				<td><b><font color="<?php echo $color_aviso ?>"><i class="fa fa-exclamation-triangle fa-2x"> </i></font></b></td>
				<td><b><?php echo $row_pedido["numero"] ?></b></td>
				<td> <?php echo $row_conteo["nombre_bodega"] ?></td>
				<td><b><?php echo $row_pedido["fecha"] ?></b> </td>
				<td><?php echo $row_conteo["nombre"] ?></td>
				<td><?php echo $entregada; ?></td>
				<td><font color=green><b><?php echo  $row_conteo["no_nulos"]; ?></b></font></td>
				<td><font color=red><b><?php echo  $row_conteo["nulos"]; ?></b></font></td>
				<td align=center><a href="?cmp=lista_equipo&id_equipo=<?php echo $row_conteo["id_equipo"] ?>&id_pedido=<?php echo $row_pedido["id"] ?>"><i class="fa fa-search-plus fa-2x"></i></a></td>
				<td align=center><a href="modulos/equipo/panel_equipo.php?excel=1&id_equipo=<?php echo $row_conteo["id_equipo"] ?>&id_pedido=<?php echo $row_pedido["id"] ?>&id_localidad=<?php echo $_SESSION["id_localidad"] ?>" target=blank> <i class="fa fa-cloud-download fa-2x"></i> </a></td>
				<td>
					<div class="progress">
					  <div class="progress-bar progress-bar-success" style="width: <?php echo  $positivo ; ?>%">
						
					  </div>
					  <div class="progress-bar progress-bar-danger" style="width: <?php echo  $negativo; ?>%">
						
					  </div>
					</div>
				</td>
		   </tr>
		   <?php	
			
		}	   
	 
   }   
 
   
   
?>
   

  </table>
</div>
</center>

<br><br>