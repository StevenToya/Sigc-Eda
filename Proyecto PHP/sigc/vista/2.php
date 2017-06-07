<style type="text/css"> 
.fijo2 {
	position:fixed !important; 
	left:0px; 
	top:0px; 
	z-index:10 !important
		width:1000px; 
} 

.fijo3 {
	position:fixed !important; 
	right:0px; 
	top:0px; 
	z-index:10 !important
		width:1000px; 
} 
</style>
<?php

$sig = 1 + $_GET["pag"]; 
$archivo_actulizar = $sig.".php";
 if (!file_exists($archivo_actulizar)) { 
	$sig = 1;
 }
include("../cnx.php");


function diames($mes)
{	
	
	if($mes=='01'){$sem = 'Ene';}	
	if($mes=='02'){$sem = 'Feb';}	
	if($mes=='03'){$sem = 'Mar';}	
	if($mes=='04'){$sem = 'Abr';}	
	if($mes=='05'){$sem = 'May';}	
	if($mes=='06'){$sem = 'Jun';}
	if($mes=='07'){$sem = 'Jul';}	
	if($mes=='08'){$sem = 'Ago';}	
	if($mes=='09'){$sem = 'Sep';}	
	if($mes=='10'){$sem = 'Oct';}	
	if($mes=='11'){$sem = 'Nov';}	
	if($mes=='12'){$sem = 'Dic';}	
	
		
		return $sem;
}




if(!$_GET["mes_ges"])
{
	$mes_ges = date("m");
}else
{
	$mes_ges = $_GET["mes_ges"];
}

if(!$_GET["ano_ges"])
{
	$ano_ges = date("Y");
}else
{
	$ano_ges = $_GET["ano_ges"];
}




$sql_tot = "SELECT  COUNT(*) AS cantidad 
FROM `tramite` 
INNER JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
WHERE 
	fecha_atencion_orden >=  '".date("Y")."-".date("m")."-".date("d")." 00:00:00' 
	AND  fecha_atencion_orden <=  '".date("Y")."-".date("m")."-".date("d")." 23:59:59' 
	".$uo." 
	AND ultimo='s' 
	 AND (tipo_trabajo.tipo = '3' && tramite.tipo_paquete!='Traslado')  ";
$res_tot = mysql_query($sql_tot);
$row_tot = mysql_fetch_array($res_tot);


$pro_diario = round($uop / (cal_days_in_month(CAL_GREGORIAN, $mes_ges, $ano_ges)));
		
