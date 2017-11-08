<?php
// Update files
// Example serf preinstall:
// 'serf'=>array('user'=>'Clommunity', 'repo'=>'package-serf','type'=>'preinstall','controller'=>'serf', 'function-check'=>'_isInstalled', 'script'=>'https://raw.githubusercontent.com/Clommunity/package-serf/master/getgithub')

$list_packages = array(
    'cDistro' => array(
        'user'           => 'Clommunity',
        'repo'           => 'cDistro',
        'type'           => 'manual',
        'script'         => 'https://raw.githubusercontent.com/Clommunity/lbmake/master/hooks/cDistro.chroot',
    ),
    'avahi-ps' => array(
        'user'           => 'Clommunity',
        'repo'           => 'avahi-ps',
        'type'           => 'manual',
        'script'         => 'https://raw.githubusercontent.com/Clommunity/lbmake/master/hooks/avahi-ps.chroot',
    ),
    'docker-compose' => array(
        'user'           => 'Clommunity',
        'repo'           => 'package-docker-compose',
        'type'           => 'automatic',
        'controller'     => 'docker-compose',
        'function-check' => '_docker_compose_isInstalled',
        'script'         => 'https://raw.githubusercontent.com/Clommunity/lbmake/master/hooks/docker-compose.chroot',
    ),
    'serf' => array(
        'user'           => 'Clommunity',
        'repo'           => 'package-serf',
        'type'           => 'manual',
        'controller'     => 'serf',
        'script'         => 'https://raw.githubusercontent.com/Clommunity/lbmake/master/hooks/serf.chroot',
    ),
    'ipfs' => array(
        'user'           => 'Clommunity',
        'repo'           => 'package-ipfs',
        'type'           => 'manual',
        'controller'     => 'ipfs',
        'script'         => 'https://raw.githubusercontent.com/Clommunity/lbmake/master/hooks/ipfs.chroot',
    ),
    'peerstreamer' => array(
        'user'           => 'Clommunity',
        'repo'           => 'build-peerstreamer',
        'type'           => 'preinstall',
        'controller'     => 'peerstreamer',
        'function-check' => '_peerstreamer_isInstalled',
        'script'         => 'https://raw.githubusercontent.com/Clommunity/lbmake/master/hooks/peerstreamer.chroot',
    ),
);

$dir_configs="/etc/cloudy";

function index_get()
{
    global $list_packages,$staticFile;

    $page = "";
    $buttons = "";

    $page .= hlc(t("cloudyupdate_common_title"));
    $page .= hl(t("cloudyupdate_index_subtitle"), 4);

    $page .= par(t("cloudyupdate_index_description1") . ' ' . t("cloudyupdate_index_description2"));

    $page .= hlc(t("cloudyupdate_index_cloudy_packages"), 3);
    $page .= ajaxStr('tPackages', t("cloudyupdate_flash_loading_cloudy"));
    $page .= "<script>\n";
    $page .= "$('#tPackages').load('".$staticFile."/cloudyupdate/getCloudyUpdateTable');\n";
    $page .= "</script>\n";

    $page .= "</br>";

    $page .= hlc(t("cloudyupdate_index_debian_packages"), 3);

    if (isset($_GET["debupdate"])) {
        $page .= ajaxStr('dPackages', t("cloudyupdate_flash_loading_debian"));
        $page .= "<script>\n";
        $page .= "$('#dPackages').load('".$staticFile."/cloudyupdate/getDebianUpdateTable');\n";
        $page .= "</script>\n";
    } else {
        $page .= par(t("cloudyupdate_index_debian_description1"));
        $buttons .= addButton(array('label'=>t('cloudyupdate_button_update_debian'),'href'=>$staticFile.'/cloudyupdate/debupdate','divOptions'=>array('class'=>'btn-group')));
    }


    $page .= $buttons;
    return(array('type' => 'render','page' => $page));
}

