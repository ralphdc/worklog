//新增节点；
function xgdCreateNode() {
	if (!scene || !JPStore) return false;

	var texts = String(new Date().getTime());
	var nodeText = 'Node' + texts;

	var nodeObj = new JTopo.Node(nodeText);
	nodeObj.fillColor = nodeColor;

	if (newX > 200) {
		newX = 30;
	}

	nodeObj.setLocation(newX, newY);
	nodeObj.setSize(nodeWidth, nodeHeight);
	nodeObj.setImage(nodeImage);
	//添加唯一标识符；
	nodeObj.setXgdId(texts);

	//初始化节点信息,存入本地;
	nodeObj.addEventListener('mouseup', function(event) {
		if (event.button == 0) {
			//左键处理函数；

		} else if (event.button == 2) {
			//右键处理函数；
			$("#nodeEditMenu").css({
				top: event.pageY,
				left: event.pageX
			}).fadeIn();
			if($(".lmenue").length > 0){
				$(".lmenue").remove();
			}
			
			
		}
	});

	//JPStore
	if (scene) {
		scene.add(nodeObj);
		beginNode = null;
		newX += 20;
	}
}


//新增子拓扑；
function xgdCreateChildTP() {
	if (!scene || !JPStore) return false;

	var texts = String(new Date().getTime());
	var nodeText = 'Container_' + texts;

	var nodeObj = new JTopo.Node(nodeText);
	nodeObj.fillColor = nodeChild.nodeColor;

	if (newX > 200) {
		newX = 30;
	}

	nodeObj.setLocation(newX, newY);
	nodeObj.setSize(nodeChild.nodeWidth, nodeChild.nodeHeight);
	nodeObj.setImage(nodeChild.nodeChildImage);
	//添加唯一标识符；
	nodeObj.setXgdId(texts);

	//初始化节点信息,存入本地;
	nodeObj.addEventListener('mouseup', function(event) {
		if (event.button == 0) {
			//左键处理函数；

		} else if (event.button == 2) {
			//右键处理函数；
			$("#childTPEditMenu").css({
				top: event.pageY,
				left: event.pageX
			}).fadeIn();
			if($(".lmenue").length > 0){
				$(".lmenue").remove();
			}
		}
	});

	//JPStore
	if (scene) {
		scene.add(nodeObj);
		beginNode = null;
		newX += 20;
	}
}


