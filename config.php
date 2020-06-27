<?php
return [
	//全局账号相关配置
	'client_id'=>'XXXXXX-XXXXXX-XXXXXXXX',
	'tenant_id'=>'XXXXXX-XXXXXX-XXXXXXXX',
	'client_secret'=>'XXXXXX-XXXXXX-XXXXXXXX',
	'domain'=>'abc.onmicrosoft.com',
	'sku_id'=>'XXXXXX-XXXXXX-XXXXXXXX',
	'sku_id2'=>'XXXXXX-XXXXXX-XXXXXXXX',
	//网站标题等文字
	'page_config'=>[
		'title'=>'免费网盘',
		'logotitle'=>'LOGO文字',
		'line1'=>'此全局管理员已翻车，子号正常使用！',
	    'line2'=>'(5TB Onedrive + 桌面版office)',
	    'line3'=>'可激活桌面Office365'
	],
	
	
	
	/*         如果不需要邀请码功能,以上配置足以           */
	
	
	
	//是否开启邀请码才可申请账号
	'is_invitation_code'=>true,//true为开启 false为关闭
	'is_invitation_ck'=>'1',//订阅： 1学生订阅  2教师订阅
	//后台相关配置
	'admin'=>[
		'ausername'=>'admin',
		'apassword'=>'21232f297a57a5a743894a0e4a801fc3',//自行输入密码 https://md5jiami.51240.com/  将32位 小写结果填入
		'invitation_code_num'=>'16',//随机生成的邀请码位数
	],
	//数据库配置
	'db'=>[
		'host'=>'127.0.0.1',
		'username'=>'office',
		'password'=>'office',
		'database'=>'office',
	],
];