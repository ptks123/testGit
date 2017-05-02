<?php if (!defined('THINK_PATH')) exit();?><html>
	<head>
		<title>欢迎</title>
		<link type="text/css" rel="stylesheet" href="<?php echo ($HOST); ?>Public/easyui/themes/default/easyui.css">
		<link type="text/css" rel="stylesheet" href="<?php echo ($HOST); ?>Public/easyui/themes/icon.css">
		<script type="text/javascript" src="<?php echo ($HOST); ?>Public/js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="<?php echo ($HOST); ?>Public/easyui/jquery.easyui.min.js"></script>
		<script type="text/javascript" src="<?php echo ($HOST); ?>Public/easyui/locale/easyui-lang-zh_CN.js"></script>
		<style type="text/css">
        .calendarShow{display:block;width:176px;height:54px;padding-top:30px;border-radius:5px;}
		.calendarShow span{font-weight:normal;opacity:0.3;}
        </style>
		<script type="text/javascript">
			function addTab(title,url){
				var a=$("#tt").tabs("exists",title);
				if(a){
					$("#tt").tabs("select", title);
					$('#tt').tabs('update', {
						tab: $("#tt").tabs("getTab", title),
						options:{}
					});
				}else{
    				$('#tt').tabs('add',{
    			    	title: title,
    			    	selected: true,
    			    	closable: true,
    			    	content:"<iframe src='"+url+"' width='100%' height='100%' frameborder='0'  ></iframe>"
    			    	
    				});
				}
			}
			$(function(){
				$("#calendar").calendar({
					formatter: function(date){
						return "<a class='calendarShow' title='点击查看当天日程'>"
						+date.getDate()+"<br/><span>点击查看</span></a>";},
					onSelect: function(date){
						var y = date.getFullYear();
						var m = date.getMonth()+1;
						var d = date.getDate();
						addTab("日程"+y+"-"+m+"-"+d,"scheduleList.php?searchDate="+y+"-"+m+"-"+d);
					}
				});
			});
		</script>
	</head>
	<body class="easyui-layout">  
        <div data-options="region:'north',split:false,collapsible:false" style="height:50px;">
        	<div > 
        		<label>欢迎<span style="color: red;font-size: 20px;font-family: '微软雅黑';font-weight: bold;"><?php echo ($_SESSION['loginUser']['truename']); ?></span>使用系统</label>
        		<a href="#">退出</a>	
        	</div>
        </div>   
        <div data-options="region:'west',title:'系统菜单',split:true" style="width:200px;">
        	<ul class="easyui-tree">
        		<?php if(is_array($_SESSION['menus'])): $i = 0; $__LIST__ = $_SESSION['menus'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i; if($m["level"] == 1): ?><li>
        					<span><?php echo ($m["name"]); ?></span>
        					<ul>
        						<?php $mid = $m["mid"]; ?>
        						<?php if(is_array($_SESSION['menus'])): $i = 0; $__LIST__ = $_SESSION['menus'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m3): $mod = ($i % 2 );++$i; if($m3["level"] == 2 AND $m3["parentid"] == $mid): ?><li><a href="javascript:addTab('<?php echo ($m3["name"]); ?>','<?php echo ($HOST); echo ($m3["url"]); ?>')"><?php echo ($m3["name"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        					</ul>
        				</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </ul>  
        </div>   
        <div data-options="region:'center'," style="padding:5px;background:#eee;">
        	<div id="tt" class="easyui-tabs" data-options="fit:true"  style="width:100%;height:100%;">   
                <div title="欢迎" >
                	<div class="easyui-calendar" id="calendar" data-options="fit:true" style="padding:5px; height:100%;width: auto"></div>
                </div>   
        	</div> 
        </div>   
    </body>  
</html>