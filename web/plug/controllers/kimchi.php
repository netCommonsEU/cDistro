<?php
//controllers/kimchi.php

$webpage="https://github.com/kimchi-project/kimchi";
$execpath='/usr/bin/kimchid';
$preinstallpath="/opt/repos/kimchi/";
$kimchiinit="/etc/init.d/kimchi";
$getzip='https://codeload.github.com/Clommunity/kimchi-install/zip/master';
$urlpath="/kimchi";
$port="8001";


function _installed_kimchi(){
	global $execpath;
	return(is_file($execpath));
}

function _run_kimchi(){
	global $port;

	$cmd = 'netstat -an|grep LISTEN|grep -q "' . $port . '"';
	$ret = execute_shell($cmd);
	return($ret['return'] ==  0 );
}

function getprogram(){
	global $staticFile, $getzip, $urlpath, $preinstallpath;

	if (!_download_kimchi()) {
		$cmd = "mkdir -p ".$preinstallpath."; f=$(mktemp); curl ".$getzip." > \$f; unzip -xj -d ".$preinstallpath." \$f; rm -f \$f";
		$ret = shell_exec($cmd);

		setFlash(t('kimchi_being_preinstalled'),"warning");
	}
	if (is_file($preinstallpath."kimstall.sh")){
		$cmd = "cd ".$preinstallpath."; ./kimstall.sh";
		$ret = shell_exec($cmd);
		setFlash(t('kimchi_being_installed'),"warning");
	} else {
		setFlash(t('kimchi_problems_with_download'),"error");
	}

	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function _download_kimchi(){
	global $preinstallpath;
	return(is_dir($preinstallpath));
}

function removeprogram(){
	global $urlpath, $staticFile;

	setFlash(t('This feature is not yet implemented.'),"error");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function runprogram(){
	global $urlpath, $staticFile, $kimchiinit;

	$cmd = $kimchiinit." start";
	execute_program_detached($cmd);

	setFlash(t('kimchi_start'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function stopprogram(){
	global $urlpath, $staticFile, $kimchiinit;

	$cmd = $kimchiinit." stop";
	execute_program_detached($cmd);

	setFlash(t('kimchi_stop'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function index()
{
	global $urlpath,$port;

	$page = "";
	$buttons = "";

	$page .= hlc(t("kimchi_title"));
	$page .= hl(t("kimchi_subtitle"),4);
	$page .= par(t("kimchi_description"));

	if (!_installed_kimchi()) {
		$page .= "<div class='alert alert-error text-center'>".t("kimchi_not_installed")."</div>\n";
   		$page .= par(t("kimchi_installation_explanation"));
		$buttons .= addButton(array('label'=>t("kimchi_button_install"),'class'=>'btn btn-success', 'href'=>"$urlpath/install"));
	}
	else {
		if (!_run_kimchi()) {
			$page .= "<div class='alert alert-warning text-center'>".t("kimchi_not_running")."</div>\n";
			$buttons .= addButton(array('label'=>t("kimchi_button_start"),'class'=>'btn btn-success', 'href'=>"$urlpath/runprogram"));
			//$buttons .= addButton(array('label'=>t("kimchi_button_uninstall"),'class'=>'btn btn-danger', 'href'=>"$urlpath/removeprogram"));
		}
		else {
			$page .= "<div class='alert alert-success text-center'>".t("kimchi_running")."</div>\n";
			$page .= "<div class='alert alert-warning text-center'>".t("kimchi_default_login")."</div>\n";
			$host = explode(':', $_SERVER['HTTP_HOST'])[0];
			$buttons .= addButton(array('label'=>t("kimchi_button_go_to"),'class'=>'btn btn-primary', 'target'=>'_blank', 'href'=>"https://".$host.":".$port));
			$buttons .= addButton(array('label'=>t("kimchi_button_stop"),'class'=>'btn btn-danger', 'href'=>"$urlpath/stopprogram"));
		}
	}

 	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}


function install()
{
	global $urlpath,$staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc(t("kimchi_title"));
	$page .= hl(t("kimchi_subtitle"),4);

	if (!_installed_kimchi()) {
		$page .= "<div class='alert alert-warning text-center'>".t("kimchi_warning")."</div>\n";
   		$page .= par(t("kimchi_understand_warning"));
		$buttons .= addButton(array('label'=>t("kimchi_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.$urlpath));
		$buttons .= addButton(array('label'=>t("kimchi_button_understand_install"),'class'=>'btn btn-success', 'href'=>"$urlpath/getprogram"));
	}
	else {
		return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
	}

 	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}
