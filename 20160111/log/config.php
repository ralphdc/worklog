<?php 

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

if(getIP() == '172.20.0.94'  /* || getIP() == '172.20.0.51' */     )
{
    $javahome = 'http://172.20.0.94:15003/'; //蒋工环境
    //$javahome = 'http://172.17.6.180:8080';
}elseif(getIP() == '172.20.0.17'  /* || getIP() == '172.20.0.51' */     )
{
    $javahome = 'http://172.20.0.17:15003/'; //环境
    //$javahome = 'http://172.17.6.180:8080';
}else
{
    //$javahome = 'http://172.17.6.180:8080/';//开发环境
    //$javahome = 'http://172.17.6.167:15003/';//开发环境
    //$javahome = 'http://172.17.6.167:15003/';//测试环境
    //$javahome = 'http://172.20.0.17:15003/';//测试环境  洪超IP地址；
    //$javahome = 'http://172.20.0.29:8080';
    $javahome = 'http://172.20.0.94:15003';
}


return array(
    'URL_MODEL'                 =>  1, // 如果你的环境不支持PATHINFO 请设置为3
    //数据库配置
    'DB_TYPE'                   =>  'mysql',
   // 'DB_HOST'                   =>  '127.0.0.1',
    'DB_HOST'                   =>  '172.17.6.167',
    'DB_NAME'                   =>  'dwztp321',
    'DB_USER'                   =>  'root',
    'DB_PWD'                    =>  '123456',
    'DB_PORT'                   =>  '3306',
    'DB_PREFIX'                 =>  'think_',

    //囚鸟先生
    'VAR_PAGE'=>'pageNum',
    'PAGE_LISTROWS'=>20,  //分页 每页显示多少条
    'PAGE_NUM_SHOWN'=>5, //分页 页标数字多少个
    
    'SESSION_AUTO_START'        =>  true,
    'TMPL_ACTION_ERROR'         =>  'Public:success', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'       =>  'Public:success', // 默认成功跳转对应的模板文件
    'USER_AUTH_ON'              =>  true,
    'USER_AUTH_TYPE'            =>  2,        // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY'             =>  'authId',    // 用户认证SESSION标记
    'ADMIN_AUTH_KEY'            =>  'administrator',
    'USER_AUTH_MODEL'           =>  'User',    // 默认验证数据表模型
    'AUTH_PWD_ENCODER'          =>  'md5',    // 用户认证密码加密方式
    'USER_AUTH_GATEWAY'         =>  'Admin/Public/login',// 默认认证网关
    'NOT_AUTH_MODULE'           =>  'Admin/Public',    // 默认无需认证模块
    'REQUIRE_AUTH_MODULE'       =>  '',        // 默认需要认证模块
    'NOT_AUTH_ACTION'           =>  '',        // 默认无需认证操作
    'REQUIRE_AUTH_ACTION'       =>  '',        // 默认需要认证操作
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
    
    //应用类库不再需要使用命名空间
    'APP_USE_NAMESPACE'    =>    false,
        
    //日志服务相关的java访问的地址

    //'LOG_SERVER_NAME'    =>    'http://172.17.1.75:15002/',

    'LOG_SERVER_NAME'    =>    $javahome,
    //'LOG_SERVER_NAME'    =>    'http://172.17.6.180:8080/',
    //'LOG_SERVER_NAME'    =>    'http://172.17.6.167:15003/',

    
    
);


