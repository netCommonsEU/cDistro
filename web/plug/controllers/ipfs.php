<?php
// controller/ipfs.php

// TODO: https://github.com/Clommunity/cDistro/issues/73

$title="IPFS";
$ipfsbinpath="/usr/local/bin/";
$ipfsbin="ipfs";
$ipfspath="/etc/cloudy/ipfs/";
$ipfsinfo="ipfs.info";
$ipfsconfig="config";
$port="5001";
$avahi_type="ipfs";
$ipfsutils=dirname(__FILE__)."/../resources/ipfs/ipfscontroller";

$urlpath='/ipfs';

$avahips_config="/etc/avahi-ps.conf";


function index()
{
    global $staticFile, $title, $urlpath;

    $page = "";
    $buttons = "";

    $page .= hlc(t($title));
    $page .= hl(t("A Distributed Services Announcement and Discovery (DADS) tool"), 4);

    $page .= par(t('<a href="https://ipfs.io">IPFS</a> is  peer-to-peer hypermedia protocol to make the web faster, safer, and more open.'));

    $page .= par(t("The Distributed Announcement and Discovery of Services (DADS) for Community Networks included in Cloudy uses IPFS to exchange information between nodes. If services publication is enabled, local services will be announced to the network and other users will be available to see and use them."));

    if (! isIPFSInstalled()) {
        $page .= "<div class='alert alert-error text-center'>".t("IPFS is not installed")."</div>\n";
        $page .= par(t("Click on the button below to install IPFS or browse the available Cloudy packages at System => Updates."));
        $buttons .= addButton(array('label'=>t("Install IPFS"),'class'=>'btn btn-success', 'href'=>$staticFile.'/cloudyupdate/update/ipfs'));
        $page .= $buttons;
    } else {
        $page .= "<div class='alert alert-success text-center'>".t("IPFS is installed")."</div>\n";

        if (! isIPFSInitialized()) {
            $page .= "<div class='alert alert-error text-center'>".t("IPFS is not initialized")."</div>\n";
            $page .= addButton(array('label'=>t('Initialize IPFS'),'href'=>$staticFile.'/ipfs/initialize'));
        } else {
            if (isRunning()) {
                $page .= "<div class='alert alert-success text-center'>".t("IPFS is running")."</div>\n";
                // See https://github.com/Clommunity/cDistro/issues/52
                //$page .= addButton(array('label'=>t('Go to node'),'href'=>'http://'. getCommunityIP()['output'][0] .':'. $port));
                $buttons .= addButton(array('label'=>t('Stop node'),'href'=>$staticFile.'/ipfs/stop'));
            } else {
                $page .= "<div class='alert alert-error text-center'>".t("IPFS is not running")."</div>\n";
                $buttons .= addButton(array('label'=>t('Start IPFS daemon'),'href'=>$staticFile.'/ipfs/startDaemon'));
            }

            if (isEnabled()) {
                $buttons .= addButton(array('label'=>t("ipfs_button_publish_disable"),'class'=>'btn btn-warning', 'href'=>"$staticFile"."$urlpath"."/disable", 'divOptions'=>array('class'=>'btn-group')));
            } else {
                $buttons .= addButton(array('label'=>t("ipfs_button_publish_enable"),'class'=>'btn btn-info', 'href'=>"$staticFile"."$urlpath"."/enable", 'divOptions'=>array('class'=>'btn-group')));
            }
        }
    }

    $page .= $buttons;

    return(array('type' => 'render','page' => $page));
}

function stop()
{
    // Stops ipfs server
    global $ipfspath,$ipfsprogram,$title,$ipfsutils,$avahi_type,$port, $staticFile;
    $page = "";
    $cmd = $ipfsutils." stop ";
    execute_program($cmd);
    $temp = avahi_unpublish($avahi_type, $port);
    $flash = ptxt($temp);
    setFlash($flash);
    return(array('type'=>'redirect','url'=>$staticFile.'/ipfs'));
}

function isRunning()
{
    // Returns whether ipfs is running or not
    global $ipfspath, $ipfsinfo;
    return(file_exists($ipfspath.$ipfsinfo));
}

function publish_get()
{
    global $ipfspath, $title;
    global $staticFile;

    $page = hlc(t($title));
    $page .= hlc(t('Publish IPFS server'), 2);
    $page .= par(t("Write the port to publish your IPFS service"));
    $page .= createForm(array('class'=>'form-horizontal'));
    $page .= addInput('description', t('Describe this server'));
    $page .= addSubmit(array('label'=>t('Publish')));
    $page .= addButton(array('label'=>t('Cancel'), 'href'=>$staticFile.'/ipfs'));
    return(array('type' => 'render', 'page' => $page));
}

function publish_post()
{
    $description = $_POST['description'];
    $ip = "";
    $page = "<pre>";
    $page .= _ipfssource($description);
    $page .= "</pre>";
    return(array('type' => 'render','page' => $page));
}

