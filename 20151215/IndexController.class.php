<?php
//Vendor('Hprose.SendMessage');
class IndexController extends CommonController {

    // 框架首页
	public function index() {
		if (isset($_SESSION['cUserNo'])) {
			//start
			//$volist=M("GroupClass")->where(array('status'=>1))->order("sort desc, id desc")->select();
			//$this->volist=$volist;
			//end
			
			 //读取数据库模块列表生成菜单项
			/* $header = C('queryMenuListOfUser');
	      	$reqs = array("userId"=>$_SESSION['login_count'],"productId"=>C('PRIVATE_PRODUCTID'),);
	      	
	      	$SendMessage= new SendMessage;
	      	$menulist = $SendMessage ->send($header,$reqs);
	      	if($menulist['code'] =='-2'){//此帐号在别的地方登录
	      		setcookie ("UserAccount",'',0);
	      		unset($_SESSION);
	      		header('Location:'. __MODULE__.'/Public/login/');
	      		exit;
	      	}
	      	$result = $menulist['dataList'][0]['subMenuList'][0]['subMenuList'];

			$this->assign ( 'menu', $result );  */
			
			//start
			//$groups=M("Group")->where(array('group_menu'=>"{$volist[0]['menu']}",'status'=>"1"))->order("sort desc,id desc")->select();	
			//$this->assign("groups",$groups);
			//end
			//$this->assign ( 'menu', $result );
		
			$this->assign ( 'enviroment', C('CONST_ENVIRONMENT') );
			C ( 'SHOW_RUN_TIME', false ); // 运行时间显示
			C ( 'SHOW_PAGE_TRACE', false );
			$this->display ();
		}else
		{
			header('Location:'. __MODULE__.'/Public/login/');
		}
	}

}