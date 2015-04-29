<?php
	if (isset($_REQUEST['domain'])){
        if (!isset($_SESSION)) {session_start();}
	    $_SESSION['domain']=$_REQUEST['domain'];
    	$_SESSION['home']=realpath('../../db/domains');
	}

	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");		
	jCnn();
?>

<?php
$table='';	
if (isset($_REQUEST['table'])){$table=$_REQUEST['table'];}
if (isset($_REQUEST['title'])){$title=$_REQUEST['title'];}
if (isset($_REQUEST['strsql'])){$strsql=$_REQUEST['strsql'];}

if (!isset($title)){$title=$table;}
if (!isset($strsql)){$strsql="select * from [".$table."]";}

$strExport='../../../cgi_bin/phpFun.php?toXls='.base64_encode($strsql);
?>

<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<title>Info_Auto-MIX</title>

<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<base target="_self">

<script type="text/javascript">
var datos;
jss.Init=function(){
	var strsql='<?php echo base64_encode($strsql) ?>';
	var datos=loadAjax('../../../cgi_bin/phpFun.php?jsgetArray=base64&strsql='+strsql).evalDecode();
	if (typeof datos!='object'){document.getElementById('grid-pos').innerHTML=datos; return;};	

	drawGrid('<?php echo $title ?>', 'grid-pos', datos);
	<?php if (!isset($_REQUEST['noChar'])){?>
	drawChart('<?php echo $title ?>', 'chart-pos', datos);
	<?php }?>
	//document.getElementById('grid-pos').innerHTML=datos;
}

function drawGrid(titulo, sitio, datos){
	new jss.Grid({
		totals: true,
		renderTo:(sitio),
		values: datos['values'],
		columns: datos['columns']
	})
}

function drawChart(titulo, sitio, datos){
	//**********************************************
	// Tipo de Grafico
	//**********************************************
	if(datos['values'].length>20 && datos['columns'].length>2){return};	
	tipoChart="columnChart";
	if(datos['values'].length>12){tipoChart="barChart";};
	
	//document.getElementById(sitio).style.height=(alto)+'px';
	
	//**********************************************
	// Graficos
	//**********************************************
    var nCols=datos['values'][0].length-1;
    var nItems= datos['values'].length;
    var maxVal=0, minVal=100000;
    for (var i=0; i < nItems; i++){
        var valCom=parseInt(datos['values'][i][nCols]);
        if(valCom>maxVal){maxVal=valCom;}
        if(valCom<minVal){minVal=valCom;}     
    }
	
     if (tipoChart=='barChart'){
        
        var cons='<table class="jss-Table" style="width: 100%;">';
        for (var i=0; i < datos['values'].length; i++){
            var ancho=parseInt(datos['values'][i][nCols])*90/maxVal;
            var p=0; if(isNumeric(datos['values'][i][0])){p=1;}            
            cons=cons+'<tr><td style="width: 10%">'+datos['values'][i][p]+'</td><td>'+
            '<table class ="jss-Table" style="width: 100%; height: 18pt;"><tr><td class="jss-Blur" style="width: '+ parseInt(ancho+1) +'%; background: Navy"></td>'+
            '<td>'+datos['values'][i][nCols]+'</td></tr></table></tr>';
        }
        cons =cons+'</table>';
        document.getElementById(sitio).innerHTML=cons;
    }
    
   if (tipoChart=='columnChart'){
        var cons='<table class="jss-TableBorder" style="width: 100%; background-image: url(../../images/lines.png);"><tr>';
        for (var i=0; i < datos['values'].length; i++){
            var ancho=parseInt(datos['values'][i][nCols])*90/maxVal;
            
            cons=cons+'<td style="text-align: center"><table class ="jss-Table" style="width: 20pt; height: 120pt; margin: auto; background-color: transparent">'+
            '<tr><td style="text-align: center; vertical-align: bottom;">'+datos['values'][i][nCols]+'</td></tr>'+
            '<tr><td class="jss-Blur" style=" height: '+ parseInt(ancho+1) +'%; background: DarkRed;"></td></tr></table>'+
            '</td>';
        }
        cons=cons+'</tr><tr style="height: 18pt">';
        for (var i=0; i < datos['values'].length; i++){
            var p=0; if(isNumeric(datos['values'][i][0])){p=1;}
            cons=cons+'<td style="text-align: center; width: 10%">'+datos['values'][i][p]+'</td>';
        }
        
        cons =cons+'</tr></table>';
        document.getElementById(sitio).innerHTML=cons;
    }
    
    if (tipoChart=='pieChart'){
	}

    if (tipoChart=='lineChart'){
	}		
	
}

</script>
</head>

<body class="jss-Body">

<table class="jss-Table" style="width: 100%">
	<tr>
		<td id="titulo" class="jss-Caption" style="width: 99%">&nbsp;<?php echo $title.' '.date('d/M/Y') ?></td>
		<td id="titulo" class="jss-Caption" style="width: 1%">
			<a href="<?php echo $strExport;?>" style="width: 1%">
			<img alt="" src="../../images/excel16.jpg" style="border-width: 0px; width: 16px; height: 16px"></a></td>
	</tr>
	<tr>
		<td id="grid-pos" colspan="2"></td>
	</tr>
	<tr>
		<td id="chart-pos" colspan="2"></td>
	</tr>
</table>

</body>

</html>
