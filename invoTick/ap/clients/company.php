<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");
	jCnn();
//-------------------------------------------------
// Parametros Company
//-------------------------------------------------
$table="companies";
$keyMaster="idCompany";
$buscaPor='company';

//-------------------------------------------------
//el registro quiere ser eliminado
//-------------------------------------------------
if (isset($_REQUEST["eliminar"])){
	// delete images
	$strsql="select idMember from members where idCompany=".$_SESSION['idCompany'];
	$results = $GLOBALS['db']->query($strsql);
	if($results){
		while ($gv = $results->fetch()) {
			$strsql="delete from images where tableName='members' and id=".$gv[0];
			$results = $GLOBALS['db']->exec($strsql);
		}
	}
	// delete members
	$strsql='delete from members where idCompany='.$_SESSION['idCompany'];
	$results = $GLOBALS['db']->exec($strsql);
	// delete company
	$strsql='delete from companies where idCompany='.$_SESSION['idCompany'];
	$results = $GLOBALS['db']->exec($strsql);
	
	header('Location: search.php'); exit;
}
//-------------------------------------------------
//recoge el puntero
//-------------------------------------------------
if (isset($_REQUEST["id"])){$_SESSION['idCompany']=$_REQUEST["id"];}
require('../tables/tableImg.php');
?>