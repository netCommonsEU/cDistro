<?php
$urlpath="$staticFile/docker";
$docker_pkg = "docker.io";

function index() {
	global $title, $urlpath, $docker_pkg;

	$page = hlc(t("docker_title"));
	$page .= hl(t("docker_desc"), 4);

	if (!isPackageInstall($docker_pkg)) {
		$page .= "<div class='alert alert-error text-center'>".t("docker_not_installed")."</div>\n";
		$page .= addButton(array('label'=>t("syncthing_install"),'class'=>'btn btn-success', 'href'=>"$urlpath/install"));
		return array('type'=>'render','page'=>$page);
	} elseif (!isConfigured()) {
		$page .= "<div class='alert alert-error text-center'>".t("docker_not_configured")."</div>\n";
		$page .= addButton(array('label'=>t("docker_configure"),'class'=>'btn btn-success', 'href'=>"$urlpath/configure"));
		$page .= addButton(array('label'=>t("docker_remove"),'class'=>'btn btn-danger', 'href'=>"$urlpath/remove"));
		return array('type'=>'render','page'=>$page);
	} elseif (!isRunning()) {
		$page .= "<div class='alert alert-error text-center'>".t("docker_not_running")."</div>\n";
		$page .= addButton(array('label'=>t("syncthing_start"),'class'=>'btn btn-success', 'href'=>"$urlpath/start"));
		$page .= addButton(array('label'=>t("syncthing_remove"),'class'=>'btn btn-danger', 'href'=>"$urlpath/remove"));
		return array('type'=>'render','page'=>$page);
	} else {
		$page .= "<div class='alert alert-success text-center'>".t("docker_running")."</div>\n";
		$page .= addButton(array('label'=>t("syncthing_stop"),'class'=>'btn btn-danger', 'href'=>"$urlpath/stop"));

		return array('type' => 'render','page' => $page);
	}
}
function isConfigured() {
  return true;
}
function isRunning(){
  return true;
}
