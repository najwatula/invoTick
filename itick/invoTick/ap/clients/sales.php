<?php
	require_once("../../../cgi_bin/phpFun.php");
	require_once("../languages/language.php");				
	jCnn();
?><?php
//-------------------------------------------------
// Captura de Parametros
//-------------------------------------------------
//on error resume next

$valorBuscar='';
if (isset($_REQUEST["valorBuscar"])){$valorBuscar=$_REQUEST["valorBuscar"];}

$idModel=jGet('select invoice from home limit 1');
if ($idModel=='-'){$idModel='0';}
$printInvoice='../reports/docs/parsec.php?idKey=idInvoice&idModel='.$idModel.'&id=';

if (isset($_POST["serie"])){$serie=$_POST["serie"];}
else{$serie='-';}

if (isset($_POST["payType"])){$payType=$_POST["payType"];}
else{$payType='-';}

$from= strftime('%Y-%m-%d',strtotime("-1 month"));
if (isset($_POST["from"])){$from=strftime('%Y-%m-%d',strtotime($_POST["from"]));}

$to= strftime('%Y-%m-%d',strtotime("+1 day"));
if (isset($_POST["to"])){$to=strftime('%Y-%m-%d',strtotime($_POST["to"]));}

//-------------------------------------------------
// Abre el Recordset
//-------------------------------------------------
$strsql='SELECT idInvoice, agent, pos, charged, date, number, serie, type, payType, total FROM [invoClients] WHERE idClient='.$_SESSION['idCompany'];
$strbase=$strsql;

$strsql=$strsql." AND (date BETWEEN '".$from."' AND '".$to."')";
if ($serie>'-'){$strsql=$strsql." AND serie='$serie'";}
if ($payType>'-'){$strsql=$strsql." AND payType='$payType'";}
if ($valorBuscar>''){$strsql=$strsql." AND number>=$valorBuscar";}

$strsql=$strsql." ORDER BY date DESC LIMIT 100;";
$strExport='../../../cgi_bin/phpfun.php?toXls='.urlencode(base64_encode($strsql));
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
<title>Sales by</title>
<script type="text/javascript">
jss.Init=function(){
	var strsql='<?php echo base64_encode($strsql)?>';
	var datos=loadAjax('../../../cgi_bin/phpFun.php?jsgetArray=base64&strsql='+strsql).evalDecode();
	new jss.Grid({
		totals: true,	
		renderTo:'list',
		fireEvent: 'getVal',
		values: datos['values'],
		columns: datos['columns']
	})
}

function getVal(q){
	var id=q.cells[0].innerText || q.cells[0].textContent;
	var type=q.cells[3].innerText || q.cells[3].textContent;
	if(type=='PARKING'){
		window.parent.document.location.href='../ticketing/ticket.php?id='+id;
	}else{
		wWin= new window.parent.window.parent.jss.Window({
			style:{width: '80%',height: '80%'},
			title:'Invoice',
			html:(loadAjax('<?php echo ($printInvoice)?>'+id))
		})
	}	
}

</script>
</head>

<body class="jss-Body">

<table class="jss-TablePanel" style="width: 100%">
	<tr>
		<td>
		<form id="frOpciones" method="POST" name="frOpciones" style="margin: 0px" target="_self">
			<table id="cabecera" class="jss-Table" style="width: 100%">
				<tr class="jss-Bar">
					<td style="width: 20%"><?php echo _SERIE?>:</td>
					<td style="width: 20%"><?php echo _PAY.' '._TYPE?>:</td>
					<td style="width: 20%">
					<img alt="" src="../../images/calendar16.gif" style="width: 16px; height: 16px"> <?php echo _FROM?>:</td>
					<td style="width: 20%">
					<img alt="" src="../../images/calendar16.gif" style="width: 16px; height: 16px"> <?php echo _TO?>:</td>
					<td style="width: 20%"><?php echo _INVOICE.' '._NUMBER?>:</td>
					<td style="text-align: center; width: 100px;">
					<a href="../tables/<?php echo $strExport;?>" style="width: 1%">
					<img alt="" src="../../images/excel16.jpg" style="border-width: 0px; width: 16px; height: 16px"></a></td>
				</tr>
				<tr>
					<td style="text-align: left">
					<select id="serie" class="jss-FieldAuto" name="serie" size="1">
					<option>-</option>
					<?php echo putOptions("select serie from [invoiceSeries] order by serie",$serie);?>
					</select> </td>
					<td>
					<select id="payType" class="jss-FieldAuto" name="payType" size="1">
					<option>-</option>
					<?php echo putOptions("select payType from [payTypes] order by payType",$payType);?>
					</select></td>
					<td>
					<input id="from" class="jss-DatePicker" maxlength="10" name="from" size="12"   style="width: 100%" value="<?php echo $from?>"></td>
					<td>
					<input id="to" class="jss-DatePicker" maxlength="10" name="to" size="12" style="width: 100%"  value="<?php echo $to?>"></td>
					<td>
					<input id="valorBuscar" class="jss-FieldAuto" name="valorBuscar" size="45" value="<?php echo $valorBuscar;?>"></td>
					<td style="text-align: center">
					<input class="jss-Boton" name="buscar" type="submit" value="Search"></td>
				</tr>
				<tr>
		<td colspan="6" id="sqlString" style="font-size: 6pt"><?php echo $strsql?></td>
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
