<?php
// by Jupiter & Najwa & Tula (2012/01/01 15:00)
//***********************************************
$url='';
$margins='-B 6 -L 6 -R 6 -T 6';
$orientation=' -O Portrait';

// dompdf --------------------------------------
if (isset($_REQUEST['dompdf'])){
    //chdir( $_SERVER['DOCUMENT_ROOT']);
    require_once("dompdf/dompdf_config.inc.php");
    $dompdf = new DOMPDF();
    $dompdf->load_html(base64_decode($_REQUEST['dompdf']));
    $dompdf->render();
    $dompdf->stream(genCode(8).'.pdf',array('Attachment'=>0));
    unset($dompdf);
    exit;
}

// parametros -------------------------------------
if (isset($_REQUEST['url'])){$url=$_REQUEST['url'];}
if (isset($_REQUEST["orientation"])){$orientation=' -O '.$_REQUEST["orientation"];}

if (isset($_REQUEST['toHTML'])){
	file_put_contents('tmp/tmp.html', $_REQUEST['toHTML']);
	$url='tmp/tmp.html';
}

if (isset($_REQUEST["margins"])){
	$margins=$_REQUEST["margins"];
	$margins='-B '.$margins.' -L '.$margins.' -R '.$margins.' -T '.$margins;
}

$s='?';
foreach($_REQUEST as $nombre => $valor) { 
    if(!($nombre == 'url' || $nombre == 'margins' || $nombre == 'toHTML')) {
       $url= $url.$s.$nombre.'='.$valor;
       $s='&';
    }
}

// ---------------------------------------------
$fName=genCode(4).'.pdf';
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $resVal = exec('wkhtmltopdf.exe --zoom 1 --enable-plugins '.$margins.$orientation.' "'.$url.'" tmp/'.$fName);
}
else{
    $resVal = exec('./wkhtmltopdf --zoom 1 --enable-plugins '.$margins.$orientation.' "'.$url.'" tmp/'.$fName);
}
sleep(1);
if (file_exists('tmp/'.$fName)){
	$strPDF = file_get_contents('tmp/'.$fName);
	header('Content-Type: application/pdf');
	header('Content-Length: '.strlen($strPDF ));
	header('Content-Disposition: inline; filename="'.$fName.'"');
	header('Cache-Control: private, max-age=0, must-revalidate');
	header('Pragma: public');
	ini_set('zlib.output_compression','0');
	echo $strPDF;
	unlink('tmp/'.$fName);
	exit;
}

else{header('Location: '.$url);}

function genCode($q){
    $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $cad = "";
    for($i = 0; $i < $q; $i++) {$cad = $cad.substr($str,rand(0,62),1);}
    return $cad;
}

?>
