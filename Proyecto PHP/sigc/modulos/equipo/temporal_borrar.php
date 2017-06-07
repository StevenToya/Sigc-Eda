<?php


if($_POST["cargar"])
{
	$destino = "documentos/hv/bak_".date("Y_m_d_G_i_s");	
	if (copy($_FILES['excel']['tmp_name'],$destino))
	{
		$i = 1;
		$trozos = explode(".", $_FILES['excel']['name']); 
		$extension = end($trozos);
		if($extension=='CSV' || $extension=='csv')
		{
			$fp = fopen ($_FILES['excel']['tmp_name'],"r");
			while ($dato = fgetcsv ($fp, 1000, ";")) 
			{
					$dato[0] = trim(strtoupper($dato[0]));
					$dato[1] = limpiar_numero($dato[1]);
					if($dato[0])
					{					
							
						$sql = "SELECT id, id_pedido, id_tramite FROM equipo_serial WHERE serial = '".$dato[0]."' AND id_pedido = '200' LIMIT 1";
						$res = mysql_query($sql);
						$row = mysql_fetch_array($res); 
						if($row["id"])
						{
							$id_serial = $row["id"];
								$sql = "DELETE FROM  `pedido_equipo_historial` WHERE  `id_serial` = '".$id_serial."' ;";  mysql_query($sql);
								$sql = "DELETE FROM  `serial_traza` WHERE  `id_equipo_serial` = '".$id_serial."' ;";  mysql_query($sql);
								$sql = "DELETE FROM `equipo_serial` WHERE `equipo_serial`.`id` = '".$id_serial."' ;";  mysql_query($sql);
								
						}
								
							
					}else
					{
						$error = $error."Linea ".$i.": Faltan campos por llenar<br>";
					}
				$i++;	
			}
			
		}
		else
		{
			$error = 'El archivo de ser un CSV (Delimitado por comas) ';
			
		}
		unlink($destino);
	}
	else
	{
		$error = 'No se pudo cargar el archivo';
		
	}		
	
	 if(!$error)
	 {				
		 echo "<script>alert('Se registraron los seriales correctamente')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_pedido'>";					
	 } 
}

?>

<h2>CARGA DE SERIALES A NUMERO DE ORDEN <b><?php echo $row_pedido["numero"]; ?></b> - BODEGA <b><?php echo $row_pedido["nombre_localidad"]; ?></b></h2>
<div align=right>
<a href="?cmp=lista_pedido"> <i class="fa fa-reply fa-2x"></i> Volvel al listado de pedidos </a>
</div>

<form action="?id=<?php echo $_GET["id"]; ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">
	
	<h4>
	<table align=center>
		<tr>
			<td colspan=2>Informacion que debe tener cada columna en el archivo CSV<br>(Delimitado por comas)<br><br></td>
		</tr>
		<tr>
			<td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A1</b></td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Serial del equipo</td>
		</tr>
		
		<tr>
			<td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B1</b></td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Identificador del equipo</td>
		</tr>
	</table>
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
Identificacion del equipo con su descripcion:<br><br>

<table width=40% class="table table-striped table-bordered table-hover">		
		<tr>
			<td>Idenficador</td>
			<td>Equipo</td>
		</tr>
		<?php
		$sql_equipo = "SELECT * FROM equipo_material WHERE tipo=1 ORDER BY id ";
		$res_equipo = mysql_query($sql_equipo);
		while($row_equipo = mysql_fetch_array($res_equipo)){
		?>
			<tr>
				<td><b><?php echo $row_equipo["id"]; ?></b></td>
				<td><?php echo $row_equipo["nombre"]; ?></td>
			</tr>
		<?php
		}
		?>
		
	</table>
		
