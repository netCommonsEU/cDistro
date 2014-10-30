<?php

require realpath(__DIR__ . "/../resources/syncthing/common.php");

$urlpath="$staticFile/syncthing";

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
	global $title, $urlpath, $webui_user, $webui_pass, $webui_port;

	$page=hlc(t($title));
	$page .= hl(t("A cloud peer-to-peer file synchronization system"),4);

	if (!isInstalled()) {
		$page .= "<div class='alert alert-error text-center'>".t("$title is not installed")."</div>\n";
		$page .= par(t("Click on the button to install $title."));
		$page .= addButton(array('label'=>t("Install $title"),'class'=>'btn btn-success', 'href'=>"$urlpath/getprogram"));
		return(array('type'=>'render','page'=>$page));
	} elseif (!hasConfig() || getPid() == -1) {
		$page .= "<div class='alert alert-error text-center'>".t("$title is installed but not yet running")."</div>\n";
		$page .= par(t("Click on the button to start $title."));
		$page .= addButton(array('label'=>t("Start $title"),'class'=>'btn btn-success', 'href'=>"$urlpath/cfgprogram"));
		return(array('type'=>'render','page'=>$page));
	} else {
		$config = readConfig();
		$page .= "<div class='alert alert-success text-center'>".t("$title is installed and running")."</div>\n";
		if (!passwordChanged($config)) {
			$page .= "<div class='alert alert-error text-center'>"
				.t("$title's public web interface password hasn't been changed yet, please change it.")
				."\n"
				.t("Default user: $webui_user. Default password: $webui_pass")
				."</div>\n";
		}
		$host = explode(':', $_SERVER['HTTP_HOST'])[0];
		$scurl = "https://$host:$webui_port";

		$page .= addButton(array('label'=>t('Go to the web interface'),'href'=>$scurl));

		//$page .= addButton(array('label'=>t('Create a repository'),'href'=>"$urlpath/newrepo"));
		return(array('type' => 'render','page' => $page));
	}
}

function connect() {
	stopprogram(); // Make sure the config file is ours
	$config = readConfig();
	unset($config->folder);
	writeConfig($config);
	startprogram(); // Make it load the new config
	setFlash("Properly connected with $foo");
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
	global $user, $binname;
	while (getPid() != -1) {
		exec_user("killall $binname", $user);
		sleep(1);
	}
}

function startprogram() {
	global $user, $cfgpath, $repospath, $binpath, $avahi_type, $avahi_desc, $sc_port;
	if (getPid() == -1) {
		execute_program_detached_user("HOME=$repospath $binpath -no-browser -home=$cfgpath", $user);
	}
	$config = readConfig();
	$sc_id = getNodeID($config);
	avahi_publish($avahi_type, $avahi_desc, $sc_port, "node_id=$sc_id");
}

function cfgprogram() {
	global $user, $title, $cfgpath, $cfgpath_xml, $repospath, $binpath, $urlpath,
		$webui_port, $webui_user, $webui_pass_bc, $sc_port, $nodeidpath;

	if (!isInstalled()) {
		setFlash("$title did not install properly!");
		return(array('type'=>'redirect','url'=>"$urlpath"));
	} else {
		execute_program_shell("/bin/su $user -c '$binpath -generate=$cfgpath'");
		startprogram(); // Start it to generate the default config
		while (!hasConfig()) sleep(1);
		stopprogram(); // Make sure the config file is ours
		$config = readConfig();
		unset($config->folder);
		$config->gui->attributes()->enabled="true";
		$config->gui->attributes()->tls="true";
		$config->gui->address="0.0.0.0:$webui_port";
		$config->gui->user=$webui_user;
		$config->gui->password=$webui_pass_bc;
		$config->options->listenAddress="0.0.0.0:$sc_port";
		$config->options->globalAnnounceEnabled="false";
		writeConfig($config);
		file_put_contents($nodeidpath, getNodeID($config));
		startprogram(); // Make it load the new config
		return(array('type'=>'redirect','url'=>"$urlpath"));
	}
}
