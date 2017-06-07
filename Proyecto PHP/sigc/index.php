<?php
session_start();
include("cnx.php");
$cuo = '2000';


include("query.php");


function diferencia_fecha($init,$finish)
{      
    $diferencia = strtotime($finish) - strtotime($init);
 
	$vect["segundos"]  = floor($diferencia); // segundos
	$vect["minutos"]  = floor($diferencia/60); // minutos
	$vect["horas"]  = floor($diferencia/3660); // horas
	$vect["dias"]  = floor($diferencia/86400); // días
	$vect["meses"]  = floor($diferencia/2592000); // meses
	$vect["anos"]  = floor($diferencia/31104000); // años 
	 
	return $vect;
}


if($_POST["clave_dos"])
{
	$_SESSION["clave_dos"] =  md5($_POST["clave_dos"]);
	$error = "Clave incorrecta";
}


function enviar_correo($correo_destino, $nombre_destino, $mensaje, $asunto, $adjunto)
{
			date_default_timezone_set('America/El_Salvador'); //Se define la zona horaria
			require_once('libreria/mailer/class.phpmailer.php'); //Incluimos la clase phpmailer

			$mail = new PHPMailer(true); // Declaramos un nuevo correo, el parametro true significa que mostrara excepciones y errores.

			$mail->IsSMTP(); // Se especifica a la clase que se utilizará SMTP
			//------------------------------------------------------
			  $correo_emisor="sistemascontratos@gmail.com";     
			  $nombre_emisor="S.I.G.C.";               //Nombre de quien envía el correo
			  $contrasena="sigc7890";          //contraseña de tu cuenta en Gmail
			  $correo_destino=$correo_destino;      //Correo de quien recibe
			  $nombre_destino=$nombre_destino;                //Nombre de quien recibe
			//--------------------------------------------------------
			  $mail->SMTPDebug  = 2;                     
			  $mail->SMTPAuth   = true;                  // Habilita la autenticación SMTP
			  $mail->SMTPSecure = "ssl";                 // Establece el tipo de seguridad SMTP
			  $mail->Host       = "smtp.gmail.com";      // Establece Gmail como el servidor SMTP
			  $mail->Port       = 465;                   // Establece el puerto del servidor SMTP de Gmail
			  $mail->Username   = $correo_emisor;  	     // Usuario Gmail
			  $mail->Password   = $contrasena;           // Contraseña Gmail
			  
			  $mail->AddReplyTo($correo_emisor, $nombre_emisor);
			  $mail->AddAddress($correo_destino, $nombre_destino);
			  $mail->SetFrom($correo_emisor, $nombre_emisor);
			  //Asunto del correo
			  $mail->Subject = $asunto;
			
			  $mail->MsgHTML($mensaje);
			  //Archivos adjuntos
			  if($adjunto){
				$mail->AddAttachment($adjunto);      // Archivos Adjuntos
				}
			  //Enviamos el correo
			  $mail->Send();
			  
}

function moneda($dato)
{	return number_format($dato, 2, ',', '.'); }


function limpiar_numero($dato)
{
	return intval(preg_replace('/[^0-9]+/', '', $dato), 10); 
}

