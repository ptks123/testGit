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
			    url:'index.php?c=Menu&a=PT_loadMember&pageNo=1&pageSize=10',
			    striped:true,
			    pagination:true,
			    rownumbers:true,
			    frozenColumns:[[{field:"aaa",checkbox:true}]],    
			    columns:[[    
                    //{field:'0',title:'编号',width:200,align:'center',hidden:true},    
                    {field:'0',title:'编号',width:200,align:'center'},    
                    {field:'1',title:'会员等级名',width:300,align:'center',formatter:function(value,row,index){
                    	return "<b style='color:red;'>"+value+"</b>";
                    }},
                    {field:'2',title:'会员升级所需经验',width:100,align:'center'}
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
						$("#uid").val(row[0]);
						$("#name").val(row[1]);
						$("#experience").val(row[2]);
// 						$('#ff').form('load',{
// 							uid:row[0],
// 							userName:row[1],
// 							userPass:row[2]
// 						});
						$('#win').window('open');  // open a window
					}
				},'-',{
					text   : '删除',
					iconCls: 'icon-delete',
					handler: function(){alert('帮助按钮')}
				}]
			});
		
			var pager = $("#dg").datagrid("getPager");
			pager.pagination({
				onSelectPage:function(pageNumber, pageSize){
					$("#dg").datagrid('loading'),
					$.post("index.php?c=Menu&a=PT_loadMember&pageNo="+pageNumber+"&pageSize="+pageSize,function(data){
						$("#dg").datagrid("loadData",{
							rows:data.rows,
							total:data.total
						});
						$("#dg").datagrid('loaded');
					},"json");
				}
			}); 
			$('#win').window('close');// close a window
		});
		function Update_easyuiMember(){
			//$("#ff").serialize();
			$.post("index.php?c=Menu&a=Update_easyuiMember",
			{
				"mid"	     : $("#uid").val(),
				"name"       : $("#name").val(),
				"experience" : $("#experience").val()
			},function(data){
				$("#dg").datagrid('loading');
				$("#dg").datagrid("loadData",{
					rows:data.rows,
					total:data.total
				});
				$("#dg").datagrid('loaded');
				$('#win').window('close');  // close a window
			},"json");
		}
		</script>
	</head>
	<body style="background-color:palevioletred">
		<table id="dg"></table>
		<div id="win" class="easyui-window" title="新增/编辑用户" style="width:300px;height:300px;top:200px;" data-options="iconCls:'icon-add2',modal:true"> 
          	<form>
           		<input type="hidden" name="mid" id="mid" value="">
				<table style="width:80%;margin:auto;margin-top:80px;" >
            		<tr>
            			<td><label>会员等级名:</label></td>
            			<td><input type="text" id="name" name="name" class="easyui-validatebox" /> </td>
            		</tr>
            		<tr>
						<td><label>经验值：</label></td>
						<td><input type="text" class="easyui-validatebox" id="experience" name="experience" ></td>
					</tr>
            		<tr>
            			<td colspan="2" style="text-align: center">
            				<a href="javascript:Update_easyuiTable();" class="easyui-linkbutton" data-options="iconCls:'icon-add'">确认</a> 
							<a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-undo'">取消</a>
            			</td>
            		</tr>
            	</table>
            
            </form>    
        </div>  


	</body>


</html>