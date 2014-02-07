<?php
// Default file..

function index(){

	$page = "";

	$page .= hl("Configure Guifi!");
	$page .= 'Configure your system!';

	return(array('type'=>'render','page'=>$page));
} 

function install(){
	global $Parameters;

	$pkg = $Parameters[0];
	$ret = "";
	if (!isPackageInstall($pkg)){
		$ret = installPackage($pkg);
	}
	$page = "";

	$page .= hl("Install '".$pkg."'");
	$page .= "<pre>";
	$page .= $ret;
	$page .= "</pre>";

	return(array('type'=>'render','page'=>$page));	
}
function uninstall(){
	global $Parameters;

	$pkg = $Parameters[0];
	$ret = "";
	if (isPackageInstall($pkg)){
		$ret = uninstallPackage($pkg);
	}
	$page = "";

	$page .= hl("Uninstall '".$pkg."'");
	$page .= "<pre>";
	$page .= $ret;
	$page .= "</pre>";

	return(array('type'=>'render','page'=>$page));	
}
?>