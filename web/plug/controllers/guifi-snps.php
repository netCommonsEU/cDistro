<?php
//plug/controllers/guifi-snps.php

$snpservices_files="/etc/snpservices/config.php";
$snpservices_pkg="snpservices";
$snpservices_desc=t("This software provides graphing services in the context of Guifi.net");
$snpservices_variables=array (
	'SNPGraphServerId' => array (
		'default' => '0',
		'desc' => t('Service ID'),
		'vdeb' => 'snpservices/SNPGraphServerId',
		'kdeb' => 'string'
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

function snpservices_form($file,$options){
	global $staticFile, $GUIFI_WEB, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE,$services_types;

	$buttons = "";
	$page = "";


	$webinfo = _getServiceInformation($services_types['snpservices']['name']);
	$variables = load_singlevalue($file,$options);

	if (($variables['SNPGraphServerId'] == 0) && (isset($webinfo['id']))) {
		$variables['SNPGraphServerId'] = $webinfo['id'];
	}

	$page .= hlc(t("Guifi SNPServices"));
	$page .= hl(t("Monitorization and graphing tools for Guifi.net nodes"),4);

	$page .= par(t("SNPServices is a set of tools to capture the status of the Guifi.net network nodes in your area that are registered with this server.").' '.t("The web server at www.guifi.net tells your server which nodes to monitor and asks for the graphs via a web interface.").' '.t("These graphs are then visible on the Guifi.net website."));

	$page .= par(t("Before setting up this service, you should have added it to your node at Guifi.net's website.").' '.t("You can check this wiki page for more information:").' '.'<a href="'.t("http://en.wiki.guifi.net/wiki/Graphs_server").'">'.t("http://en.wiki.guifi.net/wiki/Graphs_server").'</a>');

	$page .= par(t("To run this service, the machine has to be connected to both Guifi and the Internet."));


	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($options as $op=>$val){
		$page .= addInput($op,$val['desc'],$variables,'','',t("The ID number of the service at Guifi.net website (e.g. http://guifi.net/node/<strong>123456</strong>)"));
		if ($op == 'SNPGraphServerId' && $variables['SNPGraphServerId'] == 0 && file_exists($GUIFI_CONF_DIR.$GUIFI_CONF_FILE)) {
			// Crear-lo automàticament?

			$GUIFI=load_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE);
			if (isset($GUIFI['DEVICEID'])){
				$page .= par(t("guifi-snps_create_service"));
				$buttons .= addButton(array('label'=>t("guifi-snps_button_create_service"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi/createservice/snpservices'));
				$page .= addSubmit(array('label'=>t("guifi-snps_button_unregistereds"),'class'=>'btn btn-default'));
			} else {
				$page .= addSubmit(array('label'=>t("Save and apply configuration"),'class'=>'btn btn-success'));

			}
			$page .= par($bcreate);
		}

		else {
			$page .= par(t("guifi-snps_register_cloudy"));
			$page .= addSubmit(array('label'=>t("guifi-snps_button_unregistered"),'class'=>'btn btn-default'));
			$buttons .= addButton(array('label'=>t("guifi-snps_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web'));
		}


	}

	$page .= $buttons;

	return($page);

}

function index_get(){

	global $snpservices_files, $snpservices_pkg, $snpservices_desc, $snpservices_variables, $staticFile;

	$page = snpservices_form($snpservices_files,$snpservices_variables);
	if (isPackageInstall($snpservices_pkg)){
		$page .= addButton(array('label'=>t('Uninstall package'),'class'=>'btn btn-danger', 'href'=>$staticFile.'/default/uninstall/'.$snpservices_pkg));
	}
	return(array('type' => 'render','page' => $page));

}

function index_post(){

	global $snpservices_files, $snpservices_pkg, $snpservices_desc, $snpservices_variables, $staticFile, $snpservices_undefined_variables;

	$page = "";

	$datesToSave = array();
	foreach ($_POST as $key => $value) {
		$datesToSave[$key] = $value;
	}

	if (!isPackageInstall($snpservices_pkg)){
		if (($define_variables = package_default_variables($datesToSave,$snpservices_variables, $snpservices_pkg, $snpservices_undefined_variables)) != ""){
			$page .= "<div class='alert'><pre>".$define_variables."</pre></div>";
		}
		$page .= package_not_install($snpservices_pkg,$snpservices_desc);
	} else {
		//Canviar el fitxer de configuració
		foreach ($datesToSave as $key => $value) {
			if($snpservices_variables[$key]['kdeb'] == 'string'){
				$datesToSave[$key] = "'".$value."'";
			}
		}
		write_merge_conffile($snpservices_files,$datesToSave);
		setFlash(t("Save it")."!","success");
		return(array('type' => 'redirect', 'url' => $staticFile.'/guifi-snps'));
	}
	return(array('type' => 'render','page' => $page));
}
?>