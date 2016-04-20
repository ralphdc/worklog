<?php
	ini_set('default_socket_timeout',3600);
        $redis =new Redis();
        $redis->connect('172.30.2.170','6379');

	$redis->subscribe(array('mpayToOperationChannel'),'redisCallBack');

	function redisCallBack($instance,$channel,$message){
	   $redis =new Redis();
           $redis->connect('127.0.0.1','6379');
	   $redis->lpush('downloadmonitor',$message);
		
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
		$time = date('r');
		$da = json_decode($message,true);
		$c =  json_encode($da['result']);
		echo "data: {$time}\n\n";
		flush();
}
