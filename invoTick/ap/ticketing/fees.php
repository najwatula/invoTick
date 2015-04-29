<?php
	require_once("../../../cgi_bin/phpFun.php");
	require_once("../languages/language.php");				
	jCnn();
?><?php
//-------------------------------------------------
// Captura de Parametros
//-------------------------------------------------
//on error resume next

$idModel=jGet('select invoice from home limit 1');
if ($idModel=='-'){$idModel='0';}
$printInvoice='../reports/docs/parsec.php?idKey=idInvoice&idModel='.$idModel.'&id=';

if (isset($_POST["periodicity"])){$periodicity=$_POST["periodicity"];}
else{$periodicity='-';}

$dateInvoice= strftime('%Y-%m-%d',strtotime("+1 day"));
if (isset($_POST["dateInvoice"])){$dateInvoice=strftime('%Y-%m-%d',strtotime($_POST["dateInvoice"]));}

if (isset($_POST["serie"])){$serie=$_POST["serie"];}
else{$serie='-';}

if (isset($_REQUEST["idRate"])){$type=$_REQUEST["idRate"];}
else{$idRate='-';}

if (isset($_POST["idFamily"])){$idFamily=$_POST["idFamily"];}
else{$idFamily='-';}

$from= strftime('%Y-%m-%d',strtotime("-1 month"));
if (isset($_POST["from"])){$from=strftime('%Y-%m-%d',strtotime($_POST["from"]));}

$to= strftime('%Y-%m-%d',strtotime("+1 day"));
if (isset($_POST["to"])){$to=strftime('%Y-%m-%d',strtotime($_POST["to"]));}

//-------------------------------------------------
// Abre el Recordset
//-------------------------------------------------
$strsql="select idFee, idMember, periodicity, nextDate, surName, name, company, family, item, rate,vatPrice, 's' as s from [feesBymembersByproducts]";

$strsql=$strsql." WHERE (nextDate BETWEEN '$from' AND '$to')";
if ($periodicity>'-'){$strsql=$strsql." AND periodicity='$periodicity'";}
if ($idRate> '-'){$strsql=$strsql." AND idRate='$idRate'";}
if ($idFamily >'-'){$strsql=$strsql." AND idFamily ='$idFamily '";}

$strsql=$strsql." ORDER BY nextDate;";
$strExport='../../../cgi_bin/phpfun.php?toXls='.urlencode(base64_encode($strsql));
?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<script src="../../../cgi_bin/jss/jssCalendar.js" type="text/javascript"></script>
<base target="_self">
<title>Fees</title>
<script type="text/javascript">
var tList;
jss.Init=function(){
	var strsql='<?php echo base64_encode($strsql)?>';
	var datos=loadAjax('../../../cgi_bin/phpFun.php?jsgetArray=base64&strsql='+strsql).evalDecode();
	if (typeof datos!='object'){document.getElementById('sqlString').innerHTML=datos; return;};
	for(rows in datos['values']){
		var id=datos['values'][rows][0];
		datos['values'][rows][11]=('<input name="'+id+'" type="checkbox">');
	}
	
	tList= new jss.Grid({
		renderTo:'list',
		fireEvent: 'goCheck',
		totals: true,
		values: datos['values'],
		columns: datos['columns']
	})
	tList.table.rows[0].cells[0].style.width='4%';
	tList.table.rows[0].cells[11].style.width='2%';
    tList.table.rows[0].cells[11].innerHTML='<input id="checkAll" type="checkbox" onclick="javascript: checkAll();">';	
}

function checkAll(){
    var checked=document.getElementById('checkAll').checked;
    for(n=1;n<tList.table.rows.length;n++){
        tList.table.rows[n].cells[11].firstChild.checked=checked;
    }
}
function makeInvoices(idInvoice){
}

function goSearch(){
    q.cells[11].firstChild.click();
}

function goCheck(q){
    q.cells[11].firstChild.click();
}

</script>
</head>

<body class="jss-Body">

