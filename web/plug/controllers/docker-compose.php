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
        if (sizeof(_docker_compose_get_projects()) < 1 ) {
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


function create() {
    return create_post();
}

function create_post() {
    global $Parameters, $title, $urlpath, $docker_pkg, $docker_compose_pkg, $staticFile, $package, $proj_dir;

    //Regex for name field: only alphanumeric characters with dashes between
    $name_regex = "^[a-zA-Z0-9]+[a-zA-Z0-9\-]*[a-zA-Z0-9^-_]$";
    $name_preg = "/".$name_regex."/";

    $page = "";
    $buttons = "";
    $errors = "";
    
    $form_error = -1;

    $page .= hlc(t("docker_compose_title"));
    $page .= hl(t("docker_compose_subtitle"),4);
    $page .= par(t("docker_compose_create_desc"));

    $projname = "";
    $projdcy = "version: '3'

services:
   db:
     image: mysql:5.7
     volumes:
       - db_data:/var/lib/mysql
     restart: always
     environment:
       MYSQL_ROOT_PASSWORD: somewordpress
       MYSQL_DATABASE: wordpress
       MYSQL_USER: wordpress
       MYSQL_PASSWORD: wordpress

   wordpress:
     depends_on:
       - db
     image: wordpress:latest
     ports:
       - \"8000:80\"
     restart: always
     environment:
       WORDPRESS_DB_HOST: db:3306
       WORDPRESS_DB_USER: wordpress
       WORDPRESS_DB_PASSWORD: wordpress
volumes:
    db_data:";

    if ( !empty($_POST) ) {
        $form_error++;
        if (isset($_POST['name'])) {
            $projname = $_POST['name'];
            if ( $projname == "") {
                $form_error++;
                $errors .= "<div class='alert alert-warning text-center'>".t("docker_compose_alert_form_name_blank")."</div>\n";
            }
            elseif ( !preg_match($name_preg, $projname) ) {
                $form_error++;
                $errors .= "<div class='alert alert-warning text-center'>".t("docker_compose_alert_form_name_invalid")."</div>\n";
            }
        }
        else {
            $form_error++;
            $errors .= "<div class='alert alert-error text-center'>".t("docker_compose_alert_form_name_empty")."</div>\n";
        }

        if (isset($_POST['dcy'])) {
            $projdcy = $_POST['dcy'];
            if ( $projdcy == "") {
                $form_error++;
                $errors .= "<div class='alert alert-warning text-center'>".t("docker_compose_alert_form_dcy_blank")."</div>\n";
            }
            // docker-compose.yml validation not implemented
            // elseif ( !validate_dcy() ) {
            //     $form_error++;
            //     $errors .= "<div class='alert alert-warning text-center'>".t("docker_compose_alert_form_dcy_invalid")."</div>\n";
            // }
        }
        else {
            $form_error++;
            $errors .= "<div class='alert alert-error text-center'>".t("docker_compose_alert_form_dcy_empty")."</div>\n";
        }
    }

    if ($form_error == 0){
        if ( is_dir($proj_dir."/".$projname) || is_file($proj_dir."/".$projname) ) {
            $form_error++;
            $errors .= "<div class='alert alert-error text-center'>".t("docker_compose_alert_form_name_inuse")."</div>\n";
        }
        else {
            if ( mkdir($proj_dir."/".$projname) ) {
                $fpc_res = file_put_contents ($proj_dir."/".$projname."/docker-compose.yml",  $projdcy);
                if ( $fpc_res === false || $fpc_res == 0 ) {
                    $form_error++;
                    $errors .= "<div class='alert alert-error text-center'>".t("docker_compose_alert_form_dcy_put_fail_pre").$proj_dir."/".$projname."/docker-compose.yml".t("docker_compose_alert_form_dcy_put_fail_post")."</div>\n";
                }
                else {
                    setFlash(t("docker_compose_alert_form_project_pre").$projname.t("docker_compose_alert_form_project_post"),"success");
                    return(array('type'=> 'redirect', 'url' => $urlpath));
                }
            }
            else{
                $form_error++;
                $errors .= "<div class='alert alert-error text-center'>".t("docker_compose_alert_form_name_mkdir_fail_pre").$proj_dir."/".$projname.t("docker_compose_alert_form_name_mkdir_fail_post")."</div>\n";
            }
        }
    }

    switch ($form_error) {
        case 1:
            $page .= txt(t("docker_compose_create_form_error"));
            $page .= $errors;
            break;

        case 2:
            $page .= txt(t("docker_compose_create_form_errors"));
            $page .= $errors;
            break;
    }


    //Form
    $page .= createForm(array('class'=>'form-horizontal'));
    $page .= addInput('name',t("docker_compose_create_form_name"),$projname,array('type'=>'text','required'=>true,'placeholder'=>'compose_project','pattern'=>$name_regex),"",t("docker_compose_create_form_name_tooltip"));
    $page .= addTextArea('dcy', t('docker_compose_create_form_dcy'), $projdcy, array('rows'=>10, 'cols'=>"80", 'required'=>'true'),'style="width: 600px"',t('docker_compose_create_form_dcy_tooltip'));

    $buttons .= addButton(array('label'=>t("docker_compose_button_back"),'class'=>'btn btn-default', 'href'=>$urlpath));
    $buttons .= addSubmit(array('label'=>t("docker_compose_button_create"),'class'=>'btn btn-success','divOptions'=>array('class'=>'btn-group')));

    $page .= $buttons;

    return array('type' => 'render','page' => $page);
    
}

function delete() {
    global $Parameters, $title, $urlpath, $docker_pkg, $docker_compose_pkg, $staticFile, $package, $proj_dir;

    $page = "";
    $buttons = "";

//    if (isset($Parameters[0]) && $Parameters[0] != null && is_dir($proj_dir."/".$Parameters[0]) && !is_file($proj_dir."/".$Parameters[0])) {
if (true){
        $projname = $Parameters[0];
        $docks_in_proj_run = array();
        foreach (docker_ps_running() as $l => $w) {
            if ( (strpos(str_replace("-", "", $w), str_replace("-", "", $projname.'_')) === 0) && ( strlen(str_replace("-", "", $w)) > strlen(str_replace("-", "", $projname)))) {
                array_push($docks_in_proj_run, $w);
            }
        }

        if ( sizeof($docks_in_proj_run) == 0 ) {
            if (_delTree ($proj_dir."/".$projname)) {
                setFlash( t("docker_compose_flash_delete_pre") . $projname . t("docker_compose_flash_delete_post"), "success");
                return(array('type'=> 'redirect', 'url' => $urlpath));
            }
            else {
                setFlash( t("docker_compose_flash_delete_error_pre")."<strong>".$proj_dir."/".$projname."</strong>".t("docker_compose_flash_delete_error_post"),"error" );
                return(array('type'=> 'redirect', 'url' => $urlpath));
            }
        }
    

        else{
            $page .= hlc(t("docker_compose_title"));
            $page .= hl(t("docker_compose_subtitle"),4);
    
            $page .= txt(t("docker_compose_down_desc_pre")."<strong>$Parameters[0]</strong>".t("docker_compose_down_desc_post"));
            $page .= docker_ps_running_table($Parameters[0])["page"];

        }
    }

    else{
        //return(array('type'=> 'redirect', 'url' => $urlpath));
    }

    $buttons .= addButton(array('label'=>t("docker_compose_button_back"),'class'=>'btn btn-default', 'href'=>$urlpath));

    $page .= $buttons;

    return array('type' => 'render','page' => $page);
}


function down() {
    global $Parameters, $title, $urlpath, $docker_pkg, $docker_compose_pkg, $staticFile, $package, $proj_dir;

    $page = "";
    $buttons = "";

    if (isset($Parameters[0]) && $Parameters[0] != null && is_dir($proj_dir."/".$Parameters[0]) && !is_file($proj_dir."/".$Parameters[0])) {
        $projname = $Parameters[0];
        $docks_in_proj_run = array();
        foreach (docker_ps_running() as $l => $w) {
            if ( (strpos(str_replace("-", "", $w), str_replace("-", "", $projname.'_')) === 0) && ( strlen(str_replace("-", "", $w)) > strlen(str_replace("-", "", $projname)))) {
                array_push($docks_in_proj_run, $w);
            }
        }

        if ( sizeof($docks_in_proj_run) > 0 ) {
            foreach ($docks_in_proj_run as $k => $v) {
                execute_program_detached("docker stop " . $v);
                setFlash( t("docker_compose_flash_down_pre") . $projname . t("docker_compose_flash_down_post"));
            }
            return(array('type'=> 'redirect', 'url' => $urlpath));
        }

        else{
            $page .= hlc(t("docker_compose_title"));
            $page .= hl(t("docker_compose_subtitle"),4);
    
            $page .= txt(t("docker_compose_down_desc_pre")."<strong>$Parameters[0]</strong>".t("docker_compose_down_desc_post"));
            $page .= docker_ps_running_table($Parameters[0])["page"];

        }
    }

    else{
        return(array('type'=> 'redirect', 'url' => $urlpath));
    }

    $buttons .= addButton(array('label'=>t("docker_compose_button_back"),'class'=>'btn btn-default', 'href'=>$urlpath));

    $page .= $buttons;

    return array('type' => 'render','page' => $page);
}


function up() {
    global $Parameters, $title, $urlpath, $docker_pkg, $docker_compose_pkg, $staticFile, $package, $proj_dir;

    $page = "";
    $buttons = "";

    if (isset($Parameters[0]) && $Parameters[0] != null && is_dir($proj_dir."/".$Parameters[0]) && !is_file($proj_dir."/".$Parameters[0])) {
        $projname = $Parameters[0];
        $docks_in_proj_run = array();
        foreach (docker_ps_running() as $l => $w) {
            if ( (strpos(str_replace("-", "", $w), str_replace("-", "", $projname.'_')) === 0) && ( strlen(str_replace("-", "", $w)) > strlen(str_replace("-", "", $projname)))) {
                array_push($docks_in_proj_run, $w);
            }
        }

        if ( sizeof($docks_in_proj_run) == 0 ) {
            execute_program_detached("cd ".$proj_dir."/".$projname." && "."docker-compose up -d");
            setFlash( t("docker_compose_flash_up_pre") . $projname . t("docker_compose_flash_up_post"), "info");
            return(array('type'=> 'redirect', 'url' => $urlpath));
        }

        else{
            $page .= hlc(t("docker_compose_title"));
            $page .= hl(t("docker_compose_subtitle"),4);

            $page .= txt(t("docker_compose_up_desc_pre")."<strong>$Parameters[0]</strong>".t("docker_compose_up_desc_post"));
            $page .= docker_ps_stopped_table($Parameters[0])["page"];
            $page .= docker_ps_running_table($Parameters[0])["page"];

        }
    }

    else{
        return(array('type'=> 'redirect', 'url' => $urlpath));
    }

    $buttons .= addButton(array('label'=>t("docker_compose_button_back"),'class'=>'btn btn-default', 'href'=>$urlpath));

    $page .= $buttons;

    return array('type' => 'render','page' => $page);
}


function manage() {
    global $Parameters, $title, $urlpath, $docker_pkg, $docker_compose_pkg, $staticFile, $package, $proj_dir;

    $page = "";
    $buttons = "";

    $page .= hlc(t("docker_compose_title"));
    $page .= hl(t("docker_compose_subtitle"),4);

    if (isset($Parameters[0]) && $Parameters[0] != null) {
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
    }
    else {
        return(array('type'=> 'redirect', 'url' => $urlpath));
    }

    $buttons .= addButton(array('label'=>t("docker_compose_button_back"),'class'=>'btn btn-default', 'href'=>$urlpath));

    $page .= $buttons;

    return array('type' => 'render','page' => $page);
}


function _docker_compose_get_projects() {
    global $Parameters, $urlpath, $staticFile, $proj_dir;

    $projs = array();

    foreach (scandir($proj_dir) as $k => $v)
        if ( is_dir($proj_dir.'/'.$v) && ( ($v != '.') && ($v != '..')))
            array_push($projs, $v);

    return $projs;
}



function _docker_compose_list_projects(){
    global $dev, $title, $urlpath, $docker_pkg, $staticFile, $proj_dir;

    $page = "";
    $buttons = "";
    $table = "";

    $projects = _docker_compose_get_projects();

    $headers = array("Project name", "Associated containers", "Actions");
    $table .= addTableHeader($headers);


    foreach($projects as $k => $v){
        $docks_in_proj = array();
        $docks_in_proj_run = array();
        $docks_in_proj_stop = array();
        
        $button_start = false;
        $button_stop = false;
        $button_delete = false;
        $button_publish = false;
        $button_unpublish = false;

        $fields = array();
        $fields[] = $v;

        foreach (docker_ps_running() as $l => $w) {
            if ( (strpos(str_replace("-", "", $w), str_replace("-", "", $v.'_')) === 0) && ( strlen(str_replace("-", "", $w)) > strlen(str_replace("-", "", $v)))) {
                array_push($docks_in_proj, $w.' ('.t('docker_container_running').')');
                array_push($docks_in_proj_run, $w);
            }
        }
        foreach (docker_ps_stopped() as $l => $w) {
            if ( (strpos(str_replace("-", "", $w), str_replace("-", "", $v.'_')) === 0) && ( strlen(str_replace("-", "", $w)) > strlen(str_replace("-", "", $v)))) {
                array_push($docks_in_proj, $w.' ('.t('docker_container_stopped').')');
                array_push($docks_in_proj_stop, $w);
            }
        }
        $fields[] = implode(", \n",$docks_in_proj);

        if (sizeof($docks_in_proj_stop) == 0 && sizeof($docks_in_proj_run) == 0 )
            $button_delete = true;
        if (sizeof($docks_in_proj_run) == 0 )
                $button_start = true;
        if (sizeof($docks_in_proj_run) > 0 )
            $button_stop = true;

        $fields[] = addButton(array('label'=>t("docker_compose_button_manage"),'class'=>'btn btn-primary', 'href'=>"$urlpath/manage/".$fields[0]));
        if ($button_start)
            $fields[] = addButton(array('label'=>t("docker_compose_button_start"),'class'=>'btn btn-success', 'href'=>"$urlpath/up/".$fields[0]));
        if ($button_stop)
            $fields[] = addButton(array('label'=>t("docker_compose_button_stop"),'class'=>'btn btn-basic', 'href'=>"$urlpath/down/".$fields[0]));
        if ($button_delete)
            $fields[] = addButton(array('label'=>t("docker_compose_button_delete"),'class'=>'btn btn-danger', 'href'=>"$urlpath/delete/".$fields[0]));

        $table .= addTableRow($fields);
    }

    $table .= addTableFooter();
    $page .= $table;

    return $page;
}

function _docker_compose_isInstalled() {
    return( file_exists( "/usr/local/bin/docker-compose") );
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
