<?php  
if($PERMISOS_GC["liq_ges"]!='Si'){   
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
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

if(!$_SESSION["ss_fecha_inicial"]){$ss_fecha_inicial = date("Y-m-d");}else{$ss_fecha_inicial = $_SESSION["ss_fecha_inicial"];}
if(!$_SESSION["ss_fecha_final"]){$ss_fecha_final = date("Y-m-d");}else{$ss_fecha_final = $_SESSION["ss_fecha_final"];}
$where = " tramite.fecha_atencion_orden >= '".$ss_fecha_inicial." 00:00:00' AND  tramite.fecha_atencion_orden  <= '".$ss_fecha_final." 23:59:59' ";
if(!$_SESSION["ss_tecnologia"]){$ss_tecnologia = "TODAS";}else{$ss_tecnologia = $_SESSION["ss_tecnologia"]; $where = $where." AND tramite.id_tecnologia = '".$_SESSION["ss_tecnologia"]."' ";}
if(!$_SESSION["ss_item"]){$ss_item = "TODAS";}else{$ss_item = $_SESSION["ss_item"];  $where = $where." AND liquidacion_zona.item = '".$_SESSION["ss_item"]."' ";}
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

if($_GET["tip"]==1)
{
	$sql_crit = " SELECT nombre FROM tecnologia WHERE id = '".$_GET["criterio"]."' LIMIT 1";
	$res_crit = mysql_query($sql_crit);
	$row_crit = mysql_fetch_array($res_crit);
	$titulo = " LA TECNOLOGIA <b>".$row_crit["nombre"]."</b>";
	
	$where_tem = $where." AND tramite.id_tecnologia =  '".$_GET["criterio"]."' "; 	
}

if($_GET["tip"]==2)
{	
	$titulo = " EL DEPARTAMENTO DE <b>".$_GET["criterio"]."</b>";	
	$where_tem = $where." AND tramite.departamento =  '".$_GET["criterio"]."' "; 	
}

if($_GET["tip"]==3)
{	
	$titulo = " EL SERVICIO DE <b>".str_replace("___", "+", $_GET["criterio"])."</b>";	
	$where_tem = $where." AND liquidacion_zona.servicio =  '".str_replace("___", "+", $_GET["criterio"])."' "; 	
}

if($_GET["tip"]==4)
{	
	$titulo = " EL ITEM DE <b>".str_replace("___", "+", $_GET["criterio"])."</b>";	
	$where_tem = $where." AND liquidacion_zona.item =  '".str_replace("___", "+", $_GET["criterio"])."' "; 	
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

$select = ", tramite.id";
$res = tramite_liquidacion($select, $where_tem, "", "", '2');
while($row = mysql_fetch_array($res))
{
	$tem = "rad_".$row["id"]; $tem2 = "tex_".$row["id"];
	if($_POST[$tem]==1)
	{
		$sql = "UPDATE `tramite` SET `id_usuario_liquida` = '".$_SESSION["user_id"]."', `estado_liquidacion` = '1', `valor_liquidado` = '' WHERE `id` = '".$row["id"]."' LIMIT 1";
		mysql_query($sql);
		
	}
	
	if($_POST[$tem]==2)
	{
		$sql = "UPDATE `tramite` SET `id_usuario_liquida` = '".$_SESSION["user_id"]."', `estado_liquidacion` = '2', `valor_liquidado` = '".numero_decimal($_POST[$tem2])."' WHERE `id` = '".$row["id"]."' LIMIT 1";
		mysql_query($sql);
	}
}
	
/* FIN GUARDAR VALORES */
?>	




<script type="text/javascript">


function marcar(source, filtro) 
{
	checkboxes=document.getElementsByTagName('input' ); //obtenemos todos los controles del tipo Input
	for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
	{
		if(checkboxes[i].type == "radio") //solo si es un checkbox entramos
		{
			if(checkboxes[i].id== filtro)
			{
				checkboxes[i].checked="checked"; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
}


function marcar_aceptar() 
{
	textt=document.getElementsByTagName('input' ); //obtenemos todos los controles del tipo Input
	for(i=0;i<textt.length;i++) //recoremos todos los controles
	{
		if(textt[i].type == "text") //solo si es un text entramos
		{			
			 
			 
			 
			 textt[i].disabled=false;

		
			 
			 hidden = textt[i].name + '_hidden';			 
			 
				//textt[i].value= hidden;	
			//textt[i].value = document.form.name(hidden).value;
			textt[i].value = document.getElementsByName(hidden)[0].value
		}
	}
}

function marcar_omitir() 
{
	textt=document.getElementsByTagName('input' ); //obtenemos todos los controles del tipo Input
	for(i=0;i<textt.length;i++) //recoremos todos los controles
	{
		if(textt[i].type == "text") //solo si es un checkbox entramos
		{			
			 textt[i].disabled=true;
			 textt[i].value="";			
		}
	}
}


function popUpmensaje(URL) 
{
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1350,height=650,left = 200,top = 5');");
}
<?php
	$select = ", tramite.id";
	$res = tramite_liquidacion($select, $where_tem, "", "", '2');
	while($row = mysql_fetch_array($res))
	{
?>			
	 function cambiar_<?php echo $row["id"] ?>(elemento, valor, estado, valor_oficial) 
	 {
		if(estado==1)
		{

				if(elemento.value=="1") 
				  {
					document.form.tex_<?php echo $row["id"] ?>.disabled=true;
					document.form.tex_<?php echo $row["id"] ?>.value='';				
				  }
				  
				  if(elemento.value=="2") 
				  {
					 document.form.tex_<?php echo $row["id"] ?>.disabled=false;
					 document.form.tex_<?php echo $row["id"] ?>.value=valor;				
				  }
		}
		
		if(estado==2)
		{
			
			if(elemento.value=="1") 
				  {
					document.form.tex_<?php echo $row["id"] ?>.disabled=true;
					document.form.tex_<?php echo $row["id"] ?>.value='';				
				  }
				  
				  if(elemento.value=="2") 
				  {
					 document.form.tex_<?php echo $row["id"] ?>.disabled=false;
					 document.form.tex_<?php echo $row["id"] ?>.value=valor_oficial;				
				  }
			
		}
	 }	
<?php
	}
?>
</script>


<h2>TRAMITES REALIZADOS PARA  <?php echo $titulo; ?></h2>
<div align=right>
	<a href="?cmp=gestionar"> <i class="fa fa-reply fa-2x"></i> Volver al panel inicial</a>
</div>
<h5>
<center>
	<div class="col-md-100 col-sm-100" style="width:90%">
		<div class="panel panel-warning" >
			<div class="panel-heading"  align=left>
				<b>Configuracion de la busqueda</b>
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
						<td  width=5% >Item: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width=11%><b><?php echo $ss_item; ?></b></td>					
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
						<td >Tramite: </td><td><b><?php echo $ss_tipo_tramite; ?></b></td>
						<td >Fecha Inicial: </td><td><b><font color=red><?php echo $ss_fecha_inicial; ?></font></b></td>
						<td >Fecha Final: </td><td><b><font color=red><?php echo $ss_fecha_final; ?></font></b></td>				
					</tr>
				</table>
				
			</div>
		</div>
	</div>
</h5>

</center>	

<form action="?tip=<?php echo $_GET["tip"] ?>&criterio=<?php echo $_GET["criterio"] ?>"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<table align=center width=100%  class="table table-striped table-bordered table-hover" >
		<tr>
			<th>OT</th>
			<th>Tipo de Trabajo</th>
			<th>Tecnologia</th>
			<th>Tipo tramite</th>
			<th>Caja adic.</th>
			<th>Exten.</th>
			<th>Automatico</th>
			<th>Oficial</th>
			<th>Estado EIA</th>			 
			<th>Det.</th>
			<th style="background-color:#BDBDBD"><center>Omit. <br><input type="radio" onclick="marcar(this, 'rad_omitir');marcar_omitir();" name="cabeza" /> </center></th>
			<th style="background-color:#E3F6CE"><center>Acep.<br><input type="radio" onclick="marcar(this, 'rad_aceptar');marcar_aceptar();" name="cabeza" /></center></th>
			<th style="background-color:#E3F6CE"><center>Valor a cambiar</center></th>
		
		</tr>
		<?php
			$select = ", tramite.ot, tecnologia.nombre AS nom_tecnologia, tramite.fecha_atencion_orden, tipo_trabajo.nombre , tipo_trabajo.tipo, 
			liquidacion_zona.valor, tramite.id, tramite.estado_liquidacion, tramite.valor_liquidado, tramite.tipo_paquete, contratista_valor";
			$res = tramite_liquidacion($select, $where_tem,  "",  "", '2');
			while($row = mysql_fetch_array($res))
			{
					$tramite = "";
					$row["valor_liquidado"] = $row["valor_liquidado"] + 0;
					if($row["tipo"]==1){$tramite = 'Instalacion';}
					if($row["tipo"]==2){$tramite = 'Reconexion';}
					if($row["tipo"]==3){$tramite = 'Reparacion';}
					if($row["tipo"]==4){$tramite = 'Suspension';}
					if($row["tipo"]==5){$tramite = 'Retiro';}
					if($row["tipo"]==6){$tramite = 'Prematricula';}
					if($row["tipo"]==7){$tramite = 'Traslado';}
					if($row["estado_liquidacion"]==1){$valor_oficial  = '<font color =red><center>NO INGRESADO</center></font>';}else{$valor_oficial  =  '$'.moneda($row["valor_liquidado"]);}
					
					$estado_eia = "";
					if($row["contratista_valor"]==1){$estado_eia = '<center>Omitido</center>';}
					if($row["contratista_valor"]==2){$estado_eia = '<font color =green><center>Aceptado</center></font>';}
					if($row["contratista_valor"]==3){$estado_eia = '<font color =red><center>Rechazado</center></font>';}
					
					
			$row["valor"] = $row["valor"] + 0;
				?>
				<tr>
					<td><?php echo $row["ot"] ?></td>
					<td><?php echo $row["nombre"] ?></td>
					<td><?php echo $row["nom_tecnologia"] ?></td>
					<td><?php echo $row["tipo_paquete"]; ?></td>
						<td  align=center><?php echo $row["tem_adicional"] ?> cajas</td>
						<td  align=center><?php echo $row["tem_extension"] ?> </td>						
					<td align=right><b>
						<?php if($row["garantia"]=='SIN GARANTIA'  || !$row["garantia"]){ ?>
							$<?php echo moneda($row["total_total"]) ?>
						<?php }else{ ?>
								<font color=red>CON GARANTIA</font>
						<?php } ?></b>
					</td>
					<td align=right><b> <?php echo $valor_oficial; ?></b></td>
					<td align=right> <?php echo $estado_eia; ?></td>
					<td align=center> <a href="javascript:popUpmensaje('modulos/liquidacion/detalle.php?id=<?php echo $row["id"]; ?>')"> <i class="fa fa-eye fa-2x"> </i> </a></td>
					<td align=center style="background-color:#BDBDBD"><input id="rad_omitir" onclick="cambiar_<?php echo $row["id"] ?>(this, '<?php echo moneda($row["total_total"]) ?>',<?php echo $row["estado_liquidacion"] ?>, <?php echo $row["valor_liquidado"] ?>)"  <?php if($row["estado_liquidacion"]==1){ ?> checked <?php } ?> value="1" name="rad_<?php echo $row["id"] ?>"  type="radio" ></td>
					<td align=center style="background-color:#E3F6CE"><input id="rad_aceptar" onclick="cambiar_<?php echo $row["id"] ?>(this, '<?php echo moneda($row["total_total"]) ?>', <?php echo $row["estado_liquidacion"] ?>, <?php echo $row["valor_liquidado"] ?>)"  <?php if($row["estado_liquidacion"]==2){ ?> checked <?php } ?> value="2"  name="rad_<?php echo $row["id"] ?>" type="radio" ></td>
					<td align=center style="background-color:#E3F6CE">
						<input type="text"  value="<?php echo $row["valor_liquidado"] ?>" name="tex_<?php echo $row["id"] ?>"  <?php if($row["estado_liquidacion"]==1){ ?> disabled <?php } ?> size="15" >
						<input type="hidden"  value="<?php echo moneda($row["total_total"]) ?>" name="tex_<?php echo $row["id"] ?>_hidden"  >
						<input type="hidden"  value="<?php echo $row["estado_liquidacion"] ?>" name="estado_<?php echo $row["id"] ?>_hidden"  >
					</td>					
				
				</tr>
				<?php
			}							
			?>		
	</table>
	<center><input type="submit" value="Guardar valores" name="guardar" class="btn btn-primary"></center>
</form>			



