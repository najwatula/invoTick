<html>
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<title>Print</title>
</head>
<body style="font-family: 'Courier New';" >
<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");
	jCnn();
	$id='0'; $printer='192.168.3.100:9100';
	if (isset($_REQUEST["id"])){$id=$_REQUEST["id"];}
	if (isset($_REQUEST["printer"])){$printer=$_REQUEST["printer"];}
	
	$strsql="select * from [home] limit 1";
    $defaults = $GLOBALS['db']->query($strsql);
    $defaults=$defaults->fetch();
    
    $ticket='CIF: '.$defaults['vat']."\r\n".
    "\x1BE\x01".$defaults['company']."\x1BE\x00"."\r\n". 
    $defaults['address']."\r\n".  
    $defaults['zipCode'].' '.$defaults['city']."\r\n". 
    str_repeat("-", 40)."\r\n";
    
    $strsql='select * from invoClients where idInvoice='.$id;
    $defaults = $GLOBALS['db']->query($strsql);
    $defaults=$defaults->fetch();
       
    $base=number_format($defaults['base'],2,',','.');
    $vatAmount=number_format($defaults['vatAmount'],2,',','.');
    $total=number_format($defaults['total'],2,',','.');
    $ticket=$ticket.str_pad('Ticket: '.$defaults['serie'].'/'.$defaults['number'],20).str_pad('CIF/NIF: '.$defaults['cif'],20)."\r\n".  
    str_pad('Fecha: '.$defaults['date'],20).left(str_pad($defaults['company'],20),20)."\r\n".  
    str_repeat(" ", 20).str_pad($defaults['address'],20)."\r\n".
    str_repeat(" ", 20).str_pad($defaults['zipCode'].' '.$defaults['city'],20)."\r\n".
    str_repeat("-", 40)."\r\n".
    'Can|Producto        |Preci|Des|Iva|Suma '."\r\n".
    str_repeat("-", 40)."\r\n";
    
    $strsql='select * from invoLines where idInvoice='.$id;
    $defaults = $GLOBALS['db']->query($strsql);
	while($row = $defaults->fetch()){
        $line=right('   '.$row['quantity'],3).' '.
        left(str_pad($row['concept'],16),16).' '.
        right('     '.number_format($row['price'],2,',','.'),5).' '.
        right('   '.$row['discount'],3).' '.
        right('   '.$row['vat'],3).' '.
        right('     '.number_format($row['total'],2,',','.'),5)."\r\n";
        $ticket=$ticket.$line;
    }
    
    $ticket=$ticket.str_repeat("-", 40)."\r\n".
    '%  |Base     |Iva    |  Base: '.str_pad($base,10,' ',STR_PAD_LEFT)."\r\n";
    $strsql='select * from invoSum where idInvoice='.$id;
    $defaults = $GLOBALS['db']->query($strsql);
    $n=0;
	while($row = $defaults->fetch()){
        $n++;
        $line=right('   '.$row['vat'],3).'|'.
        right('     '.number_format($row['base'],2,',','.'),9).'|'.
        right('     '.number_format($row['vatAmount'],2,',','.'),7).'|';
        if($n==1){$line=$line.'   Iva: '.str_pad($vatAmount,10,' ',STR_PAD_LEFT);}
        if($n==2){$line=$line.' Total: '.str_pad($total,10,' ',STR_PAD_LEFT);}    
        $line=$line."\r\n";
        $ticket=$ticket.$line;
    }    
    if($n==1){$ticket=$ticket.'                     | Total: '.str_pad($total,10,' ',STR_PAD_LEFT);}   
    
    $show = str_replace("\r\n",'<br>',$ticket);
    echo str_replace(" ",'&nbsp',$show.'<br><br>');

    $fp = stream_socket_client("tcp://$printer", $errno, $errstr, 10);
    if (!$fp) {
        echo "$errstr ($errno)";
    } else {
        fwrite($fp, "$ticket\r\n\r\n\r\n\r\n");
        if (!feof($fp)) { echo fgets($fp, 1);}
        fclose($fp);
    }
?>
</body>
</html>