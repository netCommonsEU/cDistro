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

	$page=hlc(t("syncthing_title"));
	$page .= hl(t("syncthing_desc"),4);

	if (!isInstalled()) {
		$page .= "<div class='alert alert-error text-center'>".t("syncthing_not_installed")."</div>\n";
		$page .= addButton(array('label'=>t("syncthing_install"),'class'=>'btn btn-success', 'href'=>"$urlpath/download"));
		return(array('type'=>'render','page'=>$page));
	} elseif (!hasConfig()) {
		$page .= "<div class='alert alert-error text-center'>".t("syncthing_not_configured")."</div>\n";
		$page .= addButton(array('label'=>t("syncthing_configure"),'class'=>'btn btn-success', 'href'=>"$urlpath/configure"));
		$page .= addButton(array('label'=>t("syncthing_remove"),'class'=>'btn btn-danger', 'href'=>"$urlpath/remove"));
		return(array('type'=>'render','page'=>$page));
	} elseif (!isRunning()) {
		$page .= "<div class='alert alert-error text-center'>".t("syncthing_not_running")."</div>\n";
		$page .= addButton(array('label'=>t("syncthing_start"),'class'=>'btn btn-success', 'href'=>"$urlpath/start"));
		$page .= addButton(array('label'=>t("syncthing_remove"),'class'=>'btn btn-danger', 'href'=>"$urlpath/remove"));
		return(array('type'=>'render','page'=>$page));
	} else {
		$config = readConfig();
		$page .= "<div class='alert alert-success text-center'>".t("syncthing_running")."</div>\n";
		if (!passwordChanged($config)) {
			$page .= "<div class='alert alert-error text-center'>"
				.t("syncthing_pass_unchanged")
				."<br/>\n"
				.t("syncthing_def_user").": $webui_user"
				."<br/>\n"
				.t("syncthing_def_pass").": $webui_pass"
				."</div>\n";
		}
		$host = explode(':', $_SERVER['HTTP_HOST'])[0];
		$page .= par(t("syncthing_repos_web"));

		$page .= addButton(array('label'=>t('syncthing_web_interface'),'href'=>"https://$host:$webui_port"));
		$page .= addButton(array('label'=>t("syncthing_stop"),'class'=>'btn btn-danger', 'href'=>"$urlpath/stop"));

		return(array('type' => 'render','page' => $page));
	}
}

function connect() {
	global $urlpath;

	$ip = $_GET['ip'];
	$port = $_GET['port'];
	$host = $_GET['host'];
	$node_id = $_GET['node_id'];

	stopprogram(); // Make sure the config file is ours
	$config = readConfig();
	connectTo($config, $ip, $port, $host, $node_id);
	writeConfig($config);
	startprogram(); // Make it load the new config
	setFlash(t("syncthing_connected_node"));

	return(array('type'=>'redirect','url'=>"$urlpath"));
}

function disconnect() {
	global $urlpath;

	$ip = $_GET['ip'];
	$port = $_GET['port'];
	$host = $_GET['host'];
	$node_id = $_GET['node_id'];

	stopprogram(); // Make sure the config file is ours
	$config = readConfig();
	disconnectFrom($config, $ip, $port);
	writeConfig($config);
	startprogram(); // Make it load the new config
	setFlash(t("syncthing_disconnected_node"));

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
	if (isConfigured()) {
		return(array('type'=>'redirect','url'=>"$urlpath/start"));
	}
	return(array('type'=>'redirect','url'=>"$urlpath/configure"));
}

function remove_get() {
	global $binpath, $initpath, $urlpath;
	if (!isInstalled()) {
		setFlash(t("syncthing_remove_not_installed"));
		return(array('type'=>'redirect','url'=>"$urlpath"));
	}
	if (isRunning()) {
		setFlash(t("syncthing_remove_running"));
		return(array('type'=>'redirect','url'=>"$urlpath"));
	}
	while (isInstalled()) {
		execute_program_shell("rm -f $binpath $initpath");
		sleep(1);
	}
	return(array('type'=>'redirect','url'=>"$urlpath"));
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
	if (isRunning()) {
		return;
	}
	execute_program_detached_user("HOME=$repospath; $binpath -no-browser -home=$cfgpath", $user);
	while (!isRunning()) {
		sleep(1);
	}
	$config = readConfig();
	$sc_id = getNodeID($config);
	avahi_publish($avahi_type, $avahi_desc, $sc_port, "node_id=$sc_id");
}

function configure_get() {
	global $user, $title, $cfgpath, $binpath, $urlpath, $webui_port, $webui_user,
		$webui_pass_bc, $sc_port, $nodeidpath, $dirpath;

	if (!isInstalled()) {
		setFlash(t("syncthing_install_failed"));
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
		setFlash(t("syncthing_install_failed"));
		return(array('type'=>'redirect','url'=>"$urlpath"));
	}
	if (!hasConfig()) {
		setFlash(t("syncthing_configure_failed"));
		return(array('type'=>'redirect','url'=>"$urlpath"));
	}
	startprogram();
	return(array('type'=>'redirect','url'=>"$urlpath"));
}

function stop_get() {
	global $urlpath;

	stopprogram();
	return(array('type'=>'redirect','url'=>"$urlpath"));
}
