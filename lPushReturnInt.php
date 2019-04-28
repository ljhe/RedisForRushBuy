<?php
/**
 * lPush: 在名称为key的list左边（头）添加一个值为value的元素
 * rPush: 在名称为key的list右边（尾）添加一个值为value的元素
 * 二者插入 list 成功后，会返回一个 int 值。
 * 就是告诉你这个操作之后，list 里有多少条数据了，这个 int 是线程安全的。
 * 即使再高的并发，也不会造成这个int对于这个用户来说已经过时。
 * （此时并不需要把超过库存的用户从list里删除。库存数建议在秒杀前查询出来放到redis中，之后也不要修改redis的库存数，因为这个库存数是专门用于跟list长度做对比的）
 */

require "HelperRedis.php";
require "SaveLog.php";
header("Content-type: text/html; charset=utf-8");

$redis = HelperRedis::getRedisConn();

// 商品数量
$num = 10;
// 模拟用户唯一标识
$userId = rand(0,30);
//$redis->del('userIdList');exit;
// 判断该用户是否已经抢到商品
if ($redis->exists('userIdList')) {
    // 判断该用户是否已经存在队列中
    if (in_array($userId,$redis->lRange('userIdList',0,-1))) {
        $text = "用户:" . $userId . ",已经抢到，请不要重复抢购" . "\n";
        new SaveLog($text,'lPushReturnInt.log');
        return;
    }
    // 判断是否已经售完
    if ($redis->lLen('userIdList') > $num) {
        $text = "用户:" . $userId . ",已经售完" . "\n";
        new SaveLog($text,'lPushReturnInt.log');
        return;
    }
}
// 将用户唯一标识存入 list 中
$userIdListLen = $redis->lPush('userIdList',$userId);
// 判断队列中的人数是否大于商品库存
if($userIdListLen > $num){
    // 抢购失败的相关操作
    $text = "编号:" . $userIdListLen . ",用户:" . $userId . ",已经售完" . "\n";
}else{

    $text = "编号:" . $userIdListLen . ",用户:" . $userId . ",抢购成功" . "\n";
}
new SaveLog($text,'lPushReturnInt.log');