<?php
	require("mainPassword.php");
	require("../ap/languages/language.php");
//-------------------------------------------------
// Main
//-------------------------------------------------
if (isset($_POST['oldPassword']) && isset($_POST['newPassword']) && isset($_POST['repeatPassword'])){

	if (($_POST['oldPassword']==$mainPassword) && ($_POST["newPassword"] == $_POST["repeatPassword"])){
        $fp = fopen("mainPassword.php", "w");
        fputs($fp, "<?"."php $"."mainPassword='".$_POST["newPassword"]."'; "."?".">");
        fclose($fp);
        header('Location: login.php');exit;

	}
}


?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">

<title>Control de Acceso</title>
<base target="_top">
<link href="../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<script type="text/javascript">
jss.Init=function(){
    document.getElementById('language').value="<?php echo $_SESSION['language'];?>";
}
</script>
</head>

<body class="Jss-Body">

<table style="width: 100%; height: 100%">
	<tr>
		<td style="text-align: center; vertical-align: middle">
		<table id="contenedor" class="jss-TableFrame" style="width: 50%; height: 30%;">
			<tr style="background-color: #FFFFFF">
				<td colspan="2" style="text-align: center; height: 1%;">
				<a href="../../index.php">
				<img alt="" src="../images/tikaltok.png" style="border-width: 0px; width: 525px; height: 170px;"></a></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" style="height: 30%;">
				<form class="jss-NoMargins" method="POST" name="Validar">
				<div style="text-align: center">
				
					<table id="table2" class="jss-TableBorder" style="margin: auto; width: 350px; height: 150px;">
						<tr>
							<td class="jss-Caption" colspan="3">&nbsp;Change admin 
							password</td>
						</tr>
						<tr>
							<td style="width: 30%; text-align: right;">Old 
							password:</td>
							<td style="width: 50%">
							<input class="jss-FieldAuto" maxlength="50" name="oldPassword" size="25"></td>
							<td rowspan="3" style="width: 20%; text-align: center;">
							&nbsp;</td>
						</tr>
						<tr>
							<td style="text-align: right; width: 30%;">New password:</td>
							<td>
							<input class="jss-FieldAuto" maxlength="50" name="newPassword" size="25"></td>
						</tr>
						<tr>
							<td style="text-align: right">Repeat:</td>
							<td>
							<input class="jss-FieldAuto" maxlength="12" name="repeatPassword" size="25" type="password"></td>
						</tr>
						<tr>
							<td colspan="3" style="text-align: center">
							<input class="jss-Boton" name="Change" type="submit" value="Change"></td>
						</tr>
					</table>
				</div>
				</form>
				</td>
			</tr>
			<tr>
				<td style="font-size: xx-small; width: 50%;">
				<?php echo $_SERVER["SERVER_NAME"].' / '.$_SERVER["REMOTE_ADDR"]?>
				</td>
				<td style="font-size: xx-small; text-align: right; width: 50%;">
				&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
</table>

</body>

</html>

