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
$etcdgeturl='https://raw.githubusercontent.com/agustim/package-etcd/master/getgithub'

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

	// Reorganizar dades
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
	// Sort
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
	foreach($nServices as $k => $v){	
		$page .= "	<div class='tab-pane";
		if($active == $k) $page .= " active";		
		$page .= "' id='".$k."'>";

		$page .= addTableHeader(array(t('Description'),t('Host'),t('IP'),t('Port'),t('&mu;cloud'),t('Action')), array('class'=>'table table-striped'));
		foreach($v as $serv){
			unset($serv['type']);
			$page .= addTableRow($serv);
		}
		$page .= addTableFooter();
		$page .= " 	</div>";
	}
	$page .= "</div>";
	return(array('type'=>'ajax','page'=>$page));
}

function index()
{
	global $title, $urlpath, $avahips_config, $avahipsetc_config;
	$is_installed=_isInstalled();

	$page=hlc(t($title));
	$var_avahi = load_conffile($avahips_config);
	$page .= hl(t("A highly-available key value store for shared configuration and service discovery"),4);

	$page .= '<p>';	
	$page .= addButton(array('label'=>t("Look for services in etcd"),'class'=>'btn', 'href'=>"$urlpath/search"));
	$page .= '</p>';

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
		$page .= "<div class='alert alert-success'>".t("</i>etcd</i> is selected!")."\n";
		$page .= addButton(array('label'=>t("Deselect $title"),'class'=>'btn', 'href'=>"$urlpath/removeetcd", 'divOptions'=>array('class'=>'pull-right')));
		$page .="</div>";

	}
	$page .= '</p>';

	$page .= hl(t('Parameters'),3);
	$variable = load_conffile($avahipsetc_config, array(
					'SERVER_ETCD_BASE'=> array('default'=>'127.0.0.1:4001'),
					'ETCD_CONNECT'=> array('default'=>'10.139.40.82:7001'),
				  'ETCD_PEER_PORT'=> array('default'=>'7001'),
				  'ETCD_PORT'=> array('default'=>'4001'),
 ));
	$page .= createForm(array('class'=>'form-horizontal'));
	$page .= addInput('SERVER_ETCD_BASE',t('etcd server'),$variable,array('type'=>'text', 'required'=>''),"",t('etcd_server_help'));
	$page .= addInput('ETCD_CONNECT',t('etcd connect to'),$variable,array('type'=>'text', 'required'=>''),"",t('etcd_connect_help'));
	$page .= addInput('ETCD_PEER_PORT',t('etcd peer port'),$variable,array('type'=>'text', 'required'=>''),"",t('etcd_peer_port_help'));
	$page .= addInput('ETCD_PORT',t('etcd connect port'),$variable,array('type'=>'text', 'required'=>''),"",t('etcd_connect_port_help'));

	$page .= addSubmit(array('label'=>t('etcd_parameters_button')));

	return(array('type' => 'render','page' => $page));
}

function index_post(){
	
	global $staticFile,$staticPath;
	global $avahipsetc_config;

	$datesToSave = array();
	foreach ($_POST as $key => $value) {
		$datesToSave[$key] = $value;
	}
	write_conffile($avahipsetc_config,$datesToSave,"","",'"');

	setFlash(t('avahipsetcd_alert_saved'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'etcd'));

}

function _isInstalled(){
	global $etcdpath;
	return(is_executable($etcdpath));
}

function _existEtcdConf(){
	global $avahipsetc_config;

	return(file_exists($avahipsetc_config));
}

function getprogram(){
        global $etcdproc,$staticFile,$etcdgeturl;

        $cmd="cd /tmp && curl ".$etcdgeturl."| sh -";
        execute_program_detached($cmd);

        return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'etcd'));
}

function removeprogram(){

        global $dirpath,$staticFile;

        $cmd="rm -rf ".$dirpath;
        execute_program_detached($cmd);
        return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'etcd'));
}

function _isRun(){
	global $etcdproc;
	$ret = execute_shell("pidof -s $etcdproc");
	return($ret['return'] !=  0 );
}
function runprogram(){
	global $etcdinit, $staticFile;

	$cmd = $etcdinit." start";
	execute_program_detached($cmd);

	setFlash(t('etcdinit_start'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'etcd'));
	
}
function stopprogram(){
	global $etcdinit, $staticFile;

	$cmd = $etcdinit." stop";
	execute_program_detached($cmd);

	setFlash(t('etcdinit_stop'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'etcd'));
	
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
