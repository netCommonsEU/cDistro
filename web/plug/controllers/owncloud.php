<?php
// owncloud.php

$urlpath="owncloud";
$OWNCLOUD_PKGNAME="owncloud";

function index() {

	global $urlpath, $staticFile, $staticPath, $OWNCLOUD_PKGNAME;

	$page = generateHeader();
	$buttons = "";

	$page .= par(t("owncloud_index_description1").' '.t("owncloud_index_description2"));
	$page .= par(t("owncloud_index_description3").' '.'<a href="'.t("owncloud_index_url").'">'.t("owncloud_index_url").'</a>');

	$page .= txt(t("owncloud_common_status_pre").' '.t("owncloud_common_title").' '.t("owncloud_common_status_post"));
	if (!isPackageInstall($OWNCLOUD_PKGNAME)){
			$page .= "<div class='alert alert-error text-center'>".t("owncloud_alert_not_installed_pre").' '.t("owncloud_common_title").' '.t("owncloud_alert_not_installed_post")."</div>\n";
			$page .= par(t("owncloud_index_clickinstall_pre").t("owncloud_common_title").t("owncloud_index_clickinstall_post").'</a>');
			$buttons .= addButton(array('label'=>t("owncloud_button_install_pre").t("owncloud_common_title").t("owncloud_button_install_post"),'class'=>'btn btn-success', 'href'=>$staticPath.$urlpath.'/install'));
	}

	$page .= $buttons;
	return(array('type'=>'render','page'=>$page));
}


function install(){

	global $urlpath, $staticFile, $staticPath, $OWNCLOUD_PKGNAME;

	$page = generateHeader();
	$buttons = "";

	$buttons .= addButton(array('label'=>t("owncloud_button_back"),'class'=>'btn btn-default', 'href'=>$staticPath.$urlpath));

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

function generateHeader() {

	$header = hlc(t("owncloud_common_title"));
	$header .= hl(t("owncloud_common_subtitle"),4);

	return $header;
}

?>