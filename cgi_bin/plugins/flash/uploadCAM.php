<?php
	require('../../phpFun.php');
	if (isset($_REQUEST['keyMaster'])) {$keyMaster=$_REQUEST['keyMaster'];}
	if (isset($_REQUEST['id'])) {$id=$_REQUEST['id'];}
	if (isset($_REQUEST['table'])) {$table=$_REQUEST['table'];}
	
	jCnn();

//-------------------------------------------------
//images
//-------------------------------------------------

	$imgFile=file_get_contents('php://input');
    
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
	//if (!$results){$err=$GLOBALS['db']->errorInfo(); echo $err[2].'<br>';}
	//echo $strsql;
?>
