<?php
// controllers/serf.php
$title="serf";
$dirpath="/opt/serf";
$serfproc="serf";
define('SERFPATH',$dirpath."/".$serfproc);
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
$serf_deps = "jq";
$serf_deps_desc = t('lightweight and flexible command-line JSON processor');

if (isset($plugs_avahi)) addAvahiFiles($documentPath.$plugs_avahi);

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
	$page .= "  $('.table-data').DataTable( ";
	$page .= '		{ "language": { "url": "/lang/"+LANG+".table.json"} }';
	$page .= "	);";
	$page .= "});\n";
	$page .= "</script>\n";
	$page .=  addButton(array('label'=>t("scan_quality_of_services"), 'class'=>'btn', 'onclick'=>'$.getJSON("'.$staticFile.'/serf/ajaxquality",function(data){  $.each( data, function( key, val ) { node2color(".node-"+val.node+" td",val.acktime); });  })'));


	return(array('type'=>'render','page'=>$page));
}

function ajaxquality()
{

	$cmd = SERFPATH." reachability -json";
	$ret = execute_program_shell($cmd);

	return(array('type'=>'ajax','page'=>$ret['output']));
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
		$serv_new['node_id']= $dates_machine->node_id;
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

		$services .= addTableHeader(array(t('Description'),t('Host'),t('IP'),t('Port'),t('&mu;cloud'),t('Action')), array('class'=>'table table-striped table-data'));
		foreach($v as $serv){
			unset($serv['type']);
			$node_id=$serv['node_id'];
			unset($serv['node_id']);
			$services .= addTableRow($serv,array('class'=>"node-".$node_id));
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
	
	$page = "";
	$buttons = "";

	$page .= hlc(t("serf_common_title"));
	$page .= hl(t("serf_common_subtitle"),4);
	
	if (!_existAvahiConf()) {
		createDefaultAvahiFile();
	}
	$var_avahi = load_conffile($avahips_config);
	
	$page .= par(t("serf_index_description_1"));
	$page .= par(t("serf_index_description_2"));
	
	$page .= txt(t("serf_index_status"));


	if (!$is_installed) {
		$page .= "<div class='alert alert-error text-center'>".t("serf_alert_not_installed")."</div>\n";
		$buttons .= addButton(array('label'=>t("serf_button_install"),'class'=>'btn btn-success', 'href'=>"$urlpath/getprogram", 'divOptions'=>array('class'=>'btn-group')));
	} else {
		$page .= "<div class='alert alert-success'>".t("serf_alert_not_installed")."</div>";
		$buttons .= addButton(array('label'=>t("serf_button_uninstall"),'class'=>'btn btn-danger', 'href'=>"$urlpath/removeprogram", 'divOptions'=>array('class'=>'btn-group')));
	}
	$page .= '</p>';

	if ($is_installed){
		$page .= '<p>';
		if (_isRun()) {
			$page .= "<div class='alert alert-error'>".t($title."_is_not_running")."\n";
			$page .= addButton(array('label'=>t("start_".$title),'class'=>'btn', 'href'=>"$urlpath/runprogram", 'divOptions'=>array('class'=>'pull-right')));
			$page .="</div>";

		} else {
			$page .= "<div class='alert alert-success'>".t($title."_is_running")."\n";
			$page .= addButton(array('label'=>t("stop_".$title),'class'=>'btn', 'href'=>"$urlpath/stopprogram", 'divOptions'=>array('class'=>'pull-right')));
			$page .="</div>";

		}
		$page .= '</p>';
		
		$page .= '<p>';
	if ($var_avahi['DATABASE'] != 'serf') {
		$page .= "<div class='alert alert-error'>".t($title."_is_not_selected")."\n";
		$page .= addButton(array('label'=>t("select_".$title),'class'=>'btn', 'href'=>"$urlpath/selectserf", 'divOptions'=>array('class'=>'pull-right')));
		$page .="</div>";
	} else {
		$page .= "<div class='alert alert-success'>".t($title."_is_selected")."\n";
		$page .= addButton(array('label'=>t("deselect_".$title),'class'=>'btn', 'href'=>"$urlpath/removeserf", 'divOptions'=>array('class'=>'pull-right')));
		$page .="</div>";

	}
	$page .= '</p>';

$page .= hl(t('Parameters'),3);
	$variable = load_conffile($avahipsetc_config, $avahipsetc_data);

	if (isset($_GET['join']))
		$variable['SERF_JOIN'] = $_GET['join'];

	$page .= createForm(array('class'=>'form-horizontal'));
	$page .= addInput('SERF_RPC_ADDR',t('serf_rpc_address_desc'),$variable,array('type'=>'text', 'required'=>''),"",t('serf_rpc_addr_help'));
	$page .= addInput('SERF_BIND',t('serf_bind_port_desc'),$variable,array('type'=>'text', 'required'=>''),"",t('serf_bind_help'));
	$page .= addInput('SERF_JOIN',t('serf_peer_join_desc'),$variable,array('type'=>'text', 'required'=>''),"",t('serf_join_help'));

	$page .= addSubmit(array('label'=>t('serf_parameters_button')));	
	
	}


	

	$page .= $buttons;

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
	return(is_executable(SERFPATH));
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
	global $serfproc, $staticFile, $serfgeturl, $urlpath,$serf_deps, $serf_deps_desc;

	if (!isPackageInstall($serf_deps)){
		$page = package_not_install($serf_deps,$serf_deps_desc);
		return(array('type'=>'render','page'=> $page ) ) ;
	}
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
