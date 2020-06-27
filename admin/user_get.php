<?php
	require('common.php');
	if(!check_login()){
	    header('location:login.php');
	    exit();
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
							<a class="" href="javascript:;">用户管理</a>
							<dl class="layui-nav-child">
								<dd>
									<a href="user_manage.php">用户列表</a>
								</dd>
								<dd>
									<a href="user_get.php">获取用户</a>
								</dd>
								<dd>
									<a href="user_auth.php">订阅管理</a>
								</dd>
							</dl>
						</li>
					</ul>
				</div>
			</div>

			<div class="layui-body">
				<div class="layui-content">
					<fieldset class="layui-elem-field layui-field-title">
					  <legend>远程用户列表</legend>
					</fieldset>
			        <table class="layui-hide" id="table" lay-filter="table">
			            
			        </table>
			        
				</div>
			</div>

			<?php include 'footer.php' ?>
		</div>
		<script type="text/html" id="toolbarDemo">
		  <div class="layui-btn-container">
		    <button class="layui-btn layui-btn-sm" id="getnextpage" lay-event="getnextpage">
		    	下一页
		    	<a href="" style="display: none;"></a>
		    </button>
		  </div>
		</script>
		<script type="text/html" id="buttons">
		  <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="accountgetloc">拉取到本地</a>
		  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="accountdelskuistu">删除学生订阅</a>
		  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="accountdelskuitea">删除教师订阅</a>
	      <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="accountactive">允许</a>
	      <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="accountinactive">禁止</a>
	      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
	    </script>
		<script src="https://www.layuicdn.com/layui/layui.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">
        layui.use(['table','form','layer','element','jquery'], function(){
	          var table = layui.table;
	          var form = layui.form;
	          var element = layui.element;
	          var layer = layui.layer;
	          var $ = layui.jquery;
	          var nextpage = $("#getnextpage a").attr('href');
	            table.render({
	                elem: '#table',//表格id
	                url:"admin.php?a=invitation_code_get&nextpage=",//list接口地址
	                cellMinWidth: 80,//全局定义常规单元格的最小宽度
	                height: 'full-200',
	                page: false,
	                toolbar: '#toolbarDemo',
	                cols: [[
	                //align属性是文字在列表中的位置 可选参数left center right
	                //sort属性是排序功能
	                //title是这列的标题
	                //field是取接口的字段值
	                //width是宽度，不填则自动根据值的长度
	                  {field:'email',title: '注册账号',align: 'left',templet:function(d){
	                          if(d.email){
	                              return d.email;
	                          }else{
	                              return '-';
	                          }
	                  }},
	                  {field:'status',title: '状态',align: 'center',templet:function(d){
	                          if(d.status == 1){
	                              return '<span style="color:green;">未拉取到本地</span>';
	                          }else{
	                              return '<span style="color:red;">已拉取到本地</span>';
	                          }
	                  }},
	                  {fixed:'right',title: '操作', width: 500, align:'center', toolbar: '#buttons'}
	                ]],
	                done: function(res, curr, count){
	                	$("#getnextpage a").attr('href',escape(res['nextpage']));
	                }
	          });
	            //头工具栏事件
			  table.on('toolbar(table)', function(obj){
			    if(obj.event === 'getnextpage'){
			    	nextpage = $("#getnextpage a").attr('href');
			    	table.reload('table', { //表格的id
	                    url:"admin.php?a=invitation_code_get&nextpage="+nextpage,
	                });
			    }
			  });
	           //监听
	          table.on('tool(table)', function(obj){
	              if(obj.event === 'del'){
	                  layer.confirm('真的删除吗', function(index){
	                      $.post("admin.php?a=invitation_code_delete",{email:obj.data.email,id:obj.data.id},function(res){
	                        if (res.code == 0) {
	                            obj.del();//删除表格这行数据
	                        }
	                        layer.msg(res.msg);
	                      },'json');
	                  });
	              }
	              if(obj.event === 'accountactive'){
	                  layer.confirm('允许登录?', function(index){
	                      $.post("admin.php?a=invitation_code_activeaccount",{email:obj.data.email},function(res){
	                       if (res.code == 1) {
	                          layer.closeAll();
	                        }
	                        layer.msg(res.msg);
	                      },'json');
	                  });
	              }
	              if(obj.event === 'accountinactive'){
	                  layer.confirm('禁止登录?', function(index){
	                      $.post("admin.php?a=invitation_code_inactiveaccount",{email:obj.data.email},function(res){
	                       if (res.code == 1) {
	                          layer.closeAll();
	                        }
	                        layer.msg(res.msg);
	                      },'json');
	                  });
	              }
	              if(obj.event === 'accountgetloc'){
	                  layer.confirm('确定拉取用户到本地吗?', function(index){
	                  	var data = {
		                    email:obj.data.email,
		                };
		                $.post("admin.php?a=invitation_code_add_account",data,function(res){
	                    if (res.code == 0) {
	                        layer.closeAll();
	                    }
	                    layer.msg(res.msg);
	                	},'json');
	                  });
	              }
	              if(obj.event === 'accountdelskuistu'){
	                  layer.confirm('删除该用户的学生订阅吗?', function(index){
	                      $.post("admin.php?a=invitation_code_accountdelskuistu",{email:obj.data.email},function(res){
	                       if (res.code == 0) {
	                          layer.closeAll();
	                        }
	                        layer.msg(res.msg);
	                      },'json');
	                  });
	              }
	              if(obj.event === 'accountdelskuitea'){
	                  layer.confirm('删除该用户的教师订阅吗?', function(index){
	                      $.post("admin.php?a=invitation_code_accountdelskuitea",{email:obj.data.email},function(res){
	                       if (res.code == 0) {
	                          layer.closeAll();
	                        }
	                        layer.msg(res.msg);
	                      },'json');
	                  });
	              }
	            });
	        });
	    </script>
	</body>

</html>