//重绘节点；
//只有编辑和预览,监控状态才使用此函数；
function displayNodes(text, x, y, width, height, image, xgdid,dtype) {
	if (!scene || !JPStore) return false;
	var nodeObj = new JTopo.Node(text);
	nodeObj.setLocation(x, y);
	nodeObj.setSize(width, height);
	if(!image) image = 'node2.png';
	nodeObj.setImage("/Public/Images/jtopo/"+image);
	nodeObj.setXgdId(xgdid);
	nodeObj.setDtype(dtype);

	//判断当前操作状态： 编辑，新建，预览拓扑图；
	//只有预览状态下，值才为真；
	var dialog = $.pdialog.getCurrent();
	//console.log(dialog);
	//获取状态符号；
	//预览、监控状态不可拖拽；
	var currentAct = dialog.find('.currentActionName').val();
	if(currentAct == 'preview' || currentAct == 'monitor'){
		nodeObj.dragable = false;
	}
	//编辑状态弹出右键；
	if(currentAct=='edit'){
		nodeObj.addEventListener('mouseup', function(event) {
			if (event.button == 0) {
				//左键处理函数,拖拽，覆盖原有值
			} else if (event.button == 2) {
				//编辑子拓扑；
				if(dtype == 'childtp'){
					//右键处理函数；
					$("#childTPEditMenu").css({
						top: event.pageY,
						left: event.pageX
					}).fadeIn();
				}else{
					//右键处理函数；
					$("#nodeEditMenu").css({
						top: event.pageY,
						left: event.pageX
					}).fadeIn();
				}
			}
		})
	}
	
	//预览、监控状态下弹出左键菜单；
	if (currentAct == 'monitor') {
		nodeObj.addEventListener('mouseover', function(e) {
			if($(".lmenue").length > 0){
				$(".lmenue").remove();
			}
			$.ajaxSettings.global = false;
			$.ajax({
				type: 'POST',
				cache: false,
				url: '/index.php/Service/Graph/getNodeInfo',
				data: {
					'nodeid': xgdid,
					'dtype' : dtype
				},
				dataType: 'json',
				success: function(data) {
					if (data.nodeType && dtype != 'childtp') {
						var nodeType = '已监控节点';
						var nodeGroup = data.nodeGroup;
						var deviceType = data.deviceType;
						var deviceName = dvNames[deviceType] ? dvNames[deviceType] : '--' ;
						var hostName = data.nodename;
						var nodeid = data.nodeid;
						var nodeip = data.nodeip;
						var lmenu = '<div class="lmenue"  mouseleave="hideLeftMenue(this)" >';
						lmenu += '<ul>';
						lmenu += '<li class="jp_edit" align="right"><a href="javascript:void(0)" class="nodeinfo" onclick="showNodeInfo(' + nodeid + ')">查看详细>></a></li>';
						lmenu += '<li class="jp_del"><p>节点类型：<span class="ntype">' + nodeType + '</span></p></li>';
						lmenu += '<li class="jp_del"><p>节点分组：<span class="ngroup">' + nodeGroup + '</span></p></li>';
						lmenu += '<li class="jp_del"><p>设备类型：<span class="dtype">' + deviceName + '</span></p></li>';
						lmenu += '<li class="jp_del"><p>主机名：<span class="hname">' + hostName + '</span></p></li>';
						lmenu += '<li class="jp_del"><p>主机ID：<span class="hid">' + nodeid + '</span></p></li>';
						lmenu += '<li class="jp_del"><p>主机IP：<span class="hip">' + nodeip + '</span></p></li>'
						lmenu += '</ul>';
						lmenu += '</div>';
					}
					
					if (data.id && dtype == 'childtp') {
						var from_tpid = dialog.find('.TpId').val();
						var to_tpid = data.id;
						var lmenu = '<div class="lmenue"  mouseleave="hideLeftMenue(this)" >';
						lmenu += '<ul>';
						lmenu += '<li class="jp_edit" align="right"><a href="javascript:void(0)" class="nodeinfo" onclick="redirectTP(' + to_tpid + ','+ from_tpid +')">查看详细>></a></li>';
						lmenu += '<li class="jp_del"><p>节点名称：<span class="ntype">' + data.tname + '</span></p></li>';
						lmenu += '<li class="jp_del"><p>节点类型：<span class="ngroup">拓扑容器</span></p></li>';
						lmenu += '</ul>';
						lmenu += '</div>';
					}
					$("#toolContent").after(lmenu);
					$(".lmenue").css({
						top: e.pageY,
						left: e.pageX
					}).fadeIn();
				}
			})
		});
		
		nodeObj.addEventListener('mouseout', function(e) {
			$(".lmenue").remove();
		});
	}

	if (scene) {
		scene.add(nodeObj);
	}
}

function redirectTP(targetid){
	var fromid = $(".TpId").val();
	
	$.pdialog.reload("/index.php/Service/Graph/monitor?id="+targetid+"&monitor_from="+fromid);
}

function displayLink(beginNode, endNode, width, radius, color, dcid) {

	var lk = new JTopo.Link(beginNode, endNode);
	lk.lineWidth = width; //宽度
	//lk.bundleGap 		= 	lk_bundleGap;
	//lk.textOffsetY 		= 	lk_textOffsetY ;
	lk.arrowsRadius = lk_arrowsRadius; //箭头弧度；
	lk.strokeColor = lk_strokeColor; //连线颜色；

	lk.dcid = dcid;
	//添加连接右键菜单；
	lk.addEventListener('mouseup', function(event) {
		//右键处理函数；
		if (event.button == 2) {
			$("#linkmenu").css({
				top: event.pageY,
				left: event.pageX
			}).show();
		}
	})
	scene.add(lk);
}



//清除界面；
function xgdClearScene() {
	if (scene) {
		scene.clear();
	}

	nodeSet = [];
	linkSet = [];
}

