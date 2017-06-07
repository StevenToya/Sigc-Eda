<?php
if($PERMISOS_GC["cap_st"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

?> 
 <h2>STATUS DE LOS CAPACITACIONES </h2>
	<?php /* if($PERMISOS_GC["usu_cre"]=='Si'){  */ ?>
		<div align=right> 						
			<!-- <a href='?cmp=ingresar_programa'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Crear nuevo programa</a>	 -->				
		</div>
	<?php /* } */ ?>
	
	<div class="panel panel-default">
		<div class="panel-heading">
			 Listado de programas 
		</div>
		<div class="panel-body">
			<div class="table-responsive">
			
				<table class="table table-striped table-bordered table-hover" id="dataTables-example">
					<thead>
						<tr>
							<th width=30%>Programa</th>
							<th width=70%>Estado</th>                                                                                      										
						</tr>
					</thead>
					<tbody>
					<?php
					$sql = "SELECT programa.nombre, programa.descripcion, programa.hora, programa.id
							FROM programa 
							WHERE id_instancia='".$_SESSION["nst"]."' ORDER BY programa.nombre ;";
					$res = mysql_query($sql);
					while($row=mysql_fetch_array($res))
					{									
					?>
						<tr class="odd gradeX">
							<td valign=middle><?php echo $row["nombre"] ?></td>
							<td valign=middle>
								<div class="progress">
								  <div class="progress-bar progress-bar-success progress-bar-striped" style="width: 75%">
									<span class="sr-only">75% Complete (success)</span>
								  </div>
																				 
								  <div class="progress-bar progress-bar-danger progress-bar-striped" style="width: 25%">
									<span class="sr-only">25% Complete (danger)</span>
								  </div>
								</div>								
							</td>	
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
              
         
               

     
   