<?php
//plug/controllers/guifi.php

$guifi_proxy3_file="/etc/guifi-proxy3/config.sh";
$guifi_proxy3_pkg="guifi-proxy3";
$guifi_proxy3_variables = array(
				'base_url'=> array('default'=>'http://www.guifi.net', 
									'desc'=>'Server URL Base', 
									'vdeb'=>'guifi-proxy3/baseurl',
									'kdeb'=>'string'),
				   'node' =>  array('default'=>'0', 
				   					'desc'=>'Guifi Proxy node number', 
				   					'vdeb'=>'guifi-proxy3/node',
									'kdeb'=>'string'),
				   'ldap_main' =>  array('default'=>'ldap.guifi.net', 
				   						'desc'=>'Main Server Ldap', 
				   						'vdeb'=>'guifi-proxy3/ldap_main',
										'kdeb'=>'string'),
				   'ldap_backup' =>  array('default'=>'ldap2.guifi.net', 
				   							'desc'=>'Main Server Ldap2', 
				   							'vdeb'=>'guifi-proxy3/ldap_backup',
											'kdeb'=>'string'),
				   'realm' =>  array('default'=>'Guifi-server Squid proxy-caching web server', 
				   					 'desc'=>'Proxy Welcome Message',
				   					 'vdeb'=>'guifi-proxy3/proxy_name',
									 'kdeb'=>'string'),
				   'manager' =>  array('default'=>'webmaster', 
				   					'desc'=>'Contact email proxy server', 
				   					'vdeb'=>'guifi-proxy3/email',
									'kdeb'=>'string'),
				   'language' =>  array('default'=>'Catalan', 
				   						'desc'=>'Choosemanager a language for error pages generated', 
				   						'vdeb'=>'guifi-proxy3/language',
										'kdeb'=>'string'),
				   'cache_size' =>  array('default'=>'10240', 
				   						'desc'=>'Disk cache space (MB)', 
				   						'vdeb'=>'guifi-proxy3/hd',
										'kdeb'=>'string'),
				   'cache_mem' =>  array('default'=>'100', 
				   						'desc'=>'Ram cache space (MB)', 
				   						'vdeb'=>'guifi-proxy3/ram',
										'kdeb'=>'string')
				   );
$guifi_proxy3_desc = "Guifi.net Proxy federation system.";


// PROXY3
function proxy3_form($file,$options){
	$page = "";

	$variables = load_conffile($file,$options);
	$page .= hl("Guifi Proxy3");
	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($options as $op=>$val){
		$page .= addInput($op,$val['desc'],$variables);
	}

	$page .= addSubmit(array('label'=>'Executar'));

	return($page);

}
function proxy3_get(){
	global $guifi_proxy3_file, $guifi_proxy3_pkg, $guifi_proxy3_desc, $guifi_proxy3_variables, $staticFile;

	$page = proxy3_form($guifi_proxy3_file,$guifi_proxy3_variables);
	if (isPackageInstall($guifi_proxy3_pkg)){ 	
		$page .= addButton(array('label'=>'Uninstall package','class'=>'btn btn-success', 'href'=>$staticFile.'/default/uninstall/'.$guifi_proxy3_pkg));
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
		setFlash("Save it!","success");
		return(array('type' => 'redirect', 'url' => $staticFile.'/guifi/proxy3'));
	}
	return(array('type' => 'render','page' => $page));
}


$snpservices_files="/etc/snpservices/config.php";
$snpservices_pkg="snpservices";
$snpservices_desc="This software provides graph services in the context of the guifi.net";
$snpservices_variables=array('SNPGraphServerId' => array('default' => '0',
												'desc' => 'SNP Graph Server Id',
												'vdeb' => 'snpservices/SNPGraphServerId',
												'kdeb' => 'string')
					    ); 

// SNPSERVICES
function snpservices_form($file,$options){
	$page = "";

	$variables = load_conffile($file,$options);
	$page .= hl("Guifi SNPServices");
	$page .= createForm(array('class'=>'form-horizontal'));

	foreach($options as $op=>$val){
		$page .= addInput($op,$val['desc'],$variables);
	}

	$page .= addSubmit(array('label'=>'Executar'));

	return($page);

}
function snpservices_get(){

	global $snpservices_files, $snpservices_pkg, $snpservices_desc, $snpservices_variables, $staticFile;

	$page = snpservices_form($snpservices_files,$snpservices_variables);
	if (isPackageInstall($snpservices_pkg)){ 	
		$page .= addButton(array('label'=>'Uninstall package','class'=>'btn btn-success', 'href'=>$staticFile.'/default/uninstall/'.$snpservices_pkg));
	}
	return(array('type' => 'render','page' => $page));

}

function snpservices_post(){
	
	global $snpservices_files, $snpservices_pkg, $snpservices_desc, $snpservices_variables, $staticFile;

	$page = "";

	$datesToSave = array();
	foreach ($_POST as $key => $value) {
		$datesToSave[$key] = $value;
	}

	if (!isPackageInstall($snpservices_pkg)){
		if (($define_variables = package_default_variables($datesToSave,$snpservices_variables, $snpservices_pkg)) != ""){
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
		setFlash("Save it!","success");
		return(array('type' => 'redirect', 'url' => $staticFile.'/guifi/proxy3'));
	}
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