function getCloudyUpdateTable()
{
    global $list_packages,$staticFile,$documentPath,$plugs_controllers;

    $table = "";

    $headers = array();
    $headers[] = t('cloudyupdate_getCloudyUpdateTable_package');
    $headers[] = t('cloudyupdate_getCloudyUpdateTable_version');
    $headers[] = t('cloudyupdate_getCloudyUpdateTable_new');
    $headers[] = t('cloudyupdate_getCloudyUpdateTable_date');
    $headers[] = t('cloudyupdate_getCloudyUpdateTable_action');
    $table .= addTableHeader($headers);

    foreach ($list_packages as $pname => $package) {
        if ($package['type'] == 'preinstall') {
            require $documentPath.$plugs_controllers.$package['controller'].".php";
            if (! $package['function-check']()) {
                // Is not installed
                continue;
            }
        }
        $buttons = "";
        $installed_version = getYourVersion($package['user'], $package['repo']);

        $installed_version_short = substr($installed_version, 0, 7);

        $github_info = getGitMaster($package['user'], $package['repo']);

        $last_version = $github_info["master"]["object"]["sha"];
        $last_version_short = substr($last_version, 0, 7);

        $last_datetime = $github_info["commit"]["author"]["date"];
        $last_date = substr($last_datetime, 0, strpos($last_datetime, "T"));
        $last_time = substr($last_datetime, strpos($last_datetime, "T")+1, strpos($last_datetime, "Z")-1);

        if ($last_version == "") {
            $last_version = t("cloudyupdate_getCloudyUpdateTable_na");
        }

        if ($last_date == "") {
            $last_date = t("cloudyupdate_getCloudyUpdateTable_na");
        }

        if ($installed_version == t('cloudyupdate_getYourVersion_none') && $last_version != t("cloudyupdate_getCloudyUpdateTable_na")) {
            $buttons = addButton(array('label'=>t("cloudyupdate_button_install"),'href'=>$staticFile.'/cloudyupdate/update/'.$pname));
        } elseif ($installed_version != $last_version && $last_version != t("cloudyupdate_getCloudyUpdateTable_na")) {
            $buttons = addButton(array('label'=>t("cloudyupdate_button_upgrade"),'href'=>$staticFile.'/cloudyupdate/update/'.$pname));
        } elseif ($installed_version != t('cloudyupdate_getYourVersion_none') && $installed_version == $last_version && $last_version != t("cloudyupdate_getCloudyUpdateTable_na")) {
            $buttons = addButton(array('label'=>t("cloudyupdate_button_nu"), 'class'=>'btn btn-default disabled'));
        } else {
            $buttons = addButton(array('label'=>t("cloudyupdate_button_na"), 'class'=>'btn btn-default disabled'));
        }
        $table .= addTableRow(array($pname, $installed_version_short, $last_version_short, $last_date . " " . $last_time, $buttons));
    }
    $table .= addTableFooter();

    return(array('type'=>'ajax','page'=>$table));
}

function getDebianUpdateTable()
{
    global $staticFile;
    $table = "";

    $cmd = "apt-get -s dist-upgrade | awk '/^Inst/'";
    $cmdresult = shell_exec($cmd);

    if ($cmdresult == "") {
        $table .= txt(t("cloudyupdate_getDebianUpdateTable_status"));
        $table .= "<div class='alert alert-success text-center'>".t("cloudyupdate_getDebianUpdateTable_no_updates")."</div>";
    } else {
        //$table .= "<div class='alert alert-warning text-center'>".t("cloudyupdate_getDebianUpdateTable_updates")."</div>";

        $results = explode("\n", $cmdresult);
        $table .= addTableHeader(array(t("cloudyupdate_getDebianUpdateTable_package"), t("cloudyupdate_getDebianUpdateTable_version") , t("cloudyupdate_getDebianUpdateTable_new"),  t("cloudyupdate_getDebianUpdateTable_action")));

        foreach ($results as $pname) {
            if (explode(" ", $pname)[1] != "") {
                $action = addButton(array('label'=>t('cloudyupdate_button_upgrade'),'href'=>$staticFile.'/cloudyupdate/debpkgupgradesim/?package='.explode(" ", $pname)[1]));
                $table .= addTableRow(array( explode(" ", $pname)[1], substr(explode(" ", $pname)[2], 1, -1), substr(explode(" ", $pname)[3], 1), $action));
            } else {
                $action = addButton(array('label'=>t('cloudyupdate_button_upgrade_all'),'href'=>$staticFile.'/cloudyupdate/debupgradesim'));
                $table .= addTableRow(array( "", "", "", $action));
            }
        }
    }



    return(array('type'=>'ajax','page'=>$table));
}


