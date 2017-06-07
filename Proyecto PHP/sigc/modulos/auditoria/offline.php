<?php  
if($PERMISOS_GC["aud_gest"]!='Si'){   
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}  

if($_POST["fecha_oc"])
{
	$_SESSION["fecha_oc"] = $_POST["fecha_oc"];
}


if(!$_SESSION["fecha_oc"])
{
	$_SESSION["fecha_oc"] = date("Y-m-d");
}

$where = " tramite.fecha_atencion_orden >= '".$_SESSION["fecha_oc"]." 00:00:00' AND  tramite.fecha_atencion_orden  <= '".$_SESSION["fecha_oc"]." 23:59:59' ";


?>

<h2>TRAMITES REALIZADOS EL DIA <b><?php echo $_SESSION["fecha_oc"]; ?></b></h2>

<table width=100%>
	<tr>
		<td>
			<form   method="post" name="form" id="form"  enctype="multipart/form-data">
				<div class="row">
				   <div class="col-lg-6">
					<div class="input-group">
					  <input type="date" class="form-control" name="fecha_oc" placeholder="Ingresar fecha de cierre" required>
					  <span class="input-group-btn">
						<button class="btn btn-primary"  type="submit">Buscar fecha</button>
					  </span>
					</div>
				  </div>
				</div>
			</form>
		</td>
		<td align=right>
			<a href="?cmp=lista_gestion"> <i class="fa fa-reply fa-2x"></i> Volver al listado de auditorias </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
		</td>
	</tr>
</table>
<br>
<?php if(!$_GET["criterio"]){ ?>	


<?php
$select = ", tramite.valor_liquidado AS vl"; 
$select_sup = ", SUM(vl) AS total_liquidado "; 
$res = tramite_liquidacion($select, $where, $select_sup, "", '1');
$row = mysql_fetch_array($res);
?>


	
		<center>					
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
								<th><center>Ver</center></th>
							</tr>
							<?php
							
								
										$select_sup = ", idtt, nomtt, COUNT(*) AS cantidad,  SUM(vl) AS total_liquidado, codtt,
													SUM(tem_adicional) AS ta, SUM(tem_extension) AS te, SUM( IF(contratista_valor=2,1,0)) AS aceptados,  
													SUM( IF(contratista_valor=3,1,0)) AS rechazados	"; 
									
									$select = $select_tr.", contratista_valor, tramite.valor_liquidado AS vl, tramite.id_tipo_trabajo AS idtt, tipo_trabajo.nombre AS nomtt,  tipo_trabajo.codigo AS codtt"; 
									$where_sup = " GROUP BY idtt";
									$res = tramite_liquidacion($select, $where, $select_sup,  $where_sup, '1');
								while($row = mysql_fetch_array($res))
								{
									$promedio_valor = $row["valor_total"]/$row["cantidad"];
										$promedio_caja = $row["ta"]/$row["cantidad"];
										$promedio_ext = $row["te"]/$row["cantidad"];
									?>
									<tr>
										<td><?php echo $row["nomtt"] ?></td>
										<td><?php echo $row["codtt"] ?></td>
										<td><b><?php echo $row["cantidad"] ?></b></td>											
										<td align=center><a href="?criterio=<?php echo $row["idtt"]; ?>"><b><i class="fa fa-eye fa-2x"></i></b></a></td>
									</tr>
									<?php
								}							
								?>		
						</table>
					</div>
				</div>
			</div>						
		</center>
<?php }else{ ?>


<?php
$sql_crit = " SELECT nombre FROM tipo_trabajo WHERE id = '".$_GET["criterio"]."' LIMIT 1";
	$res_crit = mysql_query($sql_crit);
	$row_crit = mysql_fetch_array($res_crit);
	
	$titulo = " EL TIPO DE TRABAJO <b>".$row_crit["nombre"]."</b>";	
	$where_tem = $where." AND tramite.id_tipo_trabajo =  '".$_GET["criterio"]."' ";
?>



	<table align=center width=100%  class="table table-striped table-bordered table-hover" >
		<tr>
			<th>OT</th>
			<th>Tipo de Trabajo</th>
			<th>Tecnologia</th>
			<th>Tipo tramite</th>
				<th>Localidad</th>
				<th>Direccion</th>
				<th>Tecnico</th>			
			<th>Det.</th>			
		</tr>
		<?php
			$select = ", tramite.ot, tecnologia.nombre AS nom_tecnologia, tramite.fecha_atencion_orden, tipo_trabajo.nombre , tipo_trabajo.tipo, 
			liquidacion_zona.valor, tramite.id, tramite.estado_liquidacion,  tramite.tipo_paquete,  localidad.nombre AS nom_localidad, tramite.direccion, 
			tecnico.nombre AS nom_tecnico ";
			$res = tramite_liquidacion($select, $where_tem,  "",  "", '2');
			while($row = mysql_fetch_array($res))
			{
					$tramite = "";
					
					
					
			$row["valor"] = $row["valor"] + 0;
				?>
				<tr>
					<td><?php echo $row["ot"] ?></td>
					<td><?php echo $row["nombre"] ?></td>
					<td><?php echo $row["nom_tecnologia"] ?></td>
					<td><?php echo $row["tipo_paquete"]; ?></td>
							<td><?php echo $row["nom_localidad"]; ?></td>
							<td><?php echo $row["direccion"]; ?></td>
							<td><?php echo $row["nom_tecnico"]; ?></td>
					<td align=center> <a href="?cmp=offline_asignar&id=<?php echo $row["id"]; ?>&criterio=<?php echo $_GET["criterio"]; ?>"> <i class="fa fa-eye fa-2x"> </i> </a></td>				
				
				</tr>
				<?php
			}							
			?>		
	</table>

<?php } ?>


