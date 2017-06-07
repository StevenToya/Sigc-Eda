<style type="text/css"> 
.fijo {
	position:fixed !important; 
	right:0px; 
	bottom:0px; 
	z-index:10 !important
		width:1000px; 
} 

.fijoa {
	position:fixed !important; 
	left:0px; 
	bottom:0px; 
	z-index:10 !important
		width:1000px; 
} 
</style>
<?php 
if($_POST["vista"]=='N')
{
	$_POST["vista"] = 1;	
	echo "<script>alert('Debe selecionar un indicador de la lista')</script>";
}

session_start();

if($_POST["vista"])
{
	$_GET["pag"] = $_POST["vista"];
}

if($_GET["pausa"])
{
	$_SESSION["pausa"] =1;
}

if($_GET["play"])
{
	$_SESSION["pausa"] = "";
}

if($_GET["pag"])
{
	$_SESSION["pag"] = $_GET["pag"]; 
}

if(!$_SESSION["pag"])
{
	$_SESSION["pag"] = 1;
}



if($_POST["uo"])
{
	if($_POST["uo"]=='T'){$_SESSION["unidad_operativa"] = "T";  }	
	if($_POST["uo"]=='2000'){$_SESSION["unidad_operativa"] = "2000"; }
	if($_POST["uo"]=='4000'){$_SESSION["unidad_operativa"] = "4000"; }
	if($_POST["uo"]=='O'){	$_SESSION["unidad_operativa"] = "O";  }	
}


if(!$_SESSION["unidad_operativa"] || $_SESSION["unidad_operativa"] =='T'){  $uo = ""; $uop = 11000; $tit = '';}
if($_SESSION["unidad_operativa"] == "2000"){ $uo = " AND tramite.codigo_unidad_operativa = '2000' "; $uop = 9130;  $tit = ' para la unidad operativa 2000';}
if($_SESSION["unidad_operativa"] == "4000"){ $uo = " AND tramite.codigo_unidad_operativa = '4000' "; $uop =  33; $tit = ' para la unidad operativa 4000';}
if($_SESSION["unidad_operativa"] == "O"){ $uo = " AND tramite.codigo_unidad_operativa NOT IN  ('4000','2000') "; $uop = 1837; $tit = ' para la unidad operativa de EDATEL';}

echo $_SESSION["unidad_operativa"];

$archivo = $_SESSION["pag"].".php";
include($archivo);

$tems = $_SESSION["pag"] + 1;
$tema = $_SESSION["pag"] - 1;
$sig_archivo = $tems.".php";

?>


<div class="fijoa">
	<table border=0  bgcolor="#000000" align=right>
		<tr>
			<td> 
					<form name="form" method="post" action=""  >
					<select name="uo">
						<option value="T">Todas las unidades operativas</option>
						<option value="2000">2000 EIA</option>
						<option value="4000">4000 EIA</option>
						<option value="O">Otros EDATEL</option>						
					</select>
					<input type='submit' value="Ir">
					</form>
				
	</table>
</div>


<div class="fijo">      
<table border=0  bgcolor="#000000" align=right>

<tr>	
	<td width="33%"> 
		<?php if($tema > 0){ ?>
			<a href="?pag=<?php echo $tema; ?>"><b><font color='#FFFFFF'> <<-Anterior </font></b></a>
		<?php }else{ ?>
			<b> <<-Anterior </b>
		<?php } ?>
	</td>
	<td width="33%" align=center>
	    &nbsp;&nbsp;&nbsp; 
		<!--
		<?php	if($_SESSION["pausa"]!=1){	?>
			<a href="?pausa=1"><img width="30" src="../imagenes/pausa.jpg"></a>
		<?php	}else{	?>
			<a href="?play=1"><img width="30" src="../imagenes/play.jpg"></a>
		<?php	}	?>
		-->
		&nbsp;&nbsp;&nbsp;
	</td>
	<td width="33%" align="right">	
	<?php if (file_exists($sig_archivo)) { ?>
		<a href="?pag=<?php echo $tems; ?>"><b><font color='#FFFFFF'> Siguiente->> </font></b></a>
	</td>
	<?php }else{ ?>
			<b> Siguiente->> </b>
	<?php } ?>

</tr>
</table>	
</div>

 </body>

</html>