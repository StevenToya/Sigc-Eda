<?php  
/*
if($PERMISOS_GC["liq_cont"]!='Si'){   
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}  
*/

if($_GET["excel"])
{
	session_start();
	include("../../cnx.php");		
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	if($_SESSION["tip_gar"]=='gar'){$query = " AND ttt.tipo_garantia IN ('1') ";}
	if($_SESSION["tip_gar"]=='rei'){$query = " AND ttt.tipo_garantia IN ('2','3') ";}
		
	$sql_cont = "SELECT COUNT(*) AS total 
					FROM
				(
					SELECT tecnico.nombre, tecnico.cedula, tramite.id_tecnico AS idt, COUNT( * ) AS cantidad_tramites,
						(
							SELECT COUNT(*) FROM tramite AS ttt
							INNER JOIN tipo_trabajo AS tptt ON  ttt.id_tipo_trabajo = tptt.id
							WHERE
								ttt.ultimo =  's'
								AND ttt.codigo_unidad_operativa =  '2000'
								AND ttt.fecha_atencion_orden >=  '".$_SESSION["cuar_fecha_ini"]." 00:00:00'
								AND ttt.fecha_atencion_orden <=  '".$_SESSION["cuar_fecha_fin"]." 23:59:59'
								AND ttt.id_tecnico = idt									
								AND datediff(ttt.fecha_atencion_orden,ttt.fecha_ot_antecesor)<31
								AND tptt.tipo=3 
								AND ttt.descripcion_dano  NOT LIKE  '%o masivo%' 
								AND ttt.descripcion_dano  NOT LIKE  '%Infundado%'
								".$query."
						) AS cantidad_reincidencia
						FROM tramite
						INNER JOIN tecnico ON  tramite.id_tecnico = tecnico.id
						INNER JOIN tipo_trabajo ON  tramite.id_tipo_trabajo = tipo_trabajo.id
						WHERE tramite.codigo_unidad_operativa =  '2000'
						AND ultimo =  's'
						AND fecha_atencion_orden >=  '".$_SESSION["cuar_fecha_ini"]." 00:00:00'
						AND fecha_atencion_orden <=  '".$_SESSION["cuar_fecha_fin"]." 23:59:59'
						AND descripcion_dano  NOT LIKE  '%o masivo%' 
						AND descripcion_dano  NOT LIKE  '%Infundado%'
						AND tipo_trabajo.tipo=3
						GROUP BY id_tecnico
				) AS temporal
				WHERE
				cantidad_reincidencia > 0 AND cantidad_tramites >= '".$_SESSION["cuar_min"]."' AND  cantidad_tramites <= '".$_SESSION["cuar_max"]."'
					";
		$res_cont = mysql_query($sql_cont);
		$row_cont = mysql_fetch_array($res_cont);
		$total = $row_cont["total"];
	
	?>
	<table align=center width=100%  border=1 >
		<tr>
			<th>Pto.</th>
			<th>Tecnico</th>
			<th>Cedula</th>
			<th>Can. tramites</th>
			<th>Can. reincidencia</th>
			<th>Promedio</th>			
		</tr>	
	
	<?php
	
	$uno = $total /4;
	$dos = $uno * 2;
	$tres = $uno * 3;
	
	$pto = 1;
	$sql = " SELECT 	nombre, idt, ( (cantidad_reincidencia * 100) / cantidad_tramites) AS pp, cantidad_tramites, cantidad_reincidencia, cedula
				FROM
				(
					SELECT tecnico.nombre, tecnico.cedula, tramite.id_tecnico AS idt, COUNT( * ) AS cantidad_tramites,
						(
							SELECT COUNT(*) FROM tramite AS ttt
							INNER JOIN tipo_trabajo AS tptt ON  ttt.id_tipo_trabajo = tptt.id
							WHERE
								ttt.ultimo =  's'
								AND ttt.descripcion_dano  NOT LIKE  '%o masivo%' 
								AND ttt.descripcion_dano  NOT LIKE  '%Infundado%'
								AND ttt.codigo_unidad_operativa =  '2000'
								AND ttt.fecha_atencion_orden >=  '".$_SESSION["cuar_fecha_ini"]." 00:00:00'
								AND ttt.fecha_atencion_orden <=  '".$_SESSION["cuar_fecha_fin"]." 23:59:59'
								AND ttt.id_tecnico = idt									
								AND datediff(ttt.fecha_atencion_orden,ttt.fecha_ot_antecesor)<31
								AND tptt.tipo=3
								".$query."
						) AS cantidad_reincidencia
						FROM tramite
						INNER JOIN tecnico ON  tramite.id_tecnico = tecnico.id
						INNER JOIN tipo_trabajo ON  tramite.id_tipo_trabajo = tipo_trabajo.id
						WHERE tramite.codigo_unidad_operativa =  '2000'
						AND ultimo =  's'
						AND fecha_atencion_orden >=  '".$_SESSION["cuar_fecha_ini"]." 00:00:00'
						AND fecha_atencion_orden <=  '".$_SESSION["cuar_fecha_fin"]." 23:59:59'
						AND descripcion_dano  NOT LIKE  '%o masivo%' 
						AND descripcion_dano  NOT LIKE  '%Infundado%'
						AND tipo_trabajo.tipo=3
						GROUP BY id_tecnico
				) AS temporal
				WHERE
				cantidad_reincidencia > 0 AND cantidad_tramites >= '".$_SESSION["cuar_min"]."' AND  cantidad_tramites <= '".$_SESSION["cuar_max"]."'
				ORDER BY pp ASC, cantidad_tramites DESC";
		$res = mysql_query($sql);
		while($row = mysql_fetch_array($res))
		{	$color = "";				
			if($pto <= $uno){$color = "#A9F5A9";}
			if($pto > $uno && $pto <= $dos){$color = "#F2F5A9";}
			if($pto > $dos && $pto <= $tres){$color = "#F5D0A9";}
			if($pto > $tres){$color = "#F78181";}
			?>
			<tr >
				<td bgcolor='<?php echo $color ; ?>'><b><?php echo $pto ?></b></td>					
				<td bgcolor='<?php echo $color ; ?>'><?php echo $row["nombre"] ?></td>
				<td bgcolor='<?php echo $color ; ?>'><?php echo $row["cedula"] ?></td>
				<td bgcolor='<?php echo $color ; ?>'><?php echo $row["cantidad_tramites"]; ?></td>
				<td bgcolor='<?php echo $color ; ?>'><?php echo $row["cantidad_reincidencia"]; ?></td>
				<td bgcolor='<?php echo $color ; ?>'><b><?php echo round($row["pp"],10); ?>  </b></td>					
			</tr>
			<?php
			$pto++;
		}							
		?>		
	</table>
	<?php
	die();
}


