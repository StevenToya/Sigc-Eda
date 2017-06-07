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

if($_GET["elim"])
{
	
	$sql_elim = "DELETE FROM `aud_solicitud` WHERE `id` = '".$_GET["elim"]."' LIMIT 1 ";
	mysql_query($sql_elim);
}


if($_GET["excel"]==1)
{
	include("../../cnx.php");
		
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$sql = "SELECT aud_categoria.nombre, 
		SUM(CASE WHEN si_no = 'n' THEN 1 ELSE 0 END) AS  hallazgo,
		SUM(CASE WHEN si_no = 's' THEN 1 ELSE 0 END) AS  no_hallazgo
		 FROM  `aud_solicitud_item` 
		 INNER JOIN aud_item ON aud_solicitud_item.id_item =  aud_item.id
		INNER JOIN aud_solicitud ON aud_solicitud_item.id_solicitud = aud_solicitud.id
		 INNER JOIN aud_categoria ON aud_item.id_categoria =  aud_categoria.id
		WHERE aud_item.tipo = '1' 
		AND aud_solicitud.fecha_registro >= '".$_GET["ayo"]."-".$_GET["mes"]."-01 00:00:00' 
		AND aud_solicitud.fecha_registro <= '".$_GET["ayo"]."-".$_GET["mes"]."-31 23:59:59'
		GROUP BY aud_categoria.id";
		?>
		<table border=1>
			<tr>
				<th>CATEGORIA</th>
				<th>HALLAZGO ENCONTRADOS</th>
				<th>SIN HALLAZGO</th>
			</tr>
		<?php
		$res = mysql_query($sql);
		while($row = mysql_fetch_array($res))
		{
			?>
				<tr>
					<td><?php echo $row["nombre"] ?></td>
					<td><font color=red><b><?php echo $row["hallazgo"] ?></b></font></td>
					<td><?php echo $row["no_hallazgo"] ?></td>
				</tr>
			<?php
		}
		
		$sql = "SELECT 
			SUM(CASE WHEN respuesta = 'n' THEN 1 ELSE 0 END) AS  hallazgo,
			SUM(CASE WHEN respuesta = 's' THEN 1 ELSE 0 END) AS  no_hallazgo FROM `aud_solicitud_material` 
			INNER JOIN aud_solicitud ON aud_solicitud_material.id_solicitud = aud_solicitud.id
			WHERE 
			aud_solicitud.fecha_registro >= '".$_GET["ayo"]."-".$_GET["mes"]."-01 00:00:00' 
			AND aud_solicitud.fecha_registro <= '".$_GET["ayo"]."-".$_GET["mes"]."-31 23:59:59' ";
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		
		?>
				<tr>
					<td>Material</td>
					<td><font color=red><b><?php echo $row["hallazgo"] ?></b></font></td>
					<td><?php echo $row["no_hallazgo"] ?></td>
				</tr>
			
	
	
		</table>
	<?php
	die();
}

if($_GET["excel"]==2)
{
	include("../../cnx.php");
		
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	 $sql = "SELECT aud_item.pregunta, aud_categoria.nombre , 
SUM(CASE WHEN si_no = 'n' THEN 1 ELSE 0 END) AS  hallazgo,
SUM(CASE WHEN si_no = 's' THEN 1 ELSE 0 END) AS  no_hallazgo
 FROM  `aud_solicitud_item` 
 INNER JOIN aud_item ON aud_solicitud_item.id_item =  aud_item.id
INNER JOIN aud_solicitud ON aud_solicitud_item.id_solicitud = aud_solicitud.id
 INNER JOIN aud_categoria ON aud_item.id_categoria =  aud_categoria.id
WHERE aud_item.tipo = '1' 
AND aud_solicitud.fecha_registro >= '".$_GET["ayo"]."-".$_GET["mes"]."-01 00:00:00' 
AND aud_solicitud.fecha_registro <= '".$_GET["ayo"]."-".$_GET["mes"]."-31 23:59:59'
GROUP BY aud_item.id";
		?>
		<table border=1>
			<tr>
				<th>ITEM</th>
				<th>CATEGORIA</th>
				<th>HALLAZGO ENCONTRADOS</th>
				<th>SIN HALLAZGO</th>
			</tr>
		<?php
		$res = mysql_query($sql);
		while($row = mysql_fetch_array($res))
		{
			?>
				<tr>
					<td><?php echo $row["pregunta"] ?></td>
					<td><?php echo $row["nombre"] ?></td>
					<td><font color=red><b><?php echo $row["hallazgo"] ?></b></font></td>
					<td><?php echo $row["no_hallazgo"] ?></td>
				</tr>
			<?php
		}
		
	$sql = "SELECT 
			SUM(CASE WHEN respuesta = 'n' THEN 1 ELSE 0 END) AS  hallazgo,
			SUM(CASE WHEN respuesta = 's' THEN 1 ELSE 0 END) AS  no_hallazgo FROM `aud_solicitud_material` 
			INNER JOIN aud_solicitud ON aud_solicitud_material.id_solicitud = aud_solicitud.id
			WHERE 
			aud_solicitud.fecha_registro >= '".$_GET["ayo"]."-".$_GET["mes"]."-01 00:00:00' 
			AND aud_solicitud.fecha_registro <= '".$_GET["ayo"]."-".$_GET["mes"]."-31 23:59:59' ";
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		
		?>
				<tr>
					<td>Cumple con los materiales y sus cantidades</td>
					<td>Material</td>
					<td><font color=red><b><?php echo $row["hallazgo"] ?></b></font></td>
					<td><?php echo $row["no_hallazgo"] ?></td>
				</tr>
			
	
		</table>
	<?php
	die();
}


