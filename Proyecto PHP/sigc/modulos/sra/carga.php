<?php


function limpiar_fecha($dato)
{
	$fecha_x = explode('/',$dato);
	$fecha_ok = $fecha_x[2].'-'.$fecha_x[1].'-'.$fecha_x[0];
	return $fecha_ok; 
}


if($_POST["cargar"])
{
	
	$archivo = date("Y_m_d_G_i_s").".csv";
	$destino = "documentos/hv/".$archivo;
	if (copy($_FILES['excel']['tmp_name'],$destino))
	{
		$i = 1;
		$trozos = explode(".", $_FILES['excel']['name']); 
		$extension = end($trozos);
		if($extension=='CSV' || $extension=='csv')
		{
			// mysql_query("TRUNCATE sra_tramite");
			mysql_query("UPDATE `sra_tramite` SET `estado` = '2'");
			
			$fp = fopen ($_FILES['excel']['tmp_name'],"r");
			while ($tramite = fgetcsv ($fp, 1000, ";")) 
			{			
				if($i==1)
				{					
					if(trim($tramite[0])!='Pedido' || trim($tramite[5])!='Red' || trim($tramite[2])!='Localidad' )
					{
						$error = $error."<b>Linea ".$i.$sql_ing_materia."</b>: Encabezado incorrecto<br>";
						break;
					}
				}
				else
				{	
					
						$barrio =  preg_replace('/[0-9]+/', '',$tramite[10]);
						$barrio = str_replace("-", "", $barrio);
						
						$pedido = 	limpiar($tramite[0]);	
						$zona = 	limpiar($tramite[1]);	
						$localidad = 	limpiar($tramite[2]);	
						$tipo_trabajo = 	limpiar($tramite[3]);
						$tecnologia = 	limpiar($tramite[4]);	
						$clase_servicio = 	limpiar($tramite[6]);	
						$tipo_cliente = 	limpiar($tramite[7]);	
						$cliente = 	limpiar($tramite[8]);	
						$direccion = 	limpiar($tramite[9]);
						$fecha_cita = 	limpiar_fecha($tramite[11]);	
						$franja_horaria = 	limpiar($tramite[12]);	
						$agendador = 	limpiar($tramite[13]);	
						$observacion = 	limpiar($tramite[14]);	
						
						$red = explode('/',$tramite[5]);
						$distribuidor = " NULL ";
						$armario  = " NULL ";
						$caja = " NULL ";
						
						$dist_encontrado = 'n';
						foreach($red as $indice=>$dato)
						{
							
							if(strpos($dato, "Dist:")===true || strpos($dato, "Dist:")===0)
							{								
								$distribuidor =  trim(str_replace("Dist:", "", $dato));
								$distribuidor = " '".$distribuidor."' ";
								$dist_encontrado = 's';
							}
							else
							{
								if( (strpos($dato, "Troncal_DSLAM:")===true || strpos($dato, "Troncal_DSLAM:")===0) AND  $dist_encontrado == 'n')
								{								
									$dato_tem1 =  trim(str_replace("Troncal_DSLAM:", "", $dato));
									$dato_tem1 =  trim(str_replace("TR", "", $dato_tem1));
									$dato_tem2 = explode('-',$dato_tem1);
									$distribuidor =  trim($dato_tem2[0]);
									$distribuidor = " '".$distribuidor."' ";
								}
							}

							if(strpos($dato, "Arm:")===true || strpos($dato, "Arm:")===0)
							{
								$armario =  trim(str_replace("Arm:", "", $dato));
								$armario = " '".$armario."' ";
							}
							
							if( (strpos($dato, "Caja:")===true  || strpos($dato, "Caja:")===0 ) && strpos($dato, "_Caja:")===false)
							{
								$caja =  trim(str_replace("Caja:", "", $dato));
								$caja = " '".$caja."' ";
							}				
						
						}
						
						
					
						$sql_bus = "SELECT id FROM sra_tramite WHERE pedido = '".$pedido."' AND pedido = 1 LIMIT 1";
						$res_bus = mysql_query($sql_bus);
						$row_bus = mysql_fetch_array($res_bus);
						if(!$row_bus["id"])
						{								
							$sql_dis = "SELECT orden FROM `sra_distribuidor` WHERE distribuidor = ".$distribuidor."  LIMIT 1 ";
							$res_dis = mysql_query($sql_dis);
							$row_dis = mysql_fetch_array($res_dis);
							
							if($row_dis["orden"]){$orden = $row_dis["orden"];}
							else{$orden = '1000';}
							
							
							 $sql_ing = "INSERT INTO `sra_tramite` (`pedido`, `zona`, `localidad`, `tipo_trabajo`, `tecnologia`, 
							`clase_servicio`, `tipo_cliente`, `cliente`, `direccion`, `barrio`, 
							`fecha_cita`, `franja_horaria`, `agendador`, `observacion`, `distribuidor`,
							`armario`, `caja`, `orden`) 
							VALUES ('".$pedido."', '".$zona."', '".$localidad."', '".$tipo_trabajo."', '".$tecnologia."', 
							'".$clase_servicio."', '".$tipo_cliente."', '".$cliente."', '".$direccion."', '".$barrio."', 
							'".$fecha_cita."', '".$franja_horaria."', '".$agendador."', '".$observacion."', ".$distribuidor."
							, ".$armario.", ".$caja.", '".$orden."');";	 
							mysql_query($sql_ing);	
						}
												
				}				
				
			$i++;	
			}
			
		}
		else
		{
			$error = 'El archivo de ser un CSV (Delimitado por comas) ';	
		}
		
	}
	else
	{
		$error = 'No se pudo cargar el archivo';	
	}		
	 echo '<script> alert("Se termino de cargar el archivo");</script>';
}

?>

<h2>CARGA DE TRAMITES PARA AGRUPAR </h2>
<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<div align=right>
		<a href="?cmp=tramites"> <i class="fa fa-reply fa-2x"></i> Volver al listado de tramites </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
	<br><br>
	<table align="center" width=60%>
		<tr>
			 <td valign=top>
			
				<div class=" form-group input-group input-group-lg" style="width:100%">
				   <span class="input-group-addon">Cargar tamites CSV</span>
				  <input  name="excel"  id="excel" type="file" class="form-control" placeholder="Cargar tramites" required />
				</div> 
	
					<?php if($error){?>
					<div class="alert alert-info">
						<?php echo $error; ?>
					</div>
				<?php } ?>
					<center><input type="submit" class="btn btn-primary" value="Cargar" name="cargar" ></center>
			</td>
		</tr>
				
	</table>
</form>	<br><br><br>


	
