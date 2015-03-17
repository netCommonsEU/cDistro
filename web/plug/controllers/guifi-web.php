<?php
//plug/controllers/guifi-web.php

$GUIFI_CONF_DIR = "/etc";
$GUIFI_CONF_FILE = "guifi.conf";

function index(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_index_subtitle"),4);

	$page .= par(t("guifi-web_index_description"));

	$page .= txt(t("guifi-web_index_status"));
	if (!file_exists($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) || !filesize($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) ) {
		$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_index_not_initialized")."</div>\n";
		$page .= par(t("guifi-web_index_not_initialized"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_initialize"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/initialize'));
	}

	else {
		$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);
		$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_index_initialized")."</div>\n";

		if ( $GUIFI['NODE_ID'] ) {
			$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_index_nodeid")."</div>\n";

		}

		else {
			$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_index_no_nodeid")."</div>\n";
			$page .= par(t("guifi-web_index_not_registered"));
			$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/register'));
		}

	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));


}

function initialize(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_initialize_subtitle"),4);

	$page .= par(t("guifi-web_initialize_description"));

	$form = createForm(array('class'=>'form-horizontal'));
	$form .= addInput('USERNAME',t("guifi-web_initialize_form_username"),'',array('type'=>'text','required'=>true,'pattern'=>'[A-Za-z0-9_-\s\.]+'),'',t("guifi-web_initialize_form_username_tooltip"));
	$form .= addInput('PASSWORD',t("guifi-web_initialize_form_password"),'',array('type'=>'password', 'required'=>true),'',t("guifi-web_initialize_form_password_tooltip"));
 	$fbuttons = addSubmit(array('label'=>t('guifi-web_button_submit_check'),'class'=>'btn btn-primary'));

	$page .= $form;

	$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
	$buttons .= $fbuttons;

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}

