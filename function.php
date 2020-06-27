<?php
function get_rand_string($length = 10){
    $str = null;
    $strPol = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    $max = strlen($strPol) - 1;
    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];
    }
    return $str;
}
function get_ms_token($tenant_id,$client_id,$client_secret){
    $url = 'https://login.microsoftonline.com/' . $tenant_id . '/oauth2/v2.0/token';
    $scope = 'https://graph.microsoft.com/.default';
    $data = [
        'grant_type'=>'client_credentials',
        'client_id'=>$client_id,
        'client_secret'=>$client_secret,
        'scope'=>$scope
    ];
    $res = curl_post($url,$data);
    $data = json_decode($res,true);
    if(!empty($data) && !empty($data['access_token'])){
        return $data['access_token'];
    }
    return '';
}
function create_user($request,$token,$domain,$sku_id,$password){
    $url = 'https://graph.microsoft.com/v1.0/users';
    $user_email = $request['username'] . '@' . $domain;
    $data = [
        "accountEnabled"=>true,
        "displayName"=>$request['firstname'] . ' ' .$request['lastname'],
        "mailNickname"=>$request['username'],
        "passwordPolicies"=>"DisablePasswordExpiration, DisableStrongPassword",
        "passwordProfile"=>[
            "password"=>$password,
            "forceChangePasswordNextSignIn"=>true
        ],
        "userPrincipalName"=>$user_email,
        "usageLocation"=>"CN"
    ];
    $result = json_decode(curl_post_json($url,json_encode($data),$token),true);
    if(!empty($result) && !empty($result['error'])){
        if($result['error']['message'] == 'Another object with the same value for property userPrincipalName already exists.'){
            response(1,'前缀被占用,请修改后重试');
        }
        response(1,$result['error']['message']);
    }
    addsubscribe($user_email,$token,$sku_id);
}
function addsubscribe($user_email,$token,$sku_id){
    $url = 'https://graph.microsoft.com/v1.0/users/' . $user_email . '/assignLicense';
    $data = [
        'addLicenses'=>[
            [
                'disabledPlans'=>[],
                'skuId'=>$sku_id
            ],
        ],
        'removeLicenses'=> [],
    ];
    curl_post_json($url,json_encode($data),$token);
}
function curl_post($url, $post){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}
function curl_post_json($url='',$postdata='',$token){
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json;','Authorization:Bearer '.$token]);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $data=curl_exec($ch);
    curl_close($ch);
    return $data;
}
function response($code,$msg,$data = [],$count = false){
    $json = [
        'code'=>$code,
        'msg'=>$msg,
    ];
    if(!empty($data)){
        $json['data'] = $data;
    }
    if($count !== false){
        $json['count'] = $count;
    }
    exit(json_encode($json));
}
function getresponse($code,$msg,$data = [],$nextpage = false,$count = false){
    $json = [
        'code'=>$code,
        'msg'=>$msg,
    ];
    if(!empty($data)){
        $json['data'] = $data;
    }
    if($nextpage !== false){
        $json['nextpage'] = $nextpage;
    }
    if($count !== false){
        $json['count'] = $count;
    }
    exit(json_encode($json));
}
function check_login(){
    global $admin;
    if(empty($_SESSION['token'])){
        return false;
    }
    if($_SESSION['token'] != md5($admin['ausername'] . $admin['apassword'])){
        return false;
    }
    return true;
}
function mysql_conn(){
    global $db;
    $conn = mysqli_connect($db['host'],$db['username'],$db['password'],$db['database']);
    mysqli_query($conn,'set names utf8mb4');
    return $conn;
}
function get_rand_number($length = 8){
    $str = null;
    $strPol = '0123456789';
    $max = strlen($strPol) - 1;
    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];
    }
    return $str;
}
function accountenable($user_email,$token){
    $url="https://graph.microsoft.com/beta/users/".$user_email;
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json;','Authorization:Bearer '.$token]);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $data=curl_exec($ch);
    curl_close($ch);
    $jsdata=json_decode($data);
    return $jsdata->accountEnabled;
}

function accountskuid($user_email,$token){
    $url="https://graph.microsoft.com/beta/users/".$user_email;
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json;','Authorization:Bearer '.$token]);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $data=curl_exec($ch);
    curl_close($ch);
    $jsdata=json_decode($data);
    return $jsdata->assignedLicenses;
}

function accountactive($user_email,$token){
    $url="https://graph.microsoft.com/beta/users/".$user_email;
    $jsdata='{"accountEnabled":"true"}';
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json;','Authorization:Bearer '.$token]);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_POSTFIELDS,$jsdata);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $data=curl_exec($ch);
    curl_close($ch);
    return $data;
}

function accountdelskuistu($user_email,$token,$user_skuid){
    $url="https://graph.microsoft.com/v1.0/users/".$user_email."/assignLicense";
    $jsdata='
    {
	  "addLicenses": [
	    {
	      "disabledPlans": [],
	      "skuId": "'.$user_skuid.'"
	    }
	  ],
	  "removeLicenses": [ "'.$user_skuid.'" ]
	}
    ';
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json;','Authorization:Bearer '.$token]);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS,$jsdata);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $data=curl_exec($ch);
    curl_close($ch);
    return $data;
}

function accountaddskuistu($user_email,$token,$user_skuid){
    $url="https://graph.microsoft.com/v1.0/users/".$user_email."/assignLicense";
    $jsdata='
    {
	  "addLicenses": [
	    {
	      "disabledPlans": [],
	      "skuId": "'.$user_skuid.'"
	    }
	  ],
	  "removeLicenses": [  ]
	}
    ';
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json;','Authorization:Bearer '.$token]);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS,$jsdata);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $data=curl_exec($ch);
    curl_close($ch);
    return $data;
}

function accountinactive($user_email,$token){
    $url="https://graph.microsoft.com/beta/users/".$user_email;
    $jsdata='{"accountEnabled":"false"}';
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json;','Authorization:Bearer '.$token]);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_POSTFIELDS,$jsdata);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $data=curl_exec($ch);
    curl_close($ch);
    return $data;
}

function accountdelete($user_email,$token){
    $url="https://graph.microsoft.com/beta/users/".$user_email;
    $jsdata='{"accountEnabled":"false"}';
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json;','Authorization:Bearer '.$token]);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $data=curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($http_code==204){
    return TRUE;
    }else{
    return FALSE;
    }
}

function is_email($user_email)
{
    $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
    if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false)
    {
        if (preg_match($chars, $user_email)){
            return true;
        }
        else{
            return false;
        }
   }
   else{
            return false;
        }
}

function accountget($token,$nextpages){
	if($nextpages === 0){
    	$url="https://graph.microsoft.com/v1.0/users?%24select=mail";
	} else {
		$url=$nextpages;
	}
	//print_r ($url);
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json;','Authorization:Bearer '.$token]);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $data=curl_exec($ch);
    curl_close($ch);
    return $data;
}