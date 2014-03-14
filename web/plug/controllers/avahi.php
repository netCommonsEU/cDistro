<?php
// controllers/avahi.php

function search()
{
	global $staticFile,$staticPath;

	$page = "";

	$page .= ajaxStr('tableAvahi',t("Searching for published services, please wait a moment...") );
	$page .= "<script>\n";
	$page .= "$('#tableAvahi').load('".$staticFile."/avahi/ajaxsearch',function(){\n";
	$page .= "	$('#tags').tab();\n";	
	$page .= "});\n";
	$page .= "</script>\n";


	return(array('type'=>'render','page'=>$page));
}

function ajaxsearch()
{
	$aServices = avahi_search(); // This function is in lib/utilio.php

	// Reorganizar dades

	$nServices = array();
	foreach($aServices as $serv){
		$type = $serv['type'];
		if(!isset($nServices[$type])){ $nServices[$type] = array(); }
		$serv['action'] = checkAvahi($serv['type'],array($serv));
		unset($serv['txt']);
		$nServices[$type][] = $serv;
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

		$page .= addTableHeader(array(t('Description'),t('Host'),t('IP'),t('Port'),t('Action')), array('class'=>'table table-striped'));
		foreach($v as $serv){
			$serv['action'] = checkAvahi($serv['type'],array($serv));
			unset($serv['txt']);
			unset($serv['type']);
			$page .= addTableRow($serv);
		}
		$page .= addTableFooter();
		$page .= " 	</div>";
	}
	$page .= "</div>";
	return(array('type'=>'ajax','page'=>$page));
}

function checkservices()
{
	global $staticFile;

	/* Potser seria més xulo fer un avahi-services list i dels enable, fer un check */
	$ret = execute_program_shell('/usr/sbin/avahi-service list enable');
	$services = $ret['output'];
	$list_services = explode(" ", $services);
	$index = 1;
	foreach ($list_services as $service) {
		if ($service != "\n" && $service != "" && $service != "\t")
		{
			$page .= ajaxStr('service'.$service,t("Loading")." '".$service."' ".t('service'));
		}
	}
	foreach ($list_services as $service) {
		if ($service != "\n" && $service != "" && $service != "\t")
		{
			$page .= "<script>\n";
			$page .= "$('#service".$service."').load('".$staticFile."/avahi/ajaxcheckservice/".$service."');\n";
			$page .= "</script>\n";
		}
	}


	return(array('type'=>'render','page'=>$page));	
}

function ajaxcheckservice()
{
	global $Parameters;

	$ret = execute_program_shell('/usr/sbin/avahi-service start '.$Parameters[0]);

	return (array('type'=>'ajax', 'page'=>$ret['output']));
}
