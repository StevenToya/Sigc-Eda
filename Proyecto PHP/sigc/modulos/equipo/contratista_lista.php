<?php  
if($PERMISOS_GC["liq_mat_cont"]!='Si'){   
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

if(!$_SESSION["ss_fecha"]){$ss_fecha = date("Y-m");}else{$ss_fecha = $_SESSION["ss_fecha"];}
$where = " tramite.fecha_atencion_orden >= '".$ss_fecha."-01 00:00:00' AND  tramite.fecha_atencion_orden  <= '".$ss_fecha."-31 23:59:59' ";
if(!$_SESSION["ss_tecnologia"]){$ss_tecnologia = "TODAS";}else{$ss_tecnologia = $_SESSION["ss_tecnologia"]; $where = $where." AND tramite.id_tecnologia = '".$_SESSION["ss_tecnologia"]."' ";}
if(!$_SESSION["ss_tipo_trabajo"]){ $ss_tipo_trabajo = "TODAS";}else{$ss_tipo_trabajo = $_SESSION["ss_tipo_trabajo"];   $where = $where." AND tramite.id_tipo_trabajo = '".$_SESSION["ss_tipo_trabajo"]."' ";}
if(!$_SESSION["ss_zona"]){$ss_zona = "TODAS";}else{$ss_zona = $_SESSION["ss_zona"];  $where = $where." AND tramite.departamento = '".$_SESSION["ss_zona"]."' "; }
if(!$_SESSION["ss_servicio"]){$ss_servicio= "TODAS";}else{$ss_servicio = $_SESSION["ss_servicio"];  $where = $where." AND liquidacion_zona.servicio = '".$_SESSION["ss_servicio"]."' ";  }
if(!$_SESSION["ss_tipo_tramite"]){ $ss_tipo_tramite = "TODAS";}
else
{
	if($_SESSION["ss_tipo_tramite"]==1){$ss_tipo_tramite = "Instalacion"; $where = $where." AND (tipo_trabajo.tipo = '1' && tramite.tipo_paquete!='Traslado') ";}
	if($_SESSION["ss_tipo_tramite"]==2){$ss_tipo_tramite = "Reconexion"; $where = $where." AND (tipo_trabajo.tipo = '2' && tramite.tipo_paquete!='Traslado') ";}
	if($_SESSION["ss_tipo_tramite"]==3){$ss_tipo_tramite = "Reparacion"; $where = $where." AND (tipo_trabajo.tipo = '3' && tramite.tipo_paquete!='Traslado') ";}
	if($_SESSION["ss_tipo_tramite"]==4){$ss_tipo_tramite = "Suspension"; $where = $where." AND (tipo_trabajo.tipo = '4' && tramite.tipo_paquete!='Traslado') ";}
	if($_SESSION["ss_tipo_tramite"]==5){$ss_tipo_tramite = "Retiro"; $where = $where." AND (tipo_trabajo.tipo = '5' && tramite.tipo_paquete!='Traslado') "; }
	if($_SESSION["ss_tipo_tramite"]==6){$ss_tipo_tramite = "Prematricula"; $where = $where." AND (tipo_trabajo.tipo = '6' && tramite.tipo_paquete!='Traslado') "; }
	if($_SESSION["ss_tipo_tramite"]==7){$ss_tipo_tramite = "Traslado"; $where = $where." AND ((tipo_trabajo.tipo = '7' || tramite.tipo_paquete='Traslado')) "; }	
	if($_SESSION["ss_tipo_tramite"]==8){$ss_tipo_tramite = "Instalacion y Traslado"; $where = $where." AND ((tipo_trabajo.tipo = '7' || tramite.tipo_paquete='Traslado') || tipo_trabajo.tipo = '1' ) "; } 	
}

if($_GET["tip"]==5)
{	
	$sql_crit = " SELECT nombre FROM tipo_trabajo WHERE id = '".$_GET["criterio"]."' LIMIT 1";
	$res_crit = mysql_query($sql_crit);
	$row_crit = mysql_fetch_array($res_crit);
	
	$titulo = " EL TIPO DE TRABAJO <b>".$row_crit["nombre"]."</b>";	
	$where_tem = $where." AND tramite.id_tipo_trabajo =  '".$_GET["criterio"]."' "; 	
}

/* GUARDAR VALORES */

$sql = " SELECT tramite.ot, tipo_trabajo.nombre AS nom_tt , tipo_trabajo.codigo, tecnologia.nombre, tipo_trabajo.tipo, tramite.id, 
		tecnologia.nombre AS nom_tecnologia, tipo_paquete, 	contratista_valor, contratista_equipo, contratista_material
		FROM tramite 
		INNER JOIN tipo_trabajo ON 
			tramite.id_tipo_trabajo = tipo_trabajo.id
		LEFT JOIN tecnologia ON 
			tramite.id_tecnologia = tecnologia.id
		WHERE tramite.estado_liquidacion=2 AND ".$where_tem." 
		ORDER BY tramite.id_tipo_trabajo ";
$res = mysql_query($sql);
while($row = mysql_fetch_array($res))
{
	$equipo_tem = "che_equipo_".$row["id"];  
	$observacion_tem =  "obs_".$row["id"]; 
	if($_POST[$equipo_tem])
	{
		$sql = "UPDATE `tramite` SET 
		`id_usuario_revisa_equipo` = '".$_SESSION["user_id"]."', 
		`contratista_equipo` ='".$_POST[$equipo_tem]."',  
		`observacion_contratista_equipo` = '".$_POST[$observacion_tem]."'
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

<?php
$sql = " SELECT SUM(tramite.valor_liquidado) AS liquidado,  COUNT(*) AS cantidad_liq, 
SUM(IF(contratista_valor=2,tramite.valor_liquidado,0)) AS aceptado,
SUM(IF(contratista_valor=2,1,0)) AS cantidad_acep 
FROM tramite WHERE tramite.estado_liquidacion=2 AND ".$where_tem." ";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
?>


<h2>TRAMITES REALIZADOS PARA  <?php echo $titulo; ?></h2>
<div align=right>
	<a href="?cmp=contratista"> <i class="fa fa-reply fa-2x"></i> Volver al panel inicial</a>
</div>
<h5>
<center>
	<div class="col-md-100 col-sm-100" style="width:90%">
		<div class="panel panel-warning" >
			<div class="panel-heading"  align=left>
				<table width=100%>
					<tr>
						<td><b>Configuracion de la busqueda</b></td>
						<td align=right>  </td>
					</tr>
				</table>
			</div>
			<div class="panel-body">
				<table align=center  width=100%>
					<tr>
						<?php
							if($ss_tecnologia!="TODAS")
							{
								$sql_tt = "SELECT nombre  FROM `tecnologia` WHERE id = '".$ss_tecnologia."' LIMIT 1";
								$res_tt = mysql_query($sql_tt);
								$row_tt = mysql_fetch_array($res_tt);
								$ss_tecnologia = $row_tt["nombre"];
							}
						?>
						<td  width=5% >Tecnologia: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width=11%><b><?php echo $ss_tecnologia; ?></b></td>
						<td  width=5% >Departamento: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width=11%><b><?php echo $ss_zona; ?></b></td>
						<td  width=5% >Tramite: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width=11%><b><?php echo $ss_tipo_tramite; ?>	</b></td>					
					</tr>
					
					<tr>
						<td  >Servicio: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b><?php echo $ss_servicio; ?></b></td>	
						<?php
							if($ss_tipo_trabajo!="TODAS")
							{
								$sql_tt = "SELECT nombre  FROM `tipo_trabajo` WHERE id = '".$ss_tipo_trabajo."' LIMIT 1";
								$res_tt = mysql_query($sql_tt);
								$row_tt = mysql_fetch_array($res_tt);
								$ss_tipo_trabajo = $row_tt["nombre"];
							}
						?>
						<td>Tipo de trabajo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan=3><b><?php echo $ss_tipo_trabajo; ?></b></td>	
							
					</tr>
					
					<tr>
						
						<td colspan=6 align=center>
							<h5>								
										<font color=red>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo mes($mes_ges); ?></b> DEL <b><?php echo $ano_ges; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	</font>							
							</h5>
						</td>										
					</tr>
				</table>
				<!--
				<hr>
					<table width=100%>
						<td align=center width=50%>
						<center>
						Tramites liquidadas EDATEL <b>(<?php echo $row["cantidad_liq"]; ?>)</b> <br>
								<font color=red><b>$ <?php echo moneda($row["liquidado"]);  ?></b></font>
						</center>
						</td>
						<td align=center width=50%>
						<center>
						Tramites Aceptados EIA <b>(<?php echo $row["cantidad_acep"]; ?>)</b> <br>
								<font color=red><b>$ <?php echo moneda($row["aceptado"]);  ?></b></font>
						</center>
						</td>
					</table>
				-->
			</div>
		</div>
	</div>
</h5>

</center>	

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
			 <th><center>Observacion  EDATEL</center></th>	
			<th>Det.</th>           		
			<!-- <th style="background-color:#BDBDBD"><center>Valor</center></th> -->
			<th style="background-color:#E3F6CE"><center>Equipo</center></th> 
			<!-- <th style="background-color:#F2F5A9"><center>Material</center></th> -->			
			<th><center>Observacion  EIA</center></th>
		
		</tr>
		<?php
					
		$sql = " SELECT tramite.ot, tipo_trabajo.nombre AS nom_tt , tipo_trabajo.codigo, tecnologia.nombre, tipo_trabajo.tipo, tramite.id, valor_liquidado,
		tecnologia.nombre AS nom_tecnologia, tipo_paquete, 	contratista_valor, contratista_equipo, contratista_material, observacion_contratista, observacion_contratista_material,
		observacion_edatel, observacion_edatel_material, observacion_edatel_equipo, observacion_contratista_equipo
					FROM tramite 
					INNER JOIN tipo_trabajo ON 
						tramite.id_tipo_trabajo = tipo_trabajo.id
					LEFT JOIN tecnologia ON 
						tramite.id_tecnologia = tecnologia.id
					WHERE tramite.estado_liquidacion=2 AND ".$where_tem." 
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
				if($row["contratista_valor"]==4){ $contratista_valor = "<font color=red><b>Volver a revisar</b></font>";}

				$contratista_equipo = '';
				if($row["contratista_equipo"]==1){ $contratista_equipo = "<b>Omitido</b>";} 
				if($row["contratista_equipo"]==2){ $contratista_equipo = "<font color=green><b>Aceptado</b></font>";} 
				if($row["contratista_equipo"]==3){ $contratista_equipo = "<font color=red><b>Rechazado</b></font>";}
				if($row["contratista_equipo"]==4){ $contratista_equipo = "<font color=red><b>Volver a revisar</b></font>";}
				
				$contratista_material = '';
				if($row["contratista_material"]==1){ $contratista_material = "<b>Omitido</b>";} 
				if($row["contratista_material"]==2){ $contratista_material = "<font color=green><b>Aceptado</b></font>";} 
				if($row["contratista_material"]==3){ $contratista_material = "<font color=red><b>Rechazado</b></font>";}
				if($row["contratista_material"]==4){ $contratista_material = "<font color=red><b>Volver a revisar</b></font>";}
					
				?>
				<tr>
					<td><?php echo $row["ot"] ?></td>
					<!-- <td><?php echo $row["fecha_atencion_orden"] ?></td>-->
					<td><?php echo $row["nom_tt"] ?></td>
					<td><?php echo $row["nom_tecnologia"] ?></td>
					<td><?php echo $row["tipo_paquete"]; ?></td>
					<td align=center>
						<!-- Valor:<?php echo $contratista_valor ?><br> -->
					    <?php echo $contratista_equipo ?>
						<!--<?php echo $contratista_material ?>-->
					</td>					
					<td><?php echo $row["observacion_edatel_equipo"]; ?></td>					
					<td align=center> <a href="javascript:popUpmensaje('modulos/liquidacion/detalle_contratista.php?id=<?php echo $row["id"]; ?>')"> <i class="fa fa-eye fa-2x"> </i> </a></td>
				
					
				
				<!--
					<td align=center style="background-color:#BDBDBD">
						<select  name="che_valor_<?php echo $row["id"] ?>" >
							<option  <?php if($row["contratista_valor"]==1){ ?> selected <?php } ?> value="1">Omitir  </option>
							<option  <?php if($row["contratista_valor"]==2){ ?> selected <?php } ?> value="2">Aceptar</option>
							<option  <?php if($row["contratista_valor"]==3){ ?> selected <?php } ?> value="3">Rechazar</option>							
						</select>						
					</td> -->
					<td align=center style="background-color:#E3F6CE">
						<select  name="che_equipo_<?php echo $row["id"] ?>" >
							<option <?php if($row["contratista_equipo"]==1){ ?> selected <?php } ?> value="1">Omitir</option>
							<option <?php if($row["contratista_equipo"]==2){ ?> selected <?php } ?> value="2">Aceptar</option>
							<option <?php if($row["contratista_equipo"]==3){ ?> selected <?php } ?> value="3">Rechazar</option>							
						</select>	
					</td>
					<!--
					<td align=center style="background-color:#F2F5A9">
						<select  name="che_material_<?php echo $row["id"] ?>" >
							<option <?php if($row["contratista_material"]==1){ ?> selected <?php } ?> value="1">Omitir</option>
							<option <?php if($row["contratista_material"]==2){ ?> selected <?php } ?> value="2">Aceptar</option>
							<option <?php if($row["contratista_material"]==3){ ?> selected <?php } ?> value="3">Rechazar</option>						
						</select>	
					</td>	
					-->
					<td align=center><textarea name="obs_<?php echo $row["id"] ?>" ><?php echo $row["observacion_contratista_equipo"] ?></textarea> </td>
				
				</tr>
				<?php
			}							
			?>		
	</table>
	<center><input type="submit" value="Guardar valores" name="guardar" class="btn btn-primary"></center>
</form>			



