<!--
<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="1" />
	<input type="hidden" name="numPerPage" value="${model.numPerPage}" />
	<input type="hidden" name="orderField" value="${param.orderField}" />
	<input type="hidden" name="orderDirection" value="${param.orderDirection}" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="" method="post">
	<div class="searchBar">
		<ul class="searchContent">
			<li>
				<label>搜索条件:</label>
				<input type="text" name="keywords" size="32" value="<?php echo $_POST['keywords'];?>" />
			</li>
		</ul>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
				<li><a class="button" href="demo_page6.html" target="dialog" mask="true" title="查询框"><span>高级检索</span></a></li>
			</ul>
		</div>
	</div>
	</form>
</div>
-->
<?php include BASE_DIR.'/Views/PageHeader.php';?>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
		<?php if($type == 2):?>
			<li><a class="edit" href="<?php echo $this->_url('RouteQry');?>/mer/{<?php echo $dbgrid->var_name;?>}" target="dialog" rel="posinfoqry" width="800" height="450" title="查看路由绑定信息" warn="请选择一条数据"><span>查看路由绑定信息</span></a></li>
			<li class="line">line</li>
		<?php else:?>
			<li><a class="add" href="<?php echo $this->_url('Create');?>" target="dialog" rel="posinfoedit" width="402" height="320" title="终端信息添加"><span>添加</span></a></li>
			<li><a title="数据和终端绑定有关联，确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="<?php echo $this->_url('Remove02');?>/mer/{<?php echo $dbgrid->var_name;?>}/source/spos3" class="delete"><span>删除</span></a></li>
			<li><a class="edit" href="<?php echo $this->_url('Update');?>/mer/{<?php echo $dbgrid->var_name;?>}" target="dialog" rel="posinfoedit" width="402" height="320" title="终端信息修改" warn="请选择一条数据"><span>修改</span></a></li>
			<li class="line">line</li>
		<?php endif;?>
		</ul>
	</div>
	<?php echo $html;?>
</div>
