<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<title>Main</title>
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<base target="_self">
<script type="text/javascript">

jss.Init=function(){
    document.getElementById('importe').innerHTML=window.parent.document.getElementById('total').innerHTML;
}
function suma(v){
    var importe=document.getElementById('importe').innerHTML;
    var entregado=document.getElementById('entregado').innerHTML;
    var devolver=document.getElementById('devolver');
    if(v=='del'){document.getElementById('entregado').innerHTML=entregado.substr(0,entregado.length-1);}  
    else{document.getElementById('entregado').innerHTML=document.getElementById('entregado').innerHTML+v;}
    
    var entregado=document.getElementById('entregado').innerHTML;
    var total=parseFloat(entregado)-parseFloat(importe);
    devolver.innerHTML=total.toFix(2);
}
</script>
</head>

<body class="jss-Body">

<table id="table2" style="width: 100%; height: 100%; font-family: 'Courier New'; font-size: 18pt; font-weight: bold; font-style: italic; color: #EDEFC7;">
	<tr>
		<td class="jss-Caption" colspan="2">Importe a cobrar</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: center; height: 12%; background-color: #000000;" id="importe">0</td>
	</tr>
	<tr>
		<td class="jss-Caption" style="width: 50%">Entregado</td>
		<td class="jss-Caption">A devolver</td>
	</tr>
	<tr>
		<td style="text-align: center; height: 12%; background-color: #000000;" id="entregado"></td>
		<td style="text-align: center; height: 12%; background-color: #000000;" id="devolver">0</td>
	</tr>
	<tr>
		<td colspan="2">

<table id="tBotones" style="width: 100%; height: 100%; ">
	<tr>
		<td style="text-align: center; width: 33%; height: 10%;">
		<input name="1" style="font-size: 18pt; font-weight: bold; width: 100%; height: 100%;" type="button" value="1" onclick="javascript: suma(this.value);"></td>
		<td style="text-align: center; width: 33%;">
		<input name="2" style="font-size: 18pt; font-weight: bold; width: 100%; height: 100%;" type="button" value="2" onclick="javascript: suma(this.value);"></td>
		<td style="text-align: center; width: 33%;">
		<input name="3" style="font-size: 18pt; font-weight: bold; width: 100%; height: 100%;" type="button" value="3" onclick="javascript: suma(this.value);"></td>
	</tr>
	<tr>
		<td style="text-align: center; height: 10%;">
		<input name="4" style="font-size: 18pt; font-weight: bold; width: 100%; height: 100%;" type="button" value="4" onclick="javascript: suma(this.value);"></td>
		<td style="text-align: center;">
		<input name="5" style="font-size: 18pt; font-weight: bold; width: 100%; height: 100%;" type="button" value="5" onclick="javascript: suma(this.value);"></td>
		<td style="text-align: center;">
		<input name="6" style="font-size: 18pt; font-weight: bold; width: 100%; height: 100%;" type="button" value="6" onclick="javascript: suma(this.value);"></td>
	</tr>
	<tr>
		<td style="text-align: center; height: 10%;">
		<input name="7" style="font-size: 18pt; font-weight: bold; width: 100%; height: 100%;" type="button" value="7" onclick="javascript: suma(this.value);"></td>
		<td style="text-align: center;">
		<input name="8" style="font-size: 18pt; font-weight: bold; width: 100%; height: 100%;" type="button" value="8" onclick="javascript: suma(this.value);"></td>
		<td style="text-align: center;">
		<input name="9" style="font-size: 18pt; font-weight: bold; width: 100%; height: 100%;" type="button" value="9" onclick="javascript: suma(this.value);"></td>
	</tr>
	<tr>
		<td style="text-align: center; height: 10%;">
		<input name="&lt;-" style="font-size: 18pt; font-weight: bold; width: 100%; height: 100%; " type="button" onclick="javascript: suma('del');" value="&lt;-"></td>
		<td style="text-align: center">
		<input name="0" style="font-size: 18pt; font-weight: bold; width: 100%; height: 100%;" type="button" value="0" onclick="javascript: suma(this.value);"></td>
		<td style="text-align: center">
		<input name="." style="font-size: 18pt; font-weight: bold; width: 100%; height: 100%;" type="button" value="." onclick="javascript: suma(this.value);"></td>
	</tr>
</table>

		</td>
	</tr>
	</table>

</body>

</html>
