<?php
	session_start();
	require("../languages/language.php");
?>
<html>
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<title>Customers</title>
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<base target="_self">
<script type="text/javascript">
    jss.Init=function(){}
</script>
</head>
<body class="jss-NoMargins">


<table class="jss-Table" style="height: 100%; width: 100%;">
	<tr style="height: 1%">
		<td >
		<table id="pEmpresa" class="jss-Panel" style="width: 100%; height: 236pt;">
			<tr>
				<td class="jss-Caption">.</td>
				<td class="jss-Up">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" title="iFrame:{id:'ifCompany',url:'company.php',scrolling:'no'}">
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr style="height: 1%">
		<td >
		<table id="pPersona" class="jss-Panel" style="width: 100%; height:218pt">
			<tr>
				<td class="jss-Caption">.</td>
				<td class="jss-Up">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" title="iFrame:{id:'ifMember',url:'member.php',scrolling:'no'}">
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td style="vertical-align: top" >
		<table id="pTabs" class="jss-Tab" style="width: 100%; height: 100%; margin-bottom: 0px;">
			<tr >
				<td class="jss-TabInactive" title="iFrame:{id:'ifTab',url:'members.php', scroll:'yes'}">
				<?php echo _MEMBERS?></td>
				<td class="jss-TabInactive" title="iFrame:{id:'ifTab',url:'sales.php', scroll:'yes'}">
				<?php echo _INVOICE?></td>
				<td class="jss-TabInactive" title="iFrame:{id:'ifTab',url:'fees.php', scroll:'yes'}">
				<?php echo _FEES?></td>
				<td style="width: 40%"></td>
				
			</tr>
			<tr>
				<td colspan="3"></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</body>
</html>
