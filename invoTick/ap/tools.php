<?
  session_start();
  session_register("app_session");
  session_register("database_session");
?>
<? 
function Formatea()
{
  extract($GLOBALS);

//strSQL="update personas set nombre=ccase(nombre), apellidos=ccase(apellidos)"
//strCnn.execute strSQL
//say strSQL

  $strSQL="update empresas set r_social=ucase(r_social)";
  $strCnn->execute$strSQL;
  $say$strSQL;

  $strSQL="update empresas set [c_postal]='00' where [c_postal]<'0'";
  $strCnn->execute$strSQL;
  $say$strSQL;

  return $function_ret;
} 

function paraFoto($idPersona)
{
  extract($GLOBALS);

  $pFoto="select foto_jpg from [".$_SESSION['app']."] where evento='".$_SESSION['app']."' and idpersona=".$idPersona;
  $foto="../../../../cgi_bin/getImg.asp?database=".rawurlencode($cnnFotos);
  $foto=$foto."&strSQL=".rawurlencode($pFoto);
  $function_ret=$foto;
  return $function_ret;
} 
?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<!--#include file="../cnnFotos.php"-->
<!--#include file="../../../cgi_bin/AspFun.php"-->
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<title>Tools</title>
<link href="../../estilos.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/Scripts/jsFun.js" type="text/javascript"></script>
<script language="javascript">
function ejecuta(){
	if (window.strSQL.value >'.'){
		document.getElementById('resultado').innerHTML=loadExecute('../../../cgi_bin/exec.asp?strSQL='+urlEncode(window.strSQL.value));
	}
}
</script>
</head>

<body class="Cuerpo">

<table class="Contenedor">
	<tr>
		<td class="Cabecera" colspan="2">Ejecutar una instrucción SQL </td>
	</tr>
	<tr>
		<td><? echo $_SESSION['database'];?></td>
		<td align="right">
		<img alt="" class="Cursor" height="16" onclick="javascript: ejecuta();" src="../../images/Database16.bmp" width="16"></td>
	</tr>
	<tr>
		<td colspan="2">
		<textarea id="strSQL" class="CampoLista" cols="20" name="strSQL" rows="6"></textarea></td>
	</tr>
	<tr>
		<td colspan="2" id="resultado">
		&nbsp;</td>
	</tr>
	<tr>
		<td class="Cabecera" colspan="2">Eliminar Fotos de Fichas no Existentes</td>
	</tr>
	<tr>
		<td><? echo $cnnFotos;?></td>
		<td align="right">
		<img alt="" class="Cursor" onclick="javascript: document.location.search='?eliminaFotos=1'" src="../../images/info16.gif">
		<img alt="" class="Cursor" onclick="javascript: document.location.search='?eliminaFotos=2'" src="../../images/Delete16.gif"></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><? 
if (${"eliminaFotos"}>"0")
{

//-------------------------------------------------
// Establece la Conexion con la Base de Datos
//-------------------------------------------------
  // $strCnnF is of type "ADODB.Connection"
  // $rstF is of type "ADODB.Recordset"

    echo 5;
    echo 5;
    echo "SQLOLEDB";
  $a2p_connstr=$cnnFotos;
  $a2p_uid=strstr($a2p_connstr,'uid');
  $a2p_uid=substr($d,strpos($d,'=')+1,strpos($d,';')-strpos($d,'=')-1);
  $a2p_pwd=strstr($a2p_connstr,'pwd');
  $a2p_pwd=substr($d,strpos($d,'=')+1,strpos($d,';')-strpos($d,'=')-1);
  $a2p_database=strstr($a2p_connstr,'dsn');
  $a2p_database=substr($d,strpos($d,'=')+1,strpos($d,';')-strpos($d,'=')-1);
  $strCnnF=mysql_connect("localhost",$a2p_uid,$a2p_pwd);
  mysql_select_db($a2p_database,$strCnnF);
  mysql_query("SET DATEFORMAT DMY",$strCnnF);
  $strSQL="select * from [".$_SESSION['app']."] where evento='".$_SESSION['app']."'";
  $rs=mysql_query($strSQL);
  while(!($rstF==0))
  {

    if ($busca["select id from personas where id=".("idPersona")]<"0")
    {

      print "<img border=\"0\" height=\"142\" src=\"".paraFoto(("idPersona"))."\" width=\"125\" alt=\"".("idPersona")."\">  ";
      if (${"eliminaFotos"}>"1")
      {
        
      } 
    } 

    $rstF=mysql_fetch_array($rstF_query);

  } 
} 

?>
		&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" class="Cabecera">Marcar los que tienen foto (Imprimir=1)</td>
	</tr>
	<tr>
		<td><? echo $_SESSION['database'];?></td>
		<td align="right">
		<img alt="" class="Cursor" height="16" onclick="javascript: document.location.search='?marcaFotos=1'"" src="../../images/Database16.bmp" width="16"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;<? 
if (${"marcaFotos"}>"0")
{

//-------------------------------------------------
// Establece la Conexion con la Base de Datos
//-------------------------------------------------
  // $strCnnF is of type "ADODB.Connection"
  // $rstF is of type "ADODB.Recordset"

    echo 5;
    echo 5;
    echo "SQLOLEDB";
  $a2p_connstr=$cnnFotos;
  $a2p_uid=strstr($a2p_connstr,'uid');
  $a2p_uid=substr($d,strpos($d,'=')+1,strpos($d,';')-strpos($d,'=')-1);
  $a2p_pwd=strstr($a2p_connstr,'pwd');
  $a2p_pwd=substr($d,strpos($d,'=')+1,strpos($d,';')-strpos($d,'=')-1);
  $a2p_database=strstr($a2p_connstr,'dsn');
  $a2p_database=substr($d,strpos($d,'=')+1,strpos($d,';')-strpos($d,'=')-1);
  $strCnnF=mysql_connect("localhost",$a2p_uid,$a2p_pwd);
  mysql_select_db($a2p_database,$strCnnF);
  mysql_query("SET DATEFORMAT DMY",$strCnnF);
  $strSQL="update personas set imprimir='0'";
  $strCnn->execute$strSQL;

  $strSQL="select * from [".$_SESSION['app']."] where evento='".$_SESSION['app']."'";
  $rs=mysql_query($strSQL);
  while(!($rstF==0))
  {

    print ("idPersona").", ";
    $strSQL="update personas set imprimir='1' where id=".("idPersona");
    $strCnn->execute$strSQL;
    $rstF=mysql_fetch_array($rstF_query);

  } 
} 

?>
</td>
	</tr>
	<tr>
		<td colspan="2" class="Cabecera">Formatear contenidos, capitalizar 
		nombres, mayúsculas empresa</td>
	</tr>
	<tr>
		<td><? echo $_SESSION['database'];?></td>
		<td align="right">
		<img alt="" class="Cursor" height="16" onclick="javascript: document.location.search='?Formatear=1'" src="../../images/Database16.bmp" width="16"></td>
	</tr>
	<tr>
		<td colspan="2"><? if (${"Formatear"}=="1")
{
  Formatea();
} ?></td>
	</tr>
</table>

</body>

</html>

