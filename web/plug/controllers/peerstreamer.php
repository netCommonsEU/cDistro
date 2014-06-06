<?php
//peerstreamer

$pspath="/opt/peerstreamer";
$psprogram="streamer-udp-grapes-static";
$title="Peer Streamer";

//VLC
$vlcpath="/usr/bin";
$vlcprogram="cvlc";
$vlcuser="nobody";

// Aquest paquest no existeix encarà i per tant pot donar algun problema.
$pspackages="peer_web_gui";

function index_get(){
	global $paspath,$title;
	global $staticFile;

	$page=hlc(t($title));
	$page .= hl(t("A cloud P2P Media Stream system"),4);
	$page .= par("<a href='http://peerstreamer.org'>".t("PeerStreamer")."</a>". t(" is an open source P2P Media Streaming framework written in C.").' '.t("It includes a streaming engine for the efficient distribution of media streams.") .' '. t("A source application for the creation of channels and a player applications to visualize the streams."));
	$page .= txt(t("PeerStreamer status:"));

	if ( ! isPSInstalled() ) {
		$page .= "<div class='alert alert-error text-center'>".t("PeerStreamer is not install")."</div>\n";
		$page .= par(t("Click on the button to install PeerStreamer and view share videos from users."));
		$buttons .= addButton(array('label'=>t("Install PeerStreamer"),'class'=>'btn btn-success', 'href'=>$staticFile.'/peerstreamer/install'));
		$page .= $buttons;
		return(array('type'=>'render','page'=>$page));
	} else {
		$page .= "<div class='alert alert-success text-center'>".t("PeerStreamer installed")."</div>\n";
		$page .= _listPSProcs();
		$page .= addButton(array('label'=>t('Connect to Peer'),'href'=>$staticFile.'/peerstreamer/connect'));
		$page .= addButton(array('label'=>t('Publish a video stream'),'href'=>$staticFile.'/peerstreamer/publish'));

		return(array('type' => 'render','page' => $page));
	}
} 

function connect_get(){
	global $paspath,$title;
	global $staticFile;

	$page = hlc(t($title));
	$page .= hlc(t('Connect to Peer'),2);
	$page .= par(t("You can join a stream through Peer from network, or you can find channels in avahi menu option."));
	$page .= createForm(array('class'=>'form-horizontal'));
	$page .= t('Peer:');
	$page .= addInput('ip',t('IP Address'));
	$page .= addInput('port',t('Port Address'));
	$page .= t('You:');
	$page .= addInput('myudpport',t('Your UDP Port'));
	$page .= addSubmit(array('label'=>t('Connect')));

	return(array('type' => 'render','page' => $page));
}

function connect_post(){
	//Validar dades
	$ip = $_POST['ip'];
	$port = $_POST['port'];
	$myudpport = $_POST['myudpport'];

	if ( 0 == 0 ){  // validar
		return(array('type' => 'render','page' => _psshell($ip,$port,$myudpport))); 
	}
}

function publish_get(){
	global $paspath,$title;
	global $staticFile;

	$page = hlc(t($title));
	$page .= hlc(t('Publish video stream'),2); 
	$page .= par(t("Please write a stream source"));
	$page .= createForm(array('class'=>'form-horizontal'));
	$page .= addInput('url',t('URL Source'),'',array('class'=>'input-xxlarge'));
	$page .= addInput('port',t('Port Address'));
	$page .= addInput('description',t('Describe this channel'));
	$page .= addSubmit(array('label'=>t('Publish'),'class'=>'btn btn-primary'));
	$page .= addButton(array('label'=>t('Cancel'),'href'=>$staticFile.'/peerstreamer'));

	return(array('type' => 'render','page' => $page));
}

function publish_post(){
	$url = $_POST['url'];
	$port = $_POST['port'];
	$description = $_POST['description'];
	$ip = "";

	$page = "<pre>";
	$page .= _pssource($url,$ip,$port,$description);
	//foreach ($_POST as $k => $v) {
	//	$page .= "$k:$v\n";
	//}
	//$page .= "Datos....description:".$description;
	$page .= "</pre>";

	return(array('type' => 'render','page' => $page));

}

