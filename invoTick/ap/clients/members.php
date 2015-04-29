<?php
	require_once("../../../cgi_bin/phpFun.php");
	require_once("../languages/language.php");				
	jCnn();
?><?php

//-------------------------------------------------
// Abre el Recordset
//-------------------------------------------------
$strsql='SELECT idMember, surName, name, class, category FROM [members] WHERE idCompany='.$_SESSION['idCompany'].' ORDER BY surName';
?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>

<base target="_self">
<title>Members by</title>
<script type="text/javascript">
jss.Init=function(){
	var strsql='<?php echo base64_encode($strsql)?>';
	var datos=loadAjax('../../../cgi_bin/phpFun.php','jsgetArray=base64&strsql='+strsql).evalDecode();
	if (typeof datos!='object'){document.getElementById('listMembers').innerHTML=datos; return;};
	var t=new jss.Grid({
	    renderTo: 'listMembers',
		totals: false,	
		fireEvent: 'getVal',
		values: datos['values'],
		columns: datos['columns']
	})
	t.table.rows[0].cells[0].style.width='2%';
}

function getVal(q){
	var id=q.cells[0].innerText || q.cells[0].textContent;
	window.parent.frames.ifMember.location.search = '?id='+id;	
}

function newMember(){
	window.parent.frames.ifMember.location.search = '?id=0';
	window.location.search='';	
}

</script>
</head>

<body class="jss-Body">

<table class="jss-Table" style="width: 100%">
	<tr class="jss-Bar">
		<td>&nbsp;</td>
		<td style="text-align: right"><?php echo _INSERT._._NEW?>:
		<img alt="" src="../../images/insert20.gif" style="width: 20px; height: 20px" onclick="javascript: newMember();" class="jss-Cursor"></td>
	</tr>
	<tr>
		<td colspan="2" id="listMembers"></td>
	</tr>
</table>

</body>

</html>
