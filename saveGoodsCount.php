<?php
/**
 * 商品开始抢购之前将商品的数量存入一个队列中，抢购开始时就不再调用该脚本
 */
require "HelperRedis.php";

$redis = HelperRedis::getRedisConn();
// 商品数量
$num = 10;
for ($i = 1; $i <= $num ;$i++){
    $redis->lPush('goodsList',$i);
}