if($PERMISOS_GC["aud_gest"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
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
$mes_ges = $mes_ges + 0;
if($mes_ges < 9){$mm = '0'.$mes_ges; }else{$mm = $mes_ges;}

?>

<h2> AUDITORIAS ASIGNADA   </h2>

<table width="100%">
	<tr>
		<td>
			<h5>
			<a href="?mesg=<?php echo $mes_a;?>"><i class="fa fa-arrow-left"></i> Mes anterior</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo mes($mes_ges); ?></b> DEL <b><?php echo $ano_ges; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="?mesg=<?php echo $mes_s; ?>">Mes siguiente <i class="fa fa-arrow-right"></i></a>
			</h5>
		</td>
		<!--
		<td align=right> 	
			<a href='?cmp=offlinehfc'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Asignar <b>OFFLINE HFC</b> </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='?cmp=online'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Asignar <b>ONLINE</b></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='?cmp=offline'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Asignar <b>OFFLINE</b></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='?cmp=notramite'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Asignar <b>NO</b> tramites</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;					
		</td>
		-->
		<td align=right> 	
			<a href='?cmp=offlinehfcotros'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> <b>OFFLINE HFC TigoUne</b> </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='?cmp=offlinehfc'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> <b>OFFLINE HFC EIA</b> </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='?cmp=online'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> <b>ONLINE</b></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='?cmp=offline'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> <b>OFFLINE</b></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='?cmp=notramite'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> <b>NO</b> tramites</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;					
		</td>
		
	</tr>
	<tr>
		<td colspan=2>
			<a href="modulos/auditoria/lista_gestion.php?excel=1&ayo=<?php echo $ano_ges; ?>&mes=<?php echo $mes_ges ?>" target=blank> <i class="fa fa-cloud-download fa-2x"></i> Descargar por categoria </a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="modulos/auditoria/lista_gestion.php?excel=2&ayo=<?php echo $ano_ges; ?>&mes=<?php echo $mes_ges ?>" target=blank> <i class="fa fa-cloud-download fa-2x"></i> Descargar por Item </a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="modulos/auditoria/excel_material.php?ayo=<?php echo $ano_ges; ?>&mes=<?php echo $mes_ges ?>" target=blank> <i class="fa fa-cloud-download fa-2x"></i> Descargar por material </a>
		
		</td>
	</tr>
	
	
</table>

<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	
</form><br><br>
<center>	
<div class="panel panel-default"  style="width:100%">
	<div class="panel-heading" align=left>
		 listado de auditorias
	</div>
	<div class="panel-body" >
		<div class="table-responsive" >
		
			<table class="table table-striped table-bordered table-hover" >
				<thead>
					<tr>
						<th>Auditoria</th>	
						<th>Auditor</th>						
						<th>Estado</th>	
						<th>Tipo</th>
						<th>Fecha asignacion</th>								
						<th>Tramite / Numero</th>
						<th>Tecnico</th>
						<th>Detalle</th>
						<th>Eliminar</th>
					</tr>
				</thead>
				<tbody>
				<?php				
				
				$sql = "SELECT aud_base.nombre AS nom_base, usuario.nombre AS nom_usuario, usuario.apellido AS ape_usuario, aud_solicitud.estado, aud_solicitud.id,
				aud_solicitud.fecha_registro, tramite.ot, tramite.id AS offline, sra_tramite.id AS online, sra_tramite.pedido, tecnico.nombre AS nom_tecnico,
				une_pedido.id AS offlinehfc, une_pedido.numero
				FROM aud_solicitud 
				LEFT JOIN aud_base ON aud_solicitud.id_base = aud_base.id
				LEFT JOIN sra_tramite ON aud_solicitud.id_sra_tramite = sra_tramite.id
				LEFT JOIN une_pedido ON aud_solicitud.id_une_pedido = une_pedido.id
				LEFT JOIN tramite ON aud_solicitud.id_tramite = tramite.id
				LEFT JOIN tecnico ON tramite.id_tecnico = tecnico.id
				LEFT JOIN usuario ON aud_solicitud.id_realizado = usuario.id				
				WHERE aud_solicitud.fecha_registro >= '".$ano_ges."-".$mes_ges."-01 00:00:00' AND 
				aud_solicitud.fecha_registro <= '".$ano_ges."-".$mes_ges."-31 23:59:59'
				ORDER BY aud_solicitud.fecha_registro DESC";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
						$estado = '';
						if($row["estado"]==1){$estado = '<font color=red>Sin gestionar</font>';}
						if($row["estado"]==2){$estado = 'Inicializado';}
						if($row["estado"]==3){$estado = '<font color=green>Finalizado</font>';}	

						$tipo = 'NO tramites'; $tramite = '----';
						if($row["offline"]){$tipo = 'OFF LINE';$tramite = $row["ot"]; $tecnico = $row["nom_tecnico"];}
						if($row["online"]){$tipo = 'ON LINE';$tramite = $row["pedido"];}
						if($row["offlinehfc"]){$tipo = 'OFF LINE HFC';$tramite = $row["numero"];}
												
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["nom_base"] ?> </b></td>
						<td> <?php echo $row["ape_usuario"] ?>  <?php echo $row["nom_usuario"] ?> </td>
						<td><b><?php echo $estado ?></b></td>			
						<td><b><?php echo $tipo ?></b></td>
						<td> <?php echo $row["fecha_registro"] ?></td>
						<td><b><?php echo $tramite ?></b></td>
						<td><?php echo $tecnico ?></td>
						<td align=center><a href="?cmp=ejecutar_vista&id=<?php echo $row["id"]; ?>" ><i class="fa fa-eye fa-2x"> </i></a>	</td>	
						<td align=center>
							<?php if($row["estado"]==1){ ?>
								<a href="?elim=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR esta auditoria ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a>	
							<?php }else{ ?>
								----
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
</center>