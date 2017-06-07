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



?>

<h2>TRAMITES REALIZADOS EL DIA <b><?php echo $_SESSION["fecha_oc"]; ?></b> PARA  HFC</h2>

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



	<table align=center width=100%  class="table table-striped table-bordered table-hover" >
		<tr>
			<th>Numero</th>
			<th>Tipo de Trabajo</th>
			<th>Tecnico</th>
			<th>Producto</th>
			<th>Localidad</th>
			<th>Direccion</th>
			<th>Cliente</th>			
			<th>Det.</th>			
		</tr>
		<?php
			$sql = "SELECT * FROM  `une_pedido` 
			WHERE tipo=1 
			AND fecha = '".$_SESSION["fecha_oc"]."' 
			AND une_pedido.ciudad IN ('Monteria','Sincelejo','Valledupar')
			GROUP BY numero ";
			$res = mysql_query($sql);
			while($row = mysql_fetch_array($res))
			{					
				?>
				<tr>
					<td><?php echo $row["numero"] ?></td>
					<td><?php echo $row["tipo_trabajo"] ?></td>
					<td><?php echo $row["nombre_funcionario"] ?></td>
					<td><?php echo $row["producto"]; ?></td>
					<td><?php echo $row["ciudad"]; ?></td>
					<td><?php echo $row["cliente_direccion"]; ?></td>
					<td><?php echo $row["cliente_nombre"]; ?></td>
					<td align=center> <a href="?cmp=offlinehfc_asignar&id=<?php echo $row["id"]; ?>"> <i class="fa fa-eye fa-2x"> </i> </a></td>				
				
				</tr>
				<?php
			}							
			?>		
	</table>




