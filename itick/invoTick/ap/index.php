<?php
	require("../../cgi_bin/phpFun.php");
	require("languages/language.php");
	jCnn();
	$strImg='../../cgi_bin/phpFun.php?getImg=base64&strsql='.base64_encode("select image from images where tableName='home' and id=1");
?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<title>invoTick</title>
<base target="iMain">
<link href="../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<script type="text/javascript">
jss.Init=function(){
	if (top.location != self.location){top.location = self.location};
}
function imprimir_onclick(){
	window.parent.frames['iMain'].focus();
	window.parent.frames['iMain'].print();
}

function showReport(obj){
	if (obj.options[obj.selectedIndex].value=='-') return;
	var parametros='title=Resumen por '+obj.options[obj.selectedIndex].text;
	parametros+='&table='+obj.options[obj.selectedIndex].value;
	window.parent.frames['iMain'].location='reports/infoAuto.php?'+parametros;
}

</script>
</head>

<body class="jss-Body" style="overflow: visible; margin: 0px;">

<table style="width: 100%; height: 100%; border-collapse: collapse;">
	<tr>
		<td style="vertical-align: top">
		<table id="pMenu" class="jss-Panel" style="overflow: visible; width: 140pt; height: 100%;">
			<tr class="jss-Caption">
				<td class="jss-Caption"><?php echo _MENU?></td>
				<td class="jss-Left">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" style="vertical-align: top; display: none;">
				<table class="jss-Table" style="width: 100%">
					<tr>
						<td colspan="2">
						<table class="jss-Menu" style="width: 100%">
							<tr class="jss-Bar">
								<td class="jss-Down"></td>
								<td class="jss-Bar"><?php echo _MANAGEMENT?>
								</td>
							</tr>
							<tr>
								<td>
								<img alt="" src="../images/invoice16.gif" style="width: 16px; height: 16px">
								</td>
								<td>
								<a href="ticketing/ticket.php" onmouseup="javascript:pMenu.collapse();">
								<?php echo _TICKETING?></a></td>
							</tr>
							<tr>
								<td>
								<img alt="" src="../images/parking.gif" style="width: 16px; height: 16px">
								</td>
								<td>
								<a href="ticketing/invoices.php?type=PARKING"><?php echo _PARKING?>
								</a></td>
							</tr>
							<tr>
								<td>
								<img alt="" src="../images/invoice.gif" style="width: 16px; height: 16px">
								</td>
								<td>
								<a href="ticketing/invoices.php?type=TICKET"><?php echo _INVOICE?>
								</a></td>
							</tr>
							<tr>
								<td>
								<img alt="" src="../images/form16.gif" style="width: 16px; height: 16px">
								</td>
								<td><a href="ticketing/fees.php"><?php echo _INVOICE._._FEES?>
								</a></td>
							</tr>
							<tr>
								<td>
								<img alt="" src="../images/templates.gif" style="width: 16px; height: 16px">
								</td>
								<td><a href="reports/docs/edit.php"><?php echo _TEMPLATES?>
								</a></td>
							</tr>
							<tr>
								<td>
								<img alt="" height="16" src="../images/info16.gif">
								</td>
								<td><a href="manual/help.php"><?php echo _HELP?>
								</a></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<table class="jss-Menu" style="width: 100%">
							<tr class="jss-Bar">
								<td class="jss-Up"></td>
								<td class="jss-Bar"><?php echo _SYSTEM.' '._TABLES?>
								</td>
							</tr>
							<tr>
								<td>
								<img alt="" height="16" src="../images/Configure16.gif"></td>
								<td><a href="home/home.php"><?php echo _SETTINGS?>
								</a></td>
							</tr>
							<tr>
								<td>
								<img alt="" src="../images/users16.png" style="width: 16px; height: 16px">
								</td>
								<td><a href="agents/search.php"><?php echo _AGENTS?>
								</a></td>
							</tr>
							<tr>
								<td>
								<img alt="" src="../images/customers.gif" style="width: 16px; height: 16px">
								</td>
								<td><a href="clients/search.php"><?php echo _CUSTOMERS?>
								</a></td>
							</tr>
							<tr>
								<td>
								<img alt="" src="../images/familys16.gif" style="width: 16px; height: 16px">
								</td>
								<td><a href="products/familys.php"><?php echo _FAMILYS?>
								</a></td>
							</tr>
							<tr>
								<td>
								<img alt="" src="../images/product16.gif" style="width: 16px; height: 16px">
								</td>
								<td><a href="products/search.php"><?php echo _PRODUCTS?>
								</a></td>
							</tr>
							<tr>
								<td>
								<img alt="" src="../images/carry16.gif" style="width: 16px; height: 16px">
								</td>
								<td><a href="stores/search.php"><?php echo _STORES?>
								</a></td>
							</tr>
							<tr>
								<td>
								<img alt="" height="16" src="../images/form16.gif">
								</td>
								<td><a href="suppliers/search.php"><?php echo _SUPPLIERS?>
								</a></td>
							</tr>
							<tr>
								<td>
								<img alt="" height="16" src="../images/form16.gif">
								</td>
								<td><a href="leddger/search.php"><?php echo _ACCOUNT._._LEDGER?>
								</a></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<table class="jss-Menu" style="width: 100%">
							<tr class="jss-Bar">
								<td class="jss-Up">&nbsp;</td>
								<td class="jss-Bar"><?php echo _REPORTS?></td>
							</tr>
							<tr>
								<td>
								<img alt="" height="16" src="../images/report16.png">
								</td>
								<td><a href="reports/accumulates.php?report=Items"><?php echo _ACCUMULATED._._ITEM?>
								</a></td>
							</tr>
							<tr>
								<td>
								<img alt="" height="16" src="../images/report16.png">
								</td>
								<td>
								<a href="reports/accumulates.php?report=payType">
								<?php echo _ACCUMULATED._._PAY._._TYPE?></a>
								</td>
							</tr>
							<tr>
								<td>
								<img alt="" height="16" src="../images/report16.png">
								</td>
								<td>
								<a href="reports/accumulates.php?report=pos"><?php echo _ACCUMULATED._._POS?>
								</a></td>
							</tr>
							<tr>
								<td>
								<img alt="" src="../images/accounting.gif" style="width: 16px; height: 16px">
								</td>
								<td>
								<a href="reports/accumulates.php?report=vat"><?php echo _ACCUMULATED._._VAT?>
								</a></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="height: 15px">
						<table class="jss-Menu" style="width: 100%">
							<tr class="jss-Bar">
								<td class="jss-Up">&nbsp;</td>
								<td class="jss-Bar"><?php echo _QUERYS?></td>
							</tr>
							<tr>
								<td>
								<img alt="" height="16" src="../images/report16.gif">
								</td>
								<td>
								<select class="CampoTexto" name="lista" onchange="javascript: showReport(this);" size="1" style="width: 99%">
								<?php echo tableList('view','q') ;?>
								<option selected="" value="-">-</option>
								</select></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<table class="jss-Menu" style="width: 100%">
							<tr class="jss-Bar">
								<td class="jss-Up"></td>
								<td><?php echo _SYSTEM.' '._INFO?></td>
							</tr>
							<tr>
								<td>
								<img alt="" src="../images/backup.gif" style="width: 16px; height: 16px"></td>
								<td><a href="home/backup.php">Make Backup</a></td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;<?php echo _SERVER.': '.$_SERVER["HTTP_HOST"];?></td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;Domain: <?php echo $_SESSION['domain']; ?>
								</td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td style="width: 50%; text-align: center;">
						<img alt="" class="jss-Cursor" onclick="document.location.replace('../login/login.php');" src="../images/logout.png" style="width: 22px;"></td>
						<td style="width: 50%">
						<img alt="" class="jss-Cursor" onclick="return imprimir_onclick()" src="../images/pr16.gif" style="width: 22px"> 
						Print --&gt;</td>
					</tr>
					<tr>
						<td colspan="2"><a href="main.php">
						<img alt="logo" src="<?php echo $strImg ?>" style="width: 100%;"></a></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
		<td style="width: 99%; vertical-align: top;">
		<iframe id="iMain" name="iMain" scrolling="auto" src="main.php" style="overflow: auto; margin: -2px; border-width: 0px; width: 100%; height: 100%" target="_self">
		</iframe></td>
	</tr>
</table>

</body>

</html>
