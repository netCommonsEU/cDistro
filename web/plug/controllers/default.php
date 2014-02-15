<?php
// Default file..

function index(){

	$page = "";

	$page .= hl(t('Welcome!'));
	$page .= t('Configure your system!');

	return(array('type'=>'render','page'=>$page));
} 

function install(){
	return(_genericInstallUninstall('Install'));
}

function uninstall(){
	return(_genericInstallUninstall('Uninstall'));
}

function lang() {
	global $LANG,$staticFile,$Parameters;


	if (isset($Parameters[0]) && ($Parameters[0]  != "" )) {
		$LANG=$Parameters[0];
		setSessionValue('lang',$LANG);
		setFlash(t('Define language to: ')."$LANG");
	}
	return(array('type'=>'redirect','url' => $staticFile));
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
			$ret = "$pkg ".t("is already installed.");
		}

		$page .= hl(t("Installed")." '".$pkg."'");
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
			$ret = "$pkg ".t("isn't installed.");
		}

		$page .= hl(t("Uninstalled")." '".$pkg."'");
		$page .= "<pre>";
		$page .= $ret;
		$page .= "</pre>";
	}
	
	$page .= "<a class='btn btn-primar' href='".$staticFile."'>".t("Home")."</a>";

	return(array('type'=>'ajax','page'=>$page));	

}

function _genericInstallUninstall($strFunction){
	global $Parameters,$staticFile,$staticPath;

	if ((isset($Parameters[0])) && ($Parameters[0]  != "" )) {
		$pkg = $Parameters[0];
		$ret = "";

		$page = "<div id='console'><img src='".$staticPath."images/ajax_loader.gif' width='40px' height='40px' /> ";
		$page .= t($strFunction)." '".$pkg."' ".t("package, please wait!")."</div>";
		$page .= "<script>\n";
		$page .= "$('#console').load('".$staticFile."/default/real".$strFunction."/".$pkg."');\n";
		$page .= "</script>\n";

		return(array('type'=>'render','page'=>$page));	
	} else {
		return(array('type'=>'redirect','url' => $staticFile.'/'));
	}
}
