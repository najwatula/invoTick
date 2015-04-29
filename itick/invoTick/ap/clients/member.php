<script src="../../../cgi_bin/qrCode/qrcode.js" type="text/javascript"></script>
<script type="text/javascript">
function putFmt(){
    var objDate=document.getElementsByName("dateOfBirth")[0];
    objDate.className ="jss-DatePicker";
    jss.ParsecDatePicker();
    objDate.style.width="100%";
    
    var objcb=document.getElementsByName("barCode")[0];
    var objTarget=objcb.parentNode;
    var objBtn='<table style="width: 100%; border-collapse: collapse"><tr><td>'+objTarget.innerHTML+'</td>';
    objBtn+='<td><img src="../../images/code16.gif" class="jss-Cursor" onclick="getCode();"></td></tr></table>';
    objTarget.innerHTML=objBtn;
}

function getCode(){
    var target=document.getElementsByName("barCode")[0];
    if(target.value<'0'){
        target.value=genCode(12);
        if(loadAjax('../../../cgi_bin/phpFun.asp?jsGet=select barCode from members where barCode="'+target.value+'"')==target.value){
            target.value=genCode(12);
        }
    }

    for(var tipo=4; tipo<11; tipo++){
	    try{
	        var qr = qrcode(tipo,'M');
	        qr.addData(target.value);
	        qr.make();
	        break;
	    }
	    catch(err){}
	}
	
	wWin= new window.parent.jss.Window({
	    title:'QRCode',
	    style:{width:180, height: 180},
	    html: qr.createImgTag(6-parseInt(tipo/2),0)
	})
}
</script>

<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");
	jCnn();
//-------------------------------------------------
// Parametros Member
//-------------------------------------------------
$table="members";
$keyMaster="idMember";
$buscaPor='surName';
//-------------------------------------------------
//el registro quiere ser eliminado
//-------------------------------------------------
if (isset($_REQUEST["eliminar"])){
	$strsql='delete from members where idMember='.$_SESSION['idMember'];
	$results = $GLOBALS['db']->exec($strsql);
    $_SESSION['idMember']=jGet('select idMember from members where idCompany='.$_SESSION['idCompany'].' LIMIT 1');
    unset($_REQUEST["eliminar"]);
    header("Location: ".$_SERVER['PHP_SELF']);
	exit;
}

//-------------------------------------------------
//recoge el puntero
//-------------------------------------------------

if (isset($_REQUEST["id"])){$_SESSION['idMember']=$_REQUEST["id"];}
if($_SESSION['idMember']<'0'){$_SESSION['idMember']='0';}
   
$script='putFmt();';

require('../tables/tableImg.php');

if (isset($_POST["save"]) || isset($insert)){
    $strsql='UPDATE members SET idCompany='.$_SESSION['idCompany'].' WHERE idMember='.$_SESSION['idMember'];
	$results=$GLOBALS['db']->exec($strsql);
}

?>

