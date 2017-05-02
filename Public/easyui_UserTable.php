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
    			    url:"../index.php/Home/User/User/select_usertable?pageNo=1&pageSize=10",
    			    striped:true,
    			    pagination:true,
    			    rownumbers:true,
    			    columns:[[    
                        {field:'username',title:'用户名',width:100,align:'center'},    
                        {field:'pass',title:'密码',width:200,align:'center'},    
                        {field:'address',title:'地址',width:150,align:'center'}, 
                        {field:'telphone',title:'电话',width:100,align:'center',formatter:function(value,row,index){
                        	return "<b style='color:red;'>"+value+"</b>";
                        }},
                        {field:'e_mail',title:'邮箱',width:200,align:'center'},
                        {field:'visa',title:'信用卡',width:200,align:'center'},
                        {field:'card',title:'会员卡ID',width:100,align:'center'},
                        {field:'money',title:'余额',width:50,align:'center'},
                        {field:'integral',title:'积分',width:50,align:'center'},
    			    ]]
    			});
    		
    			var pager = $("#dg").datagrid("getPager");
    			pager.pagination({
    				onSelectPage:function(pageNumber, pageSize){
    					$("#dg").datagrid('loading'),
    					$.post("../index.php/Home/User/User/select_usertable?pageNo="+pageNumber+"&pageSize="+pageSize,function(data){
    						$("#dg").datagrid("loadData",{
    							rows:data.rows,
    							total:data.total
    						});
    						$("#dg").datagrid('loaded');
    					},"json");
    				}
    			}); 
    
    			$("#search").click(function(){
    				$.post("../index.php/Home/User/User/cardsearch",
    					{
    						"card"	   : $("#card").val()
    					},function(data){
    						$("#dg").datagrid('loading');
    						$("#dg").datagrid("loadData",{
    							rows:data.rows,
    							total:data.total
    						});
    						$("#dg").datagrid('loaded');
    					},"json");
    			});
    			
    			$("#search2").click(function(){
    				$.post("../index.php/Home/User/User/order",
    					{
    						"sel"	   : $("#sel").val()
    					},function(data){
    						$("#dg").datagrid('loading');
    						$("#dg").datagrid("loadData",{
    							rows:data.rows,
    							total:data.total
    						});
    						$("#dg").datagrid('loaded');
    					},"json");
    			});
    			
    		});
		</script>
	</head>
	<body style="background-color:palevioletred">
		<div id="p" class="easyui-panel" title="查询用户资料" data-options="fit:true" style="width:500px;height:550px;padding:10px;background:#fafafa;margin:auto;">
            <div class="col-md-5" style="height:50px;text-align:center;">
				<span>ID卡号：</span>
				<input type="text" name="card" id="card"  placeholder="请输入卡号"/>
				<input type="button"  id="search" name="search" style="width: 100px;" class="btn btn-primary" value="查找"/>
			</div>
			<div class="col-md-5" style="height:50px;text-align:center;">
				<select name="sel" id="sel">
					<option value="">请选择</option>
					<option value="integral">积分</option>
					<option value="money">卡内金额</option>
					<option value="time">注册时间</option>
				</select>
				<input type="button"  id="search2" name="search" style="width: 100px;" class="btn btn-primary" value="排序"/>
			</div>
			<table id="dg"></table>
        </div>
	</body>
</html>