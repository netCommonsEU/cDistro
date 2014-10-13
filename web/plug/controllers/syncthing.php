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
$version="0.10.1";

function nameForArch($arch) {
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

function downloadUrl($name) {
	global $releases_url,$version;
	return("$releases_url/v$version/$name.tar.gz");
}

function isInstalled() {
	global $binpath;
	return(is_executable($binpath));
}

function getPid() {
	global $binpath;
	$pid_str = execute_program_shell("pidof $binpath | tr -s ' ' '\\n' | sort -n | sed 1q")['output'];
	if ($pid_str == NULL or $pid_str == "") {
		return -1;
	}
	return (int)$pid_str;
}

function index() {
	global $title, $urlpath;

	$page=hlc(t($title));
	$page .= hl(t("A cloud peer-to-peer file synchronization system"),4);

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
		$page .= addButton(array('label'=>t('Create a repository'),'href'=>"$urlpath/newrepo"));
		return(array('type' => 'render','page' => $page));
	}
} 

function getprogram() {
	global $dirpath, $cfgpath, $repospath, $binpath, $urlpath;
	$name = nameForArch(php_uname("m"));
	$url = downloadUrl($name);
	$array = execute_program_shell(
		"mkdir -p $dirpath $cfgpath $repospath && " .
		"cd $dirpath && " .
		"curl -L -s $url -o $name.tar.gz && " .
		"tar -xf $name.tar.gz && " .
		"mv $name/syncthing syncthing && " .
		"rm -rf $name.tar.gz $name && " .
		"chown -R www-data $dirpath && " .
		"chmod 0755 $binpath");
	return(array('type'=>'redirect','url'=>"$urlpath/cfgprogram"));
}

function stopprogram() {
	global $user, $binpath;
	while (getPid() != -1) {
		exec_user("killall $binpath", $user);
		sleep(1);
	}
}

function startprogram() {
	global $user, $cfgpath, $repospath, $binpath;
	if (getPid() == -1) {
		execute_program_detached_user("HOME=$repospath $binpath -no-browser -home=$cfgpath", $user);
	}
}

function cfgprogram() {
	global $user, $title, $cfgpath, $cfgpath_xml, $repospath, $binpath, $urlpath;

	if (!isInstalled()) {
		setFlash("$title did not install properly!");
		return(array('type'=>'redirect','url'=>"$urlpath"));
	} else {
		execute_program_shell("/bin/su $user -c '$binpath -generate=$cfgpath'");
		startprogram(); // Start it to generate the default config
		while (!file_exists($cfgpath_xml)) sleep(1);
		stopprogram(); // Make sure the config file is ours
		$config = simplexml_load_file($cfgpath_xml);
		unset($config->repository);
		$config->gui->attributes()->enabled="true";
		$config->gui->attributes()->tls="true";
		$config->gui->address="0.0.0.0:8080";
		$config->gui->user="syncthing";
		$config->gui->password='$2a$10$COoGrWYTpPxwGWqUPlOv7eEpw5EzbxhGZpsXIsCXZRjE0cn4sr7D6'; // bcrypt for "syncthing"
		$config->options->globalAnnounceEnabled="false";
		$configstr = $config->asXml($cfgpath_xml);
		startprogram(); // Make it load the new config
		return(array('type'=>'redirect','url'=>"$urlpath"));
	}
} 
