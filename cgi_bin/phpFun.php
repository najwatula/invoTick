<?php
// by Jupiter & Najwa & Tula (2014 v1.1)
//***********************************************
if (!isset($_SESSION)) {session_start();}
if (!isset($_SESSION['domain'])){echo "ND"; exit;}
//if (isset($_REQUEST['domain'])) {$_SESSION['domain']=base64_decode($_REQUEST['domain']);}

// GETIMG
//**********************************************
if (isset($_REQUEST['getImg'])) {
    $strsql=$_REQUEST['strsql'];
    if($_REQUEST['getImg']=='base64'){$strsql=base64_decode($strsql);}
  	jCnn();
  	header('Content-type: image/jpeg');
	echo jGet($strsql);
	exit;
}

// SAVEFORM
//**********************************************
if (isset($_REQUEST['saveForm'])) {
    $strsql=$_REQUEST['strsql'];
    if($_REQUEST['saveForm']=='base64'){$strsql=base64_decode($strsql);}
	jCnn();
	echo saveForm($_REQUEST['table'],$_REQUEST['idKey'],$_REQUEST['key']);
	exit;
}
// JSGETARRAY
//**********************************************
if (isset($_REQUEST['jsgetArray'])){
    $strsql=$_REQUEST['strsql'];
    $jsFun=$_REQUEST['jsgetArray'];
    if($jsFun=='base64' || $jsFun=='gz') {$strsql=base64_decode($strsql);}
    if($jsFun=='gz'){ $strsql = gzuncompress($strsql); }
	jCnn();
	echo jsgetArray($strsql);
	exit;
}
// RUNSQL
//**********************************************
if (isset($_REQUEST['runSQL'])){
    $strsql=$_REQUEST['strsql'];
    if($_REQUEST['runSQL']=='base64'){$strsql=base64_decode($strsql);}
    jCnn();
	$results=$GLOBALS['db']->exec($strsql);
	if (!$results){$err=$GLOBALS['db']->errorInfo(); echo '<hr>'.$strsql.'<hr>Error: '.$err[2]; exit;}  
	if (isset($_REQUEST['lastId'])){echo $GLOBALS['db']->lastInsertId();}
	else{echo $strsql;}
	exit;
}
//JGET
//**********************************************
if (isset($_REQUEST['jGet'])){
	jCnn();
	echo jGet($_REQUEST['jGet']);
	exit;
}
// NUMINVOICE
//**********************************************
if (isset($_REQUEST['numInvoice'])){
	jCnn();
	$strsql="update invoiceSeries set number=number+1 where serie='".$_REQUEST['serie']."'";
	$results=$GLOBALS['db']->exec($strsql);
	$strsql="select number from invoiceSeries where serie='".$_REQUEST['serie']."'";
	$results=$GLOBALS['db']->query($strsql);
	if($results){
	    $results=$results->fetch();	
	    echo $results['number'];
	}
	exit;
}
// DIRLIST
//**********************************************
if (isset($_REQUEST['dirList'])){
	$dirList=dirList($_REQUEST['dirList']);
	//print_r($dirList);
	if(count($dirList)>0) {echo putOptions($dirList,'-');}
	exit;	
}
// TOXLS
//**********************************************
if (isset($_REQUEST['toXls'])) {
	jCnn();
	$strsql=base64_decode($_REQUEST['toXls']);
	$results = $GLOBALS['db']->query($strsql);
	//header
	$valRet='<table border=1><tr>';	
	for ($col = 0; $col<$results->columnCount(); $col++) {
	    $meta = $results->getColumnMeta($col);
		$valRet=$valRet.'<th>'.$meta['name'].'</th>';
	}
	
	//content
	$valRet=$valRet.'</tr>';
	while($row = $results->fetch() ){
		$valRet=$valRet.'<tr>';
		for ($col = 0; $col<$results->columnCount(); $col++) {
			$meta = $results->getColumnMeta($col);
			if($meta['name']!='image'){$valRet=$valRet.'<td>'.utf8_decode($row[$col]).'</td>';}
		}
		$valRet=$valRet.'</tr>';
	}
	$valRet=$valRet.'</table>';
	
	header("Content-type: application/vnd.ms-excel; name='excel'");
	header("Content-Disposition: filename=export.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	echo $valRet;
	exit;	
}

// SENDMAIL
//**********************************************
if (isset($_REQUEST['sendMail'])) {
    $to='';
    $subject='mail';
	$msg='';
	$msgFromUrl='';
    $attach='';
    
    $from='';
    $fromName='';
    $user='';
    $port='587';
    $password='';
    $serverSmtp='localhost';
    
    if (isset($_REQUEST['to'])) {$to=base64_decode($_REQUEST['to']);}
    if (isset($_REQUEST['subject'])) {$subject=base64_decode($_REQUEST['subject']);}
 	if (isset($_REQUEST['msg'])) {$msg=base64_decode($_REQUEST['msg']);}
    if (isset($_REQUEST['msgFromUrl'])) {$msgFromUrl=base64_decode($_REQUEST['msgFromUrl']);}
    if (isset($_REQUEST['attach'])) {$attach=base64_decode($_REQUEST['attach']);}

 	if (isset($_REQUEST['from'])) {$from=base64_decode($_REQUEST['from']);}
    if (isset($_REQUEST['fromName'])) {$fromName=base64_decode($_REQUEST['fromName']);}
    if (isset($_REQUEST['user'])) {$user=base64_decode($_REQUEST['user']);}
 	if (isset($_REQUEST['port'])) {$port=base64_decode($_REQUEST['port']);}
    if (isset($_REQUEST['password'])) {$password=base64_decode($_REQUEST['password']);}
    if (isset($_REQUEST['serverSmtp'])) {$serverSmtp=base64_decode($_REQUEST['serverSmtp']);}

	echo sendMail($to, $subject, $msg, $msgFromUrl, $attach, $from, $fromName, $user, $port, $password, $serverSmtp);
	exit;
}

function sendMail($to, $subject, $msg, $msgFromUrl, $attach, $from, $fromName, $user, $port, $password, $serverSmtp){
    
    if (! filter_var($from, FILTER_VALIDATE_EMAIL)) {
        return "La dirección de E-mail no es valida. / ".$from;
        exit;
    }
    
    if ($attach>'0'){$attachData=file_get_contents($attach);}
    if ($msgFromUrl>'0'){$msg=file_get_contents($msgFromUrl);}
    require_once("PHPMailer/PHPMailerAutoload.php");
    
    $mail = new PHPMailer();
    $mail->IsSMTP(true);
    $mail->Host = $serverSmtp;
    $mail->Port = intval($port);
    $mail->SMTPAuth = true;
    if($mail->Port>25){$mail->SMTPSecure = 'tls';}
    $mail->Username = $user;
    $mail->Password = $password;
    $mail->SetFrom($from, $fromName);
    $mail->AddAddress($to);
    $mail->SMTPDebug = 0;
    $mail->CharSet = 'UTF-8';
    $mail->IsHTML(true);
    $mail->Subject = $subject;
    $mail->MsgHTML($msg);
    
    //if($attach>'0'){$mail->AddAttachment($attach);}
    if($attach>'0'){
        parse_str($attach);   
        $fName=str_replace(' ','_',$subject).'_'.$id;
        $mail->AddStringAttachment($attachData, $fName.'.pdf', $encoding = 'base64', $type = 'application/pdf');
    }
    
    if(!$mail->Send()) {
        return 'ERROR: ' . $mail->ErrorInfo.' / FROM:'.$from.' / TO:'.$to;
    } else {
        return 'MENSAJE ENVIADO';
    }
    exit;
}

//**********************************************
// Establece la Conexion con la Base de Datos
//**********************************************
function jCnn(){
    if (isset($GLOBALS['db'])){return true;}
    $path=$_SESSION['home'].'/'.$_SESSION['domain'].'.db3';
    if (!file_exists($path)){return false;}    
    //try {$GLOBALS['db']= new PDO('sqlite:'.$path,NULL, NULL, array(PDO::ATTR_PERSISTENT => TRUE));}
    try {$GLOBALS['db']= new PDO('sqlite:'.$path, NULL, NULL);}
    catch(Exception $e) {echo $e->getMessage(); return false;}
	return true;
}
//**********************************************
// Desconecta la Base de Datos
//**********************************************
function descnn(){
	if (is_object($GLOBALS)){$GLOBALS['db']=null;}
	return true;
}
//**********************************************
// Coge un Valor
//**********************************************
function jGet($strsql){
	$results=$GLOBALS['db']->query($strsql);
	if($results){return $results->fetchColumn();}
	return;
}
//**********************************************
// Cuenta Registros
//**********************************************
function jCount($tbl,$flt){
	if ($flt>" "){ $strsql="select count(*) as valor from ".$tbl." where ".$flt;}
	else{$strsql="select count(*) as valor from ".$tbl;}
	$results = $GLOBALS['db']->query($strsql);
  if($results){return $results->fetchColumn();}
	return 0;
}
//**********************************************
// Suma Registros
//**********************************************
function jSum($tbl,$flt,$fld){
	$strsql="select sum(".$fld.") as valor from ".$tbl;
	if ($flt>" "){$strsql=$strsql." where ".$flt;}
	$results = $GLOBALS['db']->query($strsql);
  if($results){return $results->fetchColumn();}  
	return 0;
}

//*********************************************
// Salva Datos
//*********************************************
function saveForm ($tableName, $clave, $valClave){
	if($valClave=='0'){
		$strsql="INSERT INTO [$tableName] ($clave) VALUES(NULL)";
		$GLOBALS['db']->exec($strsql);
		$valClave = $GLOBALS['db']->lastInsertId();
		$_SESSION['id']=$valClave;
	}
	if( $valClave<'1'){ return '0';}
	$strsql="SELECT * FROM [".$tableName.'] LIMIT 1';
	$results = $GLOBALS['db']->query($strsql)->fetch();
	$results =array_keys($results);
	$strsql='';
	foreach($results as $colName){	
		if (isset($_REQUEST[$colName]) && $colName != $clave){
			//$strsql=$strsql." [$colName]='".str_replace("'","''",$_REQUEST[$colName])."',";
			$strsql=$strsql." [$colName]=:$colName,";
		}
	}	
	if(strlen($strsql)>2){
		$strsql=substr($strsql,0,strlen($strsql)-1);
		$strsql="UPDATE [$tableName] SET $strsql WHERE $clave=$valClave";
		$prepare = $GLOBALS['db']->prepare($strsql);
		foreach($results as $colName){
			if (isset($_REQUEST[$colName]) && $colName != $clave){
				$prepare->bindparam(":$colName",$_REQUEST[$colName]);
			}
		}
		$prepare->execute();
	}
	return $valClave;	
}
//*********************************************
// Relacion de Vistas
//*********************************************
function tableList($qTipos,$qdef){
	$strsql="select name from sqlite_master where type='view'";
	if ($qTipos=='all'){$strsql.=" or type='table'";}
	$strsql.=" order by name";
    $tables= @$GLOBALS['db']->query($strsql);   
    if (!$tables) {return;}
    $valRet='';
    while ($table =$tables->fetch()) {
    	if(substr($table['name'],0,1)==$qdef || $qdef<'0'){
	    	if (strtolower($table['name'])==strtolower($qdef)){$valRet="$valRet<option selected>";}
			else{$valRet="$valRet<option>";}
		    $valRet=$valRet.$table['name'].'</option>';
		}
    }    
    return $valRet;
}
function sqlite_table_exists(&$sqlite, $table){
   $result = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
   return $result->numRows() > 0;
}
//*********************************************
// Relación de Campos
//*********************************************
function fieldList ($strsql, $qdef){
	$valRet='';
 	$results = $GLOBALS['db']->query($strsql);
	if (!$results){$err=$GLOBALS['db']->errorInfo(); return "<option>".$err[2].'</option>';}

	for ($col = 0; $col < $results->columnCount(); $col++) {
	  $meta = $results->getColumnMeta($col);
    if(strtolower($meta["name"])==strtolower($qdef)){$valRet=$valRet.'<option selected>'.$meta["name"]."</option>\r\n";}
		else{$valRet=$valRet.'<option>'.$meta["name"]."</option>\r\n";}
	}
	return $valRet;
}
//*********************************************
// String Manipulate
//*********************************************
function left($string,$count){
	return substr($string,0,$count);
}
function right($string,$count){
	return substr($string, strlen($string)-$count,$count); 
}
function offLast($qString){
	return substr($qString,0,strlen($qString)-1);
}
function strLeft($qString, $sString){
	$p=strpos($qString, $sString);
	if ($p == false) {return '';}
	return substr($qString,0,$p);
}
function strRight($qString, $sString){
	$p=strpos($qString, $sString);
	if ($p == false) {return '';}
	return substr($qString,$p+1);
}
function strMid($str1,$str2,$str3){
	$strMid = strRight($str1,$str2);
	return strLeft($strMid,$str3);
}

function jsDate($d){
    return strftime('%Y-%m-%d',strtotime($d));
}

function jsTime($d){
    return strftime('%H:%M:%S',strtotime($d));
}

function jsDateTime($d){
    return strftime('%Y-%m-%d %H:%M:%S',strtotime($d));
}

function secondsToTime($seconds) {
    $h = floor($seconds / 3600);
    $m = floor(($seconds % 3600) / 60);
    $s = $seconds - ($h * 3600) - ($m * 60);
    return sprintf('%02d:%02d:%02d', $h, $m, $s);
} 

function strToSeconds($str) {
    $time=jsTime($str);
    list($h, $m, $s) = explode(':', $time);
    return ($h * 3600) + ($m * 60) + $s;
}

function jsCrypt($mode, $str){
	$key='@0993*';
	if($mode==0){
		$valRet = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5($key), $str, MCRYPT_MODE_CBC, md5(md5($key))));
	}
	else{
		$valRet = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, md5($key), base64_decode($str), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	}
	return $valRet ;
}
//**********************************************
// Pone Valores para un Combo
//**********************************************
function putOptions($strsql,$qdefault){
	$valRet='';
	if(!$strsql){return;}
	if(is_array($strsql)){
		$nCols=count($strsql[0]);
		for($n=0;$n<count($strsql);$n++){
	    	if (strtolower($strsql[$n])==strtolower($qdefault)){$valSel=' selected ';}
	    	else{$valSel='';}
			if($nCols>1) {$valRet=$valRet.'<option'.$valSel.' value="'.$strsql[$n][0].'">'.$strsql[$n][1].'</option>';}
			else{$valRet=$valRet.'<option'.$valSel.'>'.$strsql[$n].'</option>';}
		}
	}
	else{
		$results = $GLOBALS['db']->query($strsql);
    	if (!$results){$err=$GLOBALS['db']->errorInfo(); return "<option>$strsql Error: ".$err[2].'</option>';}
	
		$nCols = $results->columnCount();
	    while($gv = $results->fetch()) {
	    	if (strtolower($gv[0])==strtolower($qdefault)){$valSel=' selected ';}
	    	else{$valSel='';}
			if($nCols>1) {$valRet=$valRet.'<option'.$valSel.' value="'.$gv[0].'">'.$gv[1].'</option>';}
			else{$valRet=$valRet.'<option'.$valSel.'>'.$gv[0].'</option>';}
		}
	}
	return $valRet;
} 
//**********************************************
// Array de datos
//**********************************************
function jsgetArray($strsql){
	$columns=''; $types='';	$values='';
	
	$results = $GLOBALS['db']->query($strsql);
	if (!$results){$err=$GLOBALS['db']->errorInfo(); return "<hr>$strsql<hr>Error: ".$err[2];}
	
	for ($col = 0; $col < $results->columnCount(); $col++) {
	    $meta = $results->getColumnMeta($col);
		$columns=$columns."'".$meta['name']."',";
		$types=$types."'".$meta['native_type']."',";
	}
		
	while($row = $results->fetch()){
		$values=$values.'[';
		for ($col = 0; $col < $results->columnCount(); $col++) {
			$meta = $results->getColumnMeta($col);
			if($meta['native_type'] =='string' && $meta['name']!='image'){$values="$values'".base64_encode(utf8_decode($row[$col]))."',";}
			else{$values="$values'".base64_encode($row[$col])."',";}
		}
		$values=offLast($values).'],';
	}
	
	if(strlen($types) < 4){$types="'',";}
	if(strlen($values) < 4){$values='['.str_repeat('\'LQ==\',',$results->columnCount()).'],';}
	$columns='columns:['.offLast($columns).']';
	$types='types:['.offLast($types).']';
	$values='values:['.offLast($values).']';
	$values=preg_replace("/\r?\n/", "\\n",$values);
	return '{'."$columns,$types,$values".'}';
} 
//**********************************************
// Directory List
//**********************************************
function dirList($directory){
    $results = array();
    $dir=glob($directory);
    if($dir){
	    foreach($dir as $file){$results[] = basename($file);}
	}
    return $results;
}

