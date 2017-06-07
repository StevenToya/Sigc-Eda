<?php

if($PERMISOS_GC["sop_pag"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}

if($_GET["guar"])
{
	$periodo = $_POST["ano"].'-'.$_POST["mes"];
	
	$sql_bus = "SELECT id FROM soporte_pago WHERE ano_mes ='".$periodo."' LIMIT 1";
	$res_bus = mysql_query($sql_bus);
	$row_bus = mysql_fetch_array($res_bus);
	
	if(!$row_bus["id"])
	{
		$sql_ing = "INSERT INTO `soporte_pago` (`ano_mes`) VALUES ('".$periodo."');";
		mysql_query($sql_ing);
		
		 echo "<script>alert('Periodo creado correctamente')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?'>";
		 die();	
	}
	else{
		 echo "<script>alert('ERROR, Este perio ya fue creado')</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?'>";
		 die();	
	}
	
	

}

?>

<h2> SOPORTE PARA PAGOS </h2>


<form action="?guar=1"  method="post" name="form" id="form"  enctype="multipart/form-data">
	<table>
		<tr>
			<td>
				<select name='ano' class="form-control" required >
					<option value=""> </option>
					<option>2016</option>
					<option>2017</option>
					<option>2018</option>
					<option>2019</option>					
				</select> 
			</td>
			<td>
				<select name='mes' class="form-control" required >
					<option value=""> </option>
					<option value='01'>Enero</option>	
					<option value='02'>Febrero</option>	
					<option value='03'>Marzo</option>	
					<option value='04'>Abril</option>	
					<option value='05'>Mayo</option>	
					<option value='06'>Junio</option>	
					<option value='07'>Julio</option>	
					<option value='08'>Agosto</option>	
					<option value='09'>Septiembre</option>	
					<option value='10'>Octubre</option>					
					<option value='11'>Noviembre</option>						
					<option value='12'>Diciembre</option>				
				</select>  
			</td>
			<td><button class="btn btn-primary" name="guardar"  type="submit">Ingresar periodo</button> </td>
		</tr>
	</table>
</form>



<div class="panel panel-default"  style="width:99%">
	<div class="panel-heading" align=left>
		<b> Personal para gestionar </b>
	</div>
	<div class="panel-body" >
		<div class="table-responsive" >
		
			<table class="table table-striped table-bordered table-hover" >
				<thead>
					<tr>
						<th>Periodo</th>
						<th>Revisoria Fiscal</th>
						<th>Pago Parafiscales</th>
						<th>Pago Nomina</th>
						<th>Acta</th>
						<th>Factura</th>						
						<th>Editar</th>					
					</tr>
				</thead>
				<tbody>
				<?php
				$vec_tem = $vec_item; 
				$sql = "SELECT * FROM `soporte_pago` ORDER BY id DESC ";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res))
				{						
								
				?>
					<tr class="odd gradeX">
						<td><b> <?php echo $row["ano_mes"] ?> </b></td>
						<td> 
							<?php if($row["archivo_1"]){ ?>
								<a href="<?php echo $row["archivo_1"]; ?>" target="_blank"> Descargar </a>
							<?php }else{ ?>
								<font color=red>No ingresado</font>
							<?php } ?>
						</td>
						<td> 
							<?php if($row["archivo_2"]){ ?>
								<a href="<?php echo $row["archivo_2"]; ?>" target="_blank"> Descargar </a>
							<?php }else{ ?>
								<font color=red>No ingresado</font>
							<?php } ?>
						</td>
						<td> 
							<?php if($row["archivo_3"]){ ?>
								<a href="<?php echo $row["archivo_3"]; ?>" target="_blank"> Descargar </a>
							<?php }else{ ?>
								<font color=red>No ingresado</font>
							<?php } ?>
						</td>
						<td> 
							<?php if($row["archivo_4"]){ ?>
								<a href="<?php echo $row["archivo_4"]; ?>" target="_blank"> Descargar </a>
							<?php }else{ ?>
								<font color=red>No ingresado</font>
							<?php } ?>
						</td>
						<td> 
							<?php if($row["archivo_5"]){ ?>
								<a href="<?php echo $row["archivo_5"]; ?>" target="_blank"> Descargar </a>
							<?php }else{ ?>
								<font color=red>No ingresado</font>
							<?php } ?>
						</td>
						
						<td><center><a href="?cmp=editar&id=<?php echo $row["id"]; ?>"><i class="fa fa-pencil-square-o fa-2x"></i></a><b></center></td>						
				   </tr>
				<?php
				}
				?>				   
				</tbody>
			</table>
		</div>		
	</div>
</div>



