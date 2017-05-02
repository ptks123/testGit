<?php
    session_start();
?>
<html>
	<head>
		<meta charset="UTF-8">
		<title>表</title>
		<link type="text/css" rel="stylesheet" href="easyui/themes/default/easyui.css">
		<link type="text/css" rel="stylesheet" href="easyui/themes/icon.css">
		<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="easyui/jquery.easyui.min.js"></script>
		<script type="text/javascript" src="easyui/locale/easyui-lang-zh_CN.js"></script>
		<style type="text/css">
            #ff input{
    	        width:250px;border:1px solid green;height:25px;
            	border-radius:4px;
            }
            #ff tr{height:40px;}
        </style>
		<script type="text/javascript">
		$(function(){
			$('#dg').datagrid({    
			    url:'../index.php/Home/User/User/PT_loadtable',
			    striped:true,
			    pagination:true,
			    rownumbers:true,
			    frozenColumns:[[{field:"aaa",checkbox:true}]],    
			    columns:[[    
                    {field:'uid',title:'编号',width:200,align:'center',hidden:true},    
                    {field:'username',title:'用户名',width:200,align:'center'},    
                    {field:'sex',title:'性别',width:90,align:'center'}, 
                    {field:'truename',title:'姓名',width:100,align:'center',formatter:function(value,row,index){
                    	return "<b style='color:red;'>"+value+"</b>";
                    }},
                    {field:'address',title:'地址',width:200,align:'center'},
                    {field:'card',title:'会员ID',width:200,align:'center'},
                    {field:'name',title:'会员等级',width:200,align:'center'},
			    ]],
			    toolbar: [{
				    text   : '添加',
					iconCls: 'icon-add',
					handler: function(){
						$('#win').window('open');  // open a window
						$("#uid").val("-1");
						
					}
				},'-',{
				    text   : '编辑',
					iconCls: 'icon-edit',
					handler: function(){
						var rows = $("#dg").datagrid("getSelections");
						if(rows.length > 1){
							alert("对不起，你只能选中一行进行编辑！");
							return;
						}
						if(rows.length == 0){
							alert("对不起，请先选中一行进行编辑！");
							return;
						}
						var row = rows[0];

						//表单回填数据
						$("#uid").val(row["uid"]);
						$("#username").val(row["username"]);
						$("#userpass").val(row["pass"]);
						$("#truename").val(row["truename"]);
// 						$('#ff').form('load',{
// 							uid:row[0],
// 							userName:row[1],
// 							userPass:row[2],
// 							trueName:row[3]
// 						});
						$('#win').window('open');  // open a window
					}
				},'-',{
					text   : '删除',
					iconCls: 'icon-delete',
					handler: function(){
						var rows = $("#dg").datagrid("getSelections");
						if(rows.length == 0){
							alert("对不起，请先选中一行删除！");
							return;
						}
						var uids = new Array();
						for(var i=0;i<rows.length;i++){
							uids.push(rows[i]["uid"])
						}
						alert(uids.join(","));
						if(confirm("你确定要删除这些数据吗？")){
		        			$.post("../index.php/Home/User/User/deleteusers",
			    			{
			    				"uids"	   : uids.join(","),//可删除多个
			    				"isvalid"  :0 
			    			},function(data){
			    				$("#dg").datagrid('loading');
			    				$("#dg").datagrid("loadData",{
			    					rows:data.rows,
			    					total:data.total
			    				});
			    				$("#dg").datagrid('loaded');
			    			},"json");
		            	}
					}
				},'-',{
					text   : '导出Excel',
					iconCls: 'icon-reload',
					handler: function(){
						
					}
				},'-',{
					text:"输入姓名：<input type='text' name='user' id='user' style='margin-top:-5px;' placeholder='请输入姓名'/>"
				},'-',{
					text:"输入手机号：<input type='text' name='telphone' id='telphone' style='margin-top:-5px;' placeholder='输入手机号'/>"
				},'-',{
					text   : '查询',
					iconCls: 'icon-reload',
					handler: function(){
						$.post("../index.php/Home/User/User/search",{
							"user"	   : $("#user").val(),
							"telphone" : $("#telphone").val()	
							},function(data){
							$("#dg").datagrid("loadData",{
    							rows:data.rows,
    							total:data.total
							});
							
						},"json");
						
					}
				}]
			});
		
			var pager = $("#dg").datagrid("getPager");
			pager.pagination({
				onSelectPage:function(pageNumber, pageSize){
					$("#dg").datagrid('loading'),
					$.post("../index.php/Home/User/User/PT_loadtable?pageNo="+pageNumber+"&pageSize="+pageSize,function(data){
						$("#dg").datagrid("loadData",{
							rows:data.rows,
							total:data.total
						});
						$("#dg").datagrid('loaded');
					},"json");
				},
				onRefresh:function(pageNumber, pageSize){
					$("#no").val("");
					$("#user").val("");
					$("#telphone").val("");
					$("#dg").datagrid('loading'),
					$.post("../index.php/Home/User/User/PT_loadtable?pageNo="+pageNumber+"&pageSize="+pageSize,function(data){
						$("#dg").datagrid("loadData",{
							rows:data.rows,
							total:data.total
						});
						$("#dg").datagrid('loaded');
					},"json");
				}
			}); 
			$('#win').window('close');// close a window
			$('#win2').window('close');  // close a window
		});
		//搜素功能
		function Update_easyuiTable(){
			//$("#ff").serialize();
			$.post("../index.php?c=Menu&a=Update_easyuiTable",
			{
				"uid"	   : $("#uid").val(),
				"userName" : $("#username").val(),
				"userpass" : $("#userpass").val(),
				"trueName" : $("#truename").val()
			},function(data){
				$("#dg").datagrid('loading');
				$("#dg").datagrid("loadData",{
					rows:data.rows,
					total:data.total
				});
				$("#dg").datagrid('loaded');
				$('#win').window('close');  // close a window
				$('#win2').window('close');  // close a window
				$("#username").val("");
				$("#userpass").val("");
				$("#truename").val("");
			},"json");
			
		}
		//我做的删除功能      的删除的函数
		function delete_easyuiTable(){
			//$("#ff").serialize();
			$.post("../index.php?c=Menu&a=delete_easyuiTable",
			{
				"uid"	   : $("#uid").val()
			},function(data){
				$("#dg").datagrid('loading');
				$("#dg").datagrid("loadData",{
					rows:data.rows,
					total:data.total
				});
				$("#dg").datagrid('loaded');
				$('#win').window('close');  // close a window
				$('#win2').window('close');  // close a window
			},"json");
		}
		//取消按钮的方法
		function a(){
			$("#username").val("");
			$("#userpass").val("");
			$("#truename").val("");
			$('#win').window('close');
			$('#win2').window('close');  
		}
		
		function dosearch(){
		 	$("#dg").datagrid('loading');
			$.post("../index.php/Home/User/User/dosearch",{
				"no":$("#no").val()
				},function(data){
					
					$("#dg").datagrid("loadData",{
						rows:data.rows,
						total:data.total
					});
					$("#dg").datagrid('loaded');
				},"json");

		}	
		
		</script>
	</head>
	<body style="background-color:palevioletred">
		<div>
    		<form>
    			<input type="text" placeholder="请输出用户ID号" id="no"/>
    			<a href="javascript:dosearch();" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true">搜索</a>
    		</form>
		</div>
		<table id="dg"></table>
		<div id="win" class="easyui-window" title="新增/编辑用户" style="width:300px;height:300px;top:200px;" data-options="iconCls:'icon-add2',modal:true"> 
          	<form>
           		<input type="hidden" name="uid" id="uid" value="">
				<table style="width:80%;margin:auto;margin-top:80px;" >
            		<tr>
            			<td><label>手机:</label></td>
            			<td><input type="text" id="username" name="userName" class="easyui-validatebox" placeholder="输入手机号"/> </td>
            		</tr>
            		<tr>
						<td><label>密码：</label></td>
						<td><input type="password" class="easyui-validatebox" id="userpass" name="userpass" placeholder="输入密码"></td>
					</tr>
					<tr>
						<td><label>姓名：</label></td>
						<td><input type="text" class="easyui-validatebox" id="truename" name="trueName" placeholder="输入姓名"></td>
					</tr>
            		<tr>
            			<td colspan="2" style="text-align: center">
            				<a href="javascript:Update_easyuiTable();" class="easyui-linkbutton" data-options="iconCls:'icon-add'">确认</a> 
							<a href="javascript:a();" class="easyui-linkbutton" data-options="iconCls:'icon-undo'">取消</a>
            			</td>
            		</tr>
            	</table>
            
            </form>    
        </div>  
		<div id="win2" class="easyui-window" title="删除用户" style="width:300px;height:200px;top:200px;" data-options="iconCls:'icon-add2',modal:true">
         	<form>
          		<input type="hidden" name="uid" id="uid" value="">
				<table style="width:80%;margin:auto;margin-top:80px;" >
              		<tr>
              			<td colspan="2" style="text-align: center">
               				<a href="javascript:delete_easyuiTable();" class="easyui-linkbutton" data-options="iconCls:'icon-add'">确认</a>
							<a href="javascript:a();" class="easyui-linkbutton" data-options="iconCls:'icon-undo'">取消</a>
               			</td>
               		</tr>
               	</table>
           </form>  
       </div>
	</body>
</html>