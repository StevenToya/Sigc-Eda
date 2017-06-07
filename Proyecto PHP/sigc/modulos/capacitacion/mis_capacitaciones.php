<?php
if($PERMISOS_GC["cap_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_GET["eliminar"])
{	
	 $sql_eli = "DELETE FROM `capacitacion_persona` WHERE `id_capacitacion` = '".$_GET["eliminar"]."' ";
	mysql_query($sql_eli);	
	
	$sql_eli = "DELETE FROM `capacitacion` WHERE id = '".$_GET["eliminar"]."' ";
	mysql_query($sql_eli);
}

?> 


 <h2>TUS CAPACITACIONES</h2>
	<?php /* if($PERMISOS_GC["usu_cre"]=='Si'){  */ ?>
		<div align=right> 						
			<a href='?cmp=ingresar_capacitacion'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Ingresar capacitacion</a>					
		</div>
	<?php /* } */ ?>
	
	<div class="panel panel-default">
		<div class="panel-heading">
			 Listado de las capacitaciones realizadas
		</div>
		<div class="panel-body">
			<div class="table-responsive">
			
				<table class="table table-striped table-bordered table-hover" id="dataTables-example">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Programa</th>
							<th>Lugar</th>
							<th>Horas</th>
							<th>Aistieron</th>
							<th>Observacion del evento</th>
							<th>Documento</th>
							<th>Detalles</th>
							<?php /* if($PERMISOS_GC["usu_eli"]=='Si'){ */ ?> <th>Editar</th> <?php /* } */ ?>	
							<?php /* if($PERMISOS_GC["usu_eli"]=='Si'){ */ ?> <th>Quitar</th> <?php /* } */ ?>											
						</tr>
					</thead>
					<tbody>
					<?php
					$sql = "SELECT programa.nombre, capacitacion.fecha, capacitacion.hora_inicial, capacitacion.lugar, capacitacion.id, capacitacion.documento, capacitacion.total_hora,
							capacitacion.observacion
							FROM capacitacion
							INNER JOIN programa ON capacitacion.id_programa = programa.id							
							WHERE id_usuario ='".$_SESSION["user_id"]."' ORDER BY capacitacion.fecha DESC ;";
					$res = mysql_query($sql);
					while($row=mysql_fetch_array($res))
					{
							$sql_asis = "SELECT COUNT(*) AS cantidad FROM capacitacion_persona WHERE id_capacitacion = '".$row["id"]."' ";
							$res_asis = mysql_query($sql_asis);
							$row_asis = mysql_fetch_array($res_asis);
				
					?>
						<tr class="odd gradeX">
							<td><?php echo $row["fecha"] ?> <?php echo $row["hora_inicial"] ?></td>
							<td><?php echo $row["nombre"] ?></td>
							<td><?php echo $row["lugar"] ?></td>
							<td><?php echo $row["total_hora"] ?></td>
							<td><?php echo $row_asis["cantidad"] ?></td>
							<td><?php echo $row["observacion"] ?></td>
							<td align=center><a href="<?php echo $row["documento"] ?>" target="blank">Descargar</a></td>
							<td align=center><a href="?cmp=detalle_micapacitacion&id=<?php echo $row["id"]; ?>" ><i class="fa fa-eye fa-2x"></i></a></td> 
							<?php /* if($PERMISOS_GC["usu_eli"]=='Si'){ */ ?> 
								<td align=center>							
										<a href="?cmp=editar_capacitacion&id=<?php echo $row["id"]; ?>" ><i class="fa fa-pencil-square fa-2x"></i></a>																						
								</td> 
							<?php /* } */ ?>
							<?php /* if($PERMISOS_GC["usu_eli"]=='Si'){ */ ?> 
								<td align=center>							
										<a href="?eliminar=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR la capacitacion <?php echo $row["nombre"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a>																						
								</td> 
							<?php /* } */ ?>							
					   </tr>
					<?php
					}
					?>
					   
					</tbody>
				</table>
			</div>
			
		</div>
	</div>
	<!--End Advanced Tables -->
              
         
               

     
   