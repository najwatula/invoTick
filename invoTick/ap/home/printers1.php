<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<title>Network Printers</title>
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css" >
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<base target="_self">
<script type="text/javascript">
jss.Init=function(){}

function goFun(qFun){
    document.getElementById('imgWait').style.display='';
    setTimeout(qFun,10);
}
function checkStatus(){
    var tList=window.frames.iprinters.tList;
    for(n=1;n<tList.table.rows.length;n++){
        var printer=tList.table.rows[n].cells[2].innerHTML;
        var results=loadAjax('../../../cgi_bin/netPrint.php?search='+printer);
        tList.table.rows[n].cells[3].innerHTML=results.substring(0,30);
    }
    document.getElementById('imgWait').style.display='none';
}

function searchPrinters(q){
	if(q==1){var netWork=document.getElementById('myIp').value;}
	else{var netWork=document.getElementById('myNetwork').value;}
	
    var results=loadAjax('../../../cgi_bin/netPrint.php?search='+netWork);
    document.getElementById('results').innerHTML=results;
    document.getElementById('imgWait').style.display='none';    
}

</script>
</head>

<body class="jss-Body">

<table class="jss-Table" style="width: 100%; height: 100%;">
	<tr>
		<td style="vertical-align: top; width: 70%">
		<iframe name="iprinters" src="printers2.php" style="overflow: auto; border-width: 0px; width: 100%; height: 100%;">
		</iframe></td>
		<td style="width: 30%; vertical-align: top;">
		<table class="jss-TableBorder" style="width: 100%">
			<tr>
				<td class="jss-Bar">Check this Ip:</td>
			</tr>
			<tr>
				<td>
				<input class="Jss-FieldAuto" maxlength="20" name="myIp" id="myIp" size="20" value="<?php echo $_SERVER["REMOTE_ADDR"]?>" style="text-align: center"></td>
			</tr>
			<tr>
				<td style="text-align: center">
				<input class="jss-Boton" name="goSearch1" type="submit" value="Go" onclick="javascript: goFun('searchPrinters(1)');"></td>
			</tr>
			<tr>
				<td class="jss-Bar">Search Printers at port 9100 in the netWork:</td>
			</tr>
			<tr>
				<td>
				<input class="Jss-FieldAuto" maxlength="20" name="myNetwork" id="myNetwork" size="20" value="<?php echo $_SERVER["REMOTE_ADDR"].'/24'?>" style="text-align: center"></td>
			</tr>
			<tr>
				<td style="text-align: center;">
				<input class="jss-Boton" name="goSearch2" type="submit" value="Go" onclick="javascript: goFun('searchPrinters(0)');"></td>
			</tr>
			<tr>
				<td class="jss-Bar">Check List Printer Status:</td>
			</tr>
			<tr>
				<td style="text-align: center;">
				<input class="jss-Boton" name="goCheck" type="submit" value="Go" onclick="javascript: goFun('checkStatus()');"></td>
			</tr>
			
			<tr>
				<td style="text-align: center;">
				<img id="imgWait" alt="" src="../../images/ajax-loader.gif" style="width: 48px; height: 48px; display: none;">&nbsp;</td>
			</tr>
			<tr>
				<td id="results">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
</table>

</body>

</html>
