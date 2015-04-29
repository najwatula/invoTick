<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");
	jCnn();
//-------------------------------------------------
// Parametros Company
//-------------------------------------------------
$buscaPor="company";
$strsql="SELECT coalesce(idMember,'') || '/' || coalesce(idCompany,'') as id, cif, company, type, city, surName, name, class FROM [clients]";
$table="clients";
$keyMaster="idMember";

//-------------------------------------------------
//recoge el puntero
//-------------------------------------------------
if (isset($_REQUEST["id"])){
    $id=preg_split('/\//',$_REQUEST["id"]);
    if($id[0]>''){
	    $_SESSION['idMember']=$id[0];
	    $_SESSION['idCompany']=jGet('select idCompany from members where idMember='.$_SESSION['idMember'].' LIMIT 1');
	}
	else{
		$_SESSION['idCompany']=$id[1];
		$_SESSION['idMember']=0;
	}
    header('Location: main.php');
    exit;
}
require('../tables/tableSearch.php');
?>