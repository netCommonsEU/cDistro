<?php
//getinconf.php

$getinconf_file="/etc/getinconf-client.conf";

function index_get(){

	global $getinconf_file;

	$page = "";

	$variables = load_conffile($getinconf_file);
	$page .= hl("getinconf-client");
	$page .= createForm(array('class'=>'form-horizontal'));
	$page .= addInput('GTC_SERVER_URL',t('Getinconf Server URL'),$variables);
	$page .= addInput('NETWORK_NAME',t('Network'),$variables);
	$page .= addInput('NETWORK_KEY',t('Network Password'),$variables);
	$page .= addInput('INTERNAL_DEV',t('Device of Community IP'),$variables);
	$page .= addSubmit(array('label'=>t('Execute')));

	$page .= "<br/>";
	if (isUp($variables['NETWORK_NAME'])){
		$page .= "<div class='alert alert-success text-center'>".t("Service UP")."</div>\n";
		$page .= addButton(array('label'=>'Stop','class'=>'btn btn-danger','href'=>'getinconf/downService'));
		$page .= addButton(array('label'=>'View device', 'href' => 'getinconf/viewDevice/'.$variables['NETWORK_NAME']));
	} else {
		$page .= "<div class='alert alert-error text-center'>".t("Service DOWN")."</div>\n";
		$page .= addButton(array('label'=>'Start','class'=>'btn btn-success', 'href'=>'getinconf/upService'));
	}

	return(array('type' => 'render','page' => $page));
}

function index_post(){
	global $getinconf_file;
	global $staticFile;

	$pre = "#!/bin/sh\n\n# Automatically generate file with cGuifi\n";
	$post = "# POST=665\n# GETINCONF_IGNORE=1\n";

	//Check info!!!
	$datesToSave = array();
	foreach ($_POST as $key => $value) {
		//check($key,$value);
		$datesToSave[$key] = $value;
	}
	write_conffile($getinconf_file,$datesToSave,$pre,$post);

	setFlash(t("Save it")."!","success");
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'getinconf'));
}

function upService(){
	global $staticFile;
/*
	No se per què el server es pensa que la pàgina encarà no s'ha acabat de carregar. :-?
	Revisar, per la parada si que funciona.
	Potser l'script a de fer un fork que no depengui del pare.
*/
	execute_bg_shell('getinconf-client install');
	$page = "";
	$page .= "<div class='alert alert-warning'>".t("Now, service is loading. Please come back")." <a href='".$staticFile.'/'.'getinconf'."'>".t("previous page")."</a>.</div>";
	return(array('type'=>'render', 'page'=> $page));
	exit();
}

function downService(){
	global $staticFile;

	$r = execute_program('getinconf-client uninstall');
	if ($r['return'] == 0) {
		setFlash(t('Service DOWN').'!');
	}

	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'getinconf'));	
}

function viewDevice(){
	global $Parameters,$staticFile;

	if (isset($Parameters) && isset($Parameters[0])){
		$r = execute_program_shell('ip addr show dev '.$Parameters[0]);		
		$page = "";
		$page .= "<div class='alert alert-warning'>";
		$page .= "<pre>";
		$page .= $r['output'];
		$page .= "</pre>";
		$page .= t("You can return to the previous")." <a href='".$staticFile.'/'.'getinconf'."'>page</a>.</div>";
		return(array('type'=>'render', 'page'=> $page));
	}
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'getinconf'));		
}

function isUp($dev){
	$r = execute_program('ip addr show dev '.$dev);
	return ($r['return']==0);
}


?>