<!DOCTYPE html>

<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title>后台管理首页 - Office管理系统</title>
		<meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no">
		<link rel="stylesheet" href="https://www.layuicdn.com/layui/css/layui.css" media="all">
		<link rel="stylesheet" href="/res/css/app.css" media="all">
	</head>

	<body class="layui-layout-body" style="background: #e8e8e8;">
		<div class="layui-layout layui-layout-admin">
			<?php include 'header.php' ?>

			<div class="layui-body layui-tips">
				<div class="layui-content">
					<p class="layui-text-center">执行成功！</p>
					<a href="<?php echo $_GET['url'] ?>" class="layui-btn layui-btn-fluid">返回上一页</a>
				</div>
			</div>
		</div>
		<script src="https://www.layuicdn.com/layui/layui.js" type="text/javascript" charset="utf-8"></script>
		<script>
			layui.use(['form','element'], function() {
				var element = layui.element;

			});
		</script>
	</body>

</html>