function deleteNodeData(sNode) {
	for (q = 0; q < nodeSet.length; q++) {
		if (nodeSet[q].text == sNode[0].text) {
			nodeSet.splice(q, 1);
			break;
		}
	}
}

function deleteLinkData(sLink) {
	for (p = 0; p < linkSet.length; p++) {
		if (linkSet[p].dcid == sLink[0].dcid) {
			linkSet.splice(p, 1);
			break;
		}
	}
}


//删除节点；
function deleteNode() {
	if (!scene) return false;

	var sNode = scene.selectedElements;
	var text = sNode[0].text;
	if (sNode[0] instanceof JTopo.Node) {
		var nodeLink = sNode[0].inLinks;
		//console.log(nodeLink); 
		if (nodeLink) {
			deleteLinkData(nodeLink);
		}
		deleteNodeData(sNode);
		scene.remove(sNode[0]);
		JPStore.removeItem(text);
	} else {
		createTextNode('请先选择节点，然后点击删除！');
	}
}

//删除连线；
function deleteLink() {
	if (!scene) return false;

	var lkNode = scene.selectedElements;
	if (lkNode[0] instanceof JTopo.Link) {
		//删除缓存的数据；
		deleteLinkData(lkNode);
		scene.remove(lkNode[0]);
	} else {
		//console.log('-------------');
		createTextNode('请先选择连线，然后点击删除！');
	}
}

//创建提示；
function createTextNode(text) {
	if (!scene) return false;

	var msgNode = new JTopo.TextNode(text);
	msgNode.zIndex++;
	msgNode.setBound(msgX, msgY);
	msgNode.fontColor = msgColor;
	msgNode.font = msgFont;
	scene.add(msgNode);

	setTimeout(deleteTextNode(msgNode), 2000);
}

function deleteTextNode(textnode) {
	if (!scene) return false;
	return function() {
		scene.remove(textnode);
	}
}

function xgdLocalSave() {

	if (!scene) return false;

	var jtElements = scene.getDisplayedElements();

	var tmp = 0;
	var jpNodes = [];
	var jpLinks = [];
	for (tmp = 0; tmp < jtElements.length; tmp++) {
		//console.log(jtElements[tmp].elementType);
		//节点；
		if (jtElements[tmp].elementType == 'node') {
			var node_id_obj = jtElements[tmp].getXgdId();
			var node_id = node_id_obj.xgdid;
			
			var nodeTypeObj = jtElements[tmp].getDtype();
			var nodeType = nodeTypeObj.dtype;
 
			var nodeSet = {
				'xgdid': node_id,
				'text': jtElements[tmp].text,
				'px': jtElements[tmp].x,
				'py': jtElements[tmp].y,
				'width': jtElements[tmp].width,
				'height': jtElements[tmp].height,
				'alpha': jtElements[tmp].alpha,
				'borderRadius': jtElements[tmp].borderRadius,
				'fillColor': jtElements[tmp].fillColor,
				'fontColor': jtElements[tmp].fontColor,
				'scaleX': jtElements[tmp].scaleX,
				'scaleY': jtElements[tmp].scaleY,
				'selected': jtElements[tmp].selected,
				'shadow': jtElements[tmp].shadow,
				'visible': jtElements[tmp].visible,
				'zIndex': jtElements[tmp].zIndex,
				'dtype': nodeType
			};
			//console.log(nodeSet); return;
			jpNodes.push(nodeSet);
		}
		//连线；
		if (jtElements[tmp].elementType == 'link') {
			var nodeFrom = jtElements[tmp].nodeA;
			var nodeFromText = nodeFrom.text;
			var nodeFromIdObj = nodeFrom.getXgdId();
			var nodeFromId = nodeFromIdObj.xgdid;


			var nodeTo = jtElements[tmp].nodeZ;
			var nodeToText = nodeTo.text;
			var nodeToIdObj = nodeTo.getXgdId();
			var nodeToId = nodeToIdObj.xgdid;


			var linkSet = {
				'text': jtElements[tmp].text,
				'fromText': nodeFromText,
				'toText': nodeToText,
				'fromId': nodeFromId,
				'toId': nodeToId,
				'width': jtElements[tmp].width,
				'height': jtElements[tmp].height,
				'alpha': jtElements[tmp].alpha,
				'arrowsRadius': jtElements[tmp].arrowsRadius,
				'bundleGap': jtElements[tmp].bundleGap,
				'dcid': jtElements[tmp].dcid,
				'dragable': jtElements[tmp].dragable,
				'fillColor': jtElements[tmp].fillColor,
				'font': jtElements[tmp].font,
				'fontColor': jtElements[tmp].fontColor,
				'lineJoin': jtElements[tmp].lineJoin,
				'lineWidth': jtElements[tmp].lineWidth,
				'shadow': jtElements[tmp].shadow,
				'strokeColor': jtElements[tmp].strokeColor
			}
			jpLinks.push(linkSet);
		}

	}
	var nodeSetStr = JSON.stringify(jpNodes);
	var linkSetStr = JSON.stringify(jpLinks);
	console.log(nodeSet);
	JPStore.setItem('nodeSaveItem', nodeSetStr);
	JPStore.setItem('linkSaveItem', linkSetStr);

}

