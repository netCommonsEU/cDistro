<?php
//plug/controllers/guifi-snps.php

$snpservices_files="/etc/snpservices/config.php";
$snpservices_desc=t("guifi-snps_common_desc");
$SNPS_PKGNAME="snpservices";
$SNPS_DEFAULTS = array (
	'SNPGraphServerId' => array (
		'default' => '-1',
		'desc' => t('guifi-snps_form_id'),
		'vdeb' => 'snpservices/SNPGraphServerId',
		'kdeb' => 'string',
		'options'=>array('type'=>'number', 'required'=>true, 'min'=>0),
		'tooltip'=>t("guifi-snps_form_id_tooltip")
	)
);

$snpservices_undefined_variables=array (
	array(
		'debpkg' => 'mrtg',
		'vdeb'=> 'mrtg/conf_mods',
		'kdeb' => 'boolean',
		'default' => 'true'
	)
);

function index() {
	global $SNPS_PKGNAME, $staticFile;
	$buttons = '';
	$page = '';
	$GUIFI_CONF = '';

	$page .= hlc(t("guifi-snps_common_appname"));
	$page .= hl(t("guifi-snps_index_subtitle"),4);

	$page .= par(t("guifi-snps_index_description1").' '.t("guifi-snps_index_description2").' '.t("guifi-snps_index_description3"));

	$page .= par(t("guifi-snps_index_connected").' '.t("guifi-snps_index_checkwiki").' '.'<a href="'.t("guifi-snps_index_wikiurl").'">'.t("guifi-snps_index_wikiurl").'</a>');

	$page .= txt(t("guifi-snps_common_status_pre").t("guifi-snps_common_appname").t("guifi-snps_common_status_post"));
	if (!isPackageInstall($SNPS_PKGNAME)){
		$page .= "<div class='alert alert-error text-center'>".t("guifi-snps_alert_not_installed_pre").t("guifi-snps_common_appname").t("guifi-snps_alert_not_installed_post")."</div>\n";
		$page .= txt(t("guifi-snps_common_guifi:"));

		if ( !cloudyRegistrationFull() ) {
			$page .= "<div class='alert alert-error text-center'>".t("guifi-snps_alert_not_guifi")."</div>\n";
			$page .= par(t("guifi-snps_index_not_guifi").' '.t("guifi-snps_index_register_before"));
			$buttons .= addButton(array('label'=>t("guifi-snps_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web'));
			$buttons .= addButton(array('label'=>t("guifi-snps_button_unregistered_pre").t("guifi-snps_common_appname").t("guifi-snps_button_unregistered_post"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-snps/install'));
		}

		else {
			$page .= "<div class='alert alert-success text-center'>".t("guifi-snps_alert_guifi")."</div>\n";
			$page .= par(t("guifi-snps_index_guifi_pre").t("guifi-snps_common_appname").t("guifi-snps_index_guifi_post"));
			$buttons .= addButton(array('label'=>t("guifi-snps_button_install_pre").t("guifi-snps_common_appname").t("guifi-snps_button_install_post"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-snps/install'));
		}
	}

	else {
		$page .= "<div class='alert alert-success text-center'>".t("guifi-snps_alert_installed_pre").t("guifi-snps_common_appname").t("guifi-snps_alert_installed_post")."</div>\n";
		$page .= txt(t("guifi-snps_common_guifi:"));

		if ( !cloudyRegistrationFull() ) {
			$page .= "<div class='alert alert-warning text-center'>".t("guifi-snps_alert_not_guifi")."</div>\n";
			$page .= par(t("guifi-snps_index_not_guifi").' '.t("guifi-snps_index_register"));
			$buttons .= addButton(array('label'=>t("guifi-snps_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web'));
			$buttons .= addButton(array('label'=>t("guifi-snps_button_manage_pre").t("guifi-snps_common_appname").t("guifi-snps_button_manage_post"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/guifi-snps/install'));
		}

		else {
			$page .= "<div class='alert alert-success text-center'>".t("guifi-snps_alert_guifi")."</div>\n";
			$buttons .= addButton(array('label'=>t("guifi-snps_button_manage_pre").t("guifi-snps_common_appname").t("guifi-snps_button_manage_post"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/guifi-snps/install'));
		}
	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}


function install(){
	global $snpservices_files, $SNPS_PKGNAME, $snpservices_desc, $SNPS_DEFAULTS, $staticFile;

	$page = '';
	$buttons = '';

	$page .= hlc(t("guifi-snps_common_appname"));
	$page .= hl(t("guifi-snps_install_subtitle"),4);

	$buttons .= addButton(array('label'=>t("guifi-snps_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-snps'));

	if (!isPackageInstall($SNPS_PKGNAME)) {
		if (!CloudyRegistrationFull()) {
			$page .= par(t("guifi-snps_install_declare").' '.t("guifi-snps_index_checkwiki").' '.'<a href="'.t("guifi-snps_index_wikiurl").'">'.t("guifi-snps_index_wikiurl").'</a>');
			$page .= snpservices_form($snpservices_files,$SNPS_DEFAULTS);
			$buttons .= addButton(array('label'=>t("guifi-snps_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web'));
			$buttons .= addSubmit(array('label'=>t("guifi-snps_button_unregistered_pre").t("guifi-snps_common_appname").t("guifi-snps_button_unregistered_post"),'class'=>'btn btn-default'));
		}

		else {

			if (!serviceDeclared($SNPS_PKGNAME)) {
				$page .= par(t("guifi-snps_install_declare").' '.t("guifi-snps_install_autodeclare").' '.t("guifi-snps_install_otherwise"));
				$page .= snpservices_form($snpservices_files,$SNPS_DEFAULTS);
				$buttons .= addButton(array('label'=>t("guifi-snps_button_create_service"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-snps/createservice/snpservices'));
				$buttons .= addSubmit(array('label'=>t("guifi-snps_button_unregistereds_pre").t("guifi-snps_common_appname").t("guifi-snps_button_unregistereds_post"),'class'=>'btn btn-default'));
			}

			else {
				$page .= par(t("guifi-snps_install_declared_pre").t("guifi-snps_common_appname").t("guifi-snps_install_declared_post"));
				$page .= snpservices_form($snpservices_files,$SNPS_DEFAULTS);
				$page .= par(t("guifi-snps_install_value"));
				$buttons .= addSubmit(array('label'=>t("guifi-snps_button_sinstall_pre").t("guifi-snps_common_appname").t("guifi-snps_button_sinstall_post"),'class'=>'btn btn-success'));
			}

		}
	}
	else {
		$page .= par(t("guifi-snps_install_configure_pre").t("guifi-snps_common_appname").t("guifi-snps_install_configure_post"));
		$page .= snpservices_form($snpservices_files,$SNPS_DEFAULTS);
		$buttons .= addSubmit(array('label'=>t("guifi-snps_button_save"),'class'=>'btn btn-primary'));
		$buttons .= addButton(array('label'=>t("guifi-snps_button_uninstall_pre").t("guifi-snps_common_appname").t("guifi-snps_button_uninstall_post"),'class'=>'btn btn-danger', 'href'=>$staticFile.'/default/uninstall/'.$SNPS_PKGNAME));
	}


	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}


function install_post(){

	global $snpservices_files, $SNPS_PKGNAME, $snpservices_desc, $SNPS_DEFAULTS, $staticFile, $snpservices_undefined_variables;

	$page = "";

	$datesToSave = array();
	foreach ($_POST as $key => $value) {
		$datesToSave[$key] = $value;
	}

	if (!isPackageInstall($SNPS_PKGNAME)) {
		if (($define_variables = package_default_variables($datesToSave,$SNPS_DEFAULTS, $SNPS_PKGNAME, $snpservices_undefined_variables)) != ""){
			$page .= "<div class='alert'><pre>".$define_variables."</pre></div>";
		}
		$page .= package_not_install($SNPS_PKGNAME,$snpservices_desc);
	}

	else {
		//Canviar el fitxer de configuració
		foreach ($datesToSave as $key => $value) {
			if($SNPS_DEFAULTS[$key]['kdeb'] == 'string'){
				$datesToSave[$key] = "'".$value."'";
			}
		}

		write_merge_conffile($snpservices_files,$datesToSave);
		setFlash(t("guifi-snps_alert_save"),"success");

		return(array('type' => 'redirect', 'url' => $staticFile.'/guifi-snps'));
	}

	return(array('type' => 'render','page' => $page));
}

function snpservices_form($file,$options){
	global $staticFile, $GUIFI_WEB, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE,$services_types;

	$buttons = "";
	$page = "";

	$webinfo = _getServiceInformation($services_types['snpservices']['name']);
	$variables = load_singlevalue($file,$options);

	if (($variables['SNPGraphServerId'] == -1) && (isset($webinfo['id']))) {
		$variables['SNPGraphServerId'] = $webinfo['id'];
	}

	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($options as $op=>$val)
		$page .= addInput($op, $val['desc'], $variables, $val['options'], '', $val['tooltip']);

	$page .= $buttons;

	return($page);

}


?>