function vlcobject($client,$port,$type){

	$o = "";
	$o .= '<div id="vlc-plugin" >';
	$o .= '<!-- <object classid="clsid:9BE31822-FDAD-461B-AD51-BE1D1C159921" codebase="http://download.videolan.org/pub/videolan/vlc/last/win32/axvlc.cab"></object> -->';
	$o .= '<embed pluginspage="http://www.videolan.org"';
	$o .= 'type="application/x-vlc-plugin"';
	$o .= 'version="VideoLAN.VLCPlugin.2"';
	$o .= 'width="720" volume="50"';
	$o .= 'height="480"';
	$o .= 'name="vlc" id="vlc"';
	$o .= 'autoplay="true" allowfullscreen="true" windowless="true" loop="true" toolbar="false"';
	if ($type == 'udp') {
		$target = "udp://@";
		$end = "";
	}
	if ($type == 'rtsp') {
		$target = "rtsp://";
		$end = "/";
	}	
	$o .= ' target="'.$target.$client.':'.$port.$end.'">';
	$o .= '</embed>';
	$o .= '</div>';

	return($o);
}
function runps(){

	$ippeer = $_GET['ip'];
	$portpeer = $_GET['port'];
	if (isset($_GET['myudpport']))
		$portclient = $_GET['myudpport'];
	else
		$portclient = "4214";

	return(array('type' => 'render','page' => _psshell($ippeer,$portpeer,$portclient)));
}

// Utils
function _psshell($ip,$port,$myport) 
{
	global $pspath,$title,$psprogram,$vlcpath,$vlcprogram,$vlcuser;

	$ipclient = $_SERVER['REMOTE_ADDR'];
	$portclient = $myport;
	$device = getCommunityDev()['output'][0];
	$ipserver = getCommunityIP()['output'][0];
	$page = hlc(t($title));


	$page .= par(t('Start peerstreamer:'));
	//$cmd = $pspath . "/" . $psprogram . " -i " . $ip . " -p " . $port . " -P " . $port . " -F null,dechunkiser=udp,port0=" . $portclient . ",addr=" . $ipclient . " -I ". $device .  " &";
	$cmd = $pspath . "/" . $psprogram . " -i " . $ip . " -p " . $port . " -P " . $port . " -F null,dechunkiser=udp,port0=" . $portclient . ",addr=127.0.0.1 -I ". $device ;
	$page .= ptxt($cmd);

	execute_program_detached($cmd);

	$page .= par(t('Start vlc like rtsp server:'));

	$cmd = $vlcpath."/". $vlcprogram .' udp://@127.0.0.1:' . $portclient.' --sout=#rtp{sdp=rtsp://:' . $port . '/} --sout-keep';
	$page .= ptxt($cmd);

	execute_program_detached_user($cmd,$vlcuser);
	$page .= par(t('Please open your Video Player with <b>'). 'rtsp://' . $ipserver . ":" . $port . '/</b>');
	$page .= vlcobject($ipserver,$port,"rtsp");
	//$page .= par(t('Also you can connect your player to ')."udp://@"+$ipclient+":"+$portclient);

	return($page);
}
function _pssource($url,$ip,$port,$description){

	global $pspath,$psprogram,$title,$vlcpath,$vlcprogram,$vlcuser;

	$portclient = "4214";
	$type = "peerstreamer";
	$vlcipclient = "127.0.0.1";
	$psipclient = "127.0.0.1";
	$page = "";
	$device = getCommunityDev()['output'][0];
	
	if ($description == "") $description = $type;

	// Crear Stream con vlc

	$page .= par(t('Started VLC to get stream to pass PeerStreamer.'));
	$cmd = "/bin/su " . $vlcuser . " -c '" . $vlcpath . "/". $vlcprogram .' "'.$url.'"  --sout "#std{access=udp,mux=ts,dst='. $vlcipclient .':'. $portclient .'} "'."'";
	$temp = $cmd."\n";
	execute_program_detached($cmd);
	$page .= ptxt($temp);

	// Activar ps
	$page .= par(t('Started PeerStreamer instance, and send stream to client.'));
	$cmd = $pspath . "/" . $psprogram . " -f null,chunkiser=udp,port0=" . $portclient . ",addr=" . $psipclient .  " -P " . $port . " -I " . $device ."";
	$temp = $cmd."\n";
	execute_program_detached_user($cmd,$vlcuser);
	$page .= ptxt($temp);

	// Publish in avahi system.
	$page .= par(t('Published this stream.'));
	$description = str_replace(' ', '', $description);
	$temp = avahi_publish($type, $description, $port, "");
	$page .= ptxt($temp);

	return($page);
}

function _listPSProcs(){
	// Fer un llistat del PS actius!

} 

function install_get(){

}

function install_post(){

}

function isPSInstalled(){
	return(true);
}