function mes($mes)
{
	if($mes == 1){return ' ENERO';}
	if($mes == 2){return ' FEBRERO';}
	if($mes == 3){return ' MARZO';}
	if($mes == 4){return ' ABRIL';}
	if($mes == 5){return ' MAYO';}
	if($mes == 6){return ' JUNIO';}
	if($mes == 7){return ' JULIO';}
	if($mes == 8){return ' AGOSTO';}
	if($mes == 9){return ' SEPTIEMBRE';}
	if($mes == 10){return ' OCTUBRE';}
	if($mes == 11){return ' NOVIEMBRE';}
	if($mes == 12){return ' DICIEMBRE';} 
}




?>	




<script type="text/javascript">


function popUpmensaje(URL) 
{
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1350,height=650,left = 200,top = 5');");
}

</script>


<?php

if($_GET["tip_gar"]){	$_SESSION["tip_gar"] = $_GET["tip_gar"];  } 

if(!$_SESSION["cuar_fecha_ini"]){$_SESSION["cuar_fecha_ini"] = date("Y-m-d"); }
if(!$_SESSION["cuar_fecha_fin"]){$_SESSION["cuar_fecha_fin"] = date("Y-m-d"); }
if(!$_SESSION["cuar_min"]){$_SESSION["cuar_min"] = 20; }
if(!$_SESSION["cuar_max"]){$_SESSION["cuar_max"] = 100; }
if(!$_SESSION["tip_gar"]){$_SESSION["tip_gar"] = 'rei'; }
?>



<h2>GENERADOR DE CUARTILES</h2>

