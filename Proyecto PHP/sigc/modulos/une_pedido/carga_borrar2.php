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
								
				$stridentificacion = 	limpiar($tramite[7]);	
				$migtras = 	limpiar($tramite[11]);	
				$tipoinstaespecifico = 	limpiar($tramite[12]);	 
				$tipoinstageneral = 	limpiar($tramite[13]);	
				$portafolio = 	limpiar($tramite[15]);	
						
				
				$sql_bus_pedido = "SELECT id FROM  une_pedido WHERE numero = '".$numero."' AND une_pedido.tipo='1' LIMIT 1 ";
				$res_bus_pedido = mysql_query($sql_bus_pedido);
				$row_bus_pedido = mysql_fetch_array($res_bus_pedido);				
				
				if($row_bus_pedido["id"])
				{	
					 $sql = "UPDATE `une_pedido` 
						SET 
						`stridentificacion` = '".$stridentificacion."',
						`migtras` = '".$migtras."',
						`tipoinstaespecifico` = '".$tipoinstaespecifico."',
						`tipoinstageneral` = '".$tipoinstageneral."',
						`portafolio` = '".$portafolio."'
						WHERE `numero` = '".$numero."' AND une_pedido.tipo='1'  ;";
					mysql_query($sql);	
				
				
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
******************************************************************************************************************************************************************************************************

<h2>CARGA PARA ACTUALIZAR DATOS <b>HFC</b> EN ACTIVIDADES  </h2>
<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<br><br>
	<div align=right>
		<a href="?cmp=liquidacion"> <i class="fa fa-reply fa-2x"></i> Volver al listado de pedidos </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
