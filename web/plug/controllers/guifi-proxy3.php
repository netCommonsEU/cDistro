<?php
//plug/controllers/guifi-proxy3.php

$GUIFI_CONF_DIR = "/etc/cloudy/";
$GUIFI_CONF_FILE = "guifi.conf";
$GUIFI_PROXY3_FILE = "config.sh";
$GUIFI_PROXY3_DIR = "/etc/guifi-proxy3/";
$GUIFI_PROXY3_PKGNAME = "guifi-proxy3";
$GUIFI_PROXY3_PLUG = "guifi-proxy3";
$SQUID3_PID_FILE = "/var/run/squid3.pid";
$SQUID3_INIT_FILE = "/etc/init.d/squid3";

$guifi_proxy3_desc = t("guifi-proxy3_common_desc");

$GUIFI_PROXY3_DEFAULTS = array(
	'base_url'=> array('default'=>'http://www.guifi.net',
		'desc'=>t('guifi-proxy3_form_url'),
		'vdeb'=>'guifi-proxy3/baseurl',
		'kdeb'=>'string',
		'options'=>array('type'=>'url', 'required'=>true),
		'tooltip'=>t("guifi-proxy3_form_url_tooltip")),

	'node' =>  array('default'=>'-1',
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
		'options'=>array('type'=>'text', 'required'=>true, 'pattern'=>"^[a-zA-Z][a-zA-Z0-9-_\.]{1,256}$"),
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

	'cache_size' =>  array('default'=>'1024',
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


function index(){
	global $GUIFI_CONF_DIR, $GUIFI_CONF_FILE, $GUIFI_PROXY3_PKGNAME, $GUIFI_PROXY3_PKGNAME, $GUIFI_PROXY3_PLUG, $staticFile;

	$page = "";
	$buttons = "";
	$GUIFI_CONFIG = [];

	$page .= hlc(t("guifi-proxy3_common_appname"));
	$page .= hl(t("guifi-proxy3_common_desc"),4);

	$page .= par(t("guifi-proxy3_index_description"));

	$page .= par(t("guifi-proxy3_index_connected").' '.t("guifi-proxy3_index_checkwiki").' '.'<a href="'.t("guifi-proxy3_index_wikiurl").'">'.t("guifi-proxy3_index_wikiurl").'</a>');

	$page .= txt(t("guifi-proxy3_common_status_pre").t("guifi-proxy3_common_appname").t("guifi-proxy3_common_status_post"));
	if (!isPackageInstall($GUIFI_PROXY3_PKGNAME)) {

		$page .= "<div class='alert alert-error text-center'>".t("guifi-proxy3_alert_not_installed_pre").t("guifi-proxy3_common_appname").t("guifi-proxy3_alert_not_installed_post")."</div>\n";

		$page .= txt(t("guifi-proxy3_common_guifi:"));
		if ( !cloudyRegistrationFull() ) {
			$page .= "<div class='alert alert-error text-center'>".t("guifi-proxy3_alert_not_guifi")."</div>\n";
			$page .= par(t("guifi-proxy3_index_not_guifi").' '.t("guifi-proxy3_index_register_before_pre").t("guifi-proxy3_common_appname").t("guifi-proxy3_index_register_before_post"));
			$buttons .= addButton(array('label'=>t("guifi-proxy3_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web'));
			$buttons .= addButton(array('label'=>t("guifi-proxy3_button_unregistered_pre").t("guifi-proxy3_common_appname").t("guifi-proxy3_button_unregistered_post"),'class'=>'btn btn-default', 'href'=>$staticFile.'/'.$GUIFI_PROXY3_PLUG.'/install'));
		}

		else {
			$page .= "<div class='alert alert-success text-center'>".t("guifi-proxy3_alert_guifi")."</div>\n";
			$page .= par(t("guifi-proxy3_index_install_pre").t("guifi-proxy3_common_appname").t("guifi-proxy3_index_install_post"));
			$buttons .= addButton(array('label'=>t("guifi-proxy3_button_install_pre").t("guifi-proxy3_common_appname").t("guifi-proxy3_button_install_post"),'class'=>'btn btn-success', 'href'=>$staticFile.'/'.$GUIFI_PROXY3_PLUG.'/install'));
		}
	}

	else {
		$page .= "<div class='alert alert-success text-center'>".t("guifi-proxy3_alert_installed_pre").t("guifi-proxy3_common_appname").t("guifi-proxy3_alert_installed_post")."</div>\n";
		//Proxy3 is running
		if (Proxy3IsRunning()) {
			$page .= "<div class='alert alert-success text-center'>".t("guifi-proxy3_running")."</div>\n";
			$page .= addButton(array('label'=>t('guifi-proxy3_button_restart'),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-proxy3/restart'));
			$page .= addButton(array('label'=>t('guifi-proxy3_button_stop'),'class'=>'btn btn-danger', 'href'=>$staticFile.'/guifi-proxy3/stop'));
		}

		//Proxy3 is not running
		else {
			$page .= "<div class='alert alert-error text-center'>".t("guifi-proxy3_stopped")."</div>\n";
			$page .= addButton(array('label'=>t('guifi-proxy3_button_configuration'),'class'=>'btn btn-primary', 'href'=>$staticFile.'/guifi-proxy3/install'));
			$page .= addButton(array('label'=>t('guifi-proxy3_button_start'),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-proxy3/start'));
		}

	}
	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function install() {
	global $GUIFI_PROXY3_DIR, $GUIFI_PROXY3_FILE, $GUIFI_PROXY3_PKGNAME, $GUIFI_PROXY3_PLUG, $staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-proxy3_common_appname"));
	$page .= hl(t("guifi-proxy3_install_subtitle"),4);

	$buttons .= addButton(array('label'=>t("guifi-proxy3_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/'.$GUIFI_PROXY3_PLUG));

	if (!isPackageInstall($GUIFI_PROXY3_PKGNAME)) {

		if ( !CloudyRegistrationFull()) {
			$page .= par(t("guifi-proxy3_install_declare").' '.t("guifi-proxy3_index_checkwiki").' '.'<a href="'.t("guifi-proxy3_index_wikiurl").'">'.t("guifi-proxy3_index_wikiurl").'</a>');
			$page .= proxy3_generate_form($GUIFI_PROXY3_DIR.$GUIFI_PROXY3_FILE);
			$buttons .= addButton(array('label'=>t("guifi-proxy3_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web'));
			$buttons .= addSubmit(array('label'=>t("guifi-proxy3_button_unregistered_pre").t("guifi-proxy3_common_appname").t("guifi-proxy3_button_unregistered_post"),'class'=>'btn btn-default'));
		}

		else {

			if (!serviceDeclared($GUIFI_PROXY3_PKGNAME)) {
				$page .= par(t("guifi-proxy3_install_declare").' '.t("guifi-proxy3_install_autodeclare").' '.t("guifi-proxy3_install_otherwise"));
				$page .= proxy3_generate_form($GUIFI_PROXY3_DIR.$GUIFI_PROXY3_FILE);
				$buttons .= addButton(array('label'=>t("guifi-proxy3_button_create_service_pre").t("guifi-proxy3_common_appname").t("guifi-proxy3_button_create_service_post"),'class'=>'btn btn-success', 'href'=>$staticFile.'/'.$GUIFI_PROXY3_PLUG.'/createservice/'.$GUIFI_PROXY3_PKGNAME));
				$buttons .= addSubmit(array('label'=>t("guifi-proxy3_button_unregistereds_pre").t("guifi-proxy3_common_appname").t("guifi-proxy3_button_unregistereds_post"),'class'=>'btn btn-default'));
			}

			else {
				$page .= par(t("guifi-proxy3_install_declared_pre").t("guifi-proxy3_common_appname").t("guifi-proxy3_install_declared_post"));
				$page .= proxy3_generate_form($GUIFI_PROXY3_DIR.$GUIFI_PROXY3_FILE);
				$page .= par(t("guifi-proxy3_install_value"));
				$buttons .= addSubmit(array('label'=>t("guifi-proxy3_button_sinstall_pre").t("guifi-proxy3_common_appname").t("guifi-proxy3_button_sinstall_post"),'class'=>'btn btn-success'));
			}
		}
	}

	else {
		$page .= proxy3_generate_form($GUIFI_PROXY3_DIR.$GUIFI_PROXY3_FILE);
		$buttons .= addSubmit(array('label'=>t("guifi-proxy3_button_save"),'class'=>'btn btn-primary'));
	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}








function install_post() {
	global $GUIFI_PROXY3_DEFAULTS, $GUIFI_PROXY3_DIR, $GUIFI_PROXY3_FILE, $GUIFI_PROXY3_PKGNAME, $GUIFI_PROXY3_PLUG, $staticFile, $guifi_proxy3_desc;

	$page = "";
	$buttons = "";

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
		$datesToSave = array();
		foreach ($_POST as $key => $value) {
			$datesToSave[$key] = $value;
		}

		if (!isPackageInstall($GUIFI_PROXY3_PKGNAME)) {
			if (($define_variables = package_default_variables($datesToSave,$GUIFI_PROXY3_DEFAULTS, $GUIFI_PROXY3_PKGNAME)) != ""){
				$page .= "<div class='alert'><pre>".$define_variables."</pre></div>";
			}
			$page .= package_not_install($GUIFI_PROXY3_PKGNAME,t("guifi-proxy3_common_desc"));
		}

		else {
			//Canviar el fitxer de configuració
			foreach ($datesToSave as $key => $value) {
				if($GUIFI_PROXY3_DEFAULTS[$key]['kdeb'] == 'string'){
					$datesToSave[$key] = "'".$value."'";
				}
			}
			$page .= ptxt(print_r($datesToSave,1));
			write_merge_conffile($GUIFI_PROXY3_DIR.$GUIFI_PROXY3_FILE,$datesToSave);
			setFlash(t("guifi-proxy3_alert_save"),"success");
			return(array('type' => 'redirect', 'url' => $staticFile.'/'.$GUIFI_PROXY3_PLUG));
		}
	}

	return(array('type' => 'render','page' => $page));
}

function proxy3_generate_form($file){
	global $staticFile, $GUIFI_PROXY3_DEFAULTS,$GUIFI_WEB, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE,$services_types;

	$page = '';

	$webinfo = _getServiceInformation($services_types['guifi-proxy3']['name']);
	$variables = load_conffile($file,$GUIFI_PROXY3_DEFAULTS);

	if (($variables['node'] == -1) && (isset($webinfo['id']))) {
		$variables['node'] = $webinfo['id'];
	}

	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($GUIFI_PROXY3_DEFAULTS as $op=>$val)
		$page .= addInput($op, $val['desc'], $variables, $val['options'], '', $val['tooltip']);

	return($page);
}

function Proxy3IsRunning() {
	global $GUIFI_PROXY3_PKGNAME, $SQUID3_PID_FILE;

	$cmd = "/etc/init.d/squid3 status";
	$cmdResult = execute_program($cmd);

	if (isPackageInstall($GUIFI_PROXY3_PKGNAME) && file_exists($SQUID3_PID_FILE) && isset($cmdResult['output'])) {
		// Debian Wheezy (init system)
		if (isset($cmdResult['output'][0]) && strpos($cmdResult['output'][0],'is running') !== false) return true;
		// Debian Jessie (systemd)
		if (isset($cmdResult['output'][2]) && strpos($cmdResult['output'][2],'running') !== false) return true;
		}
	return false;
}

function restart(){
	global $SQUID3_INIT_FILE, $staticFile;

	$cmd = $SQUID3_INIT_FILE." stop";
	execute_program_detached($cmd);
	$cmd = $SQUID3_INIT_FILE." restart";
	execute_program_detached($cmd);

	setFlash(t('guifi-proxy3_flash_restarting'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.'/guifi-proxy3'));
}

function start(){
	global $SQUID3_INIT_FILE, $staticFile;

	$cmd = $SQUID3_INIT_FILE." start";
	execute_program_detached($cmd);

	setFlash(t('guifi-proxy3_flash_starting'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.'/guifi-proxy3'));
}

function stop() {
	global $SQUID3_INIT_FILE, $staticFile;

	$cmd = $SQUID3_INIT_FILE." stop";
	execute_program_detached($cmd);
	execute_program_detached($cmd);

	setFlash(t('guifi-proxy3_flash_stopping'),"error");
	return(array('type'=> 'redirect', 'url' => $staticFile.'/guifi-proxy3'));
}