function diasemana($ano,$mes,$dia)
{
	
	$dia= date("w",mktime(0, 0, 0, $mes, $dia, $ano));
	if($dia=='0'){$sem = 'Dom';}	
	if($dia=='1'){$sem = 'Lun';}	
	if($dia=='2'){$sem = 'Mar';}	
	if($dia=='3'){$sem = 'Mie';}	
	if($dia=='4'){$sem = 'Jue';}	
	if($dia=='5'){$sem = 'Vie';}	
	if($dia=='6'){$sem = 'Sab';}	
		
		return $sem;
}	
			
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

    <head>
		<?php if($_SESSION["pausa"]!=1){ ?>
		<!--	<meta http-equiv="Refresh" content="60;url=?pag=<?php echo $sig; ?>"> -->
		<?php } ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Reporte de Tramites </title>
       <link rel="stylesheet" href="style.css" type="text/css">
        <script src="amcharts/amcharts.js" type="text/javascript"></script>
        <script src="amcharts/serial.js" type="text/javascript"></script>
        <script src="amcharts/pie.js" type="text/javascript"></script>
        <!-- theme files. you only need to include the theme you use.
             feel free to modify themes and create your own themes -->
        <script src="amcharts/themes/light.js" type="text/javascript"></script>
        <script src="amcharts/themes/dark.js" type="text/javascript"></script>
        <script src="amcharts/themes/black.js" type="text/javascript"></script>
        <script src="amcharts/themes/chalk.js" type="text/javascript"></script>
        <script src="amcharts/themes/patterns.js" type="text/javascript"></script>
      
      
        <script type="text/javascript">

        // in order to set theme for a chart, all you need to include theme file
        // located in amcharts/themes folder and set theme property for the chart.

        var chart1;
        var chart2;

        makeCharts("light", "#FFFFFF");

        // Theme can only be applied when creating chart instance - this means
        // that if you need to change theme at run time, youhave to create whole
        // chart object once again.

        function makeCharts(theme, bgColor, bgImage){

            if(chart1){
                chart1.clear();
            }
            if(chart2){
                chart2.clear();
            }

            // background
            if(document.body){
                document.body.style.backgroundColor = bgColor;
                document.body.style.backgroundImage = "url(" + bgImage + ")";
            }

            // column chart
            chart1 = AmCharts.makeChart("chartdiv1", {
                type: "serial",
                theme:theme,
                dataProvider:[<?php
				$dia = 1;
				$hfc = $umts = $xdsl = $cobre = $gpon = 0;
								
				if($mes_ges != date("m"))
				{
					$dia_f = cal_days_in_month(CAL_GREGORIAN, $mes_ges, $ano_ges);
				}else
				{
					$dia_f = date("d");
				}
				
				while($dia<=$dia_f)
				{
					if($dia<10){$dia = '0'.$dia;}
					$sql = "SELECT COUNT(*) AS cantidad FROM `tramite` 
					INNER JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
					WHERE fecha_atencion_orden >=  '".$ano_ges."-".$mes_ges."-".$dia." 00:00:00' 
					AND fecha_atencion_orden <= '".$ano_ges."-".$mes_ges."-".$dia." 23:59:59'   
					".$uo." 
					AND ultimo='s' 
					AND (tipo_trabajo.tipo = '3' && tramite.tipo_paquete!='Traslado') ";
					$res = mysql_query($sql);
					$row = mysql_fetch_array($res);
					
					
					$total = $row["cantidad"] ;
					$dia_res = $dia_f+1;					
					$pro_diario = round(($uop - $total_acumulado) / ((cal_days_in_month(CAL_GREGORIAN, $mes_ges, $ano_ges)+1)-$dia));
					
					
					if($pro_diario<0){$pro_diario = 0;}
					
						$d = diasemana($ano_ges,$mes_ges,$dia);
				?>{"Dia": ' <?php echo $d; ?> '+ <?php echo $dia; ?>,
						"Total": <?php echo $total; ?>, 
						<?php
						$sql = "SELECT `id_tecnologia`, tecnologia.nombre, COUNT(*) AS cantidad 
						FROM `tramite` 
						INNER JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
						INNER JOIN tecnologia ON tramite.id_tecnologia = tecnologia.id
						WHERE 
						fecha_atencion_orden >=  '".$ano_ges."-".$mes_ges."-".$dia." 00:00:00' 
						AND fecha_atencion_orden <= '".$ano_ges."-".$mes_ges."-".$dia." 23:59:59'  
						".$uo." 
						AND ultimo='s'   
						 AND (tipo_trabajo.tipo = '3' && tramite.tipo_paquete!='Traslado') 
						GROUP BY tecnologia.nombre ";
						$res = mysql_query($sql);
						while($row = mysql_fetch_array($res))
						{	
						?>						
							"<?php echo $row["nombre"]; ?>": <?php echo $row["cantidad"]; ?>,					
						<?php
						}
						?>
						"PRESUPUESTADO": <?php echo $pro_diario; ?>	
						
					} 
				<?php
					$total_acumulado= $total_acumulado + $total;;	
					
					if($dia!=$dia_f){echo ',';}
					$dia ++;
				}
				?>],
                categoryField: "Dia",
                startDuration: 1,

                categoryAxis: {
                    gridPosition: "start"
                },
                valueAxes: [{
                    title: "Tramites"
                }],
                graphs: [{
                    type: "column",
                    title: "Total",
                    valueField: "Total",
                    lineAlpha: 0,
                    fillAlphas: 0.8,
					balloonText: "Es [[title]] en [[category]]:<b>[[value]]</b>"
                }, 
				
				
				<?php
				 $sql = "SELECT `id_tecnologia`, tecnologia.nombre, COUNT(*) AS cantidad 
				 FROM `tramite` 
				 INNER JOIN tecnologia ON tramite.id_tecnologia = tecnologia.id
				 INNER JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
				 WHERE 
				 fecha_atencion_orden >=  '".$ano_ges."-".$mes_ges."-01 00:00:00' 
				 AND fecha_atencion_orden <= '".$ano_ges."-".$mes_ges."-31 23:59:59'  
				 ".$uo." 
				 AND ultimo='s'  
				  AND (tipo_trabajo.tipo = '3' && tramite.tipo_paquete!='Traslado') 
				 GROUP BY tecnologia.nombre ";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{	
				?>
				
					{
						type: "line",
						title: "<?php echo $row["nombre"] ?>",
						valueField: "<?php echo $row["nombre"] ?>",
						lineThickness: 2,
						fillAlphas: 0,
						bullet: "round",
						balloonText: "[[title]] en [[category]]:<b>[[value]]</b>"
					}, 
				
				<?php
				}
				?>
							
				{
                    type: "line",
                    title: "PRESUPUESTADO",
                    valueField: "PRESUPUESTADO",
					lineThickness: 5,
					lineColor: "red",
					fillAlphas: 0,
                    bullet: "round",
                    balloonText: "[[title]] en [[category]]:<b>[[value]]</b>"
                }],
                legend: {
                    useGraphSettings: true
                }

            });

            // pie chart
		
            chart2 = AmCharts.makeChart("chartdiv2", {
                type: "pie",
                theme: theme,
                dataProvider: [
				<?php
				$pri_vez = 's';
				$sql = "SELECT `id_tecnologia`, tecnologia.nombre, COUNT(*) AS cantidad 
				FROM `tramite` 
				INNER JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
				INNER JOIN tecnologia ON tramite.id_tecnologia = tecnologia.id
				WHERE 
				fecha_atencion_orden >=  '".$ano_ges."-".$mes_ges."-01 00:00:00' 
				AND fecha_atencion_orden <= '".$ano_ges."-".$mes_ges."-31 23:59:59'   
				".$uo." 
				AND ultimo='s' 
				 AND (tipo_trabajo.tipo = '3' && tramite.tipo_paquete!='Traslado') 
				GROUP BY tecnologia.nombre ";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{	
					if($pri_vez != 's'){echo ',';}
				?>
					{
						"Tecnologia": "<?php echo $row["nombre"] ?>",
							"Cantidad": <?php echo $row["cantidad"] ?>
					}
				
				<?php
					$pri_vez = 'n';
				}
				?>
				
				
				
				],
                titleField: "Tecnologia",
                valueField: "Cantidad",
                balloonText: "[[title]]<br><b>[[value]]</b> ([[percents]]%)",
                legend: {
                    align: "center",
                    markerType: "circle"
                }
            });
			
			
			 // pie chart DOS
		
            chart3 = AmCharts.makeChart("chartdiv3", {
                type: "pie",
                theme: theme,
                dataProvider: [
				<?php
				$pri_vez = 's';
				$sql = "SELECT `id_tecnologia`, tecnologia.nombre, COUNT(*) AS cantidad 
				FROM `tramite` 
				INNER JOIN tecnologia ON tramite.id_tecnologia = tecnologia.id
				INNER JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
				WHERE 
				fecha_atencion_orden >=  '".$ano_ges."-".$mes_ges."-".date("d")." 00:00:00' 
				AND fecha_atencion_orden <= '".$ano_ges."-".$mes_ges."-".date("d")." 23:59:59'
				AND (tipo_trabajo.tipo = '3' && tramite.tipo_paquete!='Traslado') 				
				GROUP BY tecnologia.nombre ";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{	
					if($pri_vez != 's'){echo ',';}
				?>
					{
						"Tecnologia": "<?php echo $row["nombre"] ?>",
							"Cantidad": <?php echo $row["cantidad"] ?>
					}
				
				<?php
					$pri_vez = 'n';
				}
				?>
				
				
				
				],
                titleField: "Tecnologia",
                valueField: "Cantidad",
                balloonText: "[[title]]<br><b>[[value]]</b> ([[percents]]%)",
                legend: {
                    align: "center",
                    markerType: "circle"
                }
            });

        }


		
		
        </script>
    </head>

    <body style="font-size:15px;">
	
	<div class="fijo2">        
		<!--	<table border=0  bgcolor="#000000">
			<tr>			
				<td ><font color="#FFFFFF"><b>Actualizado: <?php echo $fecha_actualizada; ?></b></font> </td>
			</tr>
			</table>	-->
	</div>
	
	<div class="fijo3">        
			<table border=0 >
			<tr>			
				<td > <img src="../imagenes/edatel.png" border=0> </td>
			</tr>
			</table>	
	</div>
	<?php
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
			$ano_s = $ano_ges + 1 ;
			$mes_a = $mes_ges - 1;
			$ano_a = $ano_ges  ;
		}
		else
		{
			$ano_a = $ano_ges  ;
			$ano_s = $ano_ges ;
			$mes_a = $mes_ges - 1;
			$mes_s = $mes_ges + 1;
		}
	}
	
	if($mes_a<10){$mes_a = '0'.$mes_a;}
	if($mes_s<10){$mes_s = '0'.$mes_s;}
	?>
	
	
	
      <table width="100%">
	  <tr>
		<td colspan=3> 
			
			<center><h2> <font color=red>Reparaciones</font>  ejecutadas durante el dia  <?php echo $tit ?>  </h2></center>
			
			<div align=center id="chartdiv1" style="width: 100%; height: 400px;"></div> 
		</td>		
	  </tr>
	  <tr>
		<td width=45%>
			<center><b>Acumulado del mes</b></center>
			<div id="chartdiv2" style="width: 100%; height: 350px;"></div>
		</td>
		<td width=10%></td>
		<td width=45%>
			<center><b>Acumulado del dia actual (<?php echo date("Y-m-d") ?>) </b></center>
			<div id="chartdiv3" style="width: 100%; height: 350px;"></div>
		</td>
	  </tr>
	</table> 
   