<?php
	ini_set('default_socket_timeout',3600);
        $redis =new Redis();
       # $redis->connect('172.30.2.170','6379');
	$redis->connect('172.17.3.153','6379');
	$redis->subscribe(array('mpayToOperationChannel'),'redisCallBack');

	function redisCallBack($instance,$channel,$message){
	   $redis =new Redis();
           $redis->connect('172.17.3.153','6379');
	   $redis->lpush('downloadmonitor',$message);
	
}