function isIPFSInstalled()
{
    global $ipfsbinpath, $ipfsbin;

    return(file_exists($ipfsbinpath) && is_dir($ipfsbinpath) &&
        file_exists($ipfsbinpath.$ipfsbin) && is_executable($ipfsbinpath.$ipfsbin));
}

function isIPFSInitialized()
{
    global $ipfsbinpath, $ipfsbin, $ipfspath, $ipfsconfig;
    ;

    return(file_exists($ipfsbinpath) && is_dir($ipfsbinpath) &&
        file_exists($ipfsbinpath.$ipfsbin) && is_executable($ipfsbinpath.$ipfsbin) &&
        file_exists($ipfspath) && is_dir($ipfspath) &&
        file_exists($ipfspath.$ipfsconfig) && is_file($ipfspath.$ipfsconfig));
}

function install()
{
    global $ipfsutils,$staticFile;
    $ret = execute_program($ipfsutils." install");
    $output = ptxt(implode("\n", $ret['output']));
    setFlash($output);
    return(array('type'=>'redirect','url'=>$staticFile.'/ipfs'));
}

function initialize()
{
    global $ipfsutils,$staticFile;
    $ret = execute_program($ipfsutils." initialize");
    $output = ptxt(implode("\n", $ret['output']));
    setFlash($output);
    return(array('type'=>'redirect','url'=>$staticFile.'/ipfs'));
}

function startDaemon()
{
    global $ipfsutils,$staticFile, $avahi_type, $port;
    $ret = execute_program_detached($ipfsutils." startDaemon $port $avahi_type");
    //$output = ptxt(" ". print_r($ret['output'][0],1));
    $temp = avahi_publish($avahi_type, "IPFS", $port, "");
    $output = ptxt("Publishing service: " . $temp);
    setFlash($output);
    return(array('type'=>'redirect','url'=>$staticFile.'/ipfs'));
}

/**
 * function isEnabled(): check if IPFS is enabled as a publication mechanism
 *
 * This function checks if IPFS is enabled as a mechanism to publish local
 * services to the community cloud.
 *
 * @param none
 *
 * @return bool
 */
function isEnabled()
{
    global $avahips_config;

    // Load Avahi-PS configuration file
    $aps_cfg = load_conffile($avahips_config);

    // Check for IPFS as a backend database for publication
    if (isset($aps_cfg['DATABASE']) && strpos($aps_cfg['DATABASE'], 'ipfs') !== false) {
        return true;
    }
    return false;
}

/**
 * function enable(): enable IPFS as a publication mechanism
 *
 * This function enables IPFS as a mechanism to publish local servers to the
 * community cloud.
 *
 * @param none
 *
 * @return array
 */
function enable()
{
    global $avahips_config, $urlpath, $staticFile;

    // Load Avahi-PS configuration file
    $aps_cfg = load_conffile($avahips_config);

    // Add IPFS as a backend database for publication
    if (isset($aps_cfg['DATABASE'])) {
        if (strpos($aps_cfg['DATABASE'], 'ipfs') === false) {
            $aps_cfg['DATABASE'] = trim($aps_cfg['DATABASE']." ipfs");
        }
    } else {
        $aps_cfg['DATABASE'] = "ipfs";
    }

    // Save Avahi-PS configuration file
    write_conffile($avahips_config, $aps_cfg, "", "", '"');

    // Check if IPFS was actually enabled and set a flash message before return
    if (isEnabled()) {
        setFlash(t('ipfs_flash_publish_enabled'), "success");
    } else {
        setFlash(t('ipfs_flash_publish_not_enabled'), "error");
    }

    return(array('type'=>'redirect','url'=>$staticFile.$urlpath));
}

/**
 * function disable(): disable IPFS as a publication mechanism
 *
 * This function disables IPFS as a mechanism to publish local servers to the
 * community cloud.
 *
 * @param none
 *
 * @return array
 */
function disable()
{
    global $avahips_config, $urlpath, $staticFile;

    // Load Avahi-PS configuration file
    $aps_cfg = load_conffile($avahips_config);

    // Remove IPFS as a backend database for publication
    if (isset($aps_cfg['DATABASE'])) {
        $aps_cfg['DATABASE'] = trim(str_replace("ipfs", "", $aps_cfg['DATABASE']));
    }

    // Save Avahi-PS configuration file
    write_conffile($avahips_config, $aps_cfg, "", "", '"');

    // Check if IPFS was actually disabled and set a flash message before return
    if (! isEnabled()) {
        setFlash(t('ipfs_flash_publish_disabled'), "success");
    } else {
        setFlash(t('ipfs_flash_publish_not_disabled'), "error");
    }

    return(array('type'=>'redirect','url'=>$staticFile.$urlpath));
}