function getGitMaster($user, $repo)
{
    $github_master = "https://api.github.com/repos/" . $user . "/" . $repo . "/git/refs/heads/master";
    $github = execute_program_shell("curl -sk $github_master");
    $github_json = json_decode($github["output"], true);

    $github_commit = "https://api.github.com/repos/" . $user . "/" . $repo . "/git/commits/" . $github_json["object"]["sha"];
    $last_commit = execute_program_shell("curl -sk $github_commit");
    $last_commit_json = json_decode($last_commit["output"], true);

    return(["master" => $github_json, "commit" => $last_commit_json]);
}

function debupdate()
{
    global $staticFile;

    $page = "";
    $buttons = "";

    $page .= hlc(t("cloudyupdate_common_title"));
    $page .= hl(t("cloudyupdate_debupdate_subtitle"), 4);

    $page .= txt(t("cloudyupdate_debupdate_result"));

    $cmd = "apt-get update";
    $page .= ptxt(shell_exec($cmd));

    $buttons .= addButton(array('label'=>t("cloudyupdate_button_continue"), 'href'=>$staticFile.'/cloudyupdate?debupdate=true', 'class'=>'btn btn-primary', 'method' => 'post', 'action' => $staticFile, 'name'=>"cucamonga",'value'=>'123aaa'));

    $page .= $buttons;
    return(array('type' => 'render','page' => $page));
}

function debupgrade()
{
    global $staticFile;

    $page = "";
    $buttons = "";
    $nopackage = false;

    $page .= hlc(t("cloudyupdate_common_title"));
    $page .= hl(t("cloudyupdate_debupgrade_subtitle"), 4);

    $page .= txt(t("cloudyupdate_debupgrade_result"))    ;
    $cmd = "apt-get -yy upgrade " . $_GET["package"] .  "2>&1";
    $page .= ptxt(shell_exec($cmd));

    $buttons .= addButton(array('label'=>t('cloudyupdate_button_back'), 'class'=>'btn btn-default', 'href'=>$staticFile.'/cloudyupdate?debupdate=true'));

    $page .= $buttons;
    return(array('type' => 'render','page' => $page));
}

function debupgradesim()
{
    global $staticFile;

    $page = "";
    $buttons = "";
    $nopackage = false;

    $page .= hlc(t("cloudyupdate_common_title"));
    $page .= hl(t("cloudyupdate_debupgrade_subtitle"), 4);

    $page .= par(t("cloudyupdate_debupgrade_simulation"));
    $page .= txt(t("cloudyupdate_debupgrade_simresult"))    ;
    $cmd = "apt-get -s -yy upgrade " . $_GET["package"] .  "2>&1";
    $page .= ptxt(shell_exec($cmd));

    $page .= txt(t("cloudyupdate_debupgrade_question"));
    $buttons .= addButton(array('label'=>t('cloudyupdate_button_no_back'), 'class'=>'btn btn-default', 'href'=>$staticFile.'/cloudyupdate?debupdate=true'));
    $buttons .= addButton(array('label'=>t("cloudyupdate_button_yes_upgrade_all"), 'class'=>'btn btn-success', 'href'=>$staticFile.'/cloudyupdate/debupgrade'));

    $page .= $buttons;
    return(array('type' => 'render','page' => $page));
}

