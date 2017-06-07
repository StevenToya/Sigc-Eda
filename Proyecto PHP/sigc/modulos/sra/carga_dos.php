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
						
						$pedido = 	limpiar_numero($tramite[0]);	
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
						
											
						$sql_bus = "SELECT id FROM ord_tramite WHERE pedido = '".$pedido."' LIMIT 1";
						$res_bus = mysql_query($sql_bus);
						$row_bus = mysql_fetch_array($res_bus);
						if(!$row_bus["id"])
						{		
							 $sql_ing = "INSERT INTO `ord_tramite` (`pedido`, `zona`, `localidad`, `tipo_trabajo`, `tecnologia`, 
							`clase_servicio`, `tipo_cliente`, `cliente`, `direccion`, `barrio`, `fecha_registro`) 
							VALUES ('".$pedido."', '".$zona."', '".$localidad."', '".$tipo_trabajo."', '".$tecnologia."', 
							'".$clase_servicio."', '".$tipo_cliente."', '".$cliente."', '".$direccion."', '".$barrio."', '".date("Y-m-d G:i:s")."');";	 
							mysql_query($sql_ing);	
							
							$id = mysql_insert_id();
							
							$sql_ing = "INSERT INTO `ord_tramite_cita` (`id_tramite`, `fecha_cita`, `franja_cita`, `estado`) 
							VALUES ('".$id."', '".$fecha_cita."', '".$franja_horaria."', '1');";
								mysql_query($sql_ing);								
							
						}
						else
						{
								$id = $row_bus["id"];
								$sql_bus = "SELECT id FROM ord_tramite_cita 
								WHERE id_tramite = '".$id."', fecha_cita = '".$fecha_cita."', franja_cita = '".$franja_horaria."' LIMIT 1";
								$res_bus = mysql_query($sql_bus);
								$row_bus = mysql_fetch_array($res_bus);
								if(!$row_bus["id"])
								{	
									$sql_ing = "INSERT INTO `ord_tramite_cita` (`id_tramite`, `fecha_cita`, `franja_cita`, `estado`) 
									VALUES ('".$id."', '".$fecha_cita."', '".$franja_horaria."', '1');";
									mysql_query($sql_ing);
								}
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

<h2>CARGA DE TRAMITES PARA SEGUIMIENTO </h2>
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


	
