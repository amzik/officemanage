<?php
	require('common.php');
	if(!check_login()){
	    header('location:login.php');
	    exit();
	} else {
		$start = date('Y-m-d 00:00:00');
		$end = date('Y-m-d H:i:s');
		$yes = date("Y-m-d",strtotime("-1 day"));
		$conn = mysql_conn();
		$alluser = mysqli_fetch_assoc(mysqli_query($conn,"select count(*) as `count` from invitation_code where status=1"));
		$todyuser = mysqli_fetch_assoc(mysqli_query($conn,"select count(*) as `count` from invitation_code where status=1 and `update_time` >= unix_timestamp( '$start' ) AND `update_time` <= unix_timestamp( '$end' )"));
		$yestuser = mysqli_fetch_assoc(mysqli_query($conn,"select count(*) as `count` from invitation_code where status=1 and `update_time` < unix_timestamp( '$start' ) AND `update_time` > unix_timestamp( '$yes' )"));
		$lastcode = mysqli_fetch_assoc(mysqli_query($conn,"select count(*) as `count` from invitation_code where status=0"));
		$content = mysqli_fetch_assoc(mysqli_query($conn,"select * from invitation_other"));
		if($_POST){
			$post = htmlspecialchars($_POST['content'],ENT_QUOTES);
			$newpost = htmlspecialchars_decode($post);
			$insert ="UPDATE invitation_other SET `content` = '$newpost'";
			if (mysqli_query($conn, $insert)) {
				header ("Location: admin.php?a=tips&url=index.php");
			} else {
			    //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
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
						<li class="layui-nav-item">
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
				<div class="layui-fluid">
					<div class="layui-row layui-col-space15">
						<div class="layui-col-sm6 layui-col-md3">
					      <div class="layui-card">
					        <div class="layui-card-header">本地用户
					        <span class="layui-badge layui-bg-blue layuiadmin-badge">所有</span>
					        </div>
					        <div class="layui-card-body layuiadmin-card-list">
					          <p class="layuiadmin-big-font"><?php echo $alluser['count'] ?></p>
					        </div>
					      </div>
					    </div>
					    <div class="layui-col-sm6 layui-col-md3">
					      <div class="layui-card">
					        <div class="layui-card-header">
					          新增用户
					          <span class="layui-badge layui-bg-cyan layuiadmin-badge">今日</span>
					        </div>
					        <div class="layui-card-body layuiadmin-card-list">
					          <p class="layuiadmin-big-font"><?php echo $todyuser['count'] ?></p>
					        </div>
					      </div>
					    </div>
					    <div class="layui-col-sm6 layui-col-md3">
					      <div class="layui-card">
					        <div class="layui-card-header">
					          新增用户
					          <span class="layui-badge layui-bg-green layuiadmin-badge">昨日</span>
					        </div>
					        <div class="layui-card-body layuiadmin-card-list">
					          <p class="layuiadmin-big-font"><?php echo $yestuser['count']; ?></p>
					        </div>
					      </div>
					    </div>
					    <div class="layui-col-sm6 layui-col-md3">
					      <div class="layui-card">
					        <div class="layui-card-header">
					          剩余注册码
					          <span class="layui-badge layui-bg-orange layuiadmin-badge">所有</span>
					        </div>
					        <div class="layui-card-body layuiadmin-card-list">
					          <p class="layuiadmin-big-font"><?php echo $lastcode['count'] ?></p>
					        </div>
					      </div>
					    </div>
					    <div class="layui-col-sm12">
					    	<div class="layui-card">
					    		<div class="layui-card-header">
						          备忘录
						        </div>
						        <div class="layui-card-body">
						        	<form class="layui-form" action="index.php?time=<?php echo time()?>" method="post">
									  <div class="layui-form-item layui-form-text">
									    <div class="layui-input-block layui-input-block-long">
									      <textarea name="content" placeholder="随便写点什么吧~ 空一大块太难看了~" class="layui-textarea"><?php echo $content['content']; ?></textarea>
									    </div>
									  </div>
									  <div class="layui-form-item">
									    <div class="layui-input-block layui-input-block-long">
									      <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
									    </div>
									  </div>
									</form>
						        </div>
					    	</div>
					    </div>
					</div>
				</div>
			</div>

			<?php include 'footer.php' ?>
		</div>
		<script src="https://www.layuicdn.com/layui/layui.js" type="text/javascript" charset="utf-8"></script>
		<script>
			layui.use(['element','form'], function() {
				var element = layui.element;
				var form = layui.form;
			});
		</script>
	</body>

</html>