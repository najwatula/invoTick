<?php
	if(isset($_REQUEST["language"])){$_SESSION["language"]=$_REQUEST["language"];}
	if(!isset($_SESSION["language"])){$_SESSION["language"]="en";}
	include_once($_SESSION["language"].'.inc');
	setlocale(LC_ALL, 'es_ES');
?>
