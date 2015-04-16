<?php
//plug/controllers/guifi-proxy3.php

$GUIFI_CONF_DIR = "/etc/guifi/";
$GUIFI_CONF_FILE = "guifi.conf";
$guifi_proxy3_file="/etc/guifi-proxy3/config.sh";
$GUIFI_PROXY3_PKGNAME="guifi-proxy3";
$SQUID3_PID_FILE = "/var/run/squid3.pid";

$guifi_proxy3_desc = t("This software provides a federated proxy service in the context of Guifi.net");


function index_get(){
	global $GUIFI_CONF_DIR, $GUIFI_CONF_FILE, $GUIFI_PROXY3_PKGNAME, $GUIFI_PROXY3_PKGNAME, $staticFile;

	$page = "";
	$buttons = "";
	$GUIFI_CONFIG = "";

	$page .= hlc(t("guifi-proxy3_common_title"));
	$page .= hl(t("guifi-proxy3_index_subtitle"),4);

	$page .= par(t("guifi-proxy3_index_description1") . ' ' . t("guifi-proxy3_index_description2"));

	$page .= txt(t("guifi-proxy3_index_guifi_proxy3_status"));

	//Proxy3 is installed
	if (isPackageInstall($GUIFI_PROXY3_PKGNAME)) {

		//Proxy3 is running
		if (Proxy3IsRunning) {

		}

		//Proxy3 is not running
		else {

		}

	}

	//Proxy3 is not installed
	else{
		$page .= "<div class='alert alert-error text-center'>".t("guifi-proxy3_alert_not_installed")."</div>\n";
		if (file_exists($GUIFI_CONF_DIR.$GUIFI_CONF_FILE) && filesize($GUIFI_CONF_DIR.$GUIFI_CONF_FILE))
			$GUIFI_CONFIG = load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

		//Cloudy not registered
		if (!is_numeric($GUIFI_CONFIG["DEVICEID"])) {
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

function install() {
	$page = "";
	$buttons = "";
	$GUIFI_CONFIG = "";

	$page .= hlc(t("guifi-proxy3_common_title"));
	$page .= hl(t("guifi-proxy3_install_subtitle"),4);

	$page .= generate_form($guifi_proxy3_file,$guifi_proxy3_variables);
	if (isPackageInstall($guifi_proxy3_pkg)){
		$page .= addButton(array('label'=>t('Uninstall package'),'class'=>'btn btn-success', 'href'=>$staticFile.'/default/uninstall/'.$guifi_proxy3_pkg));
	}

	return(array('type' => 'render','page' => $page));
}

function index_post(){
	global $guifi_proxy3_file, $guifi_proxy3_pkg, $guifi_proxy3_desc, $guifi_proxy3_variables, $staticFile;

	$page = "";

	$datesToSave = array();
	foreach ($_POST as $key => $value) {
		$datesToSave[$key] = $value;
	}

	if (!isPackageInstall($guifi_proxy3_pkg)){
		if (($define_variables = package_default_variables($datesToSave,$guifi_proxy3_variables, $guifi_proxy3_pkg)) != ""){
			$page .= "<div class='alert'><pre>".$define_variables."</pre></div>";
		}
		$page .= package_not_install($guifi_proxy3_pkg,$guifi_proxy3_desc);
	} else {
		//Canviar el fitxer de configuració
		foreach ($datesToSave as $key => $value) {
			if($guifi_proxy3_variables[$key]['kdeb'] == 'string'){
				$datesToSave[$key] = "'".$value."'";
			}
		}
		write_conffile($guifi_proxy3_file,$datesToSave);
		setFlash(t("Save it")."!","success");
		return(array('type' => 'redirect', 'url' => $staticFile.'/guifi/proxy3'));
	}
	return(array('type' => 'render','page' => $page));
}

function generate_form($file,$options){
	$page = "";

	$options = array(
		'base_url'=> array('default'=>'http://www.guifi.net',
			'desc'=>t('guifi-proxy3_form_url'),
			'vdeb'=>'guifi-proxy3/baseurl',
			'options'=>array('type'=>'url', 'required'=>true),
			'tooltip'=>t("guifi-proxy3_form_url_tooltip")),

		'node' =>  array('default'=>'0',
			'desc'=>t('guifi-proxy3_form_node'),
			'vdeb'=>'guifi-proxy3/node',
			'options'=>array('type'=>'number', 'required'=>true, 'min'=>0),
			'tooltip'=>t("guifi-proxy3_form_node_tooltip")),

		'ldap_main' =>  array('default'=>'ldap.guifi.net',
			'desc'=>t('guifi-proxy3_form_ldap1'),
			'vdeb'=>'guifi-proxy3/ldap_main',
			'kdeb'=>'string',
			'tooltip'=>t("guifi-proxy3_form_ldap1_tooltip")),

		'ldap_backup' =>  array('default'=>'ldap2.guifi.net',
			'desc'=>t('guifi-proxy3_form_ldap2'),
			'vdeb'=>'guifi-proxy3/ldap_backup',
			'kdeb'=>'string',
			'tooltip'=>t("guifi-proxy3_form_ldap2_tooltip")),

		'realm' =>  array('default'=>t('guifi-proxy3_form_welcome_default'),
			'desc'=>t('guifi-proxy3_form_welcome'),
			'vdeb'=>'guifi-proxy3/proxy_name',
			'kdeb'=>'string',
			'tooltip'=>t('guifi-proxy3_form_welcome_tooltip')),

		'manager' =>  array('default'=>'webmaster',
			'desc'=>t('Contact email proxy server'),
			'vdeb'=>'guifi-proxy3/email',
			'kdeb'=>'string',
			'tooltip'=>t("guifi-proxy3_form_URL")),

		'language' =>  array('default'=>'Catalan',
			'desc'=>t('Choosemanager a language for error pages generated'),
			'vdeb'=>'guifi-proxy3/language',
			'kdeb'=>'string',
			'tooltip'=>t("guifi-proxy3_form_URL")),

		'cache_size' =>  array('default'=>'10240',
			'desc'=>t('Disk cache space (MB)'),
			'vdeb'=>'guifi-proxy3/hd',
			'kdeb'=>'string',
			'tooltip'=>t("guifi-proxy3_form_URL")),

		'cache_mem' =>  array('default'=>'100',
			'desc'=>t('Ram cache space (MB)'),
			'vdeb'=>'guifi-proxy3/ram',
			'kdeb'=>'string',
			'tooltip'=>t("guifi-proxy3_form_URL"))
	);

	$variables = load_conffile($file,$options);

	$page .= createForm(array('class'=>'form-horizontal'));
	foreach($options as $op=>$val)
		$page .= addInput($op, $val['desc'], $variables, $val['options'], '', $val['tooltip']);
	$page .= addSubmit(array('label'=>t('Executar')));

	return($page);

}

function Proxy3IsRunning() {
	global $GUIFI_PROXY3_PKGNAME, $SQUID3_PID_FILE;

	if (isPackageInstall($guifi_proxy3_pkg) && file_exists($SQUID3_PID_FILE))
		return true;
	return false;
}