//Ajax提交；
function submitTP() {
	xgdLocalSave();
	$.pdialog.open("/index.php/Service/Graph/graphSave", 'graphsave', '保存拓扑', {
		'width': 500,
		'height': 300,
		'mask': true
	});
}

function findNodeByText(text) {
	var displayNodes = scene.getDisplayedNodes();
	var p;
	for (p = 0; p < displayNodes.length; p++) {
		if (displayNodes[p].elementType == 'node' && displayNodes[p].text == text) {
			return displayNodes[p];
		}
	}
}

function findNodeById(nid) {
	var displayNodes = scene.getDisplayedNodes();
	var p;
	for (p = 0; p < displayNodes.length; p++) {
		var xgdidobj = displayNodes[p].getXgdId();
		var xgdid = xgdidobj.xgdid;
		if (displayNodes[p].elementType == 'node' && xgdid == nid) {
			return displayNodes[p];
		}
	}
}

/*function xgdFlush() {


	if (!scene) return false;
	if (!JPStore) return false;

	xgdLocalSave();

	scene.clear();
	var nodeSaveInfo = JPStore.getItem('nodeSaveItem');
	var linkSaveInfo = JPStore.getItem('linkSaveItem');
	//console.log(nodeSaveInfo); 
	if (nodeSaveInfo) {
		var node_obj = JSON.parse(nodeSaveInfo);
		var tmp;
		for (tmp = 0; tmp < node_obj.length; tmp++) {
			displayNodes(node_obj[tmp].text, node_obj[tmp].px, node_obj[tmp].py, node_obj[tmp].width, node_obj[tmp].height, node_obj[tmp].image, node_obj[tmp].xgdid);

		}
	}

	if (linkSaveInfo) {
		var link_obj = JSON.parse(linkSaveInfo);
		var tmp;
		for (tmp = 0; tmp < link_obj.length; tmp++) {

			var fromText = link_obj[tmp].fromText;
			var fromNode = findNodeByText(fromText);

			var fromId = link_obj[tmp].fromId;
			var fromNode = findNodeById(fromId);
			var toId = link_obj[tmp].toId;
			var toNode = findNodeById(toId);
			displayLink(fromNode, toNode, link_obj[tmp].lineWidth, link_obj[tmp].arrowsRadius, link_obj[tmp].fillColor, link_obj[tmp].dcid);

		}
	}
	alertMsg.correct("刷新完毕!");
}*/


function xgdEditShow(obj) {
	
	scene.clear();
	var topoContent = obj;
	var nodes = topoContent.node;
	var links = topoContent.link;
	var i, j;
	for (i = 0; i < nodes.length; i++) {
		var dtype = nodes[i].dtype;
		var image = jticons[dtype];
		displayNodes(nodes[i].text, nodes[i].px, nodes[i].py, nodes[i].width, nodes[i].height,image, nodes[i].xgdid,nodes[i].dtype);
	}
	//console.log(nodes);
	//console.log(links);
	for (j = 0; j < links.length; j++) {
		var fromId = links[j].fromId;
		var fromNode = findNodeById(fromId);
		var toId = links[j].toId;
		var toNode = findNodeById(toId);
		displayLink(fromNode, toNode, links[j].lineWidth, links[j].arrowsRadius, links[j].fillColor, links[j].dcid);
	}
}

