<?php
//tahoe-lafs.php

$tahoeLAFS_conf="/usr/lib/cDistro/tahoe-lafs-manager/tahoe-lafs.conf.default";

function index_get(){

	global $tahoeLAFS_conf;
	$tahoeVariables = load_conffile($tahoeLAFS_conf);

	$installButtonText = t("Install Tahoe-LAFS"); 

	$page = "";

	
	$page .= hlc("Tahoe-LAFS");
	$page .= hl(t("A cloud storage system that distributes your data across multiple servers."),4);
	$page .= par(t("Tahoe-LAFS is a free and open cloud storage system. It distributes your data across multiple servers. Even if some of the servers fail or are taken over by an attacker, the entire filesystem continues to function correctly, preserving your privacy and security."));
		
	$page .= createForm(array('class'=>'form-horizontal'));
	
	if ( introducerCreated($tahoeVariables['TAHOE_HOMEDIR']) )
		if ( introducerStarted($tahoeVariables['TAHOE_HOMEDIR'],$tahoeVariables['TAHOE_PID_FILE']) )
			$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS introducer running")."</div>\n";
		else
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS introducer stopped")."</div>\n";
	
	if ( nodeCreated($tahoeVariables['TAHOE_HOMEDIR']) )
		if ( nodeStarted($tahoeVariables['TAHOE_HOMEDIR'],$tahoeVariables['TAHOE_PID_FILE']) )
			$page .= "<div class='alert alert-success text-center'>".t("Tahoe-LAFS node running")."</div>\n";
		else
			$page .= "<div class='alert alert-warning text-center'>".t("Tahoe-LAFS node stopped")."</div>\n";
	
	if ( !(introducerCreated($tahoeVariables['TAHOE_HOMEDIR']) || nodeCreated($tahoeVariables['TAHOE_HOMEDIR']))) {
		$page .= "<div class='alert alert-error text-center'>".t("Tahoe-LAFS is not installed on this machine")."</div>\n";
		$page .= par(t("To deploy a storage grid with Tahoe-LAFS you need one <strong>introducer</strong> and multiple <strong>nodes</strong> distributed by the network. Click on the button to install Tahoe-LAFS and start creating a storage grid or to join an existing one."));
		$page .= addButton(array('label'=>t("Install Tahoe-LAFS"),'class'=>'btn btn-success', 'href'=>'tahoe-lafs/install'));
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

function introducer(){
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

function nodeCreated($dir){
	if (is_dir("$dir/node"))
		return 1;
	else	
		return 0;
}

function introducerCreated($dir){
	if (is_dir("$dir/introducer"))
		return 1;
	else	
		return 0;
}

function introducerStarted($dir,$pidfile){
	if (is_file("$dir/introducer/$pidfile"))
		return 1;
	else	
		return 0;
}

function nodeStarted($dir,$pidfile){
	if (is_file("$dir/node/$pidfile"))
		return 1;
	else	
		return 0;
}

function nothing(){

		$page = "";
		$page .= "<div class='alert alert-warning'>";
		$page .= t("Nothing to do.");
		$page .= "</div>";
		return(array('type'=>'render', 'page'=> $page));

}

?>