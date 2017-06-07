 <?php 

include("../cnx.php");
 
echo $sql_mun = "SELECT * from municipio WHERE id_departamento = ".$_GET['id'];
$res_mun = mysql_query($sql_mun);
 echo '<option value="">Seleccione un municipio</option>';
while ($row_mun = mysql_fetch_array($res_mun)) {
    echo '<option value="'.$row_mun['id'].'">'.utf8_encode($row_mun['nombre']).'</option>';
};

?>