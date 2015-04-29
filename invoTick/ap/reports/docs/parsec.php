<?php
	if (isset($_REQUEST['domain'])){
        if (!isset($_SESSION)) {session_start();}
	    $_SESSION['domain']=$_REQUEST['domain'];
    	$_SESSION['home']=realpath('../../../db/domains');
	}

	require("../../../../cgi_bin/phpFun.php");
	require("../../languages/language.php");				
	if(!jCnn()){exit;}
?>
<?php
//-------------------------------------------------
// Parameters
//-------------------------------------------------
	$id='0';
	if (isset($_REQUEST['id'])){$id=$_REQUEST['id'];}
	$idKey='id';
	if (isset($_REQUEST['idKey'])){$idKey=$_REQUEST['idKey'];}
	$idModel='0';
	if (isset($_REQUEST['idModel'])){$idModel=$_REQUEST['idModel'];}

//-------------------------------------------------
// Parsec
//-------------------------------------------------
$results = $GLOBALS['db']->query("select content from docsModel where idModel=$idModel");
if($results){$results=$results->fetch();}
else{echo ' idKey:'.$idKey.', id:'.$id.', idModel:'.$idModel; exit;}
$doc=$results['content'];
if(left($doc,8)=='REDIRECT'){header('Location: ../../'.substr($doc,9)); exit;}

// busca las tablas declaradas
//preg_match_all('/([a-zA-Z0-9]+)-&gt;([a-zA-Z0-9]+)/',$doc, $matches);
preg_match_all('/([a-zA-Z]+)-&gt;([a-zA-Z]+)/',$doc, $matches);

//print_r($matches[0]);echo '<br>';
//print_r($matches[1]); echo '<br>';

if(count($matches[1])){
	$tables=array_unique($matches[1]);
	$matches[1]=array_fill(0,count($matches[1]),'');
	foreach($tables as $table){
		//abre la tabla existente
		$strsql="select * from $table";
		if($id>'0')$strsql=$strsql." where $idKey=$id";
		if($table=='home'){$strsql='select * from home limit 1';}
		if($table=='images'){$strsql="select image from images where keyName='$idKey' and id=$id limit 1";}
		$results = @$GLOBALS['db']->query($strsql);
		// recoge los valores
		if ($results){
			while($rows = $results ->fetch()){
				for ($col=0; $col< $results->columnCount(); $col++) {
					$meta = $results->getColumnMeta($col);
					$qKey=array_search($table.'-&gt;'.$meta['name'], $matches[0]);
					if($qKey!==false){
						if($matches[1][$qKey]){$matches[1][$qKey]=$matches[1][$qKey].'<br>';}
						if($meta['name'] =='image'){$matches[1][$qKey]=$matches[1][$qKey].base64_encode($rows[$col]);}
						elseif($meta['native_type']=='double'){$matches[1][$qKey]=$matches[1][$qKey].number_format($rows[$col],2,',','.');}
						else{$matches[1][$qKey]=$matches[1][$qKey].$rows[$col];}
					}
				}
			}
		}
	}
	
	//for ($n= 0; $n<count($matches[0]); $n++) {
	uasort($matches[0],'mySort');
	foreach($matches[0] as $key => $val){
		if($matches[1][$key]>''){$doc=str_replace($val, $matches[1][$key], $doc);}
    else {$doc=str_replace($val, '-', $doc);}
	}
	unset($matches); unset($tables); unset($results);
}

// pdf ---------------------------------------
if (isset($_REQUEST['retPdf'])){
    $frmt='<meta content="text/html; charset=UTF-8" http-equiv="Content-Type"><meta content="es" http-equiv="Content-Language">';
    if ($_SERVER['HTTP_HOST'] != 'localhost'){
        $doc=str_replace('http://localhost/php','http://'.$_SERVER['HTTP_HOST'], $doc);
    }
    chdir('../../../../cgi_bin');
    // wkhtmltopdf
	$fName=genCode(4);
	file_put_contents('tmp/'.$fName.'.html', $frmt.$doc);
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	    $resVal = exec('wkhtmltopdf.exe --zoom 1 --enable-plugins "tmp/'.$fName.'.html" tmp/'.$fName.'.pdf');
	}
	else{
	    $resVal = exec('./wkhtmltopdf --zoom 1 --enable-plugins "tmp/'.$fName.'.html"  tmp/'.$fName.'.pdf');
	}
    sleep(1);
	if (file_exists('tmp/'.$fName.'.pdf')){
		$strPDF = file_get_contents('tmp/'.$fName.'.pdf');
		unlink('tmp/'.$fName.'.pdf');
		unlink('tmp/'.$fName.'.html');
		header('Content-Type: application/pdf');
		header('Content-Length: '.strlen($strPDF ));
		header('Content-Disposition: inline; filename="'.$fName.'"');
		header('Cache-Control: private, max-age=0, must-revalidate');
		header('Pragma: public');
		ini_set('zlib.output_compression','0');
		echo $strPDF;
		exit;
	}
    
    // dompdf
    require_once("dompdf/dompdf_config.inc.php");
    $dompdf = new DOMPDF();
    $dompdf->load_html($doc);
    unset($doc);
    $dompdf->render();
    $dompdf->stream(str_pad($id,6,'0',STR_PAD_LEFT).'.pdf',array('Attachment'=>0));
    unset($dompdf); 
}
else{echo $doc;}
exit;

function mySort($a,$b){
	return strlen($a)<strlen($b);
}
?>
