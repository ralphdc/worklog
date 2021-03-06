<?php
//流水数据监控redis的服务地址
$GLOBALS['RedisServerConf']	 =	'172.17.3.153';//'172.17.6.56';//redis的服务地址
$GLOBALS['RedisServerPort']  = '6379';
return array(
	//与后台通讯地址
	
	//调用java的接口存数据库 韦庆丁
	'TohostSerqulityUrl' 			=>'http://172.20.0.25:8080',//本地环境http://172.20.0.20:8080//开发环境http://172.20.4.1:8888
	//调用java的接口通知容器服务 阳海涛
	'TotradeUrl' 				=>'http://172.20.0.108:8080/container/containerService/',//本地环境http://172.20.0.108:8080/container/containerService/
	//调用渠道切换的接口 李小泉
	//'TochannelUrl'				=>'http://172.20.0.27:8801/',
    //'TochannelUrl'				=>'http://172.20.4.16:8801/',
    'TochannelUrl'				=>'http://172.30.2.138:8801/',
	//本地环境http://172.20.0.27:8801//开发环境172.17.6.59:10499 
	
	
    
	//流水监控接口 唐春苗
	//'TowatermoniUrl'			=>'http://172.17.3.86:8080',//测试环境http://172.17.6.180:8080//唐春苗http://172.20.0.33:8080
    'TowatermoniUrl'			=>'http://172.17.6.180:8080',
	
	//ICE权重和权重服务  2015-11-16 ICE权重、权重服务改造，对接人：李超群；
	'ToiceweightUrl'			=>'http://172.17.3.86:8081',//本地环境http://172.20.0.33:8080//测试环境http://172.17.3.86:8081
	//登录
	'ProxyLogin'				=>"SeraSap:tcp -h 172.17.3.131  -p 10001",//测试机不用改
	//商户列表上传路径
	'importpath'				=>'/Uploads/',
	


	'URL_MODEL'                 =>  3, // 如果你的环境不支持PATHINFO 请设置为3
    //囚鸟先生
    'VAR_PAGE'=>'pageNum',
    'PAGE_LISTROWS'=>20,  //分页 每页显示多少条
    'PAGE_NUM_SHOWN'=>10, //分页 页标数字多少个
	
    'SESSION_AUTO_START'        =>  true,
    'TMPL_ACTION_ERROR'         =>  'Public:success', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'       =>  'Public:success', // 默认成功跳转对应的模板文件
    'USER_AUTH_ON'              =>  true,//true
    'USER_AUTH_TYPE'			=>  2,		// 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY'             =>  'authId',	// 用户认证SESSION标记
    'ADMIN_AUTH_KEY'			=>  'administrator',
    'USER_AUTH_MODEL'           =>  'User',	// 默认验证数据表模型
    'AUTH_PWD_ENCODER'          =>  'md5',	// 用户认证密码加密方式
    'USER_AUTH_GATEWAY'         =>  '/Key/Public/login',// 默认认证网关
    'NOT_AUTH_MODULE'           =>  '/Index/index',	// 默认无需认证模块
    'REQUIRE_AUTH_MODULE'       =>  '',		// 默认需要认证模块
    'NOT_AUTH_ACTION'           =>  '',// 默认无需认证操作
    'REQUIRE_AUTH_ACTION'       =>  '',		// 默认需要认证操作
    'GUEST_AUTH_ON'             =>  false,    // 是否开启游客授权访问
    'GUEST_AUTH_ID'             =>  0,        // 游客的用户ID
    'DB_LIKE_FIELDS'            =>  'title|remark',
    'RBAC_ROLE_TABLE'           =>  'think_role',
    'RBAC_USER_TABLE'           =>  'think_role_user',
    'RBAC_ACCESS_TABLE'         =>  'think_access',
    'RBAC_NODE_TABLE'           =>  'think_node',
    'SHOW_PAGE_TRACE'           =>  true ,   //显示调试信息
    
    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
       // '__STATIC__' => __ROOT__ . '/Public/static'
    ),
    
    //应用类库不再需要使用命名空间
    'APP_USE_NAMESPACE'    =>    false,
		
	//登录类
	'loginIn' => array("destServID"=>16000,'messageID'=>'loginIn'),
	'loginOut'=> array("destServID"=>16000,'messageID'=>'loginOut'),
    
    //2015-11-24配置T1，T2系统，可配置；
    'TSysName'=> array('1'=>"T1系统 ",'2'=>"T2系统",'3'=>'T3系统')
);
