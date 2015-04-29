<?php
if (!isset($_SESSION)) session_start();

$file = '../../db/domains/'.$_SESSION['domain'].'.db3';
$zipname = $_SESSION['domain'].'.zip';

$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);
$zip->addFile($file,$_SESSION['domain'].'.db3');
$zip->close();

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename='.$zipname);
header('Content-Length: ' . filesize($zipname));
readfile($zipname);
?>