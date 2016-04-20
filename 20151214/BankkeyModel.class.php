<?php
use Think\Model;

// 节点模型
class BankkeyModel extends CommonModel{// 
    protected $_validate	=	array(
       array('TMK_TEMP','require','分量必须！'),
        );
  	//插入时字段要检测，以防被oracle过滤掉
	protected $fields = array( 'SID', 'MERNO','TERMNO','TMKINDEX','TMKLEN','TMK','TMK2','ZMKINDEX','ZMKLEN','PINKLEN','PINK','MACKLEN','MACK','TRACKLEN','TRACK','ISUSE','HSMID','ADDPERSON','ADDDATE','HSM_ID' );
	//主键
	protected $pk = 'SID';
	// 自动填充设置
	protected $_auto	 =	 array(
		array('SID','createGuid',self::MODEL_INSERT,'callback','1'),
		//array('TMK','createGuid',self::MODEL_INSERT,'callback','1'),
	);
	
	
	private  $method = array(
	    'index'=>'xgd.system.RouteRules.query',  //绑定规则查询
	    'getRRL'=>'xgd.system.RouteRules.get',	//获取绑定规则
	    'getRMerTerm'=>'xgd.system.RTerminal.queryUnboundRTerminal', //获取未被绑定的渠道商户号和终端号
	    'getRTrmUseInfo'=>'xgd.system.RTrmUseInfo.get',//获取重新签到表的状态
	    'updateRTrmUseInfo'=>'xgd.system.RTrmUseInfo.update',//更新重新签到表的信息
	    'getCreditData'=>'xgd.nac.riskMerchAttr.get',//获取商户信用额度信息
	    'permentry'=>'xgd.auth.check.permentry',
	);
   
	
	function getRTrmUseInfo($post=null, $get=null) {
	    $post['method']=$this->method['getRTrmUseInfo'];
	    $post = $this->formatPost($post,$get);
	    $array = $this->request_by_other( $post);
	    $array = json_decode($array,true);
	    $this->count = $array['count'];
	    return $array[object];
	}
	
	function updateRTrmUseInfo($post=null, $get=null) {
	    $post['method']=$this->method['updateRTrmUseInfo'];
	    $post = $this->formatPost($post,$get);
	    $array = $this->request_by_other( $post);
	    $array = json_decode($array,true);
	    return $array;
	}
	
	function bankKeyCtrl1($code="Mac_BankKeyCtrl1")
	{
	    $post['method']=$this->method['permentry'];
	    //产品ID从配置文件中读取
	    $post['productid']=C('BankCtrlProductId');
	    $post['entrycode']=$code;
	    $post['session']=$_SESSION;
	    
	    $serviceModel = new SendmessageModel();
	    $posts = $serviceModel->formatPost($post);
	    
	    $res = $serviceModel->request_for_service($posts,$url=C('ENCRYPTHOST').C('ENCRYPTPORT').'/enc');
        return $res;	    
	}
    
}