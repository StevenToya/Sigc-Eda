<?php
if($PERMISOS_GC["pro_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


if($_GET["eliminar"])
{	
	$sql = "DELETE FROM `programa_cargo` WHERE `id_programa` = '".$_GET["eliminar"]."' ; ";
	mysql_query($sql);
	
	$sql = "DELETE FROM  programa  WHERE id ='".$_GET["eliminar"]."' LIMIT 1;";
	mysql_query($sql);	
}

?> 


 <h2>PROGRAMAS ACTIVOS </h2>
					<?php /* if($PERMISOS_GC["usu_cre"]=='Si'){  */ ?>
						<div align=right> 						
							<a href='?cmp=ingresar_programa'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Crear nuevo programa</a>					
						</div>
					<?php /* } */ ?>
					
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Listado de programas activos
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
							
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Programa</th>
                                            <th>Descripcion</th>
                                            <th>Horas</th>
                                           	<?php /* if($PERMISOS_GC["usu_edi"]=='Si'){ */ ?> <th>Editar</th> <?php /* } */ ?>
											<?php /* if($PERMISOS_GC["usu_eli"]=='Si'){ */ ?> <th>Quitar</th> <?php /* } */ ?>											
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
                                            <td><?php echo $row["nombre"] ?></td>
                                            <td><?php echo $row["descripcion"] ?></td>
                                            <td><?php echo $row["hora"] ?></td>
                                          	<?php /* if($PERMISOS_GC["usu_edi"]=='Si'){ */ ?> 
												<td align=center> <a href="?cmp=editar_programa&id=<?php echo $row["id"]; ?>"> <i class="fa fa-pencil-square-o fa-2x"></i></td> 
											<?php /* } */ ?>
											<?php /* if($PERMISOS_GC["usu_eli"]=='Si'){ */ ?> 
												<?php
												$sql_bb = "SELECT id FROM capacitacion WHERE id_programa = '".$row["id"]."' LIMIT 1 ";
												$res_bb = mysql_query($sql_bb);
												$row_bb = mysql_fetch_array($res_bb);
												?>
												<td align=center>
													<?php if(!$row_bb["id"]){ ?>
														<a href="?eliminar=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR el programa <?php echo $row["nombre"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a>
													<?php }else{ ?>
														---
													<?php }?>													
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
              
         
               

     
   