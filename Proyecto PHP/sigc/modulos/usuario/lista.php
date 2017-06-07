<?php
if($PERMISOS_GC["usu_lis"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_GET["eliminar"])
{
	$sql = "UPDATE usuario SET estado = '2' WHERE id ='".$_GET["eliminar"]."' LIMIT 1;";
	mysql_query($sql);	
}

?> 


 <h2>GESTION DE USUARIO </h2>
					<?php if($PERMISOS_GC["usu_cre"]=='Si'){ ?>
						<div align=right> 						
							<a href='?cmp=ingresar'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Crear nuevo usuario</a>					
						</div>
					<?php } ?>
					
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Listado de usuarios activos
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
							
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Apellidos</th>
                                            <th>Nombre</th>
                                            <th>Cedula</th>
                                            <th>Direccion</th>
                                            <th>Correo</th>
											<?php if($PERMISOS_GC["usu_edi"]=='Si'){ ?> <th>Editar</th> <?php } ?>
											<?php if($PERMISOS_GC["usu_eli"]=='Si'){ ?> <th>Quitar</th> <?php } ?>
											<?php if($PERMISOS_GC["usu_per"]=='Si'){ ?> <th>Permisos</th> <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "SELECT usuario.usuario, usuario.apellido, usuario.nombre, usuario.correo, usuario.identificacion, usuario.id, usuario.direccion
											FROM usuario 
											WHERE estado=1 AND id_instancia='".$_SESSION["nst"]."' ORDER BY apellido, nombre ;";
									$res = mysql_query($sql);
									while($row=mysql_fetch_array($res))
									{
									?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $row["apellido"] ?></td>
                                            <td><?php echo $row["nombre"] ?></td>
                                            <td><?php echo $row["identificacion"] ?></td>
                                            <td class="center"><?php echo $row["direccion"] ?></td>
                                            <td class="center"><?php echo $row["correo"] ?></td>
											<?php if($PERMISOS_GC["usu_edi"]=='Si'){ ?> <td align=center> <a href="?cmp=editar&id=<?php echo $row["id"]; ?>"> <i class="fa fa-pencil-square-o fa-2x"></i></td> <?php } ?>
											<?php if($PERMISOS_GC["usu_eli"]=='Si'){ ?> <td align=center><a href="?eliminar=<?php echo $row["id"]; ?>" onclick="if(confirm('¿ Realmente desea ELIMINAR el usuario <?php echo $row["apellido"]." ".$row["nombre"]; ?> ?') == false){return false;}"><font color=red><i class="fa fa-eraser fa-2x"></i></font></a></td> <?php } ?>
											<?php if($PERMISOS_GC["usu_per"]=='Si'){ ?><td align=center> <a href="?cmp=permiso&id=<?php echo $row["id"]; ?>"> <font color=#FE9A2E> <i class="fa fa-cogs fa-2x"></i></font></td> <?php } ?>
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
              
         
               

     
   