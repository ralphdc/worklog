<?php
return array(
	//与后台通讯地址
	//登录
	'ProxyLogin' =>"SeraSap:tcp -h 172.17.3.131  -p 10001",//李海丰本地172.20.0.143//测试机172.17.3.131
	//权限验证
	'ProxyComp' => "SeraSap:tcp -h 172.17.3.131  -p 10002",
	//工作流
	'ProxyWorkProcess' => "SeraSap:tcp -h 172.17.3.131 -p 10003",//包晓亮本地172.20.0.13 -p 10001//测试机 172.17.3.131 -p 10003
	
	//ralasafe地址
	'ralasafeIP' => 'http://172.20.0.143:8081/',
    'URL_MODEL'                 =>  1, // 如果你的环境不支持PATHINFO 请设置为3
	//数据库配置
    'DB_TYPE'                   =>  'mysql',
    'DB_HOST'                   =>  '127.0.0.1',
    'DB_NAME'                   =>  'mysql',
    'DB_USER'                   =>  'root',
    'DB_PWD'                    =>  '123456',
    'DB_PORT'                   =>  '3306',
    'DB_PREFIX'                 =>  '',

    //囚鸟先生
    'VAR_PAGE'=>'pageNum',
    'PAGE_LISTROWS'=>20,  //分页 每页显示多少条
    'PAGE_NUM_SHOWN'=>5, //分页 页标数字多少个
	
    'SESSION_AUTO_START'        =>  true,
    'TMPL_ACTION_ERROR'         =>  'Public:success', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'       =>  'Public:success', // 默认成功跳转对应的模板文件
    'USER_AUTH_ON'              =>  true,
    'USER_AUTH_TYPE'			=>  2,		// 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY'             =>  'authId',	// 用户认证SESSION标记
    'ADMIN_AUTH_KEY'			=>  'administrator',
    'USER_AUTH_MODEL'           =>  'User',	// 默认验证数据表模型
    'AUTH_PWD_ENCODER'          =>  'md5',	// 用户认证密码加密方式
    'USER_AUTH_GATEWAY'         =>  'Serivice/Public/login',// 默认认证网关
    'NOT_AUTH_MODULE'           =>  'Serivice/Public',	// 默认无需认证模块
    'REQUIRE_AUTH_MODULE'       =>  '',		// 默认需要认证模块
    'NOT_AUTH_ACTION'           =>  '',		// 默认无需认证操作
    'REQUIRE_AUTH_ACTION'       =>  '',		// 默认需要认证操作
    'GUEST_AUTH_ON'             =>  false,    // 是否开启游客授权访问
    'GUEST_AUTH_ID'             =>  0,        // 游客的用户ID
    'DB_LIKE_FIELDS'            =>  'title|remark',
    'RBAC_ROLE_TABLE'           =>  'think_role',
    'RBAC_USER_TABLE'           =>  'think_role_user',
    'RBAC_ACCESS_TABLE'         =>  'think_access',
    'RBAC_NODE_TABLE'           =>  'think_node',
    'SHOW_PAGE_TRACE'           =>  false ,   //显示调试信息
    
    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static'
    ),
	
    'RedisLocal'				=>'172.30.2.153',
	'RedisLocalPort'			=>'6379',
	
	'RedisVisitorHash'			=>'Service:Visitor:Hash',
	'RedisVisitorList'			=>'Service:Visitor:List',
	'RedisVisitorListCount'		=>'Service:Visitor:Set',
	
    //应用类库不再需要使用命名空间
    'APP_USE_NAMESPACE'    =>    false,
		
	//日志服务相关的java访问的地址
	//'SERVICE_JAVA_NAME'=>$javahome,
	'SERVICE_JAVA_NAME'=> 'http://172.17.6.180:8080',
	//memcache 配置
	'DATA_CACHE_TIME'       =>  0,      // 数据缓存有效期 0表示永久缓存
	'DATA_CACHE_COMPRESS'   =>  false,   // 数据缓存是否压缩缓存
	'DATA_CACHE_CHECK'      =>  false,   // 数据缓存是否校验缓存
	'DATA_CACHE_PREFIX'     =>  '',     // 缓存前缀
	'DATA_CACHE_TYPE'       =>  'Memcache',  // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
	'MEMCACHE_HOST'   =>  'tcp://172.20.0.206:11211',
		
	//C++监控服务地址
	'MNT_GSOAP_SERVER_DealNotice' => 'http://172.20.12.73:8334/DealNotice.wsdl',
	'MNT_GSOAP_SERVER' => 'http://172.20.12.73:8333/DealCmd.wsdl',
		
	//redis服务器和端口配置
	'REDISSERVER' => '172.17.6.148',
	'REDISSERVER_PORT' => '9003',
	//运维环境数组配置
	'CONST_ENVIRONMENT'=>array('1'=>'运维镜像','2'=>'生产','3'=>'测试镜像','4'=>'测试','5'=>'开发',),//1-运维镜像，2--生产，3--测试镜像，4--测试，5--开发 
	/* 新增加菜单权限对应的产品ID*/
	'PRIVATE_PRODUCTID' => '10003',
	
	'LoginMark'=>'SERVICE_MANAGE',
    
    //韦工测试环境
    'loginRequest'=>'http://172.30.2.154:8080/kms/login/ajaxsubmit.do' , 
    //韦工测试环境；
    'loginMenu'=>'http://172.30.2.154:8080/kms/actiontree/myMenus.do',
    'loginPWD'=>'hhttp://172.30.2.154:8080/kms/user/modifyPassword.do' ,
	

    
);
