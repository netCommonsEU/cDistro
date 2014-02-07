<?php
//plug/controllers/guifi.php

$guifi_proxy3_file="/etc/guifi-proxy3/config.sh";
$guifi_proxy3_pkg="guifi-proxy3";
$guifi_proxy3_defconfig = array('base_url'=>'http://www.guifi.net',
				   'node' => '0',
				   'ldap_main' => 'ldap.guifi.net',
				   'ldap_backup' => 'ldap2.guifi.net',
				   'realm' => 'Guifi-server Squid proxy-caching web server',
				   'manager' => 'webmaster',
				   'language' => 'Catalan',
				   'cache_size' => '10240',
				   'cache_mem' => '100'
				   );
$guifi_proxy3_desc = "Guifi.net Proxy federation system.";


// PROXY3
function proxy3_form($file,$options){
	$page = "";

	$variables = load_conffile($file);
	$page .= hl("Guifi Proxy3");
	$page .= createForm(array('class'=>'form-horizontal'));
	$page .= addInput('base_url','Server URL Base',$variables);
	$page .= addInput('node','Guifi Proxy node number',$variables);
	$page .= addInput('ldap_main','Main Server Ldap',$variables);
	$page .= addInput('ldap_backup','Main Server Ldap2',$variables);
	$page .= addInput('realm','Proxy Welcome Message',$variables);
	$page .= addInput('manager','Contact email proxy server',$variables);
	$page .= addInput('language','Choosemanager a language for error pages generated',$variables);
	$page .= addInput('cache_size','Disk cache space (MB)',$variables);
	$page .= addInput('cache_mem','Ram cache space (MB) ',$variables);

	$page .= addSubmit(array('label'=>'Executar'));

	return($page);

}
function proxy3_get(){
	global $guifi_proxy3_file, $guifi_proxy3_pkg, $guifi_proxy3_desc, $guifi_proxy3_defconfig;



	if (!isPackageInstall($guifi_proxy3_pkg)){
		$page = package_not_install($guifi_proxy3_pkg,$guifi_proxy3_desc);
	} else {
		$page = proxy3_form($guifi_proxy3_file,$guifi_proxy3_defconfig);
	}
	
	return(array('type' => 'render','page' => $page));

}

function proxy3_post(){

	$page = "";
	$page .=  __FUNCTION__." in ".__FILE__." at ".__LINE__."\n" ;
	
	return(array('type' => 'render','page' => $page));

}

// SNPSERVICES
function snpservices_get(){
	
	$page = "";
	$page .=  __FUNCTION__." in ".__FILE__." at ".__LINE__."\n" ;
	
	return(array('type' => 'render','page' => $page));
}

function snpservices_post(){
	
	$page = "";
	$page .=  __FUNCTION__." in ".__FILE__." at ".__LINE__."\n" ;
	
	return(array('type' => 'render','page' => $page));
}


// DNSSERVICES
function dnsservices_get(){
	
	$page = "";
	$page .=  __FUNCTION__." in ".__FILE__." at ".__LINE__."\n" ;
	
	return(array('type' => 'render','page' => $page));
}

function dnsservices_post(){
	
	$page = "";
	$page .=  __FUNCTION__." in ".__FILE__." at ".__LINE__."\n" ;
	
	return(array('type' => 'render','page' => $page));
}
