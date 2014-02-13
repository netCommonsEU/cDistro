<?php
// Default file..

function index(){

	$page = "";

	$page .= hl("Configure Guifi!");
	$page .= 'Configure your system!';

	return(array('type'=>'render','page'=>$page));
} 

function install(){
	return(_genericInstallUninstall('Install'));
}

function uninstall(){
	return(_genericInstallUninstall('Uninstall'));
}

function realInstall(){
	global $Parameters,$staticFile;

	$page = "";

	if (isset($Parameters[0]) && ($Parameters[0]  != "" )) {
		$pkg = $Parameters[0];
		$ret = "";

		if (!isPackageInstall($pkg)){
			$ret = installPackage($pkg);
		} else {
			$ret = "$pkg is already install.";
		}

		$page .= hl("Install '".$pkg."'");
		$page .= "<pre>";
		$page .= $ret;
		$page .= "</pre>";
	}
	$page .= "<a class='btn btn-primar' href='".$staticFile."'>Home</a>";

	return(array('type'=>'ajax','page'=>$page));	

}

function realUninstall(){

	global $Parameters,$staticFile;

	$page = "";

	if (isset($Parameters[0]) && ($Parameters[0]  != "" )) {
		$pkg = $Parameters[0];
		$ret = "";

		if (isPackageInstall($pkg)){
			$ret = uninstallPackage($pkg);
		} else {
			$ret = "$pkg isn't install.";
		}

		$page .= hl("Uninstall '".$pkg."'");
		$page .= "<pre>";
		$page .= $ret;
		$page .= "</pre>";
	}
	
	$page .= "<a class='btn btn-primar' href='".$staticFile."'>Home</a>";

	return(array('type'=>'ajax','page'=>$page));	

}

function _genericInstallUninstall($strFunction){
	global $Parameters,$staticFile,$staticPath;

	if ((isset($Parameters[0])) && ($Parameters[0]  != "" )) {
		$pkg = $Parameters[0];
		$ret = "";

		$page = "<div id='console'><img src='".$staticPath."images/ajax_loader.gif' width='40px' height='40px' /> ".$strFunction." '".$pkg."' package, please wait!</div>";
		$page .= "<script>\n";
		$page .= "$('#console').load('".$staticFile."/default/real".$strFunction."/".$pkg."');\n";
		$page .= "</script>\n";

		return(array('type'=>'render','page'=>$page));	
	} else {
		return(array('type'=>'redirect','url' => $staticFile.'/'));
	}
}
