<?php if (!defined('THINK_PATH')) exit(); ?>
<html>
	<head>
		<title></title>
	</head>
	<body>
		<span>hello word!</span>
		<?php echo ($aaa); ?>
		<br/>
		<?php echo ($bbb[0]); ?>----<?php echo ($bbb[2]); ?>
		<br/>
		<?php echo ($ccc["name"]); ?>
		<br/>
		<?php echo ($ddd->name); ?>----<?php echo ($ddd->pass); ?>
		<br>
		<?php echo date('Y-m-d',$time);?>----<?php echo (date('Y-m-d',$time)); ?>
		<br/>
		<?php if(is_array($e)): $i = 0; $__LIST__ = $e;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$e): $mod = ($i % 2 );++$i; endforeach; endif; else: echo "$empty" ;endif; ?>
	</body>
</html>