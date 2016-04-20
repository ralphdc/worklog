<?php
/**
 * 服务管制--监控配置管理
 * @author zengguangqiu
 *
 */
//定义权限
define('MESSAGE', 1<< 0);    // 把短信的通知方式放在最右边
define('EMAIL', 1<<1);    // 可邮件的通知方式向左移一位
define('other', 1<<2);   // 可通知的方式向左移两位（后续可以不断的增加）
class AddmonelementController extends CommonController {
	var $navTab = 'D60610';
	var $dealType = array('0'=>'自动','1'=>'手动');
	var $status = array('0'=>'关闭','1'=>'开启');
	var $currmode = array('1'=>'生产模式','2'=>'调试模式');
	var $firstorno = array('2'=>'普通','1'=>'优先');
	
	
// 框架首页
	public function index() {
		C ( 'SHOW_RUN_TIME', false ); // 运行时间显示
		C ( 'SHOW_PAGE_TRACE', false );
		if($_GET['pageNum'])
		{
			$_REQUEST['numPerPage'] = empty($_REQUEST['numPerPage']) ?  ($_COOKIE['numPerPage']? $_COOKIE['numPerPage']:C('PAGE_LISTROWS') ): $_REQUEST['numPerPage'];
			$_REQUEST[C('VAR_PAGE')] = $_GET['pageNum'];
			$_POST['pageNum'] = $_GET['pageNum'];
		}
		$pageNum =empty($_REQUEST['numPerPage']) ?  ($_COOKIE['numPerPage']? $_COOKIE['numPerPage']:C('PAGE_LISTROWS') ): $_REQUEST['numPerPage'];
		$this->assign ( 'numPerPage', $pageNum ); //每页显示多少条
		$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);
		$Addmonelement = new AddmonelementModel($_POST,$_GET);
		$result = $Addmonelement->findByPost($_POST,$_GET);
		$count = $Addmonelement->countByPost($_POST,$_GET);
		$this->assign ( 'totalCount', $count );
		$this->assign ( 'list', $result );
		//dump($result);
		//选择出元素的类型
		$monicofig = new MoniconfigModel();
		$resType = $monicofig->getAll();
		if(is_array($resType) && count($resType))
		{
			foreach ($resType as $key => $val)
			{
				$tempArr[$val['id']] = $val['name'];
			}
		}
		$this->assign('resType',$tempArr);
		$this->assign('dealType',$this->dealType);
		$this->assign('status',$this->status);
		$this->assign('currmode',$this->currmode);
		$this->assign('firstorno',$this->firstorno);
		
