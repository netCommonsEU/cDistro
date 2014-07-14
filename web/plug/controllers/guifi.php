<?php
//plug/controllers/guifi.php

$guifi_proxy3_file="/etc/guifi-proxy3/config.sh";
$guifi_proxy3_pkg="guifi-proxy3";
$guifi_proxy3_variables = array(
	'base_url'=> array('default'=>'http://www.guifi.net',
		'desc'=>t('Server URL Base'),
		'vdeb'=>'guifi-proxy3/baseurl',
		'kdeb'=>'string'),
	'node' =>  array('default'=>'0',
		'desc'=>t('Guifi Proxy node number'),
		'vdeb'=>'guifi-proxy3/node',
		'kdeb'=>'string'),
	'ldap_main' =>  array('default'=>'ldap.guifi.net',
		'desc'=>t('Main Server Ldap'),
		'vdeb'=>'guifi-proxy3/ldap_main',
		'kdeb'=>'string'),
	'ldap_backup' =>  array('default'=>'ldap2.guifi.net',
		'desc'=>t('Main Server Ldap2'),
		'vdeb'=>'guifi-proxy3/ldap_backup',
		'kdeb'=>'string'),
	'realm' =>  array('default'=>'Guifi-server Squid proxy-caching web server',
		'desc'=>t('Proxy Welcome Message'),
		'vdeb'=>'guifi-proxy3/proxy_name',
		'kdeb'=>'string'),
	'manager' =>  array('default'=>'webmaster',
		'desc'=>t('Contact email proxy server'),
		'vdeb'=>'guifi-proxy3/email',
		'kdeb'=>'string'),
	'language' =>  array('default'=>'Catalan',
		'desc'=>t('Choosemanager a language for error pages generated'),
		'vdeb'=>'guifi-proxy3/language',
		'kdeb'=>'string'),
	'cache_size' =>  array('default'=>'10240',
		'desc'=>t('Disk cache space (MB)'),
		'vdeb'=>'guifi-proxy3/hd',
		'kdeb'=>'string'),
	'cache_mem' =>  array('default'=>'100',
		'desc'=>t('Ram cache space (MB)'),
		'vdeb'=>'guifi-proxy3/ram',
	'kdeb'=>'string')
				   );
$guifi_proxy3_desc = t("This software provides a federated proxy service in the context of Guifi.net");


// PROXY3
function proxy3_form($file,$options){
	$page = "";

	$variables = load_conffile($file,$options);
	$page .= hl(t("Guifi Proxy3"));
	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($options as $op=>$val){
		$page .= addInput($op,$val['desc'],$variables);
	}

	$page .= addSubmit(array('label'=>t('Executar')));

	return($page);

}
function proxy3_get(){
	global $guifi_proxy3_file, $guifi_proxy3_pkg, $guifi_proxy3_desc, $guifi_proxy3_variables, $staticFile;

	$page = proxy3_form($guifi_proxy3_file,$guifi_proxy3_variables);
	if (isPackageInstall($guifi_proxy3_pkg)){
		$page .= addButton(array('label'=>t('Uninstall package'),'class'=>'btn btn-success', 'href'=>$staticFile.'/default/uninstall/'.$guifi_proxy3_pkg));
	}
	return(array('type' => 'render','page' => $page));

}

