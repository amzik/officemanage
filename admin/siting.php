<?php
	require('common.php');
	if(!check_login()){
	    header('location:login.php');
	    exit();
	} else {
		if($_POST){
			$string = file_get_contents('../config.php');
			foreach($_POST as $key => $val){
				$post="/"."'$key'=>'"."(.+?)"."',"."/";
				$form="'$key'=>'$val',";
				$string = preg_replace($post,$form,$string);
			}
			file_put_contents('../config.php',$string);
			header ("Location: admin.php?a=tips&url=siting.php");
		}
	}
?>

<!DOCTYPE html>

<html>

	<head>
		<meta charset="utf-8">
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

	<body class="layui-layout-body">
		<div class="layui-layout layui-layout-admin">
			<?php include 'header.php' ?>

			<div class="layui-side layui-bg-cyan">
				<div class="layui-side-scroll">
					<ul class="layui-nav layui-bg-cyan layui-nav-tree" lay-filter="test">
						<li class="layui-nav-item layui-nav-itemed">
							<a class="" href="javascript:;">系统设置</a>
							<dl class="layui-nav-child">
								<dd>
									<a href="siting.php">系统配置</a>
								</dd>
								<dd>
									<a href="web.php">网站配置</a>
								</dd>
								<dd>
									<a href="manage.php">管理员配置</a>
								</dd>
								<dd>
									<a href="data.php">数据库配置</a>
								</dd>
								<dd>
									<a href="safe.php">后台地址</a>
								</dd>
							</dl>
						</li>
					</ul>
				</div>
			</div>

			<div class="layui-body">
				<div class="layui-content">
					<fieldset class="layui-elem-field layui-field-title">
					  <legend>系统配置</legend>
					</fieldset>
					<form class="layui-form" action="siting.php?time=<?php echo time()?>" method="post">
					  <div class="layui-form-item">
					    <label class="layui-form-label">Client Id</label>
					    <div class="layui-input-block">
					      <input type="text" value="<?php echo $client_id ?>" name="client_id" required  lay-verify="required" placeholder="请输入Client Id" autocomplete="off" class="layui-input">
					    </div>
					  </div>
					  <div class="layui-form-item">
					    <label class="layui-form-label">Tenant Id</label>
					    <div class="layui-input-block">
					      <input type="text" value="<?php echo $tenant_id ?>" name="tenant_id" required  lay-verify="required" placeholder="请输入Tenant Id" autocomplete="off" class="layui-input">
					    </div>
					  </div>
					  <div class="layui-form-item">
					    <label class="layui-form-label">Client Secret</label>
					    <div class="layui-input-block">
					      <input type="text" value="<?php echo $client_secret ?>" name="client_secret" required  lay-verify="required" placeholder="请输入Client Secret" autocomplete="off" class="layui-input">
					    </div>
					  </div>
					  <div class="layui-form-item">
					    <label class="layui-form-label">注册域名</label>
					    <div class="layui-input-block">
					      <input type="text" value="<?php echo $domain ?>" name="domain" required  lay-verify="required" placeholder="请输入域名" autocomplete="off" class="layui-input">
					    </div>
					  </div>
					  <div class="layui-form-item">
					    <label class="layui-form-label">学生订阅Sku Id</label>
					    <div class="layui-input-block">
					      <input type="text" value="<?php echo $sku_id ?>" name="sku_id" required  lay-verify="required" placeholder="请输入Sku Id" autocomplete="off" class="layui-input">
					    </div>
					  </div>
					  <div class="layui-form-item">
					    <label class="layui-form-label">教师订阅Sku Id</label>
					    <div class="layui-input-block">
					      <input type="text" value="<?php echo $sku_id2 ?>" name="sku_id2" required  lay-verify="required" placeholder="请输入Sku Id" autocomplete="off" class="layui-input">
					    </div>
					  </div>
					  <div class="layui-form-item">
					    <div class="layui-input-block">
					      <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
					    </div>
					  </div>
					</form>
				</div>
			</div>

			<?php include 'footer.php' ?>
		</div>
		<script src="https://www.layuicdn.com/layui/layui.js" type="text/javascript" charset="utf-8"></script>
		<script>
			layui.use(['form','element'], function() {
				var element = layui.element;

			});
		</script>
	</body>

</html>