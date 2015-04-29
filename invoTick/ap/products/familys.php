<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");
	jCnn();

//-------------------------------------------------
// Parametros Familys
//-------------------------------------------------
$buscaPor="family";
$strsql="select * from [familys] ";
$table="familys";
$keyMaster="idFamily";
//-------------------------------------------------
//recoge el puntero
//-------------------------------------------------
if (isset($_REQUEST["id"])){
	$_SESSION[$keyMaster]=$_REQUEST["id"];
    $script='var objColor=document.getElementsByName("color")[0]; '.
    'objColor.className ="jss-ColorPicker"; jss.ParsecColorPicker(); '.
    'objColor.style.witdth="100%"; objColor.style.backgroundColor=objColor.value; ';
	
	$goTo="tableForm.php";
}
else{
	$script='for(var i=1; i<tList.table.rows.length; i++){'.
		'var color=tList.table.rows[i].cells[2].innerText || tList.table.rows[i].cells[2].textContent;'.
		'try{tList.table.rows[i].cells[2].style.backgroundColor=color;} catch(err){}}';

	$goTo="tableSearch.php";
}

require("../tables/".$goTo);
?>