<h5>
<center>
	<div class="col-md-100 col-sm-100" style="width:90%">
		<div class="panel panel-warning" >
			<div class="panel-heading"  align=left>
				<table width=100%>
					<tr>
						<td><b>Configuracion de la busqueda</b></td>
						<td align=right>  <a href="?cmp=cambio_busqueda"><i class="fa fa-pencil-square-o fa-2x"></i></td>
					</tr>
				</table>
			</div>
			<div class="panel-body">
				<table align=center  width=100%>
					<tr>
						<td align=right>Fecha inicial: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td ><b><?php echo $_SESSION["cuar_fecha_ini"]; ?></b></td>
						<td align=right>Fecha Final: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td ><b><?php echo $_SESSION["cuar_fecha_fin"]; ?></b></td>
						<td align=right>Min Tramite: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td ><b><?php echo $_SESSION["cuar_min"]; ?>	</b></td>
						<td align=right>Max Tramite: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td ><b><?php echo $_SESSION["cuar_max"]; ?>	</b></td>
					</tr>			
				
				</table>
			
				
			</div>
		</div>
	</div>
</h5>

</center>	

<script>
var select = document.getElementById('mySelect');
select.onchange = function () {
    select.className = this.options[this.selectedIndex].className;
}

</script>
<style>
.redText {
    background-color:#F00;
}
.greenText {
    background-color:#0F0;
}
.blueText {
    background-color:#00F;
}
</style>

	<?php
	
	if($_SESSION["tip_gar"]=='gar'){$query = " AND ttt.tipo_garantia IN ('1') ";}
	if($_SESSION["tip_gar"]=='rei'){$query = " AND ttt.tipo_garantia IN ('2','3') ";}
		
	$sql_cont = "SELECT COUNT(*) AS total 
					FROM
				(
					SELECT tecnico.nombre, tecnico.cedula, tramite.id_tecnico AS idt, COUNT( * ) AS cantidad_tramites,
						(
							SELECT COUNT(*) FROM tramite AS ttt
							INNER JOIN tipo_trabajo AS tptt ON  ttt.id_tipo_trabajo = tptt.id
							WHERE
								ttt.ultimo =  's'
								AND ttt.codigo_unidad_operativa =  '2000'
								AND ttt.fecha_atencion_orden >=  '".$_SESSION["cuar_fecha_ini"]." 00:00:00'
								AND ttt.fecha_atencion_orden <=  '".$_SESSION["cuar_fecha_fin"]." 23:59:59'
								AND ttt.id_tecnico = idt									
								AND datediff(ttt.fecha_atencion_orden,ttt.fecha_ot_antecesor)<31
								AND tptt.tipo=3 
								AND ttt.descripcion_dano  NOT LIKE  '%o masivo%' 
								AND ttt.descripcion_dano  NOT LIKE  '%Infundado%'
								".$query."
						) AS cantidad_reincidencia
						FROM tramite
						INNER JOIN tecnico ON  tramite.id_tecnico = tecnico.id
						INNER JOIN tipo_trabajo ON  tramite.id_tipo_trabajo = tipo_trabajo.id
						WHERE tramite.codigo_unidad_operativa =  '2000'
						AND ultimo =  's'
						AND fecha_atencion_orden >=  '".$_SESSION["cuar_fecha_ini"]." 00:00:00'
						AND fecha_atencion_orden <=  '".$_SESSION["cuar_fecha_fin"]." 23:59:59'
						AND descripcion_dano  NOT LIKE  '%o masivo%' 
						AND descripcion_dano  NOT LIKE  '%Infundado%'
						AND tipo_trabajo.tipo=3
						GROUP BY id_tecnico
				) AS temporal
				WHERE
				cantidad_reincidencia > 0 AND cantidad_tramites >= '".$_SESSION["cuar_min"]."' AND  cantidad_tramites <= '".$_SESSION["cuar_max"]."'
				 
					";
		$res_cont = mysql_query($sql_cont);
		$row_cont = mysql_fetch_array($res_cont);
		$total = $row_cont["total"];
	?>
	
<table width=100%>
	<tr>
		<td>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="?tip_gar=rei">
				<button class="btn btn-<?php if($_SESSION["tip_gar"]=='rei'){ ?>primary<?php }else{ ?>info<?php } ?>" type="button">
				 <font size=1px><b>REITERATIVO</b></font>
				</button>
			</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="?tip_gar=gar">
				<button class="btn btn-<?php if($_SESSION["tip_gar"]=='gar'){ ?>primary<?php }else{ ?>info<?php } ?>" type="button">
				 <font size=1px><b>GARANTIAS</b></font>
				</button>
			</a>
		</td>
		<td align=right>
			<a href="modulos/cuartiles/lista.php?excel=1" target=blank> <i class="fa fa-cloud-download fa-2x"></i> Exportar a Excel</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
</table>	
	
	<br><br>
	<table align=center width=100%  class="table  table-bordered table-hover" >
		<tr>
			<th>Pto.</th>
			<th>Tecnico</th>
			<th>Cedula</th>
			<th>Can. tramites</th>
			<th>Can. reincidencia</th>
			<th>Promedio</th>
			<th>Detalle</th>
		
		</tr>
		<?php
		$uno = $total /4;
		$dos = $uno * 2;
		$tres = $uno * 3;
		
		$pto = 1;
		$sql = " SELECT 	nombre, idt, ( (cantidad_reincidencia * 100) / cantidad_tramites) AS pp, cantidad_tramites, cantidad_reincidencia, cedula
				FROM
				(
					SELECT tecnico.nombre, tecnico.cedula, tramite.id_tecnico AS idt, COUNT( * ) AS cantidad_tramites,
						(
							SELECT COUNT(*) FROM tramite AS ttt
							INNER JOIN tipo_trabajo AS tptt ON  ttt.id_tipo_trabajo = tptt.id
							WHERE
								ttt.ultimo =  's'
								AND ttt.codigo_unidad_operativa =  '2000'
								AND ttt.fecha_atencion_orden >=  '".$_SESSION["cuar_fecha_ini"]." 00:00:00'
								AND ttt.fecha_atencion_orden <=  '".$_SESSION["cuar_fecha_fin"]." 23:59:59'
								AND ttt.id_tecnico = idt									
								AND datediff(ttt.fecha_atencion_orden,ttt.fecha_ot_antecesor)<31
								AND tptt.tipo=3 
								AND ttt.descripcion_dano  NOT LIKE  '%o masivo%' 
								AND ttt.descripcion_dano  NOT LIKE  '%Infundado%'
								".$query."
						) AS cantidad_reincidencia
						FROM tramite
						INNER JOIN tecnico ON  tramite.id_tecnico = tecnico.id
						INNER JOIN tipo_trabajo ON  tramite.id_tipo_trabajo = tipo_trabajo.id
						WHERE tramite.codigo_unidad_operativa =  '2000'
						AND ultimo =  's'
						AND fecha_atencion_orden >=  '".$_SESSION["cuar_fecha_ini"]." 00:00:00'
						AND fecha_atencion_orden <=  '".$_SESSION["cuar_fecha_fin"]." 23:59:59'
						AND descripcion_dano  NOT LIKE  '%o masivo%' 
						AND descripcion_dano  NOT LIKE  '%Infundado%'
						AND tipo_trabajo.tipo=3
						GROUP BY id_tecnico
				) AS temporal
				WHERE
				cantidad_reincidencia > 0 AND cantidad_tramites >= '".$_SESSION["cuar_min"]."' AND  cantidad_tramites <= '".$_SESSION["cuar_max"]."'
				ORDER BY pp ASC, cantidad_tramites DESC ";
			$res = mysql_query($sql);
			while($row = mysql_fetch_array($res))
			{	$color = "";				
				if($pto <= $uno){$color = "#A9F5A9";}
				if($pto > $uno && $pto <= $dos){$color = "#F2F5A9";}
				if($pto > $dos && $pto <= $tres){$color = "#F5D0A9";}
				if($pto > $tres){$color = "#F78181";}
				?>
				<tr bgcolor='<?php echo $color ; ?>'>
					<td><b><?php echo $pto ?></b></td>					
					<td><?php echo $row["nombre"] ?></td>
					<td><?php echo $row["cedula"] ?></td>
					<td><?php echo $row["cantidad_tramites"]; ?></td>
					<td><?php echo $row["cantidad_reincidencia"]; ?></td>
					<td><b><?php echo round($row["pp"],2); ?>  % </b></td>
					<td><a href="?cmp=tecnico&id=<?php echo $row["idt"]; ?>"><i class="fa fa-eye fa-2x"> </i></a></td>
				</tr>
				<?php
				$pto++;
			}							
			?>		
	</table>
		



