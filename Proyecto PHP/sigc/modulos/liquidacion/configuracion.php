<?php  
if($PERMISOS_GC["liq_conf"]!='Si'){   
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}  

function numero_decimal($str) 
{ 
  if(strstr($str, ",")) { 
    $str = str_replace(".", "", $str); 
    $str = str_replace(",", ".", $str);
  } 
  
  if(preg_match("#([0-9\.]+)#", $str, $match)) { // search for number that may contain '.' 
    return floatval($match[0]); 
  } else { 
    return floatval($str); // take some last chances with floatval 
  } 
} 



if($_GET["t_t"])
{	$_SESSION["t_t"] = $_GET["t_t"];} 

if($_GET["s_t_t"])
{	$_SESSION["t_t"] = "";} 


if($_POST["tecn_ing"] && $_POST["tt_ing"])
{
	$sql_buscar = "SELECT id FROM liquidacion_zona WHERE 
	id_tipo_trabajo = '".$_POST["tt_ing"]."' 
	AND id_tecnologia = '".$_POST["tecn_ing"]."' 
	AND id_zona = '".$_SESSION["zon"]."' LIMIT 1";
	
	$res_buscar = mysql_query($sql_buscar);
	$row_buscar = mysql_fetch_array($res_buscar);
	
	if(!$row_buscar["id"])
	{
		$sql_insert = "INSERT INTO liquidacion_zona (id_tipo_trabajo, id_tecnologia, id_zona) 
		VALUES ('".$_POST["tt_ing"]."', '".$_POST["tecn_ing"]."', '".$_SESSION["zon"]."')";
		mysql_query($sql_insert);
	}
		
	
}



if($_POST["guardar"])
{
	$tem_tipo = "";
	if($_SESSION["t_t"]==1){ $tem_tipo = " OR  tipo_trabajo.tipo = '7' "; }
		
	$sql_liq = "SELECT liquidacion_zona.id
	FROM liquidacion_zona 
	INNER JOIN tipo_trabajo ON liquidacion_zona.id_tipo_trabajo = tipo_trabajo.id
	WHERE liquidacion_zona.id_zona = '".$_SESSION["zon"]."' AND ( tipo_trabajo.tipo = '".$_SESSION["t_t"]."' ".$tem_tipo."  )"; ; 
	$res_liq = mysql_query($sql_liq);
	while($row_liq = mysql_fetch_array($res_liq))
	{
		$tem_item = "item_".$row_liq["id"]; 
		$tem_serv = "serv_".$row_liq["id"]; 
		$tem_valo = "valo_".$row_liq["id"]; 
		
		$sql_act = "UPDATE `liquidacion_zona` SET 
			`valor` = '".numero_decimal($_POST[$tem_valo])."', 
			`item` = '".$_POST[$tem_item]."', 
			`servicio` = '".$_POST[$tem_serv]."' 
			WHERE `id` = '".$row_liq["id"]."' LIMIT 1;";
		mysql_query($sql_act);
	}
}


?>

