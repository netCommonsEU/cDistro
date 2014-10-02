<?php
$dirpath="/opt/syncthing";
$binpath="$dirpath/syncthing";
$cfgpath="$dirpath/config";
$cfgpath_xml = "$cfgpath/config.xml";
$repospath="$dirpath/repos";

$user="www-data";
$title="Syncthing";

$avahi_type="syncthing";
$urlpath="$staticFile/syncthing";

$releases_url="https://github.com/syncthing/syncthing/releases/download";
$version="0.9.19";

function nameForArch($arch){
	global $version;
	switch ($arch) {
	case "amd64":
	case "x86_64":
	case "ia64":
		$urlarch = "amd64";
		break;
	case "x86":
	case "i386":
	case "i686":
		$urlarch = "386";
		break;
	case "armv6l":
		$urlarch = "armv6";
		break;
	case "armv7l":
		$urlarch = "armv7";
		break;
	}
	return("syncthing-linux-$urlarch-v$version");
}

function downloadUrl($name){
	global $releases_url,$version;
	return("$releases_url/v$version/$name.tar.gz");
}

function isInstalled(){
	global $binpath;
	return(is_executable($binpath));
}

function getPid(){
	global $binpath;
	$pids_str = execute_program_shell("pidof $binpath")['output'];
	if ($pids_str == NULL or $pids_str == "") {
		return -1;
	}
	$pids = explode(" ", $pids_str);
	if (count($pids) > 1) {
		return (int)$pids[0];
	}
	return -1;
}

function index(){
	global $title, $urlpath;

	$page=hlc(t($title));
	$page .= hl(t("A cloud P2P File Sharing system"),4);

	if (!isInstalled()) {
		$page .= "<div class='alert alert-error text-center'>".t("$title is not installed")."</div>\n";
		$page .= par(t("Click on the button to install $title."));
		$page .= addButton(array('label'=>t("Install $title"),'class'=>'btn btn-success', 'href'=>"$urlpath/getprogram"));
		return(array('type'=>'render','page'=>$page));
	} elseif (getPid() == -1) {
		$page .= "<div class='alert alert-error text-center'>".t("$title is not running")."</div>\n";
		$page .= par(t("Click on the button to start $title."));
		$page .= addButton(array('label'=>t("Start $title"),'class'=>'btn btn-success', 'href'=>"$urlpath/cfgprogram"));
		return(array('type'=>'render','page'=>$page));
	} else {
		$page .= "<div class='alert alert-success text-center'>".t("$title installed")."</div>\n";
		$page .= addButton(array('label'=>t('Publish a video stream'),'href'=>"$urlpath/publish"));
		return(array('type' => 'render','page' => $page));
	}
} 

function connect_get(){
	global $title, $urlpath;

	if (isset($_GET['ip']))
		$peerip = $_GET['ip'];
	else 
		$peerip = "";

	if (isset($_GET['port']))
		$peerport = $_GET['port'];
	else
		$peerport = "";

	if (isset($_GET['id']))
		$peerid = $_GET['id'];
	else
		$peerid = "";

	$page = hlc(t($title));
	$page .= hlc(t('Connect to Node'),2);
	$page .= par(t("You can connect to a Node to share repositories with it."));
	$page .= createForm(array('class'=>'form-horizontal'));
	$page .= t('Node:');
	$page .= addInput('ip',t('IP Address'),$peerip);
	$page .= addInput('port',t('Port'),$peerport);
	$page .= addInput('id',t('ID'),$peerid);
	$page .= t('You:');
	$page .= addInput('myport',t('Port'));
	$page .= addSubmit(array('label'=>t('Connect'),'class'=>'btn btn-primary'));
	$page .= addButton(array('label'=>t('Cancel'),'href'=>$urlpath));

	return(array('type' => 'render','page' => $page));
}

function connect_post(){
	//Validar dades
	$ip = $_POST['ip'];
	$port = $_POST['port'];
	$port = $_POST['id'];
	$myport = $_POST['myport'];
	$tipo = $_POST['type'];

	return(array('type' => 'render','page' => _scshell($ip,$port,$myport,$tipo))); 
}

function publish_get(){
	global $title,$urlpath;

	$page = hlc(t($title));
	$page .= hlc(t('Publish video stream'),2); 
	$page .= par(t("Please write a stream source"));
	$page .= createForm(array('class'=>'form-horizontal'));
	$page .= addInput('url',t('URL Source'),'',array('class'=>'input-xxlarge'));
	$page .= addInput('port',t('Port Address'));
	$page .= addInput('description',t('Describe this channel'));
	$page .= addSubmit(array('label'=>t('Publish'),'class'=>'btn btn-primary'));
	$page .= addButton(array('label'=>t('Cancel'),'href'=>$urlpath));

	return(array('type' => 'render','page' => $page));
}

function publish_post(){
	$url = $_POST['url'];
	$port = $_POST['port'];
	$description = $_POST['description'];
	$ip = "";

	//$page = "<pre>";
	//$page .= _pssource($url,$ip,$port,$description);
	//foreach ($_POST as $k => $v) {
	//	$page .= "$k:$v\n";
	//}
	//$page .= "Datos....description:".$description;
	//$page .= "</pre>";

	return(array('type' => 'render','page' => $page));

}

function getprogram(){
	global $dirpath, $cfgpath, $repospath, $binpath, $urlpath;
	$name = nameForArch(php_uname("m"));
	$url = downloadUrl($name);
	$array = execute_program_shell("mkdir -p $dirpath $cfgpath $repospath && cd $dirpath && curl -L -s $url -o $name.tar.gz && tar -xf $name.tar.gz && mv $name/syncthing syncthing && rm -rf $name.tar.gz $name && chown -R www-data $dirpath && chmod 0755 $binpath");
	return(array('type'=>'redirect','url'=>"$urlpath/cfgprogram"));
}

function cfgprogram(){
	global $user, $dirpath, $cfgpath, $cfgpath_xml, $repospath, $binpath, $urlpath;

	if (!isInstalled()) {
		setFlash("$title did not install properly!");
		return(array('type'=>'redirect','url'=>"$urlpath"));
	} else {
		execute_program_shell("/bin/su $user -c '$binpath -generate=$cfgpath'");
		execute_program_detached_user("HOME=$repospath $binpath -no-browser -home=$cfgpath", $user);
		while (!file_exists($cfgpath_xml)) sleep(1);
		$config = simplexml_load_file($cfgpath_xml);
		unset($config->repository);
		$config->gui->attributes()->enabled="true";
		$config->gui->attributes()->tls="true";
		$config->gui->address="0.0.0.0:8080";
		$config->gui->user="syncthing";
		$config->gui->password='$2a$10$COoGrWYTpPxwGWqUPlOv7eEpw5EzbxhGZpsXIsCXZRjE0cn4sr7D6'; // bcrypt for "syncthing"
		$config->options->globalAnnounceEnabled=false;
		$config->asXml($cfgpath_xml);
		return(array('type'=>'redirect','url'=>"$urlpath"));
	}
} 
