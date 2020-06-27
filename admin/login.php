<?php
require('common.php');


if($_GET['a'] == 'login'){
    $username = !empty($_POST['ausername']) ? $_POST['ausername'] : '';
    $password = !empty($_POST['apassword']) ? $_POST['apassword'] : '';
    header('Content-Type:application/json; charset=utf-8');
    if($username == $admin['ausername'] && md5($password) == $admin['apassword']){
        $_SESSION['token'] = md5($admin['ausername'] . $admin['apassword']);
        $arrys = array('code'=>1,'tips'=>'登录成功');
        exit(json_encode($arrys));
    }else{
    	$arrys = array('code'=>0,'tips'=>'用户名或密码错误');
        exit(json_encode($arrys));
    }
}
?>
<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>后台登录</title>
		<meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<link rel="stylesheet" href="https://www.layuicdn.com/layui/css/layui.css" media="all">
		<link rel="stylesheet" href="/res/css/app.css" media="all">
	</head>

	<body class="layui-layout-body">
		<div class="layadmin-user-login layadmin-user-display-show">

			<div class="layadmin-user-login-main">
				<div class="layadmin-user-login-box layadmin-user-login-header">
					<h2>Office订阅管理系统</h2>
					<p></p>
				</div>
				<div class="layadmin-user-login-box layadmin-user-login-body layui-form">
					<div class="layui-form-item">
						<label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
						<input type="text" name="username" id="username" lay-verify="required" placeholder="用户名" class="layui-input">
					</div>
					<div class="layui-form-item">
						<label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
						<input type="password" name="password" id="password" lay-verify="required" placeholder="密码" class="layui-input">
					</div>
					<div class="layui-form-item">
						<button class="layui-btn layui-btn-fluid" lay-submit="" lay-filter="login-submit">登 入</button>
					</div>
				</div>
			</div>

			<div class="layui-trans layadmin-user-login-footer">
				<p>© 2020
					<a href="http://www.office.com/" target="_blank">Microsoft.com</a>
				</p>
			</div>
			<script src="https://www.layuicdn.com/layui/layui.js" type="text/javascript" charset="utf-8"></script>
			<script>
				layui.use(["jquery", 'form'], function() {
					var layer = layui.layer,
						form = layui.form;
					var $ = layui.jquery;
					form.on('submit(login-submit)', function(data) {
						$.ajax({
							type: "post",
							url: "?a=login",
							data: {
								"ausername": $("#username").val(),
								"apassword": $("#password").val()
							},
							success: function(e) {
								if(e.code == 1) {
									layer.msg("登录成功", {
										icon: 16,
										shade: 0.9,
										time: 800
									}, function() {
										location.href = "index.php";
									});
								} else if(e.code === 0) {
									layer.msg(e.tips);
								}
							}
						});
						return false;
					});
				});
			</script>

		</div>
	</body>

</html>