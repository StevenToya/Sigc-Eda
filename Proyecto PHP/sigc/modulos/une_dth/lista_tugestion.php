<?php
if($PERMISOS_GC["une_dth_ges"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}


if($_GET["excel"])
{
	include("../../cnx.php");
		
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	?>
		<table border=1>
			<tr>
				<th bgcolor=red>Pedido</th>
				<th bgcolor=red>Ciudad</th>
				<th bgcolor=red>Tipo de trabajo</th>
				<th bgcolor=red>Nombre Funcionario</th>
				<th bgcolor=red>Codigo Funcionario</th>
				<th bgcolor=red>Segmento</th>
				<th bgcolor=red>Producto</th>
				<th bgcolor=red>Producto homologado</th>				
				<th bgcolor=red>Tecnologia</th>
				<th bgcolor=red>Proceso</th>
				<th bgcolor=red>Empresa</th>
				<th bgcolor=red>Fecha</th>								
			</tr>		
	<?php
	if($_GET["ee"])
	{	$query = " estado_material = '".$_GET["ee"]."' AND ";	}else{	$query = ""; }
	
	
	
	$sql = "SELECT * FROM une_pedido 
	WHERE ".$query ." fecha >= '".$_GET["aa"]."-".$_GET["mm"]."-01' AND fecha <= '".$_GET["aa"]."-".$_GET["mm"]."-31' 
	ORDER BY numero";
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res)){
	?>
		<tr>
			<td><?php echo $row["numero"] ?></td>
			<td><?php echo $row["ciudad"] ?></td>
			<td><?php echo $row["tipo_trabajo"] ?></td>
			<td><?php echo $row["nombre_funcionario"] ?></td>
			<td><?php echo $row["codigo_funcionario"] ?></td>
			<td><?php echo $row["segmento"] ?></td>
			<td><?php echo $row["producto"] ?></td>
			<td><?php echo $row["producto_homologado"] ?></td>
			<td><?php echo $row["tecnologia"] ?></td>
			<td><?php echo $row["proceso"] ?></td>	
			<td><?php echo $row["empresa"] ?></td>	
			<td><?php echo $row["fecha"] ?></td>
			
		</tr>
		
	<?php	
	}
die();	
}
?>


<h2>TUS TRAMITES GESTIONADOS</h2>
<div align=right> 						
	<a href='?cmp=ingresar'><font color=green><i class="fa fa-plus-square fa-2x"></i></font> Ingresar tramite DTH</a>					
</div>

<center>
<div class="panel panel-default" style="width:100%;">
   <div align=left class="panel-heading">Tramites DTH ingresados por usted</div>
  <table align=center class="table table-striped table-bordered table-hover" id="dataTables-example">
  <thead>
   <tr>
		<th>Pedido</th>		
		<th>Cliente</th>
		<th>Direccion</th>		
		<th>Municipio</th>	
		<th>Zona</th>	
		<th>Fecha sistema</th>			
		<th>Detalle</th>	
   </tr>
   </thead>
   <tbody>
   <?php
    
   $sql_pedido = "SELECT une_dth.pedido, une_dth.cliente_nombre, une_dth.cliente_direccion, municipio.nombre AS nom_municipio,
   une_dth.zona, une_dth.fecha_registro, une_dth.id, usuario.nombre AS nom_usuario, usuario.apellido AS ape_usuario
				FROM  une_dth 
				INNER JOIN municipio ON une_dth.id_municipio = municipio.id
				INNER JOIN usuario ON une_dth.id_usuario = usuario.id
				WHERE une_dth.id_usuario = '".$_SESSION["user_id"]."'	 ";
   $res_pedido = mysql_query($sql_pedido);
   while($row_pedido = mysql_fetch_array($res_pedido))
   {			
	?>
		<tr>
			<td><?php echo $row_pedido["pedido"] ?> </td>
			<td><?php echo $row_pedido["cliente_nombre"] ?></td>
			<td><?php echo $row_pedido["cliente_direccion"] ?></td>
			<td><?php echo utf8_encode($row_pedido["nom_municipio"]) ?></td>
			<td><?php echo $row_pedido["zona"] ?> </td>
			<td><?php echo $row_pedido["fecha_registro"] ?></td>				
			<td align=center><a href="?cmp=detalle&id=<?php echo $row_pedido["id"] ?>"> <font <?php echo $font ?> ><i class="fa fa-eye fa-2x"> </i></font> </a></td></td>
	   </tr>
		   <?php 
   }     
?>
   </tbody>

  </table>
</div>
</center>

<br><br>