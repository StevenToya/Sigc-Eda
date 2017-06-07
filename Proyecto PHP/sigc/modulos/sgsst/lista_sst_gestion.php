<?php

if($PERMISOS_GC["sst_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


$sql = "SELECT id FROM empresa WHERE id_instancia = '".$_SESSION["nst"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);

$id_empresa = $row["id"];

?>
<h2>LISTA DE DOCUMENTOS ENTREGADOS SG-SST</h2>
<div align=right> 						
	<a href='?cmp=lista_regla'><i class="fa fa-folder-open fa-2x"></i> Gestion documentacion</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href='?cmp=auditar_documento'><i class="fa fa-pencil-square-o fa-2x"></i> Revisi&oacute;n de lo documentos</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href='?cmp=auditar_basico'><i class="fa fa-pencil-square-o fa-2x"></i> Revisi&oacute;n de los datos </a>&nbsp;&nbsp;&nbsp;&nbsp;
	
</div>


<div class="panel panel-default">

	<div class="panel-heading">
		 Status de la documentacion
	</div>
	<div class="panel-body">
		<div class="table-responsive">
		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Documentacion</th>
						<th>Plantilla</th>
						<th>Frecuencia<br> actulizacion</th>
						<th>Fecha de actualizacion</th>
						<th>Fecha de sistema</th>
						<th>Estado</th>
						<th>Documento</th>															
					</tr>
				</thead>
				<tbody>
				<?php
				/*DATOS BASICOS*/
				$sql = "SELECT * FROM empresa WHERE id_instancia = '".$_SESSION["nst"]."' LIMIT 1 ";
				$res = mysql_query($sql);
				$row = mysql_fetch_array($res);
				
				if(!$row["fecha_registro"] || substr($row["fecha_registro"],0 ,10) == '0000-00-00')
				{$estado = "<font color=red>Sin gestionar</font>";}
				else
				{
					if($row["estado"]==1){	$estado = "<font color=#8A4B08>Sin revisar</font>";	}
				
					if($row["estado"]==3)	{	$estado = '<font color=red>RECHAZADO</font>';	}
					
					if($row["estado"]==2)	
					{
						$cant_dias = '356 days';
						$fecha = date_create(substr($row["fecha_registro"],0 ,10));				
						
						date_add($fecha, date_interval_create_from_date_string($cant_dias));
						 $fecha_expedicion =  date_format($fecha, 'Y-m-d');
						
						if(strtotime(date("Y-m-d")) > strtotime($fecha_expedicion))
						{ $estado = '<font color=red>Sin actualizar</font>';}else{$estado = '<font color=green>Actualizado</font>';}
					}
				}
				
				?>
				
				<tr class="odd gradeX">
						<td> * Datos basicos de la empresa </td>
						<td align=center >---</td>
						<td align=center>12 Meses</td>
						<td align=center><?php echo substr($row["fecha_registro"], 0, 10);  ?> </td>
						<td align=center><?php echo $row["fecha_registro"] ?> </td>						
						<td align=center><b><?php echo $estado ?></b></td>
						<td align=center>
							<?php if($row_emp["archivo"]){ ?>
								<a target="blank" href="<?php echo $row_emp["archivo"] ?>">Descargar </a>
							<?php }else{ ?>
								---
							<?php } ?>
						</td>						
				   </tr>
				   
				<?php
				/*FIN DATOS BASICOS*/
				
				$sql = "SELECT * FROM documento WHERE tipo= 4 AND id_instancia='".$_SESSION["nst"]."' ORDER BY descripcion  ";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
					$sql_emp = "SELECT * FROM documento_empresa WHERE id_empresa = '".$id_empresa."'AND id_documento= '".$row["id"]."'  AND estado = 1 LIMIT 1";
					$res_emp = mysql_query($sql_emp);
					$row_emp = mysql_fetch_array($res_emp);
					
					$estado = '';
					if(!$row_emp["id"])
					{
						$estado = '<font color=red>Sin gestionar</font>';
					}
					else
					{
						if($row_emp["fase"]==1)	{	$estado = '<font color=#8A4B08>Sin revisar</font>';	}
						
						if($row_emp["fase"]==3)	{	$estado = '<font color=red>RECHAZADO</font>';	}
						
						if($row_emp["fase"]==2)	
						{
								$dias = $row["revision_mes"] * 30;
								$cant_dias = $dias.' days';
								$fecha = date_create($row_emp["fecha_expedicion"]);				
								
								date_add($fecha, date_interval_create_from_date_string($cant_dias));
								 $fecha_expedicion =  date_format($fecha, 'Y-m-d');
								
								if(strtotime(date("Y-m-d")) > strtotime($fecha_expedicion))
								{ $estado = '<font color=red>Sin actualizar</font>';}else{$estado = '<font color=green>Actualizado</font>';}
						}
					}
					
				?>
					<tr class="odd gradeX">
						<td><?php echo $row["descripcion"] ?> </td>
						<td align=center>
							<?php if($row["archivo"]){ ?>
								<a target="blank" href="<?php echo $row["archivo"] ?>">Descargar</a>
							<?php }else{ ?>
								---
							<?php } ?>
						</td>
						<td align=center><?php echo $row["revision_mes"] ?> Meses</td>
						<td align=center><?php echo $row_emp["fecha_expedicion"] ?> </td>
						<td align=center><?php echo $row_emp["fecha_registro"] ?> </td>
						<td align=center><b><?php echo $estado ?></b></td>
						<td align=center>
							<?php if($row_emp["archivo"]){ ?>
								<a target="blank" href="<?php echo $row_emp["archivo"] ?>">Descargar </a>
							<?php }else{ ?>
								---
							<?php } ?>
						</td>						
				   </tr>
				<?php
				}
				?>
				   
				</tbody>
			</table>
		</div>
		
	</div>
</div>


