<?php
// Stream m3u8 whit cdn and p2p over javascript+flash
//controllers/cdn-p2p.php

$real_cdnp2p_path=realpath(__DIR__ . "/../resources/cdn_p2p");
$cdn_p2p_home="/opt/cdn-p2p/";
define("cdn_p2p_home", "/opt/cdn-p2p");
define("cdn_p2p_listfile", "liststreams.txt");
$title="Bem TV (CDN+P2P Stream)";
$cp_avahi_type="bemtv";
$cp_avahi_desc="BemTV is running";
$cp_port="80";

function _installed_(){
	global $execpath;
	return(is_file($execpath));
}

function configureSource_get(){
	global $paspath,$title;
	global $staticFile;

	if (isset($_GET['url']))
		$paramurl = $_GET['url'];
	else
		$paramurl = "";

	$page = hlc(t($title));
	$page .= hlc(t('Connect to Stream'),2);
	$page .= par(t("You can create web page with this Stream."));
	$page .= createForm(array('class'=>'form-horizontal'));
	$page .= addInput('title',t('Stream Title'));
	$page .= addInput('source',t('CDN - Stream address'),$paramurl,array('class'=>'input-xxlarge'));
	$page .= addInput('tracker',t('P2P - Tracker Address'));


	$page .= addSubmit(array('label'=>t('Create'),'class'=>'btn btn-primary'));
	$page .= addButton(array('label'=>t('Cancel'),'href'=>$staticFile.'/cdn-p2p'));

	return(array('type' => 'render','page' => $page));
}
function configureSource_post(){
	global $cp_avahi_type, $cp_avahi_desc, $cp_port;
 //avahi adv...
 $source = $_POST['source'];
 $tracker = $_POST['tracker'];
 $title = $_POST['title'];
 $url = "";

 if ($source != "") {
	 $url .= "source=".urlencode($source);
 }
 if (($tracker != "") && ($url != "")) {
	 $url .= "&tracker=".urlencode($tracker);
 }
 if (($title != "") && ($url != "")) {
	 $url .= "&title=".urlencode($title);
 }
 avahi_unpublish($cp_avahi_type, $cp_port);
 $url=urlencode($url);
 avahi_publish($cp_avahi_type, $cp_avahi_desc, $cp_port, $url);
}
function index(){
// Llistat de
/*
$handle = fopen(cdn_p2p_home+"/"+cdn_p2p_listfile, "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $stream = split($line, ":");
    }

    fclose($handle);
} else {
    // error opening the file.
}
*/

}
function view(){
	global $real_cdnp2p_path;

	$mypage=file_get_contents($real_cdnp2p_path."/index.html");
	print($mypage);
	return(array('type'=>'ajax','page'=>$mypage));
}

 ?>
