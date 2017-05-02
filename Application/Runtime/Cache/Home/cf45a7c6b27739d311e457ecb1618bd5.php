<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Insert title here</title>
<link type="text/css" rel="stylesheet" href="<?php echo ($HOST); ?>Public/bootstrap-3.3.0-dist/dist/css/bootstrap.min.css">
<script type="text/javascript" src="<?php echo ($HOST); ?>Public/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo ($HOST); ?>Public/bootstrap-3.3.0-dist/dist/js/bootstrap.min.js"></script>
<script type="text/javascript">
	function trunpage(pageNo,pageSize,type){
		if(type==-1){
			pageNo=pageNo-1;
			if(pageNo<=0){
				alert("当前已经是第一页了");
				return;
			}
		}else if(type==0){
			pageNo=pageNo+1;
			var a=parseInt(<?php echo ($page["total"]); ?>/pageSize)+1;
			if(pageNo>a){
				alert("当前已经是最后一页了");
				return;
			}
		}else{
			pageNo=type;
		}
		location.href="<?php echo ($HOST); ?>index.php/Home/User/User/loadUserList?pageNo="+pageNo+"&pageSize="+pageSize;
	}
	function openwidows(post){
		if(post==1){
			$("#uid").val("-1");
			$("#window").modal("show");
		}else{
			var uids=$("input[name=uid]:checked");
			if(uids.length==0){
				alert("请选择！");
			}
			if(uids.length!=1){
				alert("请只选择一行进行编辑！");
				return;
			}
			var uid=uids.eq(0).val();
			$.post("<?php echo ($HOST); ?>index.php/Home/User/User/loadUserList2?uid="+uid,function(data){
				alert(data.uid);
				$("#uid").val(data.uid);
				$("#username").val(data.username);
				$("#pass").val(data.pass);
				$("#truename").val(data.truename); 
				$("#address").val(data.address)
			},"json"); 
			$("#window").modal("toggle");
		}
	}
</script>
<style type="text/css">
	b{
		color:red;
		font-weight:bold;
	}
</style>
</head>
<body>
	<form action="index.php/Home/User/PT_reg" method="post" enctype="multipart/form-data">
		<div class="form-group form-inline">
			<label for="filename2">文件：</label>
			<div style="width:50%;">
				<input type="file" name="picture" id="filename" style="display:none;" onchange="filenamechange()"/>
				<input style="width:83%;"  class="form-control" type="text" name="filename2" id="filename2" placeholder="请选择图片" readonly>
				<input type="button" class="btn btn-warning" onclick="btnclicj()" value="上传图片"/>
			</div>
		</div>
	</form>
	<div style="width:98%;margin:1% 1%">
		<button type="button" class="btn btn-info btn-sm glyphicon glyphicon-plus" data-toggle="modal" onclick="openwidows(1)">新增</button>
		<button type="button" class="btn btn-info btn-sm glyphicon glyphicon-pencil" data-toggle="modal" onclick="openwidows(0)" >编辑</button>
		<button type="button" class="btn btn-info btn-sm glyphicon glyphicon-remove" >删除</button>
		<button type="button" class="btn btn-info btn-sm glyphicon glyphicon-share-alt" >导出</button>
	</div>
	<div class="modal fade"  role="dialog" id="window">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
		    <form action="<?php echo ($HOST); ?>index.php/Home/User/User/openwindow " method="post" >
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title">编辑/增加用户</h4>
		      </div>
		      <div class="modal-body">
		      	<input  id="uid" value="" name="uid" type="hidden"/>
				<div class="form-group">
					<label >手机：</label>
					<input class="form-control" type="text" name="username" id="username" placeholder="输入手机号">
				</div>
				<div class="form-group">
					<label >密码：</label>
					<input class="form-control" type="password" name="pass" id="pass" placeholder="输入密码">
				</div>
				<div class="form-group">
					<label >真实姓名：</label>
					<input class="form-control" type="text" name="truename" id="truename" placeholder="输入用户真实姓名">
				</div>
				<div class="form-group">
					<label >地址：</label>
					<input class="form-control" type="text" name="address" id="address" placeholder="输入用户真实姓名">
				</div>  
		      </div>
		      <div class="modal-footer">
		        <button type="reset" class="btn btn-default" >取消</button>
		        <button type="submit" class="btn btn-primary">确认</button>
		      </div>
		    </form>
	    </div>
	  </div> 
	</div> 
	<table class="table table-striped table-bordered table-condensed" style="width:98%;margin:1% 1%">
		<tr>
			<th><input type="checkbox" name=""/></th>
			<th>编号</th>
			<th>用户名</th>
			<th>性别</th>
			<th>姓名</th>
			<th>地址</th>
			<th>头像地址</th>
		</tr>
		<?php if(is_array($page["rows"])): foreach($page["rows"] as $key=>$u): ?><tr>
				<td><input type="checkbox" name="uid"   value="<?php echo ($u["uid"]); ?>"/></td>
				<td><?php echo ($u["uid"]); ?></td>
				<td><?php echo ($u["username"]); ?></td>
				<td><?php echo ($u["sex"]); ?></td>
				<td><?php echo ($u["truename"]); ?></td>
				<td><?php echo ($u["address"]); ?></td>
				<td><?php echo ($u["picture"]); ?></td>
			</tr><?php endforeach; endif; ?>
	</table>
	<nav aria-label="Page navigation" class="text-center">
	  <ul class="pagination pagination-sm">
	  	<li><a href="javascript:void(0);">当前是第<b><?php echo ($page["pageNo"]); ?></b>页</a></li>
	    <li>
	      <a href="javascript:trunpage(<?php echo ($page["pageNo"]); ?>,<?php echo ($page["pageSize"]); ?>,-1);" aria-label="Previous">
	        <span aria-hidden="true">&laquo;</span>
	      </a>
	    </li>
	    <li><a href="javascript:trunpage(<?php echo ($page["pageNo"]); ?>,<?php echo ($page["pageSize"]); ?>,1);">1</a></li>
	    <li><a href="javascript:trunpage(<?php echo ($page["pageNo"]); ?>,<?php echo ($page["pageSize"]); ?>,2);">2</a></li>
	    <li><a href="javascript:trunpage(<?php echo ($page["pageNo"]); ?>,<?php echo ($page["pageSize"]); ?>,3);">3</a></li>
	    <li><a href="javascript:trunpage(<?php echo ($page["pageNo"]); ?>,<?php echo ($page["pageSize"]); ?>,4);">4</a></li>
	    <li><a href="javascript:trunpage(<?php echo ($page["pageNo"]); ?>,<?php echo ($page["pageSize"]); ?>,5);">5</a></li>
	    <li>
	      <a href="javascript:trunpage(<?php echo ($page["pageNo"]); ?>,<?php echo ($page["pageSize"]); ?>,0);" aria-label="Next">
	        <span aria-hidden="true">&raquo;</span>
	      </a>
	    </li>
	    <li><a href="javascript:void(0);">总共有<b><?php echo ($page["total"]); ?></b>条数据</a></li>
	  </ul>
	</nav>
</body>
</html>