<?php

//plug/controllers/wifi/dnsservices.php

$DNSS_DIR = "/etc/dnsservices/";
$DNSS_CONF = "config.php";
$DNSS_INITD = "/etc/init.d/bind9";
$DNSS_PKGNAME = "dnsservices";
$DNSS_PLUG = "guifi-dnss";
$DNSS_DEFAULTS = array (
	'DNSGraphServerId' => array(
		'default' => '-1',
		'desc' => t("guifi-dnss_form_id"),
		'vdeb' => 'dnsservices/DNSGraphServerId',
		'tooltip' => t("guifi-dnss_form_id_tooltip"),
		'options'=>array('type'=>'number', 'required'=>true, 'min'=>0),
		'kdeb' => 'string'),
	'DNSDataServer_url' => array (
		'default' => 'http://guifi.net',
		'desc' => t("guifi-dnss_form_url"),
		'vdeb' => 'dnsservices/DNSDataServerurl',
		'tooltip' => t("guifi-dnss_form_url_tooltip"),
		'options'=>array('type'=>'url', 'required'=>true),
		'kdeb' => 'string')
	);

$dnsservices_undefined_variables=array(array('vdeb'=> 'dnsservices/forcefetch',
										     'kdeb' => 'boolean',
										     'default' => 'false'
										     )
									);

