<?php
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION['domain'])){header('Location: newAccount.php'); exit;}	
    if (!isset($_SESSION['captcha'])){header('Location: newAccount.php'); exit;}
    	
    if (!isset($_REQUEST['captcha'])){header('Location: newAccount.php'); exit;}	
    if (!isset($_REQUEST['email'])){header('Location: newAccount.php'); exit;}	
    if (!isset($_REQUEST['domain'])){header('Location: newAccount.php'); exit;}	

	require("../../../cgi_bin/phpFun.php");
	require("../../ap/languages/language.php");
	
	
	if($_REQUEST['captcha']!=$_SESSION['captcha']){echo 'El codigo de validación no es valido.'; exit;}
	$requestedDomain=$_REQUEST['domain'];
	$domain=filter_var($requestedDomain, FILTER_SANITIZE_SPECIAL_CHARS);
	if ($domain!=$requestedDomain){echo 'El nombre de Dominio solo puede contener letras y números.'; exit;}
	
	//comprueba si el dominio existe;
	if(file_exists("../../db/domains/$domain.db3")){echo 'El nombre de Dominio no se puede establecer.<br> Por favor intentelo con otro.'; exit;}
	
	copy('../../db/domains/master.db3',"../../db/domains/$domain.db3");
	if(file_exists("../../db/domains/$domain.db3")){
	    echo "Su dominio ha sido creado con exito.<br>";
	    
	    $msg="Hemos creado una cuenta para su acceso a tikaltok, http://www.tikaltok.com,\r\n\r\n".
	    "Sus datos de acceso son los siguientes: ($domain\admin)\r\n\r\n".
	    "Nombre de dominio: $domain\r\n".
	    "Nombre de usuario: admin\r\n".
	    "Contraseña: admin\r\n\r\n".
	    "Recuerde cambiar la contraseña despues del primer acceso en el usuario admin, en la tabla de Agentes.\r\n".
	    "Aquí podra encontar el manual que le ayudara en el establecimiento de los parametros inciales y configuración general del sistema: http://www.tikaltok.com/invoTick/ap/manual/es.html\r\n\r\n".
	    "tikaltok.\r\n";
	    
	    $results=sendMail(utf8_decode($msg),$_REQUEST['email']);
	    if($results){echo 'Hemos enviado un email a su cuenta con las instrucciones de acceso.';}
	}
?>