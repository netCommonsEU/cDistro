<?php
$title="IPFS";
$ipfsbinpath="/usr/local/bin/";
$ipfsbin="ipfs";
$ipfspath="/etc/cloudy/ipfs/";
$ipfsinfo="ipfs.info";
$ipfsconfig="config";
$port="5001";
$avahi_type="ipfs";
$ipfsutils=dirname(__FILE__)."/../resources/ipfs/ipfscontroller";

function index()
{
    global $paspath,$title;
    global $staticFile;

    $page = hlc(t($title));
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
                $page .= addButton(array('label'=>t('Stop node'),'href'=>$staticFile.'/ipfs/stop'));
            } else {
                $page .= "<div class='alert alert-error text-center'>".t("IPFS is not running")."</div>\n";
                $page .= addButton(array('label'=>t('Start IPFS daemon'),'href'=>$staticFile.'/ipfs/startDaemon'));
            }
        }
    }

    return(array('type' => 'render','page' => $page));
}

function stop()
{
    // Stops ipfs server
    global $ipfspath,$ipfsprogram,$title,$ipfsutils,$avahi_type,$port;
    $page = "";
    $cmd = $ipfsutils." stop ";
    execute_program_detached($cmd);
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
    global $ipfsutils,$staticFile;
    $ret = execute_program($ipfsutils." startDaemon");
    $output = ptxt(implode("\n", $ret['output']));
    setFlash($output);
    return(array('type'=>'redirect','url'=>$staticFile.'/ipfs'));
}
