<?php
//**********************************************
// Establece la Conexion con la Base de Datos
//**********************************************
$_SESSION['domain']='update';
require("../../../cgi_bin/phpFun.php");
	
$dbDomains=dirList('../db/domains/*.db3');	
foreach($dbDomains as $db){
    echo $db.'<br>'.realpath('../db/bak/').'/'.$db.'<hr>';
    rename (realpath('../db/domains/').'/'.$db, realpath('../../db/bak/').'/'.$db);
    copy (realpath('master.db3'), realpath('../db/domains/').'/'.$db);
    $path='sqlite:'.realpath('../db/bak/'.$db);
    $origen =new PDO($path);
    $origen->exec("ATTACH '../db/domains/$db' as destino");

	$strsql="select name from main.sqlite_master where type='table'";
    $tables=$origen->query($strsql);
    while ($table =$tables->fetch()) {
        $fields= getFields($origen ,$table['name']);
        if($fields>'0'){
            $strsql='INSERT INTO destino.'.$table['name'].' ('.$fields.') SELECT '.$fields.' FROM main.'.$table['name'];
            $result=$origen->exec($strsql);    
            echo $table['name'].'<br>'.$strsql.'<br>';var_dump($result);echo '<br>';
        }   
    }
}

function getFields($db, $table){
    $fieldsOrigen = getColumnNames($db, $table, 'main');
    $fieldsDestino= getColumnNames($db, $table, 'destino');
    if(isset($fieldsOrigen) && isset($fieldsDestino)){
        $valRet = array_intersect($fieldsOrigen , $fieldsDestino );
        return implode(",", $valRet );
    }
    return '';
}

function getColumnNames($db, $table, $dbName) {
    $results = $db->query("PRAGMA $dbName.table_info('$table')");
    $columns = array();
    foreach($results as $result) {
        $columns[] = strtolower($result['name']);
    }
    sort($columns);
    return $columns;
}

?>