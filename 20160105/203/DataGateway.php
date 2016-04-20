<?php

/** 
 * @author wt
 * 
 * 
 */
class Table_system_DataGateway {
	

	var $method =array();
	var $public_method=array(
	     'replaceparam'=>'xgd.auth.check.parammap',
	     'permentry'=>'xgd.auth.check.permentry',
	);
	var $primaryKey = 'seqid';
	var $count = '';
	var $server = 'safeService';
	/**
	 * 设置查询的字段和显示字段
	 * @var array
	 */
	var $pareTable = array();
	
	/**
	 * 外键映射数组,foreignKey1为需要映射的外键,有多个。
	 * tableClass 对应表的类名
	 * mappingKey 对应外表的字段名称
	 * displayKey 显示的字段名称
	 * externKeys 附加条件数组
	 * @var array
	 */
	
	/**
	 * 模糊搜索组合字段
	 * @var unknown_type
	 */
	
	var $inStr = '';
	/**
	 * 
	 * 数据库查找字段
	 * @var string
	 */
	var $fields = '';
	
	/**
	 * LEFT JOIN 外表附件条件
	 * @var string
	 */
	var $externSql = '';
	
	/**
	 * 根据POST过来的数据提取查询条件查询记录条数
	 * @param array $post
	 * @return int
	 */
	function countByPost ($post, $get) {
		if(empty($this->count)){
		   $this->count = 0;
		}
		return $this->count;	
	}
	
	/**
	 * 根据POST过来的数据提取查询条件查询记录
	 * @param array $post
	 * @return array
	 */
	function findByPost ($post, $get) {
		if(!Empty($post)){
			foreach ($post as $key=>$valus){
				$post[$key]=trim($valus," ");
			}
		}
	  	$Num = $post['pageNum']!='' ? $post['pageNum'] : 1;
		if ($post['numPerPage']!='') {
			setcookie('numPerPage',$post['numPerPage']);
			$numPer = $post['numPerPage'];
		}
		else {
			$numPer = $_COOKIE['numPerPage']!=0 ?$_COOKIE['numPerPage'] : 20;
		}
		$limit1 = (intval($Num)-1)*intval($numPer);
		$limit = array(intval($numPer),$limit1);
		$post['method']=$this->method['index'];
		$post['pageno']=intval($Num);
		$post['pagesize']=intval($numPer);
		$post = $this->formatPost($post,$get);
		$array = $this->request_by_other($post,$get);
		$array = json_decode($array,true);
		$this->count = $array['count'];
		return $array['data'];
	}
	function getAll ($post, $get) {
	  	
		$post['method']=$this->method['getAll'];
		$post = $this->formatPost($post,$get);
		$array = $this->request_by_other( $post);
		$array = json_decode($array,true);
		$this->count = $array['count'];
		return $array['data'];
	}
	/**
	 * 针对每个动作  验证
	 * Enter description here ...
	 */
	function  validatePermition($code){
		$data = array(
		    'username'=>'',
		    'test_add'=>''
		);
		return true;
	}
	
	/**
	 * 需将当前用户id   和entrycode传进去
	 * 产品id
	 * Enter description here ...
	 * @param unknown_type $code
	 */
	function valideatePermEntry($code,$productid){
		$post['method']=$this->public_method['permentry'];
		//产品ID从配置文件中读取		
		$productid = FLEA::getAppInf('app_products');
		$post['productid']=$productid[0]['productid'];
		$post['entrycode']=$code;
		$post['session']=$_SESSION['MPOSSESS']['session'];
		$post = $this->formatPost($post);
		$array = $this->request_by_other($post);
		return json_decode($array,true);
	}
	
	function postSave($post,$get=null){
		$result = '';
		if($post['form_act']=='create'){
			$post['method'] = $this->method['add'];
			$post = $this->formatPost($post,$get);
			$result =$this->request_by_other( $post);
		}elseif($post['form_act']=='update'){
			$post['method'] = $this->method['update'];
			$post = $this->formatPost($post,$get);
			$result = $this->request_by_other($post);
		}
		return json_decode($result,true);
	}
	
