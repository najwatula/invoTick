<?php
    if (!isset($_SESSION)) session_start();
    $_SESSION['domain']='newAccount';
?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<title>new Account</title>
<base target="_top">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<script type="text/javascript">
jss.Init=function(){}
function putDomain(){
    var email=document.getElementById('email').value;
    document.getElementById('domain').value=email.replace(/[^A-Za-z0-9]/g, '');
}

function goFun(qFun){
    document.getElementById('msgResponse').innerHTML='';
    document.getElementById('imgWait').style.display='';
    setTimeout(qFun,10);
}

function send(){
    var msgMail=document.getElementById('msgMail').value;
    var from=document.getElementById('emailContacto').value;
	var result=loadAjax('../../../cgi_bin/phpFun.php?sendMail='+encodeURIComponent(msgMail.btoa())+'&from='+encodeURIComponent(from));

	if (result=='true'){msg='El mensaje ha sido enviado con exito, en breve nos pondremos en contacto.<br>Muchas gracias.'}
	else{msg=result;}
	document.getElementById('msgResponse').innerHTML=msg;
    document.getElementById('imgWait').style.display='none';	
}

function check(){
    var email=document.getElementById('email').value;
    var domain=document.getElementById('domain').value;
    var captcha=document.getElementById('captcha').value;
    
    var chk=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (! chk.test(email)){
        document.getElementById('imgWait').style.display='none';    
        document.getElementById('msgResponse').innerHTML='El email no es valido.';
        return;
    }
    
    var chk=/^[0-9a-zA-Z]+$/;
    if(! domain.match(chk)){
        document.getElementById('imgWait').style.display='none';        
        document.getElementById('msgResponse').innerHTML='El nombre de Dominio solo puede contener letras y n�meros.';
        return;
    }
    
    if(captcha<'0' || !isNumeric(captcha)){
        document.getElementById('imgWait').style.display='none';        
        document.getElementById('msgResponse').innerHTML='Debe introducir el codigo de validaci�n.';
        return;
    }
    
	var result=loadAjax('create.php?email='+encodeURIComponent(email)+'&domain='+encodeURIComponent(domain)+'&captcha='+encodeURIComponent(captcha));
	document.getElementById('msgResponse').innerHTML=result;
    document.getElementById('imgWait').style.display='none';
}
</script>
</head>

<body class="Jss-Body">

<table style="width: 100%; height: 100%">
	<tr>
		<td style="text-align: center; vertical-align: middle">
		<table id="contenedor" class="jss-TableFrame" style="width: 90%;">
			<tr style="background-color: #FFFFFF">
				<td style="text-align: center; height: 1%;">
				<a href="../../../index.php">
				<img alt="" src="../../images/tikaltok.png" style="border-width: 0px; width: 525px; height: 170px;"></a></td>
			</tr>
			<tr style="background-color: #FFFFFF">
				<td style="text-align: justify;">
				<table id="tRegister"  class="jss-Table" style="width: 100%">
					<tr>
						<td style="text-align: justify;" colspan="2">Puede crear una cuenta de pruebas 
				totalmente funcional, sin compromiso y gratuita.<br>Introduzca 
				el email donde recibira la contrase�a inicial para acceder que 
				luego podra cambiar.</td>
					</tr>
					<tr>
						<td style="text-align: right;" colspan="2">Si lo prefiere puede contactar con 
				nosotros.</td>
					</tr>
					<tr>
						<td>
					<table id="createAccount" class="jss-TableBorder" style="width: 100%;">
						<tr>
							<td class="jss-Bar" colspan="2">&nbsp;Crear una cuenta nueva de 
							pruebas:</td>
						</tr>
						<tr>
							<td colspan="2">E-Mail:</td>
						</tr>
						<tr>
							<td colspan="2">
							<input class="jss-FieldAuto" maxlength="50" size="50" id="email" onchange="javascript: putDomain();"></td>
						</tr>
						<tr>
							<td colspan="2">Nombre para mi dominio:</td>
						</tr>
						<tr>
							<td colspan="2">
							<input class="jss-FieldAuto" maxlength="20" size="20" id="domain"></td>
						</tr>
						<tr>
							<td id="canvasCaptcha" rowspan="2" style="height: 74px; width: 50%; text-align: center;">
							<img alt="captcha" src="captcha.php" ></td>
							<td style="vertical-align: bottom; width: 50%;">
							Codigo de validaci�n:</td>
						</tr>
						<tr>
							<td style="vertical-align: top;">
							<input class="jss-FieldAuto" maxlength="15" name="captcha" size="15" id="captcha"></td>
						</tr>
						<tr>
							<td style="text-align: center; vertical-align: bottom;" colspan="2">
							<input class="jss-Boton" name="Check" type="button" value="Solicitar" onclick="javascript: goFun('check()');"></td>
						</tr>
						</table>
						</td>
						<td>
				<table id="tMessage" class="jss-TableBorder" style="width: 100%;">
					<tr>
						<td class="jss-Bar">&nbsp;Enviar mensaje:</td>
					</tr>
					<tr>
						<td>
						<textarea class="jss-FieldAuto" cols="50" name="msgMail" rows="7" id="msgMail"></textarea>
						</td>
					</tr>
					<tr>
						<td>
						Mi E-Mail de contacto:</td>
					</tr>
					<tr>
						<td>
							<input class="jss-FieldAuto" maxlength="50" size="50" id="emailContacto"></td>
					</tr>
					<tr>
						<td style="text-align: center;">
						<input class="jss-Boton" name="sender" type="button" value="Enviar" onclick="javascript: goFun('send()');"></td>
					</tr>
					</table>
						</td>
					</tr>
				</table>
				</td>
			</tr>
			<tr style="background-color: #FFFFFF">
				<td style="text-align: center; height: 50px;">
					<img id="imgWait" alt="" src="../../images/ajax-loader.gif" style="width: 48px; height: 48px; display: none;"></td>
			</tr>
			<tr style="background-color: #FFFFFF">
				<td style="text-align: center; font-size: small; color: #800000; font-weight: bold; height: 50px;" id="msgResponse">
					&nbsp;</td>
			</tr>
			<tr style="background-color: #FFFFFF">
				<td style="text-align: center; height: 1%;">
				</td>
			</tr>
			</table>
		</td>
	</tr>
</table>

</body>

</html>
