<?php

date_default_timezone_set('PRC');

define('RedisLocal','172.30.2.153');
define('RedisLocalPort','6379');
define('RedisVisitorHash','Service:Visitor:Hash');
define('RedisVisitorList','Service:Visitor:List');
define('RedisVisitorListCount','Service:Visitor:List:Count');
define('RedisVisitorSet','Service:Visitor:Set');




$redis = new Redis();
if($redis->connect(RedisLocal,RedisLocalPort)){

	$date = date('Y-m-d');
	$hash['date'] = $date;
	$hash['hash'] = $redis->hgetall(RedisVisitorHash);
	$hash['list'] = $redis->lrange(RedisVisitorList,0,-1);

	
	$hashjson = json_encode($hash);

	$push = $redis->lpush(RedisVisitorListCount,$hashjson);
	
	if($push){
		$add = $redis->sadd(RedisVisitorSet,$date,1);
	}

	if($add){
		$redis->del(array(RedisVisitorHash,RedisVisitorList));
	}
	
}
