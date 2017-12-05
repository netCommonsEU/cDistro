<?php
$configFile="/etc/cloudy/cloudy.conf";
$sslShell=dirname(__FILE__)."/../resources/https/https.sh";
$urlpath=$staticFile."/https";
$sslPort=7443;
$httpPort=7000;

function index()
{
    global $urlpath;

    $page = hlc(t("https_title"));
    $page .= hl(t("https_subtitle"), 4);
    $page .= par(t("https_desc"));

    $page .= txt(t("https_status"));
    if (!isInstalled()) {
        $page .= "<div class='alert alert-error text-center'>".t("https_is_not_installed")."</div>\n";
        $page .= txt(t("https_recommend"));
        $page .= "<div class='alert alert-warning text-center'>".t("https_recommendation")."</div>\n";
        $page .= par(t("https_enable"));
        $page .= addButton(array('label'=>t("https_install"),'class'=>'btn btn-success', 'href'=>"$urlpath/install"));
    } else {
        $page .= "<div class='alert alert-success text-center'>".t("https_is_installed")."</div>\n";
        $page .= par(t("https_disable"));
        $page .= addButton(array('label'=>t("https_remove"),'class'=>'btn btn-danger', 'href'=>"$urlpath/uninstall"));
    }

    return array('type' => 'render','page' => $page);
}

function isInstalled()
{
    global $configFile;

    if (isset(parse_ini_file($configFile)['PORT_SSL'])) {
        return true;
    }

    return false;
}

function install()
{
    global $sslShell, $urlpath, $sslPort, $appHost;

    if (isInstalled()) {
        setFlash(t('https_already_enabled'), "error");
        return(array('type'=> 'redirect', 'url' => $urlpath));
    }

    $appAddress = explode(":", $appHost)[0];

    execute_program_detached($sslShell." install");
    return(array('type'=> 'redirect', 'url' => "https://".$appAddress.":".$sslPort));
}

function uninstall()
{
    global $sslShell, $urlpath, $httpPort, $appHost;

    if (!isInstalled()) {
        setFlash(t('https_not_enabled_yet'), "error");
        return(array('type'=> 'redirect', 'url' => $urlpath));
    }

    $appAddress = explode(":", $appHost)[0];

    execute_program_detached($sslShell." remove");
    return(array('type'=> 'redirect', 'url' => "http://".$appAddress.":".$httpPort));
}
