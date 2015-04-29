<?php
// by Jupiter & Najwa & Tula (2012/01/01 15:00)
//***********************************************
$printer='0'; $data='0'; $url=''; $status='0'; $timeOut=10; $ip='0';

if (isset($_REQUEST['printer'])){
	$printer=$_REQUEST['printer'];
}

if (isset($_REQUEST["data"])){
	$data=$_REQUEST["data"];
    $fp = cnn($printer);	
    
    if($fp) {
        fwrite($fp,"$data\r\n");
        if (!feof($fp)) {echo fgets($fp, 1);}
        fclose($fp);   
	}
   	else{echo '(NO CONNECT)<br>';}	    
}

if (isset($_REQUEST['url'])){
	$url=$_REQUEST['url'];
	$data = file_get_contents($url);
    $fp = cnn($printer);	

    if($fp) {
        fwrite($fp,"$data\r\n");
        if (!feof($fp)) {echo fgets($fp, 1);}
        fclose($fp);   
	}
   	else{echo '(NO CONNECT)<br>';}
}

if (isset($_REQUEST['search'])){
	if(right($_REQUEST['search'],3)=='/24'){
		set_time_limit (60);
		$netWork=strLeftLast($_REQUEST['search'],'.');
    $lastNod=substr($_REQUEST['search'],strlen($netWork)+1,strlen($_REQUEST['search'])-strlen($netWork)-4);
		for ($n=$lastNod; $n < 256; $n++){
        	$fp = cnn("$netWork.$n");
        	echo "tcp://$netWork.$n / ";
 	    	if($fp) {fclose($fp);  echo '(ON LINE)<br>';}
 	    	else{echo '(NO CONNECT)<br>';}
 	    	unset ($fp);
 	    }
 	}else{
 	    $netWork=($_REQUEST['search']);
        $fp = cnn($netWork);
        //echo "$netWork / ";
	    if($fp) {fclose($fp); echo '(ON LINE)<br>';}
    	else{echo '(NO CONNECT)<br>';}
	}
}

function cnn ($ip) {
	$ip=strLeftLast($ip,':');
	$fp = @stream_socket_client("tcp://$ip:9100", $errno, $errstr, 0.5);
    unset ($errno); unset ($errstr);
    //echo memory_get_usage()."<br>";
    return $fp; 
}
function strLeftLast($qString, $sString){
	$p=strripos($qString, $sString);
	if (!$p) {return $qString;}
	return substr($qString,0,$p);
}
function right($string,$count){
	return substr($string, strlen($string)-$count,$count); 
}

?>
