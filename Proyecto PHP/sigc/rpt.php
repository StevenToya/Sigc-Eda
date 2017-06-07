<?php
session_start();
include("cnx.php");
$sql = "SELECT id, estado FROM usuario 
WHERE  usuario  = '".$_POST["nom_user"]."' AND clave = '".md5($_POST["password"])."' AND id_instancia  = '".$_POST["instancia"]."' AND estado=1 LIMIT 1 ";  
$res = mysql_query($sql);
$row = mysql_fetch_array($res);

if($row["id"])
{
	
	$_SESSION["md"] = "inicio";
	$_SESSION["cmp"] = "index";
	$_SESSION["nst"] = $_POST["instancia"];
	$_SESSION["user_id"] = $row["id"];
	header("Location: index.php");
	

}else
{
	header("Location: index.php?error=1");
}



?>