function limpiar($data)
{
	$data = str_replace("<","&lt;",$data);
	$data = str_replace(">","&gt;",$data);
	$data = str_replace('"',"&#34;",$data);
	$data = str_replace("'","&#39;",$data);
	$data = str_replace("/","&#47;",$data);
	
	$data = str_replace('á', '&aacute;', $data);	$data = str_replace('à', '&aacute;', $data);	
	$data = str_replace('é', '&eacute;', $data);	$data = str_replace('è', '&eacute;', $data);	
	$data = str_replace('í', '&iacute;', $data);	$data = str_replace('ì', '&iacute;', $data);	
	$data = str_replace('ó', '&oacute;', $data);	$data = str_replace('ò', '&oacute;', $data);	
	$data = str_replace('ú', '&uacute;', $data);	$data = str_replace('ù', '&uacute;', $data);	
	$data = str_replace('Á', '&Aacute;', $data);	$data = str_replace('À', '&Aacute;', $data);	
	$data = str_replace('É', '&Eacute;', $data);	$data = str_replace('È', '&Eacute;', $data);	
	$data = str_replace('Í', '&Iacute;', $data);	$data = str_replace('Ì', '&Iacute;', $data);	
	$data = str_replace('Ó', '&Oacute;', $data);	$data = str_replace('Ò', '&Oacute;', $data);	
	$data = str_replace('Ú', '&Uacute;', $data);	$data = str_replace('Ù', '&Uacute;', $data);	
	$data = str_replace('ñ', '&ntilde;', $data);	$data = str_replace('Ñ', '&Ntilde;', $data);	
	
	$data = str_replace(utf8_decode('á'), '&aacute;', $data);	$data = str_replace(utf8_decode('à'), '&aacute;', $data);	
	$data = str_replace(utf8_decode('é'), '&eacute;', $data);	$data = str_replace(utf8_decode('è'), '&eacute;', $data);	
	$data = str_replace(utf8_decode('í'), '&iacute;', $data);	$data = str_replace(utf8_decode('ì'), '&iacute;', $data);	
	$data = str_replace(utf8_decode('ó'), '&oacute;', $data);	$data = str_replace(utf8_decode('ò'), '&oacute;', $data);	
	$data = str_replace(utf8_decode('ú'), '&uacute;', $data);	$data = str_replace(utf8_decode('ù'), '&uacute;', $data);	
	$data = str_replace(utf8_decode('Á'), '&Aacute;', $data);	$data = str_replace(utf8_decode('À'), '&Aacute;', $data);	
	$data = str_replace(utf8_decode('É'), '&Eacute;', $data);	$data = str_replace(utf8_decode('È'), '&Eacute;', $data);	
	$data = str_replace(utf8_decode('Í'), '&Iacute;', $data);	$data = str_replace(utf8_decode('Ì'), '&Iacute;', $data);	
	$data = str_replace(utf8_decode('Ó'), '&Oacute;', $data);	$data = str_replace(utf8_decode('Ò'), '&Oacute;', $data);	
	$data = str_replace(utf8_decode('Ú'), '&Uacute;', $data);	$data = str_replace(utf8_decode('Ù'), '&Uacute;', $data);	
	$data = str_replace(utf8_decode('ñ'), '&ntilde;', $data);	$data = str_replace(utf8_decode('Ñ'), '&Ntilde;', $data);

	$data = str_replace(utf8_encode('á'), '&aacute;', $data);	$data = str_replace(utf8_encode('à'), '&aacute;', $data);	
	$data = str_replace(utf8_encode('é'), '&eacute;', $data);	$data = str_replace(utf8_encode('è'), '&eacute;', $data);	
	$data = str_replace(utf8_encode('í'), '&iacute;', $data);	$data = str_replace(utf8_encode('ì'), '&iacute;', $data);	
	$data = str_replace(utf8_encode('ó'), '&oacute;', $data);	$data = str_replace(utf8_encode('ò'), '&oacute;', $data);	
	$data = str_replace(utf8_encode('ú'), '&uacute;', $data);	$data = str_replace(utf8_encode('ù'), '&uacute;', $data);	
	$data = str_replace(utf8_encode('Á'), '&Aacute;', $data);	$data = str_replace(utf8_encode('À'), '&Aacute;', $data);	
	$data = str_replace(utf8_encode('É'), '&Eacute;', $data);	$data = str_replace(utf8_encode('È'), '&Eacute;', $data);	
	$data = str_replace(utf8_encode('Í'), '&Iacute;', $data);	$data = str_replace(utf8_encode('Ì'), '&Iacute;', $data);	
	$data = str_replace(utf8_encode('Ó'), '&Oacute;', $data);	$data = str_replace(utf8_encode('Ò'), '&Oacute;', $data);	
	$data = str_replace(utf8_encode('Ú'), '&Uacute;', $data);	$data = str_replace(utf8_encode('Ù'), '&Uacute;', $data);	
	$data = str_replace(utf8_encode('ñ'), '&ntilde;', $data);	$data = str_replace(utf8_encode('Ñ'), '&Ntilde;', $data);
	
	return $data;
}


if($_GET["cerrar_session"])
{
	$_SESSION["user_id"] = "";
	$_SESSION["md"] = "";
	$_SESSION["cmp"] = "";
	$_SESSION["nst"] = "";	
}


if($_GET["md"])
{ $_SESSION["md"] = $_GET["md"];}

if($_GET["cmp"])
{ $_SESSION["cmp"] = $_GET["cmp"];}

