<?php
//plug/controllers/guifi-web.php

function index(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_index_subtitle"),4);

	$page .= par(t("guifi-web_index_description"));

	$page .= txt(t("guifi-web_index_status"));
	if (!file_exists($GUIFI_CONF_DIR) || !file_exists($GUIFI_CONF_DIR.$GUIFI_CONF_FILE) || !filesize($GUIFI_CONF_DIR.$GUIFI_CONF_FILE) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_index_not_registered")."</div>\n";
		$page .= par(t("guifi-web_index_not_registered"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/credentials/selectnode'));
	}

	else {
		$GUIFI=load_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE);

		if ( !isset($GUIFI['USERNAME']) || !isset($GUIFI['TOKEN']) ) {
			$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_index_not_registered")."</div>\n";

			if ( !isset($GUIFI['NODEID']) )
				$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/credentials/selectnode'));
			else if ( !isset($GUIFI['DEVICEID']) )
				$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/credentials/selectdevice'));
			else
				$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/credentials/index'));
		}

		else if ( !isset($GUIFI['NODEID']) ) {
			$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_index_not_registered")."</div>\n";
			$page .= par(t("guifi-web_index_not_registered"));
			$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/selectnode'));
		}

		else if ( !isset($GUIFI['DEVICEID'] )) {
			$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_index_not_registered")."</div>\n";
			$page .= par(t("guifi-web_index_not_registered"));
			$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/selectdevice'));
		}

		else {
			$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_index_nodeid")."</div>\n";
			$nodeinfo = _getNodeInformation($GUIFI['NODEID'], $GUIFI['DEVICEID'], $GUIFI['USERNAME']);
			$buttons .= $nodeinfo['buttons'];
			$page .= $nodeinfo['page'];
			$buttons .= addButton(array('label'=>t("guifi-web_button_change_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/credentials'));
			$buttons .= addButton(array('label'=>t("guifi-web_button_change_device"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/selectdevice'));
		}

	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));

}

function credentials(){
	global $staticPath, $staticFile;

	return(array('type' => 'render','page' => _ask_credentials(array('label'=>t("guifi-web_button_back"),'href'=>$staticFile.'/guifi-web'))));
}

function credentials_post(){
	global $staticPath;

	return(array('type' => 'render','page' => _check_credentials($_POST,
						array('label'=>t("guifi-web_button_back_credentials"), 'href'=>$staticFile.'/guifi-web/credentials'),
						array('label'=>t("guifi-web_button_selectnode_continue"), 'href'=>$staticFile.'/guifi-web/selectnode'),
						array('label'=>t("guifi-web_button_back"), 'href'=>$staticFile.'/guifi-web')
						)));
}

function refresh_credentials(){
	return(array('type' => 'render','page' => _reask_credentials(array('label'=>t("guifi-web_button_back"),'href'=>$staticFile.'/guifi-web'))));
}

function refresh_credentials_post(){
	global $Parameters,$controller,$staticFile;

	$cntrll = (isset($Parameters[0])) ? $Parameters[0] : "";

	return(array('type' => 'render','page' => _recheck_credentials($_POST,
						array('label'=>t("guifi-web_button_back_credentials"), 'href'=>$staticFile.'/guifi-web/refresh_credentials'),
						array('label'=>t("guifi-web_button_selectnode_continue"), 'href'=>$staticFile.'/'.$controller.'/'.$cntrll),
						array('label'=>t("guifi-web_button_back"), 'href'=>$staticFile.'/guifi-web')
						)));
}


function selectnode(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = '';
	$buttons = '';
	$GUIFI = '';

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_selectnode_subtitle"),4);

	$page .= par(t("guifi-web_selectnode_description"));

	if ( file_exists($GUIFI_CONF_DIR) && file_exists($GUIFI_CONF_DIR.$GUIFI_CONF_FILE) && filesize($GUIFI_CONF_DIR.$GUIFI_CONF_FILE) )
		$GUIFI=load_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE);

	$NODEID = '';
	if ( isset($GUIFI['NODEID']) )
		$NODEID = intval($GUIFI['NODEID']);

	$form = createForm(array('class'=>'form-horizontal'));
	$form .= addInput('NODE_ID',t("guifi-web_selectnode_form_nodeid"),$NODEID,array('type'=>'number','required'=>true,'min'=>1),'',t("guifi-web_selectnode_form_nodeid_tooltip"));

 	$fbuttons = addSubmit(array('label'=>t('guifi-web_button_submit_nodeid'),'class'=>'btn btn-primary'));

	$page .= $form;

	$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
	$buttons .= $fbuttons;

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}


function selectnode_post(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE,$GUIFI_WEB;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_selectnode_subtitle"),4);

	if (empty($_POST)) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_selectnode_post_empty")."</div>\n";
		$page .= par(t("guifi-web_selectnode_post_empty"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_selectnode"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/selectnode'));
	}

	else if (empty($_POST['NODE_ID'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_selectnode_post_emptynode")."</div>\n";
		$page .= par(t("guifi-web_selectnode_post_emptynode"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_selectnode"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/selectnode'));
	}

	else {
		$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
		$GUIFI=load_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE);

		$url = $GUIFI_WEB."/guifi/cnml/".$_POST['NODE_ID']."/node";
		echo $url;
		$resposta = _getHttp($url);
		$output = new SimpleXMLElement($resposta);


		if (!isset($output->node)) {
			$page .= txt(t("guifi-web_selectnode_curl_noderesult"));
			$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_selectnode_wrong_nodeid_pre").' '.$_POST['NODE_ID'].' '.t("guifi-web_alert_selectnode_wrong_nodeid_post")."</div>\n";
			$page .= par(t("guifi-web_selectnode_wrong_node"));
			$buttons .= addButton(array('label'=>t("guifi-web_button_back_selectnode"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/selectnode'));
		}

		else {
			$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_selectnode_post_found_pre").' '.$_POST['NODE_ID'].' '.t("guifi-web_alert_selectnode_post_found_post")."</div>\n";
			$page .= txt(t("guifi-web_common_nodename:"));
			$page .= ptxt($output->node['title']);
			if (preg_replace('/\s+/', '', $output->node)) {
				$page .= txt(t("guifi-web_common_nodedescription:"));
				$page .= ptxt($output->node);
			}

			write_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE, add_quotes(array_merge($GUIFI, array("NODEID"=>$_POST['NODE_ID'], "NODENAME"=>$output->node['title']))));

			if ( file_exists($GUIFI_CONF_DIR.$GUIFI_CONF_FILE) ) {
				$GUIFI=load_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE);

				if ( $GUIFI['NODEID'] == $_POST['NODE_ID'] ) {
					$page .= txt(t("guifi-web_alert_selectnode_post_nodetext_pre").' '.$_POST['NODE_ID'].' '.t("guifi-web_alert_selectnode_post_nodetext_post"));
					$buttons .= addButton(array('label'=>t("guifi-web_button_change_nodeid"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/selectnode'));
					$buttons .= addButton(array('label'=>t("guifi-web_button_selectnode_continue"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/selectdevice'));
					$page .= txt("</br>");
				}

				else {
					$page .= txt(t("guifi-web_common_error:"));
					$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_file_error")."</div>\n";
					$page .= par(t("guifi-web_new_cloudy_file_error"));
					$buttons .= addButton(array('label'=>t("guifi-web_button_retry"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/selectnode'));
				}
			}
		}
	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function selectdevice(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE,$GUIFI_WEB;

	$page = '';
	$buttons = '';
	$GUIFI = '';

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_selectdevice_subtitle"),4);

	if ( file_exists($GUIFI_CONF_DIR.$GUIFI_CONF_FILE) )
		$GUIFI=load_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE);

	if ( !isset($GUIFI['NODEID']) ) {
		$page .= txt(t("guifi-web_common_error:"));
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_missing_nodeid")."</div>\n";
		$page .= txt(t("guifi-web_selectdevice_missing_nodeid"));
		$page .= txt("</br>");
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_selectnode"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/selectnode'));
	}

	else {

		$url = $GUIFI_WEB."/guifi/cnml/".intval($GUIFI['NODEID'])."/node";
		$resposta = _getHttp($url);
		$output = new SimpleXMLElement($resposta);

		if (!isset($output->node)) {
			$page .= txt(t("guifi-web_selectdevice_curl_noderesult"));
			$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_selectdevice_wrong_nodeid_pre").' '.$_POST['NODE_ID'].' '.t("guifi-web_alert_selectdevice_wrong_nodeid_post")."</div>\n";
			$page .= par(t("guifi-web_selectdevice_wrong_node"));
			$buttons .= addButton(array('label'=>t("guifi-web_button_back_selectnode"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/selectnode'));
		}

		else {
			$page .= txt(t("guifi-web_selectdevice_description_pre").$GUIFI['NODEID'].t("guifi-web_selectdevice_description_post"));
			$page .= txt(t("guifi-web_common_nodename:"));
			$page .= ptxt($output->node['title']);
			$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
			$buttons .= addButton(array('label'=>t("guifi-web_button_back_selectnode"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/selectnode'));


			if (preg_replace('/\s+/', '', $output->node)) {
				$page .= txt(t("guifi-web_common_nodedescription:"));
				$page .= ptxt($output->node);
			}

			$page .= txt(t("guifi-web_selectdevices_nodedevices"));
			if ( $output->node['devices'][0] ) {
				$page .= addTableHeader(array(t("guifi-web_selectdevice_id"),t("guifi-web_selectdevice_name"),t("guifi-web_selectdevice_actions")));

				$nodeButtons = "";
				$cloudies = 0;
				$rowsDevices = "";
				$rowsCloudies = "";

				foreach($output->node->device as $device){
					$isCloudyAct = FALSE;
					$rowAct = array(
						$device['id'],
						$device['title']
					);
					$cloudButtonsAction = "";
					if ($device['type'] == 'cloudy'){
						$dinterf="";
						$isCloudyAct = TRUE;
						$cloudButtonsAction .= addButton(array(
							'label'=>t("guifi-web_button_selectdevice_this"),
							'class'=>'btn btn-primary',
							'href'=>$staticFile.'/guifi-web/assign/'.$GUIFI['NODEID']."/".$device['id']
						));
					}
					$rowAct[] = addButton(array('label'=>t("guifi-web_button_view"),'class'=>'btn btn-default', 'target'=>'_blank', 'href'=>$GUIFI_WEB.'/'.t("guifi-web_common_lang").'/guifi/device/'.$device['id'])).$cloudButtonsAction;
					if (!$isCloudyAct) {
						$rowsDevices .= addTableRow($rowAct);
					} else {
						$rowsCloudies .= addTableRow($rowAct);
					}
				}

				$page .= $rowsDevices.$rowsCloudies;

				$page .= addTableFooter();

				if ($rowsCloudies) {
					$page .= "<div class='alert alert-info text-center'>".t("guifi-web_alert_selectdevice_cloudies")."</div>\n";
					$page .= par(t("guifi-web_selectdevice_cloudies_pre").' '.$GUIFI['NODEID'].t("guifi-web_selectdevice_cloudies_post"));
				}

				else {
					$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_selectdevice_no_cloudies")."</div>\n";
					$page .= par(t("guifi-web_selectdevice_no_cloudies_pre").' '.$GUIFI['NODEID'].t("guifi-web_selectdevice_no_cloudies_post"));
				}

				$buttons .= addButton(array('label'=>t("guifi-web_button_selectdevice_new"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/newcloudy'));
			}

			else {
				$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_selectdevice_post_no_devices")."</div>\n";
				$page .= par(t("guifi-web_selectdevice_post_no_devices"));
				$buttons .= addButton(array('label'=>t("guifi-web_button_selectdevice_new"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/newcloudy'));
			}
			//$page .= ptxt(print_r(htmlspecialchars($xml), true));
		}
	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}


function assign(){

	global $Parameters, $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE,$GUIFI_WEB,$GUIFI_WEB_API, $GUIFI_WEB_API_AUTH;

	if (count($Parameters) != 2) {
		return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'guifi-web'));
	}
	if (!is_numeric($Parameters[0]) || !is_numeric($Parameters[1])) {
		setFlash(t("The parameters must be numerics."),"error");
		return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'guifi-web'));
	}

	$buttons = '';
	$page = '';

	$url = $GUIFI_WEB."/guifi/cnml/".$Parameters[0]."/node";
	$resposta = _getHttp($url);
	$output = new SimpleXMLElement($resposta);

	foreach($output->node->device as $v){
		if($v['id'] == $Parameters[1]) break;
	}
	if ($v['id'] != $Parameters[1]){
		setFlash(t("This node has not got there device."),"error");
		return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'guifi-web'));
	}
	if ($v['type'] != 'cloudy'){
		setFlash(t("This device not is cloudy type"),"error");
		return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'guifi-web'));
	}

	$GUIFI=load_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE);

	// This device has not IPv4
	foreach($v->interface as $dinterf){
		if($dinterf['ipv4']) break;
	}

	if(isset($dinterf['ipv4'])){
		write_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE, add_quotes(array_merge($GUIFI, array("DEVICEID"=>$Parameters[1]))));
		return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'guifi-web'));
	}

	//$gapi = new guifiAPI( $GUIFI['USERNAME'], '', $GUIFI['TOKEN'], $GUIFI_WEB_API, $GUIFI_WEB_API_AUTH );

	// Assign List nodes...
	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_assign_title"),4);
	$page .= par(t("guifi-web_assign_description"));

	$page .= addTableHeader(array(t("guifi-web_common_deviceid"),t("guifi-web_common_devicename"),t("guifi-web_common_devicetype"),t("guifi-web_common_deviceip"),t("guifi-web_common_actions")));

	foreach($output->node->device as $v){
		if (isset($v->interface) && isset($v->interface['ipv4'])){
			$row = array(
					$v['id'],
					$v['title'],
					$v['type'],
					$v->interface['ipv4'].' ('.$v->interface['mask'].')',
					addButton(array('label'=>t("guifi-web_button_obtain_ip"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/linkcloudy/'.$v['id'].'/'.$Parameters[1]))
			);
			$page .= addTableRow($row);
		}
	}

	$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
	$buttons .= addButton(array('label'=>t("guifi-web_button_change_deviceid"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/selectdevice'));

	$page .= addTableFooter();
	$page .= $buttons;

	return(array('type' => 'render','page' => $page));
}

function linkcloudy(){
	global $Parameters, $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE,$GUIFI_WEB,$GUIFI_WEB_API, $GUIFI_WEB_API_AUTH;

	if (count($Parameters) != 2) {
		return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'guifi-web'));
	}
	if (!is_numeric($Parameters[0]) || !is_numeric($Parameters[1])) {
		setFlash(t("The parameters must be numerics."),"error");
		return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'guifi-web'));
	}

	$page = "";

	$GUIFI=load_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE);
	$gapi = new guifiAPI( $GUIFI['USERNAME'], '', $GUIFI['TOKEN'], $GUIFI_WEB_API, $GUIFI_WEB_API_AUTH );
	$ret = $gapi->addCloudyLink($Parameters[0],$Parameters[1]);

	if ($ret) {
		return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'guifi-web/assign/'.$GUIFI['NODEID'].'/'.$Parameters[1]));
		//$page .= ptxt(print_r($ret,1));
		//return(array('type' => 'render','page' => $page));
	} else {
		$page .= _handle_error(null,$gapi->getErrors(),null,null);
		return(array('type' => 'render','page' => $page));
	}

}
function unlinkcloudy(){
	// Remove this function!!
	global $Parameters, $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE,$GUIFI_WEB,$GUIFI_WEB_API, $GUIFI_WEB_API_AUTH;

	if (count($Parameters) == 1) {
		$GUIFI=load_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE);
		$gapi = new guifiAPI( $GUIFI['USERNAME'], '', $GUIFI['TOKEN'], $GUIFI_WEB_API, $GUIFI_WEB_API_AUTH );
		$ret = $gapi->cloudyUnlink($Parameters[0]);
		setFlash("Remove link cloudy (".$Parameters[0].")");
	}
	return(array('type'=> 'redirect', 'url' => $staticFile.'/'.'guifi-web'));
}

function newcloudy(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_new_cloudy_subtitle"),4);

	$page .= par(t("guifi-web_new_cloudy_description"));

	$GUIFI=load_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE);

	$form = createForm(array('class'=>'form-horizontal'));
	$form .= addInput('NODEID',t("guifi-web_new_cloudy_form_nodeid"),$GUIFI['NODEID'],array('type'=>'number','required'=>true,'min'=>1),' readonly',t("guifi-web_new_cloudy_form_nodeid_tooltip"));
	$form .= addInput('DEVICENAME',t("guifi-web_new_cloudy_form_nick"),$GUIFI['NODENAME'].'-'.'Cloudy',array('type'=>'text','required'=>true,'pattern'=>'[A-Za-z0-9_-]+'),'',t("guifi-web_new_cloudy_form_nick_tooltip"));
	$form .= addInput('EMAIL',t("guifi-web_new_cloudy_form_mail"),'',array('type'=>'email','required'=>true,'placeholder'=>t("guifi-web_new_cloudy_form_mail_placeholder")),'',t("guifi-web_new_cloudy_form_mail_tooltip"));
	$form .= addInput('MAC',t("guifi-web_new_cloudy_form_mac"),strtoupper(getCommunityDevMAC()['output'][0]),array('type'=>'text','required'=>true,'pattern'=>'^([0-9A-F]{2}[:]){5}([0-9A-F]{2})$'),'',t("guifi-web_new_cloudy_form_mac_tooltip"));
	$form .= addInput('DETAILS',t("guifi-web_new_cloudy_form_comment"),'',array('type'=>'textarea','maxlength'=>150,'placeholder'=>t("guifi-web_new_cloudy_form_comment_placeholder")),'',t("guifi-web_new_cloudy_form_comment_tooltip"));

 	$fbuttons = addSubmit(array('label'=>t('guifi-web_button_submit_add'),'class'=>'btn btn-success'));

	$page .= $form;

	$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
	$buttons .= $fbuttons;

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function newcloudy_post(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE, $GUIFI_WEB_API, $GUIFI_WEB_API_AUTH;

	$page = "";
	$fbuttons = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_new_cloudy_subtitle"),4);

	if (empty($_POST)) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_empty")."</div>\n";
		$page .= par(t("guifi-web_register_add_empty"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	else if (empty($_POST['NODEID'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_emptynode")."</div>\n";
		$page .= par(t("guifi-web_new_cloudy_post_emptynode"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	else if (empty($_POST['DEVICENAME'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_nick")."</div>\n";
		$page .= par(t("guifi-web_new_cloudy_post_nick"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	else if (empty($_POST['EMAIL'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_mail")."</div>\n";
		$page .= par(t("guifi-web_new_cloudy_post_mail"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	else if (empty($_POST['MAC'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_mac")."</div>\n";
		$page .= par(t("guifi-web_new_cloudy_post_mac"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	/* //DETAILS are not required
	else if (empty($_POST['DETAILS'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_comment")."</div>\n";
		$page .= par(t("guifi-web_new_cloudy_post_comment"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}
	*/

	else {

		$GUIFI=load_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE);

		$gapi = new guifiAPI( $GUIFI['USERNAME'], '', $GUIFI['TOKEN'], $GUIFI_WEB_API, $GUIFI_WEB_API_AUTH );

		$node_id = $_POST['NODEID'];
		$type = 'cloudy';
		$mac = $_POST['MAC'];

		$device = array();
		$device['nick'] = $_POST['DEVICENAME'];
		$device['notification'] = $_POST['EMAIL'];
		$device['comment'] = $_POST['DETAILS'];

		$device['status'] = 'Testing';

		$added = $gapi->addDevice($node_id, $type, $mac, $device );

		$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));

		if ( $added && $added->device_id ) {
			write_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE, add_quotes(array_merge($GUIFI, array("DEVICEID"=>$added->device_id))));

			$page .= txt(t("guifi-web_new_cloudy_result"));
			$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_new_cloudy_post_success")."</div>\n";
			$page .= txt(t("guifi-web_new_cloudy_deviceid"));
			$page .= ptxt($added->device_id);

			$page .= txt(t("guifi-web_new_cloudy_saving"));
			if ( file_exists($GUIFI_CONF_DIR.$GUIFI_CONF_FILE) ) {
				$GUIFI=load_conffile($GUIFI_CONF_DIR.$GUIFI_CONF_FILE);

				if ( $GUIFI['DEVICEID'] == $added->device_id ) {
					$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_new_cloudy_post_file_correct")."</div>\n";
					$page .= par(t("guifi-web_new_cloudy_success"));
					$buttons .= addButton(array('label'=>t("guifi-web_button_assign_ip"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/assign/'.$GUIFI['NODEID']."/".$GUIFI['DEVICEID']));
				}

				else {
					$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_file_error")."</div>\n";
					$page .= par(t("guifi-web_new_cloudy_file_error"));
					$buttons .= addButton(array('label'=>t("guifi-web_button_back_selectdevice"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/selectdevice'));
				}
			}

			else {
				$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_file_error")."</div>\n";
				$page .= par(t("guifi-web_new_cloudy_file_error"));
				$buttons .= addButton(array('label'=>t("guifi-web_button_back_selectnode"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/selectnode'));
			}
		}

		else {
			$page .= txt(t("guifi-web_new_cloudy_result"));
			$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_fail")."</div>\n";
			$page .= txt(t("guifi-web_new_cloudy_details"));
			$page .= ptxt(print_r($gapi->getErrors(),true));

			switch($gapi->getErrors()[0]->code) {
				case 403:
					$page .= txt(t("guifi-web_common_error:"));
					$page .= "<div class='alert alert-error text-center'>".$_POST['DEVICENAME'].': '.t("guifi-web_alert_new_cloudy_post_already_in_use")."</div>\n";
					$page .= par(t("guifi-web_new_cloudy_post_already_in_use"));

					$fbuttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/newcloudy'));
					break;

				case 502:
					$page .= txt(t("guifi-web_common_error:"));
					$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_new_cloudy_post_expired")."</div>\n";
					$page .= par(t("guifi-web_new_cloudy_credentials_expired"));
					$fbuttons = addButton(array('label'=>t('guifi-web_button_submit_refresh'),'class'=>'btn btn-primary', 'href'=>$staticFile.'/guifi-web/refresh_credentials/newcloudy'));
					break;

				default:
					$page .= par(t("guifi-web_new_cloudy_fail"));
			}
		}
	}

	$buttons .= $fbuttons;
	$page .= $buttons;
	return(array('type' => 'render','page' => $page));

}

function ffileperms(){
	print_r(fileperms("/etc"));
}

function login_post(){
	global $staticFile;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_login_subtitle"),4);




	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function config_dir_exists() {
	global $GUIFI, $GUIFI_CONF_DIR;
	return file_exists($GUIFI_CONF_DIR);
}

function check_credentials() {

}

function _getNodeInformation($nodeid, $deviceid, $username){
	global $GUIFI_WEB;

	$ipbuttons = '';
	$page = '';
	$buttons = '';

	if (t("guifi-web_common_lang") == "guifi-web_common_lang") 
		$url = $GUIFI_WEB.'/en/guifi/cnml/'.$nodeid."/node";
	else 
		$url = $GUIFI_WEB.'/'.t("guifi-web_common_lang")."/guifi/cnml/".$nodeid."/node";

	$resposta = _getHttp($url);
	$output = new SimpleXMLElement($resposta);

	foreach($output->node->device as $k=>$device){
		if ($device['id'] == $deviceid) {
			//$page .= ptxt(print_r($device,1));
			$name = $device['title'];
			$viewlink = $GUIFI_WEB.'/'.t("guifi-web_common_lang")."/guifi/device/".$device['id'];

			$page .= txt(t("guifi-web_common_devicename:"));
			$page .= ptxt($name);

			$strIface = "";
			foreach($device->interface as $iface){
				if ($strIface != ""){
					$strIface .= ", ";
				}
				$strIface .= $iface['ipv4'].' ('.$iface['mask'].')';
			}

			$page .= txt(t("guifi-web_common_deviceip:"));

			if ($strIface != "") {
				$page .= ptxt($strIface);
			}
			else {
				$page .= ptxt(t("guifi-web_common_noip"));
				$ipbuttons .= addButton(array(
							'label'=>t("guifi-web_button_assign_ip"),
							'class'=>'btn btn-success',
							'href'=>$staticFile.'/guifi-web/assign/'.$nodeid."/".$deviceid
				));
			}

			$buttons = addButton(array('label'=>t('guifi-web_button_view_device'),'class'=>'btn btn-default', 'href'=>$viewlink));

			$page .= $ipbuttons;

			$page .= par(' ');
			$page .= txt(t('guifi-web_common_services:'));
			if (isset($device->service)) {
				// Hi ha serveis definits.
				$page .= addTableHeader(array(t('guifi-web_common_serviceid'), t('guifi-web_common_servicename') ,t('guifi-web_common_servicetype'), t('guifi-web_common_actions')));
				foreach($device->service as $service){
					$page .= addTableRow(array($service['id'],$service['title'],$service['type'],''));
				}
				$page .= addTableFooter();
			} else {
				$page .= par(t('guifi_services_did_not_define_in_this_device'));
			}
			break;
		}
	}
	return array('page'=>$page,'buttons'=>$buttons);
}