<?php if(!$_SESSION["t_t"]){ ?>

		<h2>TIPOS DE TRAMITES</h2>
		<?php 
		/*
		$sql = "SELECT COUNT(*) AS cantidad, tipo  FROM tipo_trabajo 
			INNER JOIN liquidacion_zona ON tipo_trabajo.id = liquidacion_zona.id_tipo_trabajo
			GROUP BY tipo_trabajo.tipo";
		*/
		
		$sql = "SELECT COUNT(*) AS cantidad, tipo  FROM tipo_trabajo 
		GROUP BY tipo ";
		$res = mysql_query($sql);
		while($row = mysql_fetch_array($res))
		{
				$vect[$row["tipo"]]= $row["cantidad"];
		}

		?>
		<br><br><br>

				<table width="90%" align=center>
				<tr>
					<td width="33%" >
						<button class="btn btn-primary" style="width:70%" type="button">
						 <a href="?t_t=1">	<font color=#FFFFFF size=5> Instalaci&oacute;n  <!-- <span class="badge"><?php echo $vect[1]; ?></span> --></font> </a>
						</button>
					</td>
					<td width="33%"  >
						<button class="btn btn-primary" style="width:70%" type="button">
						   <a href="?t_t=2">	<font color=#FFFFFF size=5> Reconexi&oacute;n  <!-- <span class="badge"><?php echo $vect[2]; ?></span> --> </font></a>
						</button>
					</td>
					<td width="33%"  >
						<button class="btn btn-primary" style="width:70%" type="button">
						   <a href="?t_t=3">	<font color=#FFFFFF size=5> Reparaci&oacute;n  <!-- <span class="badge"><?php echo $vect[3]; ?></span> --> </font></a>
						</button>
					</td>
				</tr>
				<tr>
					<td colspan=3><br><br><br></td>
				</tr>
				<tr>
					<td width="33%">
						<button class="btn btn-primary" style="width:70%" type="button">
							 <a href="?t_t=4">	<font color=#FFFFFF size=5> Suspensi&oacute;n  <!-- <span class="badge"><?php echo $vect[4]; ?></span> --> </font></a>
						</button>
					</td>
					<td width="33%" >
						<button class="btn btn-primary" style="width:70%" type="button">
						  <a href="?t_t=5">	<font color=#FFFFFF size=5> Retiro  <!-- <span class="badge"><?php echo $vect[5]; ?></span> --> </font></a>
						</button>
					</td>
					<td width="33%" >
						<button class="btn btn-primary" style="width:70%" type="button">
						   <a href="?t_t=6">	<font color=#FFFFFF size=5> Prematricula  <!-- <span class="badge"><?php echo $vect[6]; ?></span> --> </font></a>
						</button>
					</td>
				</tr>
				</table>
				
<?php } ?>


