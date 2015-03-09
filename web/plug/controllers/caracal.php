<?php
//caracal.php

$config_file="/etc/caracaldb/application.conf";
$log_file="/var/log/caracaldb.log";
$service_script="/etc/init.d/caracaldb";
$service_name="caracaldb";
$binary_url="http://cloud7.sics.se/caracal/caracaldb.deb";
$binary_deb="caracaldb.deb";
$urlpath='/caracal';

function index_get(){

	global $config_file;
	global $urlpath;
    //$disabled = '';

    $page = "";
	$buttons = "";
	$submitButtons = "";
	$stati = "";
	$config_file_content = "";
	$form_fields = "";


	// if (isSesionValue('flash')) {
	// 	$flash = getSessionValue('flash');
	// 	unsetSessionValue('flash');
	// 	$flash_class = getSessionValue('flash_class');
	// 	unsetSessionValue('flash_class');
	// 	$page .= "<div class='$flash_class text-center'>$flash</div>\n";
	// }


	//$variables = load_conffile($getinconf_file);
	$page .= hlc(t("caracal_title"));
	$page .= hl(t("caracal_subtitle"),4);
    $page .= par(t("caracal_description"));

    $page .= txt(t("caracal_status"));

    $hasJava = _check_java();

    if ($hasJava) {
    	$stati .= "<div class='alert alert-success text-center'>".t('caracal_java_installed_true')." (".$hasJava.")</div>\n";
    } else {
    	$stati .= "<div class='alert alert-fail text-center'>".t('caracal_java_installed_false')."</div>\n";
	}

    $hasCaracal = _check_caracal();

    if ($hasCaracal) {
    	$stati .= "<div class='alert alert-success text-center'>".t('caracal_caracal_installed_true')."</div>\n";

		$config_file_content .= hlc(t("caracal_config_file"), 3);
    	$config_file_content .= par(t("caracal_config_path")." $config_file");
    	$config_file_content .= ptxt(file_get_contents($config_file));

    	$buttons .= addButton(array('label'=>t("caracal_button_log"),'class'=>'btn btn-info', 'href'=>"$urlpath/logfile", 'divOptions'=>array('class'=>'btn-group')));

     	$isRunning = _check_running();
     	if ($isRunning) {
			$stati .= "<div class='alert alert-success text-center'>".t('caracal_running_true')."</div>\n";
    		$buttons .= addButton(array('label'=>t("caracal_button_stop"),'class'=>'btn btn-danger', 'href'=>"$urlpath/stop", 'divOptions'=>array('class'=>'btn-group')));
    	} else {
    		$buttons .= addButton(array('label'=>t("caracal_button_uninstall"),'class'=>'btn btn-danger', 'href'=>"$urlpath/uninstall", 'divOptions'=>array('class'=>'btn-group')));

    		$stati .= "<div class='alert alert-fail text-center'>".t('caracal_running_false')."</div>\n";
    		$buttons .= addButton(array('label'=>t("caracal_button_start"),'class'=>'btn btn-success', 'href'=>"$urlpath/start", 'divOptions'=>array('class'=>'btn-group')));

    		$variable = _get_config_values();

			$form_fields .= hlc(t("caracal_config_edit"), 3);
    		$form_fields .= createForm(array('class'=>'form-horizontal'));
    		$form_fields .= addInput('CARACAL_BOOTSTRAP_IP',t('caracal_form_bsip'),$variable,array('type'=>'text', 'pattern'=>'\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}','required'=>''),false,t('caracal_form_bsip_help'));
    		$form_fields .= addInput('CARACAL_BOOTSTRAP_PORT',t('caracal_form_bsport'),$variable,array('type'=>'number', 'min' => '1024', 'max' => '65535', 'required'=>''),false,t('caracal_form_bsport_help'));
    		$form_fields .= addInput('CARACAL_LOCAL_IP',t('caracal_form_localip'),$variable,array('type'=>'text', 'pattern'=>'\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}','required'=>''),false,t('caracal_form_localip_help'));
    		$form_fields .= addInput('CARACAL_LOCAL_PORT',t('caracal_form_localport'),$variable,array('type'=>'number', 'min' => '1024', 'max' => '65535', 'required'=>''),false,t('caracal_form_localport_help'));

    		$submitButtons .= addSubmit(array('label'=>t('caracal_button_save')));
		}
    } else {
    	$stati .= "<div class='alert alert-fail text-center'>".t('caracal_caracal_installed_false')."</div>\n";
    	$buttons .= addButton(array('label'=>t("caracal_button_install"),'class'=>'btn btn-success', 'href'=>"$urlpath/install", 'divOptions'=>array('class'=>'btn-group')));
	}

    $page .= $stati . $config_file_content . $form_fields . $buttons . $submitButtons;
	return(array('type' => 'render','page' => $page));
}

