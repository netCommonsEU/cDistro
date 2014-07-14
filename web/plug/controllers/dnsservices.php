<?php
//plug/controllers/wifi/dnsservices.php

$dnsservices_files="/etc/dnsservices/config.php";
$dnsservices_initd="/etc/init.d/bind9";
$dnsservices_name="Guifi DNSServices";
$dnsservices_pkg="dnsservices";
$dnsservices_plug="dnsservices";
$dnsservices_variables=array('DNSGraphServerId' => array('default' => '0',
												'desc' => t('dnsservices_form_service_id_label'),
												'vdeb' => 'dnsservices/DNSGraphServerId',
												'help' => t('dnsservices_form_service_id_help'),
												'kdeb' => 'string'),
							 'DNSDataServer_url' => array('default' => 'http://guifi.net',
												'desc' => t('dnsservices_form_url_label'),
												'vdeb' => 'dnsservices/DNSDataServerurl',
												'help' => t('dnsservices_form_url_help'),
												'kdeb' => 'string')
					    );

$dnsservices_undefined_variables=array(array('vdeb'=> 'dnsservices/forcefetch',
										     'kdeb' => 'boolean',
										     'default' => 'false'
										     )
									);

function index(){

	global $dnsservices_name, $dnsservices_plug, $dnsservices_files, $dnsservices_pkg, $dnsservices_variables, $staticFile, $dnsservices_undefined_variables;

	$page = "";
	$buttons = "";

	$page .= hl($dnsservices_name);
	$page .= hl(t("dnsservices_shortdesc"),4);
	$page .= par(t("dnsservices_desc"));

	if (!isPackageInstall($dnsservices_pkg)){
		$page .= "<div class='alert alert-error text-center'>".t("dnsservices_not_installed")."</div>\n";
		$page .= par(t("dnsservices_click_to_install"));
		$buttons .= addButton(array('label'=>t("dnsservices_install_button"),'class'=>'btn btn-success', 'href'=>$staticFile.'/'.$dnsservices_plug.'/install'));
		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}
	else {
		if (dnsservicesStarted()){
			$page .= "<div class='alert alert-success text-center'>".t("dnsservices_running")."</div>\n";
			$buttons .= addButton(array('label'=>t("dnsservices_button_stop"),'class'=>'btn btn-warning', 'href'=>'./dnsservices/stop'));
			$buttons .= addButton(array('label'=>t("dnsservices_button_configure"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/'.$dnsservices_plug.'/install'));
			}
		else {
			$page .= "<div class='alert alert-warning text-center'>".t("dnsservices_not_running")."</div>\n";
			$buttons .= addButton(array('label'=>t("dnsservices_button_start"),'class'=>'btn btn-success', 'href'=>'./dnsservices/start'));
			$buttons .= addButton(array('label'=>t("dnsservices_button_configure"),'class'=>'btn btn-primary', 'href'=>$staticFile.'/'.$dnsservices_plug.'/install'));
			$buttons .= addButton(array('label'=>t("dnsservices_button_uninstall"),'class'=>'btn btn-danger', 'href'=>$staticFile.'/'.$dnsservices_plug.'/uninstall'));
			}

		$page .= $buttons;
		return(array('type' => 'render','page' => $page));
	}

}

function install_get(){

	global $dnsservices_name, $dnsservices_plug, $dnsservices_files, $dnsservices_pkg, $dnsservices_variables, $staticFile, $dnsservices_undefined_variables;

	$page = "";
	$buttons = "";

	$page .= hl($dnsservices_name);
	$page .= hl(t("dnsservices_shortdesc"),4);
	$page .= par(t("dnsservices_install_desc1"));
	$page .= par(t("dnsservices_install_desc2").'<a href="'.t("dnsservices_install_wiki").'">'.t("dnsservices_install_wiki").'</a>');

	$page .= dnsservices_form($dnsservices_files,$dnsservices_variables);

	return(array('type' => 'render','page' => $page));

}

function install_post(){

	global $dnsservices_name, $dnsservices_plug, $dnsservices_files, $dnsservices_pkg, $dnsservices_variables, $staticFile, $dnsservices_undefined_variables;

	$datesToSave = array();
	foreach ($_POST as $key => $value) {
		$datesToSave[$key] = $value;
	}

	if (($define_variables = package_default_variables($datesToSave,$dnsservices_variables, $dnsservices_pkg,$dnsservices_undefined_variables)) != ""){
		$page .= "<div class='alert'><pre>".$define_variables."</pre></div>";
	}

	$page = "";
	$page .= hl($dnsservices_name);
	$page .= hl(t("dnsservices_shortdesc"),4);

		if (!isPackageInstall($dnsservices_pkg)){
			$pkgInstall = ptxt(installPackage($dnsservices_pkg));

		if (isPackageInstall($dnsservices_pkg)){
			$page .= txt(t("dnsservices_installation_result"));
			$page .= "<div class='alert alert-success text-center'>".t("dnsservices_installation_successful")."</div>\n";

			$page .= txt(t("dnsservices_installation_details"));
			$page .= $pkgInstall;

		//Afegir fitxer de configuració
		foreach ($datesToSave as $key => $value) {
			if($dnsservices_variables[$key]['kdeb'] == 'string'){
				$datesToSave[$key] = '"'.$value.'"';
				}
			}

		write_merge_conffile($dnsservices_files,$datesToSave);
			$page .= txt(t("dnsservices_postinstallation_result"));
			$page .= ptxt(t("dnsservices_configuration_successful"));

		$page .= addButton(array('label'=>t('dnsservices_button_back'),'class'=>'btn', 'href'=>$staticFile.'/'.$dnsservices_pkg));

		return(array('type' => 'render','page' => $page));
		}
	}


		else {
		//Afegir fitxer de configuració
		foreach ($datesToSave as $key => $value) {
			if($dnsservices_variables[$key]['kdeb'] == 'string'){
				$datesToSave[$key] = '"'.$value.'"';
				}
			}

		write_merge_conffile($dnsservices_files,$datesToSave);
			$page .= txt(t("dnsservices_postinstallation_result"));
			$page .= ptxt(t("dnsservices_configuration_successful"));

		$page .= addButton(array('label'=>t('dnsservices_button_back'),'class'=>'btn', 'href'=>$staticFile.'/'.$dnsservices_pkg));

		return(array('type' => 'render','page' => $page));
	}

}


function dnsservices_form($file,$options){
	global $dnsservices_name, $dnsservices_plug, $dnsservices_files, $dnsservices_pkg, $dnsservices_variables, $staticFile, $dnsservices_undefined_variables, $debug;

	$page = "";
	$buttons = "";

	$variables = load_singlevalue($file,$options);

	foreach ($variables as $key => $value) {
		if ( substr($value,0,1) == '"' && substr($value,-1,1) == '"' )
			$variables[$key] = substr($value, 1, -1);

	}

	if($debug) { echo "<pre>"; print_r($variables); echo "</pre>"; }

	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($options as $op=>$val){
		$page .= addInput($op,$val['desc'],$variables,'','',$val['help']);
	}

	if (!isPackageInstall($dnsservices_pkg))
		$page .= addSubmit(array('label'=>t('dnsservices_install_configure_button'),'class'=>'btn btn-primary'));
	else
		$page .= addSubmit(array('label'=>t('dnsservices_button_reconfigure'),'class'=>'btn btn-primary'));

	return($page);

}

function uninstall(){

	global $dnsservices_name, $dnsservices_plug, $dnsservices_files, $dnsservices_pkg, $dnsservices_variables, $staticFile, $dnsservices_undefined_variables;

  $page = "";
	$page .= hl($dnsservices_name);
	$page .= hl(t("dnsservices_shortdesc"),4);

	if (isPackageInstall($dnsservices_pkg) && !dnsservicesStarted()){
		$pkgUninstall = ptxt(uninstallPackage($dnsservices_pkg));

	$page .= txt(t("dnsservices_uninstallation_result"));

	if (!isPackageInstall($dnsservices_pkg))
		$page .= "<div class='alert alert-success text-center'>".t("dnsservices_uninstallation_successful")."</div>\n";
	else
	  $page .= "<div class='alert alert-error text-center'>".t("dnsservices_uninstallation_unsuccessful")."</div>\n";

	$page .= txt(t("dnsservices_uninstallation_details"));
	$page .= $pkgUninstall;

	$page .= addButton(array('label'=>t('dnsservices_button_back'),'class'=>'btn', 'href'=>$staticFile.'/'.$dnsservices_pkg));

	return(array('type' => 'render','page' => $page));
	}
	else if (!isPackageInstall($dnsservices_pkg)){
	$page .= "<div class='alert alert-error text-center'>".t("dnsservices_not_installed")."</div>\n";

	$page .= addButton(array('label'=>t('dnsservices_button_back'),'class'=>'btn', 'href'=>$staticFile.'/'.$dnsservices_pkg));

	return(array('type' => 'render','page' => $page));}
}



function dnsservicesStarted(){

global $dnsservices_initd;

		if (strpos(shell_exec("$dnsservices_initd status"),'is running') != false)
		return 1;
	else
		return 0;
}

function start(){

global $dnsservices_initd, $staticFile;

		setFlash(shell_exec("$dnsservices_initd start"));
		return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'dnsservices'));
}

function stop(){

global $dnsservices_initd, $staticFile;

		setFlash(shell_exec("$dnsservices_initd stop"));
		return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'dnsservices'));
}