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

if (isset($_REQUEST["type"])){$type=$_REQUEST["type"];}
else{$type='-';}

if (isset($_POST["idPayType"])){$idPayType=$_POST["idPayType"];}
else{$idPayType='-';}

$from= strftime('%Y-%m-%d',strtotime("-1 month"));
if (isset($_POST["from"])){$from=strftime('%Y-%m-%d',strtotime($_POST["from"]));}

$to= strftime('%Y-%m-%d',strtotime("+1 day"));
if (isset($_POST["to"])){$to=strftime('%Y-%m-%d',strtotime($_POST["to"]));}

//-------------------------------------------------
// Abre el Recordset
//-------------------------------------------------
$strsql="SELECT idInvoice, number, date, serie, type, payType, charged, total, company as client, agent, pos, 'pr' as pr FROM [invoClients]";
//$strsql="SELECT * FROM [invoClients];";

$strbase=$strsql;

$strsql=$strsql." WHERE (date BETWEEN '$from' AND '$to')";
if ($serie>'-'){$strsql=$strsql." AND serie='$serie'";}
if ($type>'-'){$strsql=$strsql." AND type='$type'";}
if ($idPayType>'-'){$strsql=$strsql." AND idPayType=$idPayType";}
if ($valorBuscar>''){$strsql=$strsql." AND number>=$valorBuscar";}
$strsql=$strsql." ORDER BY date DESC LIMIT 100;";
$strExport='../../../cgi_bin/phpfun.php?toXls='.urlencode(base64_encode($strsql));

$tmp=jGet('select idPrint || \',\' || ticket from home limit 1');
list($idPrint, $ticket) = explode(',', $tmp);

?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<script src="../../../cgi_bin/jss/jssCalendar.js" type="text/javascript"></script>
<base target="_self">
<title>Invoices</title>
<script type="text/javascript">
jss.Init=function(){
	var strsql='<?php echo base64_encode($strsql)?>';
	var datos=loadAjax('../../../cgi_bin/phpFun.php','jsgetArray=base64&strsql='+strsql).evalDecode();
	if (typeof datos!='object'){document.getElementById('sqlString').innerHTML=datos; return;};

	for(rows in datos['values']){
		var id=datos['values'][rows][0];
		datos['values'][rows][11]=('<img alt="Print" class="jss-Cursor" onclick="javascript: printTicket(\''+id+'\');" src="../../images/pr16.gif" style="border-width: 0px">');
	}
	
	var t= new jss.Grid({
		renderTo:'list',
		fireEvent: 'getVal',
		totals: true,
		values: datos['values'],
		columns: datos['columns']
	})
	t.table.rows[0].cells[0].style.width='35px';
    t.table.rows[0].cells[(t.table.rows[0].cells.length)-1].style.width='22px';
}

function printTicket(idInvoice){
	if(idInvoice<'1'){return;}
    var idModel=document.getElementById('idModel').value;
    var printer=document.getElementById('idModel')[document.getElementById('idModel').selectedIndex].text;
    if (printer.indexOf('*')>1){
        var printer=printer.split('*')[1].trim();
        var url='print.php?id='+idInvoice+'&printer='+printer;
        var fun='';
    } else{
	    var url='../reports/docs/parsec.php?idKey=idInvoice&id='+idInvoice+'&idModel='+idModel;
        var fun='window.frames.ifPrint.focus(); window.frames.ifPrint.print();';
    }
    
	wWin= new jss.Window({
	    style:{width: '80%',height: '80%'},
	    title:'Ticket',
	    iFrame:{id:'ifPrint', url:(url), scroll:'yes'}
	})
	setTimeout(fun,500);
}

function getVal(q){
	var id=q.cells[0].innerText || q.cells[0].textContent;
	if(id<'1'){return;}
	var type=q.cells[4].innerText || q.cells[4].textContent;
	if(type=='PARKING'){document.location.href='ticket.php?id='+id;}
	else{printTicket(id);}	
}

</script>
</head>

<body class="jss-Body" >

<table class="jss-TablePanel" style="width: 100%">
	<tr>
		<td>
		<form id="frOpciones" method="POST" name="frOpciones" style="margin: 0px" target="_self">
			<table id="cabecera" class="jss-Table" style="width: 100%">
				<tr class="jss-Caption">
					<td colspan="6"><?php echo _INVOICE?>:</td>
				</tr>
				<tr class="jss-Bar">
					<td>
					<a href="<?php echo $strExport;?>" style="width: 1%">
					<img alt="" src="../../images/excel16.jpg" style="border-width: 0px; width: 16px; height: 16px"></a></td>
					<td>
					&nbsp;</td>
					<td>
					&nbsp;</td>
					<td style="text-align: right;"><?php echo _PRINT._._TEMPLATES?>:</td>
					<td style="text-align: right">
						<select id="idModel" class="jss-FieldAuto" name="idModel" size="1">
						<?php echo putOptions('select idModel, title from [docsModel] where type=\'Invoice\' order by title ',$ticket);?>
						<?php echo putOptions('select idPrint, name || \' * \' || ip from [printers] order by name ',$idPrint);?>						
						</select></td>
					<td>
					<input class="jss-Boton" name="buscar" type="submit" value="<?php echo _SEARCH?>"></td>
					
				</tr>
				<tr class="jss-Bar">
					<td style="width: 15%"><?php echo _SERIE?>:</td>
					<td style="width: 15%">
					&nbsp;<?php echo _PAY.' '._TYPE?>:</td>
					<td style="width: 15%">
					<img alt="" src="../../images/calendar16.gif" style="width: 16px; height: 16px">
					<?php echo _FROM?>:</td>
					<td style="width: 15%">
					<img alt="" src="../../images/calendar16.gif" style="width: 16px; height: 16px">
					<?php echo _TO?>:</td>
					<td style="width: 15%"><?php echo _INVOICE.' '._NUMBER?>:</td>
					<td style="text-align: center; width: 1%;">&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align: left">
					<select id="serie" class="jss-FieldAuto" name="serie" size="1">
					<option>-</option>
					<?php echo putOptions("select serie from [invoiceSeries] order by serie",$serie);?>
					</select> </td>
					<td style="text-align: left">
					<select id="idPayType" class="jss-FieldAuto" name="idPayType" size="1">
					<option>-</option>
					<?php echo putOptions("select idPayType, payType from [payTypes] order by payType",$idPayType);?>
					</select></td>
					<td style="text-align: left">
					<input id="from" class="jss-DatePicker" maxlength="12" name="from" size="12" style="width: 100%"  value="<?php echo $from?>"></td>
					<td>
					<input id="to" class="jss-DatePicker" maxlength="12" name="to" size="12" style="width: 100%"  value="<?php echo $to?>"></td>
					<td colspan="2">
					<input id="valorBuscar" class="jss-FieldAuto" name="valorBuscar" size="45" value="<?php echo $valorBuscar;?>"></td>
				</tr>
			</table>
			<input name="type" type="hidden" value="<?php echo $type?>">
		</form>
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
