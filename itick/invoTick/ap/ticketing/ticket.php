<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");
	jCnn();
	$id='0';
	if (isset($_REQUEST["id"])){$id=$_REQUEST["id"];}
	$strsql="select * from [home] limit 1";
    $defaults = $GLOBALS['db']->query($strsql);
    $defaults=$defaults->fetch();
    
    $agent=jGet('select name from agents where idAgent='.$_SESSION['idAgent']);
    $client=jGet('select company from companies where idCompany='.$defaults['idClient']);
?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<title>ticketing</title>
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<script src="../../../cgi_bin/jss/jssCalendar.js" type="text/javascript"></script>
<script src="ticket.js" type="text/javascript"></script>
<base target="_self">
<script type="text/javascript">
var dataProduct, idFamily=0, vWin, oldValue, oldCellValue;
var db='<?php echo $_SESSION["domain"]?>';
var requestId='<?php echo $id?>';
var idAgent='<?php echo $defaults["idAgent"]?>' || '0';
var showImages='<?php echo $defaults["showImages"]?>' || '0';
var showChange='<?php echo $defaults["showChange"]?>' || '0';
var vatType=0;

jss.Init=function(){
	if (requestId>'0'){getTicket(requestId)}
	document.getElementById('date').innerHTML=new Date().format('dd/mmm');
	document.getElementById('time').innerHTML=new Date().format('hh:nn');	
	putFamilys();
	document.getElementById('barCode').focus()
}

function keyFilter(qType){
    if(window.event.keyCode==13){window.event.returnValue=false; return;} 
    var ch=String.fromCharCode(window.event.keyCode);
    if(qType=='number' && !isNumeric(ch) && ch!='.') {window.event.returnValue=false;}
}
</script>
</head>

<body class="jss-Body">

