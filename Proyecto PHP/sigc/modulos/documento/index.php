<?php

if($PERMISOS_GC["doc_veh"]!='Si' && $PERMISOS_GC["doc_hv"]!='Si' )
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

?>


<h2>DOCUMENTACION OBLIGATORIA</h2>
<?php
if($PERMISOS_GC["doc_hv"]=='Si' ){
?>
		<div align=right> 						
			<a href='?cmp=crear&tip=3'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Ingresar documento para personas</a>					
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				 <b>Personas</b>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example2">				
						<thead>
							<tr bgcolor="#210B61">
									<th><font color="#FFFFFF">Nombre</font></th>
									<th><font color="#FFFFFF">Fec. Vencimiento</font></th>
									<th><font color="#FFFFFF">Fec. Expedicion</font></th>
									<th><font color="#FFFFFF">Meses para revisar</font></th>
									<th><font color="#FFFFFF">Edit</font></th>		
							</tr>
						</thead>
						<tbody>
						<?php
						$sql = "SELECT * FROM documento WHERE tipo = '3' AND id_instancia = '".$_SESSION["nst"]."' ORDER BY descripcion ";
						$res = mysql_query($sql);
						while($row = mysql_fetch_array($res))
						{
							if(($cont % 2)==0){$color = "bgcolor = '".$color2."'";}else{$color = "bgcolor = '".$color3."'";}$cont ++;
						?>	
								<tr <?php echo $color; ?>>
										<td><?php echo $row["descripcion"] ?></td>
										<td align='center'>
											<?php if($row["fecha_vencimiento"]=='s'){ ?>
												<font color=green><i class="fa fa-check-circle fa-2x"></i></font>
											<?php }else{ ?>
												<font color=red><i class="fa fa-ban fa-2x"></i></font>
											<?php }?>
										</td>
										<td align='center'>
											<?php if($row["fecha_revision"]=='s'){ ?>
												<font color=green><i class="fa fa-check-circle fa-2x"></i></font>
											<?php }else{ ?>
												<font color=red><i class="fa fa-ban fa-2x"></i></font>
											<?php }?>
										</td>
										<td  align='center'><?php echo $row["revision_mes"] ?> meses</td>
										<td  align='center'><a href="?cmp=editar&id=<?php echo $row["id"] ?>"> <i class="fa fa-pencil-square-o fa-2x"></i></a> </td>
								</tr>					
						<?php
						}
						?>
						</tbody>
					</table>
				</div>
				
			</div>
		</div>
<br><br>
<?php
}
?>


<?php
if($PERMISOS_GC["doc_veh"]=='Si' ){
?>

<div align=right> 						
	<a href='?cmp=crear&tip=2'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Ingresar documento para vehiculos</a>					
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		 <b>Vehiculos</b>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="dataTables-example3">				
				<thead>
					<tr bgcolor="#210B61">
							<th><font color="#FFFFFF">Nombre</font></th>
							<th><font color="#FFFFFF">Fec. Vencimiento</font></th>
							<th><font color="#FFFFFF">Fec. Expedicion</font></th>
							<th><font color="#FFFFFF">Meses para revisar</font></th>
							<th><font color="#FFFFFF">Edit</font></th>		
					</tr>
				</thead>
                <tbody>
				<?php
				$sql = "SELECT * FROM documento WHERE tipo = '2' AND id_instancia = '".$_SESSION["nst"]."' ORDER BY descripcion ";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
					if(($cont % 2)==0){$color = "bgcolor = '".$color2."'";}else{$color = "bgcolor = '".$color3."'";}$cont ++;
				?>	
						<tr <?php echo $color; ?>>
								<td><?php echo $row["descripcion"] ?></td>
								<td align='center'>
									<?php if($row["fecha_vencimiento"]=='s'){ ?>
										<font color=green><i class="fa fa-check-circle fa-2x"></i></font>
									<?php }else{ ?>
										<font color=red><i class="fa fa-ban fa-2x"></i></font>
									<?php }?>
								</td>
								<td align='center'>
									<?php if($row["fecha_revision"]=='s'){ ?>
										<font color=green><i class="fa fa-check-circle fa-2x"></i></font>
									<?php }else{ ?>
										<font color=red><i class="fa fa-ban fa-2x"></i></font>
									<?php }?>
								</td>
								<td  align='center'><?php echo $row["revision_mes"] ?> meses</td>
								<td  align='center'><a href="?cmp=editar&id=<?php echo $row["id"] ?>"> <i class="fa fa-pencil-square-o fa-2x"></i></a> </td>
						</tr>					
				<?php
				}
				?>
				</tbody>
			</table>
		</div>
		
	</div>
</div>
<?php
}
?>