function index(){

	global $DNSS_PLUG, $DNSS_DIR, $DNSS_CONF, $DNSS_PKGNAME, $DNSS_DEFAULTS, $staticFile, $dnsservices_undefined_variables;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-dnss_common_appname"));
	$page .= hl(t("guifi-dnss_common_desc"),4);

	$page .= par(t("guifi-dnss_index_desc"));
	$page .= par(t("guifi-dnss_index_connected").' '.t("guifi-dnss_index_checkwiki").' '.'<a href="'.t("guifi-dnss_index_wikiurl").'">'.t("guifi-dnss_index_wikiurl").'</a>');

	$page .= txt(t("guifi-dnss_common_status_pre").t("guifi-dnss_common_appname").t("guifi-dnss_common_status_post"));
	if (!isPackageInstall($DNSS_PKGNAME)){

		$page .= "<div class='alert alert-error text-center'>".t("guifi-dnss_alert_not_installed_pre").t("guifi-dnss_common_appname").t("guifi-dnss_alert_not_installed_post")."</div>\n";

		$page .= txt(t("guifi-dnss_common_guifi:"));
		if ( !cloudyRegistrationFull() ) {
			$page .= "<div class='alert alert-error text-center'>".t("guifi-dnss_alert_not_guifi")."</div>\n";
			$page .= par(t("guifi-dnss_index_not_guifi").' '.t("guifi-dnss_index_register_before_pre").t("guifi-dnss_common_appname").t("guifi-dnss_index_register_before_post"));
			$buttons .= addButton(array('label'=>t("guifi-dnss_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web'));
			$buttons .= addButton(array('label'=>t("guifi-dnss_button_unregistered_pre").t("guifi-dnss_common_appname").t("guifi-dnss_button_unregistered_post"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-dnss/install'));
		}

		else {
			$page .= "<div class='alert alert-success text-center'>".t("guifi-dnss_alert_guifi")."</div>\n";
			$page .= par(t("guifi-dnss_index_guifi"));
			$buttons .= addButton(array('label'=>t("guifi-dnss_button_install_pre").t("guifi-dnss_common_appname").t("guifi-dnss_button_install_post"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-dnss/install'));
		}
	}

	else {
		$page .= "<div class='alert alert-success text-center'>".t("guifi-dnss_alert_installed_pre").t("guifi-dnss_common_appname").t("guifi-dnss_alert_installed_post")."</div>\n";
		if ( serviceStarted() ) {
			$page .= "<div class='alert alert-success text-center'>".t("guifi-dnss_alert_running_pre").t("guifi-dnss_common_appname").t("guifi-dnss_alert_running_post")."</div>\n";
			$buttons .= addButton(array('label'=>t("guifi-dnss_button_stop_pre").t("guifi-dnss_common_appname").t("guifi-dnss_button_stop_post"),'class'=>'btn btn-danger', 'href'=>'./'.$DNSS_PLUG.'/stop'));
		}
		else {
			$page .= "<div class='alert alert-warning text-center'>".t("guifi-dnss_alert_stopped_pre").t("guifi-dnss_common_appname").t("guifi-dnss_alert_stopped_post")."</div>\n";
			$buttons .= addButton(array('label'=>t("guifi-dnss_button_start_pre").t("guifi-dnss_common_appname").t("guifi-dnss_button_start_post"),'class'=>'btn btn-success', 'href'=>'./'.$DNSS_PLUG.'/start'));
			$buttons .= addButton(array('label'=>t("guifi-dnss_button_configure_pre").t("guifi-dnss_common_appname").t("guifi-dnss_button_configure_post"),'class'=>'btn btn-primary', 'href'=>'./'.$DNSS_PLUG.'/install'));
			$buttons .= addButton(array('label'=>t("guifi-dnss_button_uninstall_pre").t("guifi-dnss_common_appname").t("guifi-dnss_button_uninstall_post"),'class'=>'btn btn-danger', 'href'=>$staticFile.'/default/uninstall/'.$DNSS_PKGNAME));
		}

		$page .= txt(t("guifi-dnss_common_guifi:"));
		if ( !cloudyRegistrationFull() ) {
			$page .= "<div class='alert alert-warning text-center'>".t("guifi-dnss_alert_not_guifi")."</div>\n";
			$page .= par(t("guifi-dnss_index_not_guifi").' '.t("guifi-dnss_index_register"));
			$buttons .= addButton(array('label'=>t("guifi-dnss_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web'));
		}
		else {
			$page .= "<div class='alert alert-success text-center'>".t("guifi-dnss_alert_guifi")."</div>\n";
		}
	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function install(){

	global $DNSS_PLUG, $DNSS_DIR, $DNSS_CONF, $DNSS_PKGNAME, $DNSS_DEFAULTS, $staticFile, $dnsservices_undefined_variables;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-dnss_common_appname"));
	$page .= hl(t("guifi-dnss_install_subtitle"),4);

	$buttons .= addButton(array('label'=>t("guifi-dnss_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/'.$DNSS_PLUG));

	if (!isPackageInstall($DNSS_PKGNAME)) {
		if (!CloudyRegistrationFull()) {
			$page .= par(t("guifi-dnss_install_declare").' '.t("guifi-dnss_index_checkwiki").' '.'<a href="'.t("guifi-dnss_index_wikiurl").'">'.t("guifi-dnss_index_wikiurl").'</a>');
			$page .= dnsservices_form($DNSS_DIR.$DNSS_CONF,$DNSS_DEFAULTS);
			$buttons .= addButton(array('label'=>t("guifi-dnss_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web'));
			$buttons .= addSubmit(array('label'=>t("guifi-dnss_button_unregistered_pre").t("guifi-dnss_common_appname").t("guifi-dnss_button_unregistered_post"),'class'=>'btn btn-default'));
		}

		else {

			if (!serviceDeclared($DNSS_PKGNAME)) {
				$page .= par(t("guifi-dnss_install_declare").' '.t("guifi-dnss_install_autodeclare").' '.t("guifi-dnss_install_otherwise"));
				$page .= dnsservices_form($DNSS_DIR.$DNSS_CONF,$DNSS_DEFAULTS);
				$buttons .= addButton(array('label'=>t("guifi-dnss_button_create_service"),'class'=>'btn btn-success', 'href'=>$staticFile.'/'.$DNSS_PLUG.'/createservice/'.$DNSS_PKGNAME));
				$buttons .= addSubmit(array('label'=>t("guifi-dnss_button_unregistereds_pre").t("guifi-dnss_common_appname").t("guifi-dnss_button_unregistereds_post"),'class'=>'btn btn-default'));
			}

			else {
				$page .= par(t("guifi-dnss_install_declared_pre").t("guifi-dnss_common_appname").t("guifi-dnss_install_declared_post"));
				$page .= dnsservices_form($DNSS_DIR.$DNSS_CONF,$DNSS_DEFAULTS);
				$page .= par(t("guifi-dnss_install_value"));
				$buttons .= addSubmit(array('label'=>t("guifi-dnss_button_sinstall_pre").t("guifi-dnss_common_appname").t("guifi-dnss_button_sinstall_post"),'class'=>'btn btn-success'));
			}

		}
	}

	else {
		$page .= par(t("guifi-dnss_install_configure_pre").t("guifi-dnss_common_appname").t("guifi-dnss_install_configure_post"));
		$page .= dnsservices_form($DNSS_DIR.$DNSS_CONF,$DNSS_DEFAULTS);
		$buttons .= addSubmit(array('label'=>t("guifi-dnss_button_save"),'class'=>'btn btn-primary'));
	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));

}

function install_post(){

	global $DNSS_PLUG, $DNSS_DIR, $DNSS_CONF, $DNSS_PKGNAME, $DNSS_DEFAULTS, $staticFile, $dnsservices_undefined_variables;

	$page = "";

	$datesToSave = array();
	foreach ($_POST as $key => $value) {
		$datesToSave[$key] = $value;
	}

	if (!isPackageInstall($DNSS_PKGNAME)) {
		if (($define_variables = package_default_variables($datesToSave,$DNSS_DEFAULTS, $DNSS_PKGNAME, $dnsservices_undefined_variables)) != ""){
			$page .= "<div class='alert'><pre>".$define_variables."</pre></div>";
		}
		$page .= package_not_install($DNSS_PKGNAME,t("guifi-dnss_common_desc"));
	}

	else {
		//Canviar el fitxer de configuració
		foreach ($datesToSave as $key => $value) {
			if($DNSS_DEFAULTS[$key]['kdeb'] == 'string'){
				$datesToSave[$key] = "'".$value."'";
			}
		}

		write_merge_conffile($DNSS_DIR.$DNSS_CONF,$datesToSave);
		setFlash(t("guifi-dnss_alert_save"),"success");

		return(array('type' => 'redirect', 'url' => $staticFile.'/guifi-dnss'));
	}

	return(array('type' => 'render','page' => $page));
}


function dnsservices_form($file,$options){
	global $staticFile, $GUIFI_WEB, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE, $DNSS_PKGNAME, $services_types;

	$page = "";

	$webinfo = _getServiceInformation($services_types[$DNSS_PKGNAME]['name']);
	$variables = load_singlevalue($file,$options);

	if (($variables['DNSGraphServerId'] == -1) && (isset($webinfo['id']))) {
		$variables['DNSGraphServerId'] = $webinfo['id'];
	}

	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($options as $op=>$val)
		$page .= addInput($op, $val['desc'], $variables, $val['options'], '', $val['tooltip']);

	return($page);
}


function serviceStarted(){

global $DNSS_INITD;

	if (strpos(shell_exec("$DNSS_INITD status"),'is running') != false)
		return 1;
	else
		return 0;
}

function start(){

global $DNSS_INITD, $staticFile, $DNSS_PLUG;

		setFlash(shell_exec("$DNSS_INITD start"));
		return(array('type'=> 'redirect', 'url' => $staticFile.'/'.$DNSS_PLUG));
}

function stop(){

global $DNSS_INITD, $staticFile, $DNSS_PLUG;

		setFlash(shell_exec("$DNSS_INITD stop"));
		return(array('type'=> 'redirect', 'url' => $staticFile.'/'.$DNSS_PLUG));
}