<table class="jss-NoMargins" style="width: 100%; height: 99%; table-layout: fixed;">
	<tr>
		<td colspan="2" style="height: 1%">
		<table id="pTop" class="jss-TableBorder" style="width: 100%;">
			<tr>
				<td class="jss-Caption" style="width: 99%">Ticketing / <?php echo _AGENTS?>: <?php echo $_SESSION['idAgent'].' / '.$agent?>
				</td>
				<td class="jss-Caption" style="width: 1%">
								<img alt="Delete" src="../../images/delete16.gif" style="width: 16px; height: 16px" onclick="javascript:deleteTicket('<?php echo _DELETE.': '._ARE_SURE.'?' ?>');" class="jss-Cursor"></td>
			</tr>
			<tr>
				<td colspan="2">
				<table id="tPanel" class="jss-Table" style="width: 100%;">
					<tr>
						<td class="jss-Bar" style="width: 5%"><?php echo _DATE?>
						</td>
						<td class="jss-Bar" style="width: 5%"><?php echo _TIME?>
						</td>
						<td class="jss-Bar" style="width: 5%">Units</td>
						<td class="jss-Bar" style="width: 5%">Dis%</td>
						<td class="jss-Bar" style="width: 15%"><?php echo _PRINT._._TEMPLATES?>
						</td>
						<td class="jss-Bar" style="width: 10%"><?php echo _SERIE?>
						</td>
						<td class="jss-Bar" style="width: 10%"><?php echo _POS?>
						</td>
						<td rowspan="4" style="width: 5%;">
						<table style="width: 100%; border-collapse: collapse; height: 100%;">
							<tr>
								<td style="text-align: center">
								<img alt="Ticket" class="jss-TableBorder jss-Cursor" src="../../images/invoice16.gif" style="height: 30pt; width: 30pt;" onclick="javascript:makeTicket('TICKET');"></td>
							</tr>
							<tr>
								<td style="text-align: center" >
								<img alt="Parking" class="jss-TableBorder jss-Cursor" src="../../images/parking.gif" style="height: 30pt; width: 30pt;" onclick="javascript:makeTicket('PARKING');">
								</td>
							</tr>
							</table>
						</td>
						<td class="jss-Bar" style="text-align: center;"><?php echo _AMOUNT?></td>
					</tr>
					<tr>
						<td id="date" style="text-align: center"></td>
						<td id="time" style="text-align: center"></td>
						<td>
						<input id="units" class="jss-FieldAuto" maxlength="6" name="units" size="6" style="text-align: center" value="1"></td>
						<td>
						<input id="discount" class="jss-FieldAuto" maxlength="6" name="discount" size="6" style="text-align: center" value="0"></td>
						<td>
						<select id="idModel" class="jss-FieldAuto" name="idModel" size="1">
						<?php echo putOptions('select idModel, title from [docsModel] where type=\'Invoice\' order by title ',$defaults["ticket"]);?>
						<?php echo putOptions('select idPrint, name || \' * \' || ip from [printers] order by name ',$defaults["idPrint"]);?>						
						</select> </td>
						<td>
						<select id="serie" class="jss-FieldAuto" name="serie" size="1">
						<?php echo putOptions("select idSerie, serie from [invoiceSeries] order by serie",$defaults["idSerie"]);?>
						</select></td>
						<td>
						<select id="idPos" class="jss-FieldAuto" name="idPos" size="1">
						<?php echo putOptions("select idPos, posName from [pos] order by posName",$defaults["idPos"]);?>
						</select></td>
						<td rowspan="3">
						<table class="jss-TableBorder" style="width: 100%; height: 100%; background-color: #000000; font-family: 'Courier New';">
							<tr>
								<td id="total" colspan="2" style="color: #FFFF99; font-weight: bold; font-size: 22pt; text-align: center;">
								0</td>
							</tr>
							<tr>
								<td id="base" style="color: #FFFF99; font-weight: bold; font-size: 12pt; text-align: center;">
								0</td>
								<td id="vatAmount" style="color: #FFFF99; font-weight: bold; font-size: 12pt; text-align: center;">
								0</td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td class="jss-Bar" colspan="5"><?php echo _CUSTOMERS?>
						</td>
						<td class="jss-Bar" colspan="1"><?php echo _RATE?></td>
						<td class="jss-Bar"><?php echo _PAY._._TYPE?></td>
					</tr>
					<tr>
						<td id="idClient" class="jss-Cursor" style="text-align: center" title="<?php echo $defaults['idClient'] ?>">
						<img alt="" onclick="javascript: pickClient();" src="../../images/list16.gif" style="width: 16px; height: 16px"></td>
						<td colspan="4">
						<input id="client" class="jss-FieldAuto" maxlength="100" name="client" onclick="javascript: pickClient();" size="100" value="<?php echo $client ?>"></td>
						<td colspan="1">
						<select id="idRate" class="jss-FieldAuto" name="idRate" onchange="javascript: currentRate=this.value; putProducts(currentFamily, currentBgColor);" size="1">
						<?php echo putOptions("select idRate, Rate from [Rates] order by idRate",$defaults["idRate"]);?>
						</select></td>
						<td>
						<select id="idPayType" class="jss-FieldAuto" name="idPayType" size="1">
						<?php echo putOptions("select idPayType, payType from [payTypes] order by idPayType","");?>
						</select></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td style="vertical-align: top; width: 50%;">
		<table id="tInferior" class="jss-TableBorder" style="width: 100%; height: 100%;">
			<tr class="jss-Bar">
				<td ><?php echo _FAMILYS?></td>
			</tr>
			<tr><td style="height: 30%; vertical-align: top;">
				<div id="pFamilys" style="overflow: auto; height: 100%;">
				</div></td>
			</tr>
			<tr class="jss-Bar">
				<td><?php echo _PRODUCTS.' / '._INSERT._._BY?>barCode:
				<input id="barCode" class="jss-Field" maxlength="20" name="barCode" onkeypress="javascript: if (window.event.keyCode == 13){window.event.returnValue=false; putBarCode(this)}" size="20" style="text-align: center"></td>
			</tr>
			<tr><td style="vertical-align: top;">
				<div id="pProducts" style="overflow: auto; height: 100%;">
				</div></td>
			</tr>
		</table>
		</td>
		<td style="vertical-align: top; width: 50%;">
		<div style="overflow: auto; height: 100%;">
		<table id="pItems" class="jss-TableGrid" style="width: 100%;">
			<colgroup>
				<col style="width: 1%; height: 20pt;">
				<col style="width: 5%; height: 20pt;"><col style="height: 20pt">
				<col style="width: 8%; height: 20pt;">
				<col style="width: 5%; height: 20pt;">
				<col style="width: 5%; height: 20pt;">
				<col style="width: 10%; height: 20pt;">
			</colgroup>
			<tr class="jss-Bar">
				<td></td>
				<td>Quant</td>
				<td><?php echo _ITEM?></td>
				<td>Price</td>
				<td>Disc%</td>
				<td>Vat%</td>
				<td><?php echo _SUM?></td>
			</tr>
			<tr>
				<td style="text-align: right">
				<img alt="" class="jss-Cursor" onclick="javascript: deleteLine(this.parentNode.parentNode.rowIndex);" src="../../images/delete16.gif" style="width: 16px; height: 16px"></td>
				<td onkeypress="javascript:keyFilter('number');">0</td>
				<td onkeypress="javascript:keyFilter('text');"></td>
				<td onkeypress="javascript:keyFilter('number');" style="text-align: right">0</td>
				<td onkeypress="javascript:keyFilter('number');" style="text-align: right">0</td>
				<td onkeypress="javascript:keyFilter('number');" style="text-align: right">0</td>
				<td onkeypress="javascript:keyFilter('number');" style="text-align: right">0.00</td>
			</tr>
		</table></div>
		</td>
	</tr>
</table>

</body>

</html>