function index_post(){

	global $staticFile, $urlpath;
	global $config_file;

	$config_values = array();
	foreach ($_POST as $key => $value) {
		if (substr_compare($key, "CARACAL_", 0, 8, true) == 0) {
			$config_values[$key] = $value;
		}
	}

	file_put_contents($config_file, 'caracal.heartbeatInterval = "30s"'.PHP_EOL); // reset file

	file_put_contents($config_file, 'server.address.hostname="'.$config_values['CARACAL_LOCAL_IP'].'"'.PHP_EOL, FILE_APPEND | LOCK_EX);
	file_put_contents($config_file, 'server.address.port='.$config_values['CARACAL_LOCAL_PORT'].PHP_EOL, FILE_APPEND | LOCK_EX);
	file_put_contents($config_file, 'bootstrap.address.hostname="'.$config_values['CARACAL_BOOTSTRAP_IP'].'"'.PHP_EOL, FILE_APPEND | LOCK_EX);
	file_put_contents($config_file, 'bootstrap.address.port='.$config_values['CARACAL_BOOTSTRAP_PORT'].PHP_EOL, FILE_APPEND | LOCK_EX);

	setFlash(t('caracal_saved_config'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function start() {
	global $urlpath, $staticFile, $service_name, $log_file;

	// Try to clean up old log file
	execute_shell("rm $log_file");

	execute_program_detached("service $service_name start");

	setFlash(t("caracal_start_success"), "success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function stop() {
	global $urlpath, $staticFile, $service_name;

	execute_program_detached("service $service_name stop");
	setFlash(t("caracal_stop_success"), "success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function install() {
	global $urlpath, $staticFile, $binary_url, $binary_deb;

	if (!_check_java()) {
		$java_install_cmd = "apt-get install openjdk-6-jre";
		$ret = execute_shell($java_install_cmd);
		if ($ret['return'] != 0) {
			setFlash(t("caracal_java_install_fail"));
			return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath)); // abort
		}
	}
	$caracal_get_cmd = "cd /tmp && wget ".$binary_url;
	$ret = execute_shell($caracal_get_cmd);
	if ($ret['return'] != 0) {
		setFlash(t("caracal_download_fail"));
		return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath)); // abort
	}

	$caracal_install_cmd = "cd /tmp && dpkg -i $binary_deb";
	$ret = execute_shell($caracal_install_cmd);
	if ($ret['return'] != 0) {
		setFlash(t("caracal_install_fail"));
		return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath)); // abort
	}

	setFlash(t("caracal_install_success"), "success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function uninstall() {
	global $urlpath, $staticFile, $service_name;

	$caracal_uninstall_cmd = "dpkg -r $service_name";
	$ret = execute_shell($caracal_uninstall_cmd);
	if ($ret['return'] != 0) {
		setFlash(t("caracal_uninstall_fail"));
		return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath)); // abort
	}
	setFlash(t("caracal_uninstall_success"), "success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function logfile() { // nothing fancy...just quick and dirty^^
	global $urlpath, $staticFile, $log_file;
	$page = "";
	$buttons = "";

	$page .= hlc(t("caracal_title"));
	$page .= hl(t("caracal_log"),4);
	$page .= par(t("caracal_log_path")." $log_file");
	$page .= '<a href="#bottom">'.t("caracal_scroll_bottom").'</a>';
    $page .= ptxt(file_get_contents($log_file));
    $page .= '<div id="bottom" />';


    $buttons .= addButton(array('label'=>t("caracal_button_back"),'class'=>'btn btn-default', 'href'=>"$urlpath", 'divOptions'=>array('class'=>'btn-group')));
	$buttons .= addButton(array('label'=>t("caracal_button_reload"),'class'=>'btn btn-primary', 'href'=>"$urlpath/logfile", 'divOptions'=>array('class'=>'btn-group')));


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
	$variables['CARACAL_BOOTSTRAP_IP'] = "10.228.207.42";
	$variables['CARACAL_BOOTSTRAP_PORT'] = "45678";
	$variables['CARACAL_LOCAL_IP'] = _get_guifi_ip();
	$variables['CARACAL_LOCAL_PORT'] = "45678";

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
