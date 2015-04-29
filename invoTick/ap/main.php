<?php
	$strImg='../../cgi_bin/phpFun.php?getImg=base64&strsql='.base64_encode("select image from images where tableName='home' and id=3");
?>

<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<title>Main</title>
<link href="../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<base target="_self">
</head>

<body class="Jss-Body">

<table id="table2" style="width: 100%; height: 100%">
	<tr>
		<td style="text-align: center">
		<table class="jss-TableFrame" style="width: 60%; height: 60%;">
			<tr>
				<td style="text-align: center">
					<img alt="" id="logo"  src="<?php echo $strImg ?>" style="  width: 80%;">
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td style="vertical-align: bottom">
		<table id="table4" style="width: 100%" class="jss-Table">
			<tr>
				<td style="text-align: center">billing system and issuance of tickets </td>
			</tr>
			<tr>
				<td style="text-align: center">(c) invoTick 2012</td>
			</tr>
		</table>
		</td>
	</tr>
</table>

</body>

</html>
