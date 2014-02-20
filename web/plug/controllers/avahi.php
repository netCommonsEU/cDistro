<?php
// controllers/avahi.php

function search()
{
	global $staticFile,$staticPath;

	$page = "";

	$page = "<div id='tableAvahi'><img src='".$staticPath."images/ajax_loader.gif' width='40px' height='40px' /> ";
	$page .= t("Serach services, please wait!")."</div>";
	$page .= "<script>\n";
	$page .= "$('#tableAvahi').load('".$staticFile."/avahi/ajaxsearch');\n";
	$page .= "</script>\n";


	return(array('type'=>'render','page'=>$page));
}

function ajaxsearch()
{
	$aServices = avahi_search();

	$page = "";
	$page .= addTableHeader(array(t('Type'),t('Description'),t('Host'),t('IP'),t('Port'),t('Action')), array('class'=>'table table-striped'));
	foreach($aServices as $serv){
		$serv['action'] = checkAvahi($serv['type'],array($serv));
		$page .= addTableRow($serv);
	}
	$page .= addTableFooter();

	return(array('type'=>'ajax','page'=>$page));
}