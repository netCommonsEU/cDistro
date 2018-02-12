<?php
// controller/ipfs.php

// TODO: https://github.com/Clommunity/cDistro/issues/73

$title="IPFS";
$ipfsbinpath="/usr/local/bin/";
$ipfsbin="ipfs";
$ipfspath="/etc/cloudy/ipfs/";
$ipfsinfo="ipfs.info";
$ipfsconfig="config";

$avahi_type="ipfs";
$avahi_desc="Local_IPFS_instance";
$avahi_port="5001";

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
                $buttons .= addButton(array('label'=>t('Stop node'),'href'=>$staticFile.'/ipfs/stop'));
            } else {
                $page .= "<div class='alert alert-error text-center'>".t("IPFS is not running")."</div>\n";
                $buttons .= addButton(array('label'=>t('Start IPFS daemon'),'href'=>$staticFile.'/ipfs/start'));
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

// Stop the IPFS daemon
function stop()
{
    global $ipfsutils, $avahi_type, $avahi_port, $staticFile, $urlpath;

    // Stop the daemon
    $cmd = $ipfsutils." stop ";
    $cmd_res = execute_program($cmd);

    // Un-announce the IPFS instance to the community cloud via any of the
    // available methods -including IPFS itself, if enabled-
    $unpub_res = avahi_unpublish($avahi_type, $avahi_port);

    $flash_content = txt(t('ipfs_flash_stopping')) . ptxt($cmd_res['output'][0]);
    $flash_content .= txt(t('ipfs_flash_unpublish')) . ptxt($unpub_res);

    setFlash($flash_content);
    return(array('type'=>'redirect','url'=>$staticFile.$urlpath));
}

function isRunning()
{
    // Returns whether ipfs is running or not
    global $ipfspath, $ipfsinfo;
    return(file_exists($ipfspath.$ipfsinfo));
}

// function publish_get()
// {
//     global $ipfspath, $title;
//     global $staticFile;
//
//     $page = hlc(t($title));
//     $page .= hlc(t('Publish IPFS server'), 2);
//     $page .= par(t("Write the port to publish your IPFS service"));
//     $page .= createForm(array('class'=>'form-horizontal'));
//     $page .= addInput('description', t('Describe this server'));
//     $page .= addSubmit(array('label'=>t('Publish')));
//     $page .= addButton(array('label'=>t('Cancel'), 'href'=>$staticFile.'/ipfs'));
//     return(array('type' => 'render', 'page' => $page));
// }
//
// function publish_post()
// {
//     $description = $_POST['description'];
//     $ip = "";
//     $page = "<pre>";
//     $page .= _ipfssource($description);
//     $page .= "</pre>";
//     return(array('type' => 'render','page' => $page));
// }

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

// Start the IPFS daemon
function start()
{
    global $ipfsutils, $staticFile, $urlpath, $avahi_type, $avahi_port, $avahi_desc;
    $ret = execute_program_detached($ipfsutils." startDaemon $avahi_port $avahi_type");
    //$output = ptxt(" ". print_r($ret['output'][0],1));

    // Announce the IPFS instance to the community cloud via any of the
    // available methods -including IPFS itself, if enabled-
    $pub_res = avahi_publish($avahi_type, $avahi_desc, $avahi_port, null);

    setFlash(txt(t('ipfs_flash_publish')) . ptxt($pub_res));
    return(array('type'=>'redirect','url'=>$staticFile.$urlpath));
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
        $aps_cfg['DATABASE'] = trim(str_replace("none", "", $aps_cfg['DATABASE']));
    } else {
        $aps_cfg['DATABASE'] = "ipfs";
    }

    // Save Avahi-PS configuration file
    write_conffile($avahips_config, $aps_cfg, null, null);

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

    // If there are no backends remaining, set it to "none"
    if ((isset($aps_cfg['DATABASE']) && trim($aps_cfg['DATABASE']) === "") || !isset($aps_cfg['DATABASE'])) {
        $aps_cfg['DATABASE'] = "none";
    }

    // Save Avahi-PS configuration file
    write_conffile($avahips_config, $aps_cfg, null, null);

    // Check if IPFS was actually disabled and set a flash message before return
    if (! isEnabled()) {
        setFlash(t('ipfs_flash_publish_disabled'), "success");
    } else {
        setFlash(t('ipfs_flash_publish_not_disabled'), "error");
    }

    return(array('type'=>'redirect','url'=>$staticFile.$urlpath));
}

