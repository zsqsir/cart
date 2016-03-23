<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16-3-20
 * Time: 上午8:53
 */
$a = array(22,8,9);
print_r($a);
for(
    $i=$sum=0, $cnt = count($a);
    $i<$cnt;
    $sum += $a[$i++], print $sum.'<br>'
);
echo $sum;
