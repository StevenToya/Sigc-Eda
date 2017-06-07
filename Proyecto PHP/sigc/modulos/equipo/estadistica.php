<?php

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

?>
	  
	  
	  <h2>CONSUMO DE EQUIPO EN INSTALACIONES EN <b><?php echo mes($mes_ges); ?></b> DEL <b><?php echo $ano_ges; ?></b></h2>
	  
	  <h4>
	  <table width="100%">
		<tr>
			<td><a href="?mesg=<?php echo $mes_a;?>"><i class="fa fa-arrow-left"></i> Mes anterior</a></td>
			<td align="right"><a href="?mesg=<?php echo $mes_s; ?>">Mes siguiente <i class="fa fa-arrow-right"></i><a></td>
		<tr>
	  </table>
	  </h4><br>
	  <table width=90%>
		<tr>
			<?php
			$sql_equ = "SELECT nombre, id FROM equipo_material 
			WHERE id IN ('653','645','650','647','646','648','657') ORDER BY nombre";
			$res_equ = mysql_query($sql_equ);
			while($row_equ = mysql_fetch_array($res_equ))
			{
				$sql_dia = "SELECT  COUNT(*) AS cantidad
					FROM serial_traza 
					INNER JOIN 	equipo_serial ON serial_traza.id_equipo_serial = equipo_serial.id	
					INNER JOIN tramite ON serial_traza.id_tramite = tramite.id 
					INNER JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
					WHERE equipo_serial.id_equipo_material = '".$row_equ["id"]."' 
					AND fecha >= '".$ano_ges."-".$mes_ges."-01 00:00:00' AND fecha <= '".$ano_ges."-".$mes_ges."-31 23:59:59' 
					AND serial_traza.estado=2 AND tramite.codigo_unidad_operativa IN ('2000','4000')";
					$res_dia = 	mysql_query($sql_dia);
					$row_dia = mysql_fetch_array($res_dia);
				
					if($row_equ["id"]=='653'){ $color_equ = '#819FF7'; }
					if($row_equ["id"]=='645'){ $color_equ = '#9F81F7'; }
					if($row_equ["id"]=='650'){ $color_equ = '#2EFE64'; }
					if($row_equ["id"]=='647'){ $color_equ = '#BDBDBD'; }
					if($row_equ["id"]=='646'){ $color_equ = '#FAAC58'; }
					if($row_equ["id"]=='648'){ $color_equ = '#00FFBF'; }
					if($row_equ["id"]=='657'){ $color_equ = '#04B486'; }
				?>
					<td bgcolor="<?php echo $color_equ ?>" width="4%"><center><b><?php echo $row_dia["cantidad"] ?></b></center></td>
					<td width="10%">&nbsp;<a target=blank href="modulos/equipo/excel_equipo.php?id=<?php echo $row_equ["id"] ?>&ff=<?php echo $ano_ges ?>-<?php echo $mes_ges ?>"><?php echo $row_equ["nombre"] ?></a></td>
				
				<?php
				
			}
			?>
		
			
		</tr>
	  </table>
	  
	  <div align=right>
		<a target=blank href="modulos/equipo/excel_equipo.php?ff=<?php echo $ano_ges ?>-<?php echo $mes_ges ?>">Descargar todos los seriales</a>
	</div>
	  
	  <div class="chart">
		<canvas id="lineChart" style="height:500px"></canvas>
	  </div>
        <canvas id="areaChart" ></canvas>  
    <script>
      $(function () 
	  {       
        var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
        var areaChart = new Chart(areaChartCanvas);

        var areaChartData = {
			
		<?php
		$final = date("d",(mktime(0,0,0,$month+1,1,$year)-1));
		?>
		
        labels: [
		<?php
		$dia = 1;
		while($dia <= $final)
		{
		?>
			"Dia <?php echo $dia;?>"<?php if($dia != $final){ ?>, <?php } ?>
         <?php
			$ini[$dia]='0';
			$dia++;
		}
		 ?>
		],
		 
		 datasets: [<?php
			 $pos_equ = 0;
			$sql_equ = "SELECT nombre, id FROM equipo_material WHERE id IN ('653','645','650','647','646','648','657') ORDER BY nombre";
			$res_equ = mysql_query($sql_equ);
			while($row_equ = mysql_fetch_array($res_equ))
			{	
					$fin = $ini;
					$sql_dia = "SELECT  MID(fecha,9,2) AS dia, COUNT(*) AS cantidad
					FROM serial_traza 
					INNER JOIN 	equipo_serial ON serial_traza.id_equipo_serial = equipo_serial.id	
					INNER JOIN tramite ON serial_traza.id_tramite = tramite.id 
					INNER JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
					WHERE equipo_serial.id_equipo_material = '".$row_equ["id"]."' 
					AND fecha >= '".$ano_ges."-".$mes_ges."-01 00:00:00' AND fecha <= '".$ano_ges."-".$mes_ges."-31 23:59:59' AND serial_traza.estado=2  AND tramite.codigo_unidad_operativa IN ('2000','4000')
					GROUP BY MID(fecha,9,2)";
					$res_dia = 	mysql_query($sql_dia);
					while($row_dia = mysql_fetch_array($res_dia))
					{
						$row_dia["dia"] = $row_dia["dia"] + 0;
						$fin[$row_dia["dia"]] = $row_dia["cantidad"];
					}
					if($row_equ["id"]=='653'){ $color_equ = '#819FF7'; }
					if($row_equ["id"]=='645'){ $color_equ = '#9F81F7'; }
					if($row_equ["id"]=='650'){ $color_equ = '#2EFE64'; }
					if($row_equ["id"]=='647'){ $color_equ = '#BDBDBD'; }
					if($row_equ["id"]=='646'){ $color_equ = '#FAAC58'; }
					if($row_equ["id"]=='648'){ $color_equ = '#00FFBF'; }
					if($row_equ["id"]=='657'){ $color_equ = '#04B486'; }
					
		   ?>
			   {
				  label: "<?php echo $row_equ["nombre"] ?>",
				  strokeColor: "<?php echo $color_equ; ?>",
				  pointColor: "<?php echo $color_equ; ?>",					  
				  data: [<?php
						$dd = 1;
						while($dd <= $final)
						{				
					?>
							<?php echo $fin[$dd]; ?> <?php if($dd != $final){ ?> ,<?php } ?>
							
					<?php
							$dd++;
						}
					?>
										
					]
				}<?php if($pos_equ!=6){ ?>,<?php } ?>
			<?php
				$pos_equ ++;
			}
			?>
			
			
		
          ]
        };

        var areaChartOptions = {
			  //Boolean - Whether we should show a stroke on each segment
          segmentShowStroke: true,
          //String - The colour of each segment stroke
          segmentStrokeColor: "#fff",
          //Number - The width of each segment stroke
          segmentStrokeWidth: 2,
          //Number - The percentage of the chart that we cut out of the middle
          percentageInnerCutout: 50, // This is 0 for Pie charts
          //Number - Amount of animation steps
          animationSteps: 100,
          //String - Animation easing effect
          animationEasing: "easeOutBounce",
          //Boolean - Whether we animate the rotation of the Doughnut
          animateRotate: true,
          //Boolean - Whether we animate scaling the Doughnut from the centre
          animateScale: false,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true,
          // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: true,
          //String - A legend template
          legendTemplate: '<%=datasets[i].label%>'
 
        };

        areaChart.Line(areaChartOptions);
        var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
        var lineChart = new Chart(lineChartCanvas);
        var lineChartOptions = areaChartOptions;
        lineChartOptions.datasetFill = false;
        lineChart.Line(areaChartData, lineChartOptions);      
        
      });
    </script>
	
