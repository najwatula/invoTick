<?php
	require_once("../../../cgi_bin/phpFun.php");
	require_once("../languages/language.php");				
	jCnn();
?><?php

// Captura de Parametros
//-------------------------------------------------
$report='Items';
if (isset($_REQUEST['report'])){$report=$_REQUEST['report'];}

$valorBuscar='';
if (isset($_REQUEST["valorBuscar"])){$valorBuscar=$_REQUEST["valorBuscar"];}

if (isset($_POST["varItem1"])){$varItem1=$_POST["varItem1"];}
else{$varItem1='-';}

if (isset($_POST["varItem2"])){$varItem2=$_POST["varItem2"];}
else{$varItem2='-';}

if (isset($_POST["varItem3"])){$varItem3=$_POST["varItem3"];}
else{$varItem3='-';}

$from= strftime('%Y-%m-%d',strtotime("-1 month"));
if (isset($_POST["from"])){$from=strftime('%Y-%m-%d',strtotime($_POST["from"]));}

$to= strftime('%Y-%m-%d',strtotime("+1 day"));
if (isset($_POST["to"])){$to=strftime('%Y-%m-%d',strtotime($_POST["to"]));}

// Prepare Query
//-------------------------------------------------

if ($report=='Items'){
    $groupCol=0;
	$strsql='SELECT family, concept, quantity, total FROM [acumItems]';
	$varsql1="select family from familys";
	$varsql2="select '' from familys limit 0";
	$varsql3="select '' from familys limit 0";
	$orden='family, concept';
}
if ($report=='payType'){
    $groupCol=0;
	$strsql='SELECT payType, type, serie, date, date, quantity, base, vatAmount, total FROM [acumPayTypes]';
	$varsql1="select payType from payTypes";	
	$varsql2="select type from invoiceTypes";
	$varsql3="select serie from invoiceSeries";
	$orden='payType, date';	
}
if ($report=='vat'){
    $groupCol=0;
	$strsql='SELECT * FROM [invoSum]';
	$varsql1="select vatValue from vatTypes";
	$varsql2="select type from invoiceTypes";
	$varsql3="select serie from invoiceSeries";
	$orden='vat, date';		
}

if ($report=='pos'){
    $groupCol=0;
	$strsql='SELECT pos, agent, type, serie, company as client, number, date, payType, charged, total FROM [invoClients]';
	$varsql1="select posName from pos";	
	$varsql2="select name from agents";
	$varsql3="select type from invoiceTypes";
	$orden='pos, agent';			
}

$results = $GLOBALS['db']->query($strsql);
if (!$results){$err=$GLOBALS['db']->errorInfo(); return $err[2];}
for ($col = 0; $col < 3; $col++) {
	$meta = $results->getColumnMeta($col);
    $name[$col]=$meta["name"];
}

$strsql=$strsql." WHERE (date BETWEEN '".$from."' AND '".$to."')";
if ($varItem1>'-'){$strsql=$strsql." AND $name[0]='$varItem1'";}
if ($varItem2>'-'){$strsql=$strsql." AND $name[1]='$varItem2'";}
if ($varItem3>'-'){$strsql=$strsql." AND $name[2]='$varItem3'";}
$strsql=$strsql.' ORDER BY '.$orden;

$strExport='../../../cgi_bin/phpFun.php?toXls='.base64_encode($strsql);
//echo $strsql;
?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<script src="../../../cgi_bin/jss/jssCalendar.js" type="text/javascript"></script>
<base target="_self">
<title>Accumulate Reports</title>
<script type="text/javascript">
jss.Init=function(){
	var strsql='<?php echo base64_encode($strsql)?>';
	var datos=loadAjax('../../../cgi_bin/phpFun.php?jsgetArray=base64&strsql='+strsql).evalDecode();
	if (typeof datos!='object'){document.getElementById('list').innerHTML=datos; return;};	
	
	new jss.Grid({
		renderTo:'list',
		totals: true,
		group: <?php echo $groupCol?>,
		values: datos['values'],
		columns: datos['columns']
	})
}

</script>
</head>

<body class="jss-Body">

<table class="jss-TablePanel" style="width: 100%">
	<tr>
		<td>
		<form id="frOpciones" method="POST" name="frOpciones" style="margin: 0px" target="_self">
			<table id="cabecera" class="jss-Table" style="width: 100%">
				<tr class="jss-Caption">
					<td colspan="6"><?php echo _ACCUMULATED._.$report?>:</td>
				</tr>
				<tr class="jss-Bar">
					<td style="width: 20%"><?php echo $name[0]?>:</td>
					<td style="width: 20%"><?php echo $name[1]?>:</td>
					<td style="width: 20%"><?php echo $name[2]?>:</td>
					<td style="width: 20%">
					<img alt="" src="../../images/calendar16.gif" style="width: 16px; height: 16px">
					<?php echo _FROM?>:</td>
					<td style="width: 20%">
					<img alt="" src="../../images/calendar16.gif" style="width: 16px; height: 16px">
					<?php echo _TO?>:</td>
					<td style="text-align: center; width: 100px;">
					<a href="../products/<?php echo $strExport;?>" style="width: 1%">
					<img alt="" src="../../images/excel16.jpg" style="border-width: 0px; width: 16px; height: 16px"></a></td>
				</tr>
				<tr>
					<td>
					<select id="varItem1" class="jss-FieldAuto" name="varItem1" size="1">
					<option>-</option>
					<?php echo putOptions($varsql1,$varItem1)?></select></td>
					<td>
					<select id="varItem2" class="jss-FieldAuto" name="varItem2" size="1">
					<option>-</option>
					<?php echo putOptions($varsql2,$varItem2)?></select></td>
					<td>
					<select id="varItem3" class="jss-FieldAuto" name="varItem3" size="1">
					<option>-</option>
					<?php echo putOptions($varsql3,$varItem3)?></select></td>
					<td>
					<input id="from" class="jss-DatePicker" maxlength="12" name="from" size="12" style="width: 100%"  value="<?php echo $from?>"></td>
					<td>
					<input id="to" class="jss-DatePicker" maxlength="12" name="to" size="12" style="width: 100%"   value="<?php echo $to?>"></td>
					<td style="text-align: center">
					<input class="jss-Boton" name="buscar" type="submit" value="Search"></td>
				</tr>
				<tr>
					<td id="sqlString" colspan="6" style="font-size: 6pt"><?php echo $strsql?>
					</td>
				</tr>
			</table>
		</form>
		</td>
	</tr>
	<tr>
		<td id="list"></td>
	</tr>
</table>

</body>

</html>