<?php if($_SESSION["t_t"]){ ?>

	<?php
	if($_GET["zon"])
	{
		$_SESSION["zon"] = $_GET["zon"];
	}
	
	if(!$_SESSION["zon"])
	{
		$_SESSION["zon"] = 1;
	}
	
	$tem_tipo = "";
	if($_SESSION["t_t"]==1){$tramite = "INSTALACION"; $tem_tipo = " OR  tipo_trabajo.tipo = '7' "; }
	if($_SESSION["t_t"]==2){$tramite = "RECONEXION";}
	if($_SESSION["t_t"]==3){$tramite = "REPARACION";}
	if($_SESSION["t_t"]==4){$tramite = "SUSPENCION";}
	if($_SESSION["t_t"]==5){$tramite = "RETIRO";}
	if($_SESSION["t_t"]==6){$tramite = "PREMATRICULA";}
	
	
	?>

	<h2>LISTADO DE TIPO DE TRABAJOS PARA <b><?php echo $tramite ?></b></h2>
	<div align=right>
		<a href="?s_t_t=1"> <i class="fa fa-reply fa-2x"></i> Volver a la lista de tipos de tramites </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
	
	<?php
	$sql_zon = "SELECT id, nombre FROM zona ";
	$res_zon = mysql_query($sql_zon);
	while($row_zon = mysql_fetch_array($res_zon)){

			if($_SESSION["zon"]==$row_zon["id"]){$dep = $row_zon["nombre"];$boton = 'primary';}else{$boton = 'info';}
	?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button class="btn btn-<?php echo $boton; ?>"  type="button">
			 <a href="?zon=<?php echo $row_zon["id"]; ?>">	<font color=#FFFFFF> <?php echo $row_zon["nombre"]; ?> </font> </a>
			</button>
	
	<?php
	}
	
	?>
	
	
	<center>
	<br><br>
<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
<table width=100%>
	<tr>
		<td>
		<div class=" form-group input-group input-group-lg" style="width:100%">
		 <span class="input-group-addon"> <div align=left>Tecnologia</div></span>
			 <select tabindex="9"  class="form-control" name="tecn_ing" id="tecn_ing" required>
				<option value="">Seleccione tecnologia</option>
				<?php
				$sql_cont = "SELECT * FROM tecnologia ORDER BY nombre ";
				$res_cont = mysql_query($sql_cont);
				while($row_cont = mysql_fetch_array($res_cont))
				{
				?>
					<option value="<?php echo $row_cont["id"] ?>" ><?php echo $row_cont["nombre"]; ?></option>
				<?php
				}
				?>										
			 </select>
			</div>
		</td>
		
		<td>
		<div class=" form-group input-group input-group-lg" style="width:100%">
		 <span class="input-group-addon"> <div align=left>Tipo Trabajo</div></span>
			 <select tabindex="9"  class="form-control" name="tt_ing" id="tt_ing" required>
				<option value="">Seleccione tipo trabajo</option>
				<?php
				$sql_cont = "SELECT * FROM tipo_trabajo WHERE  tipo_trabajo.tipo = '".$_SESSION["t_t"]."' ".$tem_tipo." ORDER BY nombre ";
				$res_cont = mysql_query($sql_cont);
				while($row_cont = mysql_fetch_array($res_cont))
				{
				?>
					<option value="<?php echo $row_cont["id"] ?>" ><?php echo $row_cont["codigo"]; ?> - <?php echo $row_cont["nombre"]; ?></option>
				<?php
				}
				?>										
			 </select>
			 </div>
		</td>
		
		<td>
			<center><input type="submit" class="btn btn-primary" name="agregar" value="Guardar datos"></center>
		</td>

	</tr>
</table>
</form>		
		
	
	
	<br><br>
	<div class="panel panel-default" style="width:90%;">
		<div align=left class="panel-heading">
			Trabajos de <b><font color=red><?php echo $tramite ?></font></b> para el departamento de <b><font color=red><?php echo $dep ?></font></b> 
			<div align=right><b><font color=red>NOTA:</font></b> Utilice solo la <b>coma (,)</b> para  definir los decimales en el campo valor</div>
		</div>

		
		
<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<table class="table" align=center>
	   <tr>
			<th>Tecnologia</th>
			<th>Codigo</th>
			<th>Tipo de trabajo</th>
			<th>Tipo de tramite</th>
			<th>Item</th>	
			<th>Servicio</th>
			<th>Valor</th>			
			
	   </tr>
	   <?php
	   
	   
	   
			$sql_liq = "SELECT liquidacion_zona.valor, liquidacion_zona.item, liquidacion_zona.servicio , liquidacion_zona.id, tipo_trabajo.codigo,
			tipo_trabajo.nombre AS nom_trabajo, tecnologia.nombre AS nom_tecnologia, tipo_trabajo.tipo, liquidacion_zona.valor
			FROM liquidacion_zona 
			INNER JOIN tipo_trabajo ON liquidacion_zona.id_tipo_trabajo = tipo_trabajo.id
			INNER JOIN tecnologia ON liquidacion_zona.id_tecnologia = tecnologia.id
			INNER JOIN zona ON liquidacion_zona.id_zona = zona.id
			WHERE liquidacion_zona.id_zona = '".$_SESSION["zon"]."' AND  ( tipo_trabajo.tipo = '".$_SESSION["t_t"]."' ".$tem_tipo."  )
			ORDER BY nom_tecnologia, nom_trabajo"; 
			$res_liq = mysql_query($sql_liq);
			while($row_liq = mysql_fetch_array($res_liq))
			{
				if($row_liq["tipo"]==1){$tipo = 'Instalacion';}
				if($row_liq["tipo"]==2){$tipo = 'Reconexion';}
				if($row_liq["tipo"]==3){$tipo = 'reparacion';}
				if($row_liq["tipo"]==4){$tipo = 'Suspencion';}
				if($row_liq["tipo"]==5){$tipo = 'Retiro';}
				if($row_liq["tipo"]==6){$tipo = 'Prematricula';}
		?>
				<tr>
					<td><?php echo $row_liq["nom_tecnologia"] ?></td>
					<td><?php echo $row_liq["codigo"] ?></td>
					<td><?php echo $row_liq["nom_trabajo"] ?></td>
					<td><?php echo $tipo ?></td>
					<td><input type="text" name="item_<?php echo $row_liq["id"]; ?>" value="<?php echo $row_liq["item"] ?>"></td>
					<td><input type="text" name="serv_<?php echo $row_liq["id"]; ?>" value="<?php echo $row_liq["servicio"] ?>"> </td>
					<td><b>$</b><input size="10" style="color:red;font-weight:bold;text-align:right" type="float" name="valo_<?php echo $row_liq["id"]; ?>" value="<?php echo $row_liq["valor"] ?>" ></td>
				</tr>
		<?php 
			}
		?>
	</table>
</div>

	
	<center><input type="submit" class="btn btn-primary" name="guardar" value="Guardar datos"></center>
</form>
<?php } ?>