<?php
//plug/controllers/guifi-proxy3.php

$GUIFI_CONF_DIR = "/etc/cloudy/";
$GUIFI_CONF_FILE = "guifi.conf";
$GUIFI_PROXY3_FILE = "config.sh";
$GUIFI_PROXY3_DIR = "/etc/guifi-proxy3/";
$GUIFI_PROXY3_PKGNAME = "guifi-proxy3";
$SQUID3_PID_FILE = "/var/run/squid3.pid";
$SQUID3_INIT_FILE = "/etc/init.d/squid3";

$guifi_proxy3_desc = t("This software provides a federated proxy service in the context of Guifi.net");

$GUIFI_PROXY3_DEFAULTS = array(
	'base_url'=> array('default'=>'http://www.guifi.net',
		'desc'=>t('guifi-proxy3_form_url'),
		'vdeb'=>'guifi-proxy3/baseurl',
		'kdeb'=>'string',
		'options'=>array('type'=>'url', 'required'=>true),
		'tooltip'=>t("guifi-proxy3_form_url_tooltip")),

	'node' =>  array('default'=>'0',
		'desc'=>t('guifi-proxy3_form_node'),
		'vdeb'=>'guifi-proxy3/node',
		'kdeb'=>'string',
		'options'=>array('type'=>'number', 'required'=>true, 'min'=>0),
		'tooltip'=>t("guifi-proxy3_form_node_tooltip")),

	'ldap_main' =>  array('default'=>'ldap.guifi.net',
		'desc'=>t('guifi-proxy3_form_ldap1'),
		'vdeb'=>'guifi-proxy3/ldap_main',
		'kdeb'=>'string',
		'options'=>array('type'=>'text', 'required'=>true),
		'tooltip'=>t("guifi-proxy3_form_ldap1_tooltip")),

	'ldap_backup' =>  array('default'=>'ldap2.guifi.net',
		'desc'=>t('guifi-proxy3_form_ldap2'),
		'vdeb'=>'guifi-proxy3/ldap_backup',
		'kdeb'=>'string',
		'options'=>array('type'=>'text', 'required'=>true),
		'tooltip'=>t("guifi-proxy3_form_ldap2_tooltip")),

	'realm' =>  array('default'=>t('guifi-proxy3_form_welcome_default'),
		'desc'=>t('guifi-proxy3_form_welcome'),
		'vdeb'=>'guifi-proxy3/proxy_name',
		'kdeb'=>'string',
		'options'=>array('type'=>'text', 'required'=>true),
		'tooltip'=>t('guifi-proxy3_form_welcome_tooltip')),

	'manager' =>  array('default'=>'',
		'desc'=>t('guifi-proxy3_form_email'),
		'vdeb'=>'guifi-proxy3/email',
		'kdeb'=>'string',
		'options'=>array('type'=>'email', 'required'=>true, 'placeholder'=>'yourname@guifi.net'),
		'tooltip'=>t("guifi-proxy3_form_email_tooltip")),

	'language' =>  array('default'=>t('guifi-proxy3_form_language_default'),
		'desc'=>t('guifi-proxy3_form_language'),
		'vdeb'=>'guifi-proxy3/language',
		'kdeb'=>'string',
		'options'=>array('type'=>'text', 'required'=>true, 'pattern'=>"Armenian|Azerbaijani|Bulgarian|Catalan|Czech|Danish|Dutch|English|Estonian|Finnish|French|German|Greek|Hebrew|Hungarian|Italian|Japanese|Korean|Lithuanian|Polish|Portuguese|Romanian|Serbian|Slovak|Spanish|Swedish|Turkish"),
		'tooltip'=>t("guifi-proxy3_form_language_tooltip")),

	'cache_size' =>  array('default'=>'10240',
		'desc'=>t('guifi-proxy3_form_cache'),
		'vdeb'=>'guifi-proxy3/hd',
		'kdeb'=>'string',
		'options'=>array('type'=>'number', 'required'=>true, 'min'=>1),
		'tooltip'=>t("guifi-proxy3_form_cache_tooltip")),

	'cache_mem' =>  array('default'=>'128',
		'desc'=>t('guifi-proxy3_form_ram'),
		'vdeb'=>'guifi-proxy3/ram',
		'kdeb'=>'string',
		'options'=>array('type'=>'number', 'required'=>true, 'min'=>1),
		'tooltip'=>t("guifi-proxy3_form_ram_tooltip")),
);


