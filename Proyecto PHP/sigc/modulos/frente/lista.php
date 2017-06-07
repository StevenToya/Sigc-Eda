<?php
/*
if($PERMISOS_GC["hv_cre"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}
*/


?>
<h2>FRENTES CREADOS</h2>
<div align=right> 						
	<a href='?cmp=crear'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Crear frente</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<center>
<div class="panel panel-default" style="width:80%">
	<div align=left class="panel-heading">
		 listado de frentes activos
	</div>
	<div class="panel-body">
		<div class="table-responsive">
		
			<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>Oficial</th>	
						<th>Auxiliar</th>	
						<th>Tecnologia</th>	
						<th>Localidad</th>	
						<th>Ver</th>
						<th>Editar</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$sql = "SELECT frente_trabajo.nombre_1, frente_trabajo.nombre_2, municipio.nombre, frente_trabajo.id, tecnologia
				FROM frente_trabajo 	
				INNER JOIN municipio ON frente_trabajo.id_municipio = municipio.id
				WHERE frente_trabajo.id_instancia='".$_SESSION["nst"]."' ";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{						
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["nombre_1"] ?> </b></td>
						<td><b> <?php echo $row["nombre_2"] ?> </b></td>
						<td><?php echo utf8_encode($row["tecnologia"]); ?> </td>	
						<td><?php echo utf8_encode($row["nombre"]); ?> </td>	
						<td align=center><a href="?cmp=detalle&id=<?php echo $row["id"] ?>"><i class="fa fa-eye fa-2x"></i> </a></td></td>	
						<td align=center><a href="?cmp=editar&id=<?php echo $row["id"] ?>"><i class="fa fa-pencil-square-o fa-2x"></i> </a></td></td>	
				   </tr>
				<?php
				}
				?>
				   
				</tbody>
			</table>
		</div>		
	</div>
</div>



