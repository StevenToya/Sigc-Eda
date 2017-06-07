<?php
if($PERMISOS_GC["aud_conf"]!='Si')
{ 
	echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=?cmp=index&md=inicio'>";
	die();
}



?>

<h2> VISTA PREVIA DE LA  AUDITORIA </h2>
<form  method="post" action="?" enctype="multipart/form-data"> 
	<table width=100%>
		<tr>			
			<td align=right>
				<a href="?cmp=lista_base"> <i class="fa fa-reply fa-2x"></i> Volver al listado de auditorias </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
			</td>
		</tr>
	</table>
</form>
<br>
	
<?php

$sql_cate = "SELECT aud_categoria.id, aud_categoria.nombre FROM aud_categoria 
LEFT JOIN aud_item ON aud_categoria.id = aud_item.id_categoria
WHERE  aud_item.id_base='".$_GET["id_base"]."' GROUP BY aud_categoria.id 
ORDER BY orden ASC ";
$res_cate = mysql_query($sql_cate);
while($row_cate = mysql_fetch_array($res_cate))
{
?>

	<div class="col-md-100 col-sm-100">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<font size="4px"><b><?php echo $row_cate["nombre"] ?></b></font>
			</div>
			<div class="panel-body" align=center>
				<table width=100%>
						<?php 
							$sql_pre = "SELECT * FROM aud_item WHERE 
								aud_item.estado=1 AND 
								aud_item.id_base='".$_GET["id_base"]."' AND
								aud_item.id_categoria ='".$row_cate["id"]."'
								ORDER BY tipo, pregunta";
							$res_pre = mysql_query($sql_pre);
							while($row_pre = mysql_fetch_array($res_pre))
							{
								?>
								
								<?php if($row_pre["tipo"]==1){ ?>						
										<tr>
												<td width=70%>											
													<h4><?php echo $row_pre["pregunta"]; ?></h3>									
												</td>
												<td width=30% align=right>								
													
														<div class="btn-group" data-toggle="buttons">
														  <label class="btn btn-primary active">
															<input type="radio" value="1" name="pre_<?php echo $row_pre["id"]; ?>" id="pre_<?php echo $row_pre["id"]; ?>" autocomplete="off" checked> Omitir
														  </label>
														  <label class="btn btn-primary">
															<input type="radio" value="s" name="pre_<?php echo $row_pre["id"]; ?>" id="pre_<?php echo $row_pre["id"]; ?>" autocomplete="off"> Si cumple
														  </label>
														  <label class="btn btn-primary">
															<input type="radio" value="n" name="pre_<?php echo $row_pre["id"]; ?>" id="pre_<?php echo $row_pre["id"]; ?>" autocomplete="off"> No cumple
														  </label>
														</div>								
												
												</td>								
											</tr>
								<?php } ?>
								
								<?php if($row_pre["tipo"]==2){ ?>						
										<tr>
												<td width=100% colspan=2>												
													<h4><?php echo $row_pre["pregunta"]; ?></h3>	
													<textarea style="width:100%" rows="5" name="pre_<?php echo $row_pre["id"]; ?>"></textarea>
												</td>
																			
											</tr>
								<?php } ?>
								
								<?php if($row_pre["tipo"]==3){ ?>						
										<tr>
												<td width=70%>											
													<h4><?php echo $row_pre["pregunta"]; ?></h3>									
												</td>
												<td width=30% align=right>													
														<input type="file" name="pre_<?php echo $row_pre["id"]; ?>" class="form-control">												
												</td>								
											</tr>
								<?php } ?>
						<?php
							}
						?>
				</table>			
			</div>
		</div>
	</div>	

<?php
}
?>

<center><input class="btn btn-primary" type="submit" value="Enviar auditoria" name="guardar" /></center>