<?php

// lib/auth.php
$auth_file = "/etc/cdistro.conf";

function checkUser($login,$pass){
	global $auth_file;
	
	$ret = false;
	$variables = load_conffile($auth_file);

	if (isset($variables['SSHAUTH']) && ($variables['SSHAUTH'] == 1)){
		error_reporting(E_ERROR | E_PARSE);
		$connection = ssh2_connect('127.0.0.1', 22);
		$ret = ssh2_auth_password($connection, $login, $pass);
		error_reporting(E_ALL ^ E_NOTICE); 
	} 
	if (!$ret){
		$ret = ((isset($variables['LOGIN'])) && (isset($variables['PASSWORD'])) && ($login == $variables['LOGIN']) && (md5(md5($pass)) == $variables['PASSWORD']));
	}
	return ($ret);
}

function logout() {
	global $staticFile;		

	unset($user);
	unsetSessionValue('user');
	return(array('type'=>'redirect','url'=>$staticFile));
}

function login_form($values=null){

	$datos = array(
				array('name'=>'user','type'=>'text','desc'=>t("Username")),
				array('name'=>'password','type'=>'password','desc'=>t("Password"))
			);

	$page = hl(t("Login"),2);
	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($datos as $dato){
		$options = array('type'=>$dato['type']);
		$n = $dato['name'];
		if (isset($values[$n])) { $options['value'] = $values[$n];}
		$page .= addInput($n,$dato['desc'],null,$options);
	}

	$page .= addSubmit(array('label'=>t('Login')));

	return($page);

}

// Check aut

if (!isSesionValue("user")) {
	$method=strtolower($_SERVER['REQUEST_METHOD']);
	if (($method == 'post') && isset($_POST['user']) && isset($_POST['password'])) {
		if (checkUser($_POST['user'],$_POST['password'])) {
			$user = array('user'=>$_POST['user']);
			setSessionValue('user',serialize($user));
		} else {
			setFlash(t('Username or password is not correct.'),'error');
			$cb = array('type'=>'render', 'page'=>login_form(array('user'=>$_POST['user'])));
		}
	} else {
		$cb = array('type'=>'render', 'page'=>login_form());
	}
} else {
	$user = unserialize(getSessionValue("user"));
}

