<?php

date_default_timezone_set('PRC');

define('RedisLocal','172.30.2.153');
define('RedisLocalPort','6379');
define('RedisVisitorHash','Service:Visitor:Hash');
define('RedisVisitorList','Service:Visitor:List');
define('RedisVisitorListCount','Service:Visitor:List:Count');
define('RedisVisitorSet','Service:Visitor:Set');

define('MYSQLHOST','172.20.0.208');
define('MYSQLPORT','3306');
define('MYSQLUSER','static');
define('MYSQLPWD','Xgd2015!@');




$redis = new Redis();
if($redis->connect(RedisLocal,RedisLocalPort)){

	$date = date('Y-m-d H:i:s');
	$hash['visitor_date'] = $date;
	$hash['hash'] = $redis->hgetall(RedisVisitorHash);
	//$hash['list'] = $redis->lrange(RedisVisitorList,0,-1);

	$conn = new mysqli(MYSQLHOST,MYSQLPORT,MYSQLUSER,MYSQLPWD);
	if(mysqli_connect_errno){
		$str = 
	}
	
	
}
