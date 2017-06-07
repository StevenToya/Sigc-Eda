<?php
include("../cnx.php");
$cuo = '2000';


function limpiar($data)
{		
	$data = str_replace( '&aacute;', 'a',$data);	
	$data = str_replace( '&eacute;', 'e',$data);		
	$data = str_replace( '&iacute;', 'i',$data);		
	$data = str_replace( '&oacute;', 'o',$data);		
	$data = str_replace( '&uacute;', 'u',$data);	
	$data = str_replace( '&Aacute;', 'A',$data);
	$data = str_replace( '&Eacute;', 'E',$data);	
	$data = str_replace( '&Iacute;', 'I',$data);		
	$data = str_replace( '&Oacute;', 'O',$data);	
	$data = str_replace( '&Uacute;', 'U',$data);		
	$data = str_replace( '&ntilde;', 'n',$data);		
	$data = str_replace( '&Ntilde;', 'N',$data);		
	return $data;
}



$f = fopen("garantia.csv","w");
$sep = ";"; //separador

$linea = "TECNICO".$sep."OT".$sep."FECHA EJECUCION".$sep."TIPO TRABAJO".$sep."LOCALIDAD".$sep."ZONA"."\n"; 
fwrite($f,$linea);

 $sql = "SELECT tramite.ot, tecnico.nombre AS nom_tecnico, tramite.fecha_atencion_orden, tipo_trabajo.nombre AS nom_trabajo, 
   localidad.nombre AS nom_localidad, tramite.zona, tramite.id
   FROM tramite 
				LEFT JOIN tecnico ON 
					tramite.id_tecnico = tecnico.id
				LEFT JOIN localidad ON 
					tramite.id_localidad = localidad.id
				LEFT JOIN tipo_trabajo ON 
					tramite.id_tipo_trabajo = tipo_trabajo.id
				WHERE 		
				tramite.codigo_unidad_operativa = '".$cuo."' AND 
				tramite.ultimo='s'   AND 
				garantia_vista = 'n' AND
				tipo_garantia = '1' ;";
   $res = mysql_query($sql);
   while($row = mysql_fetch_array($res))
   {
		$row["nom_tecnico"] = limpiar($row["nom_tecnico"]);
		$row["ot"] = limpiar($row["ot"]);
		$row["fecha_atencion_orden"] = limpiar($row["fecha_atencion_orden"]);
		$row["nom_trabajo"] = limpiar($row["nom_trabajo"]);
		$row["nom_localidad"] = limpiar($row["nom_localidad"]);
		$row["zona"] = limpiar($row["zona"]);
			
		$linea = $row["nom_tecnico"].$sep.$row["ot"].$sep.$row["fecha_atencion_orden"].$sep.$row["nom_trabajo"].$sep.$row["nom_localidad"].$sep.$row["zona"]."\n"; 
		fwrite($f,$linea);
  }
fclose($f);



function enviar_correo($correo_destino, $nombre_destino, $mensaje, $asunto, $adjunto)
{
	date_default_timezone_set('America/El_Salvador'); //Se define la zona horaria
	require_once('../libreria/mailer/class.phpmailer.php'); //Incluimos la clase phpmailer

	$mail = new PHPMailer(true); // Declaramos un nuevo correo, el parametro true significa que mostrara excepciones y errores.

	$mail->IsSMTP(); // Se especifica a la clase que se utilizará SMTP
	//------------------------------------------------------
	  $correo_emisor="energia.andina.notificacion@gmail.com";     
	  $nombre_emisor="S.I.G.C.";               //Nombre de quien envía el correo
	  $contrasena="erickpaulr";          //contraseña de tu cuenta en Gmail
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

	$mensaje = "
	<b>BUENOS DIAS</b>
	<br><br>

	 A continuación se adjuntan los archivos con la <b>GRANTIAS, REINCIDENCIAS Y REITERATIVOS</b> que detecto el sistema de Alertas SIGC<br><br>
	Por favor tramitar su atención de manera prioritaria y enviar el soporte de la atención al siguiente Buzon: <br><br>conf.gareinrei447@edatel.com.co 
	<br><br><br><br><br>

	<b><font color=red>Manuel Antonio</font> <font color=blue>Noriega Velásquez</font>.</b><br>
	Supervisor Delegado Contrato 52000000447<br>
	E-mail: manoriegav@edatel.com.co<br>
	conf.gareinrei447@edatel.com.co<br> ";

	 enviar_correo("erickpaulr@gmail.com", "Erick Reyes", $mensaje, "Garantias de SIGC", "garantia.csv");

?>