function ipfs_search()
{
    $ret = execute_program("SEARCH_ONLY=ipfs /usr/sbin/avahi-ps search");
    return($ret['output']);
}

function raw_ipfs_search()
{
    $ret = execute_program("SEARCH_ONLY=ipfs /usr/sbin/avahi-ps search");
    $page = ptxt(print_r($ret, 1));
    return(array('type'=>'render','page'=>$page));
}

function search()
{
    global $staticFile,$staticPath;

    $page = "";

    $page .= ajaxStr('tableIPFSAjax', t("Searching for published services, please wait a moment..."));
    $page .= "<div id='tableIPFS' style='display:none'></div>";
    $page .= "<script>\n";
    $page .= "$('#tableIPFS').load('".$staticFile."/ipfs/ajaxsearch',function(){\n";
    $page .= "	$('#tableIPFSAjax').hide();";
    $page .= "	$('#tableIPFS').css({'display':'block'});";
    $page .= "	$('#tags').tab();\n";
    $page .= "  tservice = $('.table-data').DataTable( ";
    $page .= '		{ "language": { "url": "/lang/"+LANG+".table.json"} }';
    $page .= "	);";
    $page .= "});\n";
    $page .= "</script>\n";
    //$page .=  addButton(array('label'=>t("ipfs_search_quality"), 'class'=>'btn', 'onclick'=>'SQoS("'.$staticFile.'/ipfs/ajaxquality")'));

    return(array('type'=>'render','page'=>$page));
}

function ajaxsearch()
{
    $aServices = ipfs_search();

    $gService = json_decode($aServices[0]);

    $nServices = array();

    if (!empty($gService)) {
        foreach ($gService as $dates_machine) {
            $serv_new['type'] = $dates_machine->s;
            $serv_new['description'] = $dates_machine->d;
            $serv_new['host'] = $dates_machine->m;
            $serv_new['ip'] = $dates_machine->i;
            $serv_new['port'] = $dates_machine->p;
            $serv_new['microcloud'] = $dates_machine->e;
            $serv_new['txt'] = $dates_machine->t;
            $serv_new['node_id']= $dates_machine->node_id;
            $serv_new['action'] = checkAvahi($serv_new['type'], array($serv_new));
            unset($serv_new['txt']);
            $type=$serv_new['type'];
            if (!is_array($nServices[$type])) {
                $nServices[$type]=array();
            }
            $nServices[$type][] = $serv_new;
        }
    }
    ksort($nServices);

    $page = "";
    $page .= "<ul id='tabs' class='nav nav-tabs' data-tabs='tabs'>\n";
    $active = "";
    foreach ($nServices as $k => $v) {
        if ($active == "") {
            $active = $k;
        }
        $page .= "	<li";
        if ($active == $k) {
            $page .= " class='active'";
        }
        $page .= "><a href='#".$k."' data-toggle='tab'>".$k."</a></li>\n";
    }
    $page .= "</ul>\n";
    $page .= "<div id='my-tab-content' class='tab-content'>\n";
    $services = "";
    foreach ($nServices as $k => $v) {
        $services .= "	<div class='tab-pane";
        if ($active == $k) {
            $services .= " active";
        }
        $services .= "' id='".$k."'>";

        $services .= addTableHeader(array(t('%'),t('Description'),t('Host'),t('IP'),t('Port'),t('&mu;cloud'),t('Action')), array('class'=>'table table-striped table-data'));
        foreach ($v as $serv) {
            unset($serv['type']);
            $node_id=$serv['node_id'];
            unset($serv['node_id']);
            $servarray=array(0 => '', 1 => $serv['description'], 2 => $serv['host'], 3 => $serv['ip'], 4 => $serv['port'], 5 => $serv['microcloud'], 6 => $serv['action'] );
            $services .= addTableRow($servarray, array('class'=>"node-".$node_id), array( 0 => array('class'=>'scan')));
        }
        $services .= addTableFooter();
        $services .= " 	</div>";
    }
    if ($services == "") {
        $services .=t("No services.");
    }
    $page .= $services;
    $page .= "</div>";
    return(array('type'=>'ajax','page'=>$page));
}
