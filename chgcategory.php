<?php
//require_once 'DetectEncoding.php';
//echo detect_encoding('再根据一些非utf8编码的情');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'xy');
define('DB_PORT', '3306');
define('DB_PREFIX', 'mcc_');

// DB2
define('TDB_DRIVER', 'mysqli');
define('TDB_HOSTNAME', '192.168.1.83');
define('TDB_USERNAME', 'zlf');
define('TDB_PASSWORD', '');
define('TDB_DATABASE', 'a');
define('TDB_PORT', '3306');
define('TDB_PREFIX', '');
//var_dump(get_defined_constants());
require_once 'system/library/db/mysqli.php';
$newdb = new DB\MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$olddb = new DB\MySQLi(TDB_HOSTNAME, TDB_USERNAME, TDB_PASSWORD, TDB_DATABASE);
$rs=$olddb->query("select * from dkind order by id limit 3000");
$nids = array();
foreach ($rs->rows as $row) {
    $id = $row['id'];
    echo '<p>'.$id;
    $nid = str_replace('.00','',$id);
    echo '<p>'.$nid;
    $nid = substr($nid,1);
    $nid = str_replace('.','',$nid);
    echo '<p>'.$nid;
    if(in_array($nid,$nids))
        continue;
    $pid = substr($nid,0,-2);
    $sql = "update dkind set nid=$nid,pid=$pid where id='$id' ";
    echo '<p>'.$sql;
    $olddb->query($sql);
    $nids[] = $nid;


    if(strlen($nid)==2)
    {
        $pid = 0;
    }
    elseif(strlen($nid)==5)
    {
        $pid = substr($nid,0,3);
    }


}


