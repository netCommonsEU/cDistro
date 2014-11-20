<?php
// controllers/etcd.php
$title="etcd";
$dirpath="/opt/etcd";
$etcdproc="etcd";
$etcdpath=$dirpath."/".$etcdproc;
$etcd_files_path="$dirpath/machine";
$etcdinit="/etc/init.d/etcd";
$avahips_config="/etc/avahi-ps.conf";
$avahipsetc_config="/etc/avahi-ps-etcd.conf";
$urlpath='/etcd';
$etcdgeturl='https://raw.githubusercontent.com/agustim/package-etcd/master/getgithub';
$etcdmenu=dirname(__FILE__)."/../menus/etcd.lookfor.menu.php";
$avahipsetc_data=array(
					'SERVER_ETCD_BASE'=> array('default'=>'127.0.0.1:4001'),
					'ETCD_CONNECT'=> array('default'=>'10.139.40.82:7001'),
				  'ETCD_PEER_PORT'=> array('default'=>'7001'),
				  'ETCD_PORT'=> array('default'=>'4001'),
 );

addAvahiFiles($documentPath.$plugs_avahi);

function search()
{
	global $staticFile,$staticPath;

	$page = "";

	$page .= ajaxStr('tableEtcd',t("Searching for published services, please wait a moment...") );
	$page .= "<script>\n";
	$page .= "$('#tableEtcd').load('".$staticFile."/etcd/ajaxsearch',function(){\n";
	$page .= "	$('#tags').tab();\n";	
	$page .= "});\n";
	$page .= "</script>\n";


	return(array('type'=>'render','page'=>$page));
}

function ajaxsearch()
{
	$aServices = etcd_search(); // This function is in lib/utilio.php

	$gService = json_decode($aServices[0]);
	$nServices = array();

	foreach($gService->node->nodes as $service){
		$aType = explode("/",$service->key);
		$type = $aType[count($aType)-1];
		if(!isset($nServices[$type])){ $nServices[$type] = array(); }
		foreach($service->nodes as $machines){
			$dates_machine = json_decode($machines->value);
			$serv_new['type'] = $dates_machine->type;
			$serv_new['description'] = $dates_machine->description;
			$serv_new['host'] = $dates_machine->host;
			$serv_new['ip'] = $dates_machine->ip;
			$serv_new['port'] = $dates_machine->port;
			$serv_new['microcloud'] = $dates_machine->microcloud;
			$serv_new['txt'] = $dates_machine->txt;
			$serv_new['action'] = checkAvahi($serv_new['type'],array($serv_new));
			unset($serv_new['txt']);
			$nServices[$type][] = $serv_new;
		}
	}
	ksort($nServices);

	$page = "";
	$page .= "<ul id='tabs' class='nav nav-tabs' data-tabs='tabs'>\n";
	$active = "";
	foreach($nServices as $k => $v){
		if ($active == "") $active = $k;
		$page .= "	<li";
		if($active == $k) $page .= " class='active'";
		$page .= "><a href='#".$k."' data-toggle='tab'>".$k."</a></li>\n";
	}
	$page .= "</ul>\n";
	$page .= "<div id='my-tab-content' class='tab-content'>\n";
	$services = "";
	foreach($nServices as $k => $v){	
		$services .= "	<div class='tab-pane";
		if($active == $k) $services .= " active";		
		$services .= "' id='".$k."'>";

		$services .= addTableHeader(array(t('Description'),t('Host'),t('IP'),t('Port'),t('&mu;cloud'),t('Action')), array('class'=>'table table-striped'));
		foreach($v as $serv){
			unset($serv['type']);
			$services .= addTableRow($serv);
		}
		$services .= addTableFooter();
		$services .= " 	</div>";
	}
	if ($services == "") {
		$services .=t("No services.");
	}
	$page .= $services;
	$page .= "</div>";
	return(array('type'=>'ajax','page'=>$page));
}

