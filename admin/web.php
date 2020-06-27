<?php
	require('common.php');
	if(!check_login()){
	    header('location:login.php');
	    exit();
	} else {
		if($_POST){
			$string = file_get_contents('../config.php');
			foreach($_POST as $key => $val){
				if($key == 'is_invitation_code'){
					$post="/"."'$key'=>"."(.+?)".","."/";
					$form="'$key'=>$val,";
				}else if($key == 'line3'){
					$post="/"."'$key'=>'"."(.+?)"."'"."/";
					$form="'$key'=>'$val'";
				}else{
					$post="/"."'$key'=>'"."(.+?)"."',"."/";
					$form="'$key'=>'$val',";
				}
				$string = preg_replace($post,$form,$string);
			}
			file_put_contents('../config.php',$string);
			header ("Location: admin.php?a=tips&url=web.php");
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
					  <legend>网站配置</legend>
					</fieldset>
					<form class="layui-form" action="web.php?time=<?php echo time()?>" method="post">
					  <div class="layui-form-item">
					    <label class="layui-form-label">网站名称</label>
					    <div class="layui-input-block">
					      <input type="text" value="<?php echo $page_config['title'] ?>" name="title" required  lay-verify="required" placeholder="请输入网站标题" autocomplete="off" class="layui-input">
					    </div>
					  </div>
					  <div class="layui-form-item">
					    <label class="layui-form-label">LOGO标语</label>
					    <div class="layui-input-block">
					      <input type="text" value="<?php echo $page_config['logotitle'] ?>" name="logotitle" required  lay-verify="required" placeholder="请输入LOGO标语" autocomplete="off" class="layui-input">
					    </div>
					  </div>
					  <div class="layui-form-item">
					    <label class="layui-form-label">网站标语1</label>
					    <div class="layui-input-block">
					      <input type="text" value="<?php echo $page_config['line1'] ?>" name="line1" required lay-verify="required" autocomplete="off" class="layui-input">
					    </div>
					  </div>
					  <div class="layui-form-item">
					    <label class="layui-form-label">网站标语2</label>
					    <div class="layui-input-block">
					      <input type="text" value="<?php echo $page_config['line2'] ?>" name="line2" required lay-verify="required" autocomplete="off" class="layui-input">
					    </div>
					  </div>
					  <div class="layui-form-item">
					    <label class="layui-form-label">网站标语3</label>
					    <div class="layui-input-block">
					      <input type="text" value="<?php echo $page_config['line3'] ?>" name="line3" required lay-verify="required" autocomplete="off" class="layui-input">
					    </div>
					  </div>
					  <div class="layui-form-item">
					    <label class="layui-form-label">邀请码长度</label>
					    <div class="layui-input-block">
					      <input type="text" value="<?php echo $admin['invitation_code_num'] ?>" name="invitation_code_num" required  lay-verify="required" placeholder="请输入长度" autocomplete="off" class="layui-input">
					    </div>
					  </div>
					  <div class="layui-form-item">
					    <label class="layui-form-label">邀请码注册</label>
					    <div class="layui-input-block">
					    	<?php
					    		if($is_invitation_code == true){
					    			echo '<input type="radio" name="is_invitation_code" value="true" checked title="开启">
					    				<input type="radio" name="is_invitation_code" value="false" title="关闭">
					    				';
					    		} else if ($is_invitation_code == false){
					    			echo '<input type="radio" name="is_invitation_code" value="true" title="开启">
					    				<input type="radio" name="is_invitation_code" value="false" checked title="关闭">';
					    		}
					    	?>
					    </div>
					  </div>
					  <div class="layui-form-item">
					    <label class="layui-form-label">订阅注册</label>
					    <div class="layui-input-block">
					    	<?php
					    		if($is_invitation_ck == "1"){
					    			echo '<input type="radio" name="is_invitation_ck" value="1" checked title="学生订阅">
					    				<input type="radio" name="is_invitation_ck" value="2" title="教师订阅">
					    				';
					    		} else if ($is_invitation_ck == "2"){
					    			echo '<input type="radio" name="is_invitation_ck" value="1" title="学生订阅">
					    				<input type="radio" name="is_invitation_ck" value="2" checked title="教师订阅">';
					    		}
					    	?>
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