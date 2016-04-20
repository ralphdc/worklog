<?php
//流水数据监控
$GLOBALS['RedisServerConf']	 = '172.17.3.153';//	'172.17.3.153'//'172.20.5.56'//'172.18.2.117';//redis的服务地址
$GLOBALS['RedisServerPort']  = '6379';
return array(
	//与后台通讯地址
	//调用java的接口存数据帿陈治巿
	'TodatabaseUrl' 			=>'http://172.17.3.97:10056',//本地环境http://172.20.0.20:12345//开发环境http://172.20.4.1:8888
	//调用java的接口通知容器服务 阳海?
	'TotradeUrl' 				=>'http://172.20.5.55:8080/container/containerService/',//本地环境http://172.20.0.108:8080/container/containerService/
	//调用渠道切换的接卿李小汿
	'TochannelUrl'				=>'http://172.20.0.27:8801/',//本地环境http://172.20.0.27:8801//开发环?
	//流水监控接口 唐春?
	'TowatermoniUrl'			=>'http://172.17.3.97:10056',
	//ICE权重和权重服?
	'ToiceweightUrl'			=>'http://172.17.3.88:10057',
	//登录
	'ProxyLogin'				=>"SeraSap:tcp -h 172.17.3.131  -p 10001",//测试机不用改
	//商户列表上传路径
	'importpath'				=> '/Uploads/',
	
	//数据库配?
	'DB_TYPE'                   =>  'oracle',
	'DB_HOST'                   =>  '172.0.0.1',//172.20.4.1
	'DB_NAME'                   =>  'dbtest',
	'DB_USER'                   =>  'enc_user',
	'DB_PWD'                    =>  '111111',
	'DB_PORT'                   =>  '1521',
	'DB_PREFIX'                 =>  '',

	'URL_MODEL'                 =>  3, // 如果你的环境不支持PATHINFO 请设置为3
    //囚鸟先生
    'VAR_PAGE'=>'pageNum',
    'PAGE_LISTROWS'=>20,  //分页 每页显示多少?
    'PAGE_NUM_SHOWN'=>10, //分页 页标数字多少?
	
    'SESSION_AUTO_START'        =>  true,
    'TMPL_ACTION_ERROR'         =>  'Public:success', // 默认错误跳转对应的模板文?
    'TMPL_ACTION_SUCCESS'       =>  'Public:success', // 默认成功跳转对应的模板文?
    'USER_AUTH_ON'              =>  true,//true
    'USER_AUTH_TYPE'			=>  2,		// 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY'             =>  'authId',	// 用户认证SESSION标记
    'ADMIN_AUTH_KEY'			=>  'administrator',
    'USER_AUTH_MODEL'           =>  'User',	// 默认验证数据表模?
    'AUTH_PWD_ENCODER'          =>  'md5',	// 用户认证密码加密方式
    'USER_AUTH_GATEWAY'         =>  '/Key/Public/login',// 默认认证网关
    'NOT_AUTH_MODULE'           =>  '/Index/index',	// 默认无需认证模块
    'REQUIRE_AUTH_MODULE'       =>  '',		// 默认需要认证模?
    'NOT_AUTH_ACTION'           =>  '',// 默认无需认证操作
    'REQUIRE_AUTH_ACTION'       =>  '',		// 默认需要认证操?
    'GUEST_AUTH_ON'             =>  false,    // 是否开启游客授权访?
    'GUEST_AUTH_ID'             =>  0,        // 游客的用户ID
    'DB_LIKE_FIELDS'            =>  'title|remark',
    'RBAC_ROLE_TABLE'           =>  'think_role',
    'RBAC_USER_TABLE'           =>  'think_role_user',
    'RBAC_ACCESS_TABLE'         =>  'think_access',
    'RBAC_NODE_TABLE'           =>  'think_node',
    'SHOW_PAGE_TRACE'           =>  true ,   //显示调试信息
    
    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static'
    ),
    
    //应用类库不再需要使用命名空?
    'APP_USE_NAMESPACE'    =>    false,
		
	//登录?
	'loginIn' => array("destServID"=>16000,'messageID'=>'loginIn'),
	'loginOut'=> array("destServID"=>16000,'messageID'=>'loginOut'),
	//2015-11-24配置T1，T2系统，可配置�?
    'TSysName'=> array('1'=>"T1系统 ",'2'=>"T2系统",'3'=>'T3系统','4'=>'T4系统','5'=>'T5系统','6'=>'T6系统')
    
);
