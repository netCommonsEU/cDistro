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