<table class="jss-TablePanel" style="width: 100%">
	<tr>
		<td>
		<form id="frOpciones" method="POST" name="frOpciones" style="margin: 0px" target="_self">
			<table id="tInvoicing" class="jss-Table" style="width: 100%">
				<tr class="jss-Caption">
					<td colspan="3">&nbsp; <?php echo _FEES?>:</td>
				</tr>
				<tr class="jss-Bar">
					<td style="width: 50%"><?php echo _INVOICE._._TEMPLATES?>:</td>
					<td style="width: 50%">
					<img alt="" src="../../images/calendar16.gif" style="width: 16px; height: 16px">
					<?php echo _INVOICE._._DATE?>:</td>
					<td style="width: 1%; text-align: right;">
					<input class="jss-Boton" name="buscar" onclick="javascript: makeInvoices();" type="button" value="<?php echo _BILL?>"></td>
				</tr>
				<tr>
					<td>
					<select id="idModel" class="jss-FieldAuto" name="idModel" size="1">
					<?php echo putOptions('select idModel, title from [docsModel] order by title ',jGet('select invoice from home'));?>
					</select></td>
					<td colspan="2">
					<input id="dateInvoice" class="jss-DatePicker" maxlength="12" name="dateInvoice" size="12" style="width: 100%" value="<?php echo $dateInvoice?>"></td>
				</tr>
			</table>
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<form method="post" name="frmSearch" style="margin: 0px;" target="_self">
		<table id="tFilter" class="jss-Table" style="width: 100%">
			<tr class="jss-Caption">
				<td colspan="6">&nbsp;<?php echo _SEARCH?>:</td>
			</tr>
			<tr class="jss-Bar">
				<td style="width: 15%"><?php echo _FAMILY?>:</td>
				<td style="width: 15%"><?php echo _PERIODICITY?>:</td>
				<td style="width: 15%">&nbsp;<?php echo _RATE?>:</td>
				<td style="width: 15%">
				<img alt="" src="../../images/calendar16.gif" style="width: 16px; height: 16px">
				<?php echo _FROM?>:</td>
				<td style="width: 15%">
				<img alt="" src="../../images/calendar16.gif" style="width: 16px; height: 16px">
				<?php echo _TO?>:</td>
				<td rowspan="2" style="width: 10%; text-align: right;">&nbsp;<img alt="" onclick="javascript goExport();" src="../../images/excel16.jpg" style="border-width: 0px;">&nbsp;
				<input class="jss-Boton" name="search" type="submit" value="<?php echo _SEARCH?>"></td>
			</tr>
			<tr>
				<td style="text-align: left">
				<select id="idFamily" class="jss-FieldAuto" name="idFamily" onclick="javascript: goSearch();" size="1">
				<option>-</option>
				<?php echo putOptions("select idFamily, family from [familys] order by family",$idFamily);?>
				</select> </td>
				<td style="text-align: left">
				<select id="periodicity" class="jss-FieldAuto" name="periodicity" onclick="javascript: goSearch();" size="1">
				<option value="-">-</option>
			<option <?php echo ($periodicity=='DAILY' ? 'selected' :'')?> value="DAILY">
			<?php echo _DAILY ?></option>
			<option <?php echo ($periodicity=='WEEKLY' ? 'selected' :'')?> value="WEEKLY">
			<?php echo _WEEKLY ?></option>
			<option <?php echo ($periodicity=='MONTHLY' ? 'selected' :'')?> value="MONTHLY">
			<?php echo _MONTHLY ?></option>
			<option <?php echo ($periodicity=='YEARLY' ? 'selected' :'')?> value="YEARLY">
			<?php echo _YEARLY ?></option>
				</select></td>
				<td style="text-align: left">
				<select id="idRate" class="jss-FieldAuto" name="idRate" onclick="javascript: goSearch();" size="1">
				<option>-</option>
				<?php echo putOptions("select idRate, rate from [rates] order by rate",$idRate);?>
				</select></td>
				<td style="text-align: left">
				<input id="from" class="jss-DatePicker" maxlength="12" name="from" size="12" style="width: 100%" value="<?php echo $from?>" ></td>
				<td>
				<input id="to" class="jss-DatePicker" maxlength="12" name="to" size="12" style="width: 100%"  value="<?php echo $to?>"></td>
			</tr>
		</table></form>
		</td>
	</tr>
	<tr>
		<td id="sqlString" style="font-size: 6pt"><?php echo $strsql?></td>
	</tr>
	<tr>
		<td id="list"></td>
	</tr>
</table>

</body>

</html>
