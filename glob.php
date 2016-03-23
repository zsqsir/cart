
<?php
$files = glob('/opt/lampp/htdocs/cart/system/storage/cache/' . 'cache.*');
foreach ($files as $filename) {
    echo "$filename size " . filesize($filename) . "<br>\n";
    echo time();

}
?>
