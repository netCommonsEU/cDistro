<?php
//getinconf.php

$getinconf_file="/tmp/getinconf-client.conf";

function index_get(){

	global $getinconf_file;

	$page = "";

	$variables = load_conffile($getinconf_file);
	$page .= hl("getinconf-client");
	$page .= createForm(array('class'=>'form-horizontal'));
	$page .= addInput('GTC_SERVER_URL','Getinconf Server URL',$variables);
	$page .= addInput('NETWORK_NAME','Network',$variables);
	$page .= addInput('NETWORK_KEY','Network Password',$variables);
	$page .= addInput('INTERNAL_DEV','Device of Community IP',$variables);
	$page .= addSubmit(array('label'=>'Executar'));

	if (isUp($variables['NETWORK_NAME'])){
		$page .= addButton(array('label'=>'Stop','class'=>'btn btn-danger'));
	} else {
		$page .= addButton(array('label'=>'Start','class'=>'btn btn-success'));
	}

	return(array('type' => 'render','page' => $page));
}

function index_post(){
	global $getinconf_file;
	global $staticFile;

	$pre = "#!/bin/sh\n\n# Automatically generate file with cGuifi\n";
	$post = "# POST=665\n";

	//Check info!!!
	$datesToSave = array();
	foreach ($_POST as $key => $value) {
		//check($key,$value);
		$datesToSave[$key] = $value;
	}
	write_conffile($getinconf_file,$datesToSave,$pre,$post);

	setFlash("Save it!","success");
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'getinconf'));
}

function isUp($dev){
//	echo "exec? ip addr show dev $dev";
//	echo passthru('ip addr show dev '.$dev);
}

function upService(){

}


?>