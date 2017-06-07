<?php
if($PERMISOS_GC["hv_aud"]!='Si')
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
	
 <h2>DATOS BASICOS HOJAS DE VIDAS</h2>
 
 <div align=right>							
		<a href='?cmp=ingresar_excel_auditor'><font color=green><i class="fa fa-paste fa-2x"></i></font> Subir hojas de vida por CSV</a>	
		&nbsp; &nbsp; &nbsp; 
	</div>
										
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Listado de hojas de vida para auditar datos basicos
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
							
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Apellidos</th>
                                            <th>Nombre</th>
                                            <th>Cedula</th>
                                            <th>Cargo a postular</th>
                                            <th>Municipio</th>
											<th>Estado</th>
											<th>----</th>											
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									 $sql = "SELECT hv_persona.apellido, hv_persona.nombre, hv_persona.identificacion, cargo.nombre AS nom_cargo, hv_persona.estado,
													hv_persona.id, municipio.nombre AS nom_municipio
											FROM hv_persona 
											INNER JOIN municipio ON hv_persona.id_municipio = municipio.id
											INNER JOIN cargo ON hv_persona.id_cargo = cargo.id
											WHERE 	hv_persona.id_instancia='".$_SESSION["nst"]."' AND hv_persona.estado IN ('2','6')
											ORDER BY apellido, nombre ;";
									$res = mysql_query($sql);
									while($row=mysql_fetch_array($res))
									{
										$estado = '';
										if($row["estado"]=='1'){$estado="<font color=#FA5858><b>Inicializada</b></font>";}
										if($row["estado"]=='2'){$estado="<font color=#FF8000><b>Pte. confirmacion datos basicos</b></font>";}
										if($row["estado"]=='3'){$estado="<font color=#FA5858><b>Pte. ingresar documentacion legal</b></font>";}
										if($row["estado"]=='4'){$estado="<font color=#FF8000><b>Pte confirmacion documentacion legal</b></font>";}
										if($row["estado"]=='5'){$estado="<font color=green><b>Confirmada completamente</b></font>";}
										if($row["estado"]=='6'){$estado="<font color=red><b>Datos basicos RECHAZADOS</b></font>";}
										if($row["estado"]=='7'){$estado="<font color=red><b>documentacion legal RECHAZADAS</b></font>";}
										if($row["estado"]=='8'){$estado="<b>NO ACEPTADA</b>";}
										
									?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $row["apellido"] ?></td>
                                            <td><?php echo $row["nombre"] ?></td>
                                            <td><?php echo $row["identificacion"] ?></td>
											<td><?php echo $row["nom_cargo"] ?></td>
                                            <td><?php echo $row["nom_municipio"] ?></td>
                                            <td><?php echo $estado ?></td>
											<td align=center>
											<?php if($row["estado"]=='2'){ ?>
												<a href="?cmp=fase2&id=<?php echo $row["id"]; ?>"> <i class="fa fa-pencil-square-o fa-2x"></i></a>
											<?php } ?>
											<?php if($row["estado"]=='6'){ ?>
												<a href="?cmp=auditar_detalle&id=<?php echo $row["id"]; ?>"> <i class="fa fa-eye fa-2x"></i></a>
											<?php } ?>
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
             