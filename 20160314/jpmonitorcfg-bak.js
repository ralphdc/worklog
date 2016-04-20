
		var stage 		= 	null;
		var scene 		= 	null;
		var node		=	null;
		var newX		=	100;
		var newY		=	100;

		//设置屏幕模式；
		var stageModeDefault 		=   'normal';
		var stageModeMove 			=   'drag';
		var stageModeSelect 		=   'select';
		var stageModeEdit			=   'edit';


		//新建节点的宽、高；
		var nodeWidth	=	60;
		var nodeHeight	=	57;	
		var nodeColor	=	'255，255，255';
		var nodeImage	=	'/Public/Images/jtopo/node2.png';
		var nodeType	=   'normal';


		//提示文字的坐标；
		var msgX		=	10;
		var msgY		=	10;
		//提示文字的颜色；
		var msgColor	=	'251,242,9';
		var msgFont		=	'14px bold';
		//定义创建节点初始化坐标；
		var beginNode		=	null;
		var endNode			=	null;


		//  设置背景图片；
		var jpImages = {
			'normal'	:	'/Public/Images/jtopo/node2.png',
			'server'	:	'/Public/Images/jtopo/server.png',
			'firewall'	:	'/Public/Images/jtopo/firewall.png',
			'router'	:	'/Public/Images/jtopo/router.png',
			'transfer'	:	'/Public/Images/jtopo/transfer.png',
			'desktop'	:	'/Public/Images/jtopo/other.png',
			'web'		:	'/Public/Images/jtopo/other.png',
			'virtual'	:	'/Public/Images/jtopo/other.png',
			'balance'	:	'/Public/Images/jtopo/other.png',
			'other'		:	'/Public/Images/jtopo/other.png',
		}
		
		var dvNames = {
			'server'=>'服务器',
			'router'=>'路由器',
			'transfer'=>'交换机',
			'firewall'=>'防火墙',
			'desktop'=>'桌面机',
			'web'=>'WEB站点',
			'virtual'=>'虚拟主机',
			'balance'=>'负载均衡',
			'other'=>'其他'
		}
		//创建连线参数；
		lk_line_Width 		= 	3;
		lk_bundleGap 		= 	20;
		lk_textOffsetY 		= 	3;
		lk_arrowsRadius 	= 	15;
		lk_strokeColor		=	'255,255,0';
		