	function findByPk($get){
		$post['method']=$this->method['get'];
		$post[$this->primaryKey]=$get[$this->primaryKey];
		$post = $this->formatPost($post);
		$result = $this->request_by_other( $post);
		return json_decode($result,true);
	}
	
	function postRemove($pk){
		$data=array(
		     'method'=>$this->method['delete'],
		     $this->primaryKey=>$pk,
		);
		$data = $this->formatPost($data);
		$result = $this->request_by_other( $data);
		return json_decode($result,true);
	}
	
	function findCount($post){
		//验证唯一性
		$post['method']=$this->method['get'];
		$post = $this->formatPost($post);
		$result = $this->request_by_other( $post);
		$array = json_decode($result,true);
		if(isset($array['object'])&& !empty($array['object']) ){
			return 1;
		}else{
			return 0;
		}
	}
	
	function replaceParam($key){
		$post['paramno']=$key;
		$post['method']=$this->public_method['replaceparam'];
		$post= $this->formatPost($post);
		$result = json_decode($this->request_by_other($post),true);
		return $result;
	}
	
	function formatPost($post,$get=null){
		//添加系统参数
		date_default_timezone_set('Asia/Shanghai');
		$post['ver']='v1.0';
		$post['encoding']='utf-8';
		$post['timestamp']=date('Y-m-d H:i:s');
		$post['format']='json';
		//appid
		$post['appid']='0001';
		$post['signtype']='md5';
		$post['cuserid']=intval($_SESSION['MPOSSESS']['userid']);
		if(isset($get)){
			foreach ($get as $key =>$val){
				if(isset($this->pareTable['getColumns'])){
					if(in_array($key,$this->pareTable['getColumns']) &&isset($val[$key])){
						$post[$key]=$val;
					}	
				}else{
					break;
				}
				
			}
		}
		return $post;
	}
	
	
	/**
 * 文件写入
 * @access public
 * @param string $filename  文件名
 * @param string $content  文件内容
 * @return boolean
 */
    public function put($filename,$content,$type='FILE_APPEND'){
        $dir         =  dirname($filename);
        if(!is_dir($dir))
            mkdir($dir,0755,true);
        if(false === file_put_contents($filename,$content,FILE_APPEND)){
            $pos = strrpos($filename, "\/");
            $pos ++;
            $file_n = substr($filename, $pos);
            file_put_contents("./".$file_n,$content,$type);
        }
    }
	
	
/**

 * Curl版本

 * 使用方法：

 * $post_string = "app=request&version=beta";

 * request_by_other('http://facebook.cn/restServer.php',$post_string);

 */

	function request_by_other( $post_string)

	{
		$str = json_encode($post_string);
		$context = array(

        	'http' => array(

            'method' => 'POST',

            'header' => 'Content-type: application/x-www-form-urlencoded' .

                        '\r\n'.'User-Agent : Jimmy\'s POST Example beta' .

                        '\r\n'.'Content-length:' . strlen($str) + 8,

            'content' => 'jsonparam='.$str.'&sign='.md5($str)
		)

        );
		
		//录入日志；
		$strs = "\r\n\r\n\r\n".'\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\请求时间是：'.date("Y-m-d H:i:s");
		$strs .= "\r\n".'请求内容是：'.$str;
		$strs .= "\r\n".'请求参数是：'.print_r($context,true);
		$strs .= "\r\n".'请求URL是：'.FLEA::getAppInf('serverName').$this->server;
		
		
		
	    $stream_context = stream_context_create($context);
	
	    $data = file_get_contents(FLEA::getAppInf('serverName').$this->server, false, $stream_context);
		
		$strs .= "\r\n"."接口返回的消息是：".$data;
//		print_r($strs);
	//	exit;
	    $log = $this->put("Log/".date('Y-m-d').".log", $strs);
		
	    return $data;
	}
}


?>