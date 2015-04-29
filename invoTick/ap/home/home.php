<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");		
	jCnn();		
?><?php
//-------------------------------------------------
//Salva los Cambios
//-------------------------------------------------
if (isset($_REQUEST["save"])){
	if (!isset($_REQUEST['showImages'])){$_REQUEST['showImages']='0';}
	if (!isset($_REQUEST['showChange'])){$_REQUEST['showChange']='0';}	
	saveForm ('Home', "idHome", $_REQUEST["idHome"]);
}
//-------------------------------------------------
//Abre el Recordset
//-------------------------------------------------
$strsql="select * from [Home] order by idHome limit 1";
?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<title>Home</title>
<base target="_self">
<script type="text/javascript">
jss.Init=function(){
	var strsql='<?php echo base64_encode($strsql)?>';
	var datos=loadAjax('../../../cgi_bin/phpFun.php?jsgetArray=base64&strsql='+strsql).evalDecode();
	jss.putData(datos['columns'],datos['values']);
}

</script>
</head>

<body class="Jss-Body">

<table class="jss-TablePanel" style="height: 100%; width: 100%;">
	<tr style="height: 1%">
		<td>
		<table id="pSystem" class="jss-Panel" style="width: 100%; height: 100%">
			<tr>
				<td class="jss-Caption">&nbsp;<?php echo _SYSTEM.' '._PARAM;?>:</td>
				<td class="jss-Up">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2">
				<table style="width: 100%">
					<tr>
						<td>
						<form method="POST" name="Fdata" style="margin: 0px">
							<table class="jss-Table" style="width: 100%">
								<tr>
									<td style="text-align: right; width: 15%;"><?php echo _VAT?>
									:</td>
									<td style="width: 35%" colspan="3">
									<input class="Jss-FieldAuto" maxlength="15" name="vat" size="15" value=""></td>
									<td style="text-align: right; width: 15%;"><?php echo _CONTACT?>
									:</td>
									<td style="width: 35%">
									<input class="Jss-FieldAuto" maxlength="60" name="contact" size="75" value=""></td>
								</tr>
								<tr>
									<td style="text-align: right"><?php echo _COMPANY;?>
									:</td>
									<td colspan="5">
									<input class="Jss-FieldAuto" maxlength="100" name="company" size="104" value=""></td>
								</tr>
								<tr>
									<td style="text-align: right; width: 15%;"><?php echo _ADDRESS;?>
									:</td>
									<td colspan="5">
									<input class="Jss-FieldAuto" maxlength="150" name="address" size="104" value=""></td>
								</tr>
								<tr>
									<td style="text-align: right"><?php echo _POSTAL_CODE;?>
									:</td>
									<td colspan="3">
									<input class="Jss-FieldAuto" maxlength="10" name="zipCode" size="12" value=""></td>
									<td style="text-align: right; width: 15%;"><?php echo _CITY;?>
									:</td>
									<td>
									<input class="Jss-FieldAuto" maxlength="50" name="city" size="40" value=""></td>
								</tr>
								<tr>
									<td style="text-align: right"><?php echo _STATE;?>
									:</td>
									<td colspan="3">
									<input class="Jss-FieldAuto" maxlength="50" name="county" size="40" value=""></td>
									<td style="text-align: right"><?php echo _COUNTRY;?>
									:</td>
									<td>
									<input class="Jss-FieldAuto" maxlength="50" name="country" size="40" value=""></td>
								</tr>
								<tr>
									<td style="text-align: right;"><?php echo _PHONE;?>
									:</td>
									<td style="height: 19px" colspan="3">
									<input class="Jss-FieldAuto" maxlength="18" name="phone" size="40" value=""></td>
									<td style="text-align: right;"><?php echo _FAX;?>
									:</td>
									<td style="height: 19px">
									<input class="Jss-FieldAuto" maxlength="18" name="fax" size="40" value=""></td>
								</tr>
								<tr>
									<td style="text-align: right"><?php echo _MOBILE;?>
									:</td>
									<td colspan="3">
									<input class="Jss-FieldAuto" maxlength="15" name="mobile" size="40" value=""></td>
									<td style="text-align: right"><?php echo _EMAIL;?>
									:</td>
									<td>
									<input class="Jss-FieldAuto" maxlength="100" name="email" size="40" value=""></td>
								</tr>
								<tr>
									<td style="text-align: right"><?php echo _BANK;?>
									:</td>
									<td colspan="3">
									<input class="Jss-FieldAuto" maxlength="50" name="bank" size="40" value=""></td>
									<td style="text-align: right"><?php echo _ACCOUNT;?>
									:</td>
									<td>
									<input class="Jss-FieldAuto" maxlength="30" name="cc" size="40" tabindex="14" value=""></td>
								</tr>
								<tr class="jss-Bar">
									<td colspan="6">&nbsp;<?php echo _EMAIL.' '._PARAM;?>:</td>
								</tr>
								<tr>
									<td style="text-align: right; width: 15%; white-space: nowrap;">
									<?php echo _USER.' '._NAME;?>: </td>
									<td style="width: 35%" colspan="3">
									<input class="Jss-FieldAuto" maxlength="100" name="sendEmailCount" size="40" value=""></td>
									<td style="text-align: right; width: 15%; white-space: nowrap;">
									<?php echo _SERVER;?> SMTP:</td>
									<td style="width: 35%">
									<input class="Jss-FieldAuto" maxlength="50" name="smtp" size="40" value=""></td>
								</tr>
								<tr>
									<td style="text-align: right"><?php echo _PASSWORD;?>
									:</td>
									<td colspan="3">
									<input class="Jss-FieldAuto" maxlength="50" name="sendEmailPass" size="40" type="password" value=""></td>
									<td style="text-align: right">&nbsp;</td>
									<td>
									&nbsp;</td>
								</tr>
								<tr>
									<td class="jss-Bar" colspan="6">&nbsp;<?php echo _BILLING._._PARAM;?>:</td>
								</tr>
								<tr>
									<td style="text-align: right;"><?php echo _RATES;?>
									:</td>
									<td colspan="3">
									<select class="jss-FieldAuto" name="idRate" size="1">
									<option>-</option>
									<?php echo putOptions("select idRate, rate from [rates] order by rate","");?>
									</select></td>
									<td style="text-align: right;"><?php echo _INVOICE._._SERIE;?>
									:</td>
									<td>
									<select class="jss-FieldAuto" name="idSerie" size="1">
									<option>-</option>
									<?php echo putOptions("select idSerie, serie from [invoiceSeries] order by serie","");?>
									</select></td>
								</tr>
								<tr>
									<td style="text-align: right"><?php echo _INVOICE._._TEMPLATES;?>
									:</td>
									<td colspan="3">
									<select class="jss-FieldAuto" name="invoice" size="1">
									<option>-</option>
									<?php echo putOptions("select idModel, title from [docsModel] order by title","");?>
									</select></td>
									<td style="text-align: right"><?php echo _CUSTOMERS;?>
									:</td>
									<td>
									<select class="jss-FieldAuto" name="idClient" size="1">
									<option>-</option>
									<?php echo putOptions("select idCompany, company from [companies] order by company","");?>
									</select></td>
								</tr>
								<tr>
									<td style="text-align: right"><?php echo _TICKET._._TEMPLATES;?>
									:</td>
									<td colspan="3">
									<select class="jss-FieldAuto" name="ticket" size="1">
									<option>-</option>
									<?php echo putOptions("select idModel, title from [docsModel] order by title","");?>
									</select></td>
									<td style="text-align: right"><?php echo _POS;?>
									:</td>
									<td>
									<select class="jss-FieldAuto" name="idPos" size="1">
									<option>-</option>
									<?php echo putOptions("select idPos, posName from [pos] order by posName","");?>
									</select></td>
								</tr>
								<tr>
									<td style="text-align: right">Show Images:</td>
									<td>
					<input name="showImages" type="checkbox" value="1" class="jss-Check" ></td>
									<td style="text-align: right">
									Show change:</td>
									<td>
					<input name="showChange" type="checkbox" value="1" class="jss-Check" ></td>
									<td style="text-align: right">Default printer:</td>
									<td>
									<select class="jss-FieldAuto" name="idPrint" size="1">
									<option>-</option>
									<?php echo putOptions("select idPrint, name from [printers] order by name","");?>
									</select></td>
								</tr>
								<tr>
									<td colspan="6" style="text-align: center">
									<input class="jss-Boton" name="save" type="submit" value="<?php echo _SAVE?>"></td>
								</tr>
								
							</table><input name="idHome" type="hidden" value="">
						</form>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr style="vertical-align: top">
		<td>
		<table id="pTabs" class="jss-Tab" style="width: 100%; height: 100%;">
			<tr>
				<td class="jss-TabInactive" title="iFrame:{id:'ivatTypes', scroll:'yes', url:'vatTypes.php'}">
				<?php echo _TAXES;?></td>
				<td class="jss-TabInactive" title="iFrame:{id:'irates', scroll:'yes', url:'rates.php'}">
				<?php echo _RATES;?>&nbsp;</td>
				<td class="jss-TabInactive" title="iFrame:{id:'ipayTypes', scroll:'yes', url:'payTypes.php'}">
				<?php echo _PAY.' '._TYPE;?>&nbsp;</td>
				<td class="jss-TabInactive" title="iFrame:{id:'iinvoSeries', scroll:'yes', url:'invoSeries.php'}">
				<?php echo _INVOICE.' '._SERIE;?>&nbsp;</td>
				<td class="jss-TabInactive" title="iFrame:{id:'ipos', scroll:'yes', url:'pos.php'}">
				<?php echo _POS;?>&nbsp;</td>
				<td class="jss-TabInactive" title="iFrame:{id:'ipos', scroll:'yes', url:'periodicitys.php'}">
				<?php echo _PERIODICITY._._FEES;?>&nbsp;</td>
				<td class="jss-TabInactive" title="iFrame:{id:'iimages', scroll:'yes', url:'images.php'}">
				<?php echo _IMAGES;?></td>
				<td class="jss-TabInactive" title="iFrame:{id:'iprinters', scroll:'yes', url:'printers1.php'}">
				Printers</td>				
				<td></td>
			</tr>
			<tr>
				<td colspan="6">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
</table>

</body>

</html>