function index()
{
	global $title, $urlpath, $avahips_config, $avahipsetc_config,$avahipsetc_data;
	$is_installed=_isInstalled();

	$page=hlc(t($title));
	if (!_existAvahiConf()) {
		createDefaultAvahiFile();
	}
	$var_avahi = load_conffile($avahips_config);
	$page .= hl(t("A highly-available key value store for shared configuration and service discovery"),4);

	$page .= '<p>';
	if (!$is_installed) {
		$page .= "<div class='alert alert-error'>".t("$title is not installed")."\n";
		$page .= addButton(array('label'=>t("Install $title"),'class'=>'btn', 'href'=>"$urlpath/getprogram", 'divOptions'=>array('class'=>'pull-right')));
		$page .="</div>";

	} else {
		$page .= "<div class='alert alert-success'>".t("$title is installed")."\n";
		$page .= addButton(array('label'=>t("Uninstall $title"),'class'=>'btn', 'href'=>"$urlpath/removeprogram", 'divOptions'=>array('class'=>'pull-right')));
		$page .="</div>";
		
	}
	$page .= '</p>';

	if ($is_installed){
		$page .= '<p>';
		if (_isRun()) {
			$page .= "<div class='alert alert-error'>".t("$title is not running")."\n";
			$page .= addButton(array('label'=>t("Start $title"),'class'=>'btn', 'href'=>"$urlpath/runprogram", 'divOptions'=>array('class'=>'pull-right')));
			$page .="</div>";

		} else {
			$page .= "<div class='alert alert-success'>".t("$title is running")."\n";
			$page .= addButton(array('label'=>t("Stop $title"),'class'=>'btn', 'href'=>"$urlpath/stopprogram", 'divOptions'=>array('class'=>'pull-right')));
			$page .="</div>";
		
		}
		$page .= '</p>';
	}

	
	$page .= '<p>';
	if ($var_avahi['DATABASE'] != 'etcd') {
		$page .= "<div class='alert alert-error'>".t("<i>etcd</i> is not selected")." (" . $var_avahi['DATABASE'] . ")\n";
		$page .= addButton(array('label'=>t("Select $title"),'class'=>'btn', 'href'=>"$urlpath/selectetcd", 'divOptions'=>array('class'=>'pull-right')));
		$page .="</div>";
	} else {
		$page .= "<div class='alert alert-success'>".t("</i>etcd</i> is selected")."\n";
		$page .= addButton(array('label'=>t("Deselect $title"),'class'=>'btn', 'href'=>"$urlpath/removeetcd", 'divOptions'=>array('class'=>'pull-right')));
		$page .="</div>";

	}
	$page .= '</p>';

	$page .= hl(t('Parameters'),3);
	$variable = load_conffile($avahipsetc_config, $avahipsetc_data);
	$page .= createForm(array('class'=>'form-horizontal'));
	$page .= addInput('SERVER_ETCD_BASE',t('etcd server'),$variable,array('type'=>'text', 'required'=>''),"",t('etcd_server_help'));
	$page .= addInput('ETCD_CONNECT',t('etcd connect to'),$variable,array('type'=>'text', 'required'=>''),"",t('etcd_connect_help'));
	$page .= addInput('ETCD_PEER_PORT',t('etcd peer port'),$variable,array('type'=>'text', 'required'=>''),"",t('etcd_peer_port_help'));
	$page .= addInput('ETCD_PORT',t('etcd connect port'),$variable,array('type'=>'text', 'required'=>''),"",t('etcd_connect_port_help'));

	$page .= addSubmit(array('label'=>t('etcd_parameters_button')));

	return(array('type' => 'render','page' => $page));
}

function index_post(){
	
	global $staticFile, $staticPath, $urlpath;
	global $avahipsetc_config;

	$datesToSave = array();
	foreach ($_POST as $key => $value) {
		$datesToSave[$key] = $value;
	}
	write_conffile($avahipsetc_config,$datesToSave,"","",'"');

	setFlash(t('avahipsetcd_alert_saved'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));

}

function _isInstalled(){
	global $etcdpath;
	return(is_executable($etcdpath));
}

function _existAvahiConf(){
	global $avahips_config;

	return(file_exists($avahips_config));
}
function _existAvahiConfEtc(){
	global $avahipsetc_config;

	return(file_exists($avahipsetc_config));	 
}
function createDefaultAvahiFile(){
	global $avahips_config;

	write_conffile($avahips_config,array('ERRORS_PLUG'=> "errors",'EXECUTE_IN'=>"memory",'SAVE_SERVICE'=>"none",'DATABASE'=>"none"),"","",'"');
}
function createDefaultAvahiEtcFile(){
	global $avahipsetc_data,$avahipsetc_config;

	$tmparray=array();
	foreach($avahipsetc_data as $k=>$v){
		$tmparray[$k] = $v['default'];
	}
	write_conffile($avahipsetc_config,$tmparray,"","",'"');	 
}
function _existEtcdConf(){
	global $avahipsetc_config;

	return(file_exists($avahipsetc_config));
}
function _install_menu(){
	global $etcdmenu;
	rename($etcdmenu.".disable",$etcdmenu);
}
function _uninstall_menu(){
	global $etcdmenu;
	rename($etcdmenu, $etcdmenu.".disable");
}

function getprogram(){
	global $etcdproc, $staticFile, $etcdgeturl, $urlpath;

	$page = "";
        $cmd = "cd /tmp && curl ".$etcdgeturl."| sh -";
        $ret = execute_shell($cmd);

	setFlash(t('etcd_was_install'),"success");
	_install_menu();
	if (!_existAvahiConfEtc()) {
		createDefaultAvahiEtcFile();
	}
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
	
}

function removeprogram(){

	global $dirpath, $staticFile, $urlpath;

	$cmd="rm -rf ".$dirpath;
	execute_shell($cmd);
	setFlash(t('etcd_was_uninstall'),"success");
	_uninstall_menu();
        return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function _isRun(){
	global $etcdproc;
	$ret = execute_shell("pidof -s $etcdproc");
	return($ret['return'] !=  0 );
}

function runprogram(){
	global $etcdinit, $staticFile, $urlpath;

	$cmd = $etcdinit." start";
	execute_program_detached($cmd);

	setFlash(t('etcdinit_start'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
	
}

function stopprogram(){
	global $etcdinit, $staticFile, $urlpath;

	$cmd = $etcdinit." stop";
	execute_program_detached($cmd);

	setFlash(t('etcdinit_stop'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
	
}

function removeetcd(){
	global $avahips_config,$urlpath,$staticFile;

	$var_avahi = load_conffile($avahips_config);
	$var_avahi['DATABASE'] = 'none';
	write_conffile($avahips_config,$var_avahi,"","",'"');

	return(array('type'=>'redirect','url'=>$staticFile.$urlpath));
		
}

function selectetcd(){
	global $avahips_config,$urlpath,$staticFile;


	$var_avahi = load_conffile($avahips_config);
	$var_avahi['DATABASE'] = 'etcd';
	write_conffile($avahips_config,$var_avahi,"","",'"');

	return(array('type'=>'redirect','url'=>$staticFile.$urlpath));

}