function xgdMonitorShow(json) {
	scene.clear();
	var topoContent = obj;
	var nodes = topoContent.node;
	var links = topoContent.link;
	var i, j;
	for (i = 0; i < nodes.length; i++) {
		var dtype = nodes[i].dtype;
		var image = jticons[dtype];
		displayNodes(nodes[i].text, nodes[i].px, nodes[i].py, nodes[i].width, nodes[i].height, nodes[i].image, nodes[i].xgdid,dtype);
	}
	//console.log(nodes);
	//console.log(links);
	for (j = 0; j < links.length; j++) {

		var fromId = links[j].fromId;
		var fromNode = findNodeById(fromId);


		var toId = links[j].toId;
		var toNode = findNodeById(toId);

		displayLink(fromNode, toNode, links[j].lineWidth, links[j].arrowsRadius, links[j].fillColor, links[j].dcid);
	}

}

function runScreen() {
	runPrefixMethod(stage.canvas, "RequestFullScreen");
	/*$(document).keyup(function(e){
		if(e.keyCode == 27){
			$.pdialog.closeCurrent();
		}
	})*/

	$(document).bind('mousedown', function(e) {
		if (f_IsFullScreen) {
			exitFullscreen();
		}
	})
}

function runPrefixMethod(element, method) {
	var usablePrefixMethod;
	["webkit", "moz", "ms", "o", ""].forEach(function(prefix) {
		if (usablePrefixMethod) return;
		if (prefix === "") {
			// 无前缀，方法首字母小写
			method = method.slice(0, 1).toLowerCase() + method.slice(1);
		}
		var typePrefixMethod = typeof element[prefix + method];
		if (typePrefixMethod + "" !== "undefined") {
			if (typePrefixMethod === "function") {
				usablePrefixMethod = element[prefix + method]();
			} else {
				usablePrefixMethod = element[prefix + method];
			}
		}
	});

	return usablePrefixMethod;
};

//判断浏览器是否全屏 
function f_IsFullScreen() {
	return (document.body.scrollHeight == window.screen.height && document.body.scrollWidth == window.screen.width);
}


// 判断浏览器种类
function exitFullscreen() {
	if (document.exitFullscreen) {
		document.exitFullscreen();
	} else if (document.mozCancelFullScreen) {
		document.mozCancelFullScreen();
	} else if (document.webkitExitFullscreen) {
		document.webkitExitFullscreen();
	}
	
	if($(".lmenue").length && $(".lmenue").is(":visible")){
		$(".lmenue").hide();
	}
}

//新增子拓扑；
function xgdNewTP() {

}

function xgdDebug() {
	console.log(nodeSet);
	console.log(linkSet);
}

function xgdMoveCenter() {
	if (!stage) return false;
	var disNodes = scene.getDisplayedNodes();
	if (!disNodes.length) {
		alertMsg.error("屏幕没有节点，点击无效！");
		return false;
	}
	stage.centerAndZoom();
}

function xgdMoveStage(obj) {
	if (!stage) return false;
	if ($(obj).is(':checked')) {
		var modeState = $(obj).val();
		stage.mode = modeState;
	} else {
		stage.mode = stageMode;
	}
}

function editNode(panelid) {
	$.pdialog.open("/index.php/Service/Graph/editNode", panelid, '编辑节点', {
		'width': 600,
		'height': 500,
		'mask': true
	});
}

function editChildTP(panelid) {
	$.pdialog.open("/index.php/Service/Graph/editChildTP", panelid, '编辑子拓扑', {
		'width': 600,
		'height': 500,
		'mask': true
	});
}

function editChildNode(panelid) {
	$.pdialog.open("/index.php/Service/Graph/editChildTP", panelid, '编辑节点', {
		'width': 600,
		'height': 500,
		'mask': true
	});
}

function xgdfindValue() {
	var svId = $("#serviceid").val();
	$("#targetdevice").find("option").each(function() {
		if ($(this).val() == svId) {
			$(this).attr("selected", true);
			state = 1;
		}
	})
	if (state) $("#targetdevice").attr('disabled', true);
}