function proxy3_post(){
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


$snpservices_files="/etc/snpservices/config.php";
$snpservices_pkg="snpservices";
$snpservices_desc=t("This software provides graphing services in the context of Guifi.net");
$snpservices_variables=array('SNPGraphServerId' => array('default' => '0',
												'desc' => t('Service ID'),
												'vdeb' => 'snpservices/SNPGraphServerId',
												'kdeb' => 'string')
					    );

$snpservices_undefined_variables=array(array('debpkg' => 'mrtg',
											 'vdeb'=> 'mrtg/conf_mods',
										     'kdeb' => 'boolean',
										     'default' => 'true'
										     )
									);

// SNPSERVICES
function snpservices_form($file,$options){
	$page = "";


	$variables = load_singlevalue($file,$options);
	$page .= hlc(t("Guifi SNPServices"));
	$page .= hl(t("Monitorization and graphing tools for Guifi.net nodes"),4);

	$page .= par(t("SNPServices is a set of tools to capture the status of the Guifi.net network nodes in your area that are registered with this server.").' '.t("The web server at www.guifi.net tells your server which nodes to monitor and asks for the graphs via a web interface.").' '.t("These graphs are then visible on the Guifi.net website."));

	$page .= par(t("Before setting up this service, you should have added it to your node at Guifi.net's website.").' '.t("You can check this wiki page for more information:").' '.'<a href="'.t("http://en.wiki.guifi.net/wiki/Graphs_server").'">'.t("http://en.wiki.guifi.net/wiki/Graphs_server").'</a>');

	$page .= par(t("To run this service, the machine has to be connected to both Guifi and the Internet."));


	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($options as $op=>$val){
		$page .= addInput($op,$val['desc'],$variables,'','',t("The ID number of the service at Guifi.net website (e.g. http://guifi.net/node/<strong>123456</strong>)"));
	}

	$page .= addSubmit(array('label'=>t("Save and apply configuration"),'class'=>'btn btn-primary'));

	return($page);

}
function snpservices_get(){

	global $snpservices_files, $snpservices_pkg, $snpservices_desc, $snpservices_variables, $staticFile;

	$page = snpservices_form($snpservices_files,$snpservices_variables);
	if (isPackageInstall($snpservices_pkg)){
		$page .= addButton(array('label'=>t('Uninstall package'),'class'=>'btn btn-success', 'href'=>$staticFile.'/default/uninstall/'.$snpservices_pkg));
	}
	return(array('type' => 'render','page' => $page));

}

function snpservices_post(){

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
		return(array('type' => 'redirect', 'url' => $staticFile.'/guifi/snpservices'));
	}
	return(array('type' => 'render','page' => $page));
}


// DNSSERVICES

$dnsservices_files="/etc/dnsservices/config.php";
$dnsservices_pkg="dnsservices";
$dnsservices_desc=t("This software provides DNS services in the context of Guifi.net");
$dnsservices_variables=array('DNSGraphServerId' => array('default' => '0',
												'desc' => t('Select your DNS Server Id to share your domains'),
												'vdeb' => 'dnsservices/DNSGraphServerId',
												'kdeb' => 'string'),
							 'DNSDataServer_url' => array('default' => 'http://guifi.net',
												'desc' => t('Url from DNSDataServer (without ending backslash)'),
												'vdeb' => 'dnsservices/DNSDataServerurl',
												'kdeb' => 'string')
					    );

$dnsservices_undefined_variables=array(array('vdeb'=> 'dnsservices/forcefetch',
										     'kdeb' => 'boolean',
										     'default' => 'false'
										     )
									);

function dnsservices_form($file,$options){
	global $debug;

	$page = "";

	$variables = load_singlevalue($file,$options);
	if($debug) { echo "<pre>"; print_r($variables); echo "</pre>"; }
	$page .= hl(t("Guifi DNSServices"));
	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($options as $op=>$val){
		$page .= addInput($op,$val['desc'],$variables);
	}

	$page .= addSubmit(array('label'=>'Executar'));

	return($page);

}
function dnsservices_get(){



	global $dnsservices_files, $dnsservices_pkg, $dnsservices_desc, $dnsservices_variables, $staticFile;

	$page = dnsservices_form($dnsservices_files,$dnsservices_variables);
	if (isPackageInstall($dnsservices_pkg)){
		$page .= addButton(array('label'=>t('Uninstall package'),'class'=>'btn btn-success', 'href'=>$staticFile.'/default/uninstall/'.$dnsservices_pkg));
	}
	return(array('type' => 'render','page' => $page));

}

function dnsservices_post(){

	global $dnsservices_files, $dnsservices_pkg, $dnsservices_desc, $dnsservices_variables, $staticFile, $dnsservices_undefined_variables;

	$page = "";

	$datesToSave = array();
	foreach ($_POST as $key => $value) {
		$datesToSave[$key] = $value;
	}
	if (!isPackageInstall($dnsservices_pkg)){
		if (($define_variables = package_default_variables($datesToSave,$dnsservices_variables, $dnsservices_pkg,$dnsservices_undefined_variables)) != ""){
			$page .= "<div class='alert'><pre>".$define_variables."</pre></div>";
		}
		$page .= package_not_install($dnsservices_pkg,$dnsservices_desc);
	} else {
		//Canviar el fitxer de configuració
		foreach ($datesToSave as $key => $value) {
			if($dnsservices_variables[$key]['kdeb'] == 'string'){
				$datesToSave[$key] = "'".$value."'";
			}
		}
		write_merge_conffile($dnsservices_files,$datesToSave);
		setFlash("Save it!","success");
		return(array('type' => 'redirect', 'url' => $staticFile.'/guifi/dnsservices'));
	}
	return(array('type' => 'render','page' => $page));
}
