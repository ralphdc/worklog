<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id: common.php 2601 2012-01-15 04:59:14Z liu21st $

//公共函数
function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty ( $time )) {
		return '';
	}
	$format = str_replace ( '#', ':', $format );
	return date ($format, $time );
}

function getStatus($status, $imageShow = true) {
	switch ($status) {
		case 0 :
			$showText = '禁用';
			$showImg = '<IMG SRC="/Public/Images/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="禁用">';
			break;
		case 2 :
			$showText = '待审';
			$showImg = '<IMG SRC="/Public/Images/prected.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="待审">';
			break;
		case - 1 :
			$showText = '删除';
			$showImg = '<IMG SRC="/Public/Images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">';
			break;
		case 1 :
		default :
			$showText = '正常';
			$showImg = '<IMG SRC="/Public/Images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="正常">';

	}
	return ($imageShow === true) ?  $showImg  : $showText;

}

function getNodeGroupName($id) {
	if (empty ( $id )) {
		return '未分组';
	}
	if (isset ( $_SESSION ['nodeGroupList'] )) {
		return $_SESSION ['nodeGroupList'] [$id];
	}
	$Group = D ( "Group" );
	$list = $Group->getField ( 'id,title' );
	$_SESSION ['nodeGroupList'] = $list;
	$name = $list [$id];
	return $name;
}

//囚鸟先生
function showStatus($status, $id, $callback="", $url, $dwz) {
	switch ($status) {
		case 0 :
			$info = '<a href="'.$url.'/resume/id/' . $id . '/navTabId/'.$dwz.'" target="ajaxTodo" callback="'.$callback.'">恢复</a>';
			break;
		case 2 :
			$info = '<a href="'.$url.'/checkPass/id/' . $id . '/navTabId/'.$dwz.'" target="ajaxTodo" callback="'.$callback.'">批准</a>';
			break;
		case 1 :
			$info = '<a href="'.$url.'/forbid/id/' . $id . '/navTabId/'.$dwz.'" target="ajaxTodo" callback="'.$callback.'">禁用</a>';
			break;
		case - 1 :
			$info = '<a href="'.$url.'/recycle/id/' . $id . '/navTabId/'.$dwz.'" target="ajaxTodo" callback="'.$callback.'">还原</a>';
			break;
	}
	return $info;
}


function getGroupName($id) {
	if ($id == 0) {
		return '无上级组';
	}
	if ($list = F ( 'groupName' )) {
		return $list [$id];
	}
	$dao = D ( "Role" );
	$list = $dao->select( array ('field' => 'id,name' ) );
	foreach ( $list as $vo ) {
		$nameList [$vo ['id']] = $vo ['name'];
	}
	$name = $nameList [$id];
	F ( 'groupName', $nameList );
	return $name;
}

function pwdHash($password, $type = 'md5') {
	return hash ( $type, $password );
}

function list_to_tree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0){
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if(isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}



//CommonModel 自动继承
function CM($name){
	static $_model = array();
	if(isset($_model[$name])){
		return $_model[$name];
	}
	$class=$name."Model";
	import('@.Model.' . $className);
	if(class_exists($class)){
		$return=new $class();
	}else{
		$return=M("CommonModel:".$name);
	}
	$_model[$name]=$return;

return $return;
}


function getUrl($url){
    
    return $url ? $url : "javascript:void(0);";
}

//读哈希表；
function hashGet($hash,$keys)
{
    $rs='';
    $redis = new Redis();
    if($redis->connect(C('localRedisHost'),C('localRedisHostPort'))){
        $rs = $redis->hget($hash,$keys);
        $redis->close();
    }
    return $rs;
}
//写哈希表；
function hashSet($hash,$array)
{
    $redis = new Redis();
    if($redis->connect(C('localRedisHost'),C('localRedisHostPort'))){
        $redis->hmset($hash,$array);
        $redis->close();
    }
}

function getIP() {
	if (getenv(HTTP_CLIENT_IP)) {
		$ip = getenv(HTTP_CLIENT_IP);
	}
	elseif (getenv(HTTP_X_FORWARDED_FOR)) {
		$ip = getenv(HTTP_X_FORWARDED_FOR);
	}
	elseif (getenv(HTTP_X_FORWARDED)) {
		$ip = getenv(HTTP_X_FORWARDED);
	}
	elseif (getenv(HTTP_FORWARDED_FOR)) {
		$ip = getenv(HTTP_FORWARDED_FOR);
	}
	elseif (getenv(HTTP_FORWARDED)) {
		$ip = getenv(HTTP_FORWARDED);
	}
	else {
		$ip = $_SERVER[REMOTE_ADDR];
	}
	return $ip;
}


function statistic()
{
	$redis = new Redis();
	if($redis->connect(C('RedisLocal'),C('RedisLocalPort'))){
		$vistor_ip	= getIP();
		
		
		//访问量加1；
		$visitorHashKey = C('RedisVisitorHash');
		$redis->hincrby($visitorHashKey,'browsefrequency',1);

		//登录次数加1；
		$jsid = cookie('JSID');
		if(strlen(strval($jsid)) < 1){
			//总共有多少人登录；
			$redis->hincrby($visitorHashKey,'loginfrequency',1);
			//单一IP登录次数；
			$redis->hincrby($visitorHashKey,strval($vistor_ip),1);
		}

		$list = $redis->llen(C('RedisVisitorList'));
		if($list){
			$listContent = $redis->lrange(C('RedisVisitorList'),0,-1);
			if(!in_array($vistor_ip, $listContent)){
				$redis->lpush(C('RedisVisitorList'),$vistor_ip);
			}
		}else{
			$redis->lpush(C('RedisVisitorList'),$vistor_ip);
			
	}
	}
}