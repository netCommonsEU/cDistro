<?php
$urlpath="$staticFile/docker";
$dev = "docker0";

function index() {
    global $title, $urlpath, $docker_pkg, $staticFile;

    $page = "";
    $buttons = "";

    $page .= hlc(t("docker_title"));
    $page .= hl(t("docker_subtitle"),4);
    $page .= par(t("docker_desc"));
    $page .= txt(t("docker_status"));

    if (!isPackageInstall($docker_pkg)) {
        $page .= "<div class='alert alert-error text-center'>".t("docker_alert_not_installed")."</div>\n";
        $page .= addButton(array('label'=>t("docker_button_install"),'class'=>'btn btn-success', 'href'=>"$urlpath/install"));
    }
    elseif (!isRunning()) {
        $page .= "<div class='alert alert-error text-center'>".t("docker_alert_not_running")."</div>\n";
        $page .= addButton(array('label'=>t("docker_button_start"),'class'=>'btn btn-success', 'href'=>"$urlpath/start"));
        $page .= addButton(array('label'=>t('docker_button_uninstall'),'class'=>'btn btn-danger', 'href'=>$staticFile.'/default/uninstall/'.$docker_pkg));
    }
    else {
        $page .= "<div class='alert alert-success text-center'>".t("docker_alert_running")."</div>\n";
        $buttons .= addButton(array('label'=>t("docker_button_stop"),'class'=>'btn btn-danger', 'href'=>"$urlpath/stop"));
        $buttons .= addButton(array('label'=>t("docker_button_search"),'class'=>'btn btn-info', 'href'=>"$staticFile/docker-search"));
        $page .= docker_ps_running()["page"];
        $page .= docker_ps_stopped()["page"];
        $page .= docker_img()["page"];
    }

    $page .= $buttons;

    return array('type' => 'render','page' => $page);
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
    global $urlpath;

    execute_program_detached("service docker start");
    setFlash(t('docker_alert_start_message'),"success");

    return(array('type'=> 'redirect', 'url' => $urlpath));
}


function stop() {
    global $urlpath;

    execute_program_detached("service docker stop");
    setFlash(t('docker_alert_stop_message'),"success");

    return(array('type'=> 'redirect', 'url' => $urlpath));
}


function info_docker(){
    global $dev;

    $cmd = "/sbin/ip addr show dev $dev";
    $ret = execute_program($cmd);

    return ( implode("\n", $ret['output']) );
}


function container() {
    global $Parameters, $dev, $title, $urlpath, $docker_pkg, $staticFile;

    switch ($Parameters[0]) {
        case "rm":
            if (isset($Parameters[1]))
                if (isset($Parameters[2]))
                    return _dockercontainerrm($Parameters[1], $Parameters[2]);
                return _dockercontainerrm($Parameters[1]);
            break;

        case "stop":
            if (isset($Parameters[1]))
                if (isset($Parameters[2]))
                    return _dockercontainerstop($Parameters[1], $Parameters[2]);
                return _dockercontainerstop($Parameters[1]);
            break;

        case "pull":
            if (isset($Parameters[1]))
                if (isset($Parameters[2]))
                    return _dockercontainerpull($Parameters[1] . "/" . $Parameters[2]);
                return _dockercontainerpull($Parameters[1]);
            break;

        case "restart":
            if (isset($Parameters[1]))
                if (isset($Parameters[2]))
                    return _dockercontainerrestart($Parameters[1], $Parameters[2]);
                return _dockercontainerrestart($Parameters[1]);
            break;

        default:
            return(array('type'=> 'redirect', 'url' => $urlpath));
    }
}


function image() {
    global $Parameters, $dev, $title, $urlpath, $docker_pkg, $staticFile;

    switch ($Parameters[0]) {
        case "rmi":
            if (isset($Parameters[1]))
                if (isset($Parameters[2]))
                    return _dockerimagermi($Parameters[1], $Parameters[2]);
                return _dockerimagermi($Parameters[1]);
            break;

        case "run":
            if (isset($Parameters[1]))
                if (isset($Parameters[2]))
                    return _dockerimagerun($Parameters[1], $Parameters[2]);
                return _dockerimagerun($Parameters[1]);
            break;

        default:
            return(array('type'=> 'redirect', 'url' => $urlpath));
    }
}


function _dockercontainerrm($id, $name) {
    global $Parameters, $urlpath, $staticFile;

    execute_program_detached("docker stop " . $id . " && docker rm " . $id);
    setFlash( t("docker_flash_rm_pre") . $name . t("docker_flash_rm_mid") . $id . t("docker_flash_rm_post"));

  return(array('type'=> 'redirect', 'url' => $urlpath));
}


function _dockercontainerpull($name) {
    global $Parameters, $urlpath, $staticFile;

    execute_program_detached("docker pull " . $name);
    setFlash( t("docker_flash_pull_pre") . "<b>" . $name . "</b>" . t("docker_flash_pull_post"));

    return(array('type'=> 'redirect', 'url' => $urlpath));
}


function _dockercontainerstop($id, $name) {
    global $Parameters, $urlpath, $staticFile;

    execute_program_detached("docker stop " . $id);
    setFlash( t("docker_flash_stop_pre") . $name . t("docker_flash_stop_mid") . $id . t("docker_flash_stop_post"));

    return(array('type'=> 'redirect', 'url' => $urlpath));
}


function _dockercontainerrestart($id, $name) {
    global $Parameters, $urlpath, $staticFile;

    execute_program_detached("docker restart " . $id);
    setFlash( t("docker_flash_restart_pre") . $name . t("docker_flash_restart_mid") . $id . t("docker_flash_restart_post"));

    return(array('type'=> 'redirect', 'url' => $urlpath));
}


function _dockerimagermi($id, $name) {
    global $Parameters, $urlpath, $staticFile;

    execute_program_detached("docker rmi " . $id);
    setFlash( t("docker_flash_rmi_pre") . $name . t("docker_flash_rmi_mid") . $id . t("docker_flash_rmi_post"));

    return(array('type'=> 'redirect', 'url' => $urlpath));
}


function _dockerimagerun($id, $name) {
    global $Parameters, $urlpath, $staticFile;

    execute_program_detached("docker run " . $id);
    setFlash( t("docker_flash_run_pre") . $name . t("docker_flash_run_mid") . $id . t("docker_flash_run_post"));

	return(array('type'=> 'redirect', 'url' => $urlpath));
}