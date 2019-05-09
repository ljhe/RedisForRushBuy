<?php
/**
 * lPop 移出并获取列表的第一个元素,如果当前列表没有元素,则返回false
 * === 判断返回值是0还是false
 * $result = false 或者 $result = 0 的时候
 * if 判断 $result == false 都会返回真
 */
require "HelperRedis.php";
require "SaveLog.php";

$redis = HelperRedis::getRedisConn();
//$redis->del('result','goodsList');exit;
// 商品数量
//var_dump($redis->lLen('goodsList'));
// 模拟用户唯一标识
$userId = rand(0,30);
// 判断某用户是否已经抢购成功
if ($redis->sIsMember('result',$userId)) {
    $text = '用户' . $userId . '：已经抢到，请勿多次抢购' . PHP_EOL;
}else{
    $goodsId = $redis->lPop('goodsList');
    if ($goodsId !== false) {
        $redis->sAdd('result',$userId);
        $text = '用户' . $userId . '：抢购成功' . PHP_EOL;
    }else{
        // 已经售完
        $text = '用户' . $userId . '：手速慢，已经抢购完' . PHP_EOL;
    }
}
new SaveLog($text,'secondMethord.log');
// 打印 redis 集合的值
//$result = $redis->sMembers('result');
//var_dump($result);