<?php
require('common.php');

if(empty($_GET['a'])){
    if(check_login()){
        header('location:index.php');
        exit();
    }else{
        header('location:login.php');
        exit();
    }
}

if($_GET['a'] == 'tips'){
    if(!check_login()){
        header('location:login.php');
        exit();
    }
    require('tips.php');
    exit();
}


if($_GET['a'] == 'invitation_code_list'){
    if(!check_login()){
        response(1,"登录已失效");
    }
    $page = !empty($_GET['page']) ? intval($_GET['page']) : 0;
    $limit = !empty($_GET['limit']) ? intval($_GET['limit']) : 0;
    $status = !empty($_GET['status']) ? intval($_GET['status']) : 0;//0全部 1已使用 2未使用
    $keyword = !empty($_GET['keyword']) ? intval($_GET['keyword']) : '';
    if($page <= 0){
        response(1,"页码必须大于0");
    }
    if($limit <= 0){
        response(1,"每页条数必须大于0");
    }
    $where = '1';
    if(!empty($status)){
        if($status == 1){
            $where .= ' and status=1';
        }
        if($status == 2){
            $where .= ' and status=0';
        }
    }
    if(!empty($keyword)){
        $where .= " and (`code` like '%$keyword%' or `email` like '%$keyword%')";
    }
    $conn = mysql_conn();
    $token = get_ms_token($tenant_id,$client_id,$client_secret);
    $result = mysqli_query($conn,"select * from invitation_code where $where limit ". ($page-1)*$limit . ',' . $limit);
    $data = array();
    while($row = mysqli_fetch_assoc($result)){
        $data[] = $row;
    }
    foreach($data as $k =>$v){
        $data[$k]['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
        $data[$k]['update_time'] = date('Y-m-d H:i:s',$v['update_time']);
        if($v['email']){
            if(accountenable($v['email'],$token)){
                $data[$k]['accountstatus'] = "正常";
            }
            else{
                $data[$k]['accountstatus'] = "禁止登录";
            }
            $data[$k]['accountskuid'] = accountskuid($v['email'],$token);
        }
    }
    $count = mysqli_fetch_assoc(mysqli_query($conn,"select count(*) as `count` from invitation_code where $where"));
    response(0,"获取邀请码列表成功",$data,$count['count']);
}

if($_GET['a'] == 'invitation_code_list_auth'){
    if(!check_login()){
        response(1,"登录已失效");
    }
    $page = !empty($_GET['page']) ? intval($_GET['page']) : 0;
    $limit = !empty($_GET['limit']) ? intval($_GET['limit']) : 0;
    $status = !empty($_GET['status']) ? intval($_GET['status']) : 0;//0全部 1已使用 2未使用
    $keyword = !empty($_GET['keyword']) ? intval($_GET['keyword']) : '';
    if($page <= 0){
        response(1,"页码必须大于0");
    }
    if($limit <= 0){
        response(1,"每页条数必须大于0");
    }
    $where = '1';
    if(!empty($status)){
        if($status == 1){
            $where .= ' and status=1';
        }
        if($status == 2){
            $where .= ' and status=0';
        }
    }
    if(!empty($keyword)){
        $where .= " and (`code` like '%$keyword%' or `email` like '%$keyword%')";
    }
    $conn = mysql_conn();
    $token = get_ms_token($tenant_id,$client_id,$client_secret);
    $result = mysqli_query($conn,"select * from invitation_code where $where limit ". ($page-1)*$limit . ',' . $limit);
    $data = array();
    while($row = mysqli_fetch_assoc($result)){
        $data[] = $row;
    }
    $newdata = array();
    foreach($data as $k =>$v){
        if($v['email'] != ''){
            if(accountenable($v['email'],$token)){
                $newdata[$k]['accountstatus'] = "正常";
            }
            else{
                $newdata[$k]['accountstatus'] = "禁止登录";
            }
            $newdata[$k]['accountskuid'] = accountskuid($v['email'],$token);
            $newdata[$k]['email'] = $v['email'];
            $newdata[$k]['id'] = $v['id'];
        }
    }
    $count = mysqli_fetch_assoc(mysqli_query($conn,"select count(*) as `count` from invitation_code where $where"));
    response(0,"获取邀请码列表成功",$newdata,$count['count']);
}

if($_GET['a'] == 'invitation_code_create'){
    if(!check_login()){
        response(1,"登录已失效");
    }
    $num = !empty($_POST['num']) ? intval($_POST['num']) : 0;
    if($num <= 0){
        response(1,"数量必须大于0");
    }
    $conn = mysql_conn();
    $i = 0;
    $total = $num;
    $success = 0;
    $error = 0;
    while($i < $num){
        $code = get_rand_number($admin['invitation_code_num']);
        $time = time();
        $result = mysqli_query($conn,"INSERT INTO `invitation_code`(`code`, `create_time`, `update_time`, `status`) VALUES ('$code',$time,$time, 0)");
        if(!empty($result)){
            $success++;
        }else{
            $error++;
        }
        $i++;
    }
    $data = [
        'total'=>$total,
        'success'=>$success,
        'error'=>$error,
    ];
    response(0,'生成成功',$data);
}

if($_GET['a'] == 'invitation_code_add_account'){
    if(!check_login()){
        response(1,"登录已失效");
    }
    $email=$_POST['email'];
    if(is_email($email)){
        $conn = mysql_conn();
        $code = get_rand_number($admin['invitation_code_num']);
        $time = time();
        $select = mysqli_query($conn,"select * from invitation_code where `email` like '%$email%'");
        $sqlrow = mysqli_fetch_assoc($select);
        if($sqlrow['email'] == $email){
        	response(1,'添加失败，该用户已添加！');
        }else{
        	$result = mysqli_query($conn,"INSERT INTO `invitation_code`(`code`, `create_time`, `update_time`, `status`, `email`) VALUES ('$code',$time,$time, 1,'$email')");
	        if(!empty($result)){
	            response(0,'添加成功');
	        }else{
	            response(1,'添加失败，请重试');
	        }
        }
    }else{
        response(1,'你所填写的邮箱格式不正确，请查看后重试');
    }
}
if($_GET['a'] == 'invitation_code_delete'){
    if(!check_login()){
        response(1,"登录已失效");
    }
    $token = get_ms_token($tenant_id,$client_id,$client_secret);
    $user_email = !empty($_POST['email']) ? $_POST['email'] : 0;
    if($user_email){
        $resultaccount=accountdelete($user_email,$token);
    }else{
        $resultaccount=false;
    }
    $id = !empty($_POST['id']) ? intval($_POST['id']) : 0;
    $conn = mysql_conn();
    $resultsql = mysqli_query($conn,"DELETE FROM `invitation_code` WHERE `id` = $id");
    if(!empty($resultsql)&&$resultaccount){
        response(0,"邀请码删除成功,用户账户删除成功");
    }
    elseif(!empty($resultsql)){
        response(0,"邀请码删除成功,无用户或无权限删除账户");
    }else{
        response(1,"删除失败");
    }
}

if($_GET['a'] == 'invitation_code_activeaccount'){
    if(!check_login()){
        response(1,"登录已失效");
    }
    $token = get_ms_token($tenant_id,$client_id,$client_secret);
    $user_email = !empty($_POST['email']) ? $_POST['email'] : 0;
    $result=accountactive($user_email,$token);
    if(!empty($result)){
        response(0,$user_email."解锁失败");
    }else{
        response(1,$user_email."解锁成功");
    }
}

if($_GET['a'] == 'invitation_code_accountdelskuistu'){
    if(!check_login()){
        response(1,"登录已失效");
    }
    $token = get_ms_token($tenant_id,$client_id,$client_secret);
    $user_email = !empty($_POST['email']) ? $_POST['email'] : 0;
    $result=accountdelskuistu($user_email,$token,$sku_id);
    if(!empty($result)){
        response(0,$user_email."删除订阅成功");
    }else{
        response(1,$user_email."删除订阅失败");
    }
}

if($_GET['a'] == 'invitation_code_accountdelskuitea'){
    if(!check_login()){
        response(1,"登录已失效");
    }
    $token = get_ms_token($tenant_id,$client_id,$client_secret);
    $user_email = !empty($_POST['email']) ? $_POST['email'] : 0;
    $result=accountdelskuistu($user_email,$token,$sku_id2);
    if(!empty($result)){
        response(0,$user_email."删除订阅成功");
    }else{
        response(1,$user_email."删除订阅失败");
    }
}

if($_GET['a'] == 'invitation_code_accountaddskuistu'){
    if(!check_login()){
        response(1,"登录已失效");
    }
    $token = get_ms_token($tenant_id,$client_id,$client_secret);
    $user_email = !empty($_POST['email']) ? $_POST['email'] : 0;
    $result=accountaddskuistu($user_email,$token,$sku_id);
    if(!empty($result)){
        response(0,$user_email."添加订阅成功");
    }else{
        response(1,$user_email."添加订阅失败");
    }
}

if($_GET['a'] == 'invitation_code_accountaddskuitea'){
    if(!check_login()){
        response(1,"登录已失效");
    }
    $token = get_ms_token($tenant_id,$client_id,$client_secret);
    $user_email = !empty($_POST['email']) ? $_POST['email'] : 0;
    $result=accountaddskuistu($user_email,$token,$sku_id2);
    if(!empty($result)){
        response(0,$user_email."添加订阅成功");
    }else{
        response(1,$user_email."添加订阅失败");
    }
}

if($_GET['a'] == 'invitation_code_inactiveaccount'){
    if(!check_login()){
        response(1,"登录已失效");
    }
    $token = get_ms_token($tenant_id,$client_id,$client_secret);
    $user_email = !empty($_POST['email']) ? $_POST['email'] : 0;
    $result=accountinactive($user_email,$token);
    if(!empty($result)){
        response(0,$user_email."禁用失败");
    }else{
        response(1,$user_email."成功禁用");
    }
}

if($_GET['a'] == 'invitation_code_get'){
    if(!check_login()){
        response(1,"登录已失效");
    }
    $nextpages = !empty($_GET['nextpage']) ? $_GET['nextpage'] : 0;
    //print_r ($nextpages);
    $page = !empty($_GET['page']) ? intval($_GET['page']) : 0;
    $limit = !empty($_GET['limit']) ? intval($_GET['limit']) : 0;
    if($page <= 0){
        response(1,"页码必须大于0");
    }
    if($limit <= 0){
        response(1,"每页条数必须大于0");
    }
    $token = get_ms_token($tenant_id,$client_id,$client_secret);
    $getaccount = json_decode(accountget($token,$nextpages), true);
    $result = array();
    $result = $getaccount['value'];
    $conn = mysql_conn();
    $data = array();
    foreach($result as $k =>$v){
    	//if (!$select) {
        //printf("Error: %s\n", mysqli_error($conn));}
    	try {
    		if($result[$k]['mail'] != ''){
    	        $selectmail = $result[$k]['mail'];
            	$select = mysqli_query($conn,"select * from invitation_code where `email` like '%$selectmail%'");
            	$sqlrow = mysqli_fetch_assoc($select);
                if($sqlrow['email'] == $selectmail){
                    $status = 0;
                }else{
                    $status = 1;
                }
    			$data[] = array(
	            	'email' => $result[$k]['mail'],
	            	'status'=> $status
	            );
    		}
    	}
	    catch (\Exception $e) {
	            
	    }
    }
    $nextpage = $getaccount['@odata.nextLink'];
    getresponse(0,"获取用户列表成功",$data,$nextpage,'10000');
}