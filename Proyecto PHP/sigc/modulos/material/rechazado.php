<?php  
 if($PERMISOS_GC["mat_ges"]!='Si'){
	 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}  

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


function numero_decimal($str) 
{ 
  if(strstr($str, ",")) { 
    $str = str_replace(".", "", $str); 
    $str = str_replace(",", ".", $str);
  }   
  if(preg_match("#([0-9\.]+)#", $str, $match)) { 
    return floatval($match[0]); 
  } else { 
    return floatval($str); 
  } 
}

if(!$_SESSION["ss_fecha"])
{
	$mes_ges = date("m");
	$ano_ges =  date("Y");
}
else
{
	$vect_fecha = explode('-',$_SESSION["ss_fecha"]);
	$mes_ges = $vect_fecha["1"];
	$ano_ges = $vect_fecha["0"];;
}


/* GUARDAR VALORES */

$sql = " SELECT tramite.ot, tipo_trabajo.nombre AS nom_tt , tipo_trabajo.codigo, tecnologia.nombre, tipo_trabajo.tipo, tramite.id, valor_liquidado,
		tecnologia.nombre AS nom_tecnologia, tipo_paquete, 	contratista_valor, contratista_equipo, contratista_material, observacion_contratista, observacion_contratista_material,
		observacion_edatel, observacion_edatel_material, observacion_edatel_equipo
					FROM tramite 
					INNER JOIN tipo_trabajo ON 
						tramite.id_tipo_trabajo = tipo_trabajo.id
					LEFT JOIN tecnologia ON 
						tramite.id_tecnologia = tecnologia.id
					WHERE tramite.estado_liquidacion=2 AND tramite.contratista_material IN ('3','4') 
				 ";
$res = mysql_query($sql);
while($row = mysql_fetch_array($res))
{
	$material_tem = "che_material_".$row["id"]; 
	$observacion_tem =  "obs_".$row["id"]; 
	if($_POST[$material_tem])
	{
		$sql = "UPDATE `tramite` SET 
		`id_usuario_liquida_material` = '".$_SESSION["user_id"]."', 
		`contratista_material` ='".$_POST[$material_tem]."',  
		`observacion_edatel_material` = '".$_POST[$observacion_tem]."'
		WHERE `id` = '".$row["id"]."' LIMIT 1";
		mysql_query($sql);		
	}
}
	
/* FIN GUARDAR VALORES */
?>	




<script type="text/javascript">


function popUpmensaje(URL) 
{
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1350,height=650,left = 200,top = 5');");
}

</script>


<h2>TRAMITES RECHAZADOS POR MATERIALES</h2>
<div align=right>
	<a href="?cmp=panel_material"> <i class="fa fa-reply fa-2x"></i> Volver al panel inicial</a>
</div>


<script>
var select = document.getElementById('mySelect');
select.onchange = function () {
    select.className = this.options[this.selectedIndex].className;
}

</script>
<style>
.redText {
    background-color:#F00;
}
.greenText {
    background-color:#0F0;
}
.blueText {
    background-color:#00F;
}
</style>


