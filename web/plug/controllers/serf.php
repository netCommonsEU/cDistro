<?php
// controllers/serf.php
$title="serf";
$dirpath="/opt/serf";
$serfproc="serf";
$serfpath=$dirpath."/".$serfproc;
$serf_files_path="$dirpath/machine";
$serfinit="/etc/init.d/serf";
$avahips_config="/etc/avahi-ps.conf";
$avahipsetc_config="/etc/avahi-ps-serf.conf";
$urlpath='/serf';
$serfgeturl='https://raw.githubusercontent.com/Clommunity/package-serf/master/getgithub';
$serfmenu=dirname(__FILE__)."/../menus/serf.lookfor.menu.php";
$avahipsetc_data=array(
					'SERF_RPC_ADDR'=> array('default'=>'127.0.0.1:7373'),
					'SERF_BIND'=> array('default'=>'5000'),
				  'SERF_JOIN'=> array('default'=>'10.139.40.82:5000')
 );

addAvahiFiles($documentPath.$plugs_avahi);

function serf_search(){
	$ret = execute_program("SEARCH_ONLY=serf /usr/sbin/avahi-ps search");
	return($ret['output']);
}
function search()
{
	global $staticFile,$staticPath;

	$page = "";

	$page .= ajaxStr('tableSerf',t("Searching for published services, please wait a moment...") );
	$page .= "<script>\n";
	$page .= "$('#tableSerf').load('".$staticFile."/serf/ajaxsearch',function(){\n";
	$page .= "	$('#tags').tab();\n";	
	$page .= "});\n";
	$page .= "</script>\n";


	return(array('type'=>'render','page'=>$page));
}

function ajaxsearch()
{
	$aServices = serf_search(); 

	$gService = json_decode($aServices[0]);
	$nServices = array();

	foreach($gService as $dates_machine){
		$serv_new['type'] = $dates_machine->s;
		$serv_new['description'] = $dates_machine->d;
		$serv_new['host'] = $dates_machine->m;
		$serv_new['ip'] = $dates_machine->i;
		$serv_new['port'] = $dates_machine->p;
		$serv_new['microcloud'] = $dates_machine->e;
		$serv_new['txt'] = $dates_machine->t;
		$serv_new['action'] = checkAvahi($serv_new['type'],array($serv_new));
		unset($serv_new['txt']);
		$type=$serv_new['type'];
		if (!is_array($nServices[$type])) {
			$nServices[$type]=array();
		}
		$nServices[$type][] = $serv_new;
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
	$page .= hl(t("<a href='https://serfdom.io/' target='_blank'>Serf</a> is a decentralized solution for cluster membership, failure detection, and orchestration. Lightweight and highly available."),4);

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
	if ($var_avahi['DATABASE'] != 'serf') {
		$page .= "<div class='alert alert-error'>".t("<i>serf</i> is not selected")." (" . $var_avahi['DATABASE'] . ")\n";
		$page .= addButton(array('label'=>t("Select $title"),'class'=>'btn', 'href'=>"$urlpath/selectserf", 'divOptions'=>array('class'=>'pull-right')));
		$page .="</div>";
	} else {
		$page .= "<div class='alert alert-success'>".t("</i>serf</i> is selected")."\n";
		$page .= addButton(array('label'=>t("Deselect $title"),'class'=>'btn', 'href'=>"$urlpath/removeserf", 'divOptions'=>array('class'=>'pull-right')));
		$page .="</div>";

	}
	$page .= '</p>';

	$page .= hl(t('Parameters'),3);
	$variable = load_conffile($avahipsetc_config, $avahipsetc_data);
	$page .= createForm(array('class'=>'form-horizontal'));
	$page .= addInput('SERF_RPC_ADDR',t('serf rpc address'),$variable,array('type'=>'text', 'required'=>''),"",t('serf_rpc_addr_help'));
	$page .= addInput('SERF_BIND',t('serf bind port'),$variable,array('type'=>'text', 'required'=>''),"",t('serf_bind_help'));
	$page .= addInput('SERF_JOIN',t('serf peer join'),$variable,array('type'=>'text', 'required'=>''),"",t('serf_join_help'));

	$page .= addSubmit(array('label'=>t('serf_parameters_button')));

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

	setFlash(t('avahipsserf_alert_saved'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));

}

function _isInstalled(){
	global $serfpath;
	return(is_executable($serfpath));
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
function _existSerfConf(){
	global $avahipsetc_config;

	return(file_exists($avahipsetc_config));
}
function _install_menu(){
	global $serfmenu;
	rename($serfmenu.".disable",$serfmenu);
}
function _uninstall_menu(){
	global $serfmenu;
	rename($serfmenu, $serfmenu.".disable");
}

function getprogram(){
	global $serfproc, $staticFile, $serfgeturl, $urlpath;

	$page = "";
        $cmd = "cd /tmp && curl ".$serfgeturl."| sh -";
        $ret = execute_shell($cmd);

	setFlash(t('serf_was_install'),"success");
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
	setFlash(t('serf_was_uninstall'),"success");
	_uninstall_menu();
        return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
}

function _isRun(){
	global $serfproc;
	$ret = execute_shell("pidof -s $serfproc");
	return($ret['return'] !=  0 );
}

function runprogram(){
	global $serfinit, $staticFile, $urlpath;

	$cmd = $serfinit." start";
	execute_program_detached($cmd);

	setFlash(t('serfinit_start'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
	
}

function stopprogram(){
	global $serfinit, $staticFile, $urlpath;

	$cmd = $serfinit." stop";
	execute_program_detached($cmd);

	setFlash(t('serfinit_stop'),"success");
	return(array('type'=> 'redirect', 'url' => $staticFile.$urlpath));
	
}

function removeserf(){
	global $avahips_config,$urlpath,$staticFile;

	$var_avahi = load_conffile($avahips_config);
	$var_avahi['DATABASE'] = 'none';
	write_conffile($avahips_config,$var_avahi,"","",'"');

	return(array('type'=>'redirect','url'=>$staticFile.$urlpath));
		
}

function selectserf(){
	global $avahips_config,$urlpath,$staticFile;


	$var_avahi = load_conffile($avahips_config);
	$var_avahi['DATABASE'] = 'serf';
	write_conffile($avahips_config,$var_avahi,"","",'"');

	return(array('type'=>'redirect','url'=>$staticFile.$urlpath));

}
