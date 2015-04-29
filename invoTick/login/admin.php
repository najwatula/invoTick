<?php
	require("../../cgi_bin/phpFun.php");
	require("../ap/languages/language.php");
	if ( $_SESSION['domain']!='admin'){header('Location: destroy.php');}
	
    if(isset($_REQUEST['info'])){phpinfo(); exit;}
    
	$dbDomains=dirList('../db/domains/*.db3'); asort($dbDomains);
	$dbTemplates=dirList('../db/templates/*.db3');
    $resultsShow='';
//-------------------------------------------------
// Main
//-------------------------------------------------
if (isset($_POST['dbName']) && isset($_POST['dbTemplate']))
{
	if($_POST['dbName']>'0' && $_POST['dbTemplate']>'0' && strtolower($_POST['dbName'])!='admin'){
	    if(! file_exists('../db/domains/'.$_POST['dbName'].'.db3')){
		    copy('../db/templates/'.$_POST['dbTemplate'],'../db/domains/'.$_POST['dbName'].'.db3');
 		    header('Location: login.php?user='.$_POST['dbName'].'\admin&pass=admin');
 		    exit;
 		}
 	}
}

if (isset($_POST['Update']))
{
}

if (isset($_POST['Go'])  && $_POST['sqlQuery']){
    $strsql=$_POST['sqlQuery'];
    
    foreach($dbDomains as $domain){
        $resultsShow= $resultsShow.$domain.':<br>';
        descnn();
        $_SESSION['domain']=strLeft($domain, '.');
        jCnn();
	    $results= $GLOBALS['db']->exec($strsql);
	    if (!$results){$err=$GLOBALS['db']->errorInfo(); $resultsShow=$resultsShow.$err[2].'<hr>';}  
	    else{$resultsShow=$resultsShow.$results.'<hr>';}
	}
	 $_SESSION['domain']='admin';
}

?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<title>Admin Panel</title>
<base target="_top">
<link href="../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<script type="text/javascript">
jss.Init=function(){
	var tList=new jss.Grid({
		renderTo: 'listDataBases',
		//fireEvent: 'getVal',
		values: <?php echo '[["'.implode('"],["',$dbDomains).'"]]'?>,
		columns: ['dataBases sqlite: (<?php echo count($dbDomains)?>)']
	})
}
</script>
</head>

<body class="Jss-Body">

<table style="width: 100%; height: 100%">
	<tr>
		<td style="text-align: center; vertical-align: middle">
		<table id="contenedor" class="jss-TableFrame" style="width: 90%;">
			<tr style="background-color: #FFFFFF">
				<td colspan="3" style="text-align: center; height: 1%;">
				<a href="login.php">
				<img alt="" src="../images/invoTick.png" style="border-width: 0px; width: 525px; height: 170px;"></a></td>
			</tr>
			<tr>
				<td class="jss-Caption" colspan="3">&nbsp; Administrator panel</td>
			</tr>
			<tr>
				<td>
				<form class="jss-NoMargins" method="POST" name="newDataBase">
					<table id="table2" class="jss-TableBorder" style="width: 100%;">
						<tr>
							<td class="jss-Bar">&nbsp;Create new dataBase</td>
						</tr>
						<tr>
							<td>From Template:</td>
						</tr>
						<tr>
							<td style="text-align: right;">
							<select class="jss-FieldAuto" name="dbTemplate" size="1">
							<?php echo putOptions($dbTemplates,'');?></select></td>
						</tr>
						<tr>
							<td>New Domain name:</td>
						</tr>
						<tr>
							<td style="text-align: right">
							<input class="jss-FieldAuto" maxlength="15" name="dbName" size="25"></td>
						</tr>
						<tr>
							<td style="text-align: center; ">
							<input class="jss-Boton" name="Create" type="submit" value="Create"></td>
						</tr>
					</table>
				</form>
				</td>
				<td colspan="2" rowspan="2" style="vertical-align: top">
				<form class="jss-NoMargins" method="POST" name="executeQuery">
					<table id="table3" class="jss-TableBorder" style="width: 100%; height: 100%;">
						<tr>
							<td class="jss-Bar">&nbsp;Execute query (all dataBases)</td>
						</tr>
						<tr>
							<td>
							<textarea class="jss-FieldAuto" cols="50" name="sqlQuery" rows="10"></textarea></td>
						</tr>
						<tr>
							<td style="text-align: center">
							<input class="jss-Boton" name="Go" type="submit" value="Go"></td>
						</tr>
						<tr>
							<td class="jss-Bar">
							&nbsp;Results:&nbsp;</td>
						</tr>
						<tr>
							<td>
							<div id="results" style="height: 80pt; overflow: auto">
							<?php echo $resultsShow?></div></td>
						</tr>
					</table>
				</form>
				</td>
			</tr>
			<tr>
				<td>
				<form class="jss-NoMargins" method="POST" name="update">
					<table id="table4" class="jss-TableBorder" style="width: 100%;">
						<tr>
							<td style="vertical-align: top;">
							<div id="listDataBases" style="overflow: auto; height: 120pt;">
							</div>
							</td>
						</tr>
						<tr>
							<td style="text-align: center">
							<input class="jss-Boton" name="Update" type="submit" value="Update"></td>
						</tr>
					</table>
				</form>
				</td>
			</tr>
			<tr>
				<td style="font-size: xx-small; width: 33%; text-align: center;">
				<a href="admin.php?info=1" style="text-align: right">Info</a> </td>
				<td style="font-size: xx-small; width: 33%; text-align: center;">
				<?php echo $_SERVER["SERVER_NAME"].' / '.$_SERVER["REMOTE_ADDR"]?>
				</td>
				<td style="font-size: xx-small; width: 33%; text-align: center;">
				&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
</table>

</body>

</html>