<form action="?tip=<?php echo $_GET["tip"] ?>&criterio=<?php echo $_GET["criterio"] ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<table align=center width=100%  class="table table-striped table-bordered table-hover" >
		<tr>
			<th>OT</th>
			<th>Tipo de Trabajo</th>
			<th>Tecnologia</th>
			<th>Tipo tramite</th>
			<th>Estado Actual</th>
			<th><center>Observacion  EIA</center></th>
			<th>Det.</th>           		
			<th style="background-color:#F2F5A9"><center>Material</center></th>		
			<th><center>Observacion  EDATEL</center></th>	
		</tr>
		<?php
					
		$sql = " SELECT tramite.ot, tipo_trabajo.nombre AS nom_tt , tipo_trabajo.codigo, tecnologia.nombre, tipo_trabajo.tipo, tramite.id, valor_liquidado,
		tecnologia.nombre AS nom_tecnologia, tipo_paquete, 	contratista_valor, contratista_equipo, contratista_material, observacion_contratista, observacion_contratista_material,
		observacion_edatel, observacion_edatel_material, observacion_edatel_equipo
					FROM tramite 
					INNER JOIN tipo_trabajo ON 
						tramite.id_tipo_trabajo = tipo_trabajo.id
					LEFT JOIN tecnologia ON 
						tramite.id_tecnologia = tecnologia.id
					WHERE tramite.estado_liquidacion=2 AND tramite.contratista_material IN ('3','4') 
					ORDER BY tramite.id_tipo_trabajo ";
			$res = mysql_query($sql);
			while($row = mysql_fetch_array($res))
			{
				$tramite = "";
				if($row["tipo"]==1){$tramite = 'Instalacion';}
				if($row["tipo"]==2){$tramite = 'Reconexion';}
				if($row["tipo"]==3){$tramite = 'Reparacion';}
				if($row["tipo"]==4){$tramite = 'Suspension';}
				if($row["tipo"]==5){$tramite = 'Retiro';}
				if($row["tipo"]==6){$tramite = 'Prematricula';}
				if($row["tipo"]==7){$tramite = 'Traslado';}
				
				$contratista_valor = '';
				if($row["contratista_valor"]==1){ $contratista_valor = "<b>Omitido</b>";} 
				if($row["contratista_valor"]==2){ $contratista_valor = "<font color=green><b>Aceptado</b></font>";} 
				if($row["contratista_valor"]==3){ $contratista_valor = "<font color=red><b>Rechazado</b></font>";}
				if($row["contratista_valor"]==4){ $contratista_valor = "<font color=orange><b>Pendiente EIA</b></font>";}

				$contratista_equipo = '';
				if($row["contratista_equipo"]==1){ $contratista_equipo = "<b>Omitido</b>";} 
				if($row["contratista_equipo"]==2){ $contratista_equipo = "<font color=green><b>Aceptado</b></font>";} 
				if($row["contratista_equipo"]==3){ $contratista_equipo = "<font color=red><b>Rechazado</b></font>";}
				if($row["contratista_equipo"]==4){ $contratista_equipo = "<font color=orange><b>Pendiente EIA</b></font>";}
				
				$contratista_material = '';
				if($row["contratista_material"]==1){ $contratista_material = "<b>Omitido</b>";} 
				if($row["contratista_material"]==2){ $contratista_material = "<font color=green><b>Aceptado</b></font>";} 
				if($row["contratista_material"]==3){ $contratista_material = "<font color=red><b>Rechazado</b></font>";}
				if($row["contratista_material"]==4){ $contratista_material = "<font color=orange><b>Pendiente EIA</b></font>";}
					
				?>
				<tr>
					<td><?php echo $row["ot"] ?></td>
					<!-- <td><?php echo $row["fecha_atencion_orden"] ?></td>-->
					<td><?php echo $row["nom_tt"] ?></td>
					<td><?php echo $row["nom_tecnologia"] ?></td>
					<td><?php echo $row["tipo_paquete"]; ?></td>
					<td align=center><?php echo $contratista_material ?></td>					
					<td><?php echo $row["observacion_contratista_material"] ?></td>					
					<td align=center> <a href="javascript:popUpmensaje('modulos/liquidacion/detalle.php?id=<?php echo $row["id"]; ?>')"> <i class="fa fa-eye fa-2x"> </i> </a></td>
					
					<td align=center style="background-color:#F2F5A9">
						<?php if($row["contratista_material"]!=3){ ?>
							-----
						<?php }else{ ?>
							<select  name="che_material_<?php echo $row["id"] ?>" >
								<option  value="">Sin gestionar</option>
								<option  value="4">Enviar para aceptar</option>											
							</select>	
						<?php } ?>
					</td>	

					<td align=center><textarea name="obs_<?php echo $row["id"] ?>" ><?php echo $row["observacion_edatel_material"]; ?></textarea> </td>
				
				</tr>
				<?php
			}							
			?>		
	</table>
	<center><input type="submit" value="Guardar valores" name="guardar" class="btn btn-primary"></center>
</form>			

