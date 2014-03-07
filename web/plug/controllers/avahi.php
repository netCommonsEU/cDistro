<?php
// controllers/avahi.php

function search()
{
	global $staticFile,$staticPath;

	$page = "";

	$page .= ajaxStr('tableAvahi',t("Searching services, please wait!") );
	$page .= "<script>\n";
	$page .= "$('#tableAvahi').load('".$staticFile."/avahi/ajaxsearch');\n";
	$page .= "</script>\n";


	return(array('type'=>'render','page'=>$page));
}

function ajaxsearch()
{
	$aServices = avahi_search(); // This function is in lib/utilio.php

	$page = "";
	$page .= addTableHeader(array(t('Type'),t('Description'),t('Host'),t('IP'),t('Port'),t('Action')), array('class'=>'table table-striped'));
	foreach($aServices as $serv){
		$serv['action'] = checkAvahi($serv['type'],array($serv));
		unset($serv['txt']);
		$page .= addTableRow($serv);
	}
	$page .= addTableFooter();

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