?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>S.I.G.C.</title>
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
	<link href="js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <link href="css/custom.css" rel="stylesheet" />
	 <link href="js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
	 <script type="text/javascript" src="js/datepicker.js"></script>	
    <link href="css/datepicker.css" rel="stylesheet" />
	<script src="js/chart.min.js"></script> 
	
		 <script src="js/jQuery-2.1.4.min.js"></script>
		 <script src="js/bootstrap.min.js"></script>
		 <script src="js/Chart.min.js"></script> 
	
</head>
<body ONLOAD="timer()">

<?php
/////// SI EXISTE USUARIO ///////////
if($_SESSION["user_id"] && $_SESSION["md"] && $_SESSION["cmp"] && $_SESSION["nst"])
{
	 $sql_index = "SELECT usuario.id, usuario.nombre, usuario.apellido, instancia.nombre AS nombre_instancia 
		FROM usuario 
		INNER JOIN instancia ON usuario.id_instancia = instancia.id
		WHERE usuario.id = '".$_SESSION["user_id"]."' AND usuario.estado = 1 LIMIT 1 ;";
	$res_index = mysql_query($sql_index);	
	$row_index = mysql_fetch_array($res_index);	
	if(!$row_index["id"]){  echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cerrar_session=1'>"; die(); }
	
	 $sql_permisos = "SELECT componente.codigo FROM componente 
		INNER JOIN permiso ON componente.id = permiso.id_componente
		WHERE permiso.id_usuario = '".$_SESSION["user_id"]."' ";
	$res_permisos = mysql_query($sql_permisos);
	while($row_permisos = mysql_fetch_array($res_permisos))
	{			
		$PERMISOS_GC[$row_permisos["codigo"]] = 'Si';
	}
		
	
?>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
              	 <img class="navbar-brand" src="img/logo_gob2.png">
            </div>
			<!-- class="navbar-brand" -->

			<div style="color: #000000;
					padding: 10px 50px 5px 50px;
					font-size: 30px;">
					<center>SISTEMA DE INFORMACI&Oacute;N Y GESTI&Oacute;N DE CONTRATO (SIGC) </center>
			</div> 

			<div style="color: #000000;
					padding: 5px 50px 5px 50px;
					float: right;
					font-size: 16px;">					
					<?php echo $row_index["nombre"] ?> <?php echo $row_index["apellido"] ?> ( <b><?php echo $row_index["nombre_instancia"] ?></b> ) &nbsp; <a href="?cerrar_session=1" class="btn btn-danger square-btn-adjust">Salir</a>
			</div>
			
			<?php  if($_SESSION["md"]=='liquidacion'){ ?>
				<div style="color: white;
						padding: 5px 50px 5px 50px;
						float: left;
						font-size: 16px;">
						<?php
						$sql_fecha = "SELECT tramite_instalacion FROM registro_fecha WHERE id='1' LIMIT 1";
						$res_fecha = mysql_query($sql_fecha);
						$row_fecha = mysql_fetch_array($res_fecha);
						?>
						Act. tramites: <b><?php echo $row_fecha["tramite_instalacion"] ?></b>&nbsp;&nbsp;&nbsp;					
				</div>
			<?php } ?>

        </nav>   
           <!-- /. NAV TOP  -->
         <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
			             
					 <li>
						<a <?php if($_SESSION["md"]=='inicio'){ ?>  class="active-menu" <?php } ?> href="?md=inicio&cmp=index">
							<i class="fa fa-desktop fa-3x"></i> <font color="#000000">PANEL INICIAL</font>
						</a>
                    </li>
					
					<li>
						<a <?php  if($_SESSION["md"]=='une_dth_pedido'){ ?>  class="active-menu" <?php  } ?> href="#">
							<i class="fa fa-cogs fa-3x"></i> <font color="#000000"> CONFIGURACION </font><span class="fa arrow"></span>
						</a>
						<ul class="nav nav-second-level">
							<li>
								<a <?php if($_SESSION["md"]=='usuario'){ ?>  class="active-menu" <?php } ?> href="?md=usuario&cmp=lista">
									<font color="#000000"> Usuarios</font>
								</a>
							</li>						
								<li><a <?php if($_SESSION["md"]=='clave'){ ?>  class="active-menu" <?php } ?> href="?md=clave&cmp=index"> <font color="#000000">Cambiar de clave</font>	</a>
							</li>
							
						</ul>						
					</li>
					
					 <li>
						<a <?php if($_SESSION["md"]=='inicio1'){ ?>  class="active-menu" <?php } ?> href="?md=inicio1&cmp=index1">
							<i class="fa fa-book fa-3x"></i> <font color="#000000">CONTRATOS</font>
						</a>
                    </li>
				
					
					<li>
						<a <?php  if($_SESSION["md"]=='une_pedido'){ ?>  class="active-menu" <?php  } ?> href="#">
							<i class="fa fa-envelope fa-3x"></i>  <font color="#000000">ADMIN - JURIDICO</font> <span class="fa arrow"></span>
						</a>
                      
                        <ul class="nav nav-second-level">
											
							
							
							<li>
								<a href="?md=une_pedido&cmp=lista_pendiente1"><font color="#000000">Generaci&oacute;n contrato</font></a>
							</li>
							
							<li>
								<a href="?md=une_pedido&cmp=lista_pendiente1"><font color="#000000">Informacion contrato</font></a>
							</li>
							
							<li>
								<a href="?md=une_pedido&cmp=lista_pendiente1"><font color="#000000">Poliza contrato</font></a>
							</li>
															
							<li>
								<a <?php if($_SESSION["md"]=='soporte_pago'){ ?>  class="active-menu" <?php } ?> href="?md=soporte_pago&cmp=panel_inicial">
									 <font color="#000000">Soporte pago</font>
								</a>
							</li>
							
							<li>
								<a <?php if($_SESSION["md"]=='documento'){ ?>  class="active-menu" <?php } ?> href="?md=documento&cmp=index">
									 <font color="#000000">Documentacion</font>
								</a>
							</li>
							
							<li>
								<a href="?md=une_pedido&cmp=lista_pendiente"><font color="#000000">Comunicaciones  enviadas</font></a>
							</li>
							
							<li>
								<a href="?md=une_pedido&cmp=lista_pendiente"><font color="#000000">Comunicaciones  recividas</font></a>
							</li>
							
						 </ul>
					</li>
					
					<li>
                        <a <?php if($_SESSION["md"]=='hoja_vida'){ ?>  class="active-menu" <?php } ?> href="#">
							<i class="fa fa-gavel fa-3x"></i> <font color="#000000">  OPERATIVO </font> <span class="fa arrow"></span>
						</a>
                        <ul class="nav nav-second-level">
							<li>	
								<a <?php if($_SESSION["md"]=='sgsst'){ ?>  class="active-menu" <?php } ?> href="#">
									<font color="#000000">  Sg-sst </font> <span class="fa arrow"></span>
								</a>
								<ul class="nav nav-second-level">	
									<?php  if($PERMISOS_GC["sst_ges"]=='Si'){   ?>
										<li>
											<a href="?md=sgsst&cmp=lista_sst_gestion"><font color="#000000">Gestion</font></a>
										</li>
									<?php  }  ?>
									
									<?php  if($PERMISOS_GC["sst_act"]=='Si'){   ?>
										 <li>
											<a href="?md=sgsst&cmp=lista_sst_actualizar" ><font color="#000000">Actualizacion</font></a>
										</li>	
									<?php  }   ?>
									

								</ul>
							</li>
							
							<li>
								<a <?php if($_SESSION["md"]=='ambiental'){ ?>  class="active-menu" <?php } ?> href="#">
									<font color="#000000"> Gestion Ambiental</font> <span class="fa arrow"></span>
								</a>
								<ul class="nav nav-second-level">	
									<?php  if($PERMISOS_GC["amb_ges"]=='Si'){   ?>
										<li>
											<a href="?md=ambiental&cmp=lista_amb_gestion"><font color="#000000">Gestion</font></a>
										</li>
									<?php  }  ?>
									
									<?php  if($PERMISOS_GC["amb_act"]=='Si'){   ?>
										 <li>
											<a href="?md=ambiental&cmp=lista_amb_actualizar" ><font color="#000000">Actualizacion</font></a>
										</li>	
									<?php  }   ?>
								</ul>
							</li>	


							<li>
								<a <?php if($_SESSION["md"]=='vehiculo'){ ?>  class="active-menu" <?php } ?> href="#">
									 <font color="#000000">Vehiculo</font> <span class="fa arrow"></span>
								</a>
								<ul class="nav nav-second-level">
								
								<?php if($PERMISOS_GC["veh_cre"]=='Si'){ ?>	
										 <li>
											<a href="?md=vehiculo&cmp=lista" ><font color="#000000">Ingreso y gestion</font></a>
										</li>
								<?php } ?>
								
								
								<?php if($PERMISOS_GC["veh_aud"]=='Si'){ ?>
									<li>
										<a href="?md=vehiculo&cmp=lista_auditada" ><font color="#000000">Auditar documentos</font></a>
									</li>
								<?php } ?>
								</ul>
							</li>
							
							<li>
								<a <?php  if($_SESSION["md"]=='auditoria'){ ?>  class="active-menu" <?php  } ?> href="#">
									 <font color="#000000">Auditoria</font> <span class="fa arrow"></span>
								</a>
								<ul class="nav nav-second-level">	
									<?php   if($PERMISOS_GC["aud_conf"]=='Si'){  ?>
										<li>
											<a href="?md=auditoria&cmp=lista_base"><font color="#000000">Configuracion</font></a>
										</li>															
									<?php  }  ?>	

									<?php   if($PERMISOS_GC["aud_gest"]=='Si'){  ?>
										<li>
											<a href="?md=auditoria&cmp=lista_gestion"><font color="#000000">Gestionar</font></a>
										</li>															
									<?php  }  ?>

									<?php   if($PERMISOS_GC["aud_ejec"]=='Si'){  ?>
										<li>
											<a href="?md=auditoria&cmp=lista_ejecutar"><font color="#000000">Ejecutar</font></a>
										</li>															
									<?php  }  ?>
									
								</ul>
							</li>
							
								<li>
										<a <?php if($_SESSION["md"]=='hoja_vida'){ ?>  class="active-menu" <?php } ?> href="#">
											<font color="#000000">  Hoja de vida </font> <span class="fa arrow"></span>
										</a>
										<ul class="nav nav-second-level">
										
														
										<?php if($PERMISOS_GC["hv_cre"]=='Si'){ ?>	
												 <li>
													<a href="?md=hoja_vida&cmp=lista_pendiente" ><font color="#000000">Ingreso de hojas de vida</font></a>
												</li>
										<?php } ?>
										
										
										<?php if($PERMISOS_GC["hv_doc"]=='Si'){ ?>
											<li>
												<a href="?md=hoja_vida&cmp=lista_auditada" ><font color="#000000"> Ingreso de documentos</font></a>
											</li>
										<?php } ?>
										
										 <?php if($PERMISOS_GC["hv_aud"]=='Si'){ ?>   
											<li>
												<a href="?md=hoja_vida&cmp=lista_habilitar" ><font color="#000000">Auditar datos basicos</font></a>
											</li>
										<?php } ?>
										
										<?php if($PERMISOS_GC["hv_aud_doc"]=='Si'){ ?>
										   <li>
												<a href="?md=hoja_vida&cmp=auditar_pendiente" ><font color="#000000">Auditar documento</font></a>
											</li>
										<?php } ?>	
										
										<?php if($PERMISOS_GC["hv_cre"]=='Si'){ ?>	
											<li>
												<a href="?md=hoja_vida&cmp=lista_cargo" ><font color="#000000">Gestion de cargos</font></a>
											</li>
										<?php } ?>
										
										</ul>
									 </li>

							
						 </ul>
					</li>
					
					<li>
                        <a <?php if($_SESSION["md"]=='hoja_vida'){ ?>  class="active-menu" <?php } ?> href="#">
							<i class="fa fa-money fa-3x"></i> <font color="#000000">  FINANCIERO </font> <span class="fa arrow"></span>
						</a>
                        <ul class="nav nav-second-level">
								
							<li>
								<a href="?md=une_pedido&cmp=lista_pendiente"><font color="#000000">Informacion contrato</font></a>
							</li>
							
							<li>
								<a href="?md=une_pedido&cmp=lista_pendiente"><font color="#000000">Proyeccion pagos</font></a>
							</li>
							
							<li>
								<a href="?md=une_pedido&cmp=lista_pendiente"><font color="#000000">Ejecucion real</font></a>
							</li>
							
							<li>
								<a href="?md=une_pedido&cmp=lista_pendiente"><font color="#000000">Disponibilidad financiera</font></a>
							</li>
							
							
						 </ul>
					</li>
					
					<li>
                        <a  href="#">
							<i class="fa fa-search fa-3x"></i> <font color="#000000">  INTERVENTORIA </font> <span class="fa arrow"></span>
						</a>
                        <ul class="nav nav-second-level">
							<li>
								<a href="?md=Interventoria&cmp=datos_interventoria"><font color="#000000">Datos interventoria</font></a>
							</li>
							
							<li>
								<a href="?md=Interventoria&cmp=informes_interventoria"><font color="#000000">Informes interventoria</font></a>
							</li>
							
							<li>
								<a href="?md=une_pedido&cmp=lista_pendiente"><font color="#000000">Registros fotografico</font></a>
							</li>
							
							<li>
								<a href="?md=une_pedido&cmp=lista_pendiente"><font color="#000000">Auditoria</font></a>
							</li>
						 </ul>
					</li>
					
					<li>
                        <a  href="#">
							<i class="fa fa-info fa-3x"></i> <font color="#000000">  REPORTES </font> <span class="fa arrow"></span>
						</a>
                        <ul class="nav nav-second-level">
							<li>
								<a href="?md=une_pedido&cmp=lista_pendiente"><font color="#000000">Rep Individual</font></a>
							</li>
							
							<li>
								<a href="?md=une_pedido&cmp=lista_pendiente"><font color="#000000">Rep General</font></a>
							</li>
						 </ul>
					</li>
				

              
            </div>
            
        </nav>  
       
        <div id="page-wrapper" >
            <div id="page-inner">
				<!-- CUERPO -->                		
					<?php	
					$sql_seguridad = "SELECT id FROM modulo_seguridad WHERE componente = '".$_SESSION["cmp"]."' AND modulo = '".$_SESSION["md"]."' LIMIT 1";
					$res_seguridad = mysql_query($sql_seguridad);
					$row_seguridad = mysql_fetch_array($res_seguridad);
					if(!$row_seguridad["id"])
					{
						//////VISTA DE CONTENIDOS DE LOS MODULOS ///////////////
						$archivo = "modulos/".$_SESSION["md"]."/".$_SESSION["cmp"].".php";
						include($archivo);
						$_SESSION["clave_dos"] = "";
					}
					else
					{
						$sql_seguridad = "SELECT id FROM usuario WHERE id='".$_SESSION["user_id"]."' AND clave_dos ='".$_SESSION["clave_dos"]."' AND clave_dos IS NOT NULL LIMIT 1";
						$res_seguridad = mysql_query($sql_seguridad);
						$row_seguridad = mysql_fetch_array($res_seguridad);
						if(!$row_seguridad["id"])
						{
							include('seguridad.php');					
						}
						else
						{
							//////VISTA DE CONTENIDOS DE LOS MODULOS ///////////////
							$archivo = "modulos/".$_SESSION["md"]."/".$_SESSION["cmp"].".php";
							include($archivo);							
						}
						
					}
					
					
					?>	
						
				<!-- FIN CUERPO -->
            </div>        
        </div>
  
	<?php
	}else{
	?>
			 <br>
			   <table width="80%" border=0  align=center>					
					<tr>
						<td valign=top colspan=2>
							<img src="img/logo_gob.jpg" width="50%"><br>
						</td>
						<td valign=bottom align=right>
							<b><font size="2"><?php echo date("Y-m-d G:i:s") ?></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
						</td>
					</tr>
					<tr>
						<td valign=top colspan=3 bgcolor="#3ADF00">
							<h3> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>SISTEMA DE INFORMACI&Oacute;N Y GESTI&Oacute;N DE CONTRATO</b></font> </h3>
						<td>
					</tr>
					<tr>
						<td width="35%" valign=top> <br><br>
								<!-- INGRESO DE USUARIO -->
									
									<div class="jumbotron">
										<form action="rpt.php"  method="post" name="form1" id="form1">
											<div class="form-group input-group input-group-lg" >
											  <span class="input-group-addon" style="width:10%" ><i class="fa fa-user"></i></span>
											  <input   autocomplete="off" name="nom_user"  id="nom_user" type="text" class="form-control" placeholder="Usuario" required />
											</div>

											<div class="form-group input-group input-group-lg" >
											  <span class="input-group-addon" style="width:10%" ><i class="fa fa-key"></i></span>
											  <input  autocomplete="off" name="password" id="password" type="password" class="form-control" placeholder="Contrase&ntilde;a" required />
											</div>
											
											
											<div class="form-group input-group input-group-lg" >
											  <span class="input-group-addon" style="width:10%" ><i class="fa fa-laptop"></i></span>
											 <select  class="form-control" name="instancia" id="instancia" required>
												<option value="">Seleccione una instancia</option>
												<?php
													$sql_inst = "SELECT * FROM instancia WHERE estado = 1";
													$res_inst = mysql_query($sql_inst);
													while($row_int = mysql_fetch_array($res_inst)){
												?>											
													<option value="<?php echo $row_int["id"] ?>"><?php echo $row_int["nombre"] ?></option>
												<?php
													}
												?>
											</select>
											</div>
												<?php if($_GET["error"]=='1'){?>
												<div class="alert alert-info">
													Usuario o contraseña no encontradas.
												</div>
											<?php } ?>
											<center><input type="submit" class="btn btn-primary" value="Ingresar"></center>
										
										</form>
										<br> 
										 <b> <i class="fa fa-comment-o fa-2x"></i> Navegadores recomendados:</b><br><br>
										 <li>Morzilla Firefox Version 44.0.2 o Superior - <a href="https://www.mozilla.org/es-ES/firefox/new/"  target=blank>Pagina oficial</a></li>
										 <li>Chrome Version 48.0.2 o Superior - <a href="https://www.google.com/chrome/browser/desktop/index.html" target=blank>Pagina oficial</a></li>
										
									</div>
								<!-- FIN DE INGRESO DE USUARIO -->
						</td>
						<td width="5%" align=center> <img src="img/vertical.png" ></td>
						<td width="60%"  valign=top><br><br>
							<img src="img/inicio.jpg" width="100%" ><br>
							<?php
								$fecha_ini = "2016-02-01";
								$vector_fecha = diferencia_fecha($fecha_ini,date("Y-m-d"));							
							?>							
							<!-- -->
							 <script language="JavaScript">
								<!--
								var dayy = "<?php echo $vector_fecha["dias"] ?>"
								var hour = "<?php echo date("G"); ?>"
								var min = "<?php echo date("i"); ?>"
								var sec = "<?php echo date("s"); ?>"
								function timer(){
								if ((min < 10) && (min != "00")){
								 dismin = "0" + min
								}
								else{
								 dismin = min
								}
								
								if ((hour < 10) && (hour != "00")){
								 dishour = "0" + hour
								}
								else{
								 dishour = hour
								}
								
								
								 dissec = (sec < 10) ? sec = "0" + sec : sec
								 document.timer.counter.value = dayy + " : " + dishour + " : " + dismin + " : " + dissec + "   DD:HH:MM:SS"

								 if (sec < 59)
								 {
								  sec++
								 }
								 else
								 {
									  sec = "0"
									  min++
									  if (min > 59)
									  {
										   min = "0"
										   hour++
										   if(hour > 23)
											{
												hour = "0"
												dayy++
											}
									   
									  }
								 }
								 window.setTimeout("timer()",1000) 
								}
								// -->
								</script> 
								
							<!--
							<form name="timer">
								<div class="form-group input-group input-group-lg" >
								  <span class="input-group-addon" style="width:10%" >Tiempo sin accidentes laborales</span>
								  <input style="color:#000000;background-color:#40FF00;font-size:22px;text-align:center;font-weight:bold"  name="counter"  id="counter" type="text" class="form-control" placeholder="Usuario" readonly />
								</div>
							</form>
						-->
						
							
						</td>
					</tr>
				</table>
						
							
	<?php
	}
	?>
  
  
    <script src="js/jquery-1.10.2.js"></script>
     <script src="js/bootstrap.min.js"></script>
     <script src="js/jquery.metisMenu.js"></script>
    <script src="js/morris/raphael-2.1.0.min.js"></script>   
     	
	 <script src="js/dataTables/jquery.dataTables.js"></script>
    <script src="js/dataTables/dataTables.bootstrap.js"></script>
    <script>
		$(document).ready(function () {
			$('#dataTables-example').dataTable();
		});
			
		$(document).ready(function(){
			$("#departamento").change(function(event){
				var id = $("#departamento").find(':selected').val();
				$("#municipio").load('libreria/lista_municipio.php?id='+id);
			});
		});	
    </script>
    <script src="js/morris/morris.js"></script> 
	<script src="js/custom.js"></script>     
	  
   
</body>
</html>
