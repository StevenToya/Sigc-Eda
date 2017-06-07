
<a href="?cmp=periodo">
	<?php if($_SESSION["cmp"]=='periodo'){$boton = 'primary';}else{$boton = 'info';} ?>
	<button  class="btn btn-<?php echo $boton; ?>" type="button">
	 <font size=1px>Periodo </font>
	</button>
</a>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
$sql_cont = "SELECT COUNT(*) AS cantidad FROM sto_plataforma";
$res_cont = mysql_query($sql_cont);
$row_cont = mysql_fetch_array($res_cont);
?>
<a href="?cmp=plataforma">
	<?php if($_SESSION["cmp"]=='plataforma'){$boton = 'primary';}else{$boton = 'info';} ?>
	<button  class="btn btn-<?php echo $boton; ?>" type="button">
	 <font size=1px>Plataforma <span class="badge"><font color=red><?php echo $row_cont["cantidad"]; ?></font></span></font>
	</button>
</a>


&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
$sql_cont = "SELECT COUNT(*) AS cantidad FROM sto_sap";
$res_cont = mysql_query($sql_cont);
$row_cont = mysql_fetch_array($res_cont);
?>
<a href="?cmp=sap">
	<?php if($_SESSION["cmp"]=='sap'){$boton = 'primary';}else{$boton = 'info';} ?>
	<button  class="btn btn-<?php echo $boton; ?>" type="button">
	 <font size=1px>Cuenta SAP <span class="badge"><font color=red><?php echo $row_cont["cantidad"]; ?></font></span></font>
	</button>
</a>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
$sql_cont = "SELECT COUNT(*) AS cantidad FROM sto_car";
$res_cont = mysql_query($sql_cont);
$row_cont = mysql_fetch_array($res_cont);
?>
<a href="?cmp=car">
	<?php if($_SESSION["cmp"]=='car'){$boton = 'primary';}else{$boton = 'info';} ?>
	<button  class="btn btn-<?php echo $boton; ?>" type="button">
	 <font size=1px>CAR <span class="badge"><font color=red><?php echo $row_cont["cantidad"]; ?></font></span></font>
	</button>
</a>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
$sql_cont = "SELECT COUNT(*) AS cantidad FROM sto_rubro";
$res_cont = mysql_query($sql_cont);
$row_cont = mysql_fetch_array($res_cont);
?>
<a href="?cmp=rubro">
	<?php if($_SESSION["cmp"]=='rubro'){$boton = 'primary';}else{$boton = 'info';} ?>
	<button  class="btn btn-<?php echo $boton; ?>" type="button">
	 <font size=1px>Rubro <span class="badge"><font color=red><?php echo $row_cont["cantidad"]; ?></font></span></font>
	</button>
</a>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
$sql_cont = "SELECT COUNT(*) AS cantidad FROM sto_item WHERE tipo=1 ";
$res_cont = mysql_query($sql_cont);
$row_cont = mysql_fetch_array($res_cont);
?>
<a href="?cmp=rol">
	<?php if($_SESSION["cmp"]=='rol'){$boton = 'primary';}else{$boton = 'info';} ?>
	<button  class="btn btn-<?php echo $boton; ?>" type="button">
	 <font size=1px>Roles <span class="badge"><font  color=red><?php echo $row_cont["cantidad"]; ?></font></span></font>
	</button>
</a>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
$sql_cont = "SELECT COUNT(*) AS cantidad FROM sto_item WHERE tipo=2 ";
$res_cont = mysql_query($sql_cont);
$row_cont = mysql_fetch_array($res_cont);
?>
<a href="?cmp=item">
	<?php if($_SESSION["cmp"]=='item'){$boton = 'primary';}else{$boton = 'info';} ?>
	<button  class="btn btn-<?php echo $boton; ?>" type="button">
	 <font size=1px>Item <span class="badge"><font  color=red><?php echo $row_cont["cantidad"]; ?></font></span></font>
	</button>
</a>


&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
$sql_cont = "SELECT COUNT(*) AS cantidad FROM sto_persona WHERE estado = 1";
$res_cont = mysql_query($sql_cont);
$row_cont = mysql_fetch_array($res_cont);
?>
<a href="?cmp=persona">
	<?php if($_SESSION["cmp"]=='persona'){$boton = 'primary';}else{$boton = 'info';} ?>
	<button  class="btn btn-<?php echo $boton; ?>" type="button">
	 <font size=1px>Tecnicos <span class="badge"><font  color=red><?php echo $row_cont["cantidad"]; ?></font></span></font>
	</button>
</a>
<br><br>