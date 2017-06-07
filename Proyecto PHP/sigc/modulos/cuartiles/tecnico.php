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
		if(textt[i].type == "text") //solo si es un checkbox entramos
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

</script>


<h2>TRAMITES REALIZADOS <!--PARA  <?php echo $titulo; ?> --></h2>
<div align=right>
	<a href="?cmp=lista"> <i class="fa fa-reply fa-2x"></i> Volver al panel inicial</a>
</div>

<table align=center width=100%  class="table table-striped table-bordered table-hover" >
		<tr>
			<th>OT</th>
			<th>Nombre Cliente</th>	
			<th>Region</th>
			<th>Zona</th>				
			<th>Fecha_atencion_orden</th>
			<th>Tipo tramite</th>
			<th>Garantia</th>
			<th>Det.</th>	
		</tr>
		<?php
		
		
		
			$sql = "SELECT tramite.tipo_garantia, tramite.ot, tramite.region, tramite.zona, tramite.nombre_cliente, 
			tramite.fecha_atencion_orden, tipo_paquete, tramite.id,
			IF(datediff(fecha_atencion_orden,fecha_ot_antecesor)<31,'sg','ng') AS garan
			FROM tramite 
			INNER JOIN tipo_trabajo ON  tramite.id_tipo_trabajo = tipo_trabajo.id
			WHERE			
			ultimo =  's'
			AND fecha_atencion_orden >=  '".$_SESSION["cuar_fecha_ini"]." 00:00:00'
			AND fecha_atencion_orden <=  '".$_SESSION["cuar_fecha_fin"]." 23:59:59'
			AND tramite.codigo_unidad_operativa =  '2000'
			AND descripcion_dano  NOT LIKE  '%o masivo%' 
			AND descripcion_dano  NOT LIKE  '%Infundado%'
			AND id_tecnico = '".$_GET["id"]."' 
			AND tipo_trabajo.tipo=3
			".$query." ;";
			$res = mysql_query($sql);
			while($row = mysql_fetch_array($res))
			{
					/*
					$tramite = "";
					if($row["tipo"]==1){$tramite = 'Instalacion';}
					if($row["tipo"]==2){$tramite = 'Reconexion';}
					if($row["tipo"]==3){$tramite = 'Reparacion';}
					if($row["tipo"]==4){$tramite = 'Suspension';}
					if($row["tipo"]==5){$tramite = 'Retiro';}
					if($row["tipo"]==6){$tramite = 'Prematricula';}
					if($row["tipo"]==7){$tramite = 'Traslado';}
					*/
					
					$tipo_garantia ="--";
					if($row["tipo_garantia"]==1 && $row["garan"]=='sg'){$tipo_garantia = "Con garantia por instalacion";}
					if($row["tipo_garantia"]==2 && $row["garan"]=='sg'){$tipo_garantia = "Con garantia por da&ntilde;o reincidente ";}
					if($row["tipo_garantia"]==3 && $row["garan"]=='sg'){$tipo_garantia = "Con garantia por da&ntilde;o reiterativo";}
					
					
				?>
				<tr>
					<td><?php echo $row["ot"] ?></td>
					<td><?php echo $row["region"] ?></td>
					<td><?php echo $row["zona"] ?></td>
					<td><?php echo $row["nombre_cliente"] ?></td>
					<td><?php echo $row["fecha_atencion_orden"] ?></td>
					<td><?php echo $row["tipo_paquete"]; ?></td>	
					<td><?php echo $tipo_garantia; ?></td>	
					<td align=center> <a href="javascript:popUpmensaje('modulos/liquidacion/detalle.php?id=<?php echo $row["id"]; ?>')"> <i class="fa fa-eye fa-2x"> </i> </a></td>					
				</tr>
				<?php
			}							
			?>		
	</table>
			



