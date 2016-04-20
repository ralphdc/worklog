<?php
/**
 * 下载管理控制器
 * @author zengguangqiu
 *
 */
class DownloadController extends CommonController {
    
    private $host = '127.0.0.1';
    private $port = '6379';
    private $listName = 'downloadmonitor';
    
    public function __construct(){
        parent::__construct();
        
        $this->rs = new Redis();
        $this->rs->connect($this->host,$this->port);
        
    }
    // 框架首页
	public function index(){
		C ( 'SHOW_RUN_TIME', false ); // 运行时间显示
		C ( 'SHOW_PAGE_TRACE', false );
		if((empty($_POST['start']) && !empty($_POST['end'])))
		{
			$ret = array("statusCode"=>"0","message"=>'开始时间不能为空',"navTabId"=>$this->navTab,//$this->navTab
					"rel"=>"","forwardUrl"=>"","callbackType"=>"closeCurrent");
			exit(json_encode($ret));
		}
		if((!empty($_POST['start']) && empty($_POST['end'])))
		{
			$ret = array("statusCode"=>"0","message"=>'结束时间不能为空',"navTabId"=>$this->navTab,//$this->navTab
					"rel"=>"","forwardUrl"=>"","callbackType"=>"closeCurrent");
			exit(json_encode($ret));
		}
		if(!empty($_POST['start']) && !empty($_POST['end']))
		{
			$start =strtotime(trim($_POST['start']).' 00:00:00');
			$end = strtotime(trim($_POST['end']).' 23:59:59');
			$now   = strtotime(date("Y-m-d").' 23:59:59');
			$end = $end > $now ?  $now: $end;
			$days  = ceil(($end - $start)/86400);
			if($start > $end)
			{
				$ret = array("statusCode"=>"0","message"=>'开始时间不能大于结束时间',"navTabId"=>$this->navTab,//$this->navTab
						"rel"=>"","forwardUrl"=>"","callbackType"=>"closeCurrent");
				exit(json_encode($ret));
			}elseif($days > 7 )
			{
				$ret = array("statusCode"=>"0","message"=>'查询时间跨度不能超过一周',"navTabId"=>$this->navTab,//$this->navTab
						"rel"=>"","forwardUrl"=>"","callbackType"=>"closeCurrent");
				exit(json_encode($ret));
			}
		}
		if(empty($_POST['start']) || empty($_POST['end']))
		{
			$result = array();
		}else 
		{
			$Download = new DownloadModel($_POST,$_GET);
			$result = $Download->webscoketRequest($_POST,$_GET);
		}
		
		$this->assign ( 'list', $result['return'] );
		$this->assign ( 'map', array('start'=>'','end'=>'') );
		cookie('_currentUrl_', __SELF__);
		$this->display();
	}
	
	public function startStop()
	{
		$isstart = $_POST['isclose'];
		$startArr = array('1'=>'start','2'=>'stop');
		$_POST['iscloseStr'] = $startArr[$isstart]; 
		$Download = new DownloadModel($_POST,$_GET);
		$result = $Download->startorstopdown($_POST,$_GET);
		if($result['result']['errorCode'] == '00'){
			$ret = array("statusCode"=>"1","message"=>'操作成功。',"navTabId"=>$this->navTab,//$this->navTab
					"rel"=>"","forwardUrl"=>"","callbackType"=>"closeCurrent");
			echo json_encode($ret);	return;
		}else{
			if(empty($result['result']['errorDesc']))
			{
				$result['result']['errorDesc'] = "服务未开启";
			}
			$msg = '操作失败，errorDesc：'.$result['result']['errorDesc'];
			$ret = array("statusCode"=>"0","message"=>$msg,"navTabId"=>'',//$this->navTab
					"rel"=>"","forwardUrl"=>"","callbackType"=>"closeCurrent");
			echo json_encode($ret);	return;
		}
	}
	
	public function throwMqs()
	{
	    
	    $isstart = $_POST['isclose'];
	    $startArr = array('1'=>'start','2'=>'stop');
	    $_POST['iscloseStr'] = $startArr[$isstart];
	    $Download = new DownloadModel($_POST,$_GET);
	    
	    //Delete All Data from List;
	    $DataWareLen = $this->rs->llen($this->listName);
	    if($DataWareLen){
	    	for($wi=0; $wi<$DataWareLen; $wi++){
	    		$this->rs->rpop($this->listName);
	    	}
	    }
	    $DataWareLenNew = $this->rs->llen($this->listName);
	    if(!$DataWareLenNew){
	    	$randm = uniqid();
	    	$this->rs->set('downloadrand',$randm);
	    	$_POST['serial'] = $randm;
	    	
	    	$result = $Download->startStopToggle($_POST,$_GET);
			
	    	if($result){
	    		$ret = array("statusCode"=>"1","message"=>'发布成功，请等待返回。',"navTabId"=>$this->navTab,//$this->navTab
	    				"rel"=>"","forwardUrl"=>"","callbackType"=>"closeCurrent");
	    		echo json_encode($ret);	return;
	    	}else{
				$ret = array("statusCode"=>"0","message"=>'发布失败，请报告管理员检查原因。',"navTabId"=>$this->navTab,//$this->navTab
	    				"rel"=>"","forwardUrl"=>"","callbackType"=>"closeCurrent");
	    		echo json_encode($ret);	return;
			}
	    }
	}
	
	Public function getRedisInfo(){
	    /* $Download = new DownloadModel();
	    $result = $Download->modelGetRedisResponse();
	    if($result){
	    		$result = array_unique($result);
	    		$this->clearRedis();
	            $this->assign('sinfo',$result);
                $this->display();
	    }else{
		echo "出现错误，请稍后再试";
	    } */
		$this->display();
	}
	
	public function clearRedis(){
	    
	    $listLen = $this->rs->llen($this->listName);
	    if($listLen){
	        for($i=0; $i<$listLen; $i++){
	            $this->rs->rpop($this->listName);
	        }
	    }
	    $this->rs->close();
	}
	
	public function checkRedisInfo(){
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
		
		//RandNumber for This Time;
		$randStamp = $this->rs->get('downloadrand');
		//Check whether having new values;
		//$responseTime = $this->rs->get(strval($randStamp));
		
		$lens = $this->rs->llen($this->listName);
		$want=array();
		if($lens){
			$listContent = $this->rs->lrange($this->listName,0,-1);
			foreach ($listContent as $content){
				$tmp = json_decode($content,true);
				if($tmp['result']['requestSerial'] == strval($randStamp)){
					$want['cont'][] = $tmp['result'];
				}
			}
			
			//$want['cont'] = array_unique($want['cont']);
			$want['numbers']=count($want['cont']);
			//$this->rs->set($responseTime,$want['numbers']);
			$want = json_encode($want);
		}
		
		echo "data: {$want}\n\n";
		flush();
		
	}

}
