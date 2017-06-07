
<?php
$sql_cont = "SELECT COUNT(*) AS cantidad FROM aud_base WHERE estado=1";
$res_cont = mysql_query($sql_cont);
$row_cont = mysql_fetch_array($res_cont);
?>
<a href="?cmp=lista_base">
	<?php if($_SESSION["cmp"]=='lista_base'){$boton = 'primary';}else{$boton = 'info';} ?>
	<button  class="btn btn-<?php echo $boton; ?>" type="button">
	 <font size=1px>Auditorias <span class="badge"><font color=red><?php echo $row_cont["cantidad"]; ?></font></span></font>
	</button>
</a>


&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
$sql_cont = "SELECT COUNT(*) AS cantidad FROM aud_area";
$res_cont = mysql_query($sql_cont);
$row_cont = mysql_fetch_array($res_cont);
?>
<a href="?cmp=lista_area">
	<?php if($_SESSION["cmp"]=='lista_area'){$boton = 'primary';}else{$boton = 'info';} ?>
	<button  class="btn btn-<?php echo $boton; ?>" type="button">
	 <font size=1px>Areas para auditorias <span class="badge"><font color=red><?php echo $row_cont["cantidad"]; ?></font></span></font>
	</button>
</a>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
$sql_cont = "SELECT COUNT(*) AS cantidad FROM aud_categoria";
$res_cont = mysql_query($sql_cont);
$row_cont = mysql_fetch_array($res_cont);
?>
<a href="?cmp=lista_categoria">
	<?php if($_SESSION["cmp"]=='lista_categoria'){$boton = 'primary';}else{$boton = 'info';} ?>
	<button  class="btn btn-<?php echo $boton; ?>" type="button">
	 <font size=1px>Categorias para preguntas <span class="badge"><font color=red><?php echo $row_cont["cantidad"]; ?></font></span></font>
	</button>
</a>

<br><br>