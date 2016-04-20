<?php

session_start();
ini_set('default_socket_timeout',20);

$redis = new Redis();

$conn = $redis->connect('127.0.0.1','6379');

if($conn){
    
    $redis->subscribe(array('XGD'),'ckr');

}else{
	echo '本地Redis无法连接�?;
	exit;
}

function ckr($instance,$channel,$msg){
    if(empty($msg)){
        echo json_encode(array('result'=>0));
		exit;
    }else{
        echo $msg;
		exit;
    }
}