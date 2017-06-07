<?php
if($PERMISOS_GC["sst_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


if($_POST["guardar"])
{
	if($_POST["gestion"])
	{			
		$sql_act = "UPDATE `empresa` SET `estado` = '".$_POST["gestion"]."' WHERE  id_instancia = '".$_SESSION["nst"]."' ;";
		mysql_query($sql_act);
	}
	
	echo '<script> alert("Se auditaron los datos ");</script>';
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=lista_sst_gestion'>";die();
}

$sql = "SELECT * FROM empresa WHERE id_instancia = '".$_SESSION["nst"]."' LIMIT 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);

?>

<h2>DATOS BASICOS DE LA EMPRESA</h2>
	<center>
	<form action="?"  method="post" name="form" id="form"  enctype="multipart/form-data">
		<?php
			$estado_doc = '<b><font color=red>Sin Informacion</font></b>';
			if($row["estado"]==1 ){$estado_doc = '<b><font color=#DF7401>Sin revision</font></b>';}
			if($row["estado"]==2 ){$estado_doc = '<b><font color=green>Aceptada</font></b>';}
			if($row["estado"]==3 ){$estado_doc = '<b><font color=red>Rechazada</font></b>';}
			?>
			
			<?php if($row["estado"]<>1){ ?>
				<h3>	Estado: <?php echo $estado_doc; ?> </h3>
			<?php }else{ ?>
			<div class="form-group input-group input-group-lg" style="width:30%">
				 <span class="input-group-addon">Decisi&oacute;n</span>
					<select name="gestion"  class="form-control" required>
							 <option value="">Tomar una opcion</option>
							 <option value="2">Aceptar</option>
							 <option value="3">Rechazar</option>								
					</select>
			</div> 
			
			
				<input type="submit" value="Auditar datos basicos"  name="guardar" class="btn btn-primary" /><br>
		<?php } ?>	
	</form>
	</center>


<div align=right> 						
		<a href='?cmp=lista_sst_gestion&tip=3'><i class="fa fa-reply fa-2x"></i> Volver lista de documentos entregados</a> &nbsp;&nbsp;&nbsp;&nbsp;	
</div>


		<div class="col-md-100 col-sm-100">
		<div class="panel panel-primary">
			<div class="panel-heading"> 
				<center><b>INFORMACI&Oacute;N GENERAL DE LA EMPRESA CONTRATISTA</b> </center>
			</div>
			<div class="panel-body" align=center>
				<table width='100%'>
					<tr>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Actualizada</span>							 
							  <input  name="fecha_registro" readonly  id="fecha_registro" value="<?php echo $row["fecha_registro"] ?>"  type="text"  class="form-control" placeholder="" required />
							</div> 
						</td>
						<td width=4%></td>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Contrato Nº</span>							 
							  <input  name="contrato" readonly  id="contrato" value="<?php echo $row["contrato"] ?>"  type="text"  class="form-control" placeholder="" required />
							</div> 
						</td>
					</tr>
					
					<tr>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Empresa</span>							 
							  <input  name="nombre" readonly  id="nombre" value="<?php echo $row["nombre"] ?>"  type="text"  class="form-control" placeholder="" required />
							</div> 
						</td>
						<td width=4%></td>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Direccion</span>							 
							  <input  name="direccion" readonly  id="direccion" value="<?php echo $row["direccion"] ?>"  type="text"  class="form-control" placeholder="" required />
							</div> 
						</td>
					</tr>
					
					<tr>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Telefono</span>							 
							  <input  name="telefono" readonly  id="telefono" value="<?php echo $row["telefono"] ?>"  type="text"  class="form-control" placeholder="" required />
							</div> 
						</td>
						<td width=4%></td>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">E-mail</span>							 
							  <input  name="email" readonly  id="email" value="<?php echo $row["email"] ?>"  type="text" class="form-control" placeholder="" required />
							</div> 
						</td>
					</tr>
				</table>
			</div>
											
		</div>
	</div>		
 
 
	<div class="col-md-100 col-sm-100">
		<div class="panel panel-primary">
			<div class="panel-heading"> 
				<center><b>REPRESENTANTE LEGAL</b> </center>
			</div>
			<div class="panel-body" align=center>
				<table width='100%'>
					<tr>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Nombre</span>							 
							  <input readonly name="representante_nombre"  id="representante_nombre" value="<?php echo $row["representante_nombre"] ?>"  type="text"  class="form-control" placeholder="" required />
							</div> 
						</td>
						<td width=4%></td>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">Cargo</span>							 
							  <input readonly name="representante_cargo"  id="representante_cargo" value="<?php echo $row["representante_cargo"] ?>"  type="text"  class="form-control" placeholder="" required />
							</div> 
						</td>
					</tr>
				</table>
			</div>
											
		</div>
	</div>	



	<div class="col-md-100 col-sm-100">
		<div class="panel panel-primary">
			<div class="panel-heading"> 
				<center><b>COORDINADOR DE SEGURIDAD Y SALUD EN EL TRABAJO PARA EL OBJETO DEL CONTRATO<br>
				(Adjuntar Hoja de vida y licencia)</b> </center>
			</div>
			<div class="panel-body" align=center>
				<table width='100%'>
					<tr>
						<td colspan=3>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon"><div align=left>1) Nombre y apellido</div></span>							 
							  <input  name="coordinador_1" readonly  id="coordinador_1" value="<?php echo $row["coordinador_1"] ?>"  type="text"  class="form-control" placeholder=""  />
							</div> 
						</td>						
					</tr>
					<tr>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon"><div align=left>Licencia</div></span>							 
							  <input  name="coordinador_1_licencia" readonly  id="coordinador_1_licencia" value="<?php echo $row["coordinador_1_licencia"] ?>"  type="text"  class="form-control" placeholder=""  />
							</div> 
						</td>
						<td width=4%></td>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">	Hoja de vida </span>							 
							  <h5>
								<?php if($row["coordinador_1_archivo"]){ ?> 
									<a href="<?php echo $row["coordinador_1_archivo"] ?>" target="blank">&nbsp;&nbsp; Descargar hoja de vida </a>
								<?php }else{ ?>
									- - - -
								<?php } ?>
								</h5>
							</div> 
						</td>
					</tr>
				</table>
						
				<table width='100%'>
					<tr>
						<td colspan=3>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon"><div align=left>2) Nombre y apellido</div></span>							 
							  <input  name="coordinador_2"  readonly id="coordinador_2" value="<?php echo $row["coordinador_2"] ?>"  type="text"  class="form-control" placeholder=""  />
							</div> 
						</td>						
					</tr>
					<tr>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon"><div align=left>Licencia</div></span>							 
							  <input  name="coordinador_2_licencia" readonly  id="coordinador_2_licencia" value="<?php echo $row["coordinador_2_licencia"] ?>"  type="text"  class="form-control" placeholder=""  />
							</div> 
						</td>
						<td width=4%></td>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">								
									Hoja de vida								
							  </span>							 
							  <h5>
								<?php if($row["coordinador_2_archivo"]){ ?> 
									<a href="<?php echo $row["coordinador_2_archivo"] ?>" target="blank"> &nbsp;&nbsp; Descargar hoja de vida </a>
								<?php }else{ ?>
									- - - -
								<?php } ?>
								</h5>
							</div> 
						</td>
					</tr>
				</table>
					
				<table width='100%'>
					<tr>
						<td colspan=3>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon"><div align=left>3) Nombre y apellido</div></span>							 
							  <input  name="coordinador_3" readonly id="coordinador_3" value="<?php echo $row["coordinador_3"] ?>"  type="text"  class="form-control" placeholder="" />
							</div> 
						</td>						
					</tr>
					<tr>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon"><div align=left>Licencia</div></span>							 
							  <input readonly  name="coordinador_3_licencia"  id="coordinador_3_licencia" value="<?php echo $row["coordinador_3_licencia"] ?>"  type="text"  class="form-control" placeholder=""  />
							</div> 
						</td>
						<td width=4%></td>
						<td width=48%>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">
								Hoja de vida							  
							  </span>
								<h5>
								<?php if($row["coordinador_3_archivo"]){ ?> 
									<a href="<?php echo $row["coordinador_3_archivo"] ?>" target="blank"> &nbsp;&nbsp; Descargar hoja de vida </a>
								<?php }else{ ?>
									- - - -
								<?php } ?>
								</h5>
							</div> 
						</td>
					</tr>
				</table>
				
				<table width='100%'>
					<tr>
						<td width='52%'>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">N&uacute;mero estimado de trabajadores a laboral en el</span>							 
							  <input readonly  name="trabajadores_cantidad"  id="trabajadores_cantidad" value="<?php echo $row["trabajadores_cantidad"] ?>"  type="number"  class="form-control" placeholder="" required />
							</div> 
						</td>	
						<td width='4%'></td>
						<td width='44%'>
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">¿Tiene un Programa de Salud Ocupacional?</span>
								<select disabled name="programa_salud" class="form-control">
									<option <?php if($row["programa_salud"]=='n'){ ?> selected <?php } ?> value='n'>No</option>
									<option <?php if($row["programa_salud"]=='s'){ ?> selected <?php } ?> value='s'>Si</option>
							 	</select>
							</div> 
						</td>	
					</tr>
					
					<tr>
						<td >
							<div class=" form-group input-group input-group-lg" style="width:100%">
							  <span class="input-group-addon">¿Tiene un Sistema de Gestion de la seguridad y Salud?</span>							 
								<select disabled name="sistema_gestion" class="form-control">
									<option <?php if($row["sistema_gestion"]=='n'){ ?> selected <?php } ?> value='n'>No</option>
									<option <?php if($row["sistema_gestion"]=='s'){ ?> selected <?php } ?> value='s'>Si</option>
							 	</select>
							</div> 
						</td>
					</tr>
				</table>
			</div>
											
		</div>
	</div>