function debpkgupgrade()
{
    global $staticFile;

    $page = "";
    $buttons = "";
    $nopackage = false;

    if ($_GET["package"]=="") {
        $nopackage = true;
    }

    $page .= hlc(t("cloudyupdate_common_title"));
    if ($nopackage) {
        $page .= hl(t("cloudyupdate_debpkgupgrade_subtitle"), 4);
        $page .= txt(t("cloudyupdate_debpkgupgrade_result"))    ;
        $page .= "<div class='alert alert-error text-center'>".t("cloudyupdate_alert_unspecified")."</div>";
        $buttons .= addButton(array('label'=>t('cloudyupdate_button_back'), 'class'=>'btn btn-default', 'href'=>$staticFile.'/cloudyupdate?debupdate=true'));
    } else {
        $page .= hl(t("cloudyupdate_debpkgupgrade_subtitle_pre") . ' <i>' . $_GET["package"] . '</i> ' . t("cloudyupdate_debpkgupgrade_subtitle_post"), 4);
        $page .= txt(t("cloudyupdate_debpkgupgrade_result"))    ;
        $cmd = "apt-get -yy --only-upgrade install " . $_GET["package"] .  " 2>&1";
        $page .= ptxt(shell_exec($cmd));

        $buttons .= addButton(array('label'=>t('cloudyupdate_button_back'), 'class'=>'btn btn-default', 'href'=>$staticFile.'/cloudyupdate?debupdate=true'));
    }

    $page .= $buttons;
    return(array('type' => 'render','page' => $page));
}

function debpkgupgradesim()
{
    global $staticFile;

    $page = "";
    $buttons = "";
    $nopackage = false;

    if ($_GET["package"]=="") {
        $nopackage = true;
    }

    $page .= hlc(t("cloudyupdate_common_title"));
    if ($nopackage) {
        $page .= hl(t("cloudyupdate_debpkgupgrade_subtitle"), 4);
        $page .= txt(t("cloudyupdate_debpkgupgrade_result"))    ;
        $page .= "<div class='alert alert-error text-center'>".t("cloudyupdate_alert_unspecified")."</div>";
        $buttons .= addButton(array('label'=>t('cloudyupdate_button_back'), 'class'=>'btn btn-default', 'href'=>$staticFile.'/cloudyupdate?debupdate=true'));
    } else {
        $page .= hl(t("cloudyupdate_debpkgupgrade_subtitle_pre") . ' <i>' . $_GET["package"] . '</i> ' . t("cloudyupdate_debpkgupgrade_subtitle_post"), 4);
        $page .= par(t("cloudyupdate_debpkgupgrade_simulation_1") . ' <i>' . $_GET["package"] . '</i> ' . t("cloudyupdate_debpkgupgrade_simulation_2"));
        $page .= txt(t("cloudyupdate_debpkgupgrade_simresult"))    ;
        $cmd = "apt-get -s -yy --only-upgrade install " . $_GET["package"] .  " 2>&1";
        $page .= ptxt(shell_exec($cmd));

        $page .= txt(t("cloudyupdate_debpkgupgrade_question_1") . ' <i>' . $_GET["package"] . '</i> ' . t("cloudyupdate_debpkgupgrade_question_2"));
        $buttons .= addButton(array('label'=>t('cloudyupdate_button_no_back'), 'class'=>'btn btn-default', 'href'=>$staticFile.'/cloudyupdate?debupdate=true'));
        $buttons .= addButton(array('label'=>t('cloudyupdate_button_yes_upgrade_1') . ' <i>' . $_GET["package"] . '</i> ' . t("cloudyupdate_button_yes_upgrade_2"), 'class'=>'btn btn-success', 'href'=>$staticFile.'/cloudyupdate/debpkgupgrade/?package='.$_GET["package"]));
    }

    $page .= $buttons;
    return(array('type' => 'render','page' => $page));
}

function update()
{
    global $staticFile, $Parameters;

    $page = "";

    $page .= hl(t("Cloudy Update System"));

    global $list_packages;
    if (isset($Parameters[0]) && $Parameters[0] != "") {
        $packet = $Parameters[0];
        if (isset($list_packages[$packet])) {
            $info_packet = $list_packages[$packet];
            $cmd = "mkdir -p /tmp/dir_tmp; cd /tmp/dir_tmp; curl -k " . $info_packet['script'] . "| sh - ; cd /tmp; rm -rf /tmp/dir_tmp";
            $ret = execute_program($cmd);
            $page .= ptxt(implode("\n", $ret['output']));
        } else {
            $page .= ptxt(t("Error, this package does not exist."));
        }
    } else {
        $page .= ptxt(t("Need parameters."));
    }
    $page .= addButton(array('label'=>t('Back'),'href'=>$staticFile.'/cloudyupdate'));


    return(array('type' => 'render','page' => $page));
}
