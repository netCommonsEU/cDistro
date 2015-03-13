<?php
//caracal-api.php

$config_file="/etc/caracaldb-api/application.conf";
$web_config_file="/srv/www/caracalui/config.yaml";
$apache_config_file="/etc/apache2/sites-available/default";
$log_file="/var/log/caracaldb-api.log";
$service_script="/etc/init.d/caracaldb-api";
$service_name="caracaldb-api";
$binary_url="http://cloud7.sics.se/caracal/caracaldb-api.deb";
$binary_deb="caracaldb-api.deb";
$urlpath='/caracal-api';

function index_get(){

	global $config_file, $apache_config_file;
	global $urlpath, $appHost;

	$appAddress = explode(":",$appHost)[0];


	$page = "";
	$buttons = "";
	$stati = "";
	$config_file_content = "";

	$page .= hlc(t("caracalapi_title"));
	$page .= hl(t("caracalapi_subtitle"),4);
    $page .= par(t("caracalapi_description"));

    $stati .= hl(t('caracalapi_status'), 4);

    $hasJava = _check_java();

    if ($hasJava) {
    	$stati .= "<div class='alert alert-success text-center'>".t('caracalapi_java_installed_true')." (".$hasJava.")</div>\n";
    } else {
    	$stati .= "<div class='alert alert-fail text-center'>".t('caracalapi_java_installed_false')."</div>\n";
	}

    $hasCaracal = _check_caracal();

    if ($hasCaracal) {
    	$stati .= "<div class='alert alert-success text-center'>".t('caracalapi_caracalapi_installed_true')."</div>\n";

    	$config_file_content .= hl(t("caracalapi_config_file"), 4);
    	$config_file_content .= par(t("caracalapi_config_path")." $config_file: ");
    	$config_file_content .= ptxt(file_get_contents($config_file));

    	$buttons .= addButton(array('label'=>t("caracalapi_button_log"),'class'=>'btn btn-info', 'href'=>"$urlpath/logfile", 'divOptions'=>array('class'=>'btn-group')));

     	$isRunning = _check_running();
     	if ($isRunning) {
			$stati .= "<div class='alert alert-success text-center'>".t('caracalapi_running_true')."</div>\n";
    		$buttons .= addButton(array('label'=>t("caracalapi_button_stop"),'class'=>'btn btn-danger', 'href'=>"$urlpath/stop", 'divOptions'=>array('class'=>'btn-group')));
    		$buttons .= addButton(array('label'=>t("caracalapi_button_ui"),'class'=>'btn btn-success', 'href'=>"http://".$appAddress."/caracalui", 'divOptions'=>array('class'=>'btn-group')));

    	} else {
    		$buttons .= addButton(array('label'=>t("caracalapi_button_uninstall"),'class'=>'btn btn-danger', 'href'=>"$urlpath/uninstall", 'divOptions'=>array('class'=>'btn-group')));

    		$stati .= "<div class='alert alert-fail text-center'>".t('caracalapi_running_false')."</div>\n";
    		$buttons .= addButton(array('label'=>t("caracalapi_button_start"),'class'=>'btn btn-success', 'href'=>"$urlpath/start", 'divOptions'=>array('class'=>'btn-group')));

    		$buttons .= addButton(array('label'=>t("caracalapi_button_config"),'class'=>'btn btn-info', 'href'=>"$urlpath/config", 'divOptions'=>array('class'=>'btn-group')));


		}
    } else {
    	$stati .= "<div class='alert alert-fail text-center'>".t('caracalapi_caracalapi_installed_false')."</div>\n";
    	$buttons .= addButton(array('label'=>t("caracalapi_button_install"),'class'=>'btn btn-success', 'href'=>"$urlpath/install", 'divOptions'=>array('class'=>'btn-group')));
	}

    $page .= $stati . $config_file_content . $buttons;
	return(array('type' => 'render','page' => $page));
}

