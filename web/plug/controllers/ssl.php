<?php
$configFile="/etc/cloudy/cloudy.conf";
$sslShell=dirname(__FILE__)."/../resources/ssl/ssl.sh";
$urlpath=$staticFile."/ssl";
$sslPort=7443;
$httpPort=7000;

function index() {

	global $urlpath;

	$page = hlc(t("ssl_title"));
	$page .= hl(t("ssl_desc"), 4);

	if (!isInstalled()) {
		$page .= "<div class='alert alert-error text-center'>".t("https_is_not_installed")."</div>\n";
		$page .= addButton(array('label'=>t("ssl_install"),'class'=>'btn btn-success', 'href'=>"$urlpath/install"));
	} else {
		$page .= "<div class='alert alert-succes text-center'>".t("https_is_installed")."</div>\n";
		$page .= addButton(array('label'=>t("ssl_remove"),'class'=>'btn btn-danger', 'href'=>"$urlpath/uninstall"));
	}

	return array('type' => 'render','page' => $page);
}

function isInstalled(){
	global $configFile;

	$check = "/bin/fgrep 'PORT_SSL' ".$configFile." | wc -l";
	$ret = intval(execute_program_shell($check)['output']);
	return ($ret == 1);
}

function install(){
	global $sslShell, $urlpath, $sslPort, $appHost;

	if (isInstalled()){
		setFlash(t('ssl_is_installed_yet'),"error");
		return(array('type'=> 'redirect', 'url' => $urlpath));
	}

	$appAddress = explode(":",$appHost)[0];

	execute_program_detached($sslShell." install");
	return(array('type'=> 'redirect', 'url' => "https://".$appAddress.":".$sslPort));
}

function uninstall(){
	global $sslShell, $urlpath, $httpPort, $appHost;

	if (!isInstalled()){
		setFlash(t('ssl_is_not_installed_yet'),"error");
		return(array('type'=> 'redirect', 'url' => $urlpath));
	}

	$appAddress = explode(":",$appHost)[0];

	execute_program_detached($sslShell." remove");
	return(array('type'=> 'redirect', 'url' => "http://".$appAddress.":".$httpPort));

}