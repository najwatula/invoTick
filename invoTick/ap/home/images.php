<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");		
	jCnn();
?><?php

//-------------------------------------------------
//Logos
//-------------------------------------------------

if (isset($_REQUEST['logo1'])) {$logo='1';}
if (isset($_REQUEST['logo2'])) {$logo='2';}
if (isset($_REQUEST['logo3'])) {$logo='3';}

if (isset($_FILES["file"])) {
	$imgFile=file_get_contents($_FILES["file"]["tmp_name"]);
    
    $exist=jGet("select id from images where [tableName]='home' and [id]=$logo");
    if($exist){
	    $strsql="update [images] set [image]=:img where id=$exist";
	    $results = $GLOBALS['db']->prepare($strsql);
	}
	else{
	    $strsql='insert into [images] ([tableName], [keyName], [image], [id]) values("home", "images", :img, '.$logo.');';
	    $results = $GLOBALS['db']->prepare($strsql);
	}

	$results->bindParam(':img', $imgFile, PDO::PARAM_LOB);
	$results->execute();
}
$strImg1='../../../cgi_bin/phpFun.php?getImg=base64&strsql='.base64_encode('SELECT [image] from [images] WHERE [tableName]=\'home\' and [id] = 1');
$strImg2='../../../cgi_bin/phpFun.php?getImg=base64&strsql='.base64_encode('SELECT [image] from [images] WHERE [tableName]=\'home\' and [id] = 2');
$strImg3='../../../cgi_bin/phpFun.php?getImg=base64&strsql='.base64_encode('SELECT [image] from [images] WHERE [tableName]=\'home\' and [id] = 3');

?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<title>Home</title>
<base target="_self">
<script type="text/javascript">
</script>

</head>

<body class="Jss-Body">

<form enctype="multipart/form-data" method="post" name="fLogo" style="margin: 0px">
	<table class="jss-Table" style="width:99%">
		<tr class="jss-Caption">
			<td colspan="2"><?php echo _SELECT._._IMAGE._._TO._._SEND;?>:</td>
			<td colspan="2">
			<input class="jss-FieldAuto" name="file" size="50" type="file"></td>
		</tr>
		<tr class="jss-Bar">
			<td style="width: 49%">&nbsp;<?php echo _COMPANY._._LOGO;?></td>
			<td style="width: 1%">
			<input class="jss-Boton" name="logo1" type="submit" value="<?php echo _SEND;?>"></td>
			<td style="width: 49%">&nbsp;<?php echo _EVENT._._LOGO;?></td>
			<td style="width: 1%">
			<input class="jss-Boton" name="logo2" type="submit" value="<?php echo _SEND;?>"></td>
		</tr>
		<tr>
			<td style="text-align: center;" colspan="2">
			<img id="imgLogo1"  src="<?php echo $strImg1 ?>" style="width: 60%;"></td>
			<td style="text-align: center;" colspan="2">
			<img id="imgLogo2"  src="<?php echo $strImg2 ?>" style="width: 60%;"></td>
		</tr>
		<tr class="jss-Bar">
			<td style="text-align: center; width: 99%;" colspan="3"><?php echo _HEADER._._IMAGE;?></td>
			<td style="width: 1%;">
			<input class="jss-Boton" name="logo3" type="submit" value="<?php echo _SEND;?>"></td>
		</tr>
		<tr>
			<td style="text-align: center;" colspan="4">
			<img id="imgLogo3" src="<?php echo $strImg3 ?>" style="width: 60%;"></td>
		</tr>
	</table>
</form>

</body>

</html>