		$this->assign ( 'map', array('type'=>'','elemId'=>'','elemName'=>'') );
		cookie('_currentUrl_', __SELF__);
		$this->display();
	}
	public function add(){
		if($_POST){
			$request = array();
			foreach($_POST as $k =>$v){
				$request[$k] = $v;
	
			}
			//组织要保存的数据
			$post['data']['id'] = trim($_POST['id']);
			$post['data']['elemName'] = trim($_POST['elemName']);
			$post['data']['dealMode'] = trim($_POST['dealMode']);
			$post['data']['priority'] =  trim($_POST['priority']);
			$post['data']['respWarnTimes'] = trim($_POST['respWarnTimes']);
			$post['data']['respTestTimes'] = trim($_POST['respTestTimes']);
			$post['data']['flowInterval'] = trim($_POST['flowInterval']);
			$post['data']['msgNum'] = trim($_POST['msgNum']);
			$post['data']['resId'] = trim($_POST['resId']);
			$post['data']['remarks'] = urlencode(trim($_POST['remarks']));
			$post['data']['status'] = 1;
			$post['data']['currMode'] = 1;
			$post['form_act'] = 'create';
			//调用java接口进行数据的操作
			$Addmonelement = new AddmonelementModel($_POST,$_GET);
			//调用C++服务接口进行数据的操作
			/* $id = trim($_POST['id']);
			$flowInterval = intval($_POST['flowInterval']);
			$msgNum = intval($_POST['msgNum']);
			$str = '02'.$id.'11'.$this->rep_str($flowInterval, '0', 2).$flowInterval.$this->rep_str($msgNum, '0', 2).$msgNum;
			$post_str = array('pBuffer'=>$str,'iLen'=>strlen($str));
			$ret = $Addmonelement->requestBySoapForMoni('DealCmd',$post_str);
			if($ret == '1'){
				$result = $Addmonelement->postSave($post);
			}else
			{
				$result['errorCode'] = 1;
				if($_POST['status'] == 1)
					$result['errorMessage'] = '操作失败!通知C++开启监控服务失败。';
				else
					$result['errorMessage'] = '操作失败!通知C++关闭监控服务失败。';
			} */
			
			$result = $Addmonelement->postSave($post);
			if($result['errorCode'] == 0){
				$ret = array("statusCode"=>"1","message"=>'操作成功。',"navTabId"=>$this->navTab,//$this->navTab
						"rel"=>"","forwardUrl"=>"","callbackType"=>"closeCurrent");
				echo json_encode($ret);	return;
			}else{
				$msg = $result['errorMessage'];
				$ret = array("statusCode"=>"0","message"=>$msg,"navTabId"=>'',//$this->navTab
						"rel"=>"","forwardUrl"=>"","callbackType"=>"closeCurrent");
				echo json_encode($ret);	return;
			}
		}
		//选择出元素的类型
		$monicofig = new MoniconfigModel();
		$resType = $monicofig->getAll();
		if(is_array($resType) && count($resType))
		{
			foreach ($resType as $key => $val)
			{
				$tempArr[$val['id']] = $val['name'];
			}
		}
		$this->assign('resType',$tempArr);
		$this->assign('dealType',$this->dealType);
		$this->assign('firstorno',$this->firstorno);
		$this->display();
	}
	
	function edit() {
		if($_POST){
			$request = array();
			foreach($_POST as $k =>$v){
				$request[$k] = $v;
	
			}
			//组织要保存的数据
			$post['data']['id'] = trim($_POST['id']);
			$post['data']['elemName'] = trim($_POST['elemName']);
			$post['data']['dealMode'] = trim($_POST['dealMode']);
			$post['data']['priority'] =  trim($_POST['priority']);
			$post['data']['respWarnTimes'] = trim($_POST['respWarnTimes']);
			$post['data']['respTestTimes'] = trim($_POST['respTestTimes']);
			$post['data']['flowInterval'] = trim($_POST['flowInterval']);
			$post['data']['msgNum'] = trim($_POST['msgNum']);
			$post['data']['resId'] = trim($_POST['resId']);
			$post['data']['status'] =trim($_POST['statusme']);
			$post['data']['start'] =trim($_POST['start']);
			$post['data']['end'] =trim($_POST['end']);
			$post['data']['currMode'] =trim($_POST['currmode']);
			$post['data']['remarks'] = urlencode(trim($_POST['remarks']));
			$post['form_act'] = 'update';
			//2015-11-30增加关键字过滤字段，且只在调试模式下出现；
			$post['data']['notifyFilter'] = trim($_POST['filter']); 
			$post['data']['flagFilter'] = intval($_POST['flagFilter']);
			$startTime =strtotime(trim($_POST['start']));
			$endTime = strtotime(trim($_POST['end']));
			if($startTime > $endTime)
			{
				$ret = array("statusCode"=>"0","message"=>'开始时间不能大于结束时间',"navTabId"=>$this->navTab,//$this->navTab
						"rel"=>"","forwardUrl"=>"/index.php/Service/Addmonelement/index/pageNum/".$_POST['pageNumb'],"callbackType"=>"closeCurrent");
				exit(json_encode($ret));
			}
			//调用java接口进行数据的操作
			$Addmonelement = new AddmonelementModel($_POST,$_GET);
			/* $id =trim($_POST['id']);
			$flowInterval = intval($_POST['flowInterval']);
			$msgNum = intval($_POST['msgNum']);
			$str = '02'.$id.$_POST['status'].$_POST['status'].$this->rep_str($flowInterval, '0', 2).$flowInterval.$this->rep_str($msgNum, '0', 2).$msgNum;
			$post_str = array('pBuffer'=>$str,'iLen'=>strlen($str));
			$ret = $Addmonelement->requestBySoapForMoni('DealCmd',$post_str);
			if($ret == '1'){
				$result = $Addmonelement->postSave($post);
			}else
			{
				$result['errorCode'] = 1;
				if($_POST['status'] == 1)
					$result['errorMessage'] = '操作失败!通知C++开启监控服务失败。';
				else
					$result['errorMessage'] = '操作失败!通知C++关闭监控服务失败。';
			} */
			$result = $Addmonelement->postSave($post);
			if($result['errorCode'] == 0){
				$ret = array("statusCode"=>"1","message"=>'操作成功。',"navTabId"=>$this->navTab,//$this->navTab
						"rel"=>"","forwardUrl"=>"/index.php/Service/Addmonelement/index/pageNum/".$_POST['pageNumb'],"callbackType"=>"closeCurrent");
				echo json_encode($ret);	return;
			}else{
				$msg = $result['errorMessage'];
				$ret = array("statusCode"=>"0","message"=>$msg,"navTabId"=>'',//$this->navTab
						"rel"=>"","forwardUrl"=>"","callbackType"=>"closeCurrent");
				echo json_encode($ret);	return;
			}
		}
		$id = $_REQUEST ['id'];
		$Addmonelement = new AddmonelementModel($_POST,$_GET);
		$vo = $Addmonelement->getRowInfo($id);
		$this->assign('row', $vo);
		//选择出元素的类型
		$monicofig = new MoniconfigModel();
		$resType = $monicofig->getAll();
		if(is_array($resType) && count($resType))
		{
			foreach ($resType as $key => $val)
			{
				$tempArr[$val['id']] = $val['name'];
			}
		}
		$this->assign('resType',$tempArr);
		$this->assign('dealType',$this->dealType);
		$this->assign('status',$this->status);
		$this->assign('currmode',$this->currmode);
		$this->assign('firstorno',$this->firstorno);
		$this->assign('pageNum',$_GET['pageNum']);
		$this->display();
	}
	
	/**
	 * 删除
	 * @see CommonController::foreverdelete()
	 */
	function foreverdelete()
	{
		$ids =$_POST['ids'];
		$Addmonelement = new AddmonelementModel();
		$result = $Addmonelement->deleteAll($ids);
		if($result['errorCode'] == 0){
			$ret = array("statusCode"=>"1","message"=>'操作成功。',"navTabId"=>$this->navTab,//$this->navTab
					"rel"=>"","forwardUrl"=>"");
			echo json_encode($ret);	return;
		}else{
			$msg = $result['errorMessage'];
			$ret = array("statusCode"=>"0","message"=>$msg,"navTabId"=>'',//$this->navTab
					"rel"=>"","forwardUrl"=>"");
			echo json_encode($ret);	return;
		}
	}
	/**
	 * 删除单个
	 */
	function delete()
	{
		$id =$_GET['id'];
		$Addmonelement = new AddmonelementModel();
		$result = $Addmonelement->delete($id);
		if($result['errorCode'] == 0){
			$ret = array("statusCode"=>"1","message"=>'操作成功。',"navTabId"=>$this->navTab,//$this->navTab
					"rel"=>"","forwardUrl"=>"");
			echo json_encode($ret);	return;
		}else{
			$msg = $result['errorMessage'];
			$ret = array("statusCode"=>"0","message"=>$msg,"navTabId"=>'',//$this->navTab
					"rel"=>"","forwardUrl"=>"");
			echo json_encode($ret);	return;
		}
	}
	
	/**
	 * 监控开关
	 */
	function OnOff(){
		$id = $_POST['id'];
		$str = '02'.$id.$_POST['opty'];
		$post_str = array('pBuffer'=>$str,'iLen'=>strlen($str));
		$Channelswitch = new ChannelswitchModel($_POST,$_GET);
		$ret = $Channelswitch->requestBySoap('DealCmd',$post_str);
		if($ret == '1'){
			echo 'true';
		}else{
			echo 'false';
		}
		return;
	}
	/**
	 * 保存元素对应的通知的人员信息
	 */
	public function savepersons()
	{
		if($_POST){
			$request = array();
			foreach($_POST as $k =>$v){
				$_POST[$k] = trim($v);
		
			}
			//开始组合发送到java后端的数据
			$noticetypeArr = $_REQUEST['noticetype'];
			$staffIdArr = $_REQUEST['staffId'];
			if(is_array($noticetypeArr) && count($noticetypeArr))
			{
				$tempStr = implode(',', $noticetypeArr);
				if($tempStr == '1')
				{
					$type = MESSAGE;
				}elseif($tempStr == '2')
				{
					$type = EMAIL;
				}elseif($tempStr == '1,2')
				{
					$type = MESSAGE | EMAIL;
				}
			}else 
			{
				$msg = "请选择通知方式";
				$ret = array("statusCode"=>"0","message"=>$msg,"navTabId"=>'',//$this->navTab
						"rel"=>"","forwardUrl"=>"/index.php/Service/Addmonelement/index/pageNum/".$_POST['pageNumc'],"callbackType"=>"closeCurrent");
				echo json_encode($ret);	return;
			}
			
			if(is_array($staffIdArr) && count($staffIdArr))
			{
				foreach ($staffIdArr as $key=>$val)
				{
					$tempstaffIdArr[$key]['staffId'] = $val;
				}
			}else
			{
				$msg = "请选择通知人员";
				$ret = array("statusCode"=>"0","message"=>$msg,"navTabId"=>'',//$this->navTab
						"rel"=>"","forwardUrl"=>"/index.php/Service/Addmonelement/index/pageNum/".$_POST['pageNumc'],"callbackType"=>"closeCurrent");
				echo json_encode($ret);	return;
			}
			
			//组织要保存的数据
			$post['data']['elemId'] = trim($_POST['elemId']);
			$post['data']['type'] = $type;
			$post['data']['oprList'] =$tempstaffIdArr;
			
			//调用java接口进行数据的操作
			$Addmonelement = new AddmonelementModel($_POST,$_GET);
			$result = $Addmonelement->postSavecheckinfo($post);
			if($result['errorCode'] == 0){
				$ret = array("statusCode"=>"1","message"=>'操作成功。',"navTabId"=>$this->navTab,//$this->navTab
						"rel"=>"","forwardUrl"=>"/index.php/Service/Addmonelement/index/pageNum/".$_POST['pageNumc'],"callbackType"=>"closeCurrent");
				echo json_encode($ret);	return;
			}else{
				$msg = $result['errorMessage'];
				$ret = array("statusCode"=>"0","message"=>$msg,"navTabId"=>'',//$this->navTab
						"rel"=>"","forwardUrl"=>"","callbackType"=>"closeCurrent");
				echo json_encode($ret);	return;
			}
		}
		//获取所有的人员
		$Operatmember = new OperatmemberModel($_POST,$_GET);
		$persons = $Operatmember->getAll();
		//取出已经勾选的人员的ID以及对应的通知方式
		$elementID = $_GET['id'];
		$Addmonelement = new AddmonelementModel($_POST,$_GET);
		$info = $Addmonelement->getCheckedInfo($elementID);
		$rightInt = intval($info['type']);
		if($rightInt & MESSAGE)
		{
			$RightsArr[] = 1;
		}
		if($rightInt & EMAIL)
		{
			$RightsArr[] = 2;
		}
		$staffIdArrt = $info['oprList'];
		$tempstaffIdArrt = array();
		if(is_array($staffIdArrt) && count($staffIdArrt))
		{
			foreach ($staffIdArrt as $tkey=>$tval)
			{
				$tempstaffIdArrt[] = $tval['staffId'];
			}
		}
		$this->assign("list",$persons);
		$this->assign("elemId",$elementID);
		$this->assign("checkInfo",$info);
		$this->assign("tempstaffIdArrt",$tempstaffIdArrt);
		$this->assign("rights",$RightsArr);
		$this->assign('pageNum',$_GET['pageNum']);
		$this->display();
	}
	
	
	//分配权限
	function setNoticeType()
	{
		if($_POST){
		    
			//获取前端传递过来的参数组合成相关的后台的数据
			$elementArr = $_POST['staff'];
			$noticeTypeArr = $_POST['noticetype'];
			$groupArr = $_POST['sgroup'];
			$tempRight = array();
			
			if(is_array($elementArr) && count($elementArr))
			{
			    
				foreach ($elementArr as $elekey=>$eleval)
				{
					$type = '';
					//$tempnoticeArr = $noticeTypeArr[$eleval];
					$child_type_wc     = !empty($noticeTypeArr[$eleval]['wechat']) ? '1' :'0';
					$child_type_email  = !empty($noticeTypeArr[$eleval]['email']) ? '1' :'0';
					$child_type_msg    = !empty($noticeTypeArr[$eleval]['msg']) ? '1' :'0';
					$type = $child_type_wc.$child_type_email.$child_type_msg;
					$tempRight[$elekey]['oprId']= $eleval;
					$tempRight[$elekey]['type']= bindec(strval($type));
				}
			}
			//组织要保存的数据
			$post['data']['elemId'] = $_POST['id'];
			$post['data']['typeList'] =$tempRight;
			
			$post['data']['groupIds'] = implode(',',$groupArr);
			$post['form_act'] = 'create';
			//调用java接口进行数据的操作
			$Addmonelement = new AddmonelementModel($_POST,$_GET);
			$result = $Addmonelement->postSavepersonrights($post);
			if($result['errorCode'] == 0){
				$ret = array("statusCode"=>"1","message"=>'操作成功。',"navTabId"=>$this->navTab,//$this->navTab
						"rel"=>"","forwardUrl"=>"/index.php/Service/Addmonelement/index/pageNum/".$_POST['pageNuma'],"callbackType"=>"closeCurrent");
				echo json_encode($ret);	return;
			}else{
				$msg = $result['errorMessage'];
				$ret = array("statusCode"=>"0","message"=>$msg,"navTabId"=>'',//$this->navTab
						"rel"=>"","forwardUrl"=>"/index.php/Service/Addmonelement/index/pageNum/".$_POST['pageNuma'],"callbackType"=>"closeCurrent");
				echo json_encode($ret);	return;
			}
		}
		//获取所有的人员
		$Operatmember = new OperatmemberModel($_POST,$_GET);
		//$persons = $Operatmember->getAll();
		$wyperson = $Operatmember->modelGetGroupAndStaff();
		
		$persons = $wyperson['data']['staffId'];
		$groups = $wyperson['data']['groupId'];
		
		$this->assign('list',$persons);
		$this->assign('groups',$groups);
		
		//dump($allElementArr);
		//获取出当前选择的运维账号有的权限
		$elementID = $_GET['elementID'];
		$Addmonelement = new AddmonelementModel($_POST,$_GET);
		$result2 = $Addmonelement->getPersonRights($elementID);
		$rights = $result2['typeList'];
		
		if(isset($rights[0]['oprId'])){
		    $person_rights = array();
		    foreach ($rights as $r){
		        $person_rights[$r['oprId']] = $r['type'];
		    }
		}
		$this->assign('person_rights',$person_rights);
		
		$groupRights = $Addmonelement->getGroupRights($elementID);
		//echo "<pre>";
		//print_r($groupRights);
		$grights = array();
		foreach ($groupRights as $k=>$v){
		    $grights[]= $groupRights[$k]['groupId'];
		}
		$this->assign('grights',$grights);
		
		
		//拆分权限为数组
		if(is_array($rights) && count($rights))
		{
			$tempRightsArr = array();
			foreach ($rights as $key =>$val)
			{
				$rightInt = intval($val['type']);
				if($rightInt & MESSAGE)
				{
					$tempRightsArr[$val['oprId']][] = 1;
				}
				if($rightInt & EMAIL)
				{
					$tempRightsArr[$val['oprId']][] = 2;
				}
			}
		}
		
		
		$this->assign('rights',$tempRightsArr);
		$this->assign('elementID',$elementID);
		$this->assign('pageNum',$_GET['pageNum']);
		$this->display();
	}
	
	
}