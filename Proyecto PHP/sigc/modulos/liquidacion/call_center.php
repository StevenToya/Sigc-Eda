<?php 

if($_GET["excel"])
{
	include("../../cnx.php");
	include("../../query.php");
		
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte_call_center.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	function moneda($dato)
{	return number_format($dato, 2, ',', '.'); }
	
?>	 

<table border=1>
   <tr>
		<th>OT Call CENTER</th>
		<th>F.A.O Call Center</th>		
		<th>OT EIA</th>		
		<th>F.A.O EIA</th>	
		<th>Tipo de trabajo</th>	
		<th>Valor Automatico</th>				
   </tr>

   <?php
   
	
    $sql = "SELECT 
			tramite.ot AS ot_callcenter, tramite.fecha_atencion_orden AS fecha_callcenter, tramite_antecesor.ot AS ot_eia , tramite.id_tipo_trabajo,  
			tramite_antecesor.fecha_atencion_orden AS fecha_eia, tramite.id, tipo_trabajo.nombre AS nom_tipo_trabajo, tramite_antecesor.id AS id_antecesor
			FROM tramite
			INNER JOIN tramite tramite_antecesor 
				ON tramite.ot_antecesor = tramite_antecesor.id
			INNER JOIN tipo_trabajo
				ON tramite_antecesor .id_tipo_trabajo = tipo_trabajo.id
			WHERE tramite.codigo_unidad_operativa =  '253'
			AND DATEDIFF( tramite.fecha_atencion_orden, tramite.fecha_ot_antecesor ) <31
			AND tramite_antecesor.codigo_unidad_operativa =2000 
			AND tramite.ultimo='s'
			AND tipo_trabajo.tipo = 3
			AND tramite.fecha_atencion_orden >= '".$_GET["ano_ges"]."-".$_GET["mm"]."-01 00:00:00' 
			AND tramite.fecha_atencion_orden <= '".$_GET["ano_ges"]."-".$_GET["mm"]."-31 23:59:59'  
			AND tramite_antecesor.descripcion_dano NOT LIKE  '%fundado%'
			AND tramite_antecesor.descripcion_dano NOT LIKE  '%o masivo%'
			GROUP BY tramite_antecesor.id ;";
   $res = mysql_query($sql);
   while($row = mysql_fetch_array($res))
   {
	$res_valor = tramite_liquidacion_individual($row["id_antecesor"]);
	$row_valor = mysql_fetch_array($res_valor);
	?>
			<tr>
				<td><?php echo $row["ot_callcenter"] ?> </td>
				<td><?php echo $row["fecha_callcenter"] ?></td>
				<td><?php echo $row["ot_eia"] ?></td>
				<td><?php echo $row["fecha_eia"] ?></td>
				<td><?php echo $row["nom_tipo_trabajo"] ?> </td>
				<td align=right> <b>$ <?php echo moneda($row_valor["total_total"]) ?></b></td>			
								
		   </tr>
		   <?php 
	 
   }     
?>
  </table>
  <?php
  die();  
}




 
if($PERMISOS_GC["liq_ges"]!='Si'){   
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
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





if($_GET["anog"])
{
	$ano_ges =  $_GET["anog"];
}
else
{
	$ano_ges = date("Y");
}

if($_GET["mesg"])
{
	$mes_ges = $_GET["mesg"];
}
else
{
	$mes_ges = date("m");	
}

if($mes_ges==1)
{
	$mes_s = $mes_ges + 1;
	$ano_s = $ano_ges ;
	$mes_a = 12;
	$ano_a = $ano_ges - 1 ;
}
else
{
	if($mes_ges ==12)
	{
		$mes_s = 1 ;
		$ano_s = $ano_ges + 1;
		$mes_a = $mes_ges - 1;
		$ano_a = $ano_ges  ;
	}
	else
	{
		$ano_a = $ano_ges ;
		$ano_s = $ano_ges ;
		$mes_a = $mes_ges - 1;
		$mes_s = $mes_ges + 1;
	}
}
if($mes_ges < 9){$mm = '0'.$mes_ges; }else{$mm = $mes_ges;}

?>


<script type="text/javascript">


function popUpmensaje(URL) 
{
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1350,height=650,left = 200,top = 5');");
}

</script>


<h2>DESCUENTOS POR INTERVENCION DE CALL CENTER</h2>


<table width="100%">
	<tr>

		<td align=center>
			<h5>
			<a href="?mesg=<?php echo $mes_a;?>"><i class="fa fa-arrow-left"></i> Mes anterior</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo mes($mes_ges); ?></b> DEL <b><?php echo $ano_ges; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="?mesg=<?php echo $mes_s; ?>">Mes siguiente <i class="fa fa-arrow-right"></i></a>
			</h5>
		</td>
		
	</tr>
</table>

<div align=right>
<a href="modulos/liquidacion/call_center.php?excel=s&mm=<?php echo $mm ?>&ano_ges=<?php echo $ano_ges; ?>" target=blank> Descargar informe </a>
</div>

<br>
<center>
<div class="panel panel-default" style="width:100%;">
   <div align=left class="panel-heading">Pedidos sin concluir la liquidacion</div>
  <table align=center class="table table-striped table-bordered table-hover" id="dataTables-example">
  <thead>
   <tr>
		<th>OT Call CENTER</th>
		<th>F.A.O Call Center</th>		
		<th>OT EIA</th>		
		<th>F.A.O EIA</th>	
		<th>Tipo de trabajo</th>	
		<th>Valor Automatico</th>	
		<th>Detalle</th>	
   </tr>
   </thead>
   <tbody>
   <?php
   
	
    $sql = "SELECT 
			tramite.ot AS ot_callcenter, tramite.fecha_atencion_orden AS fecha_callcenter, tramite_antecesor.ot AS ot_eia , tramite.id_tipo_trabajo,  
			tramite_antecesor.fecha_atencion_orden AS fecha_eia, tramite.id, tipo_trabajo.nombre AS nom_tipo_trabajo, tramite_antecesor.id AS id_antecesor
			FROM tramite
			INNER JOIN tramite tramite_antecesor 
				ON tramite.ot_antecesor = tramite_antecesor.id
			INNER JOIN tipo_trabajo
				ON tramite_antecesor .id_tipo_trabajo = tipo_trabajo.id
			WHERE tramite.codigo_unidad_operativa =  '253'
			AND DATEDIFF( tramite.fecha_atencion_orden, tramite.fecha_ot_antecesor ) <31
			AND tramite_antecesor.codigo_unidad_operativa =2000 
			AND tramite.ultimo='s'
			AND tipo_trabajo.tipo = 3
			AND tramite.fecha_atencion_orden >= '".$ano_ges."-".$mm."-01 00:00:00' 
			AND tramite.fecha_atencion_orden <= '".$ano_ges."-".$mm."-31 23:59:59'  
			AND tramite_antecesor.descripcion_dano NOT LIKE  '%fundado%'
			AND tramite_antecesor.descripcion_dano NOT LIKE  '%o masivo%'
			GROUP BY tramite_antecesor.id ;";
   $res = mysql_query($sql);
   while($row = mysql_fetch_array($res))
   {
	$res_valor = tramite_liquidacion_individual($row["id_antecesor"]);
	$row_valor = mysql_fetch_array($res_valor);
	?>
			<tr>
				<td><?php echo $row["ot_callcenter"] ?> </td>
				<td><?php echo $row["fecha_callcenter"] ?></td>
				<td><?php echo $row["ot_eia"] ?></td>
				<td><?php echo $row["fecha_eia"] ?></td>
				<td><?php echo $row["nom_tipo_trabajo"] ?> </td>
				<td align=right> <b>$ <?php echo moneda($row_valor["total_total"]) ?></b></td>				
				<td align=center><a href="javascript:popUpmensaje('modulos/liquidacion/detalle.php?id=<?php echo $row["id"]; ?>')"> <i class="fa fa-eye fa-2x"> </i> </a></td>				
		   </tr>
		   <?php 
	 
   }     
?>
   </tbody>

  </table>
</div>
</center>

<br><br>