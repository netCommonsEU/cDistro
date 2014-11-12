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

function index() {
	global $title, $urlpath, $webui_user, $webui_pass, $webui_port;

	$page=hlc(t($title));
	$page .= hl(t("A cloud peer-to-peer file synchronization system"),4);

	if (!isInstalled()) {
		$page .= "<div class='alert alert-error text-center'>".t("$title is not installed")."</div>\n";
		$page .= par(t("Click on the button to install $title."));
		$page .= addButton(array('label'=>t("Install $title"),'class'=>'btn btn-success', 'href'=>"$urlpath/download"));
		return(array('type'=>'render','page'=>$page));
	} elseif (!hasConfig()) {
		$page .= "<div class='alert alert-error text-center'>".t("$title is installed but not yet configured")."</div>\n";
		$page .= addButton(array('label'=>t("Configure $title"),'class'=>'btn btn-success', 'href'=>"$urlpath/configure"));
		return(array('type'=>'render','page'=>$page));
	} elseif (!isRunning()) {
		$page .= "<div class='alert alert-error text-center'>".t("$title is installed but not yet running")."</div>\n";
		$page .= par(t("Click on the button to start $title."));
		$page .= addButton(array('label'=>t("Start $title"),'class'=>'btn btn-success', 'href'=>"$urlpath/start"));
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
		$page .= par(t("If you wish to add new repositories and share them, use the web interface."));

		$page .= addButton(array('label'=>t('Web interface'),'href'=>$scurl));
		$page .= addButton(array('label'=>t("Stop $title"),'class'=>'btn btn-danger', 'href'=>"$urlpath/stop"));

		return(array('type' => 'render','page' => $page));
	}
}

function connect() {
	global $urlpath;

	if (isset($_GET['ip']))
		$ip = $_GET['ip'];
	else
		$ip = "";

	if (isset($_GET['port']))
		$port = $_GET['port'];
	else
		$port = "";

	if (isset($_GET['node_id']))
		$node_id = $_GET['node_id'];
	else
		$node_id = "";

	stopprogram(); // Make sure the config file is ours
	$config = readConfig();
	connectTo($config, $ip, $port, "TODO name", $node_id);
	writeConfig($config);
	startprogram(); // Make it load the new config
	setFlash("Properly connected with $foo");

	return(array('type'=>'redirect','url'=>"$urlpath"));
}

function disconnect() {
	global $urlpath;

	if (isset($_GET['ip']))
		$ip = $_GET['ip'];
	else
		$ip = "";

	if (isset($_GET['port']))
		$port = $_GET['port'];
	else
		$port = "";

	stopprogram(); // Make sure the config file is ours
	$config = readConfig();
	disconnectFrom($config, $ip, $port);
	writeConfig($config);
	startprogram(); // Make it load the new config
	setFlash("Properly disconnected from $foo");

	return(array('type'=>'redirect','url'=>"$urlpath"));
}

function download_get() {
	global $dirpath, $cfgpath, $repospath, $binpath, $urlpath;
	$name = nameForArch(php_uname("m"));
	$url = downloadUrl($name);
	execute_program_shell(
		"mkdir -p $dirpath $cfgpath $repospath && " .
		"cd $dirpath && " .
		"curl -L -s $url -o $name.tar.gz && " .
		"tar -xf $name.tar.gz && " .
		"mv $name/syncthing syncthing && " .
		"rm -rf $name.tar.gz $name && " .
		"chown -R www-data:www-data $dirpath && " .
		"chmod 0755 $binpath");
	return(array('type'=>'redirect','url'=>"$urlpath/configure"));
}

function stopprogram() {
	global $user, $binname, $avahi_type, $sc_port;
	while (isRunning()) {
		exec_user("killall $binname", $user);
		sleep(1);
	}
	avahi_unpublish($avahi_type, $sc_port);
}

function startprogram() {
	global $user, $cfgpath, $repospath, $binpath, $avahi_type, $avahi_desc, $sc_port;
	if (!isRunning()) {
		execute_program_detached_user("HOME=$repospath; $binpath -no-browser -home=$cfgpath", $user);
	}
	$config = readConfig();
	$sc_id = getNodeID($config);
	avahi_publish($avahi_type, $avahi_desc, $sc_port, "node_id=$sc_id");
}

function configure_get() {
	global $user, $title, $cfgpath, $binpath, $urlpath, $webui_port, $webui_user,
		$webui_pass_bc, $sc_port, $nodeidpath, $dirpath;

	if (!isInstalled()) {
		setFlash("$title did not install properly!");
		return(array('type'=>'redirect','url'=>"$urlpath"));
	}
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
	execute_program_shell("chown -R www-data:www-data $dirpath");
	startprogram(); // Make it load the new config
	return(array('type'=>'redirect','url'=>"$urlpath"));
}

function start_get() {
	global $title, $urlpath;

	if (!isInstalled()) {
		setFlash("$title did not install properly!");
		return(array('type'=>'redirect','url'=>"$urlpath"));
	}
	if (!hasConfig()) {
		setFlash("$title was not configured properly!");
		return(array('type'=>'redirect','url'=>"$urlpath/configure"));
	}
	startprogram();
	return(array('type'=>'redirect','url'=>"$urlpath"));
}

function stop_get() {
	global $urlpath;

	stopprogram();
	return(array('type'=>'redirect','url'=>"$urlpath"));
}