function setNodeInfo() {
	var targetInfo = $("#targetdevice").find("option:selected").text();
	var targetArr = targetInfo.split('-');
	var serviceip = targetArr[0];
	var servicename = targetArr[1];
	var serviceid = $("#targetdevice").val();
	$("#serviceid").val(serviceid);
	$("#servicename").val(servicename);
	$("#serviceip").val(serviceip);
}

function getServiceList() {
	var vals = $("#servicename").val();
	if (vals == '' || vals == undefined) {
		alertMsg.error('请输入主机名或IP');
		return false;
	}

	$.ajax({
		type: "POST",
		cache: false,
		dataType: 'json',
		data: {
			'host': vals
		},
		url: '/index.php/Service/GraphSearch/searchHost',
		success: function(da) {
			if (da.statusCode == 1) {
				$("#serviceid").val(da.id);
				$("#serviceip").val(da.ip);
				$("#servicename").val(da.hostName);

				$("#targetdevice").find("option").each(function() {
					if ($(this).val() == da.id) {
						$(this).attr("selected", true);
						state = 1;
					}
				})
				if (state) $("#targetdevice").attr('disabled', true);
				alertMsg.correct(da.message);
			} else {
				alertMsg.error(da.message);
			}
		}
	})
}


function saveStage() {
	var canvas = document.getElementById('canvas');
	var jsonStr = stage.toJson();
	//console.log(jsonStr);
}

function showNodeInfo(id) {
	var dialog = $.pdialog.getCurrent();
	dialog.find(".dialogHeader").find("h1").html('节点详细信息');
	var tpid = dialog.find(".TpId").val();
	if(monitor_window){
		window.clearInterval(monitor_window);
	}
	
	$.pdialog.reload('/index.php/Service/Graph/Detail?id=' + id + '&tpid=' + tpid);
	
}

function goBack(tpid) {
	var dialogId = 'monitorTP';
	var dialog = $("body").data(dialogId);
	$.pdialog._current = dialog;
	$.pdialog.reload('/index.php/Service/Graph/monitor?id=' + tpid);
}


function createWarn()
{
	var node = new JTopo.Node();
	node.setImage('./img/topo/'+ icon +'.png', true);
	node.fontColor = '0,0,0';
	node.setLocation(x, y);
	scene.add(node);

	node.mouseover(function(){
		this.text = text;
	});
	node.mouseout(function(){
		this.text = null;
	});
}

function monitorNodeInfo() {
	var displayNodes = scene.getDisplayedNodes();
	var p;
	var nodeids = [];
	if (displayNodes.length) {
		for (p = 0; p < displayNodes.length; p++) {
			if (displayNodes[p].elementType == 'node') {
				var xgdidobj = displayNodes[p].getXgdId();
				var xgdid = xgdidobj.xgdid;
				nodeids.push(xgdid);
			}
		}

		if (nodeids.length > 0) {
			var nodeids_str = nodeids.join(",");
			$.ajax({
				global:false,
				type: "POST",
				dataType: 'json',
				cache: false,
				timeout: 2000,
				data: {
					'ids': nodeids_str
				},
				url: '/index.php/Service/Graph/monitorNodeInfo',
				success: function(data) {
					if(data.statusCode == DWZ.statusCode.ok){

						var monitordata = data.resinfo;
						var i;
						for(i=0; i<monitordata.length; i++){
							if(monitordata[i].warns > 0){
								var warnNode = findNodeById(monitordata[i].id);
									warnNode.alarm = '' + monitordata[i].warns;
						            warnNode.alarmColor = '255,0,0';
						            warnNode.alarmAlpha = 0.9;
							}

							if(monitordata[i].warns == 0){
								var warnNode = findNodeById(monitordata[i].id);
									warnNode.alarm = null;
							}
						}

					}else{
						//alertMsg.error(data.message);
						console.group("ErrorMessage:")
						console.log("==================没有读取到数据====================");
						console.groupEnd();
					}
				},
				error: function(xhr, msg, eobj) {
					alertMsg.error(msg);
				}
			})
		}
	}
}

function hideLeftMenue(obj){
	$(obj).hide();
}