function index_get(){
	global $GUIFI_CONF_DIR, $GUIFI_CONF_FILE, $GUIFI_PROXY3_PKGNAME, $GUIFI_PROXY3_PKGNAME, $staticFile;

	$page = "";
	$buttons = "";
	$GUIFI_CONFIG = [];

	$page .= hlc(t("guifi-proxy3_common_title"));
	$page .= hl(t("guifi-proxy3_index_subtitle"),4);

	$page .= par(t("guifi-proxy3_index_description1") . ' ' . t("guifi-proxy3_index_description2"));

	$page .= txt(t("guifi-proxy3_index_guifi_proxy3_status"));
	//Proxy3 is installed
	if (isPackageInstall($GUIFI_PROXY3_PKGNAME)) {


		//Proxy3 is running
		if (Proxy3IsRunning()) {
			$page .= "<div class='alert alert-success text-center'>".t("guifi-proxy3_running")."</div>\n";
			$page .= addButton(array('label'=>t('guifi-proxy3_button_restart'),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-proxy3/restart'));
			$page .= addButton(array('label'=>t('guifi-proxy3_button_stop'),'class'=>'btn btn-danger', 'href'=>$staticFile.'/guifi-proxy3/stop'));
		}

		//Proxy3 is not running
		else {
			$page .= "<div class='alert alert-error text-center'>".t("guifi-proxy3_stopped")."</div>\n";
			$page .= addButton(array('label'=>t('guifi-proxy3_button_configuration'),'class'=>'btn btn-primary', 'href'=>$staticFile.'/guifi-proxy3/configuration'));
			$page .= addButton(array('label'=>t('guifi-proxy3_button_start'),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-proxy3/start'));
		}

	}

	//Proxy3 is not installed
	else{
		$page .= "<div class='alert alert-error text-center'>".t("guifi-proxy3_alert_not_installed")."</div>\n";
		if (file_exists($GUIFI_CONF_DIR.$GUIFI_CONF_FILE) && filesize($GUIFI_CONF_DIR.$GUIFI_CONF_FILE))
			$GUIFI_CONFIG = load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

		//Cloudy not registered
		if (!isset($GUIFI_CONFIG["DEVICEID"]) || !is_numeric($GUIFI_CONFIG["DEVICEID"])) {
			$page .= txt(t("guifi-proxy3_common_notice"));
			$page .= "<div class='alert alert-warning text-center'>".t("guifi-proxy3_alert_not_registered")."</div>\n";
			$page .= par(t("guifi-proxy3_index_registration"));

			$page .= addButton(array('label'=>t('Register this Cloudy device before installing Guifi Proxy3'),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web'));
			$page .= addButton(array('label'=>t('guifi-proxy3_button_install_not_registered'),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-proxy3/install_not_registered'));
		}

		else {
			$page .= addButton(array('label'=>t('guifi-proxy3_button_install'),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-proxy3/install'));
		}

	}
	return(array('type' => 'render','page' => $page));
}

function configuration(){
	global $GUIFI_PROXY3_DIR, $GUIFI_PROXY3_FILE, $GUIFI_PROXY3_PKGNAME;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-proxy3_common_title"));
	$page .= hl(t("guifi-proxy3_install_subtitle"),4);

	$page .= par(t("guifi-proxy3_install_not_registered_description"));

	$page .= generate_form($GUIFI_PROXY3_DIR.$GUIFI_PROXY3_FILE);
	if (isPackageInstall($GUIFI_PROXY3_PKGNAME)){
		//$page .= addButton(array('label'=>t('Uninstall package'),'class'=>'btn btn-success', 'href'=>$staticFile.'/default/uninstall/'.$guifi_proxy3_pkg));
	}

	$page .= addButton(array('label'=>t("guifi-proxy3_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-proxy3'));
	$page .= addSubmit(array('label'=>t('guifi-proxy3_button_submit_save'),'class'=>'btn btn-success'));

	return(array('type' => 'render','page' => $page));
}

function configuration_post() {
	global $GUIFI_PROXY3_DEFAULTS, $GUIFI_PROXY3_DIR, $GUIFI_PROXY3_FILE, $GUIFI_PROXY3_PKGNAME, $staticFile, $guifi_proxy3_desc;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-proxy3_common_title"));
	$page .= hl(t("guifi-proxy3_install_subtitle"),4);

	$page .= txt(t("guifi-proxy3_install_configuration"));

	$missingVariables = false;
	foreach ($GUIFI_PROXY3_DEFAULTS as $key => $value) {
		if (!isset ($_POST[$key])){
			$page .= "<div class='alert alert-error text-center'>".t("guifi-proxy3_alert_missing_variable").' '.$key."</div>\n";
			$missingVariables = true;
		}
	}

	if ($missingVariables) {
		$page .= par(t("guifi-proxy3_install_missing_variables"));
		$page .= addButton(array('label'=>t("guifi-proxy3_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-proxy3'));
		$page .= addButton(array('label'=>t("guifi-proxy3_button_retry"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-proxy3/install_not_registered'));
	}
	else {
		$dataToSave = array();
		foreach ($_POST as $key => $value)
			$dataToSave[$key] = $value;

		if (!file_exists($GUIFI_PROXY3_DIR))
			mkdir ($GUIFI_PROXY3_DIR, 16877);
		if (fileperms($GUIFI_PROXY3_DIR) != 16877)
			chmod ($GUIFI_PROXY3_DIR, 16877);
		if (!file_exists($GUIFI_PROXY3_DIR.$GUIFI_PROXY3_FILE))
			touch($GUIFI_PROXY3_DIR.$GUIFI_PROXY3_FILE);
		if (fileperms($GUIFI_PROXY3_DIR.$GUIFI_PROXY3_FILE) != 33188)
			chmod($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, 33188);

		write_merge_conffile($GUIFI_PROXY3_DIR.$GUIFI_PROXY3_FILE,add_quotes($dataToSave));


		$page .= "<div class='alert alert-success text-center'>".t("guifi-proxy3_alert_configuration_saved")."</div>\n";
		$page .= par(t("guifi-proxy3_install_saved").' '.$GUIFI_PROXY3_DIR.$GUIFI_PROXY3_FILE);

		if (($define_variables = package_default_variables($dataToSave,$GUIFI_PROXY3_DEFAULTS, $GUIFI_PROXY3_PKGNAME)) != ""){
			$page .= "<div class='alert'><pre>".$define_variables."</pre></div>";
		}

		$page .= txt(t("guifi-proxy3_install_result"));
		if (isPackageInstall($GUIFI_PROXY3_PKGNAME)){
			$page .= "<div class='alert alert-success text-center'>".t("guifi-proxy3_alert_installation_success")."</div>\n";
			$page .= txt(t("guifi-proxy3_install_details"));
			$page .= $installationLog;
			$page .= txt(t("guifi-proxy3_install_status"));
			if (Proxy3IsRunning()){
				$page .= "<div class='alert alert-success text-center'>".t("guifi-proxy3_running")."</div>\n";
			}
			else {
				$page .= "<div class='alert alert-error text-center'>".t("guifi-proxy3_stopped")."</div>\n";
			}
			$page .= addButton(array('label'=>t("guifi-proxy3_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-proxy3'));
		}
		else {
			$page .= "<div class='alert alert-error text-center'>".t("guifi-proxy3_alert_installation_fail")."</div>\n";
			$page .= txt(t("guifi-proxy3_install_details"));
			$page .= $installationLog;
		}
	}

		//Canviar el fitxer de configuraci\F3
		foreach ($dataToSave as $key => $value) {
			if($guifi_proxy3_variables[$key]['kdeb'] == 'string'){
				$dataToSave[$key] = "'".$value."'";
			}
		}

		write_conffile($GUIFI_PROXY3_DIR.$GUIFI_PROXY3_FILE,$dataToSave);
		setFlash(t("guifi-proxy3_alert_saved"),"success");
		restart();
		return(array('type' => 'redirect', 'url' => $staticFile.'/guifi-proxy3'));

	return(array('type' => 'render','page' => $page));
}

function install_not_registered() {
	global $GUIFI_PROXY3_DIR, $GUIFI_PROXY3_FILE, $GUIFI_PROXY3_PKGNAME,$staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-proxy3_common_title"));
	$page .= hl(t("guifi-proxy3_install_subtitle"),4);

	$page .= par(t("guifi-proxy3_install_not_registered_description"));

	$page .= generate_form($GUIFI_PROXY3_DIR.$GUIFI_PROXY3_FILE);

	$page .= addButton(array('label'=>t("guifi-proxy3_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-proxy3'));
	$page .= addSubmit(array('label'=>t('guifi-proxy3_button_submit_install'),'class'=>'btn btn-success'));
	return(array('type' => 'render','page' => $page));
}

function install_not_registered_post() {
	global $GUIFI_PROXY3_DEFAULTS, $GUIFI_PROXY3_DIR, $GUIFI_PROXY3_FILE, $GUIFI_PROXY3_PKGNAME, $staticFile, $guifi_proxy3_desc;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-proxy3_common_title"));
	$page .= hl(t("guifi-proxy3_install_subtitle"),4);

	$page .= txt(t("guifi-proxy3_install_configuration"));

	$missingVariables = false;
	foreach ($GUIFI_PROXY3_DEFAULTS as $key => $value) {
		if (!isset ($_POST[$key])){
			$page .= "<div class='alert alert-error text-center'>".t("guifi-proxy3_alert_missing_variable").' '.$key."</div>\n";
			$missingVariables = true;
		}
	}

	if ($missingVariables) {
		$page .= par(t("guifi-proxy3_install_missing_variables"));
		$page .= addButton(array('label'=>t("guifi-proxy3_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-proxy3'));
		$page .= addButton(array('label'=>t("guifi-proxy3_button_retry"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-proxy3/install_not_registered'));
	}
	else {
		$dataToSave = array();
		foreach ($_POST as $key => $value)
			$dataToSave[$key] = $value;

		$page .= "<div class='alert alert-success text-center'>".t("guifi-proxy3_alert_configuration_saved")."</div>\n";
		$page .= par(t("guifi-proxy3_install_saved").' '.$GUIFI_PROXY3_DIR.$GUIFI_PROXY3_FILE);

		if (($define_variables = package_default_variables($dataToSave,$GUIFI_PROXY3_DEFAULTS, $GUIFI_PROXY3_PKGNAME)) != ""){
			$page .= "<div class='alert'><pre>".$define_variables."</pre></div>";
		}

		$installationLog = ptxt(installPackage($GUIFI_PROXY3_PKGNAME));

		$page .= txt(t("guifi-proxy3_install_result"));
		if (isPackageInstall($GUIFI_PROXY3_PKGNAME)){
			$page .= "<div class='alert alert-success text-center'>".t("guifi-proxy3_alert_installation_success")."</div>\n";
			$page .= txt(t("guifi-proxy3_install_details"));
			$page .= $installationLog;
			$page .= txt(t("guifi-proxy3_install_status"));
			if (Proxy3IsRunning()){
				$page .= "<div class='alert alert-success text-center'>".t("guifi-proxy3_running")."</div>\n";
			}
			else {
				$page .= "<div class='alert alert-error text-center'>".t("guifi-proxy3_stopped")."</div>\n";
			}
			$page .= addButton(array('label'=>t("guifi-proxy3_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-proxy3'));
		}
		else {
			$page .= "<div class='alert alert-error text-center'>".t("guifi-proxy3_alert_installation_fail")."</div>\n";
			$page .= txt(t("guifi-proxy3_install_details"));
			$page .= $installationLog;
		}
	}

	return(array('type' => 'render','page' => $page));
}

function generate_form($file){
	global $staticFile, $GUIFI_PROXY3_DEFAULTS,$GUIFI_WEB, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE,$services_types;

	$page = "";

	$webinfo = _getServiceInformation($services_types['proxy3']['name']);
	$variables = load_conffile($file,$GUIFI_PROXY3_DEFAULTS);

	if (($variables['node'] == 0) && (isset($webinfo['id']))) {
		$variables['node'] = $webinfo['id'];
	}

	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($GUIFI_PROXY3_DEFAULTS as $op=>$val) {
		$page .= addInput($op, $val['desc'], $variables, $val['options'], '', $val['tooltip']);
		if ($op == 'node' && $variables['node'] == 0) {
			// Crearlo automaticament?
			$GUIFI=load_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE);
			if (isset($GUIFI['DEVICEID'])){
				$bcreate = t("guifi-you_configure_your_cloudy_device");
				$bcreate .= addButton(array('label'=>t("guifi-create_service"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-proxy3/createservice/proxy3'));
			} else {
				$bcreate = t("guifi-please_configure_your_cloudy");
			}
			$page .= par($bcreate);
		}
	}

	return($page);
}

function Proxy3IsRunning() {
	global $GUIFI_PROXY3_PKGNAME, $SQUID3_PID_FILE;

	$cmd = "/etc/init.d/squid3 status";
	$cmdResult = execute_program($cmd);

	if (isPackageInstall($GUIFI_PROXY3_PKGNAME) && file_exists($SQUID3_PID_FILE) && isset($cmdResult['output']) && isset($cmdResult['output'][0]) && strpos($cmdResult['output'][0],'is running') !== false)
		return true;
	return false;
}

function restart(){
	global $SQUID3_INIT_FILE, $staticFile;

	$cmd = $SQUID3_INIT_FILE." stop";
	execute_program_detached($cmd);
	$cmd = $SQUID3_INIT_FILE." restart";
	execute_program_detached($cmd);

	setFlash(t('guifi-proxy3_flash_restarting'),"warning");
	return(array('type'=> 'redirect', 'url' => $staticFile.'/guifi-proxy3'));
}

function start(){
	global $SQUID3_INIT_FILE, $staticFile;

	$cmd = $SQUID3_INIT_FILE." start";
	execute_program_detached($cmd);

	setFlash(t('guifi-proxy3_flash_starting'),"warning");
	return(array('type'=> 'redirect', 'url' => $staticFile.'/guifi-proxy3'));
}

function stop() {
	global $SQUID3_INIT_FILE, $staticFile;

	$cmd = $SQUID3_INIT_FILE." stop";
	execute_program_detached($cmd);
	execute_program_detached($cmd);

	setFlash(t('guifi-proxy3_flash_stopping'),"warning");
	return(array('type'=> 'redirect', 'url' => $staticFile.'/guifi-proxy3'));
}
