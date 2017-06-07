
<h2> TEMPORAL ACTUALIZAR</h2>


<?php
$sql_item = "SELECT valor, id FROM  `stohome_item` ";
$res_item = mysql_query($sql_item);
while($row_item = mysql_fetch_array($res_item))
{

	$valor_item = $row_item["valor"] / 30;
	$valor_item = round($valor_item , 2);
		
	$sql_ejecutado  = "UPDATE  `stohome_ejecutado` SET  `valor` =  '".$valor_item."' WHERE  `id_item` = '".$row_item["id"]."' ;";
	mysql_query($sql_ejecutado);

}
?>


<?php
$sql_item = "SELECT valor, id FROM  `stohome_item` ";
$res_item = mysql_query($sql_item);
while($row_item = mysql_fetch_array($res_item))
{

	$valor_item = $row_item["valor"] / 30;
	$valor_item = round($valor_item , 2);
		
	$sql_ejecutado  = "UPDATE  `stohome_ejecutado` SET  `valor` =  '".$valor_item."' WHERE  `id_item` = '".$row_item["id"]."' ;";
	mysql_query($sql_ejecutado);

}
?>