function getUrl($rel){
	$base ="http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
    if(strpos($rel,"//")===0){return "http:".$rel;}

    if  (parse_url($rel, PHP_URL_SCHEME) != ''){ return $rel;}
    if ($rel[0]=='#'  || $rel[0]=='?'){ return $base.$rel;}
    extract(parse_url($base));
    if(!$host){return $rel;}
    $path = preg_replace('#/[^/]*$#',  '', $path);
    if ($rel[0] ==  '/') {$path = '';}
    $abs =  "$host$path/$rel";
    $re =  array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
    for($n=1; $n>0;  $abs=preg_replace($re, '/', $abs, -1, $n)) {}
    return  $scheme.'://'.$abs;
 }

function genCode($q){
    $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $cad = "";
    for($i = 0; $i < $q; $i++) {$cad = $cad.substr($str,rand(0,62),1);}
    return $cad;
}

function resizeImage($filename, $newwidth, $newheight){
    list($width, $height, $type) = getimagesize($filename);
    if($width > $height && $newheight < $height){
        $newheight = $height / ($width / $newwidth);
    } else if ($width < $height && $newwidth < $width) {
        $newwidth = $width / ($height / $newheight);    
    } else {
        $newwidth = $width;
        $newheight = $height;
    }
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    switch($type){
        case "1": $source = imagecreatefromgif($filename); break;
        case "2": $source = imagecreatefromjpeg($filename);break;
        case "3": $source = imagecreatefrompng($filename); break;
        default:  $source = imagecreatefromjpeg($filename);
    }    
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return imagepng($thumb, $filename);
}
?>