function config_post(){

	global $staticFile, $urlpath;
	global $config_file, $web_config_file;

	$config_values = array();
	foreach ($_POST as $key => $value) {
		if (substr_compare($key, "CARACALAPI_", 0, 8, true) == 0) {
			$config_values[$key] = $value;
		}
	}

	file_put_contents($config_file, 'caracal.api.workers = 3'.PHP_EOL); // reset file
	file_put_contents($config_file, 'caracal.api.cors ="*"'.PHP_EOL, FILE_APPEND | LOCK_EX);
	file_put_contents($config_file, 'datamodel.workers = 3'.PHP_EOL, FILE_APPEND | LOCK_EX);

	file_put_contents($config_file, 'caracal.api.host.hostname="'.$config_values['CARACALAPI_WEB_ADDR'].'"'.PHP_EOL, FILE_APPEND | LOCK_EX);
	file_put_contents($config_file, 'caracal.api.host.port='.$config_values['CARACALAPI_WEB_PORT'].PHP_EOL, FILE_APPEND | LOCK_EX);

	file_put_contents($config_file, 'client.address.hostname="'.$config_values['CARACALAPI_LOCAL_IP'].'"'.PHP_EOL, FILE_APPEND | LOCK_EX);
	file_put_contents($config_file, 'client.address.port='.$config_values['CARACALAPI_LOCAL_PORT'].PHP_EOL, FILE_APPEND | LOCK_EX);
	file_put_contents($config_file, 'bootstrap.address.hostname="'.$config_values['CARACALAPI_BOOTSTRAP_IP'].'"'.PHP_EOL, FILE_APPEND | LOCK_EX);
	file_put_contents($config_file, 'bootstrap.address.port='.$config_values['CARACALAPI_BOOTSTRAP_PORT'].PHP_EOL, FILE_APPEND | LOCK_EX);

	// Also update the WebUI config
	file_put_contents($web_config_file, "apiUrl: http://".$config_values['CARACALAPI_WEB_ADDR'].":".$config_values['CARACALAPI_WEB_PORT']."/"); // reset file

	setFlash(t('caracalapi_saved_config'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function config() {
	global $config_file, $apache_config_file;
	global $urlpath, $appHost, $staticFile;
    //$disabled = '';

	$appAddress = explode(":",$appHost)[0];


	$page = "";
	$buttons = "";
	$submitButtons = "";
	$stati = "";
	$config_file_content = "";
	$form_fields = "";

	$page .= hlc(t("caracalapi_title"));

    $hasCaracal = _check_caracal();

    if ($hasCaracal) {
    	$config_file_content .= hl(t("caracalapi_config_file"), 4);
    	$config_file_content .= par(t("caracalapi_config_path")." $config_file: ");
    	$config_file_content .= ptxt(file_get_contents($config_file));

    	$isRunning = _check_running();

     	if ($isRunning) {
     		return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath)); // abort
     	} else {
     		$variable = _get_config_values();

			$form_fields .= hl(t("caracalapi_config_edit"), 4);
    		$form_fields .= createForm(array('class'=>'form-horizontal'));
    		$form_fields .= addInput('CARACALAPI_BOOTSTRAP_IP',t('caracalapi_form_bsip'),$variable,array('type'=>'text', 'pattern'=>'\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}','required'=>''),false,t('caracalapi_form_bsip'));
    		$form_fields .= addInput('CARACALAPI_BOOTSTRAP_PORT',t('caracalapi_form_bsport'),$variable,array('type'=>'number', 'min' => '1024', 'max' => '65535', 'required'=>''),false,t('caracalapi_form_bsport'));
    		$form_fields .= addInput('CARACALAPI_LOCAL_IP',t('caracalapi_form_localip'),$variable,array('type'=>'text', 'pattern'=>'\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}','required'=>''),false,t('caracalapi_form_localip'));
    		$form_fields .= addInput('CARACALAPI_LOCAL_PORT',t('caracalapi_form_localport'),$variable,array('type'=>'number', 'min' => '1024', 'max' => '65535', 'required'=>''),false,t('caracalapi_form_localport'));
    		$form_fields .= addInput('CARACALAPI_WEB_ADDR',t('caracalapi_form_webaddr'),$variable,array('type'=>'text','required'=>''),false,t('caracalapi_form_webaddr'));
    		$form_fields .= addInput('CARACALAPI_WEB_PORT',t('caracalapi_form_webport'),$variable,array('type'=>'number', 'min' => '1024', 'max' => '65535', 'required'=>''),false,t('caracalapi_form_webport'));


    		$submitButtons .= addSubmit(array('label'=>t('caracalapi_button_save')));
		}
    } else {
    	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath)); // abort
    }

    $buttons .= addButton(array('label'=>t("caracalapi_button_cancel"),'class'=>'btn btn-danger', 'href'=>"$urlpath", 'divOptions'=>array('class'=>'btn-group')));


    $page .= $config_file_content . $form_fields . $buttons . $submitButtons;
	return(array('type' => 'render','page' => $page));
}

function start() {
	require_once('Config.php');
	global $urlpath, $staticFile, $service_name, $apache_config_file, $log_file;

	// Try to clean up old log file
	execute_shell("rm $log_file");

	execute_program_detached("service $service_name start");

	// Also install WebUi into Apache
	$conf = new Config();
	$root = $conf->parseConfig($apache_config_file, 'apache');
	if (PEAR::isError($root)) {
    	setFlash(t("caracalapi_start_fail")." (apache2 config)");
		return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath)); // abort
	}
	$vhost = $root->getItem('section', 'VirtualHost', null, null, 0);
	$caracalalias = $vhost->createDirective('Alias', "/caracalui /srv/www/caracalui");
	$caracaldir = $vhost->createSection('Directory', array('"/srv/www/caracalui"'));
	$caracaldir->createDirective('Options', "-Indexes FollowSymlinks");
	$caracaldir->createDirective('AllowOverride', "None");
	$caracaldir->createDirective('DirectoryIndex', "caracalui.html");

	$conf->writeConfig($apache_config_file, 'apache');

	execute_program_detached("service apache2 restart");

	setFlash(t("caracalapi_start_success"), "success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function stop() {
	require_once('Config.php');
	global $urlpath, $staticFile, $service_name, $apache_config_file;

	// Also remove WebUI from Apache
	$conf = new Config();
	$root = $conf->parseConfig($apache_config_file, 'apache');
	if (PEAR::isError($root)) {
    	setFlash(t("caracalapi_stop_fail")." (apache2 config)");
		return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath)); // abort
	}
	$vhost = $root->getItem('section', 'VirtualHost', null, null, 0);
	$caracalalias = $vhost->getItem('directive', 'Alias', "/caracalui /srv/www/caracalui", null);
	$caracalalias->removeItem();
	$caracaldir = $vhost->getItem('section', 'Directory', null, array('"/srv/www/caracalui"'), 0);
	$caracaldir->removeItem();
	$conf->writeConfig($apache_config_file, 'apache');

	execute_program_detached("service apache2 restart");

	execute_program_detached("service $service_name stop");

	setFlash(t("caracalapi_stop_success"), "success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function install() {
	global $urlpath, $staticFile, $binary_url, $binary_deb;
	if(!defined('STDIN'))  define('STDIN',  fopen('php://stdin',  'r'));
	if(!defined('STDOUT')) define('STDOUT', fopen('php://stdout', 'w'));
	if(!defined('STDERR')) define('STDERR', fopen('php://stderr', 'w'));

	if (!_check_java()) {
		$java_install_cmd = "apt-get install openjdk-6-jre";
		$ret = execute_shell($java_install_cmd);
		if ($ret['return'] != 0) {
			setFlash(t("caracalapi_java_install_fail"));
			return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath)); // abort
		}
	}
	if (!isPackageInstall("php-pear")) {
		installPackage("php-pear");
	}
	$ret = execute_shell("pear install Config");
	if ($ret['return'] != 0) {
		setFlash(t("caracalapi_dependency_fail"));
		return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath)); // abort
	}

	$caracalapi_get_cmd = "cd /tmp && wget ".$binary_url;
	$ret = execute_shell($caracalapi_get_cmd);
	if ($ret['return'] != 0) {
		setFlash(t("caracalapi_download_fail"));
		return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath)); // abort
	}

	$caracalapi_install_cmd = "cd /tmp && dpkg -i $binary_deb";
	$ret = execute_shell($caracalapi_install_cmd);
	if ($ret['return'] != 0) {
		setFlash(t("caracalapi_install_fail"));
		return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath)); // abort
	}

	setFlash(t("caracalapi_install_success"), "success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath."/config"));
}

function uninstall() {
	global $urlpath, $staticFile, $service_name;

	$caracalapi_uninstall_cmd = "dpkg -r $service_name";
	$ret = execute_shell($caracalapi_uninstall_cmd);
	if ($ret['return'] != 0) {
		setFlash(t("caracalapi_uninstall_fail"));
		return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath)); // abort
	}
	$ret = execute_shell("pear uninstall Config");
		if ($ret['return'] != 0) {
			setFlash(t("caracalapi_dependency_fail"));
			return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath)); // abort
		}
	setFlash(t("caracalapi_uninstall_success"), "success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function logfile() { // nothing fancy...just quick and dirty^^
	global $urlpath, $staticFile, $log_file;
	$page = "";
	$buttons = "";

	$page .= hlc(t("caracalapi_title"));
	$page .= hl(t("caracalapi_log"),4);
	$page .= par(t("caracalapi_log_path")." $log_file: ");
	$page .= '<a href="#bottom">'.t("caracalapi_scroll_down").'</a>';
    $page .= ptxt(file_get_contents($log_file));
    $page .= '<hr id="bottom" />';


    $buttons .= addButton(array('label'=>t("caracalapi_button_status"),'class'=>'btn btn-success', 'href'=>"$urlpath", 'divOptions'=>array('class'=>'btn-group')));
	$buttons .= addButton(array('label'=>t("caracalapi_button_reload"),'class'=>'btn btn-success', 'href'=>"$urlpath/logfile", 'divOptions'=>array('class'=>'btn-group')));


    $page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function _check_java() {
	$ret = execute_program("java -version");
	//print_r($ret);
	if ($ret['return'] == 0) {
		return($ret['output'][0]);
	} else {
		return false;
	}
}

function _check_caracal() {
	global $service_script;
	$ret = execute_shell("ls $service_script");
	return($ret['return'] == 0);
}

function _check_running() {
	global $service_name;

	$ret = execute_program("service $service_name status");
	return strpos($ret['output'][0],'not') === false; // check if the output contains 'not' (as in "Service not running");
}

function _get_config_values() {
	$variables = array();
	$variables['CARACALAPI_BOOTSTRAP_IP'] = "10.228.207.42";
	$variables['CARACALAPI_BOOTSTRAP_PORT'] = "45678";
	$variables['CARACALAPI_LOCAL_IP'] = _get_guifi_ip();
	$variables['CARACALAPI_LOCAL_PORT'] = "45678";
	$variables['CARACALAPI_WEB_ADDR'] = "localhost";
	$variables['CARACALAPI_WEB_PORT'] = "8088";

	return $variables;
}

function _get_guifi_ip() {
	$ret = execute_program("ifconfig | grep 'inet addr' | awk '{ print $2 }' | awk -F':' '{ print $2 }'");
	foreach ($ret['output'] as $ip) {
		if (substr_compare($ip,"10.",0,3,true) == 0) {
			return $ip;
		}
	}
	return $ret['output'][0];
}

?>
