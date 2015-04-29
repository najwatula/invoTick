<?php
    //check password
	require("mainPassword.php");
    if($mainPassword=='admin'){header('Location: changePassword.php');exit;}

    // check write permission for db directory
    
	session_start();
	if (isset($_SESSION['domain'])){header('Location: destroy.php');exit;}

    $_SESSION['domain']='.';
	require("../../cgi_bin/phpFun.php");
	unset($_SESSION['domain']);
	$_SESSION['language'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	require("../ap/languages/language.php");
	//$langFiles=dirList('../ap/languages/*.inc');
//-------------------------------------------------
// Main
//-------------------------------------------------
if (isset($_POST['user']) && isset($_POST['pass'])){
	$_SESSION['home']=realpath('../db/domains/'); 
    $_SESSION['domain']	= strLeft($_POST['user'],'\\');

	if ($_POST['user']=='admin' && $_POST["pass"]==$mainPassword){
        $_SESSION['domain']='admin';
	  	header('Location: admin.php');exit;
	}

    if(jCnn()){login();}
}

function login(){
    $strsql="insert into [log] ([user], [ip], [urlFrom]) values('".$_POST["user"]."', '".$_SERVER["REMOTE_ADDR"]."', '".$_SERVER["SERVER_NAME"]."');";
	$results = $GLOBALS['db']->exec($strsql);

	
	$strsql = "select idAgent, level from [agents] where user='".strRight($_POST["user"],'\\')."' and password='".$_POST["pass"]."';";
	$results = $GLOBALS['db']->query($strsql);
	
	if ($results){
	    $results=$results->fetch();
	    $_SESSION['language']=$_POST['language'];
		$_SESSION['idAgent']=$results['idAgent'];
		$_SESSION['level']=(string)$results['level'];
	  	if($_SESSION['idAgent']>'0'){header('Location: ../ap/index.php'); exit;}
	}else{
	    //print_r($GLOBALS['db']->errorInfo());
		session_destroy();
		session_unset();		
	  	header('Location: ../../cgi_bin/denied.php');
	  	exit;
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
				<img alt="" src="../images/invoTick.png" style="border-width: 0px; width: 525px; height: 170px;"></a></td>
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
							<td class="jss-Caption" colspan="3">&nbsp;Login</td>
						</tr>
						<tr>
							<td style="width: 30%; text-align: right;"><?php echo _LANGUAGE?>
							:</td>
							<td style="width: 50%">
							<select class="jss-FieldAuto" id="language" name="language" size="1">
							<option value="ca">Catalan</option>
							<option value="de">Deutchs</option>
							<option value="en">English</option>
							<option value="es">Español</option>
							<option value="fr">Francaise</option>
							<option value="pt">Portugues</option>
							<option value="ru">Russian</option>
							</select></td>
							<td rowspan="3" style="width: 20%; text-align: center;">
							<img src="../images/salir.gif" style="border-style: solid; border-width: 0px; width: 36px; height: 48px"></td>
						</tr>
						<tr>
							<td style="text-align: right"><?php echo _DOMAIN?>\<?php echo _USER?>:</td>
							<td>
							<input class="jss-FieldAuto" maxlength="50" name="user" size="25"></td>
						</tr>
						<tr>
							<td style="text-align: right"><?php echo _PASSWORD?>
							:</td>
							<td>
							<input class="jss-FieldAuto" maxlength="12" name="pass" size="25" type="password"></td>
						</tr>
						<tr>
							<td colspan="3" style="text-align: center">
							<input class="jss-Boton" name="Login" type="submit" value="Login"></td>
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

