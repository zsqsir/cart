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
$rs=$olddb->query("select * from goodsall order by quantity desc limit 5000");
foreach ($rs->rows as $row) {
    $product_id = $row['id'];
    echo '<p>'.$product_id;
    $sql = "select product_id from " . DB_PREFIX ."product where product_id=$product_id";
    if($newdb->query($sql)->num_rows)
    {
        echo '<p>have!'.PHP_EOL;
        continue;
    }

    $sql = "INSERT INTO " . DB_PREFIX . "product SET product_id=$product_id, model = '" .$row['des'].  "', sku = '" .  "', upc = '" . "', ean = '" . "', jan = '" .  "', isbn = '" . $row['isbn'] . "', mpn = '"  . "', location = '" . $row['locate'] . "', quantity = '" . $row['quantity'] . "', minimum = '" . "', subtract = '"  . "', stock_status_id = '" .  "', date_available = '" .  "', manufacturer_id = '" .  "', shipping = '" .  "', price = '" . $row['price'] . "', points = '" .  "', weight = '" . $row['weight'] . "', weight_class_id = '" .  "', length = '" . $row['length'] . "', width = '" . $row['width'] . "', height = '" . $row['thickness'] . "', length_class_id = '" . "', status = '1" . "', tax_class_id = '" . "', sort_order = '" .  "', date_added = NOW()";
    dealsql($sql);

    for ($i = 1; $i <= 3; $i++)
    {
        $sql = "INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '$i" . "', name = '" . $row['des'] . "', description = '" . $row['brief'] . "', tag = '" . $row['des'] . "', meta_title = '" . $row['writer'] . "', meta_description = '" . $row['area'] . "', meta_keyword = '" . $row['series'] . "'";
        dealsql($sql);
    }

    $store_id = 0;
    $sql = "INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'";
    dealsql($sql);

    $sql = "INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '4" . "', language_id = '1"  . "', text = '11"  . "'";
    dealsql($sql);

    $sql = "INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '10" .  "', required = '" . "'";
    dealsql($sql);

    $product_option_id = $newdb->getLastId();
    $sql = "INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '1" . "', option_value_id = '1" . "', quantity = '" . (int)$row['quantity'] . "', subtract = '1" .  "', price = '" . (float)$row['price'] . "', price_prefix = '+" .  "', points = '0" . "', points_prefix = '+" .  "', weight = '" . (float)$row['weight'] . "', weight_prefix = '+" .  "'";
    dealsql($sql);

    $sql = "INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '1" .  "', quantity = '30" . "', priority = '1" . "', price = '" . (float)$row['price'] . "', date_start = '" .  "', date_end = '" .  "'";
    dealsql($sql);

    $cid = substr($row['dkind'],1);
    $cid = str_replace('.00','',$cid);
    $cid = str_replace('.','',$cid);
    $sql = "INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '$cid" . "'";
    dealsql($sql);

    $sql = "INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '0" . "', layout_id = '0" .  "'";
    dealsql($sql);

    $sql = "INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = 'product-" . (int)$product_id . ".html'";
    dealsql($sql);


}

function dealsql($sql)
{
    $sql = iconv('GBK','UTF-8',$sql);
    echo '<p>'.$sql;
    global $newdb;
    echo $newdb->query($sql);

}