function initialize_post(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_initialize_subtitle"),4);

	if (empty($_POST)) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_post_empty")."</div>\n";
		$page .= par(t("guifi-web_initialize_post_empty"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web/initialize'));
	}

	else if (empty($_POST['USERNAME'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_post_emptyusername")."</div>\n";
		$page .= par(t("guifi-web_initialize_post_emptyusername"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
	}

	else if (empty($_POST['PASSWORD'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_post_emptypassword")."</div>\n";
		$page .= par(t("guifi-web_initialize_post_emptypassword"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
	}

	else {
		$url = "http://test.guifi.net/api?command=guifi.auth.login&username=".$_POST['USERNAME']."&password=".$_POST['PASSWORD'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$json = curl_exec($ch);
		curl_close($ch);

		if (empty($json)) {
			$page .= txt(t("guifi-web_initialize_curl_error"));
			$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_curl_empty")."</div>\n";
			$page .= par(t("guifi-web_initialize_curl_empty"));
			$page .= txt(t("guifi-web_initialize_curl_url"));
			$page .= "<div class='alert alert-info text-center'>"."http://guifi.net/api?command=guifi.auth.login&username=".$_POST['USERNAME']."&password=********</div>\n";
			$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
		}

		else {
			$output = json_decode ($json);

			if ($output->{'code'}->{'code'} == 200 ) {
				$page .= txt(t("guifi-web_initialize_curl_authresult"));
				$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_initialize_curl_ok")."</div>\n";
				$page .= txt(t("guifi-web_initialize_curl_details"));
				$page .= ptxt($json);

				if (!file_exists($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE))
					touch($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE);
				if (fileperms($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE) != "16877" )
					chmod($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, 0644);

				write_conffile($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, array("USERNAME"=>'"'.$_POST['USERNAME'].'"', "TOKEN"=>'"'.$output->{'responses'}->{'authToken'}.'"'));

				if ( !file_exists($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) ) {
					$page .= txt(t("guifi-web_initialize_result"));
					$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_file_error")."</div>\n";
					$page .= par(t("guifi-web_initialize_file_error"));
					$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
				}

				/* This does not work as the config. file is written asynchronously
				else if ( filesize($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE) == 0 ) {
					$page .= txt(t("guifi-web_initialize_result"));
					$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_file_empty")."</div>\n";
					$page .= par(t("guifi-web_initialize_file_empty"));
					$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
				}
				*/

				else {
					$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

					if ( $GUIFI['USERNAME'] != $_POST['USERNAME'] || $GUIFI['TOKEN'] != $output->{'responses'}->{'authToken'} ) {
						$page .= txt(t("guifi-web_initialize_result"));
						$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_file_different")."</div>\n";
						$page .= par(t("guifi-web_initialize_file_different"));
						$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
					}

					else {
						$page .= txt(t("guifi-web_initialize_result"));
						$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_initialize_success")."</div>\n";
						$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
						$buttons .= addButton(array('label'=>t("guifi-web_button_register"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/register'));
					}
				}
			}

			else if ($output->{'code'}->{'code'} == 201 ) {

				if ($output->{'errors'}[0]->{'code'} == 401 ) {
					$page .= txt(t("guifi-web_initialize_curl_authresult"));
					$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_curl_wrong_command")."</div>\n";
					$page .= txt(t("guifi-web_initialize_curl_details"));
					$page .= ptxt($json);
					$page .= par(t("guifi-web_initialize_curl_wrong_command"));
					$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
				}

				else if ($output->{'errors'}[0]->{'code'} == 403 ) {
					$page .= txt(t("guifi-web_initialize_curl_authresult"));
					$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_initialize_curl_wrong_login")."</div>\n";
					$page .= txt(t("guifi-web_initialize_curl_details"));
					$page .= ptxt($json);
					$page .= par(t("guifi-web_initialize_curl_wrong_login"));
					$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
				}

				else {
					$page .= txt(t("guifi-web_initialize_curl_authresult"));
					$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_curl_error")."</div>\n";
					$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
					$page .= txt(t("guifi-web_initialize_curl_details"));
					$page .= ptxt($json);
					$page .= par(t("guifi-web_initialize_curl_error"));
				}
			}

			else {
				$page .= txt(t("guifi-web_initialize_curl_authresult"));
				$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_initialize_curl_error")."</div>\n";
				$buttons .= addButton(array('label'=>t("guifi-web_button_back_credentials"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/initialize'));
				$page .= txt(t("guifi-web_initialize_curl_details"));
				$page .= ptxt($json);
				$page .= par(t("guifi-web_initialize_curl_error"));
			}

		}
	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));


}

function register(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_register_subtitle"),4);

	$page .= par(t("guifi-web_register_description"));

	$form = createForm(array('class'=>'form-horizontal'));
	$form .= addInput('NODE_ID',t("guifi-web_register_form_nodeid"),'',array('type'=>'number','required'=>true,'min'=>1),'',t("guifi-web_register_form_nodeid_tooltip"));

 	$fbuttons = addSubmit(array('label'=>t('guifi-web_button_submit_nodeid'),'class'=>'btn btn-primary'));

	$page .= $form;

	$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
	$buttons .= $fbuttons;

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}


function register_post(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_register_subtitle"),4);

	if (empty($_POST)) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_register_post_empty")."</div>\n";
		$page .= par(t("guifi-web_register_post_empty"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_register"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/register'));
	}

	else if (empty($_POST['NODE_ID'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_register_post_emptynode")."</div>\n";
		$page .= par(t("guifi-web_register_post_emptynode"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_register"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/register'));
	}

	else {

		$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

		$url = "http://test.guifi.net/guifi/cnml/".$_POST['NODE_ID']."/node";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$xml = curl_exec($ch);
		$output = new SimpleXMLElement($xml);
		curl_close($ch);

		if (!isset($output->node)) {
			$page .= txt(t("guifi-web_register_curl_noderesult"));
			$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_register_wrong_nodeid_pre").' '.$_POST['NODE_ID'].' '.t("guifi-web_alert_register_wrong_nodeid_post")."</div>\n";
			$page .= par(t("guifi-web_register_wrong_node"));
			$buttons .= addButton(array('label'=>t("guifi-web_button_back_register"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/register'));
		}

		else {
			$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_register_post_found_pre").' '.$_POST['NODE_ID'].' '.t("guifi-web_alert_register_post_found_post")."</div>\n";
			$page .= txt(t("guifi-web_alert_register_post_nodename"));
			$page .= ptxt($output->node['title']);
			$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web/register'));

			write_conffile($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, add_quotes(array_merge($GUIFI, array("NODEID"=>$_POST['NODE_ID'], "NODENAME"=>$output->node['title']))));

			if (preg_replace('/\s+/', '', $output->node)) {
				$page .= txt(t("guifi-web_alert_register_post_nodedescription"));
				$page .= ptxt($output->node);
			}

			$page .= txt(t("guifi-web_alert_register_post_nodedevices"));
			if ( $output->node['devices'][0] ) {
				$page .= txt(t("guifi-web_alert_register_post_nodedevices"));
				$nodeButtons = "";
				for ($i = 0; $i < $output->node['devices'][0]; $i++) {
					$page .= ptxt( $output->node->device[$i]['title'] );
				}
			}

			else {
				$page .= "<div class='alert alert-warning text-center'>".t("guifi-web_alert_register_post_no_devices")."</div>\n";
				$page .= par(t("guifi-web_register_no_devices"));
				$buttons .= addButton(array('label'=>t("guifi-web_button_register_new"),'class'=>'btn btn-success', 'href'=>$staticFile.'/guifi-web/add'));
			}





			//$page .= ptxt(print_r(htmlspecialchars($xml), true));

		}


	}

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));


}


function add(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_add_subtitle"),4);

	$page .= par(t("guifi-web_add_description"));

	$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

	$form = createForm(array('class'=>'form-horizontal'));
	$form .= addInput('NODEID',t("guifi-web_add_form_nodeid"),$GUIFI['NODEID'],array('type'=>'number','required'=>true,'min'=>1),'readonly',t("guifi-web_arr_form_nodeid_tooltip"));
	$form .= addInput('DEVICENAME',t("guifi-web_add_form_nick"),$GUIFI['NODENAME'].'-'.'Cloudy',array('type'=>'text','required'=>true,'pattern'=>'[A-Za-z0-9_-\s\.]+'),'',t("guifi-web_add_form_nick_tooltip"));
	$form .= addInput('EMAIL',t("guifi-web_add_form_mail"),'a@b.c',array('type'=>'email'),'',t("guifi-web_add_form_mail_tooltip"));
	$form .= addInput('MAC',t("guifi-web_add_form_mac"),strtoupper(getCommunityDevMAC()['output'][0]),array('type'=>'text','required'=>true,'pattern'=>'^([0-9A-F]{2}[:]){5}([0-9A-F]{2})$'),'',t("guifi-web_add_form_mac_tooltip"));
	$form .= addInput('DETAILS',t("guifi-web_add_form_comment"),'A Cloudy instance',array('type'=>'textarea','maxlength'=>50),'',t("guifi-web_add_form_mac_tooltip"));

 	$fbuttons = addSubmit(array('label'=>t('guifi-web_button_submit_add'),'class'=>'btn btn-default'));

	$page .= $form;

	$buttons .= addButton(array('label'=>t("guifi-web_button_back"),'class'=>'btn btn-default', 'href'=>$staticFile.'/guifi-web'));
	$buttons .= $fbuttons;

	$page .= $buttons;
	return(array('type' => 'render','page' => $page));
}




function add_post(){
	global $staticFile, $GUIFI_CONF_DIR, $GUIFI_CONF_FILE;

	$page = "";
	$buttons = "";

	$page .= hlc(t("guifi-web_common_title"));
	$page .= hl(t("guifi-web_add_subtitle"),4);

	if (empty($_POST)) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_add_post_empty")."</div>\n";
		$page .= par(t("guifi-web_register_add_empty"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	else if (empty($_POST['NODEID'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_add_post_emptynode")."</div>\n";
		$page .= par(t("guifi-web_add_post_emptynode"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	else if (empty($_POST['DEVICENAME'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_add_post_nick")."</div>\n";
		$page .= par(t("guifi-web_add_post_nick"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	else if (empty($_POST['EMAIL'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_add_post_mail")."</div>\n";
		$page .= par(t("guifi-web_add_post_mail"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	else if (empty($_POST['MAC'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_add_post_mac")."</div>\n";
		$page .= par(t("guifi-web_add_post_mac"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	else if (empty($_POST['DETAILS'])) {
		$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_add_post_comment")."</div>\n";
		$page .= par(t("guifi-web_add_post_comment"));
		$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
	}

	else {

		$GUIFI=load_conffile($GUIFI_CONF_DIR . '/' . $GUIFI_CONF_FILE);

		$gapi = new guifiAPI( $GUIFI['USERNAME'], '', $GUIFI['TOKEN'] );

		$node_id = $_POST['NODEID'];
		$type = 'cloudy';
		$mac = $_POST['MAC'];

		$device = array();
		$device['nick'] = $_POST['DEVICENAME'];
		$device['notification'] = $_POST['EMAIL'];
		$device['comment'] = $_POST['DETAILS'];

		$device['status'] = 'Testing';

		$added = $gapi->addDevice($node_id, $type, $mac, $device );

		$page .= txt(t("guifi-web_add_result"));
		if ( $added && $added->device_id ) {
			write_conffile($GUIFI_CONF_DIR.'/'.$GUIFI_CONF_FILE, add_quotes(array_merge($GUIFI, array("DEVICEID"=>$added->device_id))));

			$page .= "<div class='alert alert-success text-center'>".t("guifi-web_alert_add_success")."</div>\n";
			$page .= txt(t("guifi-web_add_deviceid"));
			$page .= ptxt($added->device_id);
			$page .= par(t("guifi-web_add_success"));
		}

		else {
			$page .= "<div class='alert alert-error text-center'>".t("guifi-web_alert_add_fail")."</div>\n";
			$page .= par(t("guifi-web_add_fail"));
			$buttons .= addButton(array('label'=>t("guifi-web_button_back_add"),'class'=>'btn btn-warning', 'href'=>$staticFile.'/guifi-web/add'));
		}


	}

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