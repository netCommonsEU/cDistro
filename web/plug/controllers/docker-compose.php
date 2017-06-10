<?php
$urlpath="$staticFile/docker-compose";
$dev = "docker_compose0";
$package['user'] = "Clommunity";
$package['repo'] = "package-docker-compose";
$docker_compose_pkg = "docker-compose";
$proj_dir = "/etc/cloudy/docker-compose/projects";

function index() {
    global $title, $urlpath, $docker_pkg, $docker_compose_pkg, $staticFile, $package;

    $page = "";
    $buttons = "";

    $page .= hlc(t("docker_compose_title"));
    $page .= hl(t("docker_compose_subtitle"),4);
    $page .= par(t("docker_compose_desc"));

    //Check if Docker is installed
    if (!isPackageInstall($docker_pkg)) {
        $page .= txt(t("docker_status"));
        $page .= "<div class='alert alert-error text-center'>".t("docker_alert_not_installed")."</div>\n";
        $page .= addButton(array('label'=>t("docker_button_install"),'class'=>'btn btn-success', 'href'=>"$urlpath/install"));
    }
    //Check if Docker Compose is installed
    elseif (!_docker_compose_isInstalled()) {
        $page .= txt(t("docker_compose_status"));
        $page .= "<div class='alert alert-error text-center'>".t("docker_compose_alert_not_installed")."</div>\n";
        $buttons .= addButton(array('label'=>t("docker_compose_button_install"),'class'=>'btn btn-success', 'href'=>$staticFile.'/cloudyupdate/update/'.$docker_compose_pkg));
    }
    //Docker and Docker Compose are installed
    else {
        //No Docker Compose projects
        if (count(sizeof(_docker_compose_get_projects())) < 1 ) {
            $page .= txt(t("docker_compose_status"));
            $page .= "<div class='alert alert-warning text-center'>".t("docker_compose_alert_no_projects")."</div>\n";
        }
        //Show Docker Compose projects table
        else {
            $page .= _docker_compose_list_projects();
        }
        $buttons .= addButton(array('label'=>t("docker_compose_button_create"),'class'=>'btn btn-success', 'href'=>"$urlpath/create"));
        $buttons .= addButton(array('label'=>t("docker_compose_button_clone"),'class'=>'btn btn-info', 'href'=>"$urlpath/clone"));
    }

    $page .= $buttons;

    return array('type' => 'render','page' => $page);
}


function manage() {
    global $Parameters, $title, $urlpath, $docker_pkg, $docker_compose_pkg, $staticFile, $package, $proj_dir;

    $page = "";
    $buttons = "";

    $page .= hlc(t("docker_compose_title"));
    $page .= hl(t("docker_compose_subtitle"),4);
    $page .= par(t("docker_compose_manage_desc_pre")."<strong>$Parameters[0]</strong>".t("docker_compose_manage_desc_post"));

    $page .= txt(t("docker_compose_manage_containers"));
    $page .= docker_ps_running_table($Parameters[0])["page"];
    $page .= docker_ps_stopped_table($Parameters[0])["page"];

    $page .= txt(t("docker_compose_manage_files"));
    foreach (scandir($proj_dir.'/'.$Parameters[0]) as $key => $value)
        if (($value != '.' ) && ($value != '..') && (!is_dir($proj_dir.'/'.$Parameters[0].'/'.$value))) {
            $page .= txt($value);
            $file5 = implode("", array_slice(file($proj_dir.'/'.$Parameters[0].'/'.$value), 0, 5));
            $file6 = implode("", array_slice(file($proj_dir.'/'.$Parameters[0].'/'.$value), 0, 6));
            if ($file5 == $file6)
                $page .= ptxt($file5);
            else
                $page .= ptxt($file6."[...]");
            //$buttons .= addButton(array('label'=>t("settings_button_sources_pre").$value.t("settings_button_sources_post"),'class'=>'btn btn-primary', 'href'=>$staticPath.$urlpath.'/sourceManage?file='.$SOURCESD_PATH.'/'.$value));
        }

    $buttons .= addButton(array('label'=>t("docker_compose_button_back"),'class'=>'btn btn-default', 'href'=>$urlpath));

    $page .= $buttons;

    return array('type' => 'render','page' => $page);
}


function _docker_compose_get_projects() {
  global $Parameters, $urlpath, $staticFile, $proj_dir;

  $projs = array();

  foreach (scandir($proj_dir) as $k => $v)
    if (is_dir($proj_dir.'/'.$v) && ( ($v != '.') && ($v != '..')))
      array_push($projs, $v);

  return $projs;
}

function _docker_compose_list_projects(){
    global $dev, $title, $urlpath, $docker_pkg, $staticFile, $proj_dir;

    $page = "";
    $buttons = "";
    $table = "";

    $projects = _docker_compose_get_projects();

    $headers = array("Name", "Associated containers", "Actions");
    $table .= addTableHeader($headers);


    foreach($projects as $k => $v){
        $docks_in_proj = array();

        $fields = array();
        $fields[] = $v;

        foreach (docker_ps_running() as $l => $w) {
            if ( (strpos(str_replace("-", "", $w), str_replace("-", "", $v.'_')) === 0) && ( strlen(str_replace("-", "", $w)) > strlen(str_replace("-", "", $v))))
                array_push($docks_in_proj, $w.' ('.t('docker_container_running').')');
        }
        foreach (docker_ps_stopped() as $l => $w) {
            if ( (strpos(str_replace("-", "", $w), str_replace("-", "", $v.'_')) === 0) && ( strlen(str_replace("-", "", $w)) > strlen(str_replace("-", "", $v))))
                array_push($docks_in_proj, $w.' ('.t('docker_container_stopped').')');
        }
        $fields[] = implode(", \n",$docks_in_proj);

        $fields[] = addButton(array('label'=>t("docker_compose_button_manage"),'class'=>'btn btn-primary', 'href'=>"$urlpath/manage/".$fields[0]));
        $table .= addTableRow($fields);
    }

    $table .= addTableFooter();
    $page .= $table;

    return $page;
}

function _docker_compose_isInstalled() {
    return( file_exists( "/usr/local/bin/docker-compose") );
}
