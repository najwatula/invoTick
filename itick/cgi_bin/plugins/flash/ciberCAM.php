<?php
	if (!isset($_SESSION)) {session_start();}
	if (isset($_REQUEST['keyMaster'])) {$keyMaster=$_REQUEST['keyMaster'];}
	if (isset($_REQUEST['id'])) {$id=$_REQUEST['id'];}
	if (isset($_REQUEST['table'])) {$table=$_REQUEST['table'];}

if (isset($_FILES["file"])) {
	require("../../phpFun.php");
	jCnn();
 	  //resizeImage($_FILES["file"]["tmp_name"],260,300);
   $imgFile=file_get_contents($_FILES["file"]["tmp_name"]);
   
    $exist=jGet("select idImage from images where keyName='$keyMaster' and id=$id limit 1");
    if($exist){
	    $strsql="update [images] set [image]=:img where idImage=$exist";
	    $results = $GLOBALS['db']->prepare($strsql);
	}
	else{
	    $strsql="insert into [images] ([tableName], [keyName], [id], [image]) values('$table', '$keyMaster', $id, :img)";
	    $results = $GLOBALS['db']->prepare($strsql);
	}

	$results->bindParam(':img', $imgFile, PDO::PARAM_LOB);
	$results->execute();
}

	$strImg='../../phpFun.php?getImg=base64&strsql='.
	base64_encode("SELECT [image] from [images] WHERE [tableName]='$table' and [id] = $id limit 1");
?>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<link href="../../jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../jss/jss.js" type="text/javascript"></script>
<!-- First, include the JPEGCam JavaScript Library -->
<script src="webcam.js" type="text/javascript"></script>
<title>CAM Capture</title>
<script type="text/javascript">
var imgRefresh;
jss.Init=function(){
	
	<!-- Configure a few settings -->
	webcam.set_api_url('uploadCAM.php<?php echo "?table=$table&keyMaster=$keyMaster&id=$id"?>');
	webcam.set_quality( 90 ); // JPEG quality (1 - 100)
	webcam.set_shutter_sound( false ); // play shutter click sound

	<!-- Code to handle the server response (see test.php) -->
	webcam.set_hook( 'onComplete', 'my_completion_handler' );
	showImage();
}

function showVideo(){
    var bVideo=document.getElementById('bVideo');
    if (bVideo.value=='VIDEO-ON'){
        bVideo.value='VIDEO-OFF';
        document.getElementById('video').innerHTML=webcam.get_html(260, 300);
    }else{
        bVideo.value='VIDEO-ON';
        webcam.reset();
        document.getElementById('video').innerHTML='';
    }
}

function showImage(){
    var image='<img alt="" src="<?php echo $strImg?>" onload="javascript: resize(this,260,300)">';
    document.getElementById('video').innerHTML=image;
}

function take_snapshot() {
	// take snapshot and upload to server
	document.getElementById('infoVideo').innerHTML = 'Uploading...';
	if(imgRefresh){setTimeout("imgRefresh.src=imgRefresh.src+'&rnd=5'",1000);}
	webcam.snap();
}

function my_completion_handler(msg) {
    webcam.reset();
    document.getElementById('infoVideo').innerHTML=msg;
    showVideo();
	showImage();
    webcam.reset();	
}

</script>
</head>

<body class="jss-Body" style="vertical-align: middle; text-align: center">

<table class="jss-TableBorder" style="margin: auto">
	<tr>
		<td class="jss-Bar" colspan="3" style="text-align: center">CAM CAPTURE</td>
	</tr>
	<tr>
		<td id="video" colspan="3" style="text-align: center; height: 304px;">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td style="text-align: center; width: 33%;">
		<input class="jss-Boton" name="bConfig" onclick="javascript: webcam.configure();" type="button" value="CONFIGURE"></td>
		<td style="text-align: center; width: 33%;">
		<input id="bVideo" class="jss-Boton" name="bVideo" onclick="javascript: showVideo();" type="button" value="VIDEO-ON"></td>
		<td style="text-align: center; width: 33%;">
		<input class="jss-Boton" name="bCapture" onclick="javascript: take_snapshot();" type="button" value="PHOTO"></td>
	</tr>
	<tr>
		<td style="text-align: right">UPLOAD FILE:</td>
		<td colspan="2">
		<form id="fLogo" class="jss-NoMargins" enctype="multipart/form-data" method="post" name="fLogo">
			<input id="file" class="jss-FieldAuto" name="file" onchange="javascript: this.form.submit();" size="5" type="file">
		</form>
		</td>
	</tr>
	<tr>
		<td id="datos" colspan="3">
		<table class="jss-Table" style="width: 100%">
			<tr>
				<td class="jss-Bar" style="text-align: center;">STATUS</td>
			</tr>
			<tr>
				<td id="infoVideo" style="text-align: center; height: 20px;">
				</td>
			</tr>
			<tr>
				<td id="infoError" style="text-align: center; height: 20px;">
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>

</body>

</html>
