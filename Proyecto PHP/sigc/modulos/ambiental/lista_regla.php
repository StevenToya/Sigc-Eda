<?php
if($PERMISOS_GC["amb_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}
?>

<h2>DOCUMENTACION OBLIGATORIA</h2>
<div align=right> 						
	<a href='?cmp=crear_regla&tip=3'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Ingresar documento para ambiental</a> &nbsp;&nbsp;&nbsp;&nbsp;
	<a href='?cmp=lista_amb_gestion&tip=3'><i class="fa fa-reply fa-2x"></i> Volver lista de documentos entregados</a> &nbsp;&nbsp;&nbsp;&nbsp;
	
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		 <b>Lista de documentacion ambiental</b>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="dataTables-example2">				
				<thead>
					<tr bgcolor="#210B61">
							<th><font color="#FFFFFF">Nombre</font></th>
							<th><font color="#FFFFFF">Meses para revisar</font></th>
							<th><font color="#FFFFFF">Documento</font></th>							
							<th><font color="#FFFFFF">Edit</font></th>		
					</tr>
				</thead>
                <tbody>
				<?php
				$sql = "SELECT * FROM documento WHERE tipo = '5' AND id_instancia = '".$_SESSION["nst"]."' ORDER BY descripcion ";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{
					if(($cont % 2)==0){$color = "bgcolor = '".$color2."'";}else{$color = "bgcolor = '".$color3."'";}$cont ++;
				?>	
						<tr <?php echo $color; ?>>
								<td><?php echo $row["descripcion"] ?></td>
								<td  align='center'><?php echo $row["revision_mes"] ?> meses</td>
								<td  align='center'>
									<?php if($row["archivo"]){ ?>
										<a href="<?php echo $row["archivo"] ?>" target="blank"> Descargar </a>
									<?php }else{ ?>
										---
									<?php } ?>
								</td>	
								<td  align='center'><a href="?cmp=editar_regla&id=<?php echo $row["id"] ?>"> <i class="fa fa-pencil-square-o fa-2x"></i></a> </td>
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
