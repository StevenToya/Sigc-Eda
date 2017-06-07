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
			$fp = fopen ($_FILES['excel']['tmp_name'],"r");
			while ($tramite = fgetcsv ($fp, 1000, ";")) 
			{
				$numero = limpiar_numero($tramite[1]);
				$codigo_material = limpiar_numero($tramite[4]);
				
				$extexiones_tv = 	limpiar($tramite[14]);	
				$tipo_direccion = 	limpiar($tramite[33]);	
				$cliente_id = 	limpiar($tramite[43]);	
				$servicio_instalado = 	limpiar($tramite[44]);	
				$reparacion = 	limpiar($tramite[45]);	
				$servicio_insta = 	limpiar($tramite[46]);	
				$estado_pedido = 	limpiar($tramite[47]);	
				$municipio_dane = 	limpiar($tramite[48]);	
				$departamento_dane = 	limpiar($tramite[49]);
				
				$numero_componente = 	limpiar($tramite[6]);	
				$unidad_material = 	limpiar($tramite[16]);	
				$identificador = 	limpiar($tramite[18]);	
				$min_ct = 	limpiar($tramite[19]);	
				$max_ct = 	limpiar($tramite[20]);	
				$min_pr = 	limpiar($tramite[21]);	
				$max_pr = 	limpiar($tramite[22]);	
				$nuevo_min = 	limpiar($tramite[23]);	
				$nuevo_max = 	limpiar($tramite[24]);	
				$alerta_cable_ct = 	limpiar($tramite[25]);	
				$calc_puntos_instalado = 	limpiar($tramite[26]);	
				$alerta_puntos_instalados = 	limpiar($tramite[27]);	
				$calc_conectores_inst = 	limpiar($tramite[28]);	
				$alerta_conectores_inst = 	limpiar($tramite[29]);	
				$calc_filtros_inst = 	limpiar($tramite[30]);	
				$alerta_filtro = 	limpiar($tramite[31]);	
				$precio = 	limpiar($tramite[34]);	
				$precio_x_cant_rep = 	limpiar($tramite[35]);	
				$difer_precio_x_cant_rep = 	limpiar($tramite[36]);	
				$li = 	limpiar($tramite[37]);	
				$ls = 	limpiar($tramite[38]);	
				$precio_x_dif_sobre_lim = 	limpiar($tramite[39]);	
				$alerta_cable_limites = 	limpiar($tramite[40]);	
				$alertado = 	limpiar($tramite[41]);	
				$reiterativo = 	limpiar($tramite[42]);					
				
				$sql_bus_pedido = "SELECT id FROM  une_pedido WHERE numero = '".$numero."' AND une_pedido.tipo='1' LIMIT 1 ";
				$res_bus_pedido = mysql_query($sql_bus_pedido);
				$row_bus_pedido = mysql_fetch_array($res_bus_pedido);				
				
				$sql_bus_material = "SELECT id FROM une_material WHERE codigo = '".$codigo_material."' AND tipo=1 LIMIT 1";
				$res_bus_material = mysql_query($sql_bus_material);
				$row_bus_material = mysql_fetch_array($res_bus_material);
				if($row_bus_material["id"] && $row_bus_pedido["id"])
				{	
					$id_material = $row_bus_material["id"];
					$id_pedido = $row_bus_pedido["id"];
					
					$sql = "UPDATE `une_pedido_material` 
						SET 
						`numero_componente` = '".$numero_componente."',
						`unidad_material` = '".$unidad_material."',
						`identificador` = '".$identificador."',
						`min_ct` = '".$min_ct."',
						`max_ct` = '".$max_ct."',
						`min_pr` = '".$min_pr."',
						`max_pr` = '".$max_pr."',
						`nuevo_min` = '".$nuevo_min."',
						`nuevo_max` = '".$nuevo_max."',
						`alerta_cable_ct` = '".$alerta_cable_ct."',
						`calc_puntos_instalado` = '".$calc_puntos_instalado."',
						`alerta_puntos_instalados` = '".$alerta_puntos_instalados."',
						`calc_conectores_inst` = '".$calc_conectores_inst."',
						`alerta_conectores_inst` = '".$alerta_conectores_inst."',
						`calc_filtros_inst` = '".$calc_filtros_inst."',
						`precio` = '".$precio."',
						`alerta_filtro` = '".$alerta_filtro."',
						`precio_x_cant_rep` = '".$precio_x_cant_rep."',
						`difer_precio_x_cant_rep` = '".$difer_precio_x_cant_rep."',
						`li` = '".$li."',
						`ls` = '".$ls."',
						`precio_x_dif_sobre_lim` = '".$precio_x_dif_sobre_lim."',
						`alerta_cable_limites` = '".$alerta_cable_limites."',
						`alertado` = '".$alertado."',
						`reiterativo` = '".$reiterativo."'
						WHERE `id_pedido` = '".$id_pedido."' AND id_material = '".$id_material."' ;";
					mysql_query($sql);	
				
				
				}
				
				
				
					$sql = "UPDATE `une_pedido` 
						SET 
						`extexiones_tv` = '".$extexiones_tv."',
						`tipo_direccion` = '".$tipo_direccion."',
						`cliente_id` = '".$cliente_id."',
						`servicio_instalado` = '".$servicio_instalado."',
						`reparacion` = '".$reparacion."',
						`servicio_insta` = '".$servicio_insta."',
						`estado_pedido` = '".$estado_pedido."',
						`municipio_dane` = '".$municipio_dane."',
						`departamento_dane` = '".$departamento_dane."'
						WHERE `numero` = '".$numero."';";
					mysql_query($sql);	






					
				
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
******************************************************************************************************************************************************************************************************

<h2>CARGA PARA ACTUALIZAR DATOS <b>HFC</b>  </h2>
<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<div align=right>
		<a href="?cmp=lista_pendiente"> <i class="fa fa-reply fa-2x"></i> Volver al listado de pedidos </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
</form>		
<br>
*********************************************************************************************************************************************************************************************************
