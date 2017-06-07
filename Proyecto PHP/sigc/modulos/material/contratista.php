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


if($_GET["anog"] || $_GET["mesg"])
{
	$ano_ges =  $_GET["anog"];
	$mes_ges = $_GET["mesg"];
	
	if($_GET["mesg"] < 9){$mes_ges  = '0'.$_GET["mesg"]; }else{$mes_ges  = $_GET["mesg"];}	
	$_SESSION["ss_fecha"] = $ano_ges.'-'.$mes_ges; 
}
else
{
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
}


if($mes_ges=='01')
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
	if($_SESSION["ss_tipo_tramite"]==7){$ss_tipo_tramite = "Traslado"; $where = $where." AND (tipo_trabajo.tipo = '7' || tramite.tipo_paquete='Traslado') "; }  
	if($_SESSION["ss_tipo_tramite"]==8){$ss_tipo_tramite = "Instalacion y Traslado"; $where = $where." AND ((tipo_trabajo.tipo = '7' || tramite.tipo_paquete='Traslado') || tipo_trabajo.tipo = '1' ) "; } 	
}

$sql = " SELECT SUM(tramite.valor_liquidado) AS liquidado,  COUNT(*) AS cantidad_liq, 
SUM(IF(contratista_valor=2,tramite.valor_liquidado,0)) AS aceptado,
SUM(IF(contratista_valor=2,1,0)) AS cantidad_acep 
FROM tramite 
INNER JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
WHERE tramite.estado_liquidacion=2 AND ".$where." ";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
?>

<h2>TRAMITES PARA REVISION EIA <b>( MATERIALES )</b> </h2>

<h5>
<center>
	<div class="col-md-100 col-sm-100" style="width:90%">
		<div class="panel panel-warning" >
			<div class="panel-heading"  align=left>
				<table width=100%>
					<tr>
						<td><b>Configuracion de la busqueda</b></td>
						<td align=right>  <a href="?cmp=cambio_busqueda_contratista"><i class="fa fa-pencil-square-o fa-2x"></i></td>
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
						<?php
							if($ss_tipo_trabajo!="TODAS")
							{
								$sql_tt = "SELECT nombre  FROM `tipo_trabajo` WHERE id = '".$ss_tipo_trabajo."' LIMIT 1";
								$res_tt = mysql_query($sql_tt);
								$row_tt = mysql_fetch_array($res_tt);
								$ss_tipo_trabajo = $row_tt["nombre"];
							}
						?>
						
						
						<td><br>Tipo de trabajo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan=5><b><?php echo $ss_tipo_trabajo; ?></b></td>	
							
					</tr>
					
					<tr>
						
						<td colspan=6 align=center>
							<h5>
								<a href="?anog=<?php echo $ano_a;?>&mesg=<?php echo $mes_a;?>"><i class="fa fa-arrow-left"></i> Mes anterior</a>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo mes($mes_ges); ?></b> DEL <b><?php echo $ano_ges; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="?anog=<?php echo $ano_s;?>&mesg=<?php echo $mes_s; ?>">Mes siguiente <i class="fa fa-arrow-right"></i></a>
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
</center>

<table width=100%>
	<tr>
		<td>
			<form  action="modulos/liquidacion/detalle_contratista.php" target="blank" method="post" name="form" id="form"  enctype="multipart/form-data">
				<div class="row">
				   <div class="col-lg-6">
					<div class="input-group">
					  <input type="text" class="form-control" name="ot" placeholder="Ingresar OT..." required>
					  <span class="input-group-btn">
						<button class="btn btn-primary"  type="submit">Buscar</button>
					  </span>
					</div>
				  </div>
				</div>
			</form>
		</td>
		<td align=right>
			<a href="modulos/liquidacion/excel_contratista.php?tip=10" target="blank"><i class="fa fa-cloud-download fa-2x"></i> Descargar toda la consulta </a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		
		</td>
	</tr>
</table>
<br>
		
<div class="col-md-100 col-sm-100" style="width:100%">
	<div class="panel panel-primary" >
		<div class="panel-heading"  align=left>
			<b>Tramites por tipo de trabajo</b>							
		</div>
		<div class="panel-body">
			<table  class="table table-striped table-bordered table-hover" align=center width=100%>
				<tr>
					<th>Tipo de Trabajo</th>
					<th>Codigo</th>
					<th>Cant.</th>
					<th>Cantidad <br> Omitida</th>
					<th>Cantidad <br> Aceptada</th>
					<th>Cantidad <br> Rechazada</th>
					<th>Cantidad <br> Volver a <br> Revisar</th>
					<th><center>Excel</center></th>
					<th><center>Gest.</center></th>
				</tr>
				<?php
														
					$sql = " SELECT COUNT(*) AS cantidad,  
					SUM(IF(tramite.contratista_material=1,1,0)) AS omitido,
					SUM(IF(tramite.contratista_material=2,1,0)) AS confirmado,
					SUM(IF(tramite.contratista_material=3,1,0)) AS rechazado,
					SUM(IF(tramite.contratista_material=4,1,0)) AS volver,					
					id_tipo_trabajo,
					SUM(IF(contratista_valor=2,tramite.valor_liquidado,0)) AS aceptado,	tipo_trabajo.nombre, tipo_trabajo.codigo
					FROM tramite 
					INNER JOIN tipo_trabajo ON tramite.id_tipo_trabajo = tipo_trabajo.id
					WHERE tramite.estado_liquidacion=2 AND ".$where." 
					GROUP BY tramite.id_tipo_trabajo ";
					$res = mysql_query($sql);
					while($row = mysql_fetch_array($res))
					{											
						?>
						<tr>
							<td><?php echo $row["nombre"] ?></td>
							<td><?php echo $row["codigo"] ?></td>
							<td  align=center><?php echo $row["cantidad"] ?></td>
							
							<td align=center><b><?php echo $row["omitido"] ?></b></td>
							<td align=center><b><font color=green><?php echo $row["confirmado"] ?></font></b></td>
							<td align=center><b><font color=red><?php echo $row["rechazado"] ?></font></b></td>
							<td align=center><b><font color=orange><?php echo $row["volver"] ?></font></b></td>
							<!-- <td align=right><b>$ <?php echo moneda($row["liquidado"]) ?></b></td> -->
							<!-- <td align=right><b><font color=red>$ <?php echo moneda($row["aceptado"]) ?></font></b></td> -->
							<td align=center><a href="modulos/liquidacion/excel_contratista.php?tip=5&criterio=<?php echo $row["id_tipo_trabajo"]; ?>" target="blank"><b><i class="fa fa-cloud-download"></i></b></a></td>
							<td align=center><a href="?cmp=contratista_lista&tip=5&criterio=<?php echo $row["id_tipo_trabajo"]; ?>"><b><i class="fa fa-pencil-square-o"></i></b></a></td>
						</tr>
						<?php
					}							
					?>		
			</table>
		</div>
	</div>
</div>

			
