<?php
if($PERMISOS_GC["aud_ejec"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}
?>

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
$mes_ges = $mes_ges + 0;
if($mes_ges < 9){$mm = '0'.$mes_ges; }else{$mm = $mes_ges;}

?>

<h2> TUS AUDITORIAS ASIGNADAS	   </h2>

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
						<th>Asignado por</th>						
						<th>Estado</th>	
						<th>Tipo</th>
						<th>Fecha asignacion</th>								
						<th>Tramite</th>
						<th>Tecnico</th>
						<th>Ejecutar</th>
						<th align=center>---</th>
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
				aud_solicitud.fecha_registro <= '".$ano_ges."-".$mes_ges."-31 23:59:59' AND aud_solicitud.id_realizado = '".$_SESSION["user_id"]."' 
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
						<td align=center><a href="?cmp=ejecutar&id=<?php echo $row["id"]; ?>" ><i class="fa fa-pencil-square fa-2x"> </i></a>	</td>		
						<td align="center"><a onClick='imprimir();' target="blank" href='modulos/auditoria/ejecutar_imprimir.php?cmp=ejecutar&id=<?php echo $row["id"]; ?>'>Imprimir</a>
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