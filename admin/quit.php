<?php
	require('common.php');
	
    if(!check_login()){
        header('location:login.php');
        exit();
    }
    session_destroy();
    header('location:login.php');
    exit();
?>