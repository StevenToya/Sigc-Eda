<?php
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
				<th>NOMBRE_EMPRESA</th>
				<th>PEDIDO_ID</th>
				<th>CIUDAD_EMPRESA</th>
				<th>TIPO_TRABAJO</th>
				<th>COD_MATERIAL</th>
				<th>NOMBRE_MATERIAL</th>
				<th>NRO_COMPONENTES</th>
				<th>NOMBRE_FUNCIONARIO</th>
				<th>COD_FUNCIONARIO</th>
				<th>SEGMENTO</th>
				<th>PRODUCTO</th>
				<th>PRODUCTO_HOMOLOGADO</th>
				<th>TECNOLOGIA_HOMOLOGADA</th>
				<th>PROCESO</th>
				<th>EXTENSIONES_TV</th>
				<th>EMPRESA_ID</th>
				<th>UNIDADMEDIDA_MATERIAL</th>
				<th>CANTIDAD_MATERIAL</th>
				<th>IDENTIFICADOR</th>
				<th>MIN_CT</th>
				<th>MAX_CT</th>
				<th>MIN_PR</th>
				<th>MAX_PR</th>
				<th>NUEVO_MIN</th>
				<th>NUEVO_MAX</th>
				<th>ALERTA_CABLE_CT</th>
				<th>CALC_PUNTOS_INSTALADOS</th>
				<th>ALERTA_PUNTOS_INST</th>
				<th>CALC_CONECTORES_INST</th>
				<th>ALERTA_CONECTORES_INST</th>
				<th>CALC_FILTROS_INST</th>
				<th>ALERTA_FILTROS</th>
				<th>FECHA</th>
				<th>TIPO_DIRECCION</th>
				<th>PRECIO</th>
				<th>PRECIO_X_CANT_REP</th>
				<th>DIFERENCIA_PRECIO_X_CANT_REP</th>
				<th>LI</th>
				<th>LS</th>
				<th>PRECIO_X_DIF_SOBRE_LIM</th>
				<th>ALERTA_CABLE_LIMITES</th>
				<th>ALERTADO</th>
				<th>REITERATIVO</th>
				<th>CLIENTE_ID</th>
				<th>SERVICIO_INSTALADOS</th>
				<th>REPARACION</th>
				<th>SERVICIOS_INSTA</th>
				<th>ESTADO_PEDIDO</th>
				<th>MUNICIPIO_DANE</th>
				<th>DEPARTAMENTO_DANE</th>
				<th>DIRECCION</th>								
			</tr>		
	<?php
	
	
	$sql = "SELECT * FROM une_pedido 
	LEFT JOIN une_pedido_material ON une_pedido.id = une_pedido_material.id_pedido
	WHERE une_pedido.fecha >= '".$_GET["aa"]."-".$_GET["mm"]."-01' AND 
	une_pedido.fecha <= '".$_GET["aa"]."-".$_GET["mm"]."-31' AND une_pedido.tipo='1' 
	AND une_pedido.ciudad IN ('Monteria','Sincelejo','Valledupar')
	ORDER BY une_pedido.numero";
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res))
	{

		$sql_mat = "SELECT * FROM une_material WHERE id = '".$row["id_material"]."' LIMIT 1";
		$res_mat = mysql_query($sql_mat);
		$row_mat = mysql_fetch_array($res_mat);
	?>
		<tr>
			<td><?php echo $row["empresa"] ?></td>
			<td><?php echo $row["numero"] ?></td>
			<td><?php echo $row["ciudad"] ?></td>
			<td><?php echo $row["tipo_trabajo"] ?></td>
			<td><?php echo $row_mat["codigo"] ?></td>
			<td><?php echo $row_mat["nombre"] ?></td>
			<td><?php echo $row["numero_componente"] ?></td>
			<td><?php echo $row["nombre_funcionario"] ?></td>
			<td><?php echo $row["codigo_funcionario"] ?></td>
			<td><?php echo $row["segmento"] ?></td>
			<td><?php echo $row["producto"] ?></td>
			<td><?php echo $row["producto_homologado"] ?></td>
			<td><?php echo $row["tecnologia"] ?></td>
			<td><?php echo $row["proceso"] ?></td>
			<td><?php echo $row["extexiones_tv"] ?></td>
			<td><?php echo $row["empresa"] ?></td>
			<td><?php echo $row["unidad_material"] ?></td>
			<td><?php echo $row["cantidad"] ?></td>
			<td><?php echo $row["identificador"] ?></td>
			<td><?php echo $row["min_ct"] ?></td>
			<td><?php echo $row["max_ct"] ?></td>
			<td><?php echo $row["min_pr"] ?></td>
			<td><?php echo $row["max_pr"] ?></td>
			<td><?php echo $row["nuevo_min"] ?></td>
			<td><?php echo $row["nuevo_max"] ?></td>
			<td><?php echo $row["alerta_cable_ct"] ?></td>
			<td><?php echo $row["calc_puntos_instalado"] ?></td>
			<td><?php echo $row["alerta_puntos_instalados"] ?></td>
			<td><?php echo $row["calc_conectores_inst"] ?></td>
			<td><?php echo $row["alerta_conectores_inst"] ?></td>
			<td><?php echo $row["calc_filtros_inst"] ?></td>
			<td><?php echo $row["alerta_filtro"] ?></td>
			<td><?php echo $row["fecha"] ?></td>
			<td><?php echo $row["tipo_direccion"] ?></td>
			<td><?php echo $row["precio"] ?></td>
			<td><?php echo $row["precio_x_cant_rep"] ?></td>
			<td><?php echo $row["difer_precio_x_cant_rep"] ?></td>
			<td><?php echo $row["li"] ?></td>
			<td><?php echo $row["ls"] ?></td>
			<td><?php echo $row["precio_x_dif_sobre_lim"] ?></td>
			<td><?php echo $row["alerta_cable_limites"] ?></td>
			<td><?php echo $row["alertado"] ?></td>
			<td><?php echo $row["reiterativo"] ?></td>
			<td><?php echo $row["cliente_id"] ?></td>
			<td><?php echo $row["servicio_instalado"] ?></td>
			<td><?php echo $row["reparacion"] ?></td>
			<td><?php echo $row["servicio_insta"] ?></td>
			<td><?php echo $row["estado_pedido"] ?></td>
			<td><?php echo $row["municipio_dane"] ?></td>
			<td><?php echo $row["departamento_dane"] ?></td>
			<td><?php echo $row["cliente_direccion"] ?></td>
		</tr>
		
	<?php	
	}

}
?>
