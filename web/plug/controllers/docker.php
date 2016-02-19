<?php
$urlpath="$staticFile/docker";
$docker_pkg = "docker.io";

function index() {
	global $title, $urlpath, $docker_pkg;

	$page = hlc(t("docker_title"));
	$page .= hl(t("docker_desc"), 4);

	if (!isPackageInstall($docker_pkg)) {
		$page .= "<div class='alert alert-error text-center'>".t("docker_not_installed")."</div>\n";
		$page .= addButton(array('label'=>t("docker_install"),'class'=>'btn btn-success', 'href'=>"$urlpath/install"));
		return array('type'=>'render','page'=>$page);
	} elseif (!isRunning()) {
		$page .= "<div class='alert alert-error text-center'>".t("docker_not_running")."</div>\n";
		$page .= addButton(array('label'=>t("docker_start"),'class'=>'btn btn-success', 'href'=>"$urlpath/start"));
		$page .= addButton(array('label'=>t('docker_remove'),'class'=>'btn btn-danger', 'href'=>$staticFile.'/default/uninstall/'.$docker_pkg));
		return array('type'=>'render','page'=>$page);
	} else {
		$page .= "<div class='alert alert-success text-center'>".t("docker_running")."</div>\n";
		$page .= addButton(array('label'=>t("docker_stop"),'class'=>'btn btn-danger', 'href'=>"$urlpath/stop"));

		return array('type' => 'render','page' => $page);
	}
}
function isRunning(){
	$cmd = "/usr/bin/docker ps";
	$ret = execute_program($cmd);
  return ( $ret['return'] ==  0 );
}
function install(){
  global $title, $urlpath, $docker_pkg;

  $page = package_not_install($docker_pkg,t("docker_desc"));
  return array('type' => 'render','page' => $page);
}
function start() {
	globals $staticFile,$urlpath;

	execute_program_detached("service docker start");
	setFlash(t('docker_start'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}
function stop() {
	globals $staticFile,$urlpath;

	execute_program_detached("service docker start");
	setFlash(t('docker_stop'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}
