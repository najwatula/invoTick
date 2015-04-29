<?php 
	require("../../../cgi_bin/phpFun.php");
	$_SESSION["language"]='es';
	require("../languages/language.php");	
	jCnn();

//-------------------------------------------------
//recoge el puntero
//-------------------------------------------------
if(isset($_REQUEST["table"])){
    $_SESSION['table']=$_REQUEST['table'];
    $_SESSION['search']=$_REQUEST['search'];
    $_SESSION['keyMaster']=$_REQUEST['keyMaster'];    
}

$buscaPor=$_SESSION['search'];
$strsql='select * from ['.$_SESSION['table'].']';
$table=$_SESSION["table"];
$keyMaster=$_SESSION['keyMaster'];

if(isset($_REQUEST['id'])){
	$_SESSION[$keyMaster]=$_REQUEST["id"];
	$goTo="tableForm.php";
}
else{
	$goTo='tableSearch.php';
}

require("../tables/".$